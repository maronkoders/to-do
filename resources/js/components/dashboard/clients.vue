<template>
    <div>
        <sideBar></sideBar>
        <div class="main-panel">
            <navBar></navBar>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-lg-12">
                            <!-- <button @click="addNewTodo()"
                                                    class="btn btn-info btn-fill"
                                                    style="margin-right:10px; 
                                                    
                                                    margin-bottom:10px;"
                                                >
                                                    ADD NEW TODO
                                                </button> -->
                            <div class="content-panel">
                                <div id="table">
                                    <VueGoodTable
                                        ref="clients"
                                        :columns="columns"
                                        :rows="rows"
                                        :isLoading.sync="isLoading"
                                        :search-options="{
                                            enabled: true,
                                            width: '100px',
                                            placeholder: 'Search Complete Todos'
                                        }"
                                        :select-options="{ enabled: true }"
                                        :sort-options="{
                                            enabled: true,
                                            initialSortBy: {
                                                field: 'title',
                                                type: 'asc'
                                            }
                                        }"
                                        :pagination-options="{
                                            enabled: true,
                                            mode: 'pages',
                                            perPage: 10,
                                            position: 'bottom',
                                            perPageDropdown: [25, 50, 100],
                                            dropdownAllowAll: false,
                                            setCurrentPage: 1,
                                            nextLabel: 'next',
                                            prevLabel: 'prev',
                                            rowsPerPageLabel: 'Rows per page',
                                            ofLabel: 'of',
                                            pageLabel: 'page', // for 'pages' mode
                                            allLabel: 'All'
                                        }"
                                    >
                                      
                                        <div slot="selected-row-actions">
                                            <button @click="deleteTasks">
                                                Delete
                                            </button>

                                               <button @click="undoTasks">
                                                UNDO
                                            </button>
                                        </div>

                                        <div
                                            slot="table-row"
                                            slot-scope="props"
                                        >
                                            <span
                                                v-if="
                                                    props.column.field == 'btn'
                                                "
                                            >
                                             
                                                    <button
                                                        style="margin-right:2px;"
                                                        type="button"
                                                        @click="
                                                            undoTask(
                                                                props.formattedRow
                                                            )
                                                        "
                                                    >
                                                        UNDO
                                                    </button>
                                               
                                               
                                               
                                                <button
                                                    style="margin-right:2px;"
                                                    type="button"
                                                    @click="
                                                        removeTask(
                                                            props.formattedRow
                                                        )
                                                    "
                                                >
                                                    DELETE
                                                </button>
                                            </span>
                                            <span v-else>
                                                {{
                                                    props.formattedRow[
                                                        props.column.field
                                                    ]
                                                }}
                                            </span>
                                        </div>
                                    </VueGoodTable> 

                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Vue from "vue";
import navBar from "../navBar.vue";
import sideBar from "../sideBar.vue";
import { VueGoodTable } from "vue-good-table";
import "vue-good-table/dist/vue-good-table.css";
import axios from "axios";
import VueToast from "vue-toast-notification";
import "vue-toast-notification/dist/theme-default.css";
import "vue-loading-overlay/dist/vue-loading.css";
import Loading from "vue-loading-overlay";
Vue.use(Loading);
Vue.use(VueToast);
import { SweetModal, SweetModalTab } from 'sweet-modal-vue'

export default {
    name: "clients",
    components: {
        navBar,
        sideBar,
        Loading,
        VueGoodTable,
        SweetModal,
		SweetModalTab
    },
    data() {
        return {
                isLoading: false,

            columns: [
                {
                    label: "id",
                    field: "id",
                    hidden: true
                },
                {
                    label: " Title",
                    field: "title"
                },
                {
                    label: "Description",
                    field: "description"
                },
                {
                    label: "Due Date",
                    field: "date"
                },
                 {
                    label: "Due Time",
                    field: "time"
                },
                   {
                    label: "Created Time",
                    field: "created_time"
                },
                 {
                    label: "Created Date",
                    field: "created_date"
                },
                {
                    label: "Status",
                    field: this.nameStatus
                },
                {
                    label: "Action",
                    field: "btn",
                    html: true
                }
            ],
            rows: [],
            title:"",
            description:"",
            time:"",
            date:"",
        };
    },
    methods: {
             removeMutlipleTasks: function () {
            arguments[0].forEach(element => {
                        const item = this.rows.findIndex((p) => p.id === element.id);
                         this.rows.splice(item, 1);
                    });
    },
          removeItems: function () {
      const item = this.rows.findIndex((p) => p.id === arguments[0]);
      this.rows.splice(item, 1);
    },
        nameStatus: function(rowObj) {
            if (rowObj.status === 1) {
                return "COMPLETED ";
            }
        },
        undoTasks:function()
        {
        this.isLoading =true
            let payload ={
                id: this.$refs.clients.selectedRows,
                status:0
            }

            axios
                .post("changeMultipleStatus",payload)
                .then(response => {
                    if (response.status === 200) {
                          this.isLoading =false
                        Vue.$toast.open({
                            message: response.data.message,
                            type: response.data.type,
                            position: response.data.position
                        });
                        setTimeout(() => {
                         this.removeMutlipleTasks(this.$refs.clients.selectedRows)
                        }, 2300);
                    } else {
                          this.isLoading =false
                        Vue.$toast.open({
                            message: response.data.message,
                            type: "error",
                            position: "top"
                        });
                    }
                })
                .catch(error => {
                      this.isLoading =false
                    Vue.$toast.open({
                        message: error.message,
                        type: "error",
                        position: "top"
                    });
                });
        },
        deleteTasks: function() {
           this.isLoading =true

            axios
                .post("deleteTasks", this.$refs.clients.selectedRows)
                .then(response => {
                    if (response.status === 200) {
                          this.isLoading =false
                        Vue.$toast.open({
                            message: response.data.message,
                            type: response.data.type,
                            position: response.data.position
                        });
                        setTimeout(() => {
                         this.removeMutlipleTasks(this.$refs.clients.selectedRows)
                        }, 2300);
                    } else {
                          this.isLoading =false
                        Vue.$toast.open({
                            message: response.data.message,
                            type: "error",
                            position: "top"
                        });
                    }
                })
                .catch(error => {
                      this.isLoading =false
                    Vue.$toast.open({
                        message: error.message,
                        type: "error",
                        position: "top"
                    });
                });
        },
        removeTask: function() {
           this.isLoading =true

            let payload = {
                id: arguments[0].id
            };

            axios
                .post("deleteTask", payload)
                .then(response => {
                    if (response.status === 200) {
                          this.isLoading =false
                        Vue.$toast.open({
                            message: response.data.message,
                            type: response.data.type,
                            position: response.data.position
                        });

                           setTimeout(() => {
                        this.removeItems(arguments[0].id)
                        }, 2300);
                        
                    } else {
                          this.isLoading =false
                        Vue.$toast.open({
                            message: response.data.message,
                            type: "error",
                            position: "top"
                        });
                    }
                })
                .catch(error => {
                      this.isLoading =false
                    Vue.$toast.open({
                        message: error.message,
                        type: "error",
                        position: "top"
                    });
                });
        },
        undoTask: function() {
            let payload ={
                id:arguments[0].id,
                status:0
            }
            this.isLoading =true
            axios
                .post("changeStatus", payload)
                .then(response => {
                    if (response.status === 200) {
                          this.isLoading =false
                        Vue.$toast.open({
                            message: response.data.message,
                            type: response.data.type,
                            position: response.data.position
                        });
                        setTimeout(() => {
                         this.removeItems(arguments[0].id)
                        }, 2300);
                    } else {
                          this.isLoading =false
                        Vue.$toast.open({
                            message: response.data.message,
                            type: "error",
                            position: "top"
                        });
                    }
                })
                .catch(error => {
                      this.isLoading =false
                    Vue.$toast.open({
                        message: error.message,
                        type: "error",
                        position: "top"
                    });
                });
           // localStorage.setItem("client", JSON.stringify(arguments[0]));
        },
          addNewTodo: function() {

              this.$router.push("/new-todo")
        
        },
        getTasks: function() {
              this.isLoading = true
            axios
                .get("getCompleted")
                .then(response => {
                    if (response.status === 200) {
                          this.isLoading =false
                        this.rows = response.data;
                    } else {
                        Vue.$toast.open({
                            message: response.data.message,
                            type: "error",
                            position: "top"
                        });
                    }
                })
                .catch(error => {
                      this.isLoading =false
                    Vue.$toast.open({
                        message: error.message,
                        type: "error",
                        position: "top"
                    });
                });
        },
        changeStatus() {
           this.isLoading =true

            let payload = {
                status: arguments[0],
                id: arguments[1].id
            };
            axios
                .post("changeStatus", payload)
                .then(response => {
                    if (response.status === 200) {
                          this.isLoading =false
                        Vue.$toast.open({
                            message: response.data.message,
                            type: response.data.type,
                            position: response.data.position
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 5000);
                    } else {
                          this.isLoading =false
                        Vue.$toast.open({
                            message: response.data.message,
                            type: "error",
                            position: "top"
                        });
                    }
                })
                .catch(error => {
                      this.isLoading =false
                    Vue.$toast.open({
                        message: error.message,
                        type: "error",
                        position: "top"
                    });
                });
        }
    },
    created() {
        this.isLoading =true
        this.getTasks();
    },
   
};
</script>
<style scoped>
button {
    font-size: x-small;
}
.days {
    float: right;
}
.checkbox {
    height: 13px;
}
.form-control {
    padding: 0px;
    height: 20px;
}
.main-panel {
    overflow: auto;
    max-height: calc(100vh - 30px);
    min-height: 100%;
}

.align {
    display: block;
}
label {
    text-transform: uppercase;
}

.container-fluid {
  margin-top: 10px;
}

</style>
