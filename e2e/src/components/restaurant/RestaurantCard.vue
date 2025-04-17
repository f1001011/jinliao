<template>
  <div class="restaurant-card" @click="$emit('click')">
    <div class="restaurant-card__image img-hover-zoom">
      <img :src="restaurant.image" :alt="restaurant.name" />
      <div class="restaurant-card__rating">
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
        <span>{{ restaurant.rating }}</span>
      </div>
    </div>
    <div class="restaurant-card__content">
      <h3 class="restaurant-card__name">{{ restaurant.name }}</h3>
      <div class="restaurant-card__info">
        <span class="restaurant-card__time">
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
          {{ restaurant.deliveryTime }}
        </span>
        <span class="restaurant-card__delivery">
          {{ restaurant.deliveryFee }}
        </span>
      </div>
      <div class="restaurant-card__tags">
        <span v-for="(tag, index) in restaurant.tags" :key="index">{{ tag }}</span>
      </div>
      <a href="#" class="restaurant-card__btn"> Order Now </a>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  restaurant: {
    id: number
    name: string
    image: string
    rating: number
    deliveryTime: string
    deliveryFee: string
    tags: string[]
  }
}>()

defineEmits<{
  (e: 'click'): void
}>()
</script>

<style lang="scss" scoped>
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.restaurant-card {
  background-color: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
  cursor: pointer;
  transition:
    transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1),
    box-shadow 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);

  &:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
  }

  &__image {
    position: relative;
    height: 140px;
    overflow: hidden;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
  }

  &__rating {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 4px 8px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    color: #333;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);

    .star-icon {
      color: #ef4444;
      width: 12px;
      height: 12px;
      margin-right: 3px;
    }
  }

  &__content {
    padding: 14px;
  }

  &__name {
    font-size: 15px;
    font-weight: 600;
    color: #333;
    margin: 0 0 6px;
    @include text-truncate;
  }

  &__info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 12px;
    color: #777;
  }

  &__time {
    display: flex;
    align-items: center;

    .clock-icon {
      width: 12px;
      height: 12px;
      margin-right: 4px;
      color: #777;
    }
  }

  &__delivery {
    color: #34c759;
    font-weight: 500;
  }

  &__tags {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin-bottom: 10px;

    span {
      font-size: 10px;
      background-color: #f5f5f5;
      color: #777;
      padding: 3px 8px;
      border-radius: 12px;
    }
  }

  &__btn {
    display: block;
    width: 100%;
    background-color: #f5f5f5;
    color: #ef4444;
    font-size: 13px;
    font-weight: 500;
    text-align: center;
    padding: 8px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s ease;

    &:hover {
      background-color: #ef4444;
      color: white;
    }
  }
}
</style>
