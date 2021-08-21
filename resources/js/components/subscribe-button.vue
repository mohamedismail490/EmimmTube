<template>
    <div>
        <button type="button" @click.prevent="toggleSubscription" class="btn " :class="owner ? 'btn-dark' : (subscribed ? 'btn-danger' : 'btn-success')">
            {{ subBtn }}
        </button>
    </div>
</template>

<script>
import numeral from 'numeral';
export default {
    props: {
        initialChannel: {
            type: Object,
            required: true,
            default: () => ({})
        }
    },
    data() {
        return {
            channel: this.initialChannel
        }
    },
    computed: {
        subscribed() {
            return this.channel.is_subscribed;
        },
        owner() {
            return this.channel.is_owner;
        },
        count() {
            return numeral(this.channel.subscriptions_count).format('0a');
        },
        subscription() {
            return this.channel.user_subscription;
        },
        subBtn() {
            return (this.owner ? '' : (this.subscribed ? 'Unsubscribe ' : 'Subscribe ')) + this.count + (this.owner ? ' Subscribers' : '')
        }
    },
    methods: {
        toggleSubscription() {
            if (this.subscribed) {
                axios.delete(`/channels/${this.channel.id}/subscriptions/${this.subscription.id}`)
                    .then(res => {
                        this.channel = res.data.channel
                    })
                    .catch(err => {
                        if ((typeof (err.response.status) !== "undefined") && (err.response.status === 401)) {
                            return alert('Please Login to Unsubscribe');
                        }
                        return alert('Something Wrong Happened! Please, Try Again.');
                    })
            }else {
                axios.post(`/channels/${this.channel.id}/subscriptions`)
                    .then(res => {
                        if (res.data.status){
                            this.channel = res.data.channel
                        }else {
                            return alert(res.data.message);
                        }
                    })
                    .catch(err => {
                        if ((typeof (err.response.status) !== "undefined") && (err.response.status === 401)) {
                            return alert('Please Login to Subscribe');
                        }
                        return alert('Something Wrong Happened! Please, Try Again.');
                    })
            }
        }
    }
}
</script>
