import { createRouter, createWebHistory } from "vue-router"
import routes from "./routes.js"

const router = createRouter({
    routes,
    history: createWebHistory(),
    // linkActiveClass: "active"
});

router.beforeEach((to, from) => {
    console.log("global before each", to, from)
    if (to.path === "/tasks"){
        return { name: "login" }
    }
})
export default router
