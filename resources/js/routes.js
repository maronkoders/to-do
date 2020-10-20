import Vue from "vue";
import VueRouter from "vue-router";

import home from "@/js/components/dashboard/home.vue";
import addClient from "@/js/components/dashboard/addClient.vue";
import clients from "@/js/components/dashboard/clients.vue";
import editClient from "@/js/components/dashboard/editClient.vue";
import newTodo from "@/js/components/dashboard/newTodo.vue";

Vue.use(VueRouter);

const router = new VueRouter({
    mode: "history",
    routes: [{
            path: "/home",
            name: "home",
            component: home
        },
        {
            path: "/all-tasks",
            name: "addClient",
            component: addClient
        },
        {
            path: "/new-todo",
            name: "newTodo",
            component: newTodo
        },
        {
            path: "/complete",
            name: "clients",
            component: clients
        },
        {
            path: "/edit-task",
            name: "editClient",
            component: editClient
        }
    ]
});
export default router;