import {createApp} from "vue";
import {createPinia} from "pinia";
import {createPersistedState} from "pinia-plugin-persistedstate";
import {createRouter, createWebHistory} from "vue-router";
import AppWrapper from "./layouts/AppWrapper.vue";
import MainLayout from "./layouts/MainLayout.vue";
import {useStateStore} from "./services/state.js";

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
                },
                {
                    path: "logout",
                    components: {
                        default: () => import("./components/LogoutComponent.vue")
                    }
                }
            ]
        }
    ],

    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            if (to.fullPath === "/dw") {
                return {
                    el: '.media-loop',
                    top: savedPosition.top,
                    left: savedPosition.left
                }
            }
        }
        return {top: 0}
    },
});

router.beforeEach(async (to, from) => {
    const state = useStateStore();
    const response = await fetch('/dw/auth/authed', {credentials: 'same-origin', headers: {Accept: 'application/json'}})

    if (response.ok) {
        const data = await response.json();
        state.auth.isAuthed = data.authed;
    } else {
        state.auth.isAuthed = false;
    }
});

const app = createApp(AppWrapper);
const pinia = createPinia();
pinia.use(createPersistedState({storage: window.sessionStorage}));
app.use(pinia).use(router).mount("#app_root");
