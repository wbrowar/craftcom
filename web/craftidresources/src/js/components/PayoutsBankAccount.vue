<template>

    <div class="card mb-3">
        <div class="card-header">Bank Account</div>
        <div class="card-body">
            <div class="spinner" v-if="loading"></div>

            <template v-else>
                <template v-if="stripeAccount">

                    <div class="row">
                        <dl class="col-md-6">
                            <dt>Stripe Account</dt>
                            <dd><template v-if="stripeAccount.display_name">{{ stripeAccount.display_name }}</template><em v-else class="text-secondary">Not provided</em></dd>
                            <dt>ID</dt>
                            <dd>{{ stripeAccount.id }}</dd>
                            <dt>Payouts enabled</dt>
                            <dd v-if="stripeAccount.payouts_enabled" class="text-success">Yes</dd>
                            <dd v-else class="text-success">No</dd>
                            <dt>Details Submitted</dt>
                            <dd v-if="stripeAccount.details_submitted" class="text-success">Yes</dd>
                            <dd v-else class="text-success">No</dd>
                        </dl>
                        <dl class="col-md-6">
                            <dt>Email</dt>
                            <dd>{{ stripeAccount.email }}</dd>
                            <dt>Business name</dt>
                            <dd><template v-if="stripeAccount.business_name">{{ stripeAccount.business_name }}</template><em v-else class="text-secondary">Not provided</em></dd>
                            <dt>Country</dt>
                            <dd>{{ stripeAccount.country }}</dd>
                            <dt>Statement descriptor</dt>
                            <dd><template v-if="stripeAccount.statement_descriptor">{{ stripeAccount.statement_descriptor }}</template><em v-else class="text-secondary">Not provided</em></dd>
                        </dl>
                    </div>

                    <button type="button" class="btn btn-secondary btn-sm" @click="disconnect()">Remove Stripe account</button> <div v-if="disconnectLoading" class="spinner"></div>

                </template>

                <template v-else>
                    <a class="btn btn-primary" href="https://id.craftcms.dev/index.php/stripe/connect">Connect a Stripe account</a>
                </template>
            </template>
        </div>
    </div>

</template>

<script>
    import { mapGetters } from 'vuex'
    import TextField from '../components/fields/TextField'

    export default {

        components: {
            TextField
        },

        data() {
            return {
                disconnectLoading: false,
            }
        },

        computed: {

            ...mapGetters({
                stripeAccount: 'stripeAccount',
            }),

            loading() {
                return this.$root.stripeAccountLoading;
            }

        },

        methods: {

            disconnect() {
                this.disconnectLoading = true;

                this.$store.dispatch('disconnectStripeAccount').then(() => {
                    this.disconnectLoading = false;
                    this.$root.displayNotice('Stripe account removed.');
                });
            }

        },

    }
</script>
