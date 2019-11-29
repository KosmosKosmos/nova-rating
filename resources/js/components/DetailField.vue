<template>
    <panel-item :field="field">
        <template slot="value">
            <star-rating
                    :read-only="false"
                    :id="field.name"
                    :name="field.name"
                    v-model="value"
                    :increment="increment"
                    :max-rating="max"
                    :show-rating="showRating"

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
    </panel-item>
</template>

<script>
    import FormField from './FormField';
    export default {
        extends: FormField
    }
</script>
