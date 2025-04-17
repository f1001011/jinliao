<template>
  <div class="card-example">
    <h2>Card Component Examples</h2>

    <div class="section">
      <h3>Basic Card</h3>
      <Card title="Basic Card">
        <p>This is a basic card with title and content.</p>
      </Card>
    </div>

    <div class="section">
      <h3>Card with Cover</h3>
      <Card title="Food Item" subtitle="Delicious and nutritious">
        <template #cover>
          <img
            src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
            alt="Food"
          />
        </template>
        <p>This card includes a cover image, title, and subtitle.</p>
      </Card>
    </div>

    <div class="section">
      <h3>Hoverable Card</h3>
      <Card title="Hoverable Card" hoverable>
        <p>This card has a hover effect. Try hovering over it!</p>
      </Card>
    </div>

    <div class="section">
      <h3>Round Card</h3>
      <Card title="Round Card" round>
        <p>This card has rounded corners for a softer appearance.</p>
      </Card>
    </div>

    <div class="section">
      <h3>Card with Footer</h3>
      <Card title="Card with Footer">
        <p>This card includes a footer section with actions.</p>
        <template #footer>
          <div class="card-footer-actions">
            <Button size="small" type="text">取消</Button>
            <Button size="small">确认</Button>
          </div>
        </template>
      </Card>
    </div>

    <div class="section">
      <h3>Card with Custom Header</h3>
      <Card>
        <template #header>
          <div class="custom-header">
            <div class="avatar"></div>
            <div class="info">
              <h4>张三</h4>
              <p>美食评论家</p>
            </div>
          </div>
        </template>
        <p>"这是我吃过的最好的汉堡！肉质鲜嫩多汁，配料新鲜。"</p>
      </Card>
    </div>
  </div>
</template>

<script setup lang="ts">
import Card from '@/components/common/Card.vue'
import Button from '@/components/common/Button.vue'
</script>

<style lang="scss" scoped>
@import '@/assets/styles/variables.scss';

.card-example {
  padding: $spacing-lg;

  .section {
    margin-bottom: $spacing-2xl;
    max-width: 400px;

    h3 {
      margin-bottom: $spacing-md;
      font-size: $font-size-lg;
      font-weight: $font-weight-semibold;
      color: $text-primary;
    }
  }

  .card-footer-actions {
    display: flex;
    justify-content: flex-end;
    gap: $spacing-sm;
  }

  .custom-header {
    display: flex;
    align-items: center;
    gap: $spacing-md;

    .avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: $gray-300;
    }

    .info {
      h4 {
        margin: 0;
        font-size: $font-size-md;
        font-weight: $font-weight-semibold;
        color: $text-primary;
      }

      p {
        margin: 4px 0 0;
        font-size: $font-size-xs;
        color: $text-secondary;
      }
    }
  }
}

@media (max-width: $breakpoint-md) {
  .card-example {
    padding: $spacing-md;

    .section {
      margin-bottom: $spacing-xl;
      max-width: 100%;
    }
  }
}
</style>
