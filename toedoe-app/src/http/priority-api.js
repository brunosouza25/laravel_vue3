import api from "./api";

const apiPath = import.meta.env.VITE_API_PATH;
const resource = apiPath + "/priorities";

export const allPriorities = () => api.get(resource);
