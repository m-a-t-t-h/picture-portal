import {defineStore} from "pinia";

const key = "state-0.2";

export const useStateStore = defineStore(key, {

    persist: {
        debug: false,
        key: "digiweb-" + key,
        include: ["prefs"],
        exclude: ["tree"],
        storage: localStorage
    },

    state: () => ({
        tree: [],
        prefs: {
            results: [],
            tag_filter: "",
            orderBy: "1",
            showFilename: false,
            showTagsBelow: true,
            showTagId: false,
            showPath: false,
            showTimestamp: false,
            showImageId: false,
            showRating: false,
            showCameraInfo: false,
            selected_photo: null,
        }
    }),

    getters: {
        showFilename: (state) => state.prefs.showFilename,
        getTree: async (state) => {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            await fetch("/tree", {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token
            })
                .then(response => response.json())
                .then(data => {

                    state.tree = data;
                })
            return state.tree;
        },
        getSelectedPhoto: (state) => state.prefs.selected_photo,
        getResults: (state) => state.prefs.results,
    },

    actions: {
        setResults(results) {
            this.prefs.results = results;
        },
        setSelectedPhoto(photo) {
            this.prefs.selected_photo = photo;
        },
        setTagFilter(filter) {
            this.prefs.tag_filter = filter;
            window.dispatchEvent(new CustomEvent("filter-updated", {detail: {filter: filter, orderBy: this.prefs.orderBy}}));
        },
        setOrderBy(value) {
            this.prefs.orderBy = value;
            window.dispatchEvent(new CustomEvent("filter-updated", {detail: {filter: this.prefs.tag_filter, orderBy: this.prefs.orderBy}}));
        },
        toggleShowFilename() {
            this.prefs.showFilename = !this.prefs.showFilename;
        },
        toggleShowRating() {
            this.prefs.showRating = !this.prefs.showRating;
        },
        toggleShowTagsBelow() {
            this.prefs.showTagsBelow = !this.prefs.showTagsBelow;
        },
        toggleShowTimestamp() {
            this.prefs.showTimestamp = !this.prefs.showTimestamp;
        },
        toggleShowPath() {
            this.prefs.showPath = !this.prefs.showPath;
        },
        toggleShowTagId() {
            this.prefs.showTagId = !this.prefs.showTagId;
        },
        toggleShowImageId() {
            this.prefs.showImageId = !this.prefs.showImageId;
        },
        toggleShowCameraInfo() {
            this.prefs.showCameraInfo = !this.prefs.showCameraInfo;
        },
    }
});
