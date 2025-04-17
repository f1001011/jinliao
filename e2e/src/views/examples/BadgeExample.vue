<template>
  <div class="badge-example">
    <h2>徽章组件示例</h2>

    <div class="section">
      <h3>基础徽章</h3>
      <div class="badges">
        <CokBadge content="5">
          <div class="box"></div>
        </CokBadge>
        <CokBadge content="10" type="success">
          <div class="box"></div>
        </CokBadge>
        <CokBadge content="99+" type="warning">
          <div class="box"></div>
        </CokBadge>
        <CokBadge content="New" type="danger">
          <div class="box"></div>
        </CokBadge>
        <CokBadge content="Hot" type="info">
          <div class="box"></div>
        </CokBadge>
      </div>
    </div>

    <div class="section">
      <h3>圆点徽章</h3>
      <div class="badges">
        <CokBadge isDot>
          <div class="box"></div>
        </CokBadge>
        <CokBadge isDot type="success">
          <div class="box"></div>
        </CokBadge>
        <CokBadge isDot type="warning">
          <div class="box"></div>
        </CokBadge>
        <CokBadge isDot type="danger">
          <div class="box"></div>
        </CokBadge>
        <CokBadge isDot type="info">
          <div class="box"></div>
        </CokBadge>
      </div>
    </div>

    <div class="section">
      <h3>最大值</h3>
      <div class="badges">
        <CokBadge content="5" :max="99">
          <div class="box"></div>
        </CokBadge>
        <CokBadge :content="100" :max="99">
          <div class="box"></div>
        </CokBadge>
        <CokBadge :content="1000" :max="999">
          <div class="box"></div>
        </CokBadge>
      </div>
    </div>

    <div class="section">
      <h3>隐藏徽章</h3>
      <div class="badges">
        <CokBadge content="5" :hidden="false">
          <div class="box"></div>
        </CokBadge>
        <CokBadge content="5" :hidden="true">
          <div class="box"></div>
        </CokBadge>
      </div>
    </div>

    <div class="section">
      <h3>与按钮组合</h3>
      <div class="badges">
        <CokBadge content="3">
          <Button>消息</Button>
        </CokBadge>
        <CokBadge content="12" type="danger">
          <Button type="secondary">通知</Button>
        </CokBadge>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import CokBadge from '@/components/common/CokBadge.vue'
import Button from '@/components/common/Button.vue'
</script>

<style lang="scss" scoped>
@import '@/assets/styles/variables.scss';

.badge-example {
  padding: $spacing-lg;

  .section {
    margin-bottom: $spacing-2xl;

    h3 {
      margin-bottom: $spacing-md;
      font-size: $font-size-lg;
      font-weight: $font-weight-semibold;
      color: $text-primary;
    }

    .badges {
      display: flex;
      flex-wrap: wrap;
      gap: $spacing-xl;
      align-items: center;
    }

    .box {
      width: 40px;
      height: 40px;
      background-color: $gray-200;
      border-radius: $border-radius-sm;
    }
  }
}

@media (max-width: $breakpoint-md) {
  .badge-example {
    padding: $spacing-md;

    .section {
      margin-bottom: $spacing-xl;

      .badges {
        gap: $spacing-lg;
      }
    }
  }
}
</style>
