<template>
  <PageContainer
    title="Search"
    :showBack="true"
    :noPadding="true"
    :pullRefresh="true"
    @refresh="handleRefresh"
  >
    <div class="search-page">
      <!-- 搜索头部 -->
      <div class="search-page__header">
        <div class="search-page__search-bar">
          <i class="fas fa-search search-page__search-icon"></i>
          <input
            type="text"
            class="search-page__input"
            v-model="searchQuery"
            placeholder="Search for restaurants or dishes"
            @input="handleSearchInput"
            @focus="handleInputFocus"
          />
          <i
            v-if="searchQuery"
            class="fas fa-times search-page__clear-icon"
            @click="clearSearch"
          ></i>
        </div>
        <button class="search-page__filter-btn" @click="showFilterModal = true">
          <i class="fas fa-sliders-h"></i>
          <span>Filters</span>
        </button>
      </div>

      <!-- 搜索历史 -->
      <div v-if="showHistory && searchHistory.length > 0" class="search-page__history">
        <div class="search-page__history-header">
          <h3 class="search-page__history-title">Recent Searches</h3>
          <button class="search-page__clear-history" @click="clearHistory">Clear All</button>
        </div>
        <div class="search-page__history-list">
          <div
            v-for="(item, index) in searchHistory"
            :key="index"
            class="search-page__history-item"
            @click="selectHistoryItem(item)"
          >
            <i class="fas fa-history search-page__history-icon"></i>
            <span class="search-page__history-text">{{ item }}</span>
            <i class="fas fa-arrow-up search-page__use-icon"></i>
          </div>
        </div>
      </div>

      <!-- 搜索建议 -->
      <div
        v-if="showSuggestions && searchQuery && suggestions.length > 0"
        class="search-page__suggestions"
      >
        <div
          v-for="(suggestion, index) in suggestions"
          :key="index"
          class="search-page__suggestion-item"
          @click="selectSuggestion(suggestion)"
        >
          <i class="fas fa-search search-page__suggestion-icon"></i>
          <span class="search-page__suggestion-text">{{ suggestion }}</span>
        </div>
      </div>

      <!-- 搜索结果 -->
      <div v-if="!showHistory && !showSuggestions" class="search-page__results">
        <!-- 搜索结果标签页 -->
        <div class="search-page__tabs">
          <div
            class="search-page__tab"
            :class="{ active: activeTab === 'all' }"
            @click="activeTab = 'all'"
          >
            All
          </div>
          <div
            class="search-page__tab"
            :class="{ active: activeTab === 'restaurants' }"
            @click="activeTab = 'restaurants'"
          >
            Restaurants
          </div>
          <div
            class="search-page__tab"
            :class="{ active: activeTab === 'dishes' }"
            @click="activeTab = 'dishes'"
          >
            Dishes
          </div>
        </div>

        <!-- 结果数量 -->
        <div class="search-page__result-count">{{ totalResults }} results found</div>

        <!-- 餐厅结果 -->
        <div
          v-if="activeTab === 'all' || activeTab === 'restaurants'"
          class="search-page__result-section"
        >
          <h3 v-if="activeTab === 'all'" class="search-page__section-title">Restaurants</h3>
          <div class="search-page__restaurant-results">
            <div
              v-for="restaurant in filteredRestaurants"
              :key="restaurant.id"
              class="search-page__restaurant-item"
              @click="navigateToRestaurant(restaurant)"
            >
              <div class="search-page__restaurant-image">
                <img :src="restaurant.image" :alt="restaurant.name" />
              </div>
              <div class="search-page__restaurant-info">
                <h4 class="search-page__restaurant-name">{{ restaurant.name }}</h4>
                <div class="search-page__restaurant-rating">
                  <i class="fas fa-star"></i>
                  <span>{{ restaurant.rating }}</span>
                  <span class="search-page__restaurant-time">{{ restaurant.deliveryTime }}</span>
                </div>
                <div class="search-page__restaurant-tags">
                  <span v-for="(tag, index) in restaurant.tags" :key="index">{{ tag }}</span>
                </div>
              </div>
            </div>

            <div v-if="filteredRestaurants.length === 0" class="search-page__empty-results">
              <i class="fas fa-store-alt-slash"></i>
              <p>No restaurants found</p>
            </div>
          </div>
        </div>

        <!-- 菜品结果 -->
        <div
          v-if="activeTab === 'all' || activeTab === 'dishes'"
          class="search-page__result-section"
        >
          <h3 v-if="activeTab === 'all'" class="search-page__section-title">Dishes</h3>
          <div class="search-page__dish-results">
            <div
              v-for="dish in filteredDishes"
              :key="dish.id"
              class="search-page__dish-item"
              @click="navigateToProduct(dish)"
            >
              <div class="search-page__dish-image">
                <img :src="dish.image" :alt="dish.name" />
              </div>
              <div class="search-page__dish-info">
                <h4 class="search-page__dish-name">{{ dish.name }}</h4>
                <p class="search-page__dish-restaurant">{{ dish.restaurantName }}</p>
                <div class="search-page__dish-price-rating">
                  <span class="search-page__dish-price">${{ dish.price.toFixed(2) }}</span>
                  <div class="search-page__dish-rating">
                    <i class="fas fa-star"></i>
                    <span>{{ dish.rating }}</span>
                  </div>
                </div>
              </div>
            </div>

            <div v-if="filteredDishes.length === 0" class="search-page__empty-results">
              <i class="fas fa-utensils"></i>
              <p>No dishes found</p>
            </div>
          </div>
        </div>

        <!-- 加载更多按钮 -->
        <div v-if="hasMore && !isLoading" class="search-page__load-more">
          <button class="search-page__load-more-btn" @click="loadMoreResults">
            Load More Results
          </button>
        </div>

        <!-- 加载中指示器 -->
        <div v-if="isLoading" class="search-page__loading">
          <i class="fas fa-spinner fa-spin"></i>
          <span>Loading more results...</span>
        </div>
      </div>

      <!-- 无搜索结果 -->
      <div
        v-if="!showHistory && !showSuggestions && searchQuery && totalResults === 0"
        class="search-page__no-results"
      >
        <div class="search-page__no-results-icon">
          <i class="fas fa-search"></i>
        </div>
        <h3 class="search-page__no-results-title">No Results Found</h3>
        <p class="search-page__no-results-text">
          We couldn't find any matches for "{{ searchQuery }}". Please try another search term or
          browse by category.
        </p>
        <button class="search-page__browse-btn" @click="navigateToHome">Browse Restaurants</button>
      </div>
    </div>

    <!-- 筛选模态框 -->
    <div class="search-filter-modal" v-if="showFilterModal">
      <div class="search-filter-modal__backdrop" @click="showFilterModal = false"></div>
      <div class="search-filter-modal__content">
        <div class="search-filter-modal__header">
          <h3 class="search-filter-modal__title">Filter Results</h3>
          <button class="search-filter-modal__close" @click="showFilterModal = false">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <div class="search-filter-modal__body">
          <!-- 排序选项 -->
          <div class="search-filter-modal__section">
            <h4 class="search-filter-modal__section-title">Sort By</h4>
            <div class="search-filter-modal__options">
              <div
                v-for="(option, index) in sortOptions"
                :key="index"
                :class="[
                  'search-filter-modal__option',
                  { active: filters.sortBy === option.value },
                ]"
                @click="filters.sortBy = option.value"
              >
                <div class="search-filter-modal__option-radio">
                  <div class="search-filter-modal__option-radio-inner"></div>
                </div>
                <span>{{ option.label }}</span>
              </div>
            </div>
          </div>

          <!-- 价格范围 -->
          <div class="search-filter-modal__section">
            <h4 class="search-filter-modal__section-title">Price Range</h4>
            <div class="search-filter-modal__price-range">
              <div
                v-for="(price, index) in priceOptions"
                :key="index"
                :class="[
                  'search-filter-modal__price-option',
                  { active: filters.priceRange.includes(price.value) },
                ]"
                @click="togglePriceRange(price.value)"
              >
                {{ price.label }}
              </div>
            </div>
          </div>

          <!-- 评分选项 -->
          <div class="search-filter-modal__section">
            <h4 class="search-filter-modal__section-title">Rating</h4>
            <div class="search-filter-modal__options">
              <div
                v-for="(rating, index) in ratingOptions"
                :key="index"
                :class="[
                  'search-filter-modal__option',
                  { active: filters.minRating === rating.value },
                ]"
                @click="filters.minRating = rating.value"
              >
                <div class="search-filter-modal__option-radio">
                  <div class="search-filter-modal__option-radio-inner"></div>
                </div>
                <span class="search-filter-modal__rating-stars">
                  <i
                    v-for="i in 5"
                    :key="i"
                    :class="['fas', i <= rating.value ? 'fa-star' : 'fa-star-o']"
                  ></i>
                  <span v-if="rating.value > 0">{{ rating.value }}+</span>
                  <span v-else>All Ratings</span>
                </span>
              </div>
            </div>
          </div>

          <!-- 菜系分类 -->
          <div class="search-filter-modal__section">
            <h4 class="search-filter-modal__section-title">Cuisines</h4>
            <div class="search-filter-modal__cuisine-options">
              <div
                v-for="(cuisine, index) in cuisineOptions"
                :key="index"
                :class="[
                  'search-filter-modal__cuisine-option',
                  { active: filters.cuisines.includes(cuisine) },
                ]"
                @click="toggleCuisine(cuisine)"
              >
                {{ cuisine }}
              </div>
            </div>
          </div>
        </div>

        <div class="search-filter-modal__footer">
          <button class="search-filter-modal__reset-btn" @click="resetFilters">Reset</button>
          <button class="search-filter-modal__apply-btn" @click="applyFilters">
            Apply Filters
          </button>
        </div>
      </div>
    </div>
  </PageContainer>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import PageContainer from '@/components/layout/PageContainer.vue'
import type { Restaurant, Dish } from '@/types/models'

// 路由
const router = useRouter()

// 搜索状态
const searchQuery = ref('')
const showHistory = ref(true)
const showSuggestions = ref(false)
const searchHistory = ref<string[]>([])
const activeTab = ref('all')
const isLoading = ref(false)
const hasMore = ref(true)
const page = ref(1)

// 筛选状态
const showFilterModal = ref(false)
const filters = ref({
  sortBy: 'relevance',
  priceRange: [] as string[],
  minRating: 0,
  cuisines: [] as string[],
})

// 筛选选项
const sortOptions = [
  { label: 'Relevance', value: 'relevance' },
  { label: 'Rating (High to Low)', value: 'rating' },
  { label: 'Price (Low to High)', value: 'price_asc' },
  { label: 'Price (High to Low)', value: 'price_desc' },
  { label: 'Delivery Time', value: 'delivery_time' },
]

const priceOptions = [
  { label: '$', value: '$' },
  { label: '$$', value: '$$' },
  { label: '$$$', value: '$$$' },
  { label: '$$$$', value: '$$$$' },
]

const ratingOptions = [
  { label: 'All Ratings', value: 0 },
  { label: '3+ Stars', value: 3 },
  { label: '4+ Stars', value: 4 },
  { label: '4.5+ Stars', value: 4.5 },
]

const cuisineOptions = [
  'American',
  'Italian',
  'Chinese',
  'Japanese',
  'Mexican',
  'Thai',
  'Indian',
  'Mediterranean',
  'French',
  'Vietnamese',
]

// 搜索建议
const suggestions = computed(() => {
  if (!searchQuery.value) return []
  // 模拟匹配查询的建议
  const query = searchQuery.value.toLowerCase()
  const allSuggestions = [
    'Burger',
    'Pizza',
    'Sushi',
    'Pasta',
    'Salad',
    'Chicken',
    'Steak',
    'Seafood',
    'Vegetarian',
    'Dessert',
    'Coffee',
    'Italian Restaurant',
    'Chinese Food',
    'Fast Food',
    'Healthy Options',
  ]

  return allSuggestions.filter((item) => item.toLowerCase().includes(query)).slice(0, 5) // 最多显示5个建议
})

// 模拟餐厅和菜品数据
const restaurants = ref<Restaurant[]>([
  {
    id: 1,
    name: 'Burger Palace',
    image:
      'https://images.unsplash.com/photo-1561758033-d89a9ad46330?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    rating: 4.7,
    deliveryTime: '25-35 min',
    deliveryFee: 'Free Delivery',
    tags: ['Burgers', 'American', 'Fast Food'],
  },
  {
    id: 2,
    name: 'Pizza Heaven',
    image:
      'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    rating: 4.5,
    deliveryTime: '30-40 min',
    deliveryFee: '\$2.99 Delivery',
    tags: ['Pizza', 'Italian', 'Pasta'],
  },
  {
    id: 3,
    name: 'Sushi World',
    image:
      'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    rating: 4.8,
    deliveryTime: '35-50 min',
    deliveryFee: '\$3.99 Delivery',
    tags: ['Japanese', 'Sushi', 'Asian'],
  },
  {
    id: 4,
    name: 'Taco Fiesta',
    image:
      'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    rating: 4.3,
    deliveryTime: '20-30 min',
    deliveryFee: '\$1.99 Delivery',
    tags: ['Mexican', 'Tacos', 'Fast Food'],
  },
  {
    id: 5,
    name: 'Healthy Greens',
    image:
      'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    rating: 4.6,
    deliveryTime: '20-35 min',
    deliveryFee: '\$2.49 Delivery',
    tags: ['Salads', 'Healthy', 'Vegetarian'],
  },
])

const dishes = ref<Dish[]>([
  {
    id: 101,
    name: 'Classic Cheeseburger',
    image:
      'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    price: 8.99,
    restaurantName: 'Burger Palace',
    restaurantId: 1,
    rating: 4.7,
  },
  {
    id: 102,
    name: 'Margherita Pizza',
    image:
      'https://images.unsplash.com/photo-1604917877934-07d8d248d396?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    price: 12.99,
    restaurantName: 'Pizza Heaven',
    restaurantId: 2,
    rating: 4.5,
  },
  {
    id: 103,
    name: 'California Roll',
    image:
      'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    price: 10.99,
    restaurantName: 'Sushi World',
    restaurantId: 3,
    rating: 4.8,
  },
  {
    id: 104,
    name: 'Beef Taco',
    image:
      'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    price: 7.99,
    restaurantName: 'Taco Fiesta',
    restaurantId: 4,
    rating: 4.3,
  },
  {
    id: 105,
    name: 'Caesar Salad',
    image:
      'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    price: 9.99,
    restaurantName: 'Healthy Greens',
    restaurantId: 5,
    rating: 4.6,
  },
])

// 过滤后的餐厅和菜品
const filteredRestaurants = computed(() => {
  if (!searchQuery.value) return restaurants.value.slice(0, 3)

  const query = searchQuery.value.toLowerCase()
  return restaurants.value.filter((restaurant) => {
    // 检查名称匹配
    if (restaurant.name.toLowerCase().includes(query)) return true

    // 检查标签匹配
    return restaurant.tags.some((tag) => tag.toLowerCase().includes(query))
  })
})

const filteredDishes = computed(() => {
  if (!searchQuery.value) return dishes.value.slice(0, 3)

  const query = searchQuery.value.toLowerCase()
  return dishes.value.filter((dish) => {
    // 检查菜品名称匹配
    if (dish.name.toLowerCase().includes(query)) return true

    // 检查餐厅名称匹配
    return dish.restaurantName.toLowerCase().includes(query)
  })
})

// 计算结果总数
const totalResults = computed(() => {
  if (activeTab.value === 'all') {
    return filteredRestaurants.value.length + filteredDishes.value.length
  } else if (activeTab.value === 'restaurants') {
    return filteredRestaurants.value.length
  } else {
    return filteredDishes.value.length
  }
})

// 处理搜索输入
const handleSearchInput = () => {
  if (searchQuery.value) {
    showHistory.value = false
    showSuggestions.value = true
  } else {
    showHistory.value = true
    showSuggestions.value = false
  }
}

// 处理输入框聚焦
const handleInputFocus = () => {
  if (searchQuery.value) {
    showSuggestions.value = true
  } else {
    showHistory.value = true
  }
}

// 清除搜索
const clearSearch = () => {
  searchQuery.value = ''
  showHistory.value = true
  showSuggestions.value = false
}

// 清除历史记录
const clearHistory = () => {
  searchHistory.value = []
  localStorage.removeItem('searchHistory')
}

// 选择历史项
const selectHistoryItem = (item: string) => {
  searchQuery.value = item
  showHistory.value = false
  showSuggestions.value = false
  executeSearch()
}

// 选择建议
const selectSuggestion = (suggestion: string) => {
  searchQuery.value = suggestion
  showSuggestions.value = false
  executeSearch()
}

// 执行搜索
const executeSearch = () => {
  if (!searchQuery.value.trim()) return

  // 添加到搜索历史
  if (!searchHistory.value.includes(searchQuery.value)) {
    searchHistory.value.unshift(searchQuery.value)
    // 只保留最近的10条搜索记录
    if (searchHistory.value.length > 10) {
      searchHistory.value = searchHistory.value.slice(0, 10)
    }
    // 保存到localStorage
    localStorage.setItem('searchHistory', JSON.stringify(searchHistory.value))
  }

  // 重置页码
  page.value = 1

  // 模拟搜索延迟
  isLoading.value = true
  setTimeout(() => {
    isLoading.value = false
    // 假设总是有更多结果可加载
    hasMore.value = true
  }, 500)
}

// 加载更多结果
const loadMoreResults = () => {
  if (isLoading.value) return

  isLoading.value = true
  page.value += 1

  // 模拟加载更多数据
  setTimeout(() => {
    isLoading.value = false
    // 假设第3页后没有更多结果
    if (page.value >= 3) {
      hasMore.value = false
    }
  }, 1000)
}

// 刷新处理
const handleRefresh = () => {
  // 重置搜索状态
  page.value = 1
  hasMore.value = true

  // 模拟刷新延迟
  isLoading.value = true
  setTimeout(() => {
    isLoading.value = false
  }, 1000)
}

// 导航方法
const navigateToRestaurant = (restaurant: Restaurant) => {
  router.push(`/restaurant/${restaurant.id}`)
}

const navigateToProduct = (dish: Dish) => {
  router.push(`/product/${dish.id}`)
}

const navigateToHome = () => {
  router.push('/')
}

// 筛选方法
const togglePriceRange = (price: string) => {
  const index = filters.value.priceRange.indexOf(price)
  if (index === -1) {
    filters.value.priceRange.push(price)
  } else {
    filters.value.priceRange.splice(index, 1)
  }
}

const toggleCuisine = (cuisine: string) => {
  const index = filters.value.cuisines.indexOf(cuisine)
  if (index === -1) {
    filters.value.cuisines.push(cuisine)
  } else {
    filters.value.cuisines.splice(index, 1)
  }
}

const resetFilters = () => {
  filters.value = {
    sortBy: 'relevance',
    priceRange: [],
    minRating: 0,
    cuisines: [],
  }
}

const applyFilters = () => {
  // 应用筛选条件并关闭模态框
  showFilterModal.value = false

  // 重置页码
  page.value = 1

  // 模拟筛选延迟
  isLoading.value = true
  setTimeout(() => {
    isLoading.value = false
    // 假设总是有更多结果可加载
    hasMore.value = true
  }, 500)
}

// 监听搜索查询变化
watch(searchQuery, (newValue) => {
  if (newValue.trim()) {
    showHistory.value = false
    showSuggestions.value = true
  } else {
    showHistory.value = true
    showSuggestions.value = false
  }
})

// 生命周期钩子
onMounted(() => {
  // 从localStorage加载搜索历史
  const savedHistory = localStorage.getItem('searchHistory')
  if (savedHistory) {
    try {
      searchHistory.value = JSON.parse(savedHistory)
    } catch (e) {
      console.error('Failed to parse search history:', e)
      searchHistory.value = []
    }
  }
})
</script>

<style lang="scss" scoped>
@use 'sass:color';
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.search-page {
  min-height: 100vh;
  background-color: #f8f9fa;

  &__header {
    background-color: $white;
    padding: $spacing-md;
    display: flex;
    align-items: center;
    gap: $spacing-sm;
    box-shadow: $shadow-sm;
  }

  &__search-bar {
    flex: 1;
    position: relative;
    height: 44px;
  }

  &__search-icon {
    position: absolute;
    left: $spacing-sm;
    top: 50%;
    transform: translateY(-50%);
    color: $gray-500;
    font-size: $font-size-sm;
  }

  &__input {
    width: 100%;
    height: 100%;
    padding: 0 $spacing-xl 0 $spacing-xl;
    border: 1px solid $gray-300;
    border-radius: $border-radius-pill;
    font-size: $font-size-sm;

    &:focus {
      outline: none;
      border-color: $primary;
    }
  }

  &__clear-icon {
    position: absolute;
    right: $spacing-sm;
    top: 50%;
    transform: translateY(-50%);
    color: $gray-500;
    font-size: $font-size-sm;
    cursor: pointer;

    &:hover {
      color: $text-secondary;
    }
  }

  &__filter-btn {
    height: 44px;
    padding: 0 $spacing-md;
    background-color: $gray-100;
    border: none;
    border-radius: $border-radius-md;
    color: $text-primary;
    font-size: $font-size-xs;
    font-weight: $font-weight-medium;
    display: flex;
    align-items: center;
    gap: $spacing-xs;
    cursor: pointer;

    &:hover {
      background-color: $gray-200;
    }

    i {
      font-size: $font-size-sm;
    }
  }

  &__history {
    background-color: $white;
    padding: $spacing-md;
  }

  &__history-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: $spacing-sm;
  }

  &__history-title {
    font-size: $font-size-sm;
    font-weight: $font-weight-semibold;
    color: $text-primary;
    margin: 0;
  }

  &__clear-history {
    font-size: $font-size-xs;
    color: $primary;
    background: none;
    border: none;
    padding: $spacing-xxs $spacing-xs;
    cursor: pointer;

    &:hover {
      text-decoration: underline;
    }
  }

  &__history-list {
    display: flex;
    flex-direction: column;
  }

  &__history-item {
    display: flex;
    align-items: center;
    padding: $spacing-sm 0;
    border-bottom: 1px solid $gray-200;
    cursor: pointer;

    &:last-child {
      border-bottom: none;
    }

    &:hover {
      background-color: $gray-100;
    }
  }

  &__history-icon {
    color: $gray-500;
    margin-right: $spacing-sm;
    font-size: $font-size-sm;
  }

  &__history-text {
    flex: 1;
    font-size: $font-size-sm;
    color: $text-primary;
  }

  &__use-icon {
    color: $gray-500;
    font-size: $font-size-xs;
    padding: $spacing-xs;
  }

  &__suggestions {
    background-color: $white;
    box-shadow: $shadow-sm;
  }

  &__suggestion-item {
    display: flex;
    align-items: center;
    padding: $spacing-sm $spacing-md;
    border-bottom: 1px solid $gray-200;
    cursor: pointer;

    &:last-child {
      border-bottom: none;
    }

    &:hover {
      background-color: $gray-100;
    }
  }

  &__suggestion-icon {
    color: $gray-500;
    margin-right: $spacing-sm;
    font-size: $font-size-sm;
  }

  &__suggestion-text {
    font-size: $font-size-sm;
    color: $text-primary;
  }

  &__results {
    padding-bottom: $spacing-xl;
  }

  &__tabs {
    display: flex;
    background-color: $white;
    padding: 0 $spacing-md;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    position: sticky;
    top: 0;
    z-index: 10;
  }

  &__tab {
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

  &__result-count {
    padding: $spacing-sm $spacing-md;
    font-size: $font-size-xs;
    color: $text-secondary;
    background-color: $white;
    border-bottom: 1px solid $gray-200;
  }

  &__result-section {
    margin-bottom: $spacing-md;
  }

  &__section-title {
    padding: $spacing-md $spacing-md $spacing-xs;
    margin: 0;
    font-size: $font-size-md;
    font-weight: $font-weight-semibold;
    color: $text-primary;
    background-color: $white;
  }

  &__restaurant-results,
  &__dish-results {
    background-color: $white;
  }

  &__restaurant-item,
  &__dish-item {
    display: flex;
    padding: $spacing-md;
    border-bottom: 1px solid $gray-200;
    cursor: pointer;

    &:last-child {
      border-bottom: none;
    }

    &:hover {
      background-color: $gray-100;
    }
  }

  &__restaurant-image,
  &__dish-image {
    width: 80px;
    height: 80px;
    border-radius: $border-radius-md;
    overflow: hidden;
    margin-right: $spacing-md;
    flex-shrink: 0;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

  &__restaurant-info,
  &__dish-info {
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  &__restaurant-name,
  &__dish-name {
    margin: 0 0 $spacing-xxs;
    font-size: $font-size-sm;
    font-weight: $font-weight-semibold;
    color: $text-primary;
  }

  &__restaurant-rating {
    display: flex;
    align-items: center;
    margin-bottom: $spacing-xs;

    i {
      color: $warning;
      font-size: $font-size-xs;
      margin-right: 4px;
    }

    span {
      font-size: $font-size-xs;
      color: $text-primary;
      margin-right: $spacing-sm;
    }
  }

  &__restaurant-time {
    color: $text-secondary;
  }

  &__restaurant-tags {
    display: flex;
    flex-wrap: wrap;
    gap: $spacing-xs;

    span {
      font-size: $font-size-xxs;
      color: $text-secondary;
      background-color: $gray-100;
      padding: 2px $spacing-xs;
      border-radius: $border-radius-pill;
    }
  }

  &__dish-restaurant {
    font-size: $font-size-xs;
    color: $text-secondary;
    margin: 0 0 $spacing-xs;
  }

  &__dish-price-rating {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  &__dish-price {
    font-size: $font-size-sm;
    font-weight: $font-weight-semibold;
    color: $primary;
  }

  &__dish-rating {
    display: flex;
    align-items: center;

    i {
      color: $warning;
      font-size: $font-size-xs;
      margin-right: 4px;
    }

    span {
      font-size: $font-size-xs;
      color: $text-primary;
    }
  }

  &__empty-results {
    padding: $spacing-xl 0;
    text-align: center;

    i {
      font-size: 48px;
      color: $gray-400;
      margin-bottom: $spacing-sm;
    }

    p {
      font-size: $font-size-sm;
      color: $text-secondary;
      margin: 0;
    }
  }

  &__load-more {
    padding: $spacing-md;
    text-align: center;
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
    padding: $spacing-md;
    text-align: center;
    color: $text-secondary;
    font-size: $font-size-sm;

    i {
      margin-right: $spacing-xs;
    }
  }

  &__no-results {
    padding: $spacing-xl $spacing-md;
    text-align: center;
  }

  &__no-results-icon {
    width: 80px;
    height: 80px;
    background-color: $gray-100;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto $spacing-lg;

    i {
      font-size: 36px;
      color: $gray-500;
    }
  }

  &__no-results-title {
    font-size: $font-size-lg;
    font-weight: $font-weight-semibold;
    color: $text-primary;
    margin: 0 0 $spacing-sm;
  }

  &__no-results-text {
    font-size: $font-size-sm;
    color: $text-secondary;
    margin: 0 0 $spacing-lg;
    line-height: 1.5;
  }

  &__browse-btn {
    padding: $spacing-sm $spacing-lg;
    background-color: $primary;
    border: none;
    border-radius: $border-radius-md;
    color: $white;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    cursor: pointer;

    &:hover {
      background-color: color.adjust($primary, $lightness: -10%);
    }
  }
}

// 筛选模态框样式
.search-filter-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: $z-index-modal;
  display: flex;
  align-items: flex-end;

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
    width: 100%;
    max-height: 80vh;
    background-color: $white;
    border-top-left-radius: $border-radius-lg;
    border-top-right-radius: $border-radius-lg;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    animation: slide-up 0.3s ease-out;
  }

  &__header {
    padding: $spacing-md;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid $gray-200;
  }

  &__title {
    font-size: $font-size-md;
    font-weight: $font-weight-semibold;
    margin: 0;
  }

  &__close {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background-color: $gray-100;
    border-radius: 50%;
    cursor: pointer;

    &:hover {
      background-color: $gray-200;
    }
  }

  &__body {
    padding: $spacing-md;
    overflow-y: auto;
    flex: 1;
  }

  &__section {
    margin-bottom: $spacing-lg;

    &:last-child {
      margin-bottom: 0;
    }
  }

  &__section-title {
    font-size: $font-size-sm;
    font-weight: $font-weight-semibold;
    margin: 0 0 $spacing-sm;
    color: $text-primary;
  }

  &__options {
    display: flex;
    flex-direction: column;
    gap: $spacing-sm;
  }

  &__option {
    display: flex;
    align-items: center;
    padding: $spacing-xs 0;
    cursor: pointer;

    &.active &__option-radio {
      border-color: $primary;

      .search-filter-modal__option-radio-inner {
        transform: scale(1);
      }
    }
  }

  &__option-radio {
    width: 20px;
    height: 20px;
    border: 2px solid $gray-300;
    border-radius: 50%;
    margin-right: $spacing-sm;
    display: flex;
    align-items: center;
    justify-content: center;

    &-inner {
      width: 10px;
      height: 10px;
      background-color: $primary;
      border-radius: 50%;
      transform: scale(0);
      transition: transform 0.2s ease;
    }
  }

  &__price-range {
    display: flex;
    gap: $spacing-sm;
    flex-wrap: wrap;
  }

  &__price-option {
    padding: $spacing-xs $spacing-sm;
    border: 1px solid $gray-300;
    border-radius: $border-radius-md;
    font-size: $font-size-sm;
    color: $text-primary;
    cursor: pointer;

    &.active {
      background-color: $primary;
      border-color: $primary;
      color: $white;
    }
  }

  &__rating-stars {
    display: flex;
    align-items: center;

    i {
      color: $warning;
      margin-right: 2px;
      font-size: $font-size-sm;
    }

    span {
      margin-left: $spacing-xs;
      font-size: $font-size-xs;
    }
  }

  &__cuisine-options {
    display: flex;
    flex-wrap: wrap;
    gap: $spacing-sm;
  }

  &__cuisine-option {
    padding: $spacing-xs $spacing-sm;
    background-color: $gray-100;
    border-radius: $border-radius-md;
    font-size: $font-size-xs;
    color: $text-primary;
    cursor: pointer;

    &.active {
      background-color: $primary;
      color: $white;
    }
  }

  &__footer {
    padding: $spacing-md;
    display: flex;
    gap: $spacing-md;
    border-top: 1px solid $gray-200;
  }

  &__reset-btn {
    flex: 1;
    height: 48px;
    background-color: $white;
    border: 1px solid $gray-300;
    border-radius: $border-radius-md;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    color: $text-primary;
    cursor: pointer;

    &:hover {
      background-color: $gray-100;
    }
  }

  &__apply-btn {
    flex: 1;
    height: 48px;
    background-color: $primary;
    border: none;
    border-radius: $border-radius-md;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    color: $white;
    cursor: pointer;

    &:hover {
      background-color: color.adjust($primary, $lightness: -10%);
    }
  }
}

@keyframes slide-up {
  from {
    transform: translateY(100%);
  }
  to {
    transform: translateY(0);
  }
}
</style>
