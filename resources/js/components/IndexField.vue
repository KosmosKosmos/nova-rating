<template>
    <star-rating
            :id="field.name"
            :name="field.name"
            v-model="value"
            :increment="increment"
            :max-rating="max"
            :show-rating="showRating"
            :read-only="true"

            :star-size="field['star-size']"
            :inactive-color="field['inactive-color']"
            :active-color="field['active-color']"
            :border-color="field['border-color']"
            :border-width="field['border-width']"
            :padding="field['padding']"
            :rounded-corners="field['rounded-corners']"
            :inline="field['inline']"
            :glow="field['glow']"
            :glow-color="field['glow-color']"
            :text-class="field['text-class']"
    />
</template>

<script>
    import StarRating from 'vue-star-rating'

    export default {
        props: ['resourceName', 'resourceId', 'field'],
        components: { StarRating },
        data: () => ({
            value: 0,
        }),
        mounted() {
            this.value = parseFloat(this.field.value) || 0
        },
        computed: {
            increment() {
                return parseFloat(this.field.increment) || .5;
            },
            max() {
                return parseInt(this.field.max);
            },
            min() {
                return parseFloat(this.field.min);
            },
            showRating() {
                return !!this.field.showRating;
            }
        }
    }
</script>
