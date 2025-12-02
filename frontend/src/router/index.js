import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import CatalogView from '../views/CatalogView.vue';
import RoomView from '../views/RoomView.vue';
import RequestsView from '../views/RequestsView.vue';
import RestaurantView from '../views/RestaurantView.vue';
import SpaView from '../views/SpaView.vue';
import MapView from '../views/MapView.vue';
import GuideView from '../views/GuideView.vue';
import NewsView from '../views/NewsView.vue';
import ChatView from '../views/ChatView.vue';
import LoginView from '../views/LoginView.vue';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'home', component: HomeView },
    { path: '/catalog', name: 'catalog', component: CatalogView },
    { path: '/room', name: 'room', component: RoomView },
    { path: '/requests', name: 'requests', component: RequestsView },
    { path: '/restaurant', name: 'restaurant', component: RestaurantView },
    { path: '/spa', name: 'spa', component: SpaView },
    { path: '/map', name: 'map', component: MapView },
    { path: '/guide', name: 'guide', component: GuideView },
    { path: '/news', name: 'news', component: NewsView },
    { path: '/chat', name: 'chat', component: ChatView },
    { path: '/login', name: 'login', component: LoginView }
  ]
});

export default router;
