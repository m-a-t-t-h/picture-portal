<script setup>
import {useStateStore} from "../../services/state.js";

defineProps({
    photo: Object,
});

const state = useStateStore();
</script>

<template>
    <div v-if="state.prefs.showRating" class="img_rating">
        <div class="img_rating_star" v-if="photo.img_rating===1">
            <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
            <img class="img_rating_star_empty" src="/svg/star-empty.svg" alt="1 star rating">
            <img class="img_rating_star_empty" src="/svg/star-empty.svg" alt="1 star rating">
            <img class="img_rating_star_empty" src="/svg/star-empty.svg" alt="1 star rating">
            <img class="img_rating_star_empty" src="/svg/star-empty.svg" alt="1 star rating">
        </div>
        <div class="img_rating_star" v-else-if="photo.img_rating===2">
            <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
            <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
            <img class="img_rating_star_empty" src="/svg/star-empty.svg" alt="1 star rating">
            <img class="img_rating_star_empty" src="/svg/star-empty.svg" alt="1 star rating">
            <img class="img_rating_star_empty" src="/svg/star-empty.svg" alt="1 star rating">
        </div>
        <div class="img_rating_star" v-else-if="photo.img_rating===3">
            <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
            <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
            <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
            <img class="img_rating_star_empty" src="/svg/star-empty.svg" alt="1 star rating">
            <img class="img_rating_star_empty" src="/svg/star-empty.svg" alt="1 star rating">
        </div>
        <div class="img_rating_star" v-else-if="photo.img_rating===4">
            <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
            <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
            <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
            <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
            <img class="img_rating_star_empty" src="/svg/star-empty.svg" alt="1 star rating">
        </div>
        <div class="img_rating_star" v-else-if="photo.img_rating===5">
            <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
            <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
            <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
            <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
            <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
        </div>
        <div class="img_rating_star" v-else>
            <img class="img_rating_star_empty" src="/svg/star-empty.svg" alt="1 star rating">
            <img class="img_rating_star_empty" src="/svg/star-empty.svg" alt="1 star rating">
            <img class="img_rating_star_empty" src="/svg/star-empty.svg" alt="1 star rating">
            <img class="img_rating_star_empty" src="/svg/star-empty.svg" alt="1 star rating">
            <img class="img_rating_star_empty" src="/svg/star-empty.svg" alt="1 star rating">
        </div>

    </div>
</template>
<style scoped>
@reference "tailwindcss";

.img_rating {
    @apply p-3 min-w-24 max-w-24 w-24 ;

    .img_rating_star {
        @apply flex;
        @apply w-4;
    }

    .img_rating_star_filled {
    }

    .img_rating_star_empty {
    }
}
</style>
