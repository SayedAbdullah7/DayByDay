<template>
    <div class="row">
        <div style="margin: 20px;">
            <div :class="{ 'show': importModalVisible }" class="modal" tabindex="-1" role="dialog" id="importModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Import Clients</h5>
                        </div>
                        <div class="modal-body">
                            <a href="/templates/leads.xlsx"  class="btn btn-info" style="margin-bottom:10px">Download template</a>
                            <form @submit.prevent="importClients" enctype="multipart/form-data">
                                <input type="file" class="form-control mx-5" name="excel_file" accept=".xlsx, .xls, .csv" />
                                <br>
                                <button type="submit" class="btn btn-primary">Import</button>
                                <button type="button" class="btn btn-danger" @click="closeModal">Close</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-primary" @click="openModal">Import</button>
                <a class="btn  btn-secondary" href="/export-leads" style="padding:0">
                    <button type="button" class="btn  btn-secondary">Export</button>
                </a>
            </div>
        </div>
        <div class="col-xs-8" :class="!selectedRow ? 'col-xs-12' : 'col-xs-8'">
            <div class="dataTables_wrapper">
                <div id="tasks-table_filter" class="dataTables_filter">
                    <label>
                        <input type="search" style="float:right;" placeholder="Søg" v-model="search">
                    </label>
                </div>
                <table class="table table-hover dataTable">
                    <thead>
                        <tr>
                            <th v-for="(column, index) in columns" :key="index" @click="sort(column)"> {{ trans(column) }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(lead, index) in filteredList" :key="index" @click="getRow(lead)">
                            <td>{{ lead["name"] }}</td>
                            <td>{{ lead["status"].title }}</td>
                            <td>{{ lead["phone_1"] }}</td>
                            <td>{{ getLastComment(lead) }}</td>
                            <td>{{ lead["lead_source"] }}</td>
                            <td>{{ lead["user"]["name"] }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <!-- <div class="invalid-feedback alert alert-danger " role="alert" id="errorContainer"></div> -->
        </div>
        <div class="col-xs-4">
            <lead-sidebar  :statuses="statuses" :users="users" :lead="selectedRow" :hidden="selectedRow" v-on:closed-lead="leadStatusChange"
                v-on:opened-lead="leadStatusChange" v-on:deleted-lead="removeRow($event.external_id)"
                v-on:closed-sidebar="selectedRow = null" />
        </div>
    </div>
</template>
<script>
import LeadSidebar from './LeadSidebar.vue'
import moment from 'moment'

export default {
    data() {
        return {
            leads: [],
            statuses: [],
            users:[],
            columns: ['name', 'status', 'primary phone', 'last comment', 'lead_source', 'assigned'],
            selectedRow: null,
            search: '',
            confirmModal: false,
            action: null,
            modalTitle: null,
            currentSort: 'deadline',
            currentSortDir: 'asc',
            importModalVisible: false,
        }
    },
    methods: {
        getRow(row) {
            this.selectedRow = row;
        },
        getDaysSinceStart(lead) {
            let today = new Date();
            let created_at = lead.created_at
            return moment(created_at).from(moment(today));
        },
        sort: function (s) {
            //if s == current sort, reverse
            if (s === this.currentSort) {
                this.currentSortDir = this.currentSortDir === 'asc' ? 'desc' : 'asc';
            }
            this.currentSort = s;
        },
        removeRow(external_id) {
            let index = this.leads.findIndex(x => x.external_id === external_id);
            this.$delete(this.leads, index);
        },
        leadStatusChange(lead) {
            console.log(lead)
            let index = this.leads.findIndex(x => x.external_id === lead.external_id);

            this.leads[index].status.title = lead.newStatus;
        },
        openModal() {
            this.importModalVisible = true;
        },
        closeModal() {
            this.importModalVisible = false;
        },
        exportLeads() {
            window.location.href = '/export-leads';

        },
        importClients() {

            const fileInput = document.querySelector('input[name="excel_file"]');
            const file = fileInput.files[0];

            if (!file) {
                // Handle case when no file is selected
                return;
            }

            // Create a FormData object to send the file
            const formData = new FormData();
            formData.append('excel_file', file);

            // Send the file to the server using an API request (example using Axios)
            axios
                .post('/leads/import', formData)
                .then((response) => {
                    window.location.reload();
                })
                .catch((error) => {
                    console.log(error.response);
                    console.log(error.response.data.message);
                    // Handle error during import
                    console.error(error);

                    var errors = error.response.data.errors;
                    console.log(errors);
                    $('#errorContainer').html('');
                    $.each(errors, function (key, values) {
                        console.log(values);
                        $.each(values, function (index, value) {
                            $('#errorContainer').append('<li>' + value + '</li>');
                        });
                    });

                });


            // Close the modal after submitting
            this.closeModal();



        },
    },
    mounted() {
        axios
            .get('/leads/data')
            .then(response => {
                console.log(response)
                this.leads = response.data.leads,
                this.statuses = response.data.statuses,
                this.users = response.data.users

            }

            )
    },
    computed: {
        filteredList() {
            return this.leads.sort((a, b) => {
                let modifier = 1;
                if (this.currentSortDir === 'desc') modifier = -1;
                if (a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
                if (a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
                return 0;
            }).filter(lead => {

                return lead.name.toLowerCase().includes(this.search.toLowerCase())
            })
        },
        getLastComment: function () {
            return function (lead) {
                if (lead.comments.length > 0) {
                    const lastComment = lead.comments[lead.comments.length - 1];
                    const commentContent = lastComment.description.replace(/<[^>]+>/g, ""); // Remove HTML tags
                    var truncatedContent = commentContent.substring(0, 40); // Limit string to 20 characters
                    if (commentContent.length > 40) {
                        truncatedContent += "...";
                    }
                    return truncatedContent;
                } else {
                    return ""; // Return an empty string if there are no comments
                }
            };
        },
    },
    components: {
        LeadSidebar,
    }
}
</script>
<style scoped>
ul {
    padding-left: 0px;
}

ul li {
    list-style: none;
}

.assignee-name {
    font-size: 1.2em;
    font-weight: 500;
    padding-left: 1.7em;
}

.company-name {
    font-size: 1.1em;
    font-weight: 600;
}

#close-sidebar {
    display: inline-block;
    overflow: auto;
    white-space: nowrap;
    margin: 0px auto;
    border: 0;
    padding: 0;
    background: transparent;
    font-size: 1.6em;
    margin-right: 10px;
}

.cta-btn {
    font-size: 0.77em !important;
    min-height: 6em;
    white-space: normal !important;
}

.cta-btn:hover {
    color: #fefefe !important;
}

.cta-btn-icon {
    font-size: 1.8em;
}

.no-padding {
    padding-left: 0% !important;
}
</style>

