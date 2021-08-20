Vue.component('channel-uploads', {
    props: {
        channel: {
            type: Object,
            required: true,
            default: () => ({})
        }
    },
    data() {
        return {
            selected: false,
            videos: [],
            progress: {},
            uploads: [],
            intervals: {}
        }
    },
    methods: {
        upload() {
            // Validate all uploaded videos from the Frontend
            var isVideo = true;
            var videosArray = Array.from(this.$refs.videos.files);
            videosArray.forEach(file => {
                var type = file.type;
                const mimeArr = type.split("/");
                if (mimeArr[0] !== "video"){
                    isVideo = false
                }
            })
            if (!isVideo){
                return alert("All Uploaded Files must be a valid Video Files!")
            }

            // Make a request to validate all uploaded videos before any uploading from backend
            var formData = new FormData();
            if (videosArray.length > 0) {
                for (let i = 0; i < videosArray.length; i++) {
                    formData.append('videos[]', videosArray[i]);
                }
            }
            return axios.post(`/channels/${this.channel.id}/videos/validate`, formData)
                .then(() => {
                    // all Videos is validated and the upload process begins
                    this.selected = true;
                    this.videos = videosArray;
                    const uploaders = this.videos.map(video => {
                        const form = new FormData();
                        this.progress[video.name] = 0;

                        form.append('video', video);
                        form.append('title', video.name);

                        return axios.post(`/channels/${this.channel.id}/videos/upload`, form, {
                            onUploadProgress: (event) => {
                                this.progress[video.name] = Math.ceil(((event.loaded / event.total) * 100));
                                this.$forceUpdate();
                            }
                        }).then(({ data }) => {
                            this.uploads = [...this.uploads, data];
                        })
                    });

                    // tracking the video processing progress.
                    axios.all(uploaders)
                        .then(() => {
                            this.videos = this.uploads;
                            this.videos.forEach(video => {
                                this.intervals[video.id] = setInterval(() => {
                                    axios.get(`/channels/${this.channel.id}/videos/${video.id}`)
                                        .then(({ data }) => {
                                            if (data.percentage === 100) {
                                                clearInterval(this.intervals[video.id]);
                                            }
                                            this.videos = this.videos.map(v => {
                                                if (v.id === data.id) {
                                                    return data;
                                                }
                                                return v;
                                            });
                                        })
                                }, 3000);
                            })
                        });
                })
                .catch(err => {
                    if ((typeof (err.response.status) !== "undefined") && (err.response.status === 422) && (typeof (err.response.data.errors) !== "undefined")) {
                        for (let i = 0; i < videosArray.length; i++) {
                            if (err.response.data.errors["videos."+i]){
                                this.selected = false;
                                return alert("All Uploaded Files must be a valid Video Files!");
                            }
                        }
                    }else {
                        return alert("Something wrong happened! please, try again.");
                    }
                })
        }
    }
});
