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
import AdminDashboard from '../views/admin/AdminDashboard.vue';
import AdminServices from '../views/admin/AdminServices.vue';
import AdminRequests from '../views/admin/AdminRequests.vue';
import AdminRooms from '../views/admin/AdminRooms.vue';
import AdminRestaurant from '../views/admin/AdminRestaurant.vue';
import AdminSpa from '../views/admin/AdminSpa.vue';
import AdminMap from '../views/admin/AdminMap.vue';
import AdminGuide from '../views/admin/AdminGuide.vue';
import AdminPromotions from '../views/admin/AdminPromotions.vue';
import AdminNews from '../views/admin/AdminNews.vue';
import AdminSettings from '../views/admin/AdminSettings.vue';
import AdminUsers from '../views/admin/AdminUsers.vue';
import InstallView from '../views/InstallView.vue';

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
    { path: '/login', name: 'login', component: LoginView },
    { path: '/install', name: 'install', component: InstallView },
    { path: '/admin', redirect: '/admin/dashboard' },
    { path: '/admin/dashboard', name: 'admin-dashboard', component: AdminDashboard, meta: { layout: 'admin' } },
    { path: '/admin/services', name: 'admin-services', component: AdminServices, meta: { layout: 'admin' } },
    { path: '/admin/requests', name: 'admin-requests', component: AdminRequests, meta: { layout: 'admin' } },
    { path: '/admin/rooms', name: 'admin-rooms', component: AdminRooms, meta: { layout: 'admin' } },
    { path: '/admin/restaurant', name: 'admin-restaurant', component: AdminRestaurant, meta: { layout: 'admin' } },
    { path: '/admin/spa', name: 'admin-spa', component: AdminSpa, meta: { layout: 'admin' } },
    { path: '/admin/map', name: 'admin-map', component: AdminMap, meta: { layout: 'admin' } },
    { path: '/admin/guide', name: 'admin-guide', component: AdminGuide, meta: { layout: 'admin' } },
    { path: '/admin/promotions', name: 'admin-promotions', component: AdminPromotions, meta: { layout: 'admin' } },
    { path: '/admin/news', name: 'admin-news', component: AdminNews, meta: { layout: 'admin' } },
    { path: '/admin/settings', name: 'admin-settings', component: AdminSettings, meta: { layout: 'admin' } },
    { path: '/admin/users', name: 'admin-users', component: AdminUsers, meta: { layout: 'admin' } }
  ]
});

export default router;
