<template>
    <div>
        <sideBar></sideBar>
        <div class="main-panel">
            <navBar></navBar>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Add Todo</h4>
                                </div>
                                <div class="card-body">
                                    <form>
                                    <p v-if="errors.length">
                                    <b style="color:red;" >Please correct the following error(s):</b>
                                    <ul>
                                    <li style="color:red;"  v-for="error in errors " :key="error" >{{ error }}</li>
                                    </ul>
                                </p>
                                        <div class="row">
                                            <div class="col-md-12 pr-1">
                                                <div class="form-group">
                                                    <label>Title</label>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        v-model="title"
                                                    />
                                                </div>
                                            </div>
                                            <div class="col-md-12 pl-1">
                                                <div class="form-group">
                                                    <label>Description</label>

                                                     <textarea v-model="description" class="form-control" cols="5"  rows="8"></textarea>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 pr-1">
                                                <div class="form-group">
                                                    <label>Due Date</label>
                                                    <input
                                                        type="date"
                                                        class="form-control"
                                                        v-model="date"
                                                        id="datefield"
                                                         ref="datefield"
                                                         min="1899-01-01" max="2000-13-1"
                                                    />
                                                    <span style="color:red;">     {{dateError}}</span>
                                                
                                                </div>
                                            </div>

                                            <div class="col-md-12 pl-1">
                                                <div class="form-group">
                                                    <label>
                                                      Due Time</label
                                                    > 
                                                    <input
                                                        type="time"
                                                        class="form-control"
                                                        v-model="time"
                                                    />
                                                </div>
                                            </div>
                                        </div>

                                        <button
                                            type="submit"
                                            class="btn btn-info btn-fill pull-left"
                                            @click="saveClient($event)"
                                        >
                                            Save Todo
                                        </button>
                                        <div class="clearfix"></div>
                                    </form>
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
import navBar from "../navBar.vue";
import sideBar from "../sideBar.vue";
import axios from "axios";
import Vue from "vue";
import VueToast from "vue-toast-notification";
import "vue-loading-overlay/dist/vue-loading.css";
import Loading from "vue-loading-overlay";
import "vue-toast-notification/dist/theme-default.css";
Vue.use(VueToast);
Vue.use(Loading);
export default {
    name: "newTodo",
    components: {
        navBar,
        sideBar
    },
    data() {
        return {
            title: "",
            description: "",
            date: "",
            time: "",
            dateError:"",
              errors: [],
        };
    },
    created(){
        // var today = new Date();
        // var dd = today.getDate();
        // var mm = today.getMonth()+1; //January is 0!
        // var yyyy = today.getFullYear();
        // if(dd<10){
        //         dd='0'+dd
        //     } 
        //     if(mm<10){
        //         mm='0'+mm
        //     } 

        // today = yyyy+'-'+mm+'-'+dd;

        // this.$refs.datefield.setAttribute("max", today);

        //document.getElementById("datefield").setAttribute("max", today);
    },
    methods: {
        saveClient: function(event) {

                let CurrentDate = new Date();
               let GivenDate = new Date(this.date);
              if (this.title && this.description && this.date && this.time && GivenDate > CurrentDate) {
           let loader = this.$loading.show({
                // Optional parameters
                canCancel: false,
                onCancel: this.onCancel,
                width: 40,
                height: 40,
                color: "#002d3f"
            });
            event.preventDefault();

            var today = new Date();  
            var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
            var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();

            let data = {
                title: this.title,
                description: this.description,
                date: this.date,
                time: this.time,
                status:0,
                created_time:time,
                created_date:date,
            };
            axios
                .post("saveTask", data)
                .then(response => {
                    loader.hide();
                    setTimeout(() => {
                        Vue.$toast.open({
                            message: response.data.message,
                            type: response.data.type,
                            position: response.data.position
                        });
                        this.clearForm(response.data.status);
                    }, 2000);
                })
                .catch(error => {
                    loader.hide();

                    setTimeout(() => {
                        Vue.$toast.open({
                            message: response.message,
                            type: "error",
                            position: "top"
                        });
                    }, 2000);
                });
      }

      this.errors = [];
      if (!this.title) {
        this.errors.push('Title name required is required');
      }
      if (!this.description) {
        this.errors.push('Description is  required.');
      }
      if (!this.date) {
        this.errors.push('Due Date  is  required.');
      }
      if (!this.time) {
        this.errors.push('Due Time  is  required.');
      }
      if (GivenDate < CurrentDate) {
        this.errors.push('Select a date that is greater than today');
      }
        },
        clearForm: function() {
            if (arguments[0] === 201) {
                this.title = "";
                this.description = "";
                this.date = "";
                this.time = "";
            }
        }
    }
};
</script>
<style scoped>
.container-fluid {
    margin-top: 10px;
}
.main-panel {
    overflow: auto;
    max-height: calc(100vh - 30px);
    min-height: 100%;
}
</style>
