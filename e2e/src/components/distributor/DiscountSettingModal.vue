<template>
  <div class="discount-modal" v-if="visible">
    <div class="discount-modal__backdrop" @click="closeModal"></div>
    <div class="discount-modal__content">
      <div class="discount-modal__header">
        <h2 class="discount-modal__title">生成支付链接</h2>
        <button class="discount-modal__close" @click="closeModal">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <div class="discount-modal__body">
        <!-- 商品信息 -->
        <div class="discount-modal__product">
          <div class="discount-modal__product-image">
            <img :src="product.image" :alt="product.name" />
          </div>
          <div class="discount-modal__product-info">
            <h3 class="discount-modal__product-name">{{ product.name }}</h3>
            <p class="discount-modal__product-price">原价: ¥{{ product.price.toFixed(2) }}</p>
          </div>
        </div>

        <!-- 折扣价格设置 -->
        <div class="discount-modal__section">
          <h4 class="discount-modal__section-title">设置折扣价格</h4>
          <div class="discount-modal__price-input">
            <span class="discount-modal__currency">¥</span>
            <input
              type="number"
              v-model="discountPrice"
              class="discount-modal__input"
              min="0.01"
              :max="product.price"
              step="0.01"
            />
          </div>

          <div class="discount-modal__price-slider">
            <input
              type="range"
              v-model="discountPercentage"
              min="1"
              max="100"
              class="discount-modal__slider"
            />
            <div class="discount-modal__slider-labels">
              <span>1折</span>
              <span>10折 (原价)</span>
            </div>
          </div>

          <div class="discount-modal__summary">
            <div class="discount-modal__discount-rate">折扣率: {{ discountRate }}折</div>
            <div class="discount-modal__savings">
              优惠: ¥{{ (product.price - discountPrice).toFixed(2) }}
            </div>
          </div>
        </div>

        <!-- 佣金信息 -->
        <div class="discount-modal__section">
          <h4 class="discount-modal__section-title">佣金信息</h4>
          <div class="discount-modal__commission">
            <div class="discount-modal__commission-item">
              <span>佣金比例:</span>
              <span>{{ commissionRate }}%</span>
            </div>
            <div class="discount-modal__commission-item">
              <span>预计佣金:</span>
              <span class="discount-modal__commission-value"
                >¥{{ estimatedCommission.toFixed(2) }}</span
              >
            </div>
          </div>
          <p class="discount-modal__commission-note">注意: 佣金将在订单完成后自动发放到您的账户</p>
        </div>
      </div>

      <div class="discount-modal__footer">
        <button class="discount-modal__cancel-btn" @click="closeModal">取消</button>
        <button
          class="discount-modal__generate-btn"
          @click="generatePaymentLink"
          :disabled="!isValidDiscount"
        >
          生成链接
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'

// 定义接口
interface Product {
  id: number
  name: string
  image: string
  price: number
}

// 定义Props接口
interface Props {
  visible: boolean
  product: Product
}

// 定义Emits接口
interface Emits {
  (e: 'close'): void
  (
    e: 'generate',
    data: {
      productId: number
      discountPrice: number
      commissionRate: number
      estimatedCommission: number
    },
  ): void
}

// Props和Emits
const props = defineProps<Props>()
const emit = defineEmits<Emits>()

// 状态
const discountPrice = ref(props.product.price * 0.9) // 默认9折
const commissionRate = ref(10) // 默认10%佣金

// 计算属性
const discountPercentage = computed({
  get: () => {
    return Math.round((discountPrice.value / props.product.price) * 100)
  },
  set: (value: number) => {
    discountPrice.value = parseFloat(((props.product.price * value) / 100).toFixed(2))
  },
})

const discountRate = computed(() => {
  return (discountPercentage.value / 10).toFixed(1)
})

const estimatedCommission = computed(() => {
  return discountPrice.value * (commissionRate.value / 100)
})

const isValidDiscount = computed(() => {
  return discountPrice.value > 0 && discountPrice.value <= props.product.price
})

// 监听props变化
watch(
  () => props.visible,
  (newValue) => {
    if (newValue) {
      // 重置状态
      discountPrice.value = props.product.price * 0.9
    }
  },
)

// 方法
const closeModal = () => {
  emit('close')
}

const generatePaymentLink = () => {
  if (!isValidDiscount.value) return

  emit('generate', {
    productId: props.product.id,
    discountPrice: discountPrice.value,
    commissionRate: commissionRate.value,
    estimatedCommission: estimatedCommission.value,
  })

  closeModal()
}
</script>

<style lang="scss" scoped>
@use 'sass:color';
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.discount-modal {
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
    max-width: 480px;
    background-color: $white;
    border-radius: $border-radius-lg;
    overflow: hidden;
    box-shadow: $shadow-lg;
    animation: modal-fade-in 0.3s ease-out;
  }

  &__header {
    padding: $spacing-md;
    background-color: $primary;
    color: $white;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  &__title {
    font-size: $font-size-lg;
    font-weight: $font-weight-bold;
    margin: 0;
  }

  &__close {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    color: $white;
    font-size: $font-size-md;
    cursor: pointer;
    border-radius: 50%;

    &:hover {
      background-color: rgba($white, 0.2);
    }
  }

  &__body {
    padding: $spacing-md;
  }

  &__product {
    display: flex;
    align-items: center;
    margin-bottom: $spacing-md;
    padding-bottom: $spacing-md;
    border-bottom: 1px solid $gray-200;
  }

  &__product-image {
    width: 80px;
    height: 80px;
    border-radius: $border-radius-md;
    overflow: hidden;
    margin-right: $spacing-md;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

  &__product-name {
    font-size: $font-size-md;
    font-weight: $font-weight-semibold;
    color: $text-primary;
    margin: 0 0 $spacing-xxs;
  }

  &__product-price {
    font-size: $font-size-sm;
    color: $text-secondary;
    margin: 0;
  }

  &__section {
    margin-bottom: $spacing-md;
  }

  &__section-title {
    font-size: $font-size-sm;
    font-weight: $font-weight-semibold;
    color: $text-primary;
    margin: 0 0 $spacing-sm;
  }

  &__price-input {
    position: relative;
    margin-bottom: $spacing-md;
  }

  &__currency {
    position: absolute;
    top: 0;
    left: 0;
    height: 48px;
    display: flex;
    align-items: center;
    padding: 0 $spacing-sm;
    font-size: $font-size-md;
    color: $text-primary;
  }

  &__input {
    width: 100%;
    height: 48px;
    padding: 0 $spacing-sm 0 $spacing-xl;
    font-size: $font-size-md;
    color: $text-primary;
    border: 1px solid $gray-300;
    border-radius: $border-radius-md;

    &:focus {
      outline: none;
      border-color: $primary;
      box-shadow: 0 0 0 2px rgba($primary, 0.2);
    }
  }

  &__price-slider {
    margin-bottom: $spacing-md;
  }

  &__slider {
    width: 100%;
    -webkit-appearance: none;
    appearance: none;
    height: 6px;
    background-color: $gray-200;
    border-radius: $border-radius-pill;
    outline: none;
    margin-bottom: $spacing-xxs;

    &::-webkit-slider-thumb {
      -webkit-appearance: none;
      appearance: none;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      background-color: $primary;
      cursor: pointer;
      box-shadow: 0 2px 4px rgba($black, 0.1);
    }

    &::-moz-range-thumb {
      width: 18px;
      height: 18px;
      border-radius: 50%;
      background-color: $primary;
      cursor: pointer;
      box-shadow: 0 2px 4px rgba($black, 0.1);
      border: none;
    }
  }

  &__slider-labels {
    display: flex;
    justify-content: space-between;
    font-size: $font-size-xxs;
    color: $text-secondary;
  }

  &__summary {
    display: flex;
    justify-content: space-between;
    padding: $spacing-sm;
    background-color: $gray-100;
    border-radius: $border-radius-md;
    margin-bottom: $spacing-md;
  }

  &__discount-rate,
  &__savings {
    font-size: $font-size-sm;
    color: $text-primary;
    font-weight: $font-weight-medium;
  }

  &__savings {
    color: $success;
  }

  &__commission {
    background-color: rgba($info, 0.1);
    padding: $spacing-sm;
    border-radius: $border-radius-md;
    margin-bottom: $spacing-sm;
  }

  &__commission-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: $spacing-xxs;

    &:last-child {
      margin-bottom: 0;
    }

    span {
      font-size: $font-size-sm;
      color: $text-primary;
    }
  }

  &__commission-value {
    font-weight: $font-weight-semibold;
    color: $primary !important;
  }

  &__commission-note {
    font-size: $font-size-xs;
    color: $text-secondary;
    margin: 0;
  }

  &__footer {
    padding: $spacing-md;
    background-color: $gray-100;
    display: flex;
    justify-content: space-between;
  }

  &__cancel-btn,
  &__generate-btn {
    height: 44px;
    padding: 0 $spacing-lg;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    border-radius: $border-radius-md;
    cursor: pointer;
  }

  &__cancel-btn {
    background-color: $white;
    color: $text-primary;
    border: 1px solid $gray-300;

    &:hover {
      background-color: $gray-100;
    }
  }

  &__generate-btn {
    background-color: $primary;
    color: $white;
    border: none;

    &:hover:not(:disabled) {
      background-color: color.adjust($primary, $lightness: -10%);
    }

    &:disabled {
      background-color: $gray-300;
      cursor: not-allowed;
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
