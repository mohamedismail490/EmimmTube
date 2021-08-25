<template>
    <div class="media my-3">
        <Avatar :username="comment.user && comment.user.name ? comment.user.name : 'Unknown User'" :size="30" class="mr-3" :src="comment.user && comment.user.channel ? comment.user.channel.channel_image : ''"></Avatar>
        <div class="media-body">
            <h6 class="mt-0">{{ comment.user && comment.user.name ? comment.user.name : 'Unknown User'}}</h6>
            <span class="pr-5">{{ comment.body }}</span>

            <div class="d-flex mt-3">
                <votes :initial_entity="comment" entity_type="comment"></votes>
                <button @click.prevent="toggleReplyInput(true)" class="btn btn-sm ml-2 btn-default" style="color: #909090; font-size: medium; margin-top: -5px;">
                    Reply
                </button>
            </div>

            <div :style="`display: ${replyInput}`" class="row mt-2 mb-4 ">
                <div class="col-md-12">
                    <input v-model="newReply" type="text" class="form-control form-control-sm reply-input"
                           placeholder="Add a public reply">
                </div>
                <div class="col-md-12 mt-2">
                    <div class="float-right">
                        <button @click.prevent="toggleReplyInput(false)" class="btn btn-sm btn-outline-dark" type="button">Cancel</button>
                        <button @click.prevent="addReply" class="btn btn-sm btn-outline-primary" type="button">Add Reply</button>
                    </div>
                </div>
            </div>

            <Replies ref='replies' :comment="comment"></Replies>
        </div>
    </div>
</template>

<script>
import Avatar from 'vue-avatar';
import Replies from './replies'
export default {
    name: "Comment",
    props: {
        comment: {
            type: Object,
            required: true,
            default: () => ({})
        },
        reply_input: {
            type: String,
            required: true,
            default: 'none'
        },
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
            replyInput: this.reply_input,
            newReply: ''
        }
    },
    methods: {
        toggleReplyInput(status) {
            this.replyInput = status ? 'block' : 'none';
            if (!status) {
                this.newReply = '';
            }
        },
        addReply() {
            axios.post(`/videos/${this.video.id}/comments`, {
                body: this.newReply,
                comment_id: this.comment.id
            }).then(({ data }) => {
                this.$refs.replies.addReply(data);
                this.toggleReplyInput(false);
            })
            .catch(err => {
                if ((typeof (err.response.status) !== "undefined") && (err.response.status === 401)) {
                    return alert('Please Login to Comment');
                }
                if ((typeof (err.response.status) !== "undefined") && (err.response.status === 422) && (typeof (err.response.data.errors) !== "undefined")) {
                    var error;
                    if (err.response.data.errors.body){
                        error = err.response.data.errors.body[0];
                    }else {
                        error = err.response.data.errors.comment_id[0];
                    }
                    return alert(error);
                }
                return alert("Something wrong happened! please, try again.");
            })
        }
    }
}
</script>
