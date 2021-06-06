<template>
    <b-container class="mt-5" style="height: 100vh">
        <h1 class="mb-3">Customer Contacts List</h1>
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
                <b-col cols="5" class="text-right">
                    <b-button
                        size=""
                        variant="primary"
                        class="mb-3"
                        v-b-modal.create-customer-modal
                    >
                        <span class="font-weight-bold font-open-sans mr-2"
                            >Add Customer
                        </span>
                        <font-awesome-icon icon="plus"></font-awesome-icon>
                    </b-button>
                </b-col>
            </b-row>
            <!-- Table -->
            <b-table
                striped
                responsive
                :fields="fields"
                :items="customers"
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
                    <b-link
                        v-b-tooltip.hover
                        title="Assign Agent"
                        class="text-secondary"
                        @click="assignAgent(row.item)"
                    >
                        <font-awesome-icon icon="user" class="mr-3" />
                    </b-link>
                    <b-link
                        v-b-tooltip.hover
                        title="Edit Contact"
                        class="text-secondary"
                        @click="editCustomer(row.item)"
                    >
                        <font-awesome-icon icon="edit" class="mr-3" />
                    </b-link>
                    <b-link
                        v-b-tooltip.hover
                        title="Delete Contact"
                        class="text-secondary"
                        @click="deleteCustomer(row.item)"
                    >
                        <font-awesome-icon icon="trash" class="" />
                    </b-link>
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
        <create-customer-modal @reload-table="getCustomers" />
        <edit-customer-modal
            :customerData="customerData"
            @reload-table="getCustomers"
        />
    </b-container>
</template>

<script>
import CreateCustomerModal from "./components/CreateCustomerModal.vue";
import EditCustomerModal from "./components/EditCustomerModal.vue";

export default {
    components: { CreateCustomerModal, EditCustomerModal },

    data() {
        return {
            loading: false,
            customers: [],
            customerData: {},
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
                    key: "name",
                    label: "Customer Name",
                    sortable: true
                },
                {
                    key: "phone",
                    label: "Phone No.",
                    sortable: true
                },
                {
                    key: "email",
                    label: "Email",
                    sortable: true
                },
                {
                    key: "status",
                    label: "Status",
                    sortable: true
                },
                {
                    key: "agent",
                    label: "Agent",
                    sortable: true
                },
                {
                    key: "actions",
                    label: "Actions",
                    sortable: true
                }
            ]
        };
    },

    computed: {
        rows() {
            return this.customers.length;
        }
    },

    created() {
        this.getCustomers();
    },

    methods: {
        async getCustomers() {
            this.loading = true;
            this.customers = [];

            try {
                const response = await this.axios.get("/customers");

                const data = response.data;

                this.customers = Object.keys(data).map(i => data[i]);

                this.loading = false;
            } catch (errors) {
                console.error(errors);
            }
        },

        editCustomer(customer) {
            this.customerData = customer;
            this.$bvModal.show("edit-customer-modal");
        },

        deleteCustomer(customer) {
            this.customerData = customer;
            this.$bvModal.show("delete-customer-modal");
        },

        assignAgent(customer) {
            //
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
