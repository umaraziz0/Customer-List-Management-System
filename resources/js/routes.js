import Admin from "./Admin.vue";
import Agent from "./Agent.vue";

const routes = [
    {
        path: "/admin",
        name: "Admin",
        component: Admin
    },
    {
        path: "/agent",
        name: "Agent",
        component: Agent
    }
];

export default routes;
