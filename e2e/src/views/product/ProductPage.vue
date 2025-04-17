<template>
  <PageContainer :title="'Product Details'" :noPadding="true">
    <div class="product-page">
      <!-- 返回按钮 -->
      <div class="product-page__back">
        <button @click="$router.go(-1)" class="product-page__back-btn">
          <i class="fas fa-arrow-left"></i>
        </button>
      </div>

      <!-- 商品图片轮播 -->
      <div class="product-page__gallery">
        <swiper
          :slides-per-view="1"
          :pagination="{ clickable: true }"
          :modules="[SwiperPagination]"
          class="product-page__swiper"
        >
          <swiper-slide v-for="(image, index) in getGoodsInfo?.head_img" :key="index">
            <div class="product-page__image">
              <img :src="image"/>
            </div>
          </swiper-slide>
        </swiper>
      </div>

      <!-- 商品信息卡片 -->
      <div class="product-page__card">
        <!-- 商品基本信息 -->
        <div class="product-page__header">
          <div class="product-page__title-row">
            <h1 class="product-page__name">{{ getGoodsInfo?.goods_name }}</h1>
            <div class="product-page__price">${{ getGoodsInfo?.goods_money.toFixed(2) }}</div>
          </div>

          <div class="product-page__restaurant">{{ getGoodsInfo?.goodsType.type_name }}</div>

          <div class="product-page__rating">
            <div class="product-page__stars">
              <i class="fas fa-star"></i>
              <span class="product-page__rating-value">{{ getGoodsInfo?.star }}</span>
            </div>
            <span class="product-page__reviews">({{ getGoodsInfo?.buy_num }})</span>
          </div>

          <div class="product-page__description">
            {{ getGoodsInfo?.describe }}
          </div>
        </div>

        <!-- 营养与过敏原信息 -->
       <div class="product-page__nutrition">
        <!--  <h2 class="product-page__section-title">Nutrition Info</h2>
          <div class="product-page__nutrition-items">
            <div class="product-page__nutrition-item">
              <span class="product-page__nutrition-value">{{ product?.nutrition?.calories }}</span>
              <span class="product-page__nutrition-label">Calories</span>
            </div>
            <div class="product-page__nutrition-item">
              <span class="product-page__nutrition-value">{{ product?.nutrition?.protein }}g</span>
              <span class="product-page__nutrition-label">Protein</span>
            </div>
            <div class="product-page__nutrition-item">
              <span class="product-page__nutrition-value">{{ product?.nutrition?.carbs }}g</span>
              <span class="product-page__nutrition-label">Carbs</span>
            </div>
            <div class="product-page__nutrition-item">
              <span class="product-page__nutrition-value">{{ product?.nutrition?.fat }}g</span>
              <span class="product-page__nutrition-label">Fat</span>
            </div>
          </div> -->

          <div class="product-page__allergens">
            <h2 class="product-page__section-title">Allergens</h2>
            <div class="product-page__allergen-tags">
              <span
                v-for="(allergen, index) in getGoodsInfo?.goodsLabel"
                :key="index"
                class="product-page__allergen-tag"
              >
                {{ allergen.title }}
              </span>
            </div>
          </div>
        </div>

        <!-- 辣度选择 -->
        <div
          v-if="getGoodsInfo?.prices && getGoodsInfo.prices.length > 0"
          class="product-page__spice"
        >
          <h2 class="product-page__section-title">Spice Level</h2>
          <div class="product-page__spice-options">
            <div
              v-for="(level, index) in getGoodsInfo.prices"
              :key="index"
              :class="[
                'product-page__spice-option',
                { active: selectedSpiceLevel === level.id },
              ]"
              @click="selectedSpiceLevel = level.id"
            >
              <span class="product-page__spice-name">{{ level.money_name }}</span>
              <!-- <span class="product-page__spice-icon">
                <i class="fas fa-pepper-hot" v-for="n in level.id" :key="n"></i>
              </span> -->
            </div>
          </div>
        </div>

        <!-- 数量选择 -->
        <div class="product-page__quantity">
          <h2 class="product-page__section-title">Quantity</h2>
          <div class="product-page__quantity-selector">
            <button
              class="product-page__quantity-btn"
              @click="decreaseQuantity"
              :disabled="quantity <= 1"
            >
              <i class="fas fa-minus"></i>
            </button>
            <span class="product-page__quantity-value">{{ quantity }}</span>
            <button class="product-page__quantity-btn" @click="increaseQuantity">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>

        <!-- 收藏和分享按钮 -->
        <!-- <div class="product-page__actions-row">
          <button class="product-page__icon-btn" @click="toggleFavorite">
            <i class="fas" :class="isFavorite ? 'fa-heart' : 'fa-heart'"></i>
            <span>Favorite</span>
          </button>
          <button class="product-page__icon-btn">
            <i class="fas fa-share-alt"></i>
            <span>Share</span>
          </button>
        </div> -->
      </div>

      <!-- 操作按钮区域 -->
      <div class="product-page__actions">
        <div class="product-page__total">
          <span class="product-page__total-label">Total:</span>
          <span class="product-page__total-price">${{ totalPrice }}</span>
        </div>
        <button class="product-page__pay-btn" @click="handlePayment">Pay Now</button>
      </div>

      <!-- 邀请代付按钮 -->
      <div class="product-page__invite-payment">
        <button class="product-page__invite-btn" @click="navigateToCheckout">
          <i class="fas fa-user-friends"></i>
          <span>Invite Someone to Pay</span>
        </button>
      </div>

      <!-- 详情标签页 -->
      <div class="product-page__tabs">
        <div class="product-page__tab-header">
          <div
            v-for="tab in filteredTabs"
            :key="tab.id"
            :class="['product-page__tab', { active: activeTab === tab.id }]"
            @click="activeTab = tab.id"
          >
            {{ tab.name }}
          </div>
        </div>

        <div class="product-page__tab-content">
          <!-- 描述标签内容 -->
          <div v-if="activeTab === 'description'" class="product-page__tab-pane">
			<ul class="product-page__ingredients-list" v-html="getGoodsInfo?.bottom_text">
			</ul>
          </div>

          <!-- 配料标签内容 -->
          <div v-if="activeTab === 'ingredients'" class="product-page__tab-pane">
            <ul class="product-page__ingredients-list" v-html="getGoodsInfo?.ingredient">
            </ul>
          </div>

          <!-- 评价标签内容 -->
          <div v-if="activeTab === 'reviews'" class="product-page__tab-pane">
            <div class="product-page__reviews-summary">
              <div class="product-page__reviews-rating">
                <span class="product-page__reviews-score">{{ product?.rating }}</span>
                <div class="product-page__reviews-stars">
                  <div
                    class="product-page__reviews-stars-filled"
                    :style="{ width: `${(product?.rating || 0) * 20}%` }"
                  ></div>
                </div>
              </div>
              <div class="product-page__reviews-count">{{ product?.reviewCount }} reviews</div>
            </div>

            <div class="product-page__reviews-list">
              <div
                v-for="review in productReviews"
                :key="review.id"
                class="product-page__review-item"
              >
                <div class="product-page__review-header">
                  <div class="product-page__review-avatar">
                    <img :src="review.avatar" :alt="review.name" />
                  </div>
                  <div class="product-page__review-info">
                    <div class="product-page__review-name">{{ review.name }}</div>
                    <div class="product-page__review-meta">
                      <div class="product-page__review-rating">
                        <i class="fas fa-star"></i>
                        <span>{{ review.rating }}</span>
                      </div>
                      <div class="product-page__review-date">{{ formatDate(review.date) }}</div>
                    </div>
                  </div>
                </div>
                <div class="product-page__review-content">
                  {{ review.comment }}
                </div>
                <!-- 添加评价图片显示 -->
                <div
                  v-if="review.images && review.images.length > 0"
                  class="product-page__review-images"
                >
                  <div
                    v-for="(image, index) in review.images"
                    :key="index"
                    class="product-page__review-image-container"
                    @click="openImagePreview(review.images, index)"
                  >
                    <img
                      :src="image"
                      :alt="`Review image ${index + 1}`"
                      class="product-page__review-image"
                    />
                  </div>
                </div>
              </div>
            </div>

            <div v-if="hasMoreReviews" class="product-page__load-more">
              <button class="product-page__load-more-btn" @click="loadMoreReviews">
                Load More Reviews
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- 相关推荐 -->
      <div class="product-page__related">
        <h2 class="product-page__section-title">You May Also Like</h2>
        <div class="product-page__related-items">
          <div
            v-for="item in relatedProducts"
            :key="item.id"
            class="product-page__related-item"
            @click="navigateToProduct(item.id)"
          >
            <div class="product-page__related-image">
              <img :src="item.image" :alt="item.name" />
            </div>
            <div class="product-page__related-info">
              <h3 class="product-page__related-name">{{ item.name }}</h3>
              <div class="product-page__related-price">${{ item.price.toFixed(2) }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- 分销商折扣设置模态框 -->
      <div v-if="showDiscountModal" class="modal-base">
        <div class="modal-base__backdrop" @click="closeDiscountModal"></div>
        <div class="modal-base__content discount-modal">
          <div class="discount-modal__header">
            <h3 class="discount-modal__title">Invite Someone to Pay</h3>
            <button class="discount-modal__close" @click="closeDiscountModal">
              <i class="fas fa-times"></i>
            </button>
          </div>
          <div class="discount-modal__body">
            <div class="discount-modal__price-row">
              <div class="discount-modal__price-label">Original Price:</div>
              <div class="discount-modal__price-value">
                ${{ product ? product.price.toFixed(2) : '0.00' }}
              </div>
            </div>

            <div class="discount-modal__price-row">
              <div class="discount-modal__price-label">Discount Price:</div>
              <div class="discount-modal__price-input-container">
                <span class="discount-modal__currency">$</span>
                <input
                  type="text"
                  class="discount-modal__price-input"
                  v-model="discountAmount"
                  placeholder="0.00"
                />
              </div>
            </div>

            <div class="discount-modal__slider-container">
              <input
                type="range"
                class="discount-modal__slider"
                min="0"
                max="50"
                step="1"
                v-model="discountPercent"
                @input="handleDiscountChange(Number(discountPercent))"
              />
            </div>

            <div class="discount-modal__slider-labels">
              <span>${{ minDiscountPrice.toFixed(2) }}</span>
              <span>${{ product ? product.price.toFixed(2) : '0.00' }}</span>
            </div>

            <div class="discount-modal__commission-container">
              <div class="discount-modal__commission-row">
                <div class="discount-modal__commission-label">Your Commission:</div>
                <div class="discount-modal__commission-value">${{ commission.toFixed(2) }}</div>
              </div>
              <div class="discount-modal__commission-info">
                You save {{ discountPercent }}% off the original price
              </div>
            </div>

            <div class="discount-modal__name-container">
              <div class="discount-modal__name-label">Friend's Name:</div>
              <input
                type="text"
                v-model="inviteForm.displayName"
                class="discount-modal__name-input"
                placeholder="Enter your friend's name"
              />
            </div>

            <div class="discount-modal__preview">
              "You're helping
              <span class="discount-modal__preview-name">{{
                inviteForm.displayName || 'Friend'
              }}</span>
              pay for their food delivery"
            </div>
          </div>

          <div class="discount-modal__footer">
            <button
              class="discount-modal__button discount-modal__button--primary"
              @click="handleDistributorGenerateLink"
              :disabled="isLoading"
            >
              <span v-if="!isLoading">Save & Share Link</span>
              <span v-else>Processing...</span>
            </button>
          </div>
        </div>
      </div>

      <!-- 普通用户邀请代付模态框 -->
      <div v-if="showInviteModal" class="modal-base">
        <div class="modal-base__backdrop" @click="closeInviteModal"></div>
        <div class="modal-base__content invite-modal">
          <div class="invite-modal__header">
            <h3 class="invite-modal__title">Invite Someone to Pay</h3>
            <button class="invite-modal__close" @click="closeInviteModal">
              <i class="fas fa-times"></i>
            </button>
          </div>
          <div class="invite-modal__body">
            <p class="invite-modal__description">
              Enter the delivery address details to generate a payment link you can share.
            </p>

            <div class="invite-modal__form">
              <div class="invite-modal__form-group">
                <label class="invite-modal__label"
                  >First Name <span class="invite-modal__required">*</span></label
                >
                <input
                  v-model="inviteForm.firstName"
                  type="text"
                  class="invite-modal__input"
                  placeholder="John"
                />
              </div>

              <div class="invite-modal__form-group">
                <label class="invite-modal__label"
                  >Last Name <span class="invite-modal__required">*</span></label
                >
                <input
                  v-model="inviteForm.lastName"
                  type="text"
                  class="invite-modal__input"
                  placeholder="Doe"
                />
              </div>

              <div class="invite-modal__form-group">
                <label class="invite-modal__label"
                  >Phone Number <span class="invite-modal__required">*</span></label
                >
                <input
                  v-model="inviteForm.phone"
                  type="tel"
                  class="invite-modal__input"
                  placeholder="(123) 456-7890"
                />
              </div>

              <div class="invite-modal__form-group">
                <label class="invite-modal__label"
                  >Address Line 1 <span class="invite-modal__required">*</span></label
                >
                <input
                  v-model="inviteForm.address.line1"
                  type="text"
                  class="invite-modal__input"
                  placeholder="123 Main St"
                />
              </div>

              <div class="invite-modal__form-group">
                <label class="invite-modal__label">Address Line 2 (Optional)</label>
                <input
                  v-model="inviteForm.address.line2"
                  type="text"
                  class="invite-modal__input"
                  placeholder="Apt 4B"
                />
              </div>

              <div class="invite-modal__form-row">
                <div class="invite-modal__form-group">
                  <label class="invite-modal__label"
                    >City <span class="invite-modal__required">*</span></label
                  >
                  <input
                    v-model="inviteForm.address.city"
                    type="text"
                    class="invite-modal__input"
                    placeholder="New York"
                  />
                </div>

                <div class="invite-modal__form-group">
                  <label class="invite-modal__label"
                    >State/Province <span class="invite-modal__required">*</span></label
                  >
                  <input
                    v-model="inviteForm.address.state"
                    type="text"
                    class="invite-modal__input"
                    placeholder="NY"
                  />
                </div>
              </div>

              <div class="invite-modal__form-group">
                <label class="invite-modal__label"
                  >ZIP/Postal Code <span class="invite-modal__required">*</span></label
                >
                <input
                  v-model="inviteForm.address.zipCode"
                  type="text"
                  class="invite-modal__input"
                  placeholder="10001"
                />
              </div>

              <div class="invite-modal__form-group">
                <label class="invite-modal__label">Delivery Instructions (Optional)</label>
                <textarea
                  v-model="inviteForm.deliveryInstructions"
                  class="invite-modal__textarea"
                  placeholder="E.g., Ring doorbell, leave at door, etc."
                ></textarea>
              </div>
            </div>
          </div>

          <div class="invite-modal__footer">
            <button
              class="invite-modal__button"
              @click="handleGenerateInviteLink"
              :disabled="isLoading"
            >
              <span v-if="!isLoading">Generate Payment Link</span>
              <span v-else>Processing...</span>
            </button>
          </div>
        </div>
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
              Your address is outside our delivery range. Please enter a different address within
              our service area.
            </p>
          </div>

          <div class="delivery-error-modal__footer">
            <button class="delivery-error-modal__button" @click="closeDeliveryErrorModal">
              Try Another Address
            </button>
          </div>
        </div>
      </div>

      <!-- 支付链接成功模态框 -->
      <div v-if="showPaymentLinkSuccessModal" class="modal-base">
        <div class="modal-base__backdrop" @click="closePaymentLinkSuccessModal"></div>
        <div class="modal-base__content payment-link-modal">
          <button class="payment-link-modal__close" @click="closePaymentLinkSuccessModal">
            <i class="fas fa-times"></i>
          </button>

          <div class="payment-link-modal__body">
            <div class="payment-link-modal__icon-container">
              <div class="payment-link-modal__icon">
                <i class="fas fa-check-circle"></i>
              </div>
            </div>

            <h3 class="payment-link-modal__title">Share Payment Link</h3>

            <p class="payment-link-modal__description">
              Share this link with someone to let them pay for this order.
            </p>

            <div class="payment-link-modal__link-container">
              <div class="payment-link-modal__link">
                <div class="payment-link-modal__link-value">{{ paymentLink }}</div>
                <button
                  class="payment-link-modal__link-copy"
                  @click="copyPaymentLink"
                  title="Copy link"
                >
                  <i class="fas fa-copy"></i>
                </button>
              </div>
            </div>

            <div class="payment-link-modal__qr-container">
              <div class="payment-link-modal__qr-code">
                <img :src="qrCodeUrl" alt="QR Code" />
              </div>
              <p class="payment-link-modal__qr-caption">Scan with your camera</p>
            </div>

            <div class="payment-link-modal__details">
              <div class="payment-link-modal__detail-item">
                <span class="payment-link-modal__detail-label">Original Price:</span>
                <span class="payment-link-modal__detail-value"
                  >${{ product?.price.toFixed(2) }}</span
                >
              </div>

              <div class="payment-link-modal__detail-item">
                <span class="payment-link-modal__detail-label">Discount Price:</span>
                <span class="payment-link-modal__detail-value payment-link-modal__price"
                  >${{ discountAmount.toFixed(2) }}</span
                >
              </div>

              <div class="payment-link-modal__detail-item">
                <span class="payment-link-modal__detail-label">Recipient Name:</span>
                <span class="payment-link-modal__detail-value">{{
                  inviteForm.displayName || userStore.currentUser?.name || 'Friend'
                }}</span>
              </div>

              <div class="payment-link-modal__detail-item">
                <span class="payment-link-modal__detail-label">Your Commission:</span>
                <span class="payment-link-modal__detail-value payment-link-modal__commission"
                  >${{ commission.toFixed(2) }}</span
                >
              </div>
            </div>

            <div class="payment-link-modal__social">
              <p class="payment-link-modal__social-text">Share via:</p>
              <div class="payment-link-modal__social-buttons">
                <button class="payment-link-modal__social-btn" @click="sharePaymentLink">
                  <i class="fas fa-share-alt"></i>
                </button>
                <button class="payment-link-modal__social-btn" @click="copyPaymentLink">
                  <i class="fas fa-copy"></i>
                </button>
              </div>
            </div>
          </div>

          <div class="payment-link-modal__footer">
            <button class="payment-link-modal__done-btn" @click="closePaymentLinkSuccessModal">
              Done
            </button>
          </div>
        </div>
      </div>

      <!-- 添加图片预览模态框 -->
      <div v-if="showImagePreview" class="product-page__image-preview-modal">
        <div class="product-page__image-preview-backdrop" @click="closeImagePreview"></div>
        <button class="product-page__image-preview-close" @click="closeImagePreview">
          <i class="fas fa-times"></i>
        </button>
        <button
          v-if="currentImageIndex > 0"
          class="product-page__image-preview-nav product-page__image-preview-prev"
          @click="prevImage"
        >
          <i class="fas fa-chevron-left"></i>
        </button>
        <div class="product-page__image-preview-content">
          <img
            :src="currentPreviewImages[currentImageIndex]"
            alt="Preview"
            class="product-page__image-preview-img"
          />
        </div>
        <button
          v-if="currentImageIndex < currentPreviewImages.length - 1"
          class="product-page__image-preview-nav product-page__image-preview-next"
          @click="nextImage"
        >
          <i class="fas fa-chevron-right"></i>
        </button>
        <div class="product-page__image-preview-counter">
          {{ currentImageIndex + 1 }} / {{ currentPreviewImages.length }}
        </div>
      </div>
    </div>
  </PageContainer>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Pagination as SwiperPagination } from 'swiper/modules'
import PageContainer from '@/components/layout/PageContainer.vue'
import DiscountSettingModal from '@/components/distributor/DiscountSettingModal.vue'
import { useUserStore } from '@/stores/modules/user'
import {getGoodsDetails} from '@/api'
// 导入样式
import 'swiper/css'
import 'swiper/css/pagination'

// 路由和导航
const route = useRoute()
const router = useRouter()
const userStore = useUserStore()

// 商品数据类型
interface ProductNutrition {
  calories: number
  protein: number
  carbs: number
  fat: number
}

interface SpiceLevel {
  name: string
  value: number
}

interface Product {
  id: number
  name: string
  description: string
  longDescription: string
  price: number
  images: string[]
  rating: number
  reviewCount: number
  nutrition: ProductNutrition
  allergens: string[]
  ingredients: string[]
  spiceLevels?: SpiceLevel[]
  restaurantId: number
  restaurantName: string
}

interface Review {
  id: number
  name: string
  avatar: string
  rating: number
  date: string
  comment: string
  images?: string[] // 添加评价图片数组
}

interface RelatedProduct {
  id: number
  name: string
  description: string
  price: number
  image: string
}

// 邀请表单类型
interface InviteForm {
  displayName: string
  firstName: string
  lastName: string
  phone: string
  address: {
    line1: string
    line2: string
    city: string
    state: string
    zipCode: string
  }
  deliveryInstructions: string
}

// 状态管理
const product = ref<Product | null>(null)
const quantity = ref(1)
const selectedSpiceLevel = ref(1)
const isFavorite = ref(false)
const activeTab = ref('description')
const productReviews = ref<Review[]>([])
const hasMoreReviews = ref(true)
const relatedProducts = ref<RelatedProduct[]>([])

// 邀请代付相关状态
const showDiscountModal = ref(false)
const showInviteModal = ref(false)
const showDeliveryErrorModal = ref(false)
const showPaymentLinkSuccessModal = ref(false)
const paymentLink = ref('')
const qrCodeUrl = ref('')
const isLoading = ref(false)

// 邀请表单
const inviteForm = ref<InviteForm>({
  displayName: '',
  firstName: '',
  lastName: '',
  phone: '',
  address: {
    line1: '',
    line2: '',
    city: '',
    state: '',
    zipCode: '',
  },
  deliveryInstructions: '',
})

// 标签页定义
const allTabs = [
  { id: 'description', name: 'Description' },
  { id: 'ingredients', name: 'Ingredients' },
  { id: 'reviews', name: 'Reviews', distributor: true }, // 标记为经销商专属
]

// 根据用户角色过滤标签页
const filteredTabs = computed(() => {
  return allTabs.filter((tab) => !tab.distributor || userStore.isDistributor)
})

// 折扣设置相关
const discountPercent = ref(20) // 默认折扣比例
const minDiscountPrice = computed(() => {
  if (!product.value) return 0
  return product.value.price * 0.5 // 最低折扣价格为原价的50%
})

const discountAmount = computed({
  get: () => {
    if (!product.value) return 0
    // 根据折扣计算优惠后价格
    return Number((product.value.price * (1 - discountPercent.value / 100)).toFixed(2))
  },
  set: (value) => {
    if (!product.value) return
    // 根据输入价格反向计算折扣比例
    const newPercent = 100 - (value / product.value.price) * 100
    discountPercent.value = Math.min(50, Math.max(0, Math.round(newPercent)))
  },
})

const commission = computed(() => {
  if (!product.value) return 0
  // 计算佣金 (原价 - 折扣价) * 佣金比例
  const commissionRate = 0.5 // 佣金比例，例如50%
  return (product.value.price - discountAmount.value) * commissionRate
})

const getGoodsInfo: any = ref([])
const goodsId = 1
const getGoodsDetail = () => {
	getGoodsDetails({goodsId:goodsId}).then(res => {
    getGoodsInfo.value = res.data
	})

}
getGoodsDetail();

// 处理折扣滑块变化
const handleDiscountChange = (percent: number) => {
  discountPercent.value = percent
}

// 处理分销商代付链接生成
const handleDistributorGenerateLink = () => {
  if (!product.value) return

  isLoading.value = true

  // 分销商姓名和折扣价格
  const distributorName = userStore.currentUser?.name || '分销商'
  const discountPrice = discountAmount.value

  // 获取当前环境的URL（本地或生产环境）
  const baseUrl = window.location.origin

  // 模拟API调用
  setTimeout(() => {
    isLoading.value = false

    // 生成支付链接
    paymentLink.value = `${baseUrl}/payment/${product.value?.id || '0'}?distributor=${encodeURIComponent(distributorName)}&price=${discountPrice.toFixed(2)}&name=${encodeURIComponent('Friend')}`

    // 生成二维码
    qrCodeUrl.value = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(paymentLink.value)}`

    // 打开模态框
    showPaymentLinkSuccessModal.value = true
  }, 1000)
}

// 关闭支付链接成功模态框
const closePaymentLinkSuccessModal = () => {
  showPaymentLinkSuccessModal.value = false
}

// 复制支付链接
const copyPaymentLink = () => {
  navigator.clipboard
    .writeText(paymentLink.value)
    .then(() => {
      alert('链接已复制到剪贴板')
    })
    .catch((err) => {
      console.error('无法复制链接:', err)
      alert('复制失败，请手动复制链接')
    })
}

// 分享支付链接
const sharePaymentLink = () => {
  if (navigator.share) {
    navigator
      .share({
        title: `Pay for ${product.value?.name}`,
        text: `Help me pay for my food on COKFOOD!`,
        url: paymentLink.value,
      })
      .catch((err) => {
        console.error('Error sharing:', err)
      })
  } else {
    // 如果Web Share API不可用，回退到复制链接
    copyPaymentLink()
  }
}

// 导航到相关商品
const navigateToProduct = (productId: number) => {
  router.push(`/product/${productId}`)
}

// 格式化日期
const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

// 加载更多评价
const loadMoreReviews = () => {
  // 模拟加载更多评价
  const moreReviews: Review[] = [
    {
      id: 4,
      name: 'Emily Davis',
      avatar: 'https://i.pravatar.cc/100?img=9',
      rating: 3,
      date: '2023-09-28',
      comment: 'Decent burger but a bit dry for my taste. Would have preferred more sauce.',
    },
    {
      id: 5,
      name: 'David Wilson',
      avatar: 'https://i.pravatar.cc/100?img=15',
      rating: 4,
      date: '2023-09-22',
      comment: 'Really good flavor! The meat was perfectly cooked and juicy.',
    },
  ]

  productReviews.value = [...productReviews.value, ...moreReviews]
  hasMoreReviews.value = false // 示例中加载一次后就没有更多了
}

// 获取商品数据
const fetchProductData = async () => {
  try {
    const id = route.params.id
    console.log(`Fetching product data for ID: ${id}`)

    // 模拟API请求
    setTimeout(() => {
      // 设置商品数据
      product.value = mockProductData

      // 设置默认辣度等级
      if (product.value.spiceLevels && product.value.spiceLevels.length > 0) {
        selectedSpiceLevel.value = product.value.spiceLevels[0].value
      }

      // 设置商品评价
      productReviews.value = mockReviews

      // 设置相关商品
      relatedProducts.value = mockRelatedProducts
    }, 500)
  } catch (error) {
    console.error('Error fetching product data:', error)
  }
}

// 模拟数据
const mockProductData: Product = {
  id: 101,
  name: 'Classic Cheeseburger',
  description: 'Juicy beef patty with melted cheese, lettuce, tomato, and special sauce',
  longDescription:
    "Our signature Classic Cheeseburger features a 100% pure beef patty, flame-grilled to perfection. It's topped with melted American cheese, crisp lettuce, juicy tomatoes, sliced onions, pickles, and our special house sauce, all served on a freshly toasted sesame seed bun. Each burger is made to order, ensuring you get the freshest, most delicious burger every time.",
  price: 8.99,
  images: [
    'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
    'https://images.unsplash.com/photo-1550317138-10000687a72b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
    'https://images.unsplash.com/photo-1572802419224-296b0aeee0d9?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
  ],
  rating: 4.7,
  reviewCount: 123,
  nutrition: {
    calories: 650,
    protein: 35,
    carbs: 40,
    fat: 32,
  },
  allergens: ['Gluten', 'Dairy', 'Soy'],
  ingredients: [
    '100% Pure Beef Patty',
    'American Cheese',
    'Sesame Seed Bun',
    'Lettuce',
    'Tomato',
    'Onion',
    'Pickles',
    'Special Sauce (contains egg, mustard)',
  ],
  spiceLevels: [
    { name: 'Mild', value: 1 },
    { name: 'Medium', value: 2 },
    { name: 'Spicy', value: 3 },
    { name: 'Extra Spicy', value: 4 },
  ],
  restaurantId: 1,
  restaurantName: 'Burger Palace',
}

const mockReviews: Review[] = [
  {
    id: 1,
    name: 'John Smith',
    avatar: 'https://i.pravatar.cc/100?img=1',
    rating: 5,
    date: '2023-10-15',
    comment:
      'Absolutely amazing burger! The meat was juicy and the cheese was perfectly melted. Will definitely order again!',
    images: [
      'https://images.unsplash.com/photo-1550950158-d0d960dff51b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
      'https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
    ],
  },
  {
    id: 2,
    name: 'Sarah Johnson',
    avatar: 'https://i.pravatar.cc/100?img=5',
    rating: 4,
    date: '2023-10-10',
    comment:
      "Great burger, really enjoyed the flavor. The special sauce makes it stand out from other burgers I've tried.",
    images: [
      'https://images.unsplash.com/photo-1565299507177-b0ac66763828?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
    ],
  },
  {
    id: 3,
    name: 'Michael Brown',
    avatar: 'https://i.pravatar.cc/100?img=12',
    rating: 5,
    date: '2023-10-05',
    comment:
      'Best burger in town! The ingredients were fresh and the patty was cooked to perfection.',
  },
]

const mockRelatedProducts: RelatedProduct[] = [
  {
    id: 102,
    name: 'Double Bacon Burger',
    description: 'Two beef patties with crispy bacon, cheese, and BBQ sauce',
    price: 12.99,
    image:
      'https://images.unsplash.com/photo-1553979459-d2229ba7433b?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
  },
  {
    id: 103,
    name: 'Chicken Burger',
    description: 'Crispy chicken fillet with lettuce, mayo, and pickles',
    price: 9.99,
    image:
      'https://images.unsplash.com/photo-1572802419224-296b0aeee0d9?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
  },
  {
    id: 105,
    name: 'French Fries',
    description: 'Crispy golden fries with sea salt',
    price: 3.99,
    image:
      'https://images.unsplash.com/photo-1576107232684-1279f390859f?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
  },
  {
    id: 108,
    name: 'Milkshake',
    description: 'Creamy vanilla, chocolate, or strawberry milkshake',
    price: 5.99,
    image:
      'https://images.unsplash.com/photo-1577805947697-89e18249d767?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
  },
]

// 生命周期钩子
onMounted(() => {
  fetchProductData()
})

// 计算总价
const totalPrice = computed(() => {
  if (!product.value) return '0.00'
  return (product.value.price * quantity.value).toFixed(2)
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

// 切换收藏状态
const toggleFavorite = () => {
  isFavorite.value = !isFavorite.value
}

// 添加到购物车
const addToCart = () => {
  if (!product.value) return

  // 这里将来会集成实际的购物车功能
  console.log('Adding to cart:', {
    product: product.value,
    quantity: quantity.value,
    spiceLevel: selectedSpiceLevel.value,
  })

  // 显示成功提示
  alert(`Added ${quantity.value} ${product.value.name} to cart!`)
}

// 支付处理逻辑
const handlePayment = () => {
  if (!product.value) return

  // 直接跳转到订单填写页面
  router.push(
    `/checkout?productId=${product.value.id}&quantity=${quantity.value}&spiceLevel=${selectedSpiceLevel.value}`,
  )
}

// 邀请代付处理逻辑
const handleInvitePayment = () => {
  if (userStore.isDistributor) {
    // 分销商用户显示折扣设置模态框
    showDiscountModal.value = true
  } else {
    // 普通用户显示地址输入模态框
    showInviteModal.value = true
  }
}

// 导航到结账页面
const navigateToCheckout = () => {
  if (!product.value) return
  handleInvitePayment()
}

// 生成邀请链接
const handleGenerateInviteLink = () => {
  // 检查普通用户表单必填项是否填写完整
  if (!userStore.isDistributor) {
    const requiredFields = [
      { field: inviteForm.value.firstName, name: 'First Name' },
      { field: inviteForm.value.lastName, name: 'Last Name' },
      { field: inviteForm.value.phone, name: 'Phone Number' },
      { field: inviteForm.value.address.line1, name: 'Address Line 1' },
      { field: inviteForm.value.address.city, name: 'City' },
      { field: inviteForm.value.address.state, name: 'State/Province' },
      { field: inviteForm.value.address.zipCode, name: 'ZIP/Postal Code' },
    ]

    const missingFields = requiredFields.filter((item) => !item.field.trim())

    if (missingFields.length > 0) {
      // 显示错误提示
      alert(
        `Please fill in the following required fields: ${missingFields.map((item) => item.name).join(', ')}`,
      )
      return
    }
  }

  // 关闭地址输入模态框
  showInviteModal.value = false

  // 设置displayName (如果用户没有直接在displayName中输入)
  if (!inviteForm.value.displayName) {
    inviteForm.value.displayName =
      `${inviteForm.value.firstName} ${inviteForm.value.lastName}`.trim()
  }

  // 处理不同用户类型
  if (userStore.isDistributor) {
    // 分销商用户 - 生成链接并显示链接成功模态框
    const distributorName = inviteForm.value.displayName || userStore.currentUser?.name
    const discountPrice = discountAmount.value

    // 模拟 API 调用生成链接
    setTimeout(() => {
      // 生成当前环境的URL（本地或生产环境）
      const baseUrl = window.location.origin

      // 生成支付链接
      paymentLink.value = `${baseUrl}/payment/${product.value?.id || '0'}?distributor=${encodeURIComponent(distributorName)}&price=${discountPrice.toFixed(2)}&name=${encodeURIComponent(String(inviteForm.value?.displayName || 'Friend'))}`

      // 生成二维码
      qrCodeUrl.value = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(paymentLink.value)}`

      // 显示链接生成成功模态框
      showPaymentLinkSuccessModal.value = true
    }, 1000)
  } else {
    // 普通用户 - 始终显示不在配送范围错误
    const isInDeliveryRange = false // 普通用户永远不在配送范围

    if (!isInDeliveryRange) {
      // 显示不在配送范围错误模态框
      showDeliveryErrorModal.value = true
    }
  }
}

// 关闭不在配送范围错误模态框
const closeDeliveryErrorModal = () => {
  showDeliveryErrorModal.value = false
  // 不再重新打开邀请模态框，直接回到产品页
}

// 关闭折扣设置模态框
const closeDiscountModal = () => {
  showDiscountModal.value = false
}

// 关闭邀请模态框
const closeInviteModal = () => {
  showInviteModal.value = false
}

// 添加图片预览功能
const currentPreviewImages = ref<string[]>([])
const currentImageIndex = ref(0)
const showImagePreview = ref(false)

const openImagePreview = (images: string[], index: number) => {
  currentPreviewImages.value = images
  currentImageIndex.value = index
  showImagePreview.value = true
}

const closeImagePreview = () => {
  showImagePreview.value = false
}

const nextImage = () => {
  if (currentImageIndex.value < currentPreviewImages.value.length - 1) {
    currentImageIndex.value++
  }
}

const prevImage = () => {
  if (currentImageIndex.value > 0) {
    currentImageIndex.value--
  }
}

// 确保非经销商用户不能访问评价标签页
watch(activeTab, (newTab) => {
  const tab = allTabs.find((t) => t.id === newTab)
  if (tab?.distributor && !userStore.isDistributor) {
    activeTab.value = 'description' // 默认回到描述标签
  }
})
</script>

<style lang="scss" scoped>
@use 'sass:color';
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.product-page {
  display: flex;
  flex-direction: column;
  background-color: #f8f8f8;

  &__back {
    position: absolute;
    top: 16px;
    left: 16px;
    z-index: 10;
  }

  &__back-btn {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    cursor: pointer;

    i {
      color: #333;
      font-size: 16px;
    }
  }

  &__gallery {
    width: 100%;
    height: 280px;
    position: relative;
  }

  &__swiper {
    width: 100%;
    height: 100%;
  }

  &__image {
    width: 100%;
    height: 100%;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

  &__card {
    margin-top: -20px;
    margin-bottom: 12px;
    padding: 20px 16px;
    background-color: #fff;
    border-radius: 20px 20px 0 0;
    box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.05);
    position: relative;
    z-index: 2;
  }

  &__header {
    margin-bottom: 20px;
  }

  &__title-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 4px;
  }

  &__name {
    font-size: 22px;
    font-weight: 700;
    color: #333;
    margin: 0;
    flex: 1;
  }

  &__restaurant {
    font-size: 14px;
    color: #666;
    margin-bottom: 8px;
  }

  &__price {
    font-size: 22px;
    font-weight: 700;
    color: #ef4444;
  }

  &__rating {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
  }

  &__stars {
    display: flex;
    align-items: center;
    margin-right: 6px;

    i {
      color: #ffb800;
      margin-right: 4px;
    }
  }

  &__rating-value {
    font-weight: 600;
    color: #333;
  }

  &__reviews {
    color: #666;
    font-size: 14px;
  }

  &__description {
    font-size: 14px;
    line-height: 1.5;
    color: #666;
    margin: 12px 0 16px;
  }

  &__section-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 24px 0 12px;
  }

  &__nutrition {
    margin-bottom: 16px;

    &-items {
      display: flex;
      justify-content: space-between;
      background-color: #f9f9f9;
      border-radius: 12px;
      padding: 16px;
    }

    &-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    &-value {
      font-size: 16px;
      font-weight: 600;
      color: #333;
      margin-bottom: 4px;
    }

    &-label {
      font-size: 12px;
      color: #666;
    }
  }

  &__allergens {
    margin-top: 20px;
  }

  &__allergen-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }

  &__allergen-tag {
    font-size: 12px;
    color: #666;
    background-color: #f0f0f0;
    padding: 6px 12px;
    border-radius: 100px;
  }

  &__spice {
    margin-bottom: 20px;

    &-options {
      display: flex;
      gap: 10px;
      overflow-x: auto;
      padding-bottom: 8px;

      &::-webkit-scrollbar {
        display: none;
      }
    }

    &-option {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 10px;
      min-width: 70px;
      border: 1px solid #eee;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.2s ease;

      &.active {
        border-color: #ef4444;
        background-color: rgba(239, 68, 68, 0.05);

        .product-page__spice-name {
          color: #ef4444;
          font-weight: 500;
        }
      }

      &:hover {
        border-color: #ef4444;
      }
    }

    &-name {
      font-size: 13px;
      margin-bottom: 4px;
    }

    &-icon {
      color: #ef4444;
      font-size: 12px;

      i {
        margin-right: 2px;

        &:last-child {
          margin-right: 0;
        }
      }
    }
  }

  &__quantity {
    margin-bottom: 24px;

    &-selector {
      display: flex;
      align-items: center;
      width: fit-content;
      border: 1px solid #eee;
      border-radius: 8px;
      overflow: hidden;
    }

    &-btn {
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #fff;
      border: none;
      cursor: pointer;

      &:disabled {
        color: #ccc;
        cursor: not-allowed;
      }

      &:hover:not(:disabled) {
        background-color: #f5f5f5;
      }
    }

    &-value {
      width: 40px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 15px;
      font-weight: 500;
      border-left: 1px solid #eee;
      border-right: 1px solid #eee;
    }
  }

  &__actions-row {
    display: flex;
    gap: 16px;
    margin-bottom: 24px;
  }

  &__icon-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 0;

    i {
      font-size: 20px;
    }

    span {
      font-size: 12px;
    }

    &:hover {
      color: #ef4444;
    }

    i.fa-heart {
      color: #ef4444;
    }
  }

  &__actions {
    padding: 16px;
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
    margin-bottom: 10px;
  }

  &__total {
    &-label {
      font-size: 14px;
      color: #666;
      margin-right: 4px;
    }

    &-price {
      font-size: 20px;
      font-weight: 700;
      color: #ef4444;
    }
  }

  &__pay-btn {
    height: 44px;
    background-color: #ef4444;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 0 24px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;

    &:hover {
      background-color: color.adjust(#ef4444, $lightness: -5%);
    }
  }

  &__invite-payment {
    padding: 0 16px;
    margin-bottom: 20px;
  }

  &__invite-btn {
    width: 100%;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(to right, #ef4444, #f87171);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(239, 68, 68, 0.25);

    i {
      margin-right: 8px;
    }

    &:hover {
      background: linear-gradient(to right, #dc2626, #ef4444);
      box-shadow: 0 3px 8px rgba(239, 68, 68, 0.35);
    }

    &:active {
      transform: translateY(1px);
    }
  }

  &__tabs {
    background-color: #fff;
    margin-bottom: 20px;
  }

  &__tab-header {
    display: flex;
    border-bottom: 1px solid #eee;
  }

  &__tab {
    flex: 1;
    padding: 14px 0;
    text-align: center;
    font-size: 14px;
    font-weight: 500;
    color: #666;
    cursor: pointer;
    position: relative;

    &.active {
      color: #ef4444;

      &:after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 30%;
        right: 30%;
        height: 2px;
        background-color: #ef4444;
      }
    }
  }

  &__tab-content {
    padding: 16px;
  }

  &__tab-pane {
    font-size: 14px;
    line-height: 1.6;
    color: #666;

    p {
      margin: 0 0 16px;
    }
  }

  &__ingredients-list {
    list-style-type: disc;
    padding-left: 20px;

    li {
      margin-bottom: 8px;
    }
  }

  &__reviews-summary {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
  }

  &__reviews-rating {
    display: flex;
    align-items: center;
    margin-right: 16px;
  }

  &__reviews-score {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin-right: 10px;
  }

  &__reviews-stars {
    position: relative;
    width: 100px;
    height: 20px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23E0E0E0'%3E%3Cpath d='M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z'/%3E%3C/svg%3E");
    background-repeat: repeat-x;
    background-size: 20px 20px;

    &-filled {
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23FFB800'%3E%3Cpath d='M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z'/%3E%3C/svg%3E");
      background-repeat: repeat-x;
      background-size: 20px 20px;
    }
  }

  &__reviews-count {
    font-size: 14px;
    color: #666;
  }

  &__review-item {
    padding: 16px 0;
    border-bottom: 1px solid #eee;

    &:last-child {
      border-bottom: none;
    }
  }

  &__review-header {
    display: flex;
    margin-bottom: 10px;
  }

  &__review-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 12px;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

  &__review-name {
    font-size: 14px;
    font-weight: 500;
    color: #333;
    margin-bottom: 4px;
  }

  &__review-meta {
    display: flex;
    align-items: center;
  }

  &__review-rating {
    display: flex;
    align-items: center;
    margin-right: 12px;
    font-size: 13px;
    color: #ffb800;

    i {
      margin-right: 4px;
    }
  }

  &__review-date {
    font-size: 12px;
    color: #999;
  }

  &__review-content {
    font-size: 14px;
    line-height: 1.5;
    color: #666;
  }

  &__load-more {
    text-align: center;
    padding-top: 16px;

    &-btn {
      padding: 8px 16px;
      background-color: #f5f5f5;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      color: #666;
      cursor: pointer;

      &:hover {
        background-color: #eee;
      }
    }
  }

  &__related {
    padding: 16px;
    margin-bottom: 30px;

    &-items {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
    }

    &-item {
      background-color: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      cursor: pointer;

      &:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      }
    }

    &-image {
      height: 100px;

      img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
    }

    &-info {
      padding: 12px;
    }

    &-name {
      font-size: 14px;
      font-weight: 500;
      color: #333;
      margin: 0 0 6px;
      @include text-truncate;
    }

    &-price {
      font-size: 14px;
      font-weight: 600;
      color: #ef4444;
    }
  }

  &__review-images {
    display: flex;
    gap: 8px;
    margin-top: 12px;
    flex-wrap: wrap;
  }

  &__review-image-container {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    position: relative;

    &:hover {
      &::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.1);
      }
    }
  }

  &__review-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;

    &:hover {
      transform: scale(1.05);
    }
  }

  &__image-preview-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 30;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  &__image-preview-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
  }

  &__image-preview-close {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 40px;
    height: 40px;
    background-color: rgba(255, 255, 255, 0.2);
    border: none;
    border-radius: 50%;
    color: white;
    font-size: 18px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;

    &:hover {
      background-color: rgba(255, 255, 255, 0.3);
    }
  }

  &__image-preview-content {
    position: relative;
    max-width: 90%;
    max-height: 80vh;
    z-index: 1;
  }

  &__image-preview-img {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
    border-radius: 4px;
  }

  &__image-preview-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 50px;
    height: 50px;
    background-color: rgba(255, 255, 255, 0.2);
    border: none;
    border-radius: 50%;
    color: white;
    font-size: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;

    &:hover {
      background-color: rgba(255, 255, 255, 0.3);
    }

    &.product-page__image-preview-prev {
      left: 20px;
    }

    &.product-page__image-preview-next {
      right: 20px;
    }
  }

  &__image-preview-counter {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(0, 0, 0, 0.6);
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 14px;
    z-index: 2;
  }
}

// 通用模态框样式
.modal-base {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 20;
  display: flex;
  align-items: center;
  justify-content: center;

  &__backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
  }

  &__content {
    position: relative;
    width: 90%;
    max-width: 480px;
    max-height: 90vh;
    overflow-y: auto;
    background-color: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    animation: modal-fade-in 0.3s ease-out;
  }

  &__header {
    padding: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #eee;
    position: sticky;
    top: 0;
    z-index: 1;
    background-color: #fff;
  }

  &__title {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
  }

  &__close {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    color: #666;
    font-size: 16px;
    cursor: pointer;
    border-radius: 50%;

    &:hover {
      background-color: #f5f5f5;
    }
  }

  &__body {
    padding: 16px;
  }

  &__footer {
    padding: 16px;
    background-color: #f8f8f8;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    border-top: 1px solid #eee;
  }
}

// 折扣设置模态框
.discount-modal {
  &__header {
    background-color: #fff;
    color: #333;
    border-bottom: 1px solid #eee;
    padding: 16px 20px;
  }

  &__title {
    color: #333;
    font-size: 18px;
    font-weight: 600;
  }

  &__close {
    color: #666;

    &:hover {
      background-color: #f5f5f5;
    }
  }

  &__body {
    padding: 20px;
  }

  &__price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
  }

  &__price-label {
    font-size: 16px;
    font-weight: 500;
    color: #333;
  }

  &__price-value {
    font-size: 16px;
    font-weight: 600;
    color: #333;
  }

  &__price-input-container {
    position: relative;
    width: 120px;
  }

  &__currency {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    color: #333;
  }

  &__price-input {
    width: 100%;
    height: 44px;
    padding: 0 12px 0 24px;
    font-size: 16px;
    font-weight: 500;
    border: 1px solid #ddd;
    border-radius: 8px;
    text-align: right;

    &:focus {
      outline: none;
      border-color: #ef4444;
      box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.1);
    }
  }

  &__slider-container {
    margin-bottom: 8px;
  }

  &__slider {
    width: 100%;
    height: 6px;
    -webkit-appearance: none;
    appearance: none;
    background: linear-gradient(to right, #ef4444, #f87171);
    outline: none;
    border-radius: 3px;

    &::-webkit-slider-thumb {
      -webkit-appearance: none;
      appearance: none;
      width: 22px;
      height: 22px;
      border-radius: 50%;
      background: #fff;
      border: 2px solid #ef4444;
      cursor: pointer;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
  }

  &__slider-labels {
    display: flex;
    justify-content: space-between;
    margin-bottom: 24px;
    font-size: 14px;
    color: #666;
  }

  &__commission-container {
    background-color: #f9f9f9;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 24px;
  }

  &__commission-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
  }

  &__commission-label {
    font-size: 16px;
    font-weight: 500;
    color: #333;
  }

  &__commission-value {
    font-size: 16px;
    font-weight: 600;
    color: #10b981;
  }

  &__commission-info {
    font-size: 14px;
    color: #666;
  }

  &__name-container {
    margin-bottom: 12px;
  }

  &__name-label {
    font-size: 16px;
    font-weight: 500;
    color: #333;
    margin-bottom: 8px;
  }

  &__name-input {
    width: 100%;
    height: 44px;
    padding: 0 12px;
    font-size: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;

    &:focus {
      outline: none;
      border-color: #ef4444;
      box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.1);
    }
  }

  &__preview {
    background-color: #fff6f6;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 14px;
    color: #ef4444;
    margin-top: 12px;
    text-align: center;
  }

  &__preview-name {
    font-weight: 600;
  }

  &__footer {
    padding: 20px;
    text-align: center;
    border-top: 1px solid #eee;
  }

  &__button {
    height: 48px;
    font-size: 16px;
    font-weight: 500;
    border-radius: 8px;
    padding: 0 24px;
    cursor: pointer;

    &--primary {
      background-color: #ef4444;
      color: #fff;
      border: none;
      width: 100%;

      &:hover:not(:disabled) {
        background-color: #dc2626;
      }

      &:disabled {
        background-color: #f5a5a5;
        cursor: not-allowed;
      }
    }
  }
}

// 普通用户邀请代付模态框
.invite-modal {
  &__header {
    background-color: #fff;
    border-bottom: 1px solid #eee;
    padding: 16px 20px;
  }

  &__title {
    color: #333;
    font-size: 20px;
    font-weight: 600;
  }

  &__close {
    color: #666;

    &:hover {
      background-color: #f5f5f5;
    }
  }

  &__body {
    padding: 20px;
  }

  &__description {
    text-align: center;
    color: #666;
    margin: 0 0 20px;
    font-size: 15px;
    line-height: 1.5;
  }

  &__form {
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  &__form-row {
    display: flex;
    gap: 16px;

    .invite-modal__form-group {
      flex: 1;
    }
  }

  &__label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #333;
    margin-bottom: 6px;
  }

  &__required {
    color: #ef4444;
  }

  &__input {
    width: 100%;
    height: 44px;
    padding: 0 12px;
    font-size: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;

    &:focus {
      outline: none;
      border-color: #ef4444;
      box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.1);
    }

    &::placeholder {
      color: #aaa;
    }
  }

  &__textarea {
    width: 100%;
    height: 80px;
    padding: 12px;
    font-size: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    resize: vertical;

    &:focus {
      outline: none;
      border-color: #ef4444;
      box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.1);
    }

    &::placeholder {
      color: #aaa;
    }
  }

  &__footer {
    padding: 20px;
    text-align: center;
    border-top: 1px solid #eee;
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

    &:hover:not(:disabled) {
      background-color: #dc2626;
    }

    &:disabled {
      background-color: #f5a5a5;
      cursor: not-allowed;
    }
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

// 支付链接成功模态框
.payment-link-modal {
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
    background-color: #e6f7ee;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;

    i {
      font-size: 32px;
      color: #10b981;
    }
  }

  &__title {
    font-size: 22px;
    font-weight: 600;
    color: #333;
    margin: 0 0 12px;
  }

  &__description {
    font-size: 15px;
    line-height: 1.5;
    color: #666;
    margin: 0 0 24px;
  }

  &__link-container {
    margin-bottom: 24px;
  }

  &__link {
    display: flex;
    align-items: center;
    background-color: #f5f5f5;
    border-radius: 8px;
    padding: 4px;
    overflow: hidden;
    border: 1px solid #eee;
  }

  &__link-value {
    flex: 1;
    padding: 12px;
    font-size: 14px;
    text-align: left;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #333;
  }

  &__link-copy {
    width: 44px;
    height: 44px;
    border: none;
    background-color: #fff;
    color: #666;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);

    &:hover {
      background-color: #f0f0f0;
      color: #ef4444;
    }

    &:active {
      transform: scale(0.95);
    }
  }

  &__qr-container {
    margin-bottom: 24px;
  }

  &__qr-code {
    width: 180px;
    height: 180px;
    margin: 0 auto 8px;
    padding: 8px;
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);

    img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }
  }

  &__qr-caption {
    font-size: 13px;
    color: #666;
    margin: 0;
  }

  &__details {
    background-color: #f9f9f9;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 24px;
  }

  &__detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #eee;

    &:last-child {
      border-bottom: none;
    }
  }

  &__detail-label {
    font-size: 14px;
    color: #666;
  }

  &__detail-value {
    font-size: 14px;
    font-weight: 500;
    color: #333;
  }

  &__price {
    color: #ef4444;
    font-weight: 600;
  }

  &__commission {
    color: #10b981;
    font-weight: 600;
  }

  &__social {
    margin-bottom: 16px;
  }

  &__social-text {
    font-size: 14px;
    color: #666;
    margin: 0 0 8px;
  }

  &__social-buttons {
    display: flex;
    justify-content: center;
    gap: 12px;
  }

  &__social-btn {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background-color: #f5f5f5;
    border: none;
    color: #666;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;

    &:hover {
      background-color: #ef4444;
      color: #fff;
    }

    &:active {
      transform: scale(0.95);
    }
  }

  &__footer {
    padding: 16px 24px 24px;
    border-top: 1px solid #eee;
  }

  &__done-btn {
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

    &:active {
      transform: translateY(1px);
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
