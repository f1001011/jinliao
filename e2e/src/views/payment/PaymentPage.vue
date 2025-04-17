<template>
  <PageContainer :title="'Payment'" :hasHeader="false" :noPadding="true">
    <div class="payment-page">
      <!-- 顶部横幅 -->
      <div class="payment-page__banner">
        <div class="payment-page__banner-content">
          <i class="fas fa-heart payment-page__banner-icon"></i>
          <h1 class="payment-page__banner-title">You're helping {{ payerName }} pay for food</h1>
          <p class="payment-page__banner-text">
            Your generosity means a lot! Complete the payment to help {{ payerName }} enjoy a
            delicious meal.
          </p>
        </div>
      </div>

      <!-- 商品信息 -->
      <div class="payment-page__product">
        <div class="payment-page__product-image">
          <img :src="product.image" :alt="product.name" />
        </div>
        <div class="payment-page__product-info">
          <h2 class="payment-page__product-name">{{ product.name }}</h2>
          <div class="payment-page__product-rating">
            <i class="fas fa-star"></i>
            <span>{{ product.rating }}</span>
          </div>
          <div class="payment-page__product-prices">
            <div class="payment-page__product-discount">
              ${{ product.discountPrice.toFixed(2) }}
            </div>
            <div class="payment-page__product-original">
              ${{ product.originalPrice.toFixed(2) }}
            </div>
            <div class="payment-page__product-saving">
              Save ${{ (product.originalPrice - product.discountPrice).toFixed(2) }}
            </div>
          </div>
        </div>
      </div>

      <!-- 商品描述 -->
      <div class="payment-page__description">
        {{ product.description }}
      </div>

      <!-- 数量选择 -->
      <div class="payment-page__quantity">
        <div class="payment-page__quantity-label">Quantity</div>
        <div class="payment-page__quantity-selector">
          <button
            class="payment-page__quantity-btn"
            @click="decreaseQuantity"
            :disabled="quantity <= 1"
          >
            <i class="fas fa-minus"></i>
          </button>
          <span class="payment-page__quantity-value">{{ quantity }}</span>
          <button class="payment-page__quantity-btn" @click="increaseQuantity">
            <i class="fas fa-plus"></i>
          </button>
        </div>
      </div>

      <!-- 订单摘要 -->
      <div class="payment-page__summary">
        <div class="payment-page__summary-row">
          <span>Item Total</span>
          <span>${{ (product.discountPrice * quantity).toFixed(2) }}</span>
        </div>
        <div class="payment-page__summary-row">
          <span>Delivery Fee</span>
          <span>\$0.00</span>
        </div>
        <div class="payment-page__summary-row total">
          <span>Total</span>
          <span>${{ (product.discountPrice * quantity).toFixed(2) }}</span>
        </div>
      </div>

      <!-- 支付按钮 -->
      <div class="payment-page__payment">
        <button class="payment-page__payment-btn" @click="processPayment" :disabled="isProcessing">
          <img
            v-if="!isProcessing"
            src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/PP_logo_h_100x26.png"
            alt="PayPal"
          />
          <i v-else class="fas fa-spinner fa-spin"></i>
        </button>
        <div class="payment-page__secure-text">
          <i class="fas fa-lock"></i>
          <span>Secure payment processing by PayPal</span>
        </div>
        <div class="payment-page__thank-you">
          Thank you for your generosity! Your payment will be securely processed and
          {{ payerName }} will be notified immediately.
        </div>
      </div>

      <!-- 餐厅信息 -->
      <div class="payment-page__restaurant">
        <h3 class="payment-page__restaurant-title">About the Restaurant</h3>
        <p class="payment-page__restaurant-text">
          {{ product.restaurantName }} specializes in serving delicious food made with fresh
          ingredients. They are known for their excellent service and quality meals.
        </p>
      </div>
    </div>

    <!-- 支付成功模态框 -->
    <div class="payment-success" v-if="showSuccessModal">
      <div class="payment-success__backdrop"></div>
      <div class="payment-success__content">
        <div class="payment-success__icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <h2 class="payment-success__title">Payment Successful!</h2>
        <p class="payment-success__message">
          Thank you for helping {{ payerName }}! Your payment has been successfully processed.
        </p>
        <div class="payment-success__amount">
          ${{ (product.discountPrice * quantity).toFixed(2) }}
        </div>
        <p class="payment-success__info">
          {{ payerName }} has been notified of your payment. The restaurant will start preparing the
          order right away.
        </p>
        <button class="payment-success__btn" @click="navigateToHome">Done</button>
      </div>
    </div>
  </PageContainer>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import PageContainer from '@/components/layout/PageContainer.vue'

// 路由
const route = useRoute()
const router = useRouter()

// 状态
const payerName = ref('John')
const quantity = ref(1)
const isProcessing = ref(false)
const showSuccessModal = ref(false)
const isLoading = ref(false)

// 商品数据
const product = ref({
  id: 101,
  name: 'Classic Cheeseburger',
  image:
    'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
  originalPrice: 8.99,
  discountPrice: 7.99,
  rating: 4.7,
  description:
    'Juicy beef patty with melted cheese, fresh lettuce, tomato, and our special sauce, all served on a toasted sesame seed bun.',
  restaurantName: 'Burger Palace',
})

// 增减数量
const increaseQuantity = () => {
  quantity.value++
}

const decreaseQuantity = () => {
  if (quantity.value > 1) {
    quantity.value--
  }
}

// 处理支付
const processPayment = () => {
  isProcessing.value = true

  // 模拟支付处理
  setTimeout(() => {
    isProcessing.value = false
    showSuccessModal.value = true
  }, 2000)
}

// 导航到首页
const navigateToHome = () => {
  router.push('/')
}

// 加载支付数据
const loadPaymentData = () => {
  const id = route.params.id
  const queryParams = route.query

  console.log(`Loading payment data for ID: ${id}, params:`, queryParams)

  try {
    // 从URL参数获取支付人姓名（来自分销商设置的收件人名称）
    if (queryParams.name) {
      payerName.value = queryParams.name as string
    }

    // 获取折扣价格
    let discountPrice = 7.99
    if (queryParams.price && !isNaN(parseFloat(queryParams.price as string))) {
      discountPrice = parseFloat(queryParams.price as string)
    } else {
      console.warn('Invalid price parameter:', queryParams.price)
    }

    isLoading.value = true

    // 模拟API请求加载商品数据 (实际应用中应该调用API根据ID获取商品数据)
    setTimeout(() => {
      // 解析产品ID
      let productId = 101
      try {
        if (id) {
          productId = parseInt(id as string)
          if (isNaN(productId)) productId = 101
        }
      } catch (e) {
        console.error('Failed to parse product ID:', e)
      }

      // 这里应该根据productId获取实际商品数据
      // 在实际应用中，这里会调用API: fetchProductDetails(productId)
      // 下面暂时使用模拟数据
      if (productId === 101) {
        product.value = {
          id: productId,
          name: 'Classic Cheeseburger',
          image:
            'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
          originalPrice: 8.99,
          discountPrice: discountPrice,
          rating: 4.7,
          description:
            'Juicy beef patty with melted cheese, fresh lettuce, tomato, and our special sauce, all served on a toasted sesame seed bun.',
          restaurantName: 'Burger Palace',
        }
      } else {
        // 根据产品ID加载不同商品数据 (演示用)
        product.value = {
          id: productId,
          name: `Product #${productId}`,
          image:
            'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
          originalPrice: 9.99,
          discountPrice: discountPrice,
          rating: 4.5,
          description: 'Product description',
          restaurantName: 'COKFOOD Restaurant',
        }
      }

      isLoading.value = false
    }, 1000)
  } catch (error) {
    console.error('Error in loadPaymentData:', error)
    // 加载出错时使用默认数据
    product.value = {
      id: 101,
      name: 'Classic Cheeseburger',
      image:
        'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
      originalPrice: 8.99,
      discountPrice: 7.99,
      rating: 4.7,
      description:
        'Juicy beef patty with melted cheese, fresh lettuce, tomato, and our special sauce, all served on a toasted sesame seed bun.',
      restaurantName: 'Burger Palace',
    }
    isLoading.value = false
  }
}

// 生命周期钩子
onMounted(() => {
  loadPaymentData()
})
</script>

<style lang="scss" scoped>
@use 'sass:color';
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.payment-page {
  min-height: 100vh;
  background-color: $gray-100;

  &__banner {
    background: linear-gradient(135deg, #ff6baa, #ff4b87);
    padding: $spacing-xl $spacing-md;
    color: $white;
    position: relative;
    overflow: hidden;

    &::before {
      content: '';
      position: absolute;
      top: -20px;
      left: -20px;
      right: -20px;
      bottom: -20px;
      background: radial-gradient(
        circle at 70% 30%,
        rgba(255, 255, 255, 0.2) 0%,
        rgba(255, 255, 255, 0) 60%
      );
      z-index: 0;
    }
  }

  &__banner-content {
    text-align: center;
    position: relative;
    z-index: 1;
  }

  &__banner-icon {
    font-size: 40px;
    margin-bottom: $spacing-sm;
    animation: pulse 1.5s infinite;
    display: inline-block;
    color: rgba(255, 255, 255, 0.9);
    background: rgba(255, 255, 255, 0.2);
    padding: 12px;
    border-radius: 50%;
  }

  &__banner-title {
    font-size: 1.4rem;
    font-weight: $font-weight-bold;
    margin: 0 0 $spacing-sm;
    line-height: 1.3;
  }

  &__banner-text {
    font-size: $font-size-sm;
    margin: 0;
    line-height: 1.5;
    opacity: 0.9;
    max-width: 280px;
    margin: 0 auto;
  }

  &__product {
    background-color: $white;
    padding: $spacing-md;
    display: flex;
    align-items: center;
    box-shadow: $shadow-sm;
  }

  &__product-image {
    width: 100px;
    height: 100px;
    border-radius: $border-radius-md;
    overflow: hidden;
    margin-right: $spacing-md;
    background-color: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

  &__product-info {
    flex: 1;
  }

  &__product-name {
    font-size: $font-size-lg;
    font-weight: $font-weight-bold;
    margin: 0 0 $spacing-xxs;
    color: $text-primary;
  }

  &__product-rating {
    display: flex;
    align-items: center;
    margin-bottom: $spacing-xs;

    i {
      color: #ffd700;
      margin-right: 4px;
      font-size: $font-size-xs;
    }

    span {
      font-size: $font-size-xs;
      color: $text-primary;
      font-weight: $font-weight-medium;
    }
  }

  &__product-prices {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: $spacing-xs;
  }

  &__product-discount {
    font-size: $font-size-md;
    font-weight: $font-weight-bold;
    color: #ff4b87;
  }

  &__product-original {
    font-size: $font-size-sm;
    color: $text-secondary;
    text-decoration: line-through;
  }

  &__product-saving {
    font-size: $font-size-xs;
    color: #22c55e;
    background-color: rgba(34, 197, 94, 0.1);
    padding: 3px $spacing-xs;
    border-radius: $border-radius-pill;
    font-weight: $font-weight-medium;
  }

  &__description {
    background-color: $white;
    padding: $spacing-md;
    font-size: $font-size-sm;
    line-height: 1.6;
    color: $text-secondary;
    border-top: 1px solid $gray-200;
  }

  &__quantity {
    background-color: $white;
    padding: $spacing-md;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: $spacing-md;
    box-shadow: $shadow-sm;
    border-radius: $border-radius-md;
  }

  &__quantity-label {
    font-size: $font-size-sm;
    font-weight: $font-weight-semibold;
    color: $text-primary;
  }

  &__quantity-selector {
    display: flex;
    align-items: center;
  }

  &__quantity-btn {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: $white;
    border: 1px solid $gray-300;
    border-radius: $border-radius-md;
    cursor: pointer;
    transition: all $transition-base;

    &:hover:not(:disabled) {
      background-color: #f9f9f9;
      border-color: $gray-400;
    }

    &:disabled {
      color: $gray-400;
      cursor: not-allowed;
    }
  }

  &__quantity-value {
    width: 40px;
    text-align: center;
    font-size: $font-size-sm;
    font-weight: $font-weight-semibold;
  }

  &__summary {
    background-color: $white;
    padding: $spacing-lg;
    margin-top: $spacing-md;
    border-radius: $border-radius-md;
    box-shadow: $shadow-sm;
  }

  &__summary-row {
    display: flex;
    justify-content: space-between;
    font-size: $font-size-sm;
    margin-bottom: $spacing-sm;

    &:last-child {
      margin-bottom: 0;
    }

    &.total {
      margin-top: $spacing-sm;
      padding-top: $spacing-sm;
      border-top: 1px solid $gray-200;
      font-size: $font-size-md;
      font-weight: $font-weight-bold;
      color: #ff4b87;
    }
  }

  &__payment {
    background-color: $white;
    padding: $spacing-lg;
    margin-top: $spacing-md;
    text-align: center;
    border-radius: $border-radius-md;
    box-shadow: $shadow-sm;
  }

  &__payment-btn {
    width: 100%;
    height: 54px;
    background-color: #0070ba;
    border: none;
    border-radius: $border-radius-md;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    margin-bottom: $spacing-sm;
    transition: background-color $transition-base;
    color: $white;
    font-weight: $font-weight-semibold;
    font-size: $font-size-md;

    &:hover:not(:disabled) {
      background-color: #005ea6;
    }

    &:disabled {
      cursor: not-allowed;
      opacity: 0.7;
    }

    img {
      height: 26px;
    }
  }

  &__secure-text {
    font-size: $font-size-xs;
    color: $text-secondary;
    margin-bottom: $spacing-sm;
    display: flex;
    align-items: center;
    justify-content: center;

    i {
      margin-right: 4px;
      color: #0070ba;
    }
  }

  &__thank-you {
    font-size: $font-size-xs;
    color: $text-secondary;
    line-height: 1.5;
    padding: $spacing-sm;
    background-color: rgba(59, 130, 246, 0.1);
    border-radius: $border-radius-md;
    border-left: 3px solid #3b82f6;
  }

  &__restaurant {
    background-color: $white;
    padding: $spacing-lg;
    margin-top: $spacing-md;
    margin-bottom: $spacing-xl;
    border-radius: $border-radius-md;
    box-shadow: $shadow-sm;
  }

  &__restaurant-title {
    font-size: $font-size-md;
    font-weight: $font-weight-semibold;
    margin: 0 0 $spacing-sm;
    color: $text-primary;
  }

  &__restaurant-text {
    font-size: $font-size-sm;
    color: $text-secondary;
    line-height: 1.6;
    margin: 0;
  }
}

.payment-success {
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
    background-color: rgba($black, 0.6);
  }

  &__content {
    position: relative;
    width: 90%;
    max-width: 360px;
    background-color: $white;
    border-radius: $border-radius-lg;
    padding: $spacing-xl $spacing-lg;
    box-shadow: $shadow-xl;
    text-align: center;
    animation: modal-fade-in 0.3s ease-out;
  }

  &__icon {
    font-size: 64px;
    color: #22c55e;
    margin-bottom: $spacing-md;
  }

  &__title {
    font-size: $font-size-lg;
    font-weight: $font-weight-bold;
    color: $text-primary;
    margin: 0 0 $spacing-sm;
  }

  &__message {
    font-size: $font-size-sm;
    color: $text-secondary;
    margin: 0 0 $spacing-md;
    line-height: 1.5;
  }

  &__amount {
    font-size: $font-size-xl;
    font-weight: $font-weight-bold;
    color: #22c55e;
    margin-bottom: $spacing-md;
    background-color: rgba(34, 197, 94, 0.1);
    padding: $spacing-sm;
    border-radius: $border-radius-md;
    display: inline-block;
  }

  &__info {
    font-size: $font-size-xs;
    color: $text-secondary;
    margin: 0 0 $spacing-lg;
    line-height: 1.5;
    padding: $spacing-sm;
    background-color: rgba(59, 130, 246, 0.1);
    border-radius: $border-radius-md;
    border-left: 3px solid #3b82f6;
  }

  &__btn {
    width: 100%;
    height: 48px;
    background-color: #ff4b87;
    color: $white;
    border: none;
    border-radius: $border-radius-md;
    font-size: $font-size-sm;
    font-weight: $font-weight-semibold;
    cursor: pointer;
    transition: background-color $transition-base;

    &:hover {
      background-color: color.adjust(#ff4b87, $lightness: -10%);
    }
  }
}

@keyframes pulse {
  0% {
    transform: scale(1);
    opacity: 0.9;
  }
  50% {
    transform: scale(1.05);
    opacity: 1;
  }
  100% {
    transform: scale(1);
    opacity: 0.9;
  }
}

@keyframes modal-fade-in {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>
