import {defineStore} from "pinia";
import { allTasks } from "../http/task-api.js"

export const useTaskStore = defineStore("taskStore", {
    state: () => ({
        tasks: [],
        task: {
            id: 1,
            name: "First Task",
            is_completed: false
        }
    }),
    getters: {
        //forma 1 de fazer o getter
        completedTasks: (state) => state.tasks.filter(task => task.is_completed),
        //forma 2 de fazer o getter
        uncompletedTasks () {
            return this.tasks.filter(task => !task.is_completed)
        }
    },
    actions: {
        async fetchAllTasks() {
            const{ data } = await allTasks()
            this.tasks = data.data
        }
    }
})
