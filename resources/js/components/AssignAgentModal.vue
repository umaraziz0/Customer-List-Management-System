<template>
    <b-modal
        id="assign-agent-modal"
        title="Assign Customer Contact"
        hide-footer
    >
        <b-form @submit="onSubmit">
            <v-select
                v-model="customerData.agent"
                :options="agents"
                class="mb-3"
            ></v-select>
            <b-button type="submit" variant="primary" class="float-right"
                ><b-spinner small v-if="loading"></b-spinner
                ><span v-else>Assign</span>
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
            agents: [{ label: "None", id: null }],
            emits: ["reload-table"]
        };
    },

    created() {
        this.getAgents();
    },

    methods: {
        async getAgents() {
            try {
                const response = await this.axios.get("/admin/getagents");

                const data = response.data;

                data.forEach(agent => {
                    this.agents.push({
                        label: agent.name,
                        id: agent.id
                    });
                });
            } catch (errors) {
                console.error(errors.response.data);
            }
        },

        async onSubmit(event) {
            event.preventDefault();

            this.loading = true;

            try {
                const response = await this.axios.put(
                    "/admin/assign/" + this.customerData.id,
                    {
                        agent_id: this.customerData.agent.id
                    }
                );

                const data = response.data;

                this.loading = false;
                this.$bvModal.hide("assign-agent-modal");

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
                this.$bvModal.hide("assign-agent-modal");

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
