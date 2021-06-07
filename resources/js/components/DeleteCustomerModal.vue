<template>
    <b-modal
        id="delete-customer-modal"
        title="Delete Customer Contact"
        hide-footer
    >
        <p class="lead text-center">
            Are you sure to delete {{ customerData.name }}'s contact?
        </p>
        <b-form @submit="onSubmit">
            <b-button type="submit" variant="danger" class="float-right"
                ><b-spinner small v-if="loading"></b-spinner
                ><span v-else>Delete</span>
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
            toastCount: 0,
            emits: ["reload-table"]
        };
    },

    methods: {
        async onSubmit(event) {
            event.preventDefault();

            this.loading = true;

            try {
                const response = await this.axios.delete(
                    "/admin/customer/" + this.customerData.id
                );

                const data = response.data;

                this.loading = false;
                this.$bvModal.hide("delete-customer-modal");

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
                this.$bvModal.hide("delete-customer-modal");

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
