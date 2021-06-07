<template>
    <b-modal id="follow-up-modal" title="Follow Up Customer" hide-footer>
        <b-form @submit="onSubmit">
            <b-form-group id="input-group-2" label="" label-for="title">
                <b-form-input
                    id="title"
                    v-model="form.title"
                    placeholder="Enter title..."
                    required
                ></b-form-input>
            </b-form-group>

            <b-form-textarea
                id="message"
                v-model="form.message"
                placeholder="Enter message..."
                rows="3"
                max-rows="6"
                class="mb-3"
            ></b-form-textarea>

            <b-button type="submit" variant="primary" class="float-right"
                ><b-spinner small v-if="loading"></b-spinner
                ><span v-else>Submit</span>
            </b-button>
        </b-form>
    </b-modal>
</template>

<script>
export default {
    props: ["customerData"],
    data() {
        return {
            loading: false,
            form: {
                title: "",
                message: ""
            },
            toastCount: 0,
            emits: ["reload-table"]
        };
    },

    methods: {
        async onSubmit(event) {
            event.preventDefault();

            this.loading = true;

            const formData = {
                title: this.form.title,
                message: this.form.message,
                customer_id: this.customerData.id
            };

            try {
                const response = await this.axios.post(
                    "/agent/followup/",
                    formData
                );

                const data = response.data;

                this.loading = false;
                this.$bvModal.hide("follow-up-modal");

                // Show Toast
                this.toastCount++;
                this.$bvToast.toast(data.message, {
                    title: "Success",
                    autoHideDelay: 5000,
                    variant: "success"
                });

                // Refresh table
                this.$emit("reload-table");
            } catch (errors) {
                this.loading = false;
                this.$bvModal.hide("follow-up-modal");

                // Show Toast
                this.toastCount++;
                this.$bvToast.toast(errors.response.data.message, {
                    title: "Failed",
                    autoHideDelay: 5000,
                    variant: "danger"
                });

                console.error(errors.response.data);
            }
        }
    }
};
</script>
