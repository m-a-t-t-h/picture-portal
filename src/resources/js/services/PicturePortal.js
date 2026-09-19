import {toRaw} from "vue";
import {useStateStore} from "./state.js";

export default class PicturePortal {



    static async loadMore() {
        const state = useStateStore();
        const token = document.querySelector('meta[name="csrf-token"]').content;

        if (state.filterSelectedTags) {

            console.log("Loading image results page %d", state.prefs.page);
            fetch("/dw/results", {
                method: "POST",
                headers: {"Content-Type": "application/json", "X-CSRF-TOKEN": token},
                body: JSON.stringify({
                    filter: state.filterSelectedTags,
                    orderBy: state.filterOrderBy,
                    page: state.filterPage
                })
            }).then(response => response.json()).then(data => {
                if (data.length) {
                    state.setResults(data);
                }
            });
        }
    }
}
