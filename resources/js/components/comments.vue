<template>
    <div class="card mt-2 mb-5 video-card" style="width: 92% !important; border: none !important; margin-left: auto !important; margin-right: auto !important;">
        <div class="row my-4">
            <div class="col-md-12">
                <input @focus.prevent="toggleCommentInput(true)" v-model="newComment" type="text" class="form-control form-control-sm reply-input"
                       placeholder="Add a public comment">
            </div>
            <div v-if="comment_input" class="col-md-12 mt-2">
                <div class="float-right">
                    <button @click.prevent="toggleCommentInput(false)" class="btn btn-sm btn-outline-dark" type="button">Cancel</button>
                    <button @click.prevent="addComment" class="btn btn-sm btn-outline-primary" type="button">Add Comment</button>
                </div>
            </div>
        </div>

        <div class="card video-card" style="width: 100% !important; border: none !important; margin-left: auto !important; margin-right: auto !important;">
            <div v-if="comments.data.length > 0">
                <Comment v-for="comment in comments.data" :key="comment.id" :comment="comment" :reply_input="reply_input[comment.id]" :video="video"></Comment>
            </div>
            <span v-else class="text-center mt-3">No comments added for this video yet!</span>
        </div>

        <div v-if="comments.data.length > 0" class="text-center">
            <button type="button" @click.prevent="fetchComments" v-if="comments.next_page_url" class="btn btn-outline-success">
                load more comments
            </button>
<!--            <span v-else>No more comments to show...</span>-->
        </div>
    </div>
</template>

<script>
import Comment from './comment';
export default {
    name: "Comments",
    props: {
        video: {
            type: Object,
            required: true,
            default: () => ({})
        }
    },
    components: {
        Comment
    },
    data() {
        return {
            comments: {
                data: []
            },
            comment_input: false,
            reply_input: {},
            newComment: ''
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
            if (!status) {
                this.newComment = '';
            }
        },
        addComment() {
            axios.post(`/videos/${this.video.id}/comments`, {
                body: this.newComment
            }).then(({ data }) => {
                this.comments = {
                    ...this.comments,
                    data: [
                        data,
                        ...this.comments.data
                    ]
                }
                this.toggleCommentInput(false);
                this.reply_input[data.id] = 'none';
                if ((this.comments.data.length > 10) && this.comments.next_page_url) {
                    this.comments.data = this.comments.data.filter(c => {
                        return c.id !== this.comments.data[parseInt((this.comments.data.length - 1))].id
                    });
                }
            })
            .catch(err => {
                if ((typeof (err.response.status) !== "undefined") && (err.response.status === 401)) {
                    return alert('Please Login to Comment');
                }
                if ((typeof (err.response.status) !== "undefined") && (err.response.status === 422) && (typeof (err.response.data.errors) !== "undefined")) {
                    return alert(err.response.data.errors.body[0]);
                }
                return alert("Something wrong happened! please, try again.");
            })
        }
    },
    mounted() {
        this.fetchComments();
    }
}
</script>
