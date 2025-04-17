<template>
  <div class="button-example">
    <h1 class="example-title">按钮示例</h1>

    <section class="example-section">
      <h2 class="section-title">按钮类型</h2>
      <div class="button-group">
        <Button type="primary">主要按钮</Button>
        <Button type="secondary">次要按钮</Button>
        <Button type="success">成功按钮</Button>
        <Button type="warning">警告按钮</Button>
        <Button type="danger">危险按钮</Button>
        <Button type="info">信息按钮</Button>
        <Button type="text">文本按钮</Button>
      </div>
    </section>

    <section class="example-section">
      <h2 class="section-title">按钮尺寸</h2>
      <div class="button-group">
        <Button size="small">小型按钮</Button>
        <Button>默认按钮</Button>
        <Button size="large">大型按钮</Button>
      </div>
    </section>

    <section class="example-section">
      <h2 class="section-title">轮廓按钮</h2>
      <div class="button-group">
        <Button type="primary" outlined>主要按钮</Button>
        <Button type="secondary" outlined>次要按钮</Button>
        <Button type="success" outlined>成功按钮</Button>
        <Button type="warning" outlined>警告按钮</Button>
        <Button type="danger" outlined>危险按钮</Button>
        <Button type="info" outlined>信息按钮</Button>
      </div>
    </section>

    <section class="example-section">
      <h2 class="section-title">圆角按钮</h2>
      <div class="button-group">
        <Button rounded>默认圆角</Button>
        <Button type="success" rounded>成功圆角</Button>
        <Button type="danger" rounded>危险圆角</Button>
      </div>
    </section>

    <section class="example-section">
      <h2 class="section-title">块级按钮</h2>
      <div class="example-block">
        <Button block>块级按钮</Button>
        <Button type="success" block>成功块级按钮</Button>
        <Button type="danger" block>危险块级按钮</Button>
      </div>
    </section>

    <section class="example-section">
      <h2 class="section-title">禁用状态</h2>
      <div class="button-group">
        <Button disabled>禁用按钮</Button>
        <Button type="primary" disabled>禁用主要</Button>
        <Button type="primary" outlined disabled>禁用轮廓</Button>
      </div>
    </section>

    <section class="example-section">
      <h2 class="section-title">加载状态</h2>
      <div class="button-group">
        <Button loading>加载中</Button>
        <Button type="primary" loading>加载中</Button>
        <Button type="success" loading>加载中</Button>
      </div>
    </section>

    <section class="example-section">
      <h2 class="section-title">图标按钮</h2>
      <div class="button-group">
        <Button icon="fa-search">搜索</Button>
        <Button type="primary" icon="fa-plus">添加</Button>
        <Button type="success" icon="fa-check" iconPosition="right">确认</Button>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import Button from '@/components/common/Button.vue'
</script>

<style lang="scss" scoped>
@import '@/assets/styles/variables.scss';

.button-example {
  max-width: 1200px;
  margin: 0 auto;
  padding: $spacing-xl;

  .example-title {
    font-size: $font-size-2xl;
    font-weight: $font-weight-bold;
    color: $text-primary;
    margin-bottom: $spacing-xl;
  }

  .example-section {
    margin-bottom: $spacing-xl;

    .section-title {
      font-size: $font-size-lg;
      font-weight: $font-weight-semibold;
      color: $text-secondary;
      margin-bottom: $spacing-md;
      padding-bottom: $spacing-xs;
      border-bottom: 1px solid $gray-200;
    }

    .button-group {
      display: flex;
      flex-wrap: wrap;
      gap: $spacing-md;
      margin-bottom: $spacing-lg;
    }

    .example-block {
      display: flex;
      flex-direction: column;
      gap: $spacing-md;
      width: 100%;
      max-width: 500px;
    }
  }
}

@media (max-width: $breakpoint-md) {
  .button-example {
    padding: $spacing-md;

    .example-title {
      font-size: $font-size-xl;
    }

    .example-section {
      margin-bottom: $spacing-lg;

      .section-title {
        font-size: $font-size-md;
      }
    }
  }
}
</style>
