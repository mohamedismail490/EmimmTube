<template>
    <div class="card">
        <div class="card-header">
            <h4 class="m-0 font-weight-bold">Videos</h4>
        </div>
        <div v-if="videos.data.length > 0" class="row mx-1">
            <Card v-for="video in videos.data" :key="video.id" :channel="channel" :video="video" :in_channel_page="true" class="col-xl-3 col-sm-6 col-lg-4 col-md-6 px-1 py-2"></Card>
        </div>
        <div v-else class="my-3">
            <h6 v-if="showMessage"  class="text-center text-white">This Channel has No Videos Yet!</h6>
        </div>
        <div v-if="videos.data.length > 0" class="text-center my-5">
            <button type="button" @click.prevent="channelVideos" v-if="videos.next_page_url" class="btn btn-outline-success">
                load more Videos
            </button>
        </div>
    </div>
</template>

<script>
import Card from './card'
export default {
    name: "Channel-videos",
    props: {
        channel: {
            type: Object,
            required: true,
            default: () => ({})
        }
    },
    components: {
        Card
    },
    data() {
        return {
            videos: {
                data: []
            }
        }
    },
    methods: {
        channelVideos() {
            const url = this.videos.next_page_url ? this.videos.next_page_url : `/channels/${this.channel.id}`;
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
        this.channelVideos();
    }
}
</script>
