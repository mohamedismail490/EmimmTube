<template>
    <div v-if="videos.data.length > 0" class="col-md-12 mb-n3">
        <div class="card">
            <div class="card-header">
                <h4 class="m-0 font-weight-bold">Recent Uploads</h4>
            </div>
            <div class="card-body">
                <div class="row mx-n3">
                    <Card v-for="video in videos.data" :key="video.id" :channel="video.channel" :video="video" :in_channel_page="false" class="col-xl-3 col-sm-6 col-lg-4 col-md-6 px-1 py-2"></Card>
                </div>
                <div class="text-center mt-5 mb-2">
                    <button type="button" @click.prevent="homepageRecentVideos" v-if="videos.next_page_url" class="btn btn-sm btn-outline-primary">
                        load more
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Card from './card'
export default {
    name: "Homepage",
    components: {
        Card
    },
    data() {
        return {
            videos: {
                data: []
            },
            showMessage: false
        }
    },
    methods: {
        homepageRecentVideos() {
            const url = this.videos.next_page_url ? this.videos.next_page_url : `/videos/recent`;
            axios.post(url)
                .then(({ data }) => {
                    if (data.data.length > 0) {
                        this.videos = {
                            ...data,
                            data: [
                                ...this.videos.data,
                                ...data.data
                            ]
                        }
                    }else {
                        this.showMessage = true;
                    }
                })
                .catch()
        },
    },
    mounted() {
        this.homepageRecentVideos();
    }
}
</script>
