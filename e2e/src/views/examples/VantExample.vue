<template>
  <PageContainer title="Vant UI Examples" hasFooter>
    <div class="vant-examples">
      <h2>Vant UI Components</h2>

      <section>
        <h3>Basic Components</h3>

        <div class="example-block">
          <h4>Buttons</h4>
          <div class="button-row">
            <van-button type="primary">Primary</van-button>
            <van-button type="success">Success</van-button>
            <van-button type="danger">Danger</van-button>
            <van-button type="warning">Warning</van-button>
          </div>
        </div>

        <div class="example-block">
          <h4>Icons</h4>
          <div class="icon-grid">
            <van-icon name="good-job" />
            <van-icon name="fire" />
            <van-icon name="like" />
            <van-icon name="star" />
            <van-icon name="shop" />
            <van-icon name="cart" />
          </div>
        </div>

        <div class="example-block">
          <h4>Cell</h4>
          <van-cell-group inset>
            <van-cell title="Cell Title" value="Content" />
            <van-cell title="Cell Title" value="Content" label="Description" />
            <van-cell title="Cell Title" icon="location-o" />
            <van-cell title="Link" is-link />
            <van-cell title="URL" is-link url="https://github.com" />
            <van-cell title="Show Toast" :is-link="true" @click="showToastExample" />
          </van-cell-group>
        </div>
      </section>

      <section>
        <h3>Form Components</h3>

        <div class="example-block">
          <h4>Field & Form</h4>
          <van-form @submit="onSubmit">
            <van-cell-group inset>
              <van-field
                v-model="name"
                name="name"
                label="Username"
                placeholder="Username"
                :rules="[{ required: true, message: 'Username is required' }]"
              />
              <van-field
                v-model="password"
                type="password"
                name="password"
                label="Password"
                placeholder="Password"
                :rules="[{ required: true, message: 'Password is required' }]"
              />
            </van-cell-group>
            <div style="margin: 16px">
              <van-button round block type="primary" native-type="submit"> Submit </van-button>
            </div>
          </van-form>
        </div>

        <div class="example-block">
          <h4>Stepper</h4>
          <van-stepper v-model="stepperValue" />
        </div>

        <div class="example-block">
          <h4>Checkbox & Radio</h4>
          <van-checkbox-group v-model="checkedFruits">
            <van-checkbox name="apple">Apple</van-checkbox>
            <van-checkbox name="banana">Banana</van-checkbox>
            <van-checkbox name="orange">Orange</van-checkbox>
          </van-checkbox-group>

          <div style="margin-top: 16px">
            <van-radio-group v-model="radio">
              <van-radio name="1">Option 1</van-radio>
              <van-radio name="2">Option 2</van-radio>
            </van-radio-group>
          </div>
        </div>
      </section>

      <section>
        <h3>Display Components</h3>

        <div class="example-block">
          <h4>Swipe</h4>
          <van-swipe :autoplay="3000" indicator-color="white">
            <van-swipe-item v-for="(color, index) in swipeColors" :key="index">
              <div class="swipe-item" :style="{ background: color }">{{ index + 1 }}</div>
            </van-swipe-item>
          </van-swipe>
        </div>

        <div class="example-block">
          <h4>Tag</h4>
          <div class="tag-row">
            <van-tag type="primary">Primary</van-tag>
            <van-tag type="success">Success</van-tag>
            <van-tag type="danger">Danger</van-tag>
            <van-tag type="warning">Warning</van-tag>
          </div>
        </div>

        <div class="example-block">
          <h4>Rate</h4>
          <van-rate v-model="rateValue" />
        </div>
      </section>
    </div>

    <template #footer>
      <div class="footer-actions">
        <van-button type="primary" block @click="showToast">Show Toast</van-button>
      </div>
    </template>
  </PageContainer>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import PageContainer from '@/components/layout/PageContainer.vue'
import { showToast } from 'vant'

// Form data
const name = ref('')
const password = ref('')
const stepperValue = ref(1)
const checkedFruits = ref(['apple'])
const radio = ref('1')
const rateValue = ref(3)
const swipeColors = ['#FF4757', '#FFA502', '#28A745', '#17A2B8']

// Methods
const onSubmit = (values: any) => {
  showToast('Form submitted: ' + JSON.stringify(values))
}

const showToastExample = () => {
  showToast('This is a toast message from the example')
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/variables.scss';

.vant-examples {
  section {
    margin-bottom: $spacing-xl;
  }

  h2 {
    font-size: $font-size-lg;
    margin-bottom: $spacing-md;
  }

  h3 {
    font-size: $font-size-md;
    margin-bottom: $spacing-md;
    color: $text-primary;
    border-left: 4px solid $primary;
    padding-left: $spacing-sm;
  }

  h4 {
    font-size: $font-size-sm;
    margin-bottom: $spacing-sm;
    color: $text-secondary;
  }

  .example-block {
    background-color: $white;
    border-radius: $border-radius-md;
    padding: $spacing-md;
    margin-bottom: $spacing-md;
    box-shadow: $shadow-sm;
  }

  .button-row {
    display: flex;
    flex-wrap: wrap;
    gap: $spacing-xs;
  }

  .icon-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: $spacing-md;
    text-align: center;

    .van-icon {
      font-size: 24px;
    }
  }

  .tag-row {
    display: flex;
    flex-wrap: wrap;
    gap: $spacing-xs;
  }

  .swipe-item {
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: $white;
    font-size: $font-size-xl;
    font-weight: $font-weight-bold;
  }

  .van-checkbox,
  .van-radio {
    margin-bottom: $spacing-xs;
  }
}

.footer-actions {
  padding: $spacing-md;
}
</style>
