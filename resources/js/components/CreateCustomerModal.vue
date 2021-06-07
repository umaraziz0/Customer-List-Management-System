<template>
    <b-modal
        id="create-customer-modal"
        title="Add New Customer Contact"
        hide-footer
    >
        <b-form @submit="onSubmit">
            <b-form-group
                id="input-group-2"
                label="Customer Name:"
                label-for="name"
            >
                <b-form-input
                    id="name"
                    v-model="form.name"
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
                    v-model="form.phone"
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
                    v-model="form.email"
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
    data() {
        return {
            loading: false,
            form: {
                name: "",
                phone: "",
                email: ""
            },
            toastCount: 0,
            emits: ["reload-table"]
        };
    },

    methods: {
        async onSubmit(event) {
            event.preventDefault();

            this.loading = true;

            try {
                const response = await this.axios.post(
                    "/admin/customer",
                    this.form
                );

                const data = response.data;

                this.loading = false;
                this.$bvModal.hide("create-customer-modal");

                // Show Toast
                this.toastCount++;
                this.$bvToast.toast(data.message, {
                    title: "Success",
                    autoHideDelay: 5000,
                    variant: "success"
                });

                // Reset form if successful
                this.form.name = "";
                this.form.email = "";
                this.form.phone = "";

                // Refresh table
                this.$emit("reload-table");
            } catch (errors) {
                this.loading = false;
                this.$bvModal.hide("create-customer-modal");

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
