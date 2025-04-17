import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'
import { useUserStore } from '@/stores/modules/user'

// 定义路由
const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'Home',
    component: () => import('@/views/home/HomePage.vue'),
    meta: {
      title: 'COKFOOD - Food Delivery',
      keepAlive: true,
    },
  },
  {
    path: '/categories',
    name: 'Categories',
    component: () => import('@/views/category/CategoryPage.vue'),
    meta: {
      title: 'Food Categories',
      keepAlive: true,
    },
  },
  {
    path: '/restaurant/:id',
    name: 'Restaurant',
    component: () => import('@/views/restaurant/RestaurantPage.vue'),
    meta: {
      title: 'Restaurant Details',
    },
  },
  {
    path: '/product/:id',
    name: 'Product',
    component: () => import('@/views/product/ProductPage.vue'),
    meta: {
      title: 'Product Details',
    },
  },
  {
    path: '/search',
    name: 'Search',
    component: () => import('@/views/search/SearchPage.vue'),
    meta: {
      title: 'Search',
    },
  },
  {
    path: '/checkout',
    name: 'Checkout',
    component: () => import('@/views/order/CheckoutPage.vue'),
    meta: {
      title: 'Checkout',
    },
  },
  {
    path: '/orders',
    name: 'Orders',
    component: () => import('@/views/order/OrdersListPage.vue'),
    meta: {
      title: 'My Orders',
      requiresAuth: true,
    },
  },
  {
    path: '/order/:id',
    name: 'OrderDetail',
    component: () => import('@/views/order/OrderDetailPage.vue'),
    meta: {
      title: 'Order Details',
      requiresAuth: true,
    },
  },
  {
    path: '/payment/:id',
    name: 'Payment',
    component: () => import('@/views/payment/PaymentPage.vue'),
    meta: {
      title: 'Payment',
    },
  },
  {
    path: '/not-available',
    name: 'NotAvailable',
    component: () => import('@/views/error/NotAvailablePage.vue'),
    meta: {
      title: 'Not Available',
    },
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/views/auth/LoginPage.vue'),
    meta: {
      title: 'Login',
    },
  },
  {
    path: '/profile',
    name: 'Profile',
    component: () => import('@/views/profile/ProfilePage.vue'),
    meta: {
      title: 'My Profile',
      requiresAuth: true,
    },
  },
  {
    path: '/distributor/links',
    name: 'DistributorLinks',
    component: () => import('@/views/distributor/LinksPage.vue'),
    meta: {
      title: 'Payment Links',
      requiresAuth: true,
      requiresDistributor: true,
    },
  },
  {
    path: '/examples/button',
    name: 'ButtonExample',
    component: () => import('@/views/examples/ButtonExample.vue'),
    meta: {
      title: 'Button Examples',
    },
  },
  {
    path: '/examples/card',
    name: 'CardExample',
    component: () => import('@/views/examples/CardExample.vue'),
    meta: {
      title: 'Card Examples',
    },
  },
  {
    path: '/examples/badge',
    name: 'BadgeExample',
    component: () => import('@/views/examples/BadgeExample.vue'),
    meta: {
      title: 'Badge Examples',
    },
  },
  {
    path: '/examples/page-container',
    name: 'PageContainerExample',
    component: () => import('@/views/examples/PageContainerExample.vue'),
    meta: {
      title: 'Page Container Example',
    },
  },
  {
    path: '/examples/vant',
    name: 'VantExample',
    component: () => import('@/views/examples/VantExample.vue'),
    meta: {
      title: 'Vant UI Examples',
    },
  },
  {
    path: '/example/chart',
    name: 'ChartExample',
    component: () => import('@/views/examples/ChartExample.vue'),
    meta: {
      title: 'Chart Examples',
    },
  },
  // 404页面
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('@/views/error/NotFoundPage.vue'),
    meta: {
      title: '404 Not Found',
    },
  },
]

// 创建路由实例
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { top: 0 }
    }
  },
})

// 路由前置守卫，设置页面标题
router.beforeEach((to, from, next) => {
  // 设置页面标题
  document.title = (to.meta.title as string) || 'COKFOOD'

  // 获取用户状态
  const userStore = useUserStore()
  const isLoggedIn = userStore.isLoggedIn
  const isDistributor = userStore.isDistributor

  // 检查是否需要认证
  if (to.meta.requiresAuth && !isLoggedIn) {
    // 记录原始请求的URL，登录后重定向回来
    next({
      path: '/login',
      query: { redirect: to.fullPath },
    })
  }
  // 检查是否需要分销商权限
  else if (to.meta.requiresDistributor && !isDistributor) {
    next('/')
  } else {
    next()
  }
})

export default router
