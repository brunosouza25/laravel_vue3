<template>
    <div class="relative">
        <input
            ref="inputRef"
            type="text"
            class="form-control form-control-lg padding-right-lg"
            placeholder="+ Add new task. Press enter to save."
            @keydown.enter="addNewTask"
        />
        <div class="select-priority">
            <SelectPriority @change="setPriority" />
        </div>

    </div>

</template>

<script setup>
import { reactive, ref } from "vue"
import { useTaskStore } from "../../stores/task.js"
import SelectPriority from "./SelectPriority.vue";
const store = useTaskStore();
const { handleAddedTask } = store
const newTask = reactive({
    name: "",
    is_completed: false,
    priority_id: null
});
const addNewTask = async (event) => {
    if(event.target.value.trim()){
        newTask.name = event.target.value
        event.target.value = ""
        await handleAddedTask(newTask)
    }
}

const inputRef = ref()

const setPriority = (id) => {
    newTask.priority_id = id;
    inputRef.value.focus();
};

</script>
<style>
.relative {
    position: relative;
}

.select-priority {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    right: 10px;
    padding-left: 10px;
    z-index: 999;
}
</style>
