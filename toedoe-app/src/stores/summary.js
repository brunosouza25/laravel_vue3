import { ref } from "vue";
import { defineStore } from "pinia"
import { taskSummary } from "../http/summary-api.js"

export const useSummaryStore = defineStore("summaryStore", () => {
    const summaries = ref([])


    const fetchTaskSummary = async () => {
        const { data } = await taskSummary()
        summaries.value = data

    }

    return {
        summaries,
        fetchTaskSummary
    }
})
