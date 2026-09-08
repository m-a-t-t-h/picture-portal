import {createApp} from "vue";
import {createPinia} from "pinia";
import {createPersistedState} from "pinia-plugin-persistedstate";
import {createRouter, createWebHistory} from "vue-router";
import AppWrapper from "./layouts/AppWrapper.vue";
import MainLayout from "./layouts/MainLayout.vue";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/dw',
            component: MainLayout,
            children: [
                {
                    path: '',
                    components: {
                        default: () => import("./pages/HomeView.vue"),
                        header: () => import("./pages/HomeHeader.vue"),
                        footer: () => import("./components/Footer.vue")
                    }
                }, {
                    path: 'image/:img_hash/info',
                    components: {
                        default: () => import("./pages/ImageView.vue"),
                    }
                }
            ]
        }
    ],
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            console.log("Returning to savedPosition of " + savedPosition.top);
            //window.scrollTo(0, savedPosition.top);
            console.log(to);

            if (to.fullPath === "/") {
                return {
                    el: '.media',
                    top: savedPosition.top,
                    left: savedPosition.left
                }
            }
        }
        return {top: 0}
    }
});

const app = createApp(AppWrapper);
const pinia = createPinia();
pinia.use(createPersistedState({storage: window.sessionStorage}));
app.use(pinia).use(router).mount("#app_root");
