<template>
    <div>
        <div class="sidebar">
            <div class="sidebar-wrapper">
                <div class="logo">
                    <a href="#" class="simple-text">To-Dos</a>
                </div>
                <ul class="nav">
                    <li class="nav-item">
                        <router-link to="/home">
                            <a class="nav-link">
                                <p>Dashboard</p>
                            </a>
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/all-tasks">
                            <a class="nav-link">
                                <p>All Todos</p>
                            </a>
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/complete">
                            <a class="nav-link">
                                <p>Complete Todos</p>
                            </a>
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "sideBar"
};
</script>
