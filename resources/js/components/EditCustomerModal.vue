<template>
    <b-modal id="edit-customer-modal" title="Edit Customer Contact" hide-footer>
        <b-form @submit="onSubmit">
            <b-form-group
                id="input-group-2"
                label="Customer Name:"
                label-for="name"
            >
                <b-form-input
                    id="name"
                    v-model="customerData.name"
                    placeholder=""
                    required
                ></b-form-input>
            </b-form-group>

            <b-form-group
                id="input-group-2"
                label="Customer Phone No.:"
                label-for="phone"
            >
                <b-form-input
                    id="phone"
                    v-model="customerData.phone"
                    placeholder=""
                    required
                ></b-form-input>
            </b-form-group>

            <b-form-group
                id="input-group-2"
                label="Customer Email:"
                label-for="email"
            >
                <b-form-input
                    id="email"
                    v-model="customerData.email"
                    placeholder=""
                    required
                ></b-form-input>
            </b-form-group>

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
            toastCount: 0,
            emits: ["reload-table"]
        };
    },

    methods: {
        async onSubmit(event) {
            event.preventDefault();

            this.loading = true;

            try {
                const response = await this.axios.put(
                    "/customer/" + this.customerData.id,
                    this.customerData
                );

                const data = response.data;

                this.loading = false;
                this.$bvModal.hide("edit-customer-modal");

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
                this.$bvModal.hide("edit-customer-modal");

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
