<template>
    <b-container class="mt-5">
        <h1>Customers List</h1>
        <b-spinner
            v-if="loading"
            variant="primary"
            label="Spinning"
        ></b-spinner>
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
                        v-b-modal.addUserModal
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
                <template #cell(actions)="row">
                    <b-link class="text-secondary" @click="editUser(row.item)">
                        <font-awesome-icon icon="edit" class="mr-3" />
                    </b-link>
                    <b-link
                        class="text-secondary"
                        @click="deleteUser(row.item)"
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
    </b-container>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            customers: [],
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
                    label: "Name",
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
                    key: "actions",
                    label: "Actions",
                    sortable: true
                }
            ]
        };
    },

    methods: {
        onFiltered() {
            //
        }
    }
};
</script>
