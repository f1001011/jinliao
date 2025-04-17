<template>
  <div
    :class="[
      'cok-card',
      { 'cok-card--shadow': shadow },
      { 'cok-card--hoverable': hoverable },
      { 'cok-card--round': round },
    ]"
  >
    <div v-if="$slots.cover" class="cok-card__cover">
      <slot name="cover"></slot>
    </div>

    <div v-if="$slots.header || title" class="cok-card__header">
      <slot name="header">
        <div class="cok-card__title">{{ title }}</div>
        <div v-if="subtitle" class="cok-card__subtitle">{{ subtitle }}</div>
      </slot>
    </div>

    <div class="cok-card__body" :style="bodyStyle">
      <slot></slot>
    </div>

    <div v-if="$slots.footer" class="cok-card__footer">
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  title?: string
  subtitle?: string
  shadow?: boolean | 'always' | 'hover' | 'never'
  hoverable?: boolean
  round?: boolean
  bodyStyle?: Record<string, string>
}

withDefaults(defineProps<Props>(), {
  shadow: true,
  hoverable: false,
  round: false,
  bodyStyle: () => ({}),
})
</script>

<style lang="scss" scoped>
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.cok-card {
  position: relative;
  background-color: $white;
  border-radius: $border-radius-md;
  overflow: hidden;
  transition: all $transition-base;

  &--shadow {
    box-shadow: $shadow-sm;
  }

  &--hoverable {
    cursor: pointer;

    &:hover {
      transform: translateY(-4px);
      box-shadow: $shadow-md;
    }
  }

  &--round {
    border-radius: $border-radius-lg;
  }

  &__cover {
    position: relative;
    width: 100%;

    img {
      width: 100%;
      display: block;
    }
  }

  &__header {
    padding: $spacing-md $spacing-md 0;
  }

  &__title {
    font-size: $font-size-md;
    font-weight: $font-weight-semibold;
    color: $text-primary;
    margin-bottom: $spacing-xs;
  }

  &__subtitle {
    font-size: $font-size-xs;
    color: $text-secondary;
    margin-bottom: $spacing-xs;
  }

  &__body {
    padding: $spacing-md;
  }

  &__footer {
    padding: 0 $spacing-md $spacing-md;
    border-top: 1px solid $gray-200;
    padding-top: $spacing-md;
  }
}
</style>
