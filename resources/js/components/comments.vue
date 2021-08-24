<template>
    <div class="card mt-2 mb-5 video-card" style="width: 92% !important; border: none !important; margin-left: auto !important; margin-right: auto !important;">
        <div class="row my-4">
            <div class="col-md-12">
                <input @focus.prevent="toggleCommentInput(true)" type="text" class="form-control form-control-sm reply-input"
                       placeholder="Add a public comment">
            </div>
            <div v-if="comment_input" class="col-md-12 mt-2">
                <div class="float-right">
                    <button @click.prevent="toggleCommentInput(false)" class="btn btn-sm btn-outline-dark" type="button">Cancel</button>
                    <button class="btn btn-sm btn-outline-primary" type="button">Add Comment</button>
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
                            <button @click.prevent="toggleReplyInput(comment.id,true)" class="btn btn-sm ml-2 btn-default" style="color: #909090; font-size: medium; margin-top: -5px;">
                                Reply
                            </button>
                        </div>

                        <div :style="`display: ${reply_input[comment.id]}`" class="row mt-2 mb-4 ">
                            <div class="col-md-12">
                                <input type="text" class="form-control form-control-sm reply-input"
                                       placeholder="Add a public reply">
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="float-right">
                                    <button @click.prevent="toggleReplyInput(comment.id,false)" class="btn btn-sm btn-outline-dark" type="button">Cancel</button>
                                    <button class="btn btn-sm btn-outline-primary" type="button">Add Reply</button>
                                </div>
                            </div>
                        </div>

                        <Replies :comment="comment"></Replies>
                    </div>
                </div>
            </div>
            <span v-else class="text-center mt-3">No comments added for this video yet!</span>
        </div>

        <div v-if="comments.data.length > 0" class="text-center">
            <button type="button" @click.prevent="fetchComments" v-if="comments.next_page_url" class="btn btn-outline-success">
                load more comments
            </button>
            <span v-else>No more comments to show...</span>
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
            },
            comment_input: false,
            reply_input: {},
        }
    },
    methods: {
        fetchComments() {
            const url = this.comments.next_page_url ? this.comments.next_page_url : `/videos/${this.video.id}/comments`;
            axios.get(url)
                .then(({ data }) => {
                    data.data.forEach(c => {
                        this.reply_input[c.id] = 'none';
                    })
                    this.comments = {
                        ...data,
                        data: [
                            ...this.comments.data,
                            ...data.data
                        ]
                    }
                })
        },
        toggleCommentInput(status) {
            this.comment_input = status
        },
        toggleReplyInput(commentId,status) {
            this.reply_input[commentId] = status ? 'block' : 'none';
            this.$forceUpdate();
        }
    },
    mounted() {
        this.fetchComments();
    }
}
</script>
