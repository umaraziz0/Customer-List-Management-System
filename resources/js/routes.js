import Admin from "./Admin.vue";
import Agent from "./Agent.vue";
import FollowUpList from "./components/FollowUpList.vue";

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
    },
    {
        path: "/agent/messages",
        name: "FollowUpList",
        component: FollowUpList
    }
];

export default routes;
