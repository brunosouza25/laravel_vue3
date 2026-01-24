import api from "./api.js"

export const csrfCookie = () => api.get("/sanctum/csfr-cookie")
export const login = (credentials) => api.post("/auth/login", credentials)
export const register = (user) => api.post("/auth/register", user)
export const logout = () => api.post("/auth/logout")
export const getUser = () => api.get("/api/user")
