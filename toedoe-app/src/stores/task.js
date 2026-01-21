import {defineStore} from "pinia";

export const useTaskStore = defineStore("taskStore", {
    state: () => ({
        tasks: [{
            id: 1,
            name: "First Task",
            is_completed: false
        },
            {
                id: 2,
                name: "Second Task",
                is_completed: true
            }],
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
    }
})
