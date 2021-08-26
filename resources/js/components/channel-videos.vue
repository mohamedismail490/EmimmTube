<template>
    <div class="card">
        <div class="card-header">
            <h4 class="m-0 font-weight-bold">Videos</h4>
        </div>
<!--        <div class="table-responsive">-->
<!--            <table class="table align-items-center table-flush table-dark">-->
<!--                <thead>-->
<!--                <tr>-->
<!--                    <th>Image</th>-->
<!--                    <th>Title</th>-->
<!--                    <th>Views</th>-->
<!--                    <th v-if="channel.is_owner" >Status</th>-->
<!--                    <th></th>-->
<!--                </tr>-->
<!--                </thead>-->
<!--                <tbody v-if="videos.data.length > 0">-->
<!--                <tr v-for="video in videos.data" :key="video.id">-->
<!--                    <td>-->
<!--                        <img :src="video.thumbnail" width="60" class="rounded" alt="">-->
<!--                    </td>-->
<!--                    <td>{{ video.title }}</td>-->
<!--                    <td>{{ video.views }}</td>-->
<!--                    <td v-if="channel.is_owner" :class="video.percentage === 100 ? 'text-success' : 'text-warning'">-->
<!--                        {{ video.percentage === 100 ? 'Live' : 'Processing' }}-->
<!--                    </td>-->
<!--                    <td>-->
<!--                        <a v-if="video.percentage === 100" :href="`/channels/${channel.id}/videos/${video.id}`"-->
<!--                           target="_blank" class="btn btn-outline-info">-->
<!--                            View-->
<!--                        </a>-->
<!--                    </td>-->
<!--                </tr>-->
<!--                </tbody>-->
<!--                <tbody v-else>-->
<!--                <tr>-->
<!--                    <td colspan="5" class="text-center">-->
<!--                        This Channel has No Videos Yet!-->
<!--                    </td>-->
<!--                </tr>-->
<!--                </tbody>-->
<!--            </table>-->
<!--        </div>-->
<!--        <div class="card-footer"></div>-->

        <div v-if="videos.data.length > 0" class="row mx-1">
            <Card v-for="video in videos.data" :key="video.id" :channel="channel" :video="video" :in_channel_page="true" class="col-xl-3 col-sm-6 col-lg-4 col-md-6 px-1 py-2"></Card>
        </div>
        <div v-else class="my-3">
            <h6 class="text-center text-white">This Channel has No Videos Yet!</h6>
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
                    this.videos = {
                        ...data,
                        data: [
                            ...this.videos.data,
                            ...data.data
                        ]
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
