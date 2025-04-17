<template>
  <PageContainer title="Categories" :hasHeader="true" :showBack="true" :noPadding="true">
    <!-- 分类选择部分 -->
    <div class="category-page__category-selector">
      <div class="category-page__categories">
        <div v-for="category in categories" :key="category.id" class="category-page__category-item"
          :class="{ 'category-page__category-item--active': activeCategory == category.type_name }"
          @click="setActiveCategory(category)">
          <i v-if="category.type_name === 'All'" class="fas fa-th-large category-page__category-icon"></i>
          <i v-else-if="category.type_name === 'pizza'" class="fas fa-pizza-slice category-page__category-icon"></i>
          <i v-else-if="category.type_name === 'burger'" class="fas fa-hamburger category-page__category-icon"></i>
          <i v-else-if="category.type_name === 'sushi'" class="fas fa-fish category-page__category-icon"></i>
          <i v-else-if="category.type_name === 'chinese'" class="fas fa-utensils category-page__category-icon"></i>
          <i v-else-if="category.type_name === 'italian'" class="fas fa-cheese category-page__category-icon"></i>
          <i v-else-if="category.type_name === 'mexican'" class="fas fa-pepper-hot category-page__category-icon"></i>
          <i v-else-if="category.type_name === 'healthy'" class="fas fa-seedling category-page__category-icon"></i>
          <i v-else-if="category.type_name === 'dessert'" class="fas fa-ice-cream category-page__category-icon"></i>
          <i v-else-if="category.type_name === 'vegetarian'" class="fas fa-leaf category-page__category-icon"></i>
          <i v-else class="fas fa-utensils category-page__category-icon"></i>
          <span>{{ category.type_name }}</span>
        </div>
      </div>
    </div>

    <div class="category-page__content" ref="contentRef">
      <!-- 结果标题和计数 -->
      <div class="category-page__header">
        <h2 class="category-page__title">
          {{ activeCategory === 'All' ? 'All Restaurants' : activeCategory }}
        </h2>
        <div class="category-page__results-count">
          <i class="fas fa-store-alt"></i> {{ allRestaurants.length }} results
        </div>
      </div>

      <!-- 餐厅列表 -->
      <div class="category-page__restaurants">
        <div v-if="allRestaurants.length === 0" class="category-page__empty-state">
          <i class="fas fa-utensils category-page__empty-icon"></i>
          <h3 class="category-page__empty-title">No restaurants found</h3>
          <p class="category-page__empty-text">
            There are no restaurants in this category at the moment.
          </p>
        </div>
        <div v-else>
          <!-- 餐厅网格 -->
          <div class="category-page__list">
            <div v-for="(restaurant, index) in allRestaurants" :key="restaurant.id"
              class="category-page__restaurant-card" :style="{ animationDelay: `${index * 0.05}s` }"
              @click="navigateToRestaurant(restaurant.id)">
              <div class="category-page__restaurant-image">
                <img style="width: 100%;height: 100%;object-fit: cover;" :src="restaurant.user_cover_list[0].img"
                  :alt="restaurant.shop_title" loading="lazy" />
                <div class="category-page__rating-badge">
                  {{ restaurant.commission_rate || 0 }} <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="category-page__restaurant-info">
                <h3 class="category-page__restaurant-name">{{ restaurant.shop_title }}</h3>
                <div class="category-page__restaurant-tags">
                  <span v-for="(tag, index) in restaurant.user_label_list" :key="index" class="category-page__tag">
                    {{ tag.title }}
                  </span>
                </div>
                <!-- <div class="category-page__restaurant-delivery">
                  <span class="category-page__delivery-time">{{ restaurant.deliveryTime }}</span>
                  <span class="category-page__delivery-fee">${{ restaurant.deliveryFee.toFixed(2) }} delivery</span>
                </div> -->
              </div>
            </div>
          </div>

          <!-- 加载更多指示器 -->
          <div v-if="isLoading" class="category-page__loading">
            <i class="fas fa-spinner fa-spin"></i>
            <span>Loading more restaurants...</span>
          </div>

          <!-- 没有更多数据提示 -->
          <div v-if="!hasMore && allRestaurants.length > 0" class="category-page__no-more">
            You've reached the end of the list
          </div>
        </div>
      </div>
    </div>
  </PageContainer>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue'
import { useRouter } from 'vue-router'
import PageContainer from '@/components/layout/PageContainer.vue'
import { getProductClass, getStoresList } from '@/api'

// 路由
const router = useRouter()

// 引用容器元素
const contentRef = ref<HTMLElement | null>(null)

// 分类数据
const categories: any = ref([])

// 当前选中的分类
const activeCategory = ref('All')

// 获取分类名称


// 设置活跃分类
const setActiveCategory = (item: any) => {
  activeCategory.value = item.type_name
  // 重置分页和加载状态
  currentPage.value = 1
  isLoading.value = false
  hasMore.value = true
  getCurrentRestRant()
}

const getCate = () => {
  getProductClass().then((res) => {
    res.data.unshift({
      id: 12,
      type_name: "All"
    })
    categories.value = res.data
    getCurrentRestRant()
  })
}


const getCurrentRestRant = () => {
  const item = categories.value.find((i: any) => i.type_name == activeCategory.value)


  let data = {
    typeId: item.type_name == 'All' ? "" : item.id,
    page: 1,
    limit: 20
  }
  getStoresList(data).then(res => {
    allRestaurants.value = res.data.data
  })
}

getCate()
// 所有餐厅数据
const allRestaurants: any = ref([])



// 无限加载相关状态
const currentPage = ref(1)
const isLoading = ref(false)
const hasMore = ref(true)



// 导航到餐厅详情页
const navigateToRestaurant = (id: number) => {
  router.push(`/restaurant/${id}`)
}

// 加载数据
onMounted(() => { })

// 清理事件监听
onUnmounted(() => {

})
</script>

<style lang="scss" scoped>
@use 'sass:color';
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.category-page {
  &__category-selector {
    background-color: $white;
    padding: $spacing-md 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    position: sticky;
    top: 0;
    z-index: 20;
  }

  &__categories {
    padding: 0 $spacing-md;
    display: flex;
    overflow-x: auto;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
    scrollbar-width: none;
    /* Firefox */
    -ms-overflow-style: none;
    /* IE and Edge */
    padding-bottom: 4px;

    &::-webkit-scrollbar {
      display: none;
      /* Chrome, Safari and Opera */
    }
  }

  &__category-item {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 36px;
    padding: 0 $spacing-md;
    margin-right: $spacing-sm;
    background-color: #f8f8f8;
    color: $gray-700;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    border-radius: $border-radius-full;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(0, 0, 0, 0.03);
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
    min-width: 70px;

    .category-page__category-icon {
      margin-right: 6px;
      font-size: 12px;
      opacity: 0.8;
    }

    &:hover {
      background-color: #f2f2f2;
      color: $gray-900;
      transform: translateY(-1px);
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
    }

    &:active {
      transform: translateY(0);
      background-color: color.adjust(#ef4444, $lightness: -5%);
    }

    &--active {
      background-color: #ef4444;
      color: $white;
      border-color: #ef4444;
      font-weight: $font-weight-semibold;
      box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);

      &::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom right,
            rgba(255, 255, 255, 0.2),
            rgba(255, 255, 255, 0));
        pointer-events: none;
      }

      &:hover {
        background-color: darken(#ef4444, 5%);
        box-shadow: 0 3px 8px rgba(239, 68, 68, 0.4);
      }

      .category-page__category-icon {
        opacity: 1;
      }
    }
  }

  &__content {
    background-color: #f8f9fa;
    // min-height: calc(100vh - 300px);
    // overflow-y: auto;
    // height: calc(100vh - 300px);
  }

  &__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: $spacing-md $spacing-md $spacing-sm;
    position: sticky;
    top: 0;
    background-color: #f8f9fa;
    z-index: 5;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border-bottom: 1px solid rgba(0, 0, 0, 0.03);
  }

  &__title {
    font-size: $font-size-lg;
    font-weight: $font-weight-bold;
    color: $gray-900;
    margin: 0;
    position: relative;

    &::after {
      content: '';
      position: absolute;
      bottom: -4px;
      left: 0;
      width: 24px;
      height: 2px;
      background-color: #ef4444;
      border-radius: 2px;
    }
  }

  &__results-count {
    font-size: $font-size-sm;
    color: $gray-600;
    background-color: rgba(239, 68, 68, 0.08);
    padding: 4px 10px;
    border-radius: 16px;
    font-weight: $font-weight-medium;
    display: flex;
    align-items: center;

    i {
      margin-right: 5px;
      font-size: 11px;
      color: #ef4444;
    }
  }

  &__restaurants {
    padding: $spacing-sm $spacing-md $spacing-lg;
  }

  &__list {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: $spacing-md;
    animation: fadeInUp 0.4s ease;
  }

  &__restaurant-card {
    background-color: $white;
    border-radius: $spacing-sm;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;

    &::after {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: $spacing-sm;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      opacity: 0;
      transition: opacity 0.3s ease;
      pointer-events: none;
    }

    &:hover {
      transform: translateY(-4px);

      &::after {
        opacity: 1;
      }

      .category-page__restaurant-image img {
        transform: scale(1.08);
      }
    }
  }

  &__restaurant-image {
    position: relative;
    width: 100%;
    height: 130px;
    overflow: hidden;
    background-color: $gray-200;

    &::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 40px;
      background: linear-gradient(to top, rgba(0, 0, 0, 0.3), transparent);
      z-index: 1;
    }

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
  }

  &__rating-badge {
    position: absolute;
    top: $spacing-xs;
    right: $spacing-xs;
    background-color: $white;
    color: $gray-800;
    font-size: $font-size-xs;
    font-weight: $font-weight-semibold;
    padding: 4px 8px;
    border-radius: $border-radius-full;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 3px;

    i {
      color: #f59e0b;
      font-size: $font-size-xs;
    }
  }

  &__restaurant-info {
    padding: $spacing-sm $spacing-sm;
  }

  &__restaurant-name {
    font-size: 15px;
    font-weight: $font-weight-semibold;
    color: $gray-900;
    margin: 0 0 $spacing-xxs;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    letter-spacing: -0.2px;
  }

  &__restaurant-tags {
    margin-bottom: $spacing-xs;
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
  }

  &__tag {
    font-size: $font-size-xxs;
    color: $gray-700;
    background-color: $gray-100;
    padding: 2px 8px;
    border-radius: $border-radius-full;
    white-space: nowrap;
  }

  &__restaurant-delivery {
    display: flex;
    justify-content: space-between;
    font-size: $font-size-xs;
    color: $gray-600;
    border-top: 1px solid $gray-100;
    padding-top: $spacing-xs;
    margin-top: $spacing-xxs;
  }

  &__delivery-time {
    color: $gray-700;
    font-weight: $font-weight-medium;
    display: flex;
    align-items: center;

    &::before {
      content: '';
      display: inline-block;
      width: 10px;
      height: 10px;
      margin-right: 4px;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='10'%3E%3C/circle%3E%3Cpath d='M12 6v6l4 2'%3E%3C/path%3E%3C/svg%3E");
      background-size: contain;
      background-repeat: no-repeat;
      opacity: 0.6;
    }
  }

  &__delivery-fee {
    color: #ef4444;
    font-weight: $font-weight-medium;
  }

  &__loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: $spacing-xl 0;
    color: $gray-600;
    font-size: $font-size-sm;

    i {
      font-size: $font-size-xl;
      margin-bottom: $spacing-sm;
      color: #ef4444;
      animation: pulse 1.5s infinite ease-in-out;
    }

    @keyframes pulse {
      0% {
        opacity: 0.6;
        transform: scale(0.9);
      }

      50% {
        opacity: 1;
        transform: scale(1.1);
      }

      100% {
        opacity: 0.6;
        transform: scale(0.9);
      }
    }
  }

  &__no-more {
    text-align: center;
    padding: $spacing-lg 0;
    color: $gray-600;
    font-size: $font-size-sm;
    font-style: italic;
    position: relative;

    &::before,
    &::after {
      content: '';
      position: absolute;
      top: 50%;
      width: 60px;
      height: 1px;
      background-color: $gray-300;
    }

    &::before {
      left: calc(50% - 120px);
    }

    &::after {
      right: calc(50% - 120px);
    }
  }

  &__empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: $spacing-2xl 0;
    text-align: center;
    background-color: $white;
    border-radius: $spacing-md;
    margin: $spacing-lg;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
  }

  &__empty-icon {
    font-size: 52px;
    color: $gray-400;
    margin-bottom: $spacing-md;
    opacity: 0.7;
  }

  &__empty-title {
    font-size: $font-size-lg;
    font-weight: $font-weight-semibold;
    color: $gray-800;
    margin: 0 0 $spacing-sm;
  }

  &__empty-text {
    font-size: $font-size-sm;
    color: $gray-600;
    max-width: 260px;
    margin: 0 auto;
    line-height: 1.5;
  }

  // 添加触感反馈
  @media (hover: hover) {

    &__category-item,
    &__restaurant-card {
      will-change: transform;
    }
  }
}

// 添加页面进入动画
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

// 优化移动设备布局
@media (max-width: 360px) {
  .category-page {
    &__list {
      gap: $spacing-sm;
    }

    &__restaurant-image {
      height: 110px;
    }
  }
}
</style>
