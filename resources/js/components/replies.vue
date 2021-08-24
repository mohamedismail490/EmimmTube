<template>
    <div>
        <div v-if="comment.replies_count > 0">
            <button type="button" @click.prevent="fetchRepliesOnce('show')"
                    v-if="showAllReplies[comment.id]"
                    class="btn btn-outline-info mt-2 mb-3 load-replies">
                View {{ repliesCount+' '+(comment.replies_count > 1 ? 'replies' : 'reply') }}
            </button>
            <button type="button" @click.prevent="fetchRepliesOnce('hide')"
                    v-if="hideAllReplies[comment.id]"
                    class="btn btn-outline-info mt-2 mb-3 load-replies">
                Hide {{ repliesCount+' '+(comment.replies_count > 1 ? 'replies' : 'reply') }}
            </button>
        </div>
        <div v-if="(replies.data.length > 0) && hideAllReplies[comment.id]">
            <div class="media my-3" v-for="reply in replies.data" :key="reply.id">
                <Avatar :username="reply.user && reply.user.name ? reply.user.name : 'Unknown User'" :size="30" class="mr-3" :src="reply.user && reply.user.channel ? reply.user.channel.channel_image : ''"></Avatar>
                <div class="media-body">
                    <h6 class="mt-0">{{ reply.user && reply.user.name ? reply.user.name : 'Unknown User'}}</h6>
                    <span>{{ reply.body }}</span>
                    <div class="mt-3">
                        <votes :initial_entity="reply" entity_type="comment"></votes>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="(comment.replies_count > 0) && hideAllReplies[comment.id]">
            <button type="button" @click.prevent="fetchReplies"
                    v-if="replies.next_page_url"
                    class="btn btn-outline-info mt-2 mb-3 load-replies">
                Show more replies
            </button>
        </div>
    </div>
</template>

<script>
import Avatar from 'vue-avatar';
import numeral from 'numeral';
export default {
    name: "replies",
    props: {
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
            },
            repliesCount: numeral(this.comment.replies_count).format('0a'),
            repliesShowedForTheFirstTime: {},
            showAllReplies: {},
            hideAllReplies: {},
        }
    },
    methods: {
        fetchReplies(fetchOnce = false) {
            axios.get(this.replies.next_page_url)
                .then(({ data }) => {
                    this.replies = {
                        ...data,
                        data: [
                            ...this.replies.data,
                            ...data.data
                        ]
                    }
                    if (fetchOnce) {
                        this.repliesShowedForTheFirstTime[this.comment.id] = true;
                        this.showAllReplies[this.comment.id] = false;
                        this.hideAllReplies[this.comment.id] = true;
                    }
                })
        },
        fetchRepliesOnce(type) {
            if ((!this.repliesShowedForTheFirstTime[this.comment.id]) && (type === 'show')) {
                this.fetchReplies(true);
            }else {
                if (type === 'show') {
                    this.showAllReplies[this.comment.id] = false;
                    this.hideAllReplies[this.comment.id] = true;
                }else {
                    this.showAllReplies[this.comment.id] = true;
                    this.hideAllReplies[this.comment.id] = false;
                }
            }
            this.$forceUpdate();
        }
    },
    mounted() {
        this.repliesShowedForTheFirstTime[this.comment.id] = false;
        this.showAllReplies[this.comment.id] = true;
        this.hideAllReplies[this.comment.id] = false;
        this.$forceUpdate();
    }
}
</script>
