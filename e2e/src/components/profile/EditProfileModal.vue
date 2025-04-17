<template>
  <div class="edit-profile-modal" v-if="visible">
    <div class="edit-profile-modal__backdrop" @click="closeModal"></div>
    <div class="edit-profile-modal__content">
      <div class="edit-profile-modal__header">
        <h2 class="edit-profile-modal__title">Edit Profile</h2>
        <button class="edit-profile-modal__close" @click="closeModal">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <div class="edit-profile-modal__body">
        <form @submit.prevent="submitForm">
          <div class="edit-profile-modal__avatar-upload">
            <div class="edit-profile-modal__avatar">
              <img :src="formData.avatar || defaultAvatar" alt="Profile Avatar" />
              <div class="edit-profile-modal__avatar-overlay">
                <i class="fas fa-camera"></i>
              </div>
            </div>
            <input
              type="file"
              accept="image/*"
              @change="handleAvatarChange"
              class="edit-profile-modal__file-input"
            />
          </div>

          <div class="edit-profile-modal__form-group">
            <label class="edit-profile-modal__label">Name</label>
            <input
              type="text"
              class="edit-profile-modal__input"
              v-model="formData.name"
              placeholder="Enter your name"
            />
          </div>

          <div class="edit-profile-modal__form-group">
            <label class="edit-profile-modal__label">Email</label>
            <input
              type="email"
              class="edit-profile-modal__input"
              v-model="formData.email"
              placeholder="Enter your email"
              disabled
            />
            <span class="edit-profile-modal__input-hint">Email cannot be changed</span>
          </div>

          <div class="edit-profile-modal__form-group">
            <label class="edit-profile-modal__label">Phone</label>
            <input
              type="tel"
              class="edit-profile-modal__input"
              v-model="formData.phone"
              placeholder="Enter your phone number"
            />
          </div>

          <div class="edit-profile-modal__actions">
            <button type="button" class="edit-profile-modal__cancel-btn" @click="closeModal">
              Cancel
            </button>
            <button type="submit" class="edit-profile-modal__save-btn">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, defineProps, defineEmits, onMounted } from 'vue'

// Props
interface Props {
  visible: boolean
  userData: {
    name: string
    email: string
    phone: string
    avatar?: string
  }
}

const props = defineProps<Props>()

// Emits
const emit = defineEmits(['close', 'update'])

// Default avatar
const defaultAvatar = 'https://i.pravatar.cc/300'

// Form data
const formData = ref({
  name: '',
  email: '',
  phone: '',
  avatar: '' as string | undefined,
})

// Initialize form data
onMounted(() => {
  formData.value = {
    name: props.userData.name,
    email: props.userData.email,
    phone: props.userData.phone,
    avatar: props.userData.avatar,
  }
})

// Handle avatar change
const handleAvatarChange = (event: Event) => {
  const input = event.target as HTMLInputElement
  if (input.files && input.files[0]) {
    const reader = new FileReader()
    reader.onload = (e) => {
      formData.value.avatar = e.target?.result as string
    }
    reader.readAsDataURL(input.files[0])
  }
}

// Close modal
const closeModal = () => {
  emit('close')
}

// Submit form
const submitForm = () => {
  emit('update', formData.value)
  closeModal()
}
</script>

<style lang="scss" scoped>
@use 'sass:color';
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.edit-profile-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: $z-index-modal;
  display: flex;
  align-items: center;
  justify-content: center;

  &__backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba($black, 0.5);
  }

  &__content {
    position: relative;
    width: 90%;
    max-width: 400px;
    max-height: 90%;
    background-color: $white;
    border-radius: $border-radius-lg;
    overflow: hidden;
    box-shadow: $shadow-lg;
    display: flex;
    flex-direction: column;
  }

  &__header {
    padding: $spacing-md;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid $gray-200;
  }

  &__title {
    font-size: $font-size-lg;
    font-weight: $font-weight-semibold;
    margin: 0;
    color: $text-primary;
  }

  &__close {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background-color: $gray-100;
    border-radius: 50%;
    cursor: pointer;

    &:hover {
      background-color: $gray-200;
    }
  }

  &__body {
    padding: $spacing-md;
    overflow-y: auto;
  }

  &__avatar-upload {
    position: relative;
    width: 100px;
    height: 100px;
    margin: 0 auto $spacing-lg;
    cursor: pointer;
  }

  &__avatar {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid $gray-200;
    position: relative;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

  &__avatar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba($black, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity $transition-base;

    i {
      color: $white;
      font-size: $font-size-lg;
    }
  }

  &__avatar-upload:hover &__avatar-overlay {
    opacity: 1;
  }

  &__file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
  }

  &__form-group {
    margin-bottom: $spacing-md;
  }

  &__label {
    display: block;
    font-size: $font-size-xs;
    font-weight: $font-weight-medium;
    margin-bottom: $spacing-xs;
    color: $text-primary;
  }

  &__input {
    width: 100%;
    height: 44px;
    padding: 0 $spacing-sm;
    font-size: $font-size-sm;
    border: 1px solid $gray-300;
    border-radius: $border-radius-md;
    transition: border-color $transition-base;

    &:focus {
      border-color: $primary;
      outline: none;
    }

    &:disabled {
      background-color: $gray-100;
      cursor: not-allowed;
    }
  }

  &__input-hint {
    display: block;
    font-size: $font-size-xxs;
    color: $text-secondary;
    margin-top: $spacing-xxs;
  }

  &__actions {
    display: flex;
    gap: $spacing-sm;
    margin-top: $spacing-lg;
  }

  &__cancel-btn {
    flex: 1;
    height: 44px;
    background-color: $white;
    color: $text-primary;
    border: 1px solid $gray-300;
    border-radius: $border-radius-md;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    cursor: pointer;

    &:hover {
      background-color: $gray-100;
    }
  }

  &__save-btn {
    flex: 1;
    height: 44px;
    background-color: $primary;
    color: $white;
    border: none;
    border-radius: $border-radius-md;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    cursor: pointer;

    &:hover {
      background-color: color.adjust($primary, $lightness: -10%);
    }
  }
}
</style>
