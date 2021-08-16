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
            selected: false
        }
    },
    methods: {
        upload() {
            this.selected = true;

            const videos = this.$refs.videos.files;


        }
    }
});
