<template>
  <PageContainer title="Payment Links" :showBack="true">
    <div class="links-page">
      <div class="links-page__header">
        <h1 class="links-page__title">Your Payment Links</h1>
        <p class="links-page__subtitle">Manage your generated payment links</p>
      </div>

      <div class="links-page__links" v-if="paymentLinks.length > 0">
        <div
          v-for="link in paymentLinks"
          :key="link.id"
          class="links-page__link-card"
          @click="viewLinkDetails(link)"
        >
          <div class="links-page__link-header">
            <div class="links-page__link-status" :class="{ active: link.active }">
              {{ link.active ? 'Active' : 'Inactive' }}
            </div>
            <div class="links-page__link-date">Created {{ link.createdAt }}</div>
          </div>

          <div class="links-page__link-content">
            <div class="links-page__link-product">
              <img :src="link.productImage" :alt="link.productName" />
              <div class="links-page__link-product-info">
                <h3 class="links-page__link-product-name">{{ link.productName }}</h3>
                <div class="links-page__link-prices">
                  <span class="links-page__link-discount-price"
                    >${{ link.discountPrice.toFixed(2) }}</span
                  >
                  <span class="links-page__link-original-price"
                    >${{ link.originalPrice.toFixed(2) }}</span
                  >
                </div>
              </div>
            </div>

            <div class="links-page__link-stats">
              <div class="links-page__link-stat">
                <div class="links-page__link-stat-label">Visits</div>
                <div class="links-page__link-stat-value">{{ link.visits }}</div>
              </div>
              <div class="links-page__link-stat">
                <div class="links-page__link-stat-label">Orders</div>
                <div class="links-page__link-stat-value">{{ link.orders }}</div>
              </div>
              <div class="links-page__link-stat">
                <div class="links-page__link-stat-label">Commission</div>
                <div class="links-page__link-stat-value">${{ link.commission.toFixed(2) }}</div>
              </div>
            </div>

            <div class="links-page__link-actions">
              <button class="links-page__link-action-btn copy" @click.stop="copyLink(link)">
                <i class="fas fa-copy"></i>
                <span>Copy</span>
              </button>
              <button class="links-page__link-action-btn share" @click.stop="shareLink(link)">
                <i class="fas fa-share-alt"></i>
                <span>Share</span>
              </button>
              <button
                class="links-page__link-action-btn toggle"
                @click.stop="toggleLinkStatus(link)"
              >
                <i :class="link.active ? 'fas fa-toggle-on' : 'fas fa-toggle-off'"></i>
                <span>{{ link.active ? 'Disable' : 'Enable' }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- 空状态 -->
      <div class="links-page__empty" v-else>
        <div class="links-page__empty-icon">
          <i class="fas fa-link"></i>
        </div>
        <h2 class="links-page__empty-title">No Payment Links Yet</h2>
        <p class="links-page__empty-text">
          You haven't created any payment links yet. Go to a product page and click "Invite Someone
          to Pay" to create your first link.
        </p>
        <button class="links-page__browse-btn" @click="navigateToHome">Browse Products</button>
      </div>
    </div>

    <!-- 链接详情模态框 -->
    <div class="link-details-modal" v-if="showLinkDetails">
      <div class="link-details-modal__backdrop" @click="closeLinkDetails"></div>
      <div class="link-details-modal__content">
        <div class="link-details-modal__header">
          <h2 class="link-details-modal__title">Link Details</h2>
          <button class="link-details-modal__close" @click="closeLinkDetails">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="link-details-modal__body" v-if="selectedLink">
          <div class="link-details-modal__product">
            <img :src="selectedLink.productImage" :alt="selectedLink.productName" />
            <div class="link-details-modal__product-info">
              <h3 class="link-details-modal__product-name">{{ selectedLink.productName }}</h3>
              <div class="link-details-modal__prices">
                <span class="link-details-modal__discount-price"
                  >${{ selectedLink.discountPrice.toFixed(2) }}</span
                >
                <span class="link-details-modal__original-price"
                  >${{ selectedLink.originalPrice.toFixed(2) }}</span
                >
              </div>
            </div>
          </div>

          <div class="link-details-modal__link">
            <div class="link-details-modal__link-label">Payment Link:</div>
            <div class="link-details-modal__link-value">{{ selectedLink.url }}</div>
          </div>

          <div class="link-details-modal__stats">
            <div class="link-details-modal__stat-item">
              <div class="link-details-modal__stat-label">Created</div>
              <div class="link-details-modal__stat-value">{{ selectedLink.createdAt }}</div>
            </div>
            <div class="link-details-modal__stat-item">
              <div class="link-details-modal__stat-label">Status</div>
              <div class="link-details-modal__stat-value" :class="{ active: selectedLink.active }">
                {{ selectedLink.active ? 'Active' : 'Inactive' }}
              </div>
            </div>
            <div class="link-details-modal__stat-item">
              <div class="link-details-modal__stat-label">Visits</div>
              <div class="link-details-modal__stat-value">{{ selectedLink.visits }}</div>
            </div>
            <div class="link-details-modal__stat-item">
              <div class="link-details-modal__stat-label">Orders</div>
              <div class="link-details-modal__stat-value">{{ selectedLink.orders }}</div>
            </div>
            <div class="link-details-modal__stat-item">
              <div class="link-details-modal__stat-label">Commission</div>
              <div class="link-details-modal__stat-value">
                ${{ selectedLink.commission.toFixed(2) }}
              </div>
            </div>
          </div>

          <div class="link-details-modal__actions">
            <button class="link-details-modal__action-btn copy" @click="copyLink(selectedLink)">
              <i class="fas fa-copy"></i>
              <span>Copy Link</span>
            </button>
            <button class="link-details-modal__action-btn share" @click="shareLink(selectedLink)">
              <i class="fas fa-share-alt"></i>
              <span>Share Link</span>
            </button>
            <button
              class="link-details-modal__action-btn toggle"
              @click="toggleLinkStatus(selectedLink)"
            >
              <i :class="selectedLink.active ? 'fas fa-toggle-on' : 'fas fa-toggle-off'"></i>
              <span>{{ selectedLink.active ? 'Disable Link' : 'Enable Link' }}</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </PageContainer>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import PageContainer from '@/components/layout/PageContainer.vue'
import { useUserStore } from '@/stores/modules/user'

// 路由
const router = useRouter()

// 用户状态
const userStore = useUserStore()

// 支付链接类型定义
interface PaymentLink {
  id: number
  productName: string
  productImage: string
  originalPrice: number
  discountPrice: number
  active: boolean
  createdAt: string
  visits: number
  orders: number
  commission: number
  url: string
}

// 支付链接数据
const paymentLinks = ref<PaymentLink[]>([
  {
    id: 1,
    productName: 'Classic Cheeseburger',
    productImage:
      'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    originalPrice: 8.99,
    discountPrice: 7.99,
    active: true,
    createdAt: '2 hours ago',
    visits: 12,
    orders: 3,
    commission: 3.0,
    url: 'https://cokfood.com/pay/101?discount=7.99&name=John',
  },
  {
    id: 2,
    productName: 'Double Bacon Burger',
    productImage:
      'https://images.unsplash.com/photo-1553979459-d2229ba7433b?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    originalPrice: 12.99,
    discountPrice: 10.99,
    active: true,
    createdAt: '1 day ago',
    visits: 8,
    orders: 2,
    commission: 4.0,
    url: 'https://cokfood.com/pay/102?discount=10.99&name=John',
  },
  {
    id: 3,
    productName: 'Margherita Pizza',
    productImage:
      'https://images.unsplash.com/photo-1604917877934-07d8d248d396?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    originalPrice: 12.99,
    discountPrice: 11.99,
    active: false,
    createdAt: '3 days ago',
    visits: 5,
    orders: 1,
    commission: 1.0,
    url: 'https://cokfood.com/pay/201?discount=11.99&name=John',
  },
])

// 链接详情模态框
const showLinkDetails = ref(false)
const selectedLink = ref<PaymentLink | null>(null)

// 复制链接
const copyLink = (link: PaymentLink) => {
  navigator.clipboard
    .writeText(link.url)
    .then(() => {
      alert('Payment link copied to clipboard!')
    })
    .catch((err) => {
      console.error('Failed to copy link: ', err)
    })
}

// 分享链接
const shareLink = (link: PaymentLink) => {
  if (navigator.share) {
    navigator
      .share({
        title: `Pay for ${link.productName}`,
        text: `Help me pay for my food on COKFOOD!`,
        url: link.url,
      })
      .catch((err) => {
        console.error('Error sharing:', err)
      })
  } else {
    // 如果Web Share API不可用，回退到复制链接
    copyLink(link)
  }
}

// 切换链接状态
const toggleLinkStatus = (link: PaymentLink) => {
  link.active = !link.active
  // 这里可以添加API调用来更新链接状态
}

// 查看链接详情
const viewLinkDetails = (link: PaymentLink) => {
  selectedLink.value = link
  showLinkDetails.value = true
}

// 关闭链接详情
const closeLinkDetails = () => {
  showLinkDetails.value = false
}

// 导航到首页
const navigateToHome = () => {
  router.push('/')
}

// 生命周期钩子
onMounted(() => {
  // 检查用户是否是分销商
  if (!userStore.isDistributor) {
    router.push('/')
  }
})
</script>

<style lang="scss" scoped>
@use 'sass:color';
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.links-page {
  padding: $spacing-md;

  &__header {
    margin-bottom: $spacing-lg;
  }

  &__title {
    font-size: $font-size-xl;
    font-weight: $font-weight-bold;
    color: $text-primary;
    margin: 0 0 $spacing-xxs;
  }

  &__subtitle {
    font-size: $font-size-sm;
    color: $text-secondary;
    margin: 0;
  }

  &__links {
    display: flex;
    flex-direction: column;
    gap: $spacing-md;
  }

  &__link-card {
    background-color: $white;
    border-radius: $border-radius-md;
    overflow: hidden;
    box-shadow: $shadow-sm;
    cursor: pointer;
    transition: all $transition-base;

    &:hover {
      box-shadow: $shadow-md;
      transform: translateY(-2px);
    }
  }

  &__link-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: $spacing-sm $spacing-md;
    background-color: $gray-100;
  }

  &__link-status {
    font-size: $font-size-xs;
    font-weight: $font-weight-medium;
    padding: $spacing-xxs $spacing-xs;
    border-radius: $border-radius-pill;
    background-color: $gray-300;
    color: $text-secondary;

    &.active {
      background-color: rgba($success, 0.1);
      color: $success;
    }
  }

  &__link-date {
    font-size: $font-size-xs;
    color: $text-secondary;
  }

  &__link-content {
    padding: $spacing-md;
  }

  &__link-product {
    display: flex;
    align-items: center;
    margin-bottom: $spacing-md;

    img {
      width: 60px;
      height: 60px;
      border-radius: $border-radius-sm;
      object-fit: cover;
      margin-right: $spacing-sm;
    }
  }

  &__link-product-name {
    font-size: $font-size-sm;
    font-weight: $font-weight-semibold;
    color: $text-primary;
    margin: 0 0 $spacing-xxs;
  }

  &__link-prices {
    display: flex;
    align-items: center;
    gap: $spacing-xs;
  }

  &__link-discount-price {
    font-size: $font-size-sm;
    font-weight: $font-weight-semibold;
    color: $primary;
  }

  &__link-original-price {
    font-size: $font-size-xs;
    color: $text-secondary;
    text-decoration: line-through;
  }

  &__link-stats {
    display: flex;
    justify-content: space-between;
    margin-bottom: $spacing-md;
    padding-bottom: $spacing-md;
    border-bottom: 1px solid $gray-200;
  }

  &__link-stat {
    text-align: center;
  }

  &__link-stat-label {
    font-size: $font-size-xxs;
    color: $text-secondary;
    margin-bottom: 2px;
  }

  &__link-stat-value {
    font-size: $font-size-sm;
    font-weight: $font-weight-semibold;
    color: $text-primary;
  }

  &__link-actions {
    display: flex;
    justify-content: space-between;
    gap: $spacing-xs;
  }

  &__link-action-btn {
    flex: 1;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: $font-size-xs;
    font-weight: $font-weight-medium;
    border-radius: $border-radius-md;
    border: none;
    cursor: pointer;

    i {
      margin-right: $spacing-xxs;
    }

    &.copy {
      background-color: $gray-100;
      color: $text-primary;

      &:hover {
        background-color: $gray-200;
      }
    }

    &.share {
      background-color: rgba($info, 0.1);
      color: $info;

      &:hover {
        background-color: rgba($info, 0.2);
      }
    }

    &.toggle {
      background-color: rgba($success, 0.1);
      color: $success;

      &:hover {
        background-color: rgba($success, 0.2);
      }
    }
  }

  &__empty {
    padding: $spacing-xl;
    text-align: center;
    background-color: $white;
    border-radius: $border-radius-md;
    box-shadow: $shadow-sm;
  }

  &__empty-icon {
    width: 80px;
    height: 80px;
    background-color: $gray-100;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto $spacing-md;

    i {
      font-size: 32px;
      color: $gray-400;
    }
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
      background-color: color.adjust($primary, $lightness: -10%);
    }
  }
}

// 链接详情模态框样式
.link-details-modal {
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
    max-height: 90vh;
    overflow-y: auto;
    background-color: $white;
    border-radius: $border-radius-lg;
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
    position: sticky;
    top: 0;
    z-index: 1;
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
    margin-bottom: $spacing-md;
    padding-bottom: $spacing-md;
    border-bottom: 1px solid $gray-200;

    img {
      width: 80px;
      height: 80px;
      border-radius: $border-radius-md;
      object-fit: cover;
      margin-right: $spacing-md;
    }

    &-info {
      flex: 1;
    }

    &-name {
      font-size: $font-size-md;
      font-weight: $font-weight-semibold;
      color: $text-primary;
      margin: 0 0 $spacing-xs;
    }
  }

  &__prices {
    display: flex;
    align-items: center;
    gap: $spacing-sm;
  }

  &__discount-price {
    font-size: $font-size-md;
    font-weight: $font-weight-semibold;
    color: $primary;
  }

  &__original-price {
    font-size: $font-size-sm;
    color: $text-secondary;
    text-decoration: line-through;
  }

  &__link {
    background-color: $gray-100;
    padding: $spacing-md;
    border-radius: $border-radius-md;
    margin-bottom: $spacing-md;

    &-label {
      font-size: $font-size-xs;
      font-weight: $font-weight-medium;
      color: $text-secondary;
      margin-bottom: $spacing-xxs;
    }

    &-value {
      font-size: $font-size-sm;
      color: $text-primary;
      word-break: break-all;
    }
  }

  &__stats {
    margin-bottom: $spacing-md;
  }

  &__stat-item {
    display: flex;
    justify-content: space-between;
    padding: $spacing-sm 0;
    border-bottom: 1px solid $gray-100;

    &:last-child {
      border-bottom: none;
    }
  }

  &__stat-label {
    font-size: $font-size-sm;
    color: $text-secondary;
  }

  &__stat-value {
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    color: $text-primary;

    &.active {
      color: $success;
    }
  }

  &__actions {
    display: flex;
    flex-direction: column;
    gap: $spacing-sm;
  }

  &__action-btn {
    width: 100%;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    border-radius: $border-radius-md;
    border: none;
    cursor: pointer;

    i {
      margin-right: $spacing-xs;
    }

    &.copy {
      background-color: $gray-100;
      color: $text-primary;

      &:hover {
        background-color: $gray-200;
      }
    }

    &.share {
      background-color: rgba($info, 0.1);
      color: $info;

      &:hover {
        background-color: rgba($info, 0.2);
      }
    }

    &.toggle {
      background-color: rgba($success, 0.1);
      color: $success;

      &:hover {
        background-color: rgba($success, 0.2);
      }
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
