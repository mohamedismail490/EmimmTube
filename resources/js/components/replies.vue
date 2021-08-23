<template>
    <div>
        <div v-if="replies.data.length > 0">
            <div class="media my-3" v-for="reply in replies.data" :key="reply.id">
                <Avatar :username="reply.user && reply.user.name ? reply.user.name : 'Unknown User'" :size="30" class="mr-3" :src="reply.user && reply.user.channel ? reply.user.channel.channel_image : ''"></Avatar>
                <div class="media-body">
                    <h6 class="mt-0">{{ reply.user && reply.user.name ? reply.user.name : 'Unknown User'}}</h6>
                    <small>{{ reply.body }}</small>

                    <votes :initial_entity="video"></votes>
                </div>
            </div>
        </div>

        <div v-if="comment.replies_count > 0" class="text-center">
            <button type="button" @click.prevent="fetchReplies"
                    v-if="replies.next_page_url"
                    class="btn btn-outline-info btn-sm ml-n5">
                load replies
            </button>
        </div>
    </div>
</template>

<script>
import Avatar from 'vue-avatar';
export default {
    name: "replies",
    props: {
        video: {
            type: Object,
            required: true,
            default: () => ({})
        },
        comment: {
            type: Object,
            required: true,
            default: () => ({})
        }
    },
    components: {
        Avatar
    },
    data() {
        return {
            replies: {
                data: [],
                next_page_url: `/comments/${this.comment.id}/replies`
            }
        }
    },
    methods: {
        fetchReplies() {
            axios.get(this.replies.next_page_url)
                .then(({ data }) => {
                    this.replies = {
                        ...data,
                        data: [
                            ...this.replies.data,
                            ...data.data
                        ]
                    }
                })
        }
    }
}
</script>
