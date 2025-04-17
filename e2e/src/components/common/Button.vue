<template>
  <button :class="['btn', classes]" :disabled="disabled" @click="handleClick">
    <span v-if="loading" class="btn-spinner"></span>
    <i v-else-if="icon && iconPosition === 'left'" :class="['btn-icon', icon]"></i>
    <span class="btn-content">
      <slot></slot>
    </span>
    <i v-if="icon && iconPosition === 'right'" :class="['btn-icon', icon]"></i>
  </button>
</template>

<script setup lang="ts">
import { computed } from 'vue'

// 组件名称
defineOptions({
  name: 'AppButton',
})

interface ButtonProps {
  type?: 'primary' | 'secondary' | 'success' | 'warning' | 'danger' | 'info' | 'text'
  size?: 'small' | 'medium' | 'large'
  disabled?: boolean
  loading?: boolean
  outlined?: boolean
  rounded?: boolean
  block?: boolean
  icon?: string
  iconPosition?: 'left' | 'right'
}

const props = withDefaults(defineProps<ButtonProps>(), {
  type: 'primary',
  size: 'medium',
  disabled: false,
  loading: false,
  outlined: false,
  rounded: false,
  block: false,
  iconPosition: 'left',
})

const emit = defineEmits<{
  (e: 'click', event: MouseEvent): void
}>()

const handleClick = (event: MouseEvent) => {
  if (!props.disabled && !props.loading) {
    emit('click', event)
  }
}

const classes = computed(() => {
  return {
    [`btn-${props.type}`]: true,
    [`btn-${props.size}`]: true,
    'btn-outlined': props.outlined,
    'btn-rounded': props.rounded,
    'btn-block': props.block,
    'btn-disabled': props.disabled,
    'btn-loading': props.loading,
    [`icon-${props.iconPosition}`]: !!props.icon,
  }
})
</script>

<style lang="scss" scoped>
@use 'sass:color';
@import '@/assets/styles/variables.scss';

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: $spacing-xs;
  font-family: $font-family-sans;
  font-weight: $font-weight-medium;
  line-height: $line-height-normal;
  text-align: center;
  text-decoration: none;
  vertical-align: middle;
  cursor: pointer;
  user-select: none;
  border: $border-width-thin solid transparent;
  padding: $spacing-xs $spacing-md;
  border-radius: $border-radius-md;
  transition: all $transition-base;
  position: relative;
  overflow: hidden;

  &:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba($primary, 0.25);
  }

  &.btn-block {
    display: flex;
    width: 100%;
  }

  &.btn-rounded {
    border-radius: $border-radius-full;
  }

  &.btn-outlined {
    background-color: transparent;

    &:hover:not(.btn-disabled) {
      background-color: rgba($primary, 0.05);
    }
  }

  &.btn-disabled {
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
  }

  &.btn-loading {
    color: transparent;
    pointer-events: none;

    .btn-spinner {
      position: absolute;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
      width: 1em;
      height: 1em;
      border: 2px solid currentColor;
      border-right-color: transparent;
      border-radius: 50%;
      animation: btn-spinner 0.75s linear infinite;
    }

    @keyframes btn-spinner {
      100% {
        transform: translate(-50%, -50%) rotate(360deg);
      }
    }
  }

  // 尺寸变体
  &.btn-small {
    font-size: $font-size-xs;
    padding: $spacing-xs $spacing-sm;
    height: 2rem;
  }

  &.btn-medium {
    font-size: $font-size-sm;
    padding: $spacing-xs $spacing-md;
    height: 2.5rem;
  }

  &.btn-large {
    font-size: $font-size-md;
    padding: $spacing-sm $spacing-lg;
    height: 3rem;
  }

  // 颜色变体
  &.btn-primary {
    background-color: $primary;
    color: $white;
    border-color: $primary;

    &:hover:not(.btn-disabled) {
      background-color: $primary-dark;
      border-color: $primary-dark;
    }

    &.btn-outlined {
      color: $primary;

      &:hover:not(.btn-disabled) {
        color: $white;
        background-color: $primary;
      }
    }
  }

  &.btn-secondary {
    background-color: $secondary;
    color: $white;
    border-color: $secondary;

    &:hover:not(.btn-disabled) {
      background-color: $secondary-dark;
      border-color: $secondary-dark;
    }

    &.btn-outlined {
      color: $secondary;

      &:hover:not(.btn-disabled) {
        color: $white;
        background-color: $secondary;
      }
    }
  }

  &.btn-success {
    background-color: $success;
    color: $white;
    border-color: $success;

    &:hover:not(.btn-disabled) {
      background-color: color.adjust($success, $lightness: -10%);
      border-color: color.adjust($success, $lightness: -10%);
    }

    &.btn-outlined {
      color: $success;

      &:hover:not(.btn-disabled) {
        color: $white;
        background-color: $success;
      }
    }
  }

  &.btn-warning {
    background-color: $warning;
    color: $white;
    border-color: $warning;

    &:hover:not(.btn-disabled) {
      background-color: color.adjust($warning, $lightness: -10%);
      border-color: color.adjust($warning, $lightness: -10%);
    }

    &.btn-outlined {
      color: $warning;

      &:hover:not(.btn-disabled) {
        color: $white;
        background-color: $warning;
      }
    }
  }

  &.btn-danger {
    background-color: $danger;
    color: $white;
    border-color: $danger;

    &:hover:not(.btn-disabled) {
      background-color: color.adjust($danger, $lightness: -10%);
      border-color: color.adjust($danger, $lightness: -10%);
    }

    &.btn-outlined {
      color: $danger;

      &:hover:not(.btn-disabled) {
        color: $white;
        background-color: $danger;
      }
    }
  }

  &.btn-info {
    background-color: $info;
    color: $white;
    border-color: $info;

    &:hover:not(.btn-disabled) {
      background-color: color.adjust($info, $lightness: -10%);
      border-color: color.adjust($info, $lightness: -10%);
    }

    &.btn-outlined {
      color: $info;

      &:hover:not(.btn-disabled) {
        color: $white;
        background-color: $info;
      }
    }
  }

  &.btn-text {
    background-color: transparent;
    color: $text-primary;
    border-color: transparent;
    padding: $spacing-xs;

    &:hover:not(.btn-disabled) {
      color: $primary;
      background-color: rgba($primary, 0.05);
    }
  }
}

// 响应式调整
@media (max-width: $breakpoint-md) {
  .btn {
    &.btn-small,
    &.btn-medium,
    &.btn-large {
      font-size: $font-size-xs;
      padding: $spacing-xs $spacing-sm;
    }
  }
}
</style>
