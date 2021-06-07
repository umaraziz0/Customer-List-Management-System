<template>
    <b-container class="mt-5" style="height: 100vh">
        <h1 class="mb-3">Follow Up Remarks List</h1>
        <div
            v-if="loading"
            class="d-flex justify-content-center align-items-center h-100"
        >
            <b-spinner
                variant="primary"
                label="Spinning"
                class="text-center"
            ></b-spinner>
        </div>
        <div v-else>
            <b-row class="w-100 justify-content-between ">
                <!-- Filter / Search -->
                <b-col cols="5">
                    <b-form-group class="mb-3">
                        <b-input-group>
                            <b-form-input
                                id="filter-input"
                                v-model="filter"
                                type="search"
                                placeholder="Search Customer..."
                            ></b-form-input>

                            <b-input-group-append>
                                <b-button
                                    variant="primary"
                                    @click="filter = ''"
                                >
                                    <font-awesome-icon
                                        icon="search"
                                        class="text-white"
                                    />
                                </b-button>
                            </b-input-group-append>
                        </b-input-group>
                    </b-form-group>
                </b-col>
                <!-- Add Student -->
                <b-col cols="5" class="text-right mb-3">
                    <b-button
                        size=""
                        variant="primary"
                        class="mr-2"
                        to="/agent"
                    >
                        <span class="font-weight-bold font-open-sans mr-2"
                            >Contacts List
                        </span>
                        <font-awesome-icon
                            icon="address-book"
                        ></font-awesome-icon>
                    </b-button>
                    <b-button
                        size=""
                        variant="primary"
                        class=""
                        @click="getMessages"
                    >
                        <font-awesome-icon icon="sync"></font-awesome-icon>
                    </b-button>
                </b-col>
            </b-row>
            <!-- Table -->
            <b-table
                striped
                responsive
                :fields="fields"
                :items="messages"
                :per-page="perPage"
                :current-page="currentPage"
                :filter="filter"
                :filter-included-fields="filterOn"
                @filtered="onFiltered"
                class="font-roboto"
            >
                <!-- Edit and Delete Buttons -->
                <template #cell(status)="row">
                    <b-badge :variant="statusColor(row.item.status)">{{
                        row.item.status.toUpperCase()
                    }}</b-badge>
                </template>

                <template #cell(actions)="row">
                    <div class="text-center">
                        <b-link
                            v-b-tooltip.hover
                            title="Follow Up"
                            class="text-secondary mr-3"
                            @click="followUp(row.item)"
                        >
                            <font-awesome-icon icon="reply" class="" />
                        </b-link>
                        <b-link
                            v-b-tooltip.hover
                            title="Update Status"
                            class="text-secondary"
                            @click="updateStatus(row.item)"
                        >
                            <font-awesome-icon icon="user-edit" class="" />
                        </b-link>
                    </div>
                </template>
            </b-table>
            <b-row class="justify-content-between">
                <b-col cols="4">
                    <b-form-group
                        label="Per page"
                        label-for="per-page-select"
                        label-cols-sm="6"
                        label-cols-md="4"
                        label-cols-lg="3"
                        label-align-sm="right"
                        class="mb-0"
                    >
                        <b-form-select
                            id="per-page-select"
                            v-model="perPage"
                            :options="pageOptions"
                        ></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-pagination
                        class="ml-auto"
                        align="right"
                        v-model="currentPage"
                        :total-rows="rows"
                        :per-page="perPage"
                        aria-controls="my-table"
                    ></b-pagination>
                </b-col>
            </b-row>
        </div>
    </b-container>
</template>

<script>
export default {
    components: {
        //
    },

    data() {
        return {
            loading: false,
            messages: [],
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 25, 50, 100],
            filter: null,
            filterOn: [],
            fields: [
                {
                    key: "id",
                    label: "ID",
                    sortable: true
                },
                {
                    key: "customer_id",
                    label: "Customer ID",
                    sortable: true
                },
                {
                    key: "customer_name",
                    label: "Customer Name",
                    sortable: true
                },
                {
                    key: "title",
                    label: "Title",
                    sortable: true
                },
                {
                    key: "message",
                    label: "Message",
                    sortable: true
                },
                {
                    key: "created_at",
                    label: "Sent On",
                    sortable: true
                }
            ]
        };
    },

    computed: {
        rows() {
            return this.messages.length;
        }
    },

    created() {
        this.getMessages();
    },

    methods: {
        async getMessages() {
            this.loading = true;
            this.messages = [];

            try {
                const response = await this.axios.get("/agent/getmessages");

                const data = response.data;

                this.messages = Object.keys(data).map(i => data[i]);

                this.loading = false;
            } catch (errors) {
                console.error(errors);
            }
        },

        followUp(customer) {
            this.customerData = customer;
            this.$bvModal.show("follow-up-modal");
        },

        updateStatus(customer) {
            this.customerData = customer;

            // Uppercase first letter of status
            this.customerData.status =
                customer.status.charAt(0).toUpperCase() +
                customer.status.slice(1);

            this.$bvModal.show("update-status-modal");
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },

        statusColor(status) {
            switch (status) {
                case "qualified":
                    return "success";
                    break;
                case "pending":
                    return "primary";
                    break;
                case "lost":
                    return "danger";
                    break;
                default:
                    return "secondary";
            }
        }
    }
};
</script>
