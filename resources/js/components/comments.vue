<template>
    <div class="card mt-2 video-card" style="width: 92% !important; border: none !important; margin-left: auto !important; margin-right: auto !important;">
        <div class="form-inline my-4">
            <div class="input-group" style="width: 100% !important; margin-left: auto !important; margin-right: auto !important;">
                <input type="text" class="form-control form-control-sm" placeholder="add public comment" aria-describedby="button-addon1">
                <div class="input-group-prepend">
                    <button class="btn btn-sm btn-outline-primary" type="button">Add comment</button>
                </div>
            </div>
        </div>

        <div class="card video-card" style="width: 100% !important; border: none !important; margin-left: auto !important; margin-right: auto !important;">
            <div v-if="comments.data.length > 0">
                <div class="media my-3" v-for="comment in comments.data" :key="comment.id">
                    <Avatar :username="comment.user && comment.user.name ? comment.user.name : 'Unknown User'" :size="30" class="mr-3" :src="comment.user && comment.user.channel ? comment.user.channel.channel_image : ''"></Avatar>
                    <div class="media-body">
                        <h6 class="mt-0">{{ comment.user && comment.user.name ? comment.user.name : 'Unknown User'}}</h6>
                        <span>{{ comment.body }}</span>

                        <div class="d-flex mt-3">
                            <votes :initial_entity="comment" entity_type="comment"></votes>
                            <button class="btn btn-sm ml-2 btn-default" style="color: #909090; font-size: medium; margin-top: -6px;">
                                Reply
                            </button>
                        </div>

                        <div class="form-inline my-4">
                            <div class="input-group" style="width: 100% !important; margin-left: auto !important; margin-right: auto !important;">
                                <input type="text" class="form-control form-control-sm" placeholder="add public reply" aria-describedby="button-addon1">
                                <div class="input-group-prepend">
                                    <button class="btn btn-sm btn-outline-primary" type="button">Add reply</button>
                                </div>
                            </div>
                        </div>

                        <Replies :comment="comment"></Replies>
                    </div>
                </div>
            </div>
            <span v-else class="text-center mt-3">no comments added for this video yet!</span>
        </div>

        <div v-if="comments.data.length > 0" class="text-center">
            <button type="button" @click.prevent="fetchComments" v-if="comments.next_page_url" class="btn btn-outline-success">
                load more comments
            </button>
            <span v-else>no more comments to show...</span>
        </div>
    </div>
</template>

<script>
import Avatar from 'vue-avatar';
import Replies from './replies'
export default {
    name: "comments",
    props: {
        video: {
            type: Object,
            required: true,
            default: () => ({})
        }
    },
    components: {
        Avatar,
        Replies
    },
    data() {
        return {
            comments: {
                data: []
            }
        }
    },
    methods: {
        fetchComments() {
            const url = this.comments.next_page_url ? this.comments.next_page_url : `/videos/${this.video.id}/comments`;
            axios.get(url)
                .then(({ data }) => {
                    this.comments = {
                        ...data,
                        data: [
                            ...this.comments.data,
                            ...data.data
                        ]
                    }
                })
        }
    },
    mounted() {
        this.fetchComments();
    }
}
</script>
