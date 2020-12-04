import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

const Overview = () => import('./views/Overview');

/**
 * Users
 */
const UsersIndex  = () => import('./views/users/Index');
const UsersCreate = () => import('./views/users/Create');
const UsersView   = () => import('./views/users/View');
const UsersEdit   = () => import('./views/users/Edit');

export default new Router({
    mode: 'history',
    routes: [
        // Overiew
        { path: '/', name: 'overview', component: Overview },

        // Users
        { path: '/users', name: 'users.index', component: UsersIndex },
        { path: '/users/create', name: 'users.create', component: UsersCreate },
        { path: '/users/:id', name: 'users.view', component: UsersView },
        { path: '/users/:id/edit', name: 'users.edit', component: UsersEdit }
    ]
});
