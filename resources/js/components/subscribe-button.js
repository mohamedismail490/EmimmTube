import numeral from 'numeral';
Vue.component('subscribe-button', {
    props: {
        channel: {
          type: Object,
          required: true,
          default: () => ({})
        },
        initialSubscriptions: {
            type: Array,
            required: true,
            default: () => []
        }
    },
    data() {
        return {
            subscriptions: this.initialSubscriptions
        }
    },
    computed: {
        subscribed() {
            if (!__auth() || (this.channel.user_id === __auth().id)) return false;
            return !!this.subscription
        },
        owner() {
            return !!(__auth() && (this.channel.user_id === __auth().id));
        },
        count() {
            return numeral(this.subscriptions.length).format('0a');
        },
        subscription() {
            if (!__auth()) return null;
            return this.subscriptions.find(subscription => subscription.user_id === __auth().id)
        }
    },
    methods: {
        toggleSubscription() {
            if (!__auth()) {
                return alert('Please Login to Subscribe');
            }

            if (this.owner) {
                return alert('You can\'t Subscribe to Your Channel');
            }

            if (this.subscribed) {
                axios.delete(`/channels/${this.channel.id}/subscriptions/${this.subscription.id}`)
                    .then(() => {
                        this.subscriptions = this.subscriptions.filter(sub => sub.id !== this.subscription.id)
                    });
            }else {
                axios.post(`/channels/${this.channel.id}/subscriptions`)
                    .then(res => {
                        this.subscriptions = [
                            ...this.subscriptions,
                            res.data
                        ]
                    });
            }
        }
    }
})
