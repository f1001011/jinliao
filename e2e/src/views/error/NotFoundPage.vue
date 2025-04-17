<template>
  <div class="not-found-page">
    <h1>404 - 页面未找到</h1>
    <p>您访问的页面不存在或已被移除。</p>
  </div>
</template>

<script setup lang="ts">
// 页面逻辑
</script>

<style lang="scss" scoped>
.not-found-page {
  padding: 1rem;
  text-align: center;

  h1 {
    color: var(--color-danger, #dc3545);
  }
}
</style>
