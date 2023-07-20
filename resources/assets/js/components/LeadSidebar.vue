<template>
<div v-if="lead !== null" v-show="hidden">
    <div class="modal fade" id="create-offer" tabindex="-1" role="dialog" aria-hidden="true"
         style="display:none;">
        <div class="modal-dialog modal-lg" style="background:white;">
            <invoice-line-modal type="offer" :resource="lead"/>
        </div>
    </div>
  
    <transition name="appear">
        <confirm-modal v-if='confirmModal' :title="modalTitle" @cancel="closeConfirmModal()" @confirm='action()'></confirm-modal>
    </transition>
   
    <!--INFO-->
    <div class="tablet">
        <div class="tablet__head tablet__head__color-brand">
                <h3 class="tablet__head-title text-white">{{trans('Information')}}</h3>
            <button id="close-sidebar" @click="closeSidebar">
                <i class="fa fa-times text-right"></i>
            </button>
        </div>
            <div class="tablet__body">
                <div class="tab-content">
                        <div class="k-scroll ps ps--active-y" data-scroll="true" style="overflow: hidden;" data-mobile-height="350">
                            <div class="row">
                                <div class="col-xs-3">
                                    <a :href="'/leads/' + lead.external_id">
                                        <button class="btn btn-brand btn-full-width cta-btn">
                                            <i class="ion ion-ios-redo cta-btn-icon"></i> <br>
                                            {{trans('View')}}
                                        </button>
                                    </a>
                                </div>
                                <!-- <div class="col-xs-3 no-padding">
                                    <button class="btn btn-brand btn-full-width cta-btn"  @click="showOfferModal()">
                                        <i class="fa fa-dollar cta-btn-icon"></i> <br>
                                        {{trans('Create offer')}}
                                    </button>
                                </div> -->
                                <div v-if="lead.status.title != 'Closed'" class="col-xs-3 no-padding" style="z-index: 999">
                                    <button class="btn btn-brand btn-full-width cta-btn" @click="openConfirmModal(closeLead, 'Are you sure you want to close the lead?')">
                                        <i class="fa fa-close cta-btn-icon"></i> <br>
                                        {{trans('Close')}}
                                    </button>
                                </div>
                                <div v-if="lead.status.title == 'Closed'" class="col-xs-3 no-padding" style="z-index: 999">
                                    <button class="btn btn-brand btn-full-width cta-btn" @click="openConfirmModal(reopenLead, 'Are you sure you want to open the lead?')">
                                        <i class="fa fa-check cta-btn-icon"></i> <br>
                                        {{trans('Reopen')}}
                                    </button>
                                </div>
                                <div class="col-xs-3 no-padding" >
                                    <button class="btn btn-brand btn-full-width cta-btn" @click="openConfirmModal(deleteLead, 'Are you sure you want to delete the lead?')">
                                        <i class="fa fa-trash cta-btn-icon"></i> <br>
                                        {{trans('Delete')}}
                                    </button>
                                </div>
                                <div class="col-xs-12 movedown">
                                    <p>{{lead.title}}</p>
                                </div>
                                <div class="col-xs-4">
                                    {{trans('Deadline')}}
                                </div>
                                <div class="col-xs-8">
                                    <p>{{lead.visible_deadline_date}} {{lead.visible_deadline_time}}</p>
                                </div>
                                <br>
                                <div class="col-xs-4">
                                    {{trans('Project')}}
                                </div>
                                <div class="col-xs-8">
                                    <p  v-if="lead.project" >{{ lead.project.title }}</p>
                                    <p v-else> ... </p>
                                </div>
                                <br>
                                <br>
                                <div class="col-xs-4">
                                    {{trans('Status')}}
                                </div>
                                <div class="col-xs-8" v-if="!editMode.statusTitle">
                                    <p style="display: inline-table;">{{ lead.status.title }}</p>
                                    <span @click="editField('statusTitle')" class="edit-icon">✎</span>
                                </div>
                                <div class="col-xs-8" v-else>
                                    <select v-model="lead.status.id">
                                        <option v-for="status in statuses" :key="status.id" :value="status.id" :selected="lead.status.id === status.id">{{ status.title }}</option>
                                    </select>
                                    <span @click="saveField('statusTitle')" class="save-icon">✔</span>
                                    <span @click="cancelEditField('statusTitle')" class="cancel-icon">✖</span>
                                </div>
                                <div class="col-xs-4">
                                    {{trans('Assignee user')}}
                                </div>
                                <div class="col-xs-8" v-if="!editMode.assigneeUser">
                                    <p style="display: inline-table;">{{ lead.user.name }}</p>
                                    <span @click="editField('assigneeUser')" class="edit-icon">✎</span>
                                </div>
                                <div class="col-xs-8" v-else>
                                    <select v-model="lead.user.id">
                                        <option v-for="user in users" :key="user.name" :value="user.id" :selected="lead.user.id == user.id">{{ user.name }}</option>
                                    </select>
                                    <span @click="saveField('assigneeUser')" class="save-icon">✔</span>
                                    <span @click="cancelEditField('assigneeUser')" class="cancel-icon">✖</span>
                                </div>
                                <br>
                                <div class="col-xs-4 " >
                                    {{trans('Created by')}}
                                </div>
                                <div class="col-xs-8">
                                    <span>{{ lead.creator.name }}</span>
                                </div>


                            </div>
                        </div>
                </div>
            </div>
        </div>
    <!--Client-->
    <div class="tablet" v-if="lead.project">
        <div class="tablet__head">
            <div class="tablet__head-label">
                <h3 class="tablet__head-title">{{trans('Project')}}</h3>
            </div>
        </div>
        <div class="tablet__body">
            <div class="row">
                <div class="col-xs-12">
                    <p class="company-name">
                    {{lead.project.title}}
                    </p>
                </div>
                    <div class="col-xs-6"><p aria-hidden="true" data-toggle="tooltip"
                                                :title="trans('Project name')">{{lead.project.title}}</p></div>
                    <!-- <div class="col-xs-6"><p><a :href="'mailto:' + lead.client.primary_contact.email">{{lead.client.primary_contact.email}}</a></p></div> -->
                    <div class="col-xs-6"><p aria-hidden="true" data-toggle="tooltip"
                                                :title="trans('Primary number')">{{lead.phone_1}}</p></div>
                    <div class="col-xs-6"><p aria-hidden="true" data-toggle="tooltip"
                                                :title="trans('Secondary number')">{{lead.phone_2}}</p></div>
                    
                    
                    
            </div>
        </div>
    </div>
    <!--Assignee-->
    <div class="tablet">
        <div class="tablet__head">
            <div class="tablet__head-label">
                <h3 class="tablet__head-title">{{trans('Assignee')}}</h3>
            </div>
        </div>
        <div class="tablet__body">
            <div class="row">
                <div class="col-sm-3">
                    <div class="profilepic">
                        <img :src="lead.user.avatar" class="profilepicsize">
                    </div>
                </div>
                <div class="col-sm-9">
                    <ul>
                        <li class="assignee-name">{{lead.user.name}}</li>
                        <li v-if="isUsersOnePhoneNumberSet(lead.user)">
                            <i class="fa fa-phone" aria-hidden="true" style="padding-right: 1em"></i>
                            {{lead.user.primary_number}}
                            {{lead.user.primary_number != "" &&  lead.user.secondary_number != "" ? '/' : ''}}
                            {{lead.user.secondary_number}}
                        </li>
                        <li><i class="fa fa-envelope-o" aria-hidden="true" style="padding-right: 1em"></i><a :href="'mailto:' + lead.user.email">{{lead.user.email}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import ConfirmModal from './ConfirmModal.vue'
import InvoiceLineModal from './InvoiceLineModal.vue'
export default {
    data() {
        return {
            confirmModal: false,
            action: null,
            editMode: {
                projectTitle: false,
                statusTitle: false,
                assigneeUser: false,
            },
        }
    },
  methods: {
    closeSidebar() {
        this.$emit('closed-sidebar', this.lead)
    },
    closeLead() {
        axios
            .post('/leads/updatestatus/' + this.lead.external_id, {"closeLead": true})
        this.lead.newStatus = "Closed"
        this.$emit('closed-lead', this.lead)
        this.closeConfirmModal()

    },
    reopenLead() {
        axios
            .post('/leads/updatestatus/' + this.lead.external_id, {"openLead": true})
        this.lead.newStatus = "Open"
        this.$emit('opened-lead', this.lead)
        this.closeConfirmModal()

    },
    deleteLead(){
        axios
            .delete('/leads/' + this.lead.external_id + "/json")
        this.$emit('deleted-lead', this.lead)
        this.closeSidebar()
        this.closeConfirmModal()
    },
    isUsersOnePhoneNumberSet(user){
        if (user.primary_number || user.secondary_number) {
            return true;
        }
        return false;
    },
    openConfirmModal(action, modalTitle) {
        this.confirmModal = true
        this.action = action
        this.modalTitle = modalTitle
    },
    closeConfirmModal() {
        this.confirmModal = false
    },
    showOfferModal() {
        $('#create-offer').modal('show');
    },
    editField(field) {
      this.editMode[field] = true;
    },
    // Method to save changes to a field
    saveField(field) {
      this.editMode[field] = false;
      if(field == 'statusTitle'){
        var updatedData = {
            field: field,
            external_id:this.lead.external_id,
            status_id: this.lead.status.id, // Pass the selected value
            jsonResponse : true
        };
        var url= '/leads/updatestatus/'+this.lead.external_id;
      }else{
        if(!this.lead.user.id){
            return;
        }
        var updatedData = {
            field: field,
            external_id:this.lead.external_id,
            user_assigned_id: this.lead.user.id, // Pass the selected value
            jsonResponse : true
        };
        var url= '/leads/updateassign/'+this.lead.external_id;
        console.log(updatedData);
      }
        console.log(updatedData);
        // + this.lead.external_id,
        axios.post(url, updatedData)
        .then(response => {
          // Handle the response from the backend if necessary
          console.log(response);
          if(response.status){
            if(field == 'statusTitle'){
                const selectedStatus = this.statuses.find(status => status.id === this.lead.status.id);
                this.lead.status.title = selectedStatus.title;
                this.lead.status = selectedStatus;
            }else{
                const selectedUser = this.users.find(user => user.id === this.lead.user.id);
                this.lead.user = selectedUser;
            }
            this.editMode[field] = false;
          }

        })
        .catch(error => {
            console.log('error');
            if (error.response) {
                console.log(error.response.data);
                console.log(error.response.status);
                console.log(error.response.headers);
            } else if (error.request) {

                console.log(error.request);
            } else {
                console.log('Error', error.message);
            }
        });


        
      // Save changes to the server (you might want to use axios.post to update the data)
    },

    // Method to cancel editing and revert to original value
    cancelEditField(field) {
      this.editMode[field] = false;
      // Revert to original value (if necessary)
    },
  },
  components: {
        ConfirmModal,
        InvoiceLineModal,
  },
  props: {
      lead: {},
      hidden: false,
      statuses: {},
      users:{}
  },
    
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
        display:inline-block;
        overflow: auto;
        white-space: nowrap;
        margin:0px auto;
        border: 0;
        padding: 0;
        background: transparent;
        font-size:1.6em;
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
<style>
/* Add your CSS styles here */
.edit-icon,
.save-icon,
.cancel-icon {
  cursor: pointer;
  margin-left: 5px;
}
</style>