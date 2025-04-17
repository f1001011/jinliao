<template>
  <div class="restaurant-reviews">
    <div class="reviews-header">
      <div class="reviews-summary">
        <div class="reviews-rating">
          <span class="rating-value">{{ averageRating }}</span>
          <div class="rating-stars">
            <div class="stars-filled" :style="{ width: `${ratingPercentage}%` }"></div>
          </div>
        </div>
        <div class="reviews-count">{{ reviews.length }} reviews</div>
      </div>
      <div class="reviews-distribution">
        <div v-for="i in 5" :key="i" class="distribution-item">
          <span class="distribution-label">{{ 6 - i }}</span>
          <div class="distribution-bar">
            <div
              class="distribution-fill"
              :style="{ width: `${getDistributionPercentage(6 - i)}%` }"
            ></div>
          </div>
          <span class="distribution-count">{{ getReviewCountByRating(6 - i) }}</span>
        </div>
      </div>
    </div>

    <div class="reviews-list">
      <div v-for="review in reviews" :key="review.id" class="review-item">
        <div class="review-header">
          <div class="review-avatar">
            <img :src="review.avatar || defaultAvatar" :alt="review.name" />
          </div>
          <div class="review-info">
            <div class="review-name">{{ review.name }}</div>
            <div class="review-meta">
              <div class="review-rating">
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
                <span>{{ review.rating }}</span>
              </div>
              <div class="review-date">{{ formatDate(review.date) }}</div>
            </div>
          </div>
        </div>
        <div class="review-content">
          {{ review.comment }}
        </div>
      </div>
    </div>

    <div v-if="hasMoreReviews" class="load-more">
      <button class="load-more-btn btn-hover" @click="loadMoreReviews">
        <span>See all reviews</span>
        <svg
          class="chevron-icon"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M9 5l7 7-7 7"
          ></path>
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'

// 默认头像图片
const defaultAvatar = 'https://i.pravatar.cc/100'

interface Review {
  id: number
  name: string
  avatar?: string
  rating: number
  date: string
  comment: string
}

interface Props {
  reviews: Review[]
  limit?: number
  hasMore?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  limit: 3,
  hasMore: false,
})

const emit = defineEmits<{
  (e: 'loadMore'): void
}>()

// 计算平均评分
const averageRating = computed(() => {
  if (props.reviews.length === 0) return 0

  const sum = props.reviews.reduce((acc, review) => acc + review.rating, 0)
  return (sum / props.reviews.length).toFixed(1)
})

// 计算评分百分比（用于星级显示）
const ratingPercentage = computed(() => {
  return (parseFloat(String(averageRating.value)) / 5) * 100
})

// 获取特定评分的评价数量
const getReviewCountByRating = (rating: number) => {
  return props.reviews.filter((review) => Math.floor(review.rating) === rating).length
}

// 计算特定评分的分布百分比
const getDistributionPercentage = (rating: number) => {
  if (props.reviews.length === 0) return 0

  const count = getReviewCountByRating(rating)
  return (count / props.reviews.length) * 100
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
  emit('loadMore')
}

// 计算是否有更多评价可加载
const hasMoreReviews = computed(() => {
  return props.hasMore
})
</script>

<style lang="scss" scoped>
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.restaurant-reviews {
  background-color: white;
  border-radius: 24px;
  padding: 24px;
  margin-top: 16px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.reviews-header {
  margin-bottom: 24px;
}

.reviews-summary {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.reviews-rating {
  display: flex;
  align-items: center;
  margin-right: 16px;

  .rating-value {
    font-size: 28px;
    font-weight: 700;
    color: #2f3542;
    margin-right: 12px;
  }
}

.rating-stars {
  position: relative;
  width: 100px;
  height: 20px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23E5E7EB'%3E%3Cpath d='M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z'/%3E%3C/svg%3E");
  background-repeat: repeat-x;
  background-size: 20px 20px;

  .stars-filled {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23FFA502'%3E%3Cpath d='M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z'/%3E%3C/svg%3E");
    background-repeat: repeat-x;
    background-size: 20px 20px;
  }
}

.reviews-count {
  font-size: 14px;
  color: #6b7280;
}

.reviews-distribution {
  margin-bottom: 24px;

  .distribution-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
  }

  .distribution-label {
    width: 15px;
    font-size: 14px;
    color: #6b7280;
    margin-right: 8px;
  }

  .distribution-bar {
    flex: 1;
    height: 6px;
    background-color: #f3f4f6;
    border-radius: 9999px;
    overflow: hidden;
    margin-right: 8px;
  }

  .distribution-fill {
    height: 100%;
    background-color: #ffa502;
  }

  .distribution-count {
    width: 30px;
    font-size: 14px;
    color: #6b7280;
    text-align: right;
  }
}

.reviews-list {
  margin-bottom: 24px;
}

.review-item {
  padding: 20px 0;
  border-bottom: 1px solid #f3f4f6;

  &:last-child {
    border-bottom: none;
  }
}

.review-header {
  display: flex;
  margin-bottom: 12px;
}

.review-avatar {
  width: 48px;
  height: 48px;
  border-radius: 9999px;
  overflow: hidden;
  margin-right: 12px;
  flex-shrink: 0;

  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}

.review-info {
  flex: 1;
}

.review-name {
  font-size: 16px;
  font-weight: 600;
  color: #2f3542;
  margin-bottom: 4px;
}

.review-meta {
  display: flex;
  align-items: center;
}

.review-rating {
  display: flex;
  align-items: center;
  margin-right: 12px;
  font-size: 14px;
  color: #ffa502;

  .star-icon {
    width: 16px;
    height: 16px;
    margin-right: 4px;
  }
}

.review-date {
  font-size: 12px;
  color: #6b7280;
}

.review-content {
  font-size: 14px;
  color: #2f3542;
  line-height: 1.6;
}

.load-more {
  text-align: center;
  padding-top: 16px;

  .load-more-btn {
    background-color: #ff6b00;
    color: white;
    font-size: 14px;
    font-weight: 500;
    padding: 10px 24px;
    border-radius: 9999px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(255, 107, 0, 0.25);

    .chevron-icon {
      width: 16px;
      height: 16px;
      margin-left: 8px;
    }

    &:hover {
      background-color: #e05a00;
      box-shadow: 0 6px 16px rgba(255, 107, 0, 0.3);
    }

    &:after {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: 0.5s;
    }

    &:hover:after {
      left: 100%;
    }
  }
}
</style>
