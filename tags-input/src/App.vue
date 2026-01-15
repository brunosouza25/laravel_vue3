<script>
import TagsInput from "./components/TagsInput.vue";

export default {
    data: () => ({
        jsFrameworks: ["react.js", "vue.js", "angular"],
        backEndFrameworks: []
    }),

    methods: {
        handleChange(tags){
            this.jsFrameworks = [...tags]
        }
    }

}
</script>

<template>
    <h1>Your favorite frameworks</h1>
    <div>{{ jsFrameworks }}</div>
    <tags-input :selected-tags="jsFrameworks" @change="handleChange"/>

    <div>{{ backEndFrameworks }}</div>
    <tags-input :selected-tags="backEndFrameworks" @change="backEndFrameworks = [...$event]"/>

</template>

<style>
</style>
