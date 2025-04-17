<template>
  <div class="dish-card" @click="$emit('click')">
    <div class="dish-card__image img-hover-zoom">
      <img :src="dish.image" :alt="dish.name" />
      <div class="dish-card__price">${{ dish.price.toFixed(2) }}</div>
    </div>
    <div class="dish-card__content">
      <h3 class="dish-card__name">{{ dish.name }}</h3>
      <div class="dish-card__restaurant">{{ dish.restaurantName }}</div>
      <div class="dish-card__bottom">
        <div class="dish-card__rating">
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
          <span>{{ dish.rating }}</span>
        </div>
        <a href="#" class="dish-card__btn">Order Now</a>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  dish: {
    id: number
    name: string
    image: string
    price: number
    restaurantName: string
    restaurantId: number
    rating: number
  }
}>()

defineEmits<{
  (e: 'click'): void
}>()
</script>

<style lang="scss" scoped>
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.dish-card {
  background-color: white;
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
  cursor: pointer;
  transition:
    transform 0.3s ease,
    box-shadow 0.3s ease;

  &:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
  }

  &__image {
    position: relative;
    height: 160px;
    overflow: hidden;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }
  }

  &__price {
    position: absolute;
    top: 12px;
    right: 12px;
    background-color: #ff6b00;
    color: white;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 8px;
    border-radius: 9999px;
  }

  &__content {
    padding: 16px;
  }

  &__name {
    font-size: 16px;
    font-weight: 700;
    color: #2f3542;
    margin: 0 0 4px;
    @include text-truncate;
  }

  &__restaurant {
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 12px;
    @include text-truncate;
  }

  &__bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  &__rating {
    display: flex;
    align-items: center;
    font-size: 12px;
    color: #2f3542;
    font-weight: 500;

    .star-icon {
      color: #ffa502;
      width: 14px;
      height: 14px;
      margin-right: 4px;
    }
  }

  &__btn {
    font-size: 12px;
    font-weight: 500;
    color: #ff6b00;
    text-decoration: none;
    background-color: #fff1e8;
    padding: 6px 10px;
    border-radius: 9999px;
    transition: all 0.3s ease;

    &:hover {
      background-color: #ff6b00;
      color: white;
    }
  }
}
</style>
