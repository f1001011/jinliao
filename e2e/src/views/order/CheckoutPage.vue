<template>
  <PageContainer title="Checkout">
    <div class="checkout-page">
      <!-- 配送地址表单 -->
      <div class="checkout-page__section">
        <div class="checkout-page__section-header">
          <i class="fas fa-map-marker-alt checkout-page__section-icon"></i>
          <h2 class="checkout-page__section-title">Delivery Address</h2>
        </div>
        <form class="checkout-page__address-form">
          <div class="checkout-page__form-row">
            <div class="checkout-page__form-group">
              <label class="checkout-page__label">First Name <span class="required">*</span></label>
              <input
                type="text"
                class="checkout-page__input"
                v-model="addressForm.firstName"
                placeholder="John"
                required
              />
            </div>

            <div class="checkout-page__form-group">
              <label class="checkout-page__label">Last Name <span class="required">*</span></label>
              <input
                type="text"
                class="checkout-page__input"
                v-model="addressForm.lastName"
                placeholder="Doe"
                required
              />
            </div>
          </div>

          <div class="checkout-page__form-group">
            <label class="checkout-page__label">Phone Number <span class="required">*</span></label>
            <input
              type="tel"
              class="checkout-page__input"
              v-model="addressForm.phone"
              placeholder="(123) 456-7890"
              required
            />
          </div>

          <div class="checkout-page__form-group">
            <label class="checkout-page__label">Email <span class="required">*</span></label>
            <input
              type="email"
              class="checkout-page__input"
              v-model="addressForm.email"
              placeholder="john.doe@example.com"
              required
            />
          </div>

          <div class="checkout-page__form-group">
            <label class="checkout-page__label"
              >Address Line 1 <span class="required">*</span></label
            >
            <input
              type="text"
              class="checkout-page__input"
              v-model="addressForm.address1"
              placeholder="123 Main St"
              required
            />
          </div>

          <div class="checkout-page__form-group">
            <label class="checkout-page__label">Address Line 2 (Optional)</label>
            <input
              type="text"
              class="checkout-page__input"
              v-model="addressForm.address2"
              placeholder="Apt 4B"
            />
          </div>

          <div class="checkout-page__form-row">
            <div class="checkout-page__form-group">
              <label class="checkout-page__label">City <span class="required">*</span></label>
              <input
                type="text"
                class="checkout-page__input"
                v-model="addressForm.city"
                placeholder="New York"
                required
              />
            </div>

            <div class="checkout-page__form-group">
              <label class="checkout-page__label"
                >State/Province <span class="required">*</span></label
              >
              <input
                type="text"
                class="checkout-page__input"
                v-model="addressForm.state"
                placeholder="NY"
                required
              />
            </div>

            <div class="checkout-page__form-group">
              <label class="checkout-page__label"
                >ZIP/Postal Code <span class="required">*</span></label
              >
              <input
                type="text"
                class="checkout-page__input"
                v-model="addressForm.zipCode"
                placeholder="10001"
                required
              />
            </div>
          </div>

          <div class="checkout-page__form-group">
            <label class="checkout-page__label">Delivery Instructions (Optional)</label>
            <textarea
              class="checkout-page__textarea"
              v-model="addressForm.instructions"
              placeholder="E.g., Ring doorbell, leave at door, etc."
            ></textarea>
          </div>
        </form>
      </div>

      <!-- 付款方式 -->
      <div class="checkout-page__section">
        <div class="checkout-page__section-header">
          <i class="fas fa-credit-card checkout-page__section-icon"></i>
          <h2 class="checkout-page__section-title">Payment Method</h2>
        </div>
        <div class="checkout-page__payment-methods">
          <div
            class="checkout-page__payment-method"
            :class="{ active: selectedPaymentMethod === 'paypal' }"
            @click="selectedPaymentMethod = 'paypal'"
          >
            <div class="checkout-page__payment-radio">
              <div
                class="radio-circle"
                :class="{ checked: selectedPaymentMethod === 'paypal' }"
              ></div>
            </div>
            <div class="checkout-page__payment-logo">
              <img
                src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg"
                alt="PayPal"
              />
            </div>
            <div class="checkout-page__payment-info">
              <div class="checkout-page__payment-name">Pay with PayPal</div>
            </div>
          </div>
        </div>
      </div>

      <!-- 订单备注 -->
      <div class="checkout-page__section">
        <div class="checkout-page__section-header">
          <i class="fas fa-sticky-note checkout-page__section-icon"></i>
          <h2 class="checkout-page__section-title">Order Notes (Optional)</h2>
        </div>
        <textarea
          class="checkout-page__textarea"
          v-model="orderNotes"
          placeholder="Any special requests or instructions for your order"
        ></textarea>
      </div>

      <!-- 订单摘要 -->
      <div class="checkout-page__section">
        <div class="checkout-page__section-header">
          <i class="fas fa-shopping-cart checkout-page__section-icon"></i>
          <h2 class="checkout-page__section-title">Order Summary</h2>
        </div>
        <div class="checkout-page__cart-items">
          <div v-for="item in cartItems" :key="item.id" class="checkout-page__cart-item">
            <div class="checkout-page__cart-item-image">
              <img :src="item.image" :alt="item.name" />
            </div>
            <div class="checkout-page__cart-item-info">
              <div class="checkout-page__cart-item-name">{{ item.name }}</div>
              <div class="checkout-page__cart-item-restaurant">{{ item.restaurantName }}</div>
              <div class="checkout-page__cart-item-price">
                ${{ item.price.toFixed(2) }} × {{ item.quantity }}
              </div>
            </div>
            <div class="checkout-page__cart-item-total">
              ${{ (item.price * item.quantity).toFixed(2) }}
            </div>
          </div>
        </div>

        <div class="checkout-page__divider"></div>

        <div class="checkout-page__summary">
          <div class="checkout-page__summary-row">
            <span>Subtotal</span>
            <span>${{ subtotal.toFixed(2) }}</span>
          </div>
          <div class="checkout-page__summary-row">
            <span>Delivery Fee</span>
            <span>${{ deliveryFee.toFixed(2) }}</span>
          </div>
          <div class="checkout-page__summary-row">
            <span>Tax</span>
            <span>${{ tax.toFixed(2) }}</span>
          </div>
          <div class="checkout-page__summary-row">
            <span>Service Fee</span>
            <span>${{ serviceFee.toFixed(2) }}</span>
          </div>
          <div v-if="discount > 0" class="checkout-page__summary-row discount">
            <span>Discount</span>
            <span>-${{ discount.toFixed(2) }}</span>
          </div>
          <div class="checkout-page__summary-row total">
            <span>Total</span>
            <span>${{ total.toFixed(2) }}</span>
          </div>
        </div>

        <div class="checkout-page__promo-container">
          <input
            type="text"
            class="checkout-page__input promo-input"
            v-model="promoCode"
            placeholder="Enter promo code"
          />
          <button class="checkout-page__promo-btn" @click="applyPromoCode">Apply</button>
        </div>

        <!-- 添加订单摘要内的Place Order按钮 -->
        <button class="checkout-page__order-btn" @click="submitOrder">Place Order</button>

        <div class="checkout-page__delivery-time">
          <i class="fas fa-clock"></i>
          <span>Estimated delivery time: 30-45 minutes</span>
        </div>
      </div>

      <!-- 底部间距，防止提交按钮遮挡内容 -->
      <div class="checkout-page__bottom-space"></div>
    </div>

    <!-- 底部提交订单按钮 -->
    <div class="checkout-page__footer">
      <button class="checkout-page__submit-btn" @click="submitOrder">
        <span>Place Order</span>
      </button>
    </div>

    <!-- 配送范围错误模态框 -->
    <div v-if="showDeliveryErrorModal" class="modal-base">
      <div class="modal-base__backdrop" @click="closeDeliveryErrorModal"></div>
      <div class="modal-base__content delivery-error-modal">
        <button class="delivery-error-modal__close" @click="closeDeliveryErrorModal">
          <i class="fas fa-times"></i>
        </button>

        <div class="delivery-error-modal__body">
          <div class="delivery-error-modal__icon-container">
            <div class="delivery-error-modal__icon">
              <i class="fas fa-map-marker-alt"></i>
            </div>
          </div>

          <h3 class="delivery-error-modal__title">Outside Delivery Area</h3>

          <p class="delivery-error-modal__message">
            Your address is outside our delivery range. Please enter a different address within our
            service area.
          </p>
        </div>

        <div class="delivery-error-modal__footer">
          <button class="delivery-error-modal__button" @click="closeDeliveryErrorModal">
            Try Another Address
          </button>
        </div>
      </div>
    </div>
  </PageContainer>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import PageContainer from '@/components/layout/PageContainer.vue'

// 路由
const router = useRouter()

// 地址表单数据
interface AddressForm {
  firstName: string
  lastName: string
  phone: string
  email: string
  address1: string
  address2: string
  city: string
  state: string
  zipCode: string
  instructions: string
}

const addressForm = ref<AddressForm>({
  firstName: '',
  lastName: '',
  phone: '',
  email: '',
  address1: '',
  address2: '',
  city: '',
  state: '',
  zipCode: '',
  instructions: '',
})

// 购物车商品
interface CartItem {
  id: number
  name: string
  price: number
  quantity: number
  image: string
  restaurantName: string
}

const cartItems = ref<CartItem[]>([
  {
    id: 101,
    name: 'Classic Cheeseburger',
    price: 8.99,
    quantity: 2,
    image:
      'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    restaurantName: 'Burger Palace',
  },
  {
    id: 105,
    name: 'French Fries',
    price: 3.99,
    quantity: 1,
    image:
      'https://images.unsplash.com/photo-1576107232684-1279f390859f?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    restaurantName: 'Burger Palace',
  },
])

// 付款方式
const selectedPaymentMethod = ref('paypal')

// 订单备注
const orderNotes = ref('')

// 优惠码
const promoCode = ref('')
const discount = ref(0)

// 应用优惠码
const applyPromoCode = () => {
  if (promoCode.value === 'WELCOME10') {
    discount.value = subtotal.value * 0.1 // 10% discount
    alert('Promo code applied successfully! 10% discount added.')
  } else if (promoCode.value) {
    alert('Invalid promo code. Please try again.')
  }
}

// 计算总价
const subtotal = computed(() => {
  return cartItems.value.reduce((total, item) => total + item.price * item.quantity, 0)
})

const deliveryFee = ref(2.99)
const tax = computed(() => subtotal.value * 0.08) // 8% tax
const serviceFee = computed(() => subtotal.value * 0.05) // 5% service fee

const total = computed(() => {
  return subtotal.value + deliveryFee.value + tax.value + serviceFee.value - discount.value
})

// 提交订单
const submitOrder = () => {
  // 模拟表单验证
  if (
    !addressForm.value.firstName ||
    !addressForm.value.lastName ||
    !addressForm.value.phone ||
    !addressForm.value.email ||
    !addressForm.value.address1 ||
    !addressForm.value.city ||
    !addressForm.value.state ||
    !addressForm.value.zipCode
  ) {
    alert('Please fill in all required fields.')
    return
  }

  // 模拟订单提交
  console.log('Submitting order with data:', {
    address: addressForm.value,
    paymentMethod: selectedPaymentMethod.value,
    items: cartItems.value,
    notes: orderNotes.value,
    total: total.value,
  })

  // 显示"不在配送区域"错误模态框
  showDeliveryErrorModal.value = true
}

// 关闭配送范围错误模态框
const closeDeliveryErrorModal = () => {
  showDeliveryErrorModal.value = false
}

// 设置错误模态框显示状态
const showDeliveryErrorModal = ref(false)

// 生命周期钩子
onMounted(() => {
  // 这里可以加载用户保存的地址信息
  // 或者从购物车加载商品
})
</script>

<style lang="scss" scoped>
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.checkout-page {
  padding: $spacing-md;
  background-color: #f8f9fa;
  max-width: 800px;
  margin: 0 auto;
  padding-bottom: calc(80px + #{$spacing-md}); // 为底部固定按钮留出空间

  &__section {
    background-color: $white;
    border-radius: 12px;
    padding: $spacing-lg;
    margin-bottom: $spacing-md;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  }

  &__section-header {
    display: flex;
    align-items: center;
    margin-bottom: $spacing-md;
  }

  &__section-icon {
    font-size: 18px;
    color: #f43f5e;
    margin-right: $spacing-sm;
  }

  &__section-title {
    font-size: 18px;
    font-weight: $font-weight-semibold;
    margin: 0;
    color: $text-primary;
  }

  &__form-row {
    display: flex;
    gap: $spacing-md;
    margin-bottom: $spacing-md;

    .checkout-page__form-group {
      flex: 1;
      margin-bottom: 0;
    }
  }

  &__form-group {
    margin-bottom: $spacing-md;
  }

  &__label {
    display: block;
    font-size: 13px;
    font-weight: $font-weight-medium;
    margin-bottom: $spacing-xs;
    color: $text-secondary;

    .required {
      color: #f43f5e;
    }
  }

  &__input {
    width: 100%;
    height: 48px;
    padding: 0 $spacing-md;
    font-size: $font-size-sm;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background-color: $white;
    transition: all 0.2s ease;

    &:focus {
      border-color: #f43f5e;
      box-shadow: 0 0 0 2px rgba(244, 63, 94, 0.1);
      outline: none;
    }

    &::placeholder {
      color: #a0aec0;
    }
  }

  &__textarea {
    width: 100%;
    height: 80px;
    padding: $spacing-md;
    font-size: $font-size-sm;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background-color: $white;
    resize: vertical;
    transition: all 0.2s ease;

    &:focus {
      border-color: #f43f5e;
      box-shadow: 0 0 0 2px rgba(244, 63, 94, 0.1);
      outline: none;
    }

    &::placeholder {
      color: #a0aec0;
    }
  }

  &__payment-methods {
    display: flex;
    flex-direction: column;
    gap: $spacing-sm;
  }

  &__payment-method {
    display: flex;
    align-items: center;
    padding: $spacing-md;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;

    &.active {
      border-color: #f43f5e;
      background-color: rgba(244, 63, 94, 0.03);
    }

    &:hover {
      border-color: #f43f5e;
    }
  }

  &__payment-radio {
    margin-right: $spacing-md;

    .radio-circle {
      width: 20px;
      height: 20px;
      border: 2px solid #cbd5e0;
      border-radius: 50%;
      position: relative;
      transition: all 0.2s ease;

      &.checked {
        border-color: #f43f5e;

        &:after {
          content: '';
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          width: 10px;
          height: 10px;
          background-color: #f43f5e;
          border-radius: 50%;
        }
      }
    }
  }

  &__payment-logo {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: $spacing-md;

    img {
      max-width: 100%;
      max-height: 100%;
    }

    i {
      font-size: 24px;
      color: $gray-600;
    }
  }

  &__payment-info {
    flex: 1;
  }

  &__payment-name {
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
  }

  &__cart-items {
    margin-bottom: $spacing-md;
  }

  &__cart-item {
    display: flex;
    align-items: center;
    padding: $spacing-sm 0;
    margin-bottom: $spacing-sm;

    &:last-child {
      margin-bottom: 0;
    }
  }

  &__cart-item-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    margin-right: $spacing-md;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

  &__cart-item-info {
    flex: 1;
  }

  &__cart-item-name {
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    color: $text-primary;
    margin-bottom: 2px;
  }

  &__cart-item-restaurant {
    font-size: 11px;
    color: $text-secondary;
    margin-bottom: 4px;
  }

  &__cart-item-price {
    font-size: $font-size-xs;
    color: $text-secondary;
  }

  &__cart-item-total {
    font-size: $font-size-sm;
    font-weight: $font-weight-semibold;
    color: $text-primary;
  }

  &__divider {
    height: 1px;
    background-color: #e2e8f0;
    margin: $spacing-md 0;
  }

  &__summary {
    margin-bottom: $spacing-md;
  }

  &__summary-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    font-size: $font-size-sm;
    color: $text-secondary;

    &.discount {
      color: #10b981;
    }

    &.total {
      border-top: 1px solid #e2e8f0;
      padding-top: $spacing-sm;
      margin-top: 4px;
      font-size: 16px;
      font-weight: $font-weight-semibold;
      color: $text-primary;
    }
  }

  &__promo-container {
    display: flex;
    gap: $spacing-sm;
    margin-bottom: $spacing-md;

    .promo-input {
      flex: 1;
    }
  }

  &__promo-btn {
    height: 48px;
    padding: 0 $spacing-md;
    background-color: #f43f5e;
    color: $white;
    border: none;
    border-radius: 8px;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    cursor: pointer;
    transition: all 0.2s ease;

    &:hover {
      background-color: #e11d48;
    }
  }

  &__order-btn {
    width: 100%;
    height: 50px;
    margin: $spacing-md 0;
    background-color: #ef4444;
    color: $white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: $font-weight-bold;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 8px rgba(239, 68, 68, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;

    &:hover {
      background-color: #dc2626;
      transform: translateY(-1px);
      box-shadow: 0 6px 12px rgba(239, 68, 68, 0.25);
    }

    &:active {
      transform: translateY(1px);
      box-shadow: 0 2px 4px rgba(239, 68, 68, 0.15);
    }
  }

  &__delivery-time {
    display: flex;
    align-items: center;
    font-size: $font-size-xs;
    color: $text-secondary;
    background-color: #fef2f2;
    padding: $spacing-sm;
    border-radius: 8px;

    i {
      margin-right: $spacing-xs;
      color: #f43f5e;
    }
  }

  &__bottom-space {
    height: 80px; /* 为固定底部按钮留出空间 */
  }

  &__footer {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: $white;
    padding: $spacing-md;
    padding-bottom: calc(#{$spacing-md} + env(safe-area-inset-bottom, 0));
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    z-index: 300; /* 确保在底部导航栏之上 */
  }

  &__submit-btn {
    width: 100%;
    height: 50px;
    background-color: #f43f5e;
    color: $white;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    font-weight: $font-weight-bold;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 8px rgba(244, 63, 94, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;

    &:hover {
      background-color: #e11d48;
      transform: translateY(-1px);
      box-shadow: 0 6px 12px rgba(244, 63, 94, 0.25);
    }

    &:active {
      transform: translateY(1px);
      box-shadow: 0 2px 4px rgba(244, 63, 94, 0.15);
    }
  }
}

// 媒体查询适配移动设备
@media (max-width: $breakpoint-md) {
  .checkout-page {
    padding: $spacing-sm;

    &__section {
      padding: $spacing-md;
    }

    &__footer {
      padding: $spacing-sm;
    }
  }
}

// 基础模态框样式
.modal-base {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  animation: modal-fade-in 0.3s ease;

  &__backdrop {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
  }

  &__content {
    position: relative;
    background-color: white;
    border-radius: 16px;
    width: 90%;
    max-width: 400px;
    max-height: 90vh;
    overflow-y: auto;
    z-index: 1001;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  }
}

// 配送范围错误模态框
.delivery-error-modal {
  position: relative;
  text-align: center;

  &__close {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    font-size: 16px;
    border-radius: 50%;

    &:hover {
      background-color: #f5f5f5;
    }
  }

  &__body {
    padding: 32px 24px 24px;
  }

  &__icon-container {
    margin: 0 auto 20px;
  }

  &__icon {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background-color: #ffebee;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;

    i {
      font-size: 32px;
      color: #ef4444;
    }
  }

  &__title {
    font-size: 20px;
    font-weight: 600;
    color: #333;
    margin: 0 0 16px;
  }

  &__message {
    font-size: 15px;
    line-height: 1.5;
    color: #666;
    margin: 0 0 24px;
    padding: 0 16px;
  }

  &__footer {
    padding: 16px 24px 24px;
  }

  &__button {
    width: 100%;
    height: 48px;
    background-color: #ef4444;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;

    &:hover {
      background-color: #dc2626;
    }
  }
}

@keyframes modal-fade-in {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
