<template>
  <PageContainer title="Delivery Not Available" :showBack="true">
    <div class="not-available-page">
      <div class="not-available-page__content">
        <div class="not-available-page__icon">
          <i class="fas fa-map-marker-alt"></i>
        </div>
        <h1 class="not-available-page__title">Not in Delivery Area</h1>
        <p class="not-available-page__message">
          We're sorry, your address is not within our current delivery range. Please try a different
          address, or consider using our "Invite Someone to Pay" feature.
        </p>
        <div class="not-available-page__actions">
          <button class="not-available-page__btn primary" @click="navigateToHome">
            <i class="fas fa-home"></i>
            Return to Home
          </button>
          <button class="not-available-page__btn secondary" @click="navigateToInvite">
            <i class="fas fa-user-friends"></i>
            Invite Someone to Pay
          </button>
        </div>
      </div>

      <div class="not-available-page__map">
        <div class="not-available-page__map-container">
          <div class="not-available-page__map-circle"></div>
          <div class="not-available-page__map-restaurant">
            <i class="fas fa-store"></i>
          </div>
          <div class="not-available-page__map-user">
            <i class="fas fa-user"></i>
          </div>
        </div>
        <div class="not-available-page__map-legend">
          <div class="not-available-page__map-legend-item">
            <div class="not-available-page__map-legend-icon restaurant">
              <i class="fas fa-store"></i>
            </div>
            <span>Restaurant</span>
          </div>
          <div class="not-available-page__map-legend-item">
            <div class="not-available-page__map-legend-icon circle"></div>
            <span>Delivery Zone</span>
          </div>
          <div class="not-available-page__map-legend-item">
            <div class="not-available-page__map-legend-icon user">
              <i class="fas fa-user"></i>
            </div>
            <span>Your Location</span>
          </div>
        </div>
      </div>
    </div>
  </PageContainer>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import PageContainer from '@/components/layout/PageContainer.vue'

const router = useRouter()

const navigateToHome = () => {
  router.push('/')
}

const navigateToInvite = () => {
  // This would navigate to the invite feature
  // For now, just go back to the product page
  router.push('/product/101')
}
</script>

<style lang="scss" scoped>
@use 'sass:color';
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.not-available-page {
  padding: $spacing-md;

  &__content {
    background-color: $white;
    border-radius: $border-radius-md;
    padding: $spacing-lg;
    text-align: center;
    margin-bottom: $spacing-lg;
    box-shadow: $shadow-sm;
  }

  &__icon {
    width: 80px;
    height: 80px;
    margin: 0 auto $spacing-md;
    background-color: rgba($danger, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;

    i {
      font-size: 36px;
      color: $danger;
    }
  }

  &__title {
    font-size: $font-size-xl;
    font-weight: $font-weight-bold;
    margin: 0 0 $spacing-sm;
    color: $text-primary;
  }

  &__message {
    font-size: $font-size-sm;
    line-height: 1.6;
    color: $text-secondary;
    margin-bottom: $spacing-lg;
  }

  &__actions {
    display: flex;
    flex-direction: column;
    gap: $spacing-sm;
  }

  &__btn {
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: $border-radius-md;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    cursor: pointer;
    transition: all $transition-base;

    i {
      margin-right: $spacing-xs;
    }

    &.primary {
      background-color: $primary;
      color: $white;

      &:hover {
        background-color: color.adjust($primary, $lightness: -10%);
      }
    }

    &.secondary {
      background-color: $white;
      color: $text-primary;
      border: 1px solid $gray-300;

      &:hover {
        background-color: $gray-100;
      }
    }
  }

  &__map {
    background-color: $white;
    border-radius: $border-radius-md;
    padding: $spacing-md;
    box-shadow: $shadow-sm;
  }

  &__map-container {
    height: 200px;
    position: relative;
    background-color: $gray-100;
    border-radius: $border-radius-md;
    margin-bottom: $spacing-md;
    overflow: hidden;
  }

  &__map-circle {
    position: absolute;
    width: 150px;
    height: 150px;
    border: 2px dashed $primary;
    border-radius: 50%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0.7;
  }

  &__map-restaurant {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 30px;
    height: 30px;
    background-color: $white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: $shadow-md;
    z-index: 2;

    i {
      color: $primary;
    }
  }

  &__map-user {
    position: absolute;
    bottom: 20px;
    right: 30px;
    width: 30px;
    height: 30px;
    background-color: $white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: $shadow-md;
    z-index: 2;

    i {
      color: $secondary;
    }
  }

  &__map-legend {
    display: flex;
    justify-content: space-around;
  }

  &__map-legend-item {
    display: flex;
    align-items: center;
    font-size: $font-size-xxs;
    color: $text-secondary;
  }

  &__map-legend-icon {
    width: 20px;
    height: 20px;
    margin-right: $spacing-xxs;
    display: flex;
    align-items: center;
    justify-content: center;

    &.restaurant {
      color: $primary;
    }

    &.circle {
      border: 1px dashed $primary;
      border-radius: 50%;
    }

    &.user {
      color: $secondary;
    }
  }
}
</style>
