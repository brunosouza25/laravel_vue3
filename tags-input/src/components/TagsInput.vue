<template>
    <div v-for="(tag, index) in tags">
        {{ index + " : " + tag }}
        <a @click.prevent="removeTag(index)" href="#" :key="ubdex">&times;</a>
    </div>
    <hr>

    <input type="text" v-model.trim="newTag"
           @keydown.enter="addNewTag"
           @keydown.delete="removeLastTag"
           @keydown.tab.prevent="addNewTag"
           :class="{ 'tag-exists' : isTagExists  }"
    />
</template>

<script >
    export default {
        data: () => ({
            tags: ["vue", "react", "angular"],
            newTag: "preact"
        }),
        computed: {
            isTagExists(){
                return this.tags.includes(this.newTag);
            }
        },
        watch: {
            newTag(newVal){
                if (newVal.indexOf(',') > -1){
                    this.newTag = this.newTag.slice(0, -1)
                    this.addNewTag()
                }
            }
        },
        methods: {
            addNewTag () {
                if (this.newTag && !this.isTagExists){
                    this.tags.push(this.newTag)
                    this.newTag = ""
                }
            },
            removeTag(index) {
                this.tags.splice(index, 1);
            },
            removeLastTag(){
                if (this.newTag.length === 0){
                    this.removeTag(this.tags.length - 1)
                }
            }
        }
    }
</script>

<style scoped>
.tag-exists {
    color: red;
    text-decoration: line-through;
}
</style>
