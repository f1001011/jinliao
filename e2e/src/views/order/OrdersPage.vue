<template>
  <PageContainer title="My Orders" :showBack="false" :pullRefresh="true" @refresh="handleRefresh">
    <div class="orders-page">
      <!-- 未登录状态 -->
      <div v-if="!isLoggedIn" class="orders-page__not-logged-in">
        <div class="orders-page__not-logged-in-icon">
          <i class="fas fa-receipt"></i>
        </div>
        <h2 class="orders-page__not-logged-in-title">You're not logged in</h2>
        <p class="orders-page__not-logged-in-text">
          Login to view your order history and track current orders.
        </p>
        <button class="orders-page__login-btn" @click="navigateToLogin">Login / Register</button>
      </div>

      <!-- 已登录状态 -->
      <template v-else>
        <!-- 订单标签页 -->
        <div class="orders-page__tabs">
          <div
            class="orders-page__tab"
            :class="{ active: activeTab === 'current' }"
            @click="activeTab = 'current'"
          >
            Current
          </div>
          <div
            class="orders-page__tab"
            :class="{ active: activeTab === 'past' }"
            @click="activeTab = 'past'"
          >
            Past
          </div>
        </div>

        <!-- 当前订单 -->
        <div v-if="activeTab === 'current'" class="orders-page__orders">
          <div v-if="currentOrders.length > 0">
            <div
              v-for="order in currentOrders"
              :key="order.id"
              class="orders-page__order-card"
              @click="navigateToOrderDetail(order.id)"
            >
              <div class="orders-page__order-header">
                <div class="orders-page__restaurant-info">
                  <img
                    :src="order.restaurantImage"
                    :alt="order.restaurantName"
                    class="orders-page__restaurant-image"
                  />
                  <div>
                    <h3 class="orders-page__restaurant-name">{{ order.restaurantName }}</h3>
                    <p class="orders-page__order-date">{{ formatDate(order.orderDate) }}</p>
                  </div>
                </div>
                <div :class="['orders-page__status', `orders-page__status--${order.status}`]">
                  {{ formatStatus(order.status) }}
                </div>
              </div>

              <div class="orders-page__order-items">
                <div
                  class="orders-page__order-item"
                  v-for="(item, index) in order.items"
                  :key="index"
                >
                  <span class="orders-page__item-quantity">{{ item.quantity }}x</span>
                  <span class="orders-page__item-name">{{ item.name }}</span>
                </div>
              </div>

              <div class="orders-page__order-footer">
                <div class="orders-page__order-total">
                  <span>Total:</span>
                  <span class="orders-page__total-amount">${{ order.total.toFixed(2) }}</span>
                </div>

                <div class="orders-page__order-actions">
                  <button
                    v-if="order.status === 'preparing' || order.status === 'ready'"
                    class="orders-page__track-btn"
                    @click.stop="trackOrder(order.id)"
                  >
                    <i class="fas fa-map-marker-alt"></i>
                    Track Order
                  </button>

                  <button
                    v-if="order.status === 'delivered'"
                    class="orders-page__reorder-btn"
                    @click.stop="reorderItems(order)"
                  >
                    <i class="fas fa-redo-alt"></i>
                    Reorder
                  </button>
                </div>
              </div>

              <div v-if="order.status === 'preparing'" class="orders-page__progress">
                <div class="orders-page__progress-bar">
                  <div class="orders-page__progress-fill" :style="{ width: '30%' }"></div>
                </div>
                <div class="orders-page__progress-steps">
                  <div class="orders-page__progress-step completed">
                    <div class="orders-page__step-dot"></div>
                    <span>Order Placed</span>
                  </div>
                  <div class="orders-page__progress-step active">
                    <div class="orders-page__step-dot"></div>
                    <span>Preparing</span>
                  </div>
                  <div class="orders-page__progress-step">
                    <div class="orders-page__step-dot"></div>
                    <span>On the Way</span>
                  </div>
                  <div class="orders-page__progress-step">
                    <div class="orders-page__step-dot"></div>
                    <span>Delivered</span>
                  </div>
                </div>
              </div>

              <div v-if="order.status === 'ready'" class="orders-page__progress">
                <div class="orders-page__progress-bar">
                  <div class="orders-page__progress-fill" :style="{ width: '65%' }"></div>
                </div>
                <div class="orders-page__progress-steps">
                  <div class="orders-page__progress-step completed">
                    <div class="orders-page__step-dot"></div>
                    <span>Order Placed</span>
                  </div>
                  <div class="orders-page__progress-step completed">
                    <div class="orders-page__step-dot"></div>
                    <span>Preparing</span>
                  </div>
                  <div class="orders-page__progress-step active">
                    <div class="orders-page__step-dot"></div>
                    <span>On the Way</span>
                  </div>
                  <div class="orders-page__progress-step">
                    <div class="orders-page__step-dot"></div>
                    <span>Delivered</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- 无当前订单 -->
          <div v-else class="orders-page__empty">
            <div class="orders-page__empty-icon">
              <i class="fas fa-clipboard-list"></i>
            </div>
            <h3 class="orders-page__empty-title">No Current Orders</h3>
            <p class="orders-page__empty-text">You don't have any active orders at the moment.</p>
            <button class="orders-page__browse-btn" @click="navigateToHome">
              Browse Restaurants
            </button>
          </div>
        </div>

        <!-- 历史订单 -->
        <div v-if="activeTab === 'past'" class="orders-page__orders">
          <div v-if="pastOrders.length > 0">
            <div
              v-for="order in pastOrders"
              :key="order.id"
              class="orders-page__order-card"
              @click="navigateToOrderDetail(order.id)"
            >
              <div class="orders-page__order-header">
                <div class="orders-page__restaurant-info">
                  <img
                    :src="order.restaurantImage"
                    :alt="order.restaurantName"
                    class="orders-page__restaurant-image"
                  />
                  <div>
                    <h3 class="orders-page__restaurant-name">{{ order.restaurantName }}</h3>
                    <p class="orders-page__order-date">{{ formatDate(order.orderDate) }}</p>
                  </div>
                </div>
                <div :class="['orders-page__status', `orders-page__status--${order.status}`]">
                  {{ formatStatus(order.status) }}
                </div>
              </div>

              <div class="orders-page__order-items">
                <div
                  class="orders-page__order-item"
                  v-for="(item, index) in order.items.slice(0, 2)"
                  :key="index"
                >
                  <span class="orders-page__item-quantity">{{ item.quantity }}x</span>
                  <span class="orders-page__item-name">{{ item.name }}</span>
                </div>
                <div v-if="order.items.length > 2" class="orders-page__more-items">
                  +{{ order.items.length - 2 }} more items
                </div>
              </div>

              <div class="orders-page__order-footer">
                <div class="orders-page__order-total">
                  <span>Total:</span>
                  <span class="orders-page__total-amount">${{ order.total.toFixed(2) }}</span>
                </div>

                <div class="orders-page__order-actions">
                  <button class="orders-page__reorder-btn" @click.stop="reorderItems(order)">
                    <i class="fas fa-redo-alt"></i>
                    Reorder
                  </button>
                </div>
              </div>
            </div>

            <!-- 加载更多按钮 -->
            <div v-if="hasMore && !isLoading" class="orders-page__load-more">
              <button class="orders-page__load-more-btn" @click="loadMoreOrders">
                Load More Orders
              </button>
            </div>

            <!-- 加载中指示器 -->
            <div v-if="isLoading" class="orders-page__loading">
              <i class="fas fa-spinner fa-spin"></i>
              <span>Loading more orders...</span>
            </div>
          </div>

          <!-- 无历史订单 -->
          <div v-else class="orders-page__empty">
            <div class="orders-page__empty-icon">
              <i class="fas fa-history"></i>
            </div>
            <h3 class="orders-page__empty-title">No Order History</h3>
            <p class="orders-page__empty-text">You haven't placed any orders yet.</p>
            <button class="orders-page__browse-btn" @click="navigateToHome">
              Browse Restaurants
            </button>
          </div>
        </div>
      </template>
    </div>
  </PageContainer>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import PageContainer from '@/components/layout/PageContainer.vue'
import { useUserStore } from '@/stores/modules/user'
import { useCartStore } from '@/stores/modules/cart'

// 路由
const router = useRouter()

// Store
const userStore = useUserStore()
const cartStore = useCartStore()

// 用户登录状态
const isLoggedIn = computed(() => userStore.isLoggedIn)

// 标签页状态
const activeTab = ref('current')

// 加载状态
const isLoading = ref(false)
const hasMore = ref(true)
const page = ref(1)

// 订单类型定义
interface OrderItem {
  name: string
  quantity: number
  price: number
}

interface Order {
  id: number
  restaurantName: string
  restaurantImage: string
  orderDate: string
  status: 'placed' | 'preparing' | 'ready' | 'delivered' | 'cancelled'
  items: OrderItem[]
  total: number
}

// 模拟订单数据
const currentOrders = ref<Order[]>([
  {
    id: 1001,
    restaurantName: 'Burger Palace',
    restaurantImage:
      'https://images.unsplash.com/photo-1561758033-d89a9ad46330?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    orderDate: '2023-05-01T14:30:00',
    status: 'preparing',
    items: [
      { name: 'Classic Cheeseburger', quantity: 2, price: 8.99 },
      { name: 'French Fries', quantity: 1, price: 3.99 },
      { name: 'Chocolate Milkshake', quantity: 1, price: 4.99 },
    ],
    total: 26.96,
  },
  {
    id: 1002,
    restaurantName: 'Pizza Heaven',
    restaurantImage:
      'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    orderDate: '2023-05-01T15:45:00',
    status: 'ready',
    items: [
      { name: 'Margherita Pizza', quantity: 1, price: 12.99 },
      { name: 'Garlic Bread', quantity: 1, price: 4.99 },
      { name: 'Coke', quantity: 2, price: 1.99 },
    ],
    total: 21.96,
  },
])

const pastOrders = ref<Order[]>([
  {
    id: 998,
    restaurantName: 'Sushi World',
    restaurantImage:
      'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    orderDate: '2023-04-28T19:20:00',
    status: 'delivered',
    items: [
      { name: 'California Roll', quantity: 2, price: 10.99 },
      { name: 'Miso Soup', quantity: 1, price: 3.99 },
      { name: 'Green Tea', quantity: 1, price: 2.49 },
    ],
    total: 28.46,
  },
  {
    id: 997,
    restaurantName: 'Taco Fiesta',
    restaurantImage:
      'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    orderDate: '2023-04-25T12:15:00',
    status: 'delivered',
    items: [
      { name: 'Beef Taco', quantity: 3, price: 7.99 },
      { name: 'Nachos', quantity: 1, price: 6.99 },
      { name: 'Guacamole', quantity: 1, price: 3.99 },
      { name: 'Mexican Beer', quantity: 2, price: 4.99 },
    ],
    total: 44.92,
  },
  {
    id: 996,
    restaurantName: 'Healthy Greens',
    restaurantImage:
      'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    orderDate: '2023-04-20T13:40:00',
    status: 'cancelled',
    items: [
      { name: 'Caesar Salad', quantity: 1, price: 9.99 },
      { name: 'Avocado Toast', quantity: 1, price: 8.99 },
      { name: 'Fruit Smoothie', quantity: 1, price: 5.99 },
    ],
    total: 24.97,
  },
])

// 格式化日期
const formatDate = (dateString: string): string => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
    hour: 'numeric',
    minute: 'numeric',
  })
}

// 格式化订单状态
const formatStatus = (status: string): string => {
  switch (status) {
    case 'placed':
      return 'Order Placed'
    case 'preparing':
      return 'Preparing'
    case 'ready':
      return 'On the Way'
    case 'delivered':
      return 'Delivered'
    case 'cancelled':
      return 'Cancelled'
    default:
      return status
  }
}

// 导航到订单详情
const navigateToOrderDetail = (orderId: number) => {
  router.push(`/order/${orderId}`)
}

// 追踪订单
const trackOrder = (orderId: number) => {
  router.push(`/order/${orderId}/track`)
}

// 重新下单
const reorderItems = (order: Order) => {
  // 清空当前购物车
  cartStore.clearCart()

  // 将订单中的商品添加到购物车
  order.items.forEach((item) => {
    cartStore.addItem({
      id: `reorder-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`,
      name: item.name,
      price: item.price,
      quantity: item.quantity,
      image: order.restaurantImage, // 使用餐厅图片作为默认图片
      restaurantId: `restaurant-${order.id}`,
      restaurantName: order.restaurantName,
    })
  })

  // 导航到结账页面
  router.push('/checkout')
}

// 加载更多订单
const loadMoreOrders = () => {
  if (isLoading.value) return

  isLoading.value = true
  page.value += 1

  // 模拟加载更多数据
  setTimeout(() => {
    // 假设第2页有这些订单
    if (page.value === 2) {
      const moreOrders: Order[] = [
        {
          id: 995,
          restaurantName: 'Burger Palace',
          restaurantImage:
            'https://images.unsplash.com/photo-1561758033-d89a9ad46330?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
          orderDate: '2023-04-15T18:30:00',
          status: 'delivered',
          items: [
            { name: 'Double Bacon Burger', quantity: 1, price: 12.99 },
            { name: 'Onion Rings', quantity: 1, price: 4.99 },
            { name: 'Soda', quantity: 1, price: 1.99 },
          ],
          total: 19.97,
        },
        {
          id: 994,
          restaurantName: 'Pizza Heaven',
          restaurantImage:
            'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
          orderDate: '2023-04-10T20:15:00',
          status: 'delivered',
          items: [
            { name: 'Pepperoni Pizza', quantity: 1, price: 14.99 },
            { name: 'Cheesy Bread', quantity: 1, price: 5.99 },
            { name: 'Buffalo Wings', quantity: 1, price: 8.99 },
          ],
          total: 29.97,
        },
      ]

      pastOrders.value = [...pastOrders.value, ...moreOrders]
    } else {
      // 假设第3页后没有更多数据
      hasMore.value = false
    }

    isLoading.value = false
  }, 1000)
}

// 刷新处理
const handleRefresh = () => {
  // 重置页码
  page.value = 1
  hasMore.value = true

  // 模拟刷新数据
  isLoading.value = true
  setTimeout(() => {
    // 假设刷新后数据有变化
    currentOrders.value = [
      {
        id: 1001,
        restaurantName: 'Burger Palace',
        restaurantImage:
          'https://images.unsplash.com/photo-1561758033-d89a9ad46330?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
        orderDate: '2023-05-01T14:30:00',
        status: 'preparing',
        items: [
          { name: 'Classic Cheeseburger', quantity: 2, price: 8.99 },
          { name: 'French Fries', quantity: 1, price: 3.99 },
          { name: 'Chocolate Milkshake', quantity: 1, price: 4.99 },
        ],
        total: 26.96,
      },
      {
        id: 1002,
        restaurantName: 'Pizza Heaven',
        restaurantImage:
          'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
        orderDate: '2023-05-01T15:45:00',
        status: 'ready',
        items: [
          { name: 'Margherita Pizza', quantity: 1, price: 12.99 },
          { name: 'Garlic Bread', quantity: 1, price: 4.99 },
          { name: 'Coke', quantity: 2, price: 1.99 },
        ],
        total: 21.96,
      },
    ]

    // 重置历史订单
    pastOrders.value = [
      {
        id: 998,
        restaurantName: 'Sushi World',
        restaurantImage:
          'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
        orderDate: '2023-04-28T19:20:00',
        status: 'delivered',
        items: [
          { name: 'California Roll', quantity: 2, price: 10.99 },
          { name: 'Miso Soup', quantity: 1, price: 3.99 },
          { name: 'Green Tea', quantity: 1, price: 2.49 },
        ],
        total: 28.46,
      },
      {
        id: 997,
        restaurantName: 'Taco Fiesta',
        restaurantImage:
          'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
        orderDate: '2023-04-25T12:15:00',
        status: 'delivered',
        items: [
          { name: 'Beef Taco', quantity: 3, price: 7.99 },
          { name: 'Nachos', quantity: 1, price: 6.99 },
          { name: 'Guacamole', quantity: 1, price: 3.99 },
          { name: 'Mexican Beer', quantity: 2, price: 4.99 },
        ],
        total: 44.92,
      },
      {
        id: 996,
        restaurantName: 'Healthy Greens',
        restaurantImage:
          'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
        orderDate: '2023-04-20T13:40:00',
        status: 'cancelled',
        items: [
          { name: 'Caesar Salad', quantity: 1, price: 9.99 },
          { name: 'Avocado Toast', quantity: 1, price: 8.99 },
          { name: 'Fruit Smoothie', quantity: 1, price: 5.99 },
        ],
        total: 24.97,
      },
    ]

    isLoading.value = false
  }, 1000)
}

// 导航方法
const navigateToLogin = () => {
  router.push('/login')
}

const navigateToHome = () => {
  router.push('/')
}

// 生命周期钩子
onMounted(() => {
  // 可以在这里添加初始化逻辑，例如从API获取订单数据
})
</script>

<style lang="scss" scoped>
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.orders-page {
  min-height: 100vh;
  background-color: #f8f9fa; // 直接使用具体颜色值

  &__not-logged-in {
    padding: $spacing-xl;
    text-align: center;
    background-color: $white;
    border-radius: $border-radius-md;
    margin: $spacing-md;
  }

  &__not-logged-in-icon {
    font-size: 64px;
    color: $gray-400;
    margin-bottom: $spacing-md;
  }

  &__not-logged-in-title {
    font-size: $font-size-lg;
    font-weight: $font-weight-semibold;
    margin-bottom: $spacing-sm;
    color: $text-primary;
  }

  &__not-logged-in-text {
    font-size: $font-size-sm;
    color: $text-secondary;
    margin-bottom: $spacing-lg;
    line-height: 1.5;
  }

  &__login-btn {
    width: 100%;
    height: 48px;
    background-color: $primary;
    color: $white;
    border: none;
    border-radius: $border-radius-md;
    font-size: $font-size-sm;
    font-weight: $font-weight-semibold;
    cursor: pointer;
    transition: background-color $transition-base;

    &:hover {
      background-color: darken($primary, 10%);
    }
  }

  &__tabs {
    display: flex;
    background-color: $white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    position: sticky;
    top: 0;
    z-index: 10;
  }

  &__tab {
    flex: 1;
    text-align: center;
    padding: $spacing-md;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    color: $text-secondary;
    position: relative;
    cursor: pointer;

    &.active {
      color: $primary;

      &:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background-color: $primary;
      }
    }
  }

  &__orders {
    padding: $spacing-md;
  }

  &__order-card {
    background-color: $white;
    border-radius: $border-radius-md;
    margin-bottom: $spacing-md;
    overflow: hidden;
    box-shadow: $shadow-sm;
  }

  &__order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: $spacing-md;
    border-bottom: 1px solid $gray-200;
  }

  &__restaurant-info {
    display: flex;
    align-items: center;
  }

  &__restaurant-image {
    width: 40px;
    height: 40px;
    border-radius: $border-radius-sm;
    object-fit: cover;
    margin-right: $spacing-sm;
  }

  &__restaurant-name {
    font-size: $font-size-sm;
    font-weight: $font-weight-semibold;
    margin: 0 0 2px;
    color: $text-primary;
  }

  &__order-date {
    font-size: $font-size-xxs;
    color: $text-secondary;
    margin: 0;
  }

  &__status {
    font-size: $font-size-xxs;
    font-weight: $font-weight-medium;
    padding: $spacing-xxs $spacing-xs;
    border-radius: $border-radius-pill;

    &--placed {
      background-color: $gray-200;
      color: $text-secondary;
    }

    &--preparing {
      background-color: rgba($warning, 0.1);
      color: $warning;
    }

    &--ready {
      background-color: rgba($info, 0.1);
      color: $info;
    }

    &--delivered {
      background-color: rgba($success, 0.1);
      color: $success;
    }

    &--cancelled {
      background-color: rgba($danger, 0.1);
      color: $danger;
    }
  }

  &__order-items {
    padding: $spacing-md;
  }

  &__order-item {
    display: flex;
    margin-bottom: $spacing-xs;

    &:last-child {
      margin-bottom: 0;
    }
  }

  &__item-quantity {
    font-size: $font-size-xs;
    font-weight: $font-weight-medium;
    color: $text-primary;
    margin-right: $spacing-xs;
  }

  &__item-name {
    font-size: $font-size-xs;
    color: $text-secondary;
  }

  &__more-items {
    font-size: $font-size-xs;
    color: $text-secondary;
    margin-top: $spacing-xs;
  }

  &__order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: $spacing-md;
    border-top: 1px solid $gray-200;
  }

  &__order-total {
    font-size: $font-size-sm;
    color: $text-secondary;

    .orders-page__total-amount {
      font-weight: $font-weight-semibold;
      color: $text-primary;
      margin-left: $spacing-xs;
    }
  }

  &__order-actions {
    display: flex;
    gap: $spacing-xs;
  }

  &__track-btn,
  &__reorder-btn {
    padding: $spacing-xs $spacing-sm;
    font-size: $font-size-xs;
    font-weight: $font-weight-medium;
    border-radius: $border-radius-md;
    display: flex;
    align-items: center;
    cursor: pointer;

    i {
      margin-right: 4px;
    }
  }

  &__track-btn {
    background-color: rgba($info, 0.1);
    color: $info;
    border: 1px solid rgba($info, 0.2);

    &:hover {
      background-color: rgba($info, 0.15);
    }
  }

  &__reorder-btn {
    background-color: rgba($primary, 0.1);
    color: $primary;
    border: 1px solid rgba($primary, 0.2);

    &:hover {
      background-color: rgba($primary, 0.15);
    }
  }

  &__progress {
    padding: 0 $spacing-md $spacing-md;
  }

  &__progress-bar {
    height: 4px;
    background-color: $gray-200;
    border-radius: $border-radius-pill;
    margin-bottom: $spacing-sm;
    position: relative;
    overflow: hidden;
  }

  &__progress-fill {
    position: absolute;
    height: 100%;
    left: 0;
    top: 0;
    background-color: $primary;
    border-radius: $border-radius-pill;
  }

  &__progress-steps {
    display: flex;
    justify-content: space-between;
  }

  &__progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    flex: 1;

    &:not(:last-child):after {
      content: '';
      position: absolute;
      top: 8px;
      left: 50%;
      right: -50%;
      height: 1px;
      background-color: $gray-200;
      z-index: 0;
    }

    &.completed {
      .orders-page__step-dot {
        background-color: $primary;
      }

      &:not(:last-child):after {
        background-color: $primary;
      }
    }

    &.active {
      .orders-page__step-dot {
        border-color: $primary;
      }

      span {
        color: $primary;
        font-weight: $font-weight-medium;
      }
    }
  }

  &__step-dot {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background-color: $white;
    border: 2px solid $gray-300;
    margin-bottom: $spacing-xxs;
    z-index: 1;
  }

  &__progress-step span {
    font-size: $font-size-xxs;
    color: $text-secondary;
    text-align: center;
  }

  &__empty {
    text-align: center;
    padding: $spacing-xl 0;
    background-color: $white;
    border-radius: $border-radius-md;
    margin-bottom: $spacing-md;
  }

  &__empty-icon {
    font-size: 48px;
    color: $gray-400;
    margin-bottom: $spacing-md;
  }

  &__empty-title {
    font-size: $font-size-lg;
    font-weight: $font-weight-semibold;
    color: $text-primary;
    margin: 0 0 $spacing-sm;
  }

  &__empty-text {
    font-size: $font-size-sm;
    color: $text-secondary;
    margin: 0 0 $spacing-lg;
    line-height: 1.5;
  }

  &__browse-btn {
    padding: $spacing-sm $spacing-lg;
    background-color: $primary;
    color: $white;
    border: none;
    border-radius: $border-radius-md;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    cursor: pointer;

    &:hover {
      background-color: darken($primary, 10%);
    }
  }

  &__load-more {
    text-align: center;
    margin-top: $spacing-md;
  }

  &__load-more-btn {
    padding: $spacing-sm $spacing-lg;
    background-color: $white;
    border: 1px solid $primary;
    border-radius: $border-radius-md;
    color: $primary;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    cursor: pointer;

    &:hover {
      background-color: rgba($primary, 0.05);
    }
  }

  &__loading {
    text-align: center;
    padding: $spacing-md;
    color: $text-secondary;
    font-size: $font-size-sm;

    i {
      margin-right: $spacing-xs;
    }
  }
}
</style>
