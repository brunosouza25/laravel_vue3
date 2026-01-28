import { ref } from "vue";
import { defineStore } from "pinia"
import { taskSummary } from "../http/summary-api.js"

export const useSummaryStore = defineStore("summaryStore", () => {
    const summaries = ref([])

    const fetchTaskSummary = async (params = {}) => {
        const { data } = await taskSummary(params)
        summaries.value = data

    }

    return {
        summaries,
        fetchTaskSummary
    }
})
