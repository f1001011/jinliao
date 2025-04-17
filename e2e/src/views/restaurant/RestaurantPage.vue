<template>
  <PageContainer
    :hasTitle="false"
    :pullRefresh="true"
    :noPadding="true"
    @refresh="fetchRestaurantData"
  >
    <div class="restaurant-page">
      <!-- 餐厅封面区域 -->
      <div class="restaurant-cover">
        <div class="back-button" @click="$router.back()">
          <svg
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 19l-7-7 7-7"
            ></path>
          </svg>
        </div>
        <div class="restaurant-image">
          <img :src="restaurant?.coverImage" alt="Restaurant Cover" />
        </div>
      </div>

      <!-- 餐厅详情区域 -->
      <div class="restaurant-details">
        <h1 class="restaurant-name">{{ restaurant?.name }}</h1>

        <div class="restaurant-tags">
          <span v-for="(tag, index) in restaurant?.tags" :key="index">{{ tag }}</span>
        </div>

        <div class="restaurant-info">
          <div class="rating-container">
            <svg
              class="star-icon"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
              ></path>
            </svg>
            <span class="rating-text"
              >{{ restaurant?.rating }} ({{ restaurant?.ratingCount || '243' }})</span
            >
          </div>
       <!--   <div class="delivery-info">
            <svg
              class="clock-icon"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
              ></path>
            </svg>
            <span>{{ restaurant?.deliveryTime }}</span>
            <span class="dot-separator">•</span>
            <span class="delivery-fee">{{ restaurant?.deliveryFee }}</span>
          </div> -->
        </div>

        <p class="restaurant-description">
          {{
            restaurant?.description ||
            'Authentic Italian cuisine with a modern twist. Our dishes are made with fresh, locally sourced ingredients.'
          }}
        </p>
      </div>

      <!-- 搜索菜单栏 -->
      <div class="menu-search">
        <svg
          class="search-icon"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
          ></path>
        </svg>
        <input type="text" placeholder="Search menu items" />
      </div>

      <!-- 菜单分类导航 -->
      <div class="category-tabs">
        <div
          class="tab-item"
          v-for="category in getProductClassList"
          :key="category.id"
          :class="{ active: activeCategory === category.id }"
          @click="scrollToCategory(category.id)"
        >
          {{ category.type_name }}
        </div>
      </div>

      <!-- 菜单内容区域 -->
      <div class="menu-container">
        <div
          v-for="category in menuCategories"
          :key="category.id"
          :id="`category-${category.id}`"
          class="menu-section"
        >
          <h2 class="section-title">{{ category.name }} Dishes</h2>

          <div class="dish-list">
            <div v-for="dish in getDishesForCategory(category.id)" :key="dish.id" class="dish-card">
              <div class="dish-header">
                <div class="dish-content">
                  <h3 class="dish-name">{{ dish.name }}</h3>
                  <p class="dish-description">{{ dish.description }}</p>
                  <div class="dish-price">${{ dish.price.toFixed(2) }}</div>
                </div>

                <div class="dish-image">
                  <img :src="dish.image" :alt="dish.name" />
                </div>
              </div>

             <div class="dish-rating" v-if="category.id === 1">
                <svg
                  class="star-icon-small"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                  ></path>
                </svg>
                <span>{{ dish.rating }} ({{ dish.ratingCount || '132' }})</span>
                <span class="popular-tag">Popular</span>
              </div>

              <div class="dish-action-container">
                <button class="order-now-btn" @click="orderNow(dish.id)">Order Now</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 用户类型切换底部栏 - 仅用于开发测试 -->
      <div class="user-type-switcher">
        <button
          class="user-type-btn"
          :class="{ active: userType === 'regular' }"
          @click="switchUserType('regular')"
        >
          Regular User
        </button>
        <button
          class="user-type-btn"
          :class="{ active: userType === 'distributor' }"
          @click="switchUserType('distributor')"
        >
          Distributor
        </button>
      </div>
    </div>
  </PageContainer>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import PageContainer from '@/components/layout/PageContainer.vue'
import {getBannerList ,getShopGoodsList,getProductClass} from '@/api'

// 扩展RestaurantDetail接口
interface RestaurantDetail {
  id: number
  name: string
  coverImage: string
  rating: number
  ratingCount?: number
  deliveryTime: string
  deliveryFee: string
  description?: string
  tags: string[]
  menu: {
    id: number
    name: string
    description: string
    price: number
    image: string
    categoryId: number
    rating?: number
    ratingCount?: number
  }[]
  categories: {
    id: number
    name: string
  }[]
}

// 路由和导航
const route = useRoute()
const router = useRouter()

// 餐厅数据
const restaurant = ref<RestaurantDetail | null>(null)
const activeCategory = ref<number>(0)
const userType = ref<'regular' | 'distributor'>('regular')

// Mock 数据 - 轮播图
const banners: any = ref([])
const getProductClassList: any = ref([])
const getShopGoodsLists: any = ref([])

//获取 url 的参数
let bannersData = {
	shopId:10
  }
  
const getBanner = () => {
	getBannerList(bannersData).then(res => {
    banners.value = res.data
  })
}
const shopId = 10;
let getGoodsListData = {
	shopId:shopId,
	classId:0,
	page: 1,
	limit: 20
  }
  
const getGoodsList = () => {
	getShopGoodsList(getGoodsListData).then(res => {
    getShopGoodsLists.value = res.data
  })
}
//获取店铺分类
const getGoodsClassList = () => {
	getProductClass({shopId:shopId}).then(res => {
    getProductClassList.value = res.data
  })
}
getBanner()
getGoodsList()
getGoodsClassList()
// 获取餐厅数据
const fetchRestaurantData = async () => {
  try {
    // API请求获取餐厅数据
    const id = route.params.id
    // 模拟API加载
    // 实际项目中，这里应该替换为真实的API调用
    setTimeout(() => {
      // 假设从API获取数据
      const apiData = {
        id: Number(id),
        name: 'Restaurant 1',
        coverImage:
          'https://images.unsplash.com/photo-1561758033-d89a9ad46330?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
        rating: 4.7,
        ratingCount: 243,
        deliveryTime: '30-45 min',
        deliveryFee: '$2.99 delivery',
        tags: ['Italian', 'Pizza', 'Pasta'],
        description:
          'Authentic Italian cuisine with a modern twist. Our dishes are made with fresh, locally sourced ingredients.',
        categories: [
          { id: 1, type_name: 'Popular' },
          { id: 2, type_name: 'Appetizers' },
          { id: 3, type_name: 'Soups & Salads' },
          { id: 4, type_name: 'Main Courses' },
          { id: 5, type_name: 'Desserts' },
        ],
        menu: [
          {
            id: 101,
            name: 'Margherita Pizza',
            description: 'Classic pizza with tomato sauce, mozzarella, and basil',
            price: 14.99,
            rating: 4.7,
            ratingCount: 132,
            image:
              'https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
            categoryId: 1,
          },
          {
            id: 102,
            name: 'Spaghetti Carbonara',
            description: 'Creamy pasta with pancetta, egg, and parmesan cheese',
            price: 16.99,
            rating: 4.7,
            ratingCount: 131,
            image:
              'https://images.unsplash.com/photo-1546549032-9571cd6b27df?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
            categoryId: 1,
          },
          // 其他菜品数据...
        ],
      }

      restaurant.value = apiData as RestaurantDetail
      activeCategory.value = apiData.categories[0]?.id || 0
    }, 500)
  } catch (error) {
    console.error('Error fetching restaurant data:', error)
  }
}

// 计算属性：获取菜单分类
const menuCategories = computed(() => {
	
  return restaurant.value?.categories || []
})

// 根据分类获取菜品
const getDishesForCategory = (categoryId: number) => {
  return restaurant.value?.menu.filter((dish) => dish.categoryId === categoryId) || []
}

// 滚动到指定分类
const scrollToCategory = (categoryId: number) => {
  activeCategory.value = categoryId
  nextTick(() => {
    const element = document.getElementById(`category-${categoryId}`)
    if (element) {
      element.scrollIntoView({ behavior: 'smooth' })
    }
  })
}

// 导航到商品详情页
const navigateToProduct = (productId: number) => {
  router.push(`/product/${productId}`)
}

// 下单处理
const orderNow = (productId: number) => {
  // 不论用户类型，都导航到产品详情页
  navigateToProduct(productId)
}

// 测试功能：切换用户类型
const switchUserType = (type: 'regular' | 'distributor') => {
  userType.value = type
  console.log(`已切换到${type === 'regular' ? '普通用户' : '分销商'}模式`)
}

// 生命周期钩子
onMounted(() => {
  fetchRestaurantData()
})
</script>

<style lang="scss" scoped>
@use 'sass:color';
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.restaurant-page {
  background-color: #f5f5f5;
  min-height: 100vh;
  padding-bottom: 60px; // 为底部的用户类型切换器留出空间
}

// 餐厅封面区域
.restaurant-cover {
  position: relative;
  height: 220px;
  width: 100%;
  background-color: #e5e5e5;

  .back-button {
    position: absolute;
    top: 16px;
    left: 16px;
    width: 40px;
    height: 40px;
    background-color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    cursor: pointer;

    svg {
      width: 24px;
      height: 24px;
      color: #333;
    }
  }

  .restaurant-image {
    width: 100%;
    height: 100%;
    overflow: hidden;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }
}

// 餐厅详情区域
.restaurant-details {
  padding: 20px 16px;
  background-color: white;
  border-radius: 0 0 16px 16px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
  margin-bottom: 16px;
}

.restaurant-name {
  font-size: 24px;
  font-weight: 700;
  color: #333;
  margin: 0 0 12px;
}

.restaurant-tags {
  display: flex;
  gap: 8px;
  margin-bottom: 12px;
  flex-wrap: wrap;

  span {
    font-size: 14px;
    color: #666;
    padding: 4px 12px;
    background-color: #f0f0f0;
    border-radius: 20px;
  }
}

.restaurant-info {
  display: flex;
  align-items: center;
  margin-bottom: 12px;
  gap: 16px;

  .rating-container {
    display: flex;
    align-items: center;
    gap: 4px;

    .star-icon {
      width: 18px;
      height: 18px;
      color: #ffc107;
    }

    .rating-text {
      font-size: 14px;
      font-weight: 500;
      color: #333;
    }
  }

  .delivery-info {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 14px;
    color: #666;

    .clock-icon {
      width: 18px;
      height: 18px;
      color: #666;
    }

    .dot-separator {
      margin: 0 4px;
    }

    .delivery-fee {
      color: #4caf50;
    }
  }
}

.restaurant-description {
  font-size: 14px;
  line-height: 1.6;
  color: #666;
  margin: 0;
}

// 搜索菜单栏
.menu-search {
  display: flex;
  align-items: center;
  background-color: white;
  margin: 0 16px 16px;
  padding: 0 12px;
  height: 44px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);

  .search-icon {
    width: 18px;
    height: 18px;
    color: #999;
    margin-right: 8px;
  }

  input {
    flex: 1;
    height: 100%;
    border: none;
    outline: none;
    font-size: 14px;
    color: #333;

    &::placeholder {
      color: #999;
    }
  }
}

// 分类导航
.category-tabs {
  display: flex;
  overflow-x: auto;
  gap: 10px;
  padding: 0 16px 16px;
  scrollbar-width: none;

  &::-webkit-scrollbar {
    display: none;
  }

  .tab-item {
    flex-shrink: 0;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 500;
    color: #666;
    background-color: white;
    border-radius: 20px;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s ease;

    &.active {
      color: white;
      background-color: #ef4444;
    }
  }
}

// 菜单内容
.menu-container {
  padding: 0 16px 24px;
}

.section-title {
  font-size: 18px;
  font-weight: 700;
  color: #333;
  margin: 0 0 16px;
  border-left: 4px solid #ef4444;
  padding-left: 12px;
}

.menu-section {
  margin-bottom: 24px;
}

.dish-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.dish-card {
  display: flex;
  flex-direction: column;
  background-color: white;
  border-radius: 12px;
  overflow: hidden;
  padding: 16px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);

  .dish-header {
    display: flex;
    margin-bottom: 16px;

    .dish-content {
      flex: 1;
      padding-right: 12px;
    }

    .dish-image {
      width: 120px;
      height: 120px;
      border-radius: 8px;
      overflow: hidden;
      flex-shrink: 0;

      img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
    }
  }

  .dish-name {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 0 0 8px;
  }

  .dish-description {
    font-size: 14px;
    color: #666;
    margin: 0 0 8px;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .dish-price {
    font-size: 16px;
    font-weight: 600;
    color: #ef4444;
    margin-bottom: 8px;
  }

  .dish-rating {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 12px;

    .star-icon-small {
      width: 14px;
      height: 14px;
      color: #ffc107;
    }

    span {
      font-size: 13px;
      color: #666;
    }

    .popular-tag {
      background-color: #fff3e6;
      color: #ff6b01;
      font-size: 12px;
      font-weight: 500;
      padding: 2px 8px;
      border-radius: 4px;
      margin-left: 8px;
    }
  }

  .dish-action-container {
    width: 100%;
    margin-top: auto;

    .order-now-btn {
      width: 100%;
      font-size: 16px;
      font-weight: 500;
      color: white;
      background-color: #ef4444;
      border: none;
      padding: 10px 0;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.2s ease;
      box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);

      &:active {
        background-color: color.adjust(#ef4444, $lightness: -5%);
      }
    }
  }
}

// 用户类型切换器 - 仅用于开发测试
.user-type-switcher {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  display: flex;
  background-color: #fff;
  box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
  z-index: 100;

  .user-type-btn {
    flex: 1;
    padding: 12px;
    text-align: center;
    font-size: 14px;
    font-weight: 500;
    color: #666;
    background-color: #fff;
    border: none;
    cursor: pointer;

    &.active {
      color: #ef4444;
      font-weight: 600;
      border-bottom: 2px solid #ef4444;
    }
  }
}
</style>
