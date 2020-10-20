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
                                    <h4 class="card-title">Total Todos</h4>
                                    <p class="card-category">
                                       Complete Vs Imcomplete
                                    </p>
                                </div>
                                <div class="card-body">
                                    <div ref="clients"></div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-5">
                            <div class="card card-tasks">
                                <div class="card-header">
                                    <h4 class="card-title">User Activities</h4>
                                    <p class="card-category">
                                        Current user logs
                                    </p>
                                </div>
                                <div class="card-body" style="max-height:190px;">
                                    <div class="table-full-width">
                                        <div style="overflow-y:auto;">
                                                     <table>
                                                     <tr
                                                    v-for="item in activities"
                                                    :key="item.id"
                                                >
                                                    <td
                                                        style="width:fit-content"
                                                    >
                                                        <span>
                                                            {{ item.userName }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ item.action }}
                                                    </td>
                                                </tr>
                                                 </table>
                                            </div>
                                       
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Current Todos</h4>
                                    <p class="card-category">
                                    Due Today
                                    </p>
                                </div>
                                <div class="card-body">
                               
                                        <div style="overflow-y:auto;">
                                                     <table>
                                                      ​​​​​​<tr>
                                                    <th>TITLE</th>
                                                    <th>DESCRIPTION</th>
                                                    <th>DUE TIME</th>
                                                    <th>ACTION</th>
                                                  
                                                    </tr>
                                                     <tr
                                                    v-for="item in dueTodayTodos"
                                                    :key="item.id"
                                                    v-show="allTasks"
                                                >
                                                    <td
                                                        style="width:fit-content"
                                                    >
                                                        <span>
                                                            {{ item.title }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ item.description }}
                                                    </td>
                                                      <td>
                                                        {{ item.time }}
                                                    </td>
                                                      <td>
                                                       <button
                                                                style="margin-right:2px;"
                                                                type="button"
                                                                @click="
                                                                    completed(
                                                                      item.id
                                                                    )
                                                                "
                                                            >
                                                                COMPLETE
                                                        </button>

                                                <button
                                                    style="margin-right:2px;"
                                                    type="button"
                                                    @click="
                                                        removeTask(
                                                             item.id
                                                        )
                                                    "
                                                >
                                                    DELETE
                                                </button>
                                                    </td>
                                                </tr>

                                                  <tr
                                                    v-show="zeroTasks"
                                                >
                                                <td></td>
                                                <td> <center>  You have no current tasks</center></td>
                                                <td></td>
                                                <td></td>

                                                 


                                                </tr>
                                                 </table>
                                            </div>
                                       
                                   
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
import Vue from "vue";
import axios from "axios";
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";

am4core.useTheme(am4themes_animated);
export default {
    name: "home",
    components: {
        navBar,
        sideBar,
        axios
    },
    data() {
        return {
            allClients: [],
            activities: [],
            dueTodayTodos: [],
            zeroTasks:false,
            allTasks:false,
        };
    },
    methods: {
           removeItems: function () {
      const item = this.dueTodayTodos.findIndex((p) => p.id === arguments[0]);
      this.dueTodayTodos.splice(item, 1);
      if(this.dueTodayTodos.length === 0){
          this.zeroTasks =true
      }
    },
         completed:function()
        {
            this.isLoading =true
            let payload = {
                id: arguments[0],
                status :1
            };
            axios
                .post("changeStatus", payload)
                .then(response => {
                    if (response.data.status === 200) {
                          this.isLoading =false
                        Vue.$toast.open({
                            message: response.data.message,
                            type: response.data.type,
                            position: response.data.position
                        });

                           setTimeout(() => {
                        this.removeItems(arguments[0])
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
                id: arguments[0]
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
                      this.removeItems(arguments[0])
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
        getClients: function() {
            axios
                .get("getAll")
                .then(response => {
                    if (response.status === 200) {
                        localStorage.setItem(
                            "serverClients",
                            JSON.stringify(response.data)
                        );
                    } else {
                        Vue.$toast.open({
                            message: response.data.message,
                            type: "error",
                            position: "top"
                        });
                    }
                })
                .catch(error => {
                    Vue.$toast.open({
                        message: error.message,
                        type: "error",
                        position: "top"
                    });
                });
        },
        getActivities: function() {
            axios
                .get("getActivites")
                .then(response => {
                    if (response.status === 200) {
                        this.activities = response.data;
                    } else {
                        Vue.$toast.open({
                            message: response.data.message,
                            type: "error",
                            position: "top"
                        });
                    }
                })
                .catch(error => {
                    Vue.$toast.open({
                        message: error.message,
                        type: "error",
                        position: "top"
                    });
                });
        },
          dueToday: function() {
             
            axios
                .get("dueTodayTasks")
                .then(response => {
                    if (response.status === 200) {
                      
                        if(response.data.length ===0)
                        {
                            this.allTasks =false;
                            this.zeroTasks =true;
                        }else{
                              this.allTasks =true;
                            this.dueTodayTodos = response.data;
                        }
                    } else {
                        Vue.$toast.open({
                            message: response.data.message,
                            type: "error",
                            position: "top"
                        });
                    }
                })
                .catch(error => {
                    Vue.$toast.open({
                        message: error.message,
                        type: "error",
                        position: "top"
                    });
                });
        }
    },
    created() {
        this.getClients();
        this.getActivities();
        this.dueToday();
    },
    mounted() {
        let clients = JSON.parse(localStorage.getItem("serverClients"));
        let chart = am4core.create(this.$refs.clients, am4charts.PieChart);
        let complete = [];
        let incomplete = [];

        // if (clients === null) {
        //     window.location.reload();
        // }
        clients.forEach(element => {
            if (element.status === 1) {
                complete.push(element);
            }else{
                 incomplete.push(element);  
            }
         
        });

        chart.data = [
            {
                status: "Complete",
                total: complete.length
            },
            {
                status: "Incomplete",
                total: incomplete.length
            }
        ];
        let pieSeries = chart.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "total";
        pieSeries.dataFields.category = "status";
    }
};
</script>
<style scoped>
.container-fluid {
    margin-top: 10px;
}
.card {
    height: fit-content;
}
.card-body {
    height: fit-content;
}
.main-panel {
    overflow: auto;
    max-height: calc(100vh - 30px);
    min-height: 100%;
}

table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  max-height: 180px;
 
}

th, td {
  text-align: left;
  padding: 8px;
}

tr:nth-child(even){background-color: #f2f2f2}
</style>
