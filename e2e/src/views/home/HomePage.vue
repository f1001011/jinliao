<template>
  <div class="home-page">
    <!-- 顶部导航栏 -->
    <header class="navbar-fixed" :class="{ 'shadow-sm': isScrolled }">
      <div class="container">
        <div class="navbar-content">
          <div class="navbar-logo">
            <span class="logo-text">COKFOOD</span>
          </div>
          <div class="navbar-address" @click="showAddressSelection">
            <svg class="address-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span>{{ currentAddress }}</span>
            <svg class="chevron-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </div>
        </div>
      </div>
    </header>

    <!-- 页面内容区域 -->
    <PageContainer :hasHeader="false" :pullRefresh="true" @scroll="handleScroll">
      <!-- 搜索框 -->
      <div class="search-container">
        <div class="search-input" @click="navigateToSearch">
          <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
          <span>Search for restaurants or dishes</span>
        </div>
      </div>

      <!-- 英雄轮播区 -->
      <div class="hero-slider">
        <van-swipe :autoplay="4000" :show-indicators="false" @change="setCurrentBanner">
          <van-swipe-item v-for="(banner, index) in banners" :key="index">
            <div class="hero-slide" @click="handleBannerClick(banner)">
              <div class="hero-slide-placeholder">
                <img style="width: 100%;height: 100%; object-fit: cover;" :src="banner.img" alt="">
              </div>
            </div>
          </van-swipe-item>
        </van-swipe>

        <!-- 轮播指示器 -->
        <div class="hero-indicators">
          <span v-for="(_, index) in banners" :key="index" :class="{ active: currentBanner === index }"
            @click="setCurrentBanner(index)"></span>
        </div>
      </div>

      <!-- 食品分类区 -->
      <div class="section food-categories">
        <div class="section-header">
          <h2>Categories</h2>
          <button class="see-all-btn" @click="navigateToAllCategories">See All</button>
        </div>

        <div class="categories-grid">
          <div class="category-item" v-for="category in categories" :key="category.id"
            @click="navigateToCategory(category)">
            <div class="category-icon">
              <div class="category-icon-placeholder">
                <svg class="placeholder-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                  </path>
                </svg>
              </div>
            </div>
            <span class="category-name">{{ category.type_name }}</span>
          </div>
        </div>
      </div>

      <!-- 特色餐厅区 -->
      <div class="section featured-restaurants">
        <div class="section-header">
          <h2>Featured Restaurants</h2>
        </div>

        <div class="restaurant-list">
          <div v-for="restaurant in featuredRestaurants" :key="restaurant.id" class="restaurant-card"
            @click="navigateToRestaurant(restaurant)">
            <div class="restaurant-card-image">
              <div class="restaurant-image-placeholder">
                <img style="width: 100%;height: 100%;object-fit: cover;" :src="restaurant.cover_list[0].img" alt="">
              </div>
            </div>
            <div class="restaurant-card-content">
              <h3 class="restaurant-name">{{ restaurant.shop_title }}</h3>
              <!-- <div class="restaurant-info">
                <span class="restaurant-time">{{ restaurant.deliveryTime }}</span>
                <span class="restaurant-separator">•</span>
                <span class="restaurant-delivery">{{ restaurant.deliveryFee }}</span>
              </div> -->
            </div>
          </div>
        </div>
      </div>

      <!-- 热门菜品区 -->
      <div class="section popular-dishes">
        <div class="section-header">
          <h2>Popular Dishes</h2>
        </div>

        <div class="dishes-grid">
          <div v-for="dish in displayedDishes" :key="dish.id" class="dish-card" @click="navigateToDish(dish)">
            <div class="dish-card-image">
              <div class="dish-image-placeholder">
                <img style="width: 100%;height: 100%;object-fit: cover;" :src="dish.head_img[0]" alt="">
              </div>
            </div>
            <div class="dish-card-content">
              <h3 class="dish-name">{{ dish.goods_name }}</h3>
              <div class="dish-info">
                <span class="dish-restaurant">{{ dish.restaurantName }}</span>
              </div>
              <div class="dish-footer">
                <span class="dish-price">${{ dish.goods_money.toFixed(2) }}</span>
                <div class="dish-rating">
                  <span>{{ dish.rating || 0 }}</span>
                  <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                    </path>
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- 加载更多按钮 -->
        <!-- <div v-if="hasMoreDishes && !isLoadingMore" class="load-more">
          <button class="load-more-btn" @click="loadMoreDishes">Load More Dishes</button>
        </div> -->

        <!-- 加载中指示器 -->
        <div v-if="isLoadingMore" class="loading-indicator">
          <svg class="spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
            </path>
          </svg>
          <span>Loading more dishes...</span>
        </div>
      </div>
    </PageContainer>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import PageContainer from '@/components/layout/PageContainer.vue'
import { showToast } from 'vant'
import type { Banner, Category, Restaurant, Dish } from '@/types/models'
import { getHotList, getProductClass, getRestRant, getBannerList } from '@/api/index'

// 路由实例
const router = useRouter()

// 基础状态
const isScrolled = ref(false)
const currentAddress = ref('123 Main Street')
const currentBanner = ref(0)

// 热门菜品加载状态
const isLoadingMore = ref(false)
const currentPage = ref(1)
const pageSize = ref(6)
const hasMoreDishes = ref(true)

// Mock 数据 - 轮播图
const banners: any = ref([])



const categories: any = ref([])

const featuredRestaurants: any = ref([])

// Mock 数据 - 热门菜品
const allPopularDishes: any = ref([])

const getCate = () => {
  getProductClass().then((res) => {
    categories.value = res.data.slice(0, 5);
  })
}

const getStores = () => {
  getRestRant().then((res) => {
    featuredRestaurants.value = res.data
  })
}


const getHot = () => {
  getHotList().then((res) => {
    allPopularDishes.value = res.data
  })
}


const getBanner = () => {
  getBannerList().then(res => {
    banners.value = res.data
  })
}


getBanner()
getHot()
getStores()
getCate()







// 计算当前展示的菜品
const displayedDishes = computed(() => {
  const endIndex = currentPage.value * pageSize.value
  return allPopularDishes.value.slice(0, endIndex)
})

// 加载更多菜品
const loadMoreDishes = () => {
  if (isLoadingMore.value) return

  isLoadingMore.value = true

  // 模拟API请求延迟
  setTimeout(() => {
    currentPage.value += 1

    // 检查是否还有更多数据
    if (currentPage.value * pageSize.value >= allPopularDishes.value.length) {
      hasMoreDishes.value = false
    }

    isLoadingMore.value = false
  }, 800)
}

// 处理滚动事件
const handleScroll = (position: { scrollTop: number }) => {
  isScrolled.value = position.scrollTop > 10
}


// 显示地址选择
const showAddressSelection = () => {
  showToast('Address selection will be implemented')
}

// 导航到搜索页面
const navigateToSearch = () => {
  router.push('/search')
}

// 处理轮播图点击
const handleBannerClick = (banner: Banner) => {
  router.push(banner.link)
}

// 设置当前轮播图索引
const setCurrentBanner = (index: number) => {
  currentBanner.value = index
}

// 导航到分类页面
const navigateToCategory = (category: Category) => {
  router.push(`/category/${category.id}`)
}

// 导航到餐厅详情页面
const navigateToRestaurant = (restaurant: Restaurant) => {
  router.push(`/restaurant/${restaurant.user_id}`)
}

// 导航到菜品详情页面
const navigateToDish = (dish: Dish) => {
  router.push(`/product/${dish.id}`)
}

// 导航到所有分类页面
const navigateToAllCategories = () => {
  router.push('/categories')
}

// 生命周期钩子
onMounted(() => {
  // 轮播图自动更新索引
  const bannerInterval = setInterval(() => {
    currentBanner.value = (currentBanner.value + 1) % banners.value.length
  }, 4000)

  // 组件卸载时清除定时器
  return () => {
    clearInterval(bannerInterval)
  }
})
</script>

<style lang="scss" scoped>
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

// 页面基础样式
.home-page {
  position: relative;
  background-color: #f9f9f9;
  min-height: 100vh;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
}

// 顶部导航栏样式
.navbar-fixed {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 56px;
  background-color: white;
  z-index: 50;
  transition: all 0.3s ease;

  &.shadow-sm {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  }

  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 16px;
    height: 100%;
  }

  .navbar-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 100%;
  }

  .navbar-logo {
    .logo-text {
      font-size: 22px;
      font-weight: 700;
      color: #ef4444;
      letter-spacing: 0.5px;
      text-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
    }
  }

  .navbar-address {
    display: flex;
    align-items: center;
    padding: 8px 14px;
    border-radius: 24px;
    cursor: pointer;
    font-size: 14px;
    background-color: #f9f9f9;
    transition: all 0.2s ease;

    &:hover {
      background-color: #f5f5f5;
    }

    .address-icon {
      width: 16px;
      height: 16px;
      color: #ef4444;
      margin-right: 6px;
    }

    span {
      font-weight: 500;
      color: #333;
    }

    .chevron-icon {
      width: 14px;
      height: 14px;
      margin-left: 6px;
      color: #777;
    }
  }
}

// 搜索栏样式
.search-container {
  padding: 72px 16px 16px;

  .search-input {
    display: flex;
    align-items: center;
    background-color: white;
    height: 48px;
    padding: 0 16px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease;

    &:hover {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      transform: translateY(-1px);
    }

    .search-icon {
      width: 20px;
      height: 20px;
      color: #ef4444;
      margin-right: 12px;
      opacity: 0.8;
    }

    span {
      color: #999;
      font-size: 15px;
    }
  }
}

// 英雄轮播区样式
.hero-slider {
  position: relative;
  margin: 0 16px 28px;

  .hero-slide {
    position: relative;
    height: 160px;
    border-radius: 16px;
    overflow: hidden;
    background-color: #f3f3f3;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);

    .hero-slide-placeholder {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;

      .placeholder-icon {
        width: 32px;
        height: 32px;
        color: #ccc;
      }
    }

    .hero-slide-gradient {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      padding: 24px;
      background: linear-gradient(to right, rgba(0, 0, 0, 0.3), transparent);
    }

    .hero-slide-content {
      max-width: 80%;

      h2 {
        color: white;
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 8px;
        line-height: 1.2;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
      }

      p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 14px;
        margin: 0;
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
      }
    }
  }

  .hero-indicators {
    display: flex;
    justify-content: center;
    margin-top: 14px;

    span {
      width: 6px;
      height: 6px;
      background-color: #ddd;
      border-radius: 50%;
      margin: 0 4px;
      cursor: pointer;
      transition: all 0.3s ease;

      &.active {
        width: 22px;
        border-radius: 4px;
        background-color: #ef4444;
      }
    }
  }
}

// 通用栏目样式
.section {
  margin-bottom: 32px;
  // padding: 0 16px;

  .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;

    h2 {
      font-size: 20px;
      font-weight: 700;
      color: #222;
      margin: 0;
      position: relative;
      padding-left: 12px;

      &::before {
        content: '';
        position: absolute;
        left: 0;
        top: 3px;
        bottom: 3px;
        width: 4px;
        background-color: #ef4444;
        border-radius: 4px;
      }
    }

    .see-all-btn {
      background: none;
      border: none;
      color: #ef4444;
      font-size: 14px;
      font-weight: 600;
      padding: 6px 12px;
      cursor: pointer;
      border-radius: 6px;
      transition: all 0.2s;

      &:hover {
        background-color: rgba(239, 68, 68, 0.08);
      }

      &:active {
        background-color: rgba(239, 68, 68, 0.15);
      }
    }
  }
}

// 分类网格样式
.categories-grid {
  display: flex;
  overflow-x: auto;
  padding-bottom: 12px;
  gap: 20px;
  margin-bottom: 8px;

  &::-webkit-scrollbar {
    display: none;
  }

  .category-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 80px;
    cursor: pointer;
    transition: transform 0.3s ease;

    &:hover {
      transform: translateY(-4px);

      .category-icon {
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.15);
      }
    }

    &:active {
      transform: translateY(-2px);
    }

    .category-icon {
      width: 68px;
      height: 68px;
      border-radius: 50%;
      background-color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      border: 1px solid rgba(0, 0, 0, 0.04);
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;

      &::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.08), rgba(239, 68, 68, 0.16));
      }

      .category-icon-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 1;

        .placeholder-icon {
          width: 32px;
          height: 32px;
          color: #ef4444;
          opacity: 0.85;
        }
      }
    }

    .category-name {
      font-size: 14px;
      font-weight: 600;
      color: #333;
      text-align: center;
      white-space: nowrap;
      margin-top: 4px;
    }
  }
}

// 餐厅列表样式
.restaurant-list {
  display: flex;
  flex-direction: column;
  gap: 16px;

  .restaurant-card {
    display: flex;
    flex-direction: column;
    background-color: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;

    &:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    }

    .restaurant-card-image {
      width: 100%;
      height: 140px;
      background-color: #f0f0f0;
      position: relative;

      &::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50px;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.1), transparent);
      }

      .restaurant-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;

        .placeholder-icon {
          width: 32px;
          height: 32px;
          color: #ccc;
        }
      }
    }

    .restaurant-card-content {
      padding: 16px;
    }

    .restaurant-name {
      font-size: 18px;
      font-weight: 600;
      color: #222;
      margin: 0 0 6px;
    }

    .restaurant-info {
      font-size: 13px;
      color: #666;
      display: flex;
      align-items: center;

      .restaurant-time {
        color: #555;
        font-weight: 500;
      }

      .restaurant-separator {
        margin: 0 6px;
        color: #ccc;
      }

      .restaurant-delivery {
        color: #666;
      }
    }
  }
}

// 菜品网格样式
.dishes-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;

  .dish-card {
    background-color: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;

    &:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);

      .dish-card-image .dish-image-placeholder {
        transform: scale(1.05);
      }
    }

    .dish-card-image {
      width: 100%;
      height: 130px;
      background-color: #f0f0f0;
      overflow: hidden;

      .dish-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.5s ease;

        .placeholder-icon {
          width: 32px;
          height: 32px;
          color: #ccc;
        }
      }
    }

    .dish-card-content {
      padding: 14px;
    }

    .dish-name {
      font-size: 15px;
      font-weight: 600;
      color: #222;
      margin: 0 0 6px;
      @include text-truncate;
    }

    .dish-info {
      margin-bottom: 8px;

      .dish-restaurant {
        font-size: 13px;
        color: #666;
        font-weight: 500;
        @include text-truncate;
      }
    }

    .dish-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;

      .dish-price {
        font-size: 15px;
        font-weight: 700;
        color: #ef4444;
      }

      .dish-rating {
        display: flex;
        align-items: center;
        font-size: 13px;
        color: #555;
        font-weight: 600;
        background-color: #fff9e6;
        padding: 3px 8px;
        border-radius: 12px;

        .star-icon {
          width: 13px;
          height: 13px;
          color: #ffcc00;
          margin-left: 3px;
        }
      }
    }
  }
}

// 加载更多按钮
.load-more {
  text-align: center;
  margin-top: 24px;

  .load-more-btn {
    background-color: #ef4444;
    color: white;
    font-size: 15px;
    font-weight: 600;
    padding: 12px 28px;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    transition: all 0.3s ease;

    &:hover {
      background-color: #e03535;
      box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3);
      transform: translateY(-2px);
    }

    &:active {
      transform: translateY(0);
    }
  }
}

// 加载中指示器
.loading-indicator {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: 24px;
  color: #666;
  font-size: 15px;
  font-weight: 500;

  .spinner {
    width: 20px;
    height: 20px;
    margin-right: 10px;
    animation: spin 1s linear infinite;
    color: #ef4444;
  }

  @keyframes spin {
    from {
      transform: rotate(0deg);
    }

    to {
      transform: rotate(360deg);
    }
  }
}

// 动画
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.4s ease forwards;
}

// 添加波纹动画效果
@keyframes ripple {
  0% {
    box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.1);
  }

  100% {
    box-shadow: 0 0 0 20px rgba(239, 68, 68, 0);
  }
}

// 为主要交互元素添加焦点状态
.category-item:focus-visible,
.restaurant-card:focus-visible,
.dish-card:focus-visible,
.load-more-btn:focus-visible,
.search-input:focus-visible,
.navbar-address:focus-visible {
  outline: 2px solid #ef4444;
  outline-offset: 2px;
}
</style>
