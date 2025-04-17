<template>
  <PageContainer title="Order Details" :showBack="true">
    <div class="order-detail-page">
      <div class="order-detail-page__card">
        <div class="order-detail-page__header">
          <div class="order-detail-page__order-number">Order #{{ orderId }}</div>
          <div
            :class="['order-detail-page__status', `order-detail-page__status--${order?.status}`]"
          >
            {{ formatStatus(order?.status) }}
          </div>
        </div>

        <div class="order-detail-page__info">
          <div class="order-detail-page__info-row">
            <span class="order-detail-page__info-label">Date:</span>
            <span class="order-detail-page__info-value">{{ formatDate(order?.orderDate) }}</span>
          </div>
          <div class="order-detail-page__info-row">
            <span class="order-detail-page__info-label">Restaurant:</span>
            <span class="order-detail-page__info-value">{{ order?.restaurantName }}</span>
          </div>
        </div>

        <div class="order-detail-page__items">
          <div class="order-detail-page__items-header">
            <span>Items</span>
          </div>
          <div class="order-detail-page__item" v-for="(item, index) in order?.items" :key="index">
            <div class="order-detail-page__item-name-qty">
              <span class="order-detail-page__item-quantity">{{ item.quantity }}x</span>
              <span class="order-detail-page__item-name">{{ item.name }}</span>
            </div>
            <span class="order-detail-page__item-price"
              >${{ (item.price * item.quantity).toFixed(2) }}</span
            >
          </div>
        </div>

        <div class="order-detail-page__summary">
          <div class="order-detail-page__summary-row">
            <span>Subtotal</span>
            <span>${{ subtotal.toFixed(2) }}</span>
          </div>
          <div class="order-detail-page__summary-row">
            <span>Delivery Fee</span>
            <span>${{ deliveryFee.toFixed(2) }}</span>
          </div>
          <div class="order-detail-page__summary-row">
            <span>Tax</span>
            <span>${{ tax.toFixed(2) }}</span>
          </div>
          <div class="order-detail-page__summary-row total">
            <span>Total</span>
            <span>${{ order?.total.toFixed(2) }}</span>
          </div>
        </div>

        <div class="order-detail-page__address">
          <h3 class="order-detail-page__section-title">Delivery Address</h3>
          <p class="order-detail-page__address-text">
            123 Main Street, Apt 4B<br />
            New York, NY 10001
          </p>
        </div>

        <div
          v-if="order?.status === 'preparing' || order?.status === 'ready'"
          class="order-detail-page__progress"
        >
          <h3 class="order-detail-page__section-title">Order Status</h3>
          <div class="order-detail-page__progress-bar">
            <div
              class="order-detail-page__progress-fill"
              :style="{ width: progressPercentage }"
            ></div>
          </div>
          <div class="order-detail-page__progress-steps">
            <div
              v-for="(step, index) in progressSteps"
              :key="index"
              :class="[
                'order-detail-page__progress-step',
                { completed: step.completed },
                { active: step.active },
              ]"
            >
              <div class="order-detail-page__step-dot"></div>
              <span>{{ step.label }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="order-detail-page__actions">
        <button
          v-if="order?.status === 'preparing' || order?.status === 'ready'"
          class="order-detail-page__track-btn"
          @click="trackOrder"
        >
          <i class="fas fa-map-marker-alt"></i>
          Track Order
        </button>

        <button
          v-if="order?.status === 'delivered'"
          class="order-detail-page__reorder-btn"
          @click="reorderItems"
        >
          <i class="fas fa-redo-alt"></i>
          Reorder
        </button>

        <button
          v-if="canCancel"
          class="order-detail-page__cancel-btn"
          @click="showCancelConfirmation = true"
        >
          <i class="fas fa-times"></i>
          Cancel Order
        </button>
      </div>
    </div>

    <!-- 取消订单确认对话框 -->
    <div class="cancel-dialog" v-if="showCancelConfirmation">
      <div class="cancel-dialog__backdrop" @click="showCancelConfirmation = false"></div>
      <div class="cancel-dialog__content">
        <h3 class="cancel-dialog__title">Cancel Order?</h3>
        <p class="cancel-dialog__message">
          Are you sure you want to cancel this order? This action cannot be undone.
        </p>
        <div class="cancel-dialog__actions">
          <button
            class="cancel-dialog__btn cancel-dialog__btn--secondary"
            @click="showCancelConfirmation = false"
          >
            No, Keep Order
          </button>
          <button class="cancel-dialog__btn cancel-dialog__btn--primary" @click="cancelOrder">
            Yes, Cancel Order
          </button>
        </div>
      </div>
    </div>
  </PageContainer>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import PageContainer from '@/components/layout/PageContainer.vue'
import { useCartStore } from '@/stores/modules/cart'

// 路由相关
const route = useRoute()
const router = useRouter()
const orderId = route.params.id

// Store
const cartStore = useCartStore()

// 状态
const showCancelConfirmation = ref(false)

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
const order = ref<Order | null>(null)

// 获取订单数据
const fetchOrderData = () => {
  // 模拟API请求
  setTimeout(() => {
    // 根据订单ID返回不同状态的订单
    if (orderId === '1001') {
      order.value = {
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
      }
    } else if (orderId === '1002') {
      order.value = {
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
      }
    } else if (orderId === '998') {
      order.value = {
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
      }
    } else {
      // 默认订单
      order.value = {
        id: parseInt(orderId as string),
        restaurantName: 'Default Restaurant',
        restaurantImage:
          'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
        orderDate: new Date().toISOString(),
        status: 'placed',
        items: [{ name: 'Sample Item', quantity: 1, price: 9.99 }],
        total: 9.99,
      }
    }
  }, 500)
}

// 计算属性
const subtotal = computed(() => {
  if (!order.value) return 0
  return order.value.items.reduce((sum, item) => sum + item.price * item.quantity, 0)
})

const deliveryFee = computed(() => {
  return 2.99
})

const tax = computed(() => {
  return subtotal.value * 0.08 // 假设税率为8%
})

const canCancel = computed(() => {
  return order.value?.status === 'placed' || order.value?.status === 'preparing'
})

// 进度条百分比
const progressPercentage = computed(() => {
  if (!order.value) return '0%'

  switch (order.value.status) {
    case 'placed':
      return '10%'
    case 'preparing':
      return '40%'
    case 'ready':
      return '70%'
    case 'delivered':
      return '100%'
    default:
      return '0%'
  }
})

// 进度步骤
const progressSteps = computed(() => {
  if (!order.value) return []

  const steps = [
    {
      label: 'Order Placed',
      completed: ['placed', 'preparing', 'ready', 'delivered'].includes(order.value.status),
      active: order.value.status === 'placed',
    },
    {
      label: 'Preparing',
      completed: ['preparing', 'ready', 'delivered'].includes(order.value.status),
      active: order.value.status === 'preparing',
    },
    {
      label: 'On the Way',
      completed: ['ready', 'delivered'].includes(order.value.status),
      active: order.value.status === 'ready',
    },
    {
      label: 'Delivered',
      completed: ['delivered'].includes(order.value.status),
      active: order.value.status === 'delivered',
    },
  ]

  return steps
})

// 方法
const formatDate = (dateString: string | undefined): string => {
  if (!dateString) return ''

  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
    hour: 'numeric',
    minute: 'numeric',
  })
}

const formatStatus = (status: string | undefined): string => {
  if (!status) return ''

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

// 跟踪订单
const trackOrder = () => {
  router.push(`/order/${orderId}/track`)
}

// 重新下单
const reorderItems = () => {
  if (!order.value) return

  // 清空当前购物车
  cartStore.clearCart()

  // 将订单中的商品添加到购物车
  order.value.items.forEach((item) => {
    cartStore.addItem({
      id: `reorder-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`,
      name: item.name,
      price: item.price,
      quantity: item.quantity,
      image: order.value?.restaurantImage || '',
      restaurantId: `restaurant-${order.value?.id}`,
      restaurantName: order.value?.restaurantName || '',
    })
  })

  // 导航到结账页面
  router.push('/checkout')
}

// 取消订单
const cancelOrder = () => {
  // 模拟API请求
  setTimeout(() => {
    if (order.value) {
      order.value.status = 'cancelled'
    }
    showCancelConfirmation.value = false

    // 可以添加一个成功提示
    alert('Order has been cancelled successfully.')
  }, 500)
}

// 生命周期钩子
onMounted(() => {
  fetchOrderData()
})
</script>

<style lang="scss" scoped>
@use 'sass:color';
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.order-detail-page {
  padding: $spacing-md;

  &__card {
    background-color: $white;
    border-radius: $border-radius-md;
    box-shadow: $shadow-sm;
    margin-bottom: $spacing-md;
    overflow: hidden;
  }

  &__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: $spacing-md;
    border-bottom: 1px solid $gray-200;
  }

  &__order-number {
    font-size: $font-size-md;
    font-weight: $font-weight-semibold;
    color: $text-primary;
  }

  &__status {
    font-size: $font-size-xs;
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

  &__info {
    padding: $spacing-md;
    border-bottom: 1px solid $gray-200;
  }

  &__info-row {
    display: flex;
    margin-bottom: $spacing-xs;

    &:last-child {
      margin-bottom: 0;
    }
  }

  &__info-label {
    width: 100px;
    font-size: $font-size-sm;
    color: $text-secondary;
  }

  &__info-value {
    font-size: $font-size-sm;
    color: $text-primary;
  }

  &__items {
    padding: $spacing-md;
    border-bottom: 1px solid $gray-200;
  }

  &__items-header {
    font-size: $font-size-sm;
    font-weight: $font-weight-semibold;
    color: $text-primary;
    margin-bottom: $spacing-sm;
  }

  &__item {
    display: flex;
    justify-content: space-between;
    margin-bottom: $spacing-xs;

    &:last-child {
      margin-bottom: 0;
    }
  }

  &__item-name-qty {
    display: flex;
  }

  &__item-quantity {
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    color: $text-primary;
    margin-right: $spacing-xs;
  }

  &__item-name {
    font-size: $font-size-sm;
    color: $text-secondary;
  }

  &__item-price {
    font-size: $font-size-sm;
    color: $text-primary;
  }

  &__summary {
    padding: $spacing-md;
    border-bottom: 1px solid $gray-200;
  }

  &__summary-row {
    display: flex;
    justify-content: space-between;
    font-size: $font-size-sm;
    color: $text-secondary;
    margin-bottom: $spacing-xs;

    &:last-child {
      margin-bottom: 0;
    }

    &.total {
      font-weight: $font-weight-semibold;
      color: $text-primary;
      margin-top: $spacing-sm;
      padding-top: $spacing-sm;
      border-top: 1px solid $gray-200;
    }
  }

  &__address {
    padding: $spacing-md;
    border-bottom: 1px solid $gray-200;
  }

  &__section-title {
    font-size: $font-size-sm;
    font-weight: $font-weight-semibold;
    color: $text-primary;
    margin: 0 0 $spacing-sm;
  }

  &__address-text {
    font-size: $font-size-sm;
    color: $text-secondary;
    line-height: 1.5;
    margin: 0;
  }

  &__progress {
    padding: $spacing-md;
  }

  &__progress-bar {
    height: 4px;
    background-color: $gray-200;
    border-radius: $border-radius-pill;
    margin: $spacing-md 0;
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
      .order-detail-page__step-dot {
        background-color: $primary;
      }

      &:not(:last-child):after {
        background-color: $primary;
      }
    }

    &.active {
      .order-detail-page__step-dot {
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

  &__actions {
    display: flex;
    gap: $spacing-md;
  }

  &__track-btn,
  &__reorder-btn,
  &__cancel-btn {
    flex: 1;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    border-radius: $border-radius-md;
    cursor: pointer;

    i {
      margin-right: $spacing-xs;
    }
  }

  &__track-btn {
    background-color: $primary;
    color: $white;
    border: none;

    &:hover {
      background-color: color.adjust($primary, $lightness: -10%);
    }
  }

  &__reorder-btn {
    background-color: $white;
    color: $primary;
    border: 1px solid $primary;

    &:hover {
      background-color: rgba($primary, 0.05);
    }
  }

  &__cancel-btn {
    background-color: $white;
    color: $danger;
    border: 1px solid $danger;

    &:hover {
      background-color: rgba($danger, 0.05);
    }
  }
}

// 取消订单确认对话框样式
.cancel-dialog {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: $z-index-modal;
  display: flex;
  align-items: center;
  justify-content: center;

  &__backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba($black, 0.5);
  }

  &__content {
    position: relative;
    width: 90%;
    max-width: 340px;
    background-color: $white;
    border-radius: $border-radius-lg;
    padding: $spacing-lg;
    box-shadow: $shadow-lg;
    animation: fade-in 0.3s ease-out;
  }

  &__title {
    font-size: $font-size-lg;
    font-weight: $font-weight-semibold;
    color: $text-primary;
    margin: 0 0 $spacing-sm;
    text-align: center;
  }

  &__message {
    font-size: $font-size-sm;
    color: $text-secondary;
    margin: 0 0 $spacing-lg;
    text-align: center;
    line-height: 1.5;
  }

  &__actions {
    display: flex;
    gap: $spacing-sm;
  }

  &__btn {
    flex: 1;
    height: 44px;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    border-radius: $border-radius-md;
    cursor: pointer;

    &--secondary {
      background-color: $white;
      color: $text-primary;
      border: 1px solid $gray-300;

      &:hover {
        background-color: $gray-100;
      }
    }

    &--primary {
      background-color: $danger;
      color: $white;
      border: none;

      &:hover {
        background-color: color.adjust($danger, $lightness: -10%);
      }
    }
  }
}

@keyframes fade-in {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>
