<template>
    <div>
        <div class="channel-video-card">
            <div class="processing-card card text-light bg-dark mb-n3" style="border: none !important; height: 100% !important;">
                <a v-if="video.percentage === 100" :href="`/channels/${channel.id}/videos/${video.id}`">
                    <div class="video-thumbnail" :style="`background-image: url('${video.thumbnail}')`"
                         style="background-size: cover">
                    </div>
                </a>
                <div v-else class="video-thumbnail" :style="`background-image: url('${video.thumbnail}')`"
                     style="background-size: cover">
                </div>
                <div class="card-body">
                    <div class="d-flex row justify-content-between align-items-center">
                        <div class="col-md-12">
                            <div v-if="!in_channel_page" class="media mt-n2">
                                <a :href="`/channels/${channel.id}`">
                                    <Avatar :username="channel.name" :size="40" class="ml-n3 mr-2"
                                            :src="channel.channel_image"></Avatar>
                                </a>
                                <div class="media-body mt-0">
                                    <h6 class="mt-0 mb-0">
                                        <a v-if="video.percentage === 100" :href="`/channels/${channel.id}/videos/${video.id}`"
                                           style="text-decoration: none; color: #fff; font-size: medium;"
                                           :title="video.title">{{ video.short_title }}</a>
                                        <span v-else style="color: #fff; font-size: medium;"
                                              :title="video.title">{{ video.short_title }}</span>
                                    </h6>
                                    <a class="video-channel release-info" style="font-size: small;"
                                       :href="`/channels/${channel.id}`">
                                        {{ channel.name }}
                                    </a>
                                    <div class="d-flex">
                                        <span class="release-info mt-n1" style="font-size: medium;">{{ viewsCount }} {{ video.views === 1 ? 'view' : 'views' }} . {{ video.created_since }}</span>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <h6 class="mt-n2 mb-0 mx-n3">
                                    <a v-if="video.percentage === 100" :href="`/channels/${channel.id}/videos/${video.id}`"
                                       style="text-decoration: none; color: #fff; font-size: medium;"
                                       :title="video.title">{{ video.title }}</a>
                                    <span v-else style="color: #fff; font-size: medium;"
                                          :title="video.title">{{ video.short_title }}</span>
                                </h6>
                                <span class="release-info mt-0 mx-n3" style="font-size: medium;">{{ viewsCount }} {{ video.views === 1 ? 'view' : 'views' }} . {{ video.created_since }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="video.percentage < 100" class="video-card-overlay">
                <div class="video-overlay-text">Still Processing</div>
            </div>
        </div>
    </div>

</template>

<script>
import Avatar from 'vue-avatar';
import numeral from 'numeral';

export default {
    name: "Card",
    props: {
        channel: {
            type: Object,
            required: true,
            default: () => ({})
        },
        video: {
            type: Object,
            required: true,
            default: () => ({})
        },
        in_channel_page: {
            type: Boolean,
            required: true,
            default: false
        }
    },
    components: {
        Avatar
    },
    computed: {
        viewsCount() {
            return numeral(this.video.views).format('0a')
        }
    }
}
</script>
