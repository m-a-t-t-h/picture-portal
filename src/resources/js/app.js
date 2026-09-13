import {createApp} from "vue";
import {createPinia} from "pinia";
import {createPersistedState} from "pinia-plugin-persistedstate";
import {createRouter, createWebHistory} from "vue-router";
import {useStateStore} from "./services/state.js";

import AppWrapper from "./layouts/AppWrapper.vue";
import MainLayout from "./layouts/MainLayout.vue";
import HomeView from "./pages/HomeView.vue";
import HomeHeader from "./pages/HomeHeader.vue";
import Footer from "./components/Footer.vue";
import ImageView from "./pages/ImageView.vue";
import LogoutComponent from "./components/LogoutComponent.vue";

console.log("Booting");

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
                        default:  HomeView,
                        header:  HomeHeader,
                        footer:  Footer
                    }
                }, {
                    path: 'image/:img_hash/info',
                    components: {
                        default: ImageView,
                    }
                },
                {
                    path: "logout",
                    components: {
                        default: LogoutComponent
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

router.beforeEach(async () => {
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
