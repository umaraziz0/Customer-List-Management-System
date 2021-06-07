<template>
    <b-modal
        id="update-status-modal"
        title="Manually Update Customer Status"
        hide-footer
    >
        <b-form @submit="onSubmit">
            <v-select
                v-model="customerData.status"
                :options="options"
                class="mb-3"
            ></v-select>
            <b-button type="submit" variant="primary" class="float-right"
                ><b-spinner small v-if="loading"></b-spinner
                ><span v-else>Update</span>
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
            options: ["Uncontacted", "Pending", "Qualified", "Lost"],
            emits: ["reload-table"]
        };
    },

    methods: {
        async onSubmit(event) {
            event.preventDefault();

            this.loading = true;

            try {
                const response = await this.axios.put(
                    "/agent/updatestatus/" + this.customerData.id,
                    {
                        status: this.customerData.status
                    }
                );

                const data = response.data;

                this.loading = false;
                this.$bvModal.hide("update-status-modal");

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
                this.$bvModal.hide("update-status-modal");

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
