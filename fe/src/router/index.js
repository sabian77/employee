import { createMemoryHistory, createRouter } from 'vue-router';

import Home from '../views/Home.vue';
import Login from '../views/Login.vue';
import Division from '../views/Division.vue';

const routes = [
  { path: '/', component: Home },
  { path: '/login', component: Login },
  { path: '/division', component: Division },
];

const router = createRouter({
  history: createMemoryHistory(),
  routes,
});

export default router;
