import api from "@/http/api";

const apiPath = import.meta.env.VITE_API_PATH
const resource = apiPath + "/summaries"

export const taskSummary = () => api.get(resource)
