<template>
  <div class="cok-badge">
    <slot></slot>
    <span
      v-if="!hidden && (content || content === 0 || isDot)"
      :class="[
        'cok-badge__content',
        type ? `cok-badge__content--${type}` : '',
        isDot ? 'is-dot' : '',
      ]"
    >
      {{ !isDot ? displayContent : '' }}
    </span>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface BadgeProps {
  content?: string | number
  type?: 'primary' | 'success' | 'warning' | 'danger' | 'info'
  isDot?: boolean
  hidden?: boolean
  max?: number
}

const props = withDefaults(defineProps<BadgeProps>(), {
  content: '',
  type: 'primary',
  isDot: false,
  hidden: false,
  max: 0,
})

// 计算显示内容，如果数字大于最大值，则显示最大值+
const displayContent = computed(() => {
  if (props.max === 0) return props.content

  const content = Number(props.content)
  return !isNaN(content) && content > props.max ? `${props.max}+` : props.content
})
</script>

<style lang="scss" scoped>
@import '@/assets/styles/variables.scss';

.cok-badge {
  position: relative;
  display: inline-block;
  vertical-align: middle;

  &__content {
    position: absolute;
    top: -10px;
    right: -10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transform: translateY(-50%) translateX(50%);
    height: 20px;
    border-radius: 10px;
    padding: 0 $spacing-xs;
    font-size: $font-size-xs;
    white-space: nowrap;
    color: $white;

    &--primary {
      background-color: $primary;
    }

    &--success {
      background-color: $success;
    }

    &--warning {
      background-color: $warning;
    }

    &--danger {
      background-color: $danger;
    }

    &--info {
      background-color: $info;
    }

    &.is-dot {
      height: 8px;
      width: 8px;
      padding: 0;
      right: -5px;
      top: -5px;
      border-radius: 50%;
    }
  }
}

// 响应式调整
@media (max-width: $breakpoint-md) {
  .cok-badge {
    &__content {
      height: 18px;
      font-size: $font-size-xs;
    }
  }
}
</style>
