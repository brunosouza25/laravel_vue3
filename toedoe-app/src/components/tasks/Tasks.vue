<template>
    <div class="card mt-2" v-if="tasks.length && show">
        <ul class="list-group list-group-flush">
            <Task v-for="task in tasks" :task="task" :key="task.id"
                  @completed="handleCompletedTask"
                  @updated="handleUpdatedTask"
                  @removed="handleRemovedTask"
            />

        </ul>
    </div>
</template>

<script setup>
import { useTaskStore } from "../../stores/task.js"
const store = useTaskStore()
const { handleUpdatedTask, handleCompletedTask, handleRemovedTask } = store

import Task from "@/components/tasks/Task.vue";
defineProps({
    tasks: Array,
    show: {
        type: Boolean,
        default: true
    }
})
</script>
