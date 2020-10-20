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
                                    <h4 class="card-title">Update Task</h4>
                                </div>
                                <div class="card-body">
                                    <form>

                                          <p v-if="errors.length">
                                    <b style="color:red;" >Please correct the following error(s):</b>
                                    <ul>
                                    <li style="color:red;"  v-for="error in errors " :key="error.id" >{{ error }}</li>
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
                                            Update Todo
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
    name: "editClient",
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
            id:"",
              errors: [],
        };
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

           let data = {
                title: this.title,
                description: this.description,
                date: this.date,
                time: this.time,
                id:this.id
            };
            axios
                .post("/updateTask", data)
                .then(response => {
                    loader.hide();
                    setTimeout(() => {
                        Vue.$toast.open({
                            message: response.data.message,
                            type: response.data.type,
                            position: response.data.position
                        });
                    }, 2000);
                })
                .catch(error => {
                    loader.hide();
                    setTimeout(() => {
                        Vue.$toast.open({
                            message: error.message,
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

        }
    },
    created() {
        let data = JSON.parse(localStorage.getItem("client"));

        console.log(data.title)
        this.title = data.title;
        this.id = data.id;
        this.description = data.description;
        this.date = data.date;
        this.time = data.time;
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
