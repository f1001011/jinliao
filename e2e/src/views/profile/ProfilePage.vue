<template>
  <PageContainer title="My Profile" :showBack="false">
    <div class="profile-page">
      <!-- 用户未登录状态 -->
      <div v-if="!isLoggedIn" class="profile-page__not-logged-in">
        <div class="profile-page__not-logged-in-icon">
          <i class="fas fa-user-circle"></i>
        </div>
        <h2 class="profile-page__not-logged-in-title">You're not logged in</h2>
        <p class="profile-page__not-logged-in-text">
          Login to view your profile, track orders, and manage your account.
        </p>
        <button class="profile-page__login-btn" @click="navigateToLogin">Login / Register</button>
      </div>

      <!-- 用户已登录状态 -->
      <template v-else>
        <!-- 用户信息卡片 -->
        <div class="profile-page__user-card">
          <div class="profile-page__user-info">
            <div class="profile-page__avatar">
              <img :src="currentUser?.avatar || defaultAvatar" :alt="currentUser?.name" />
            </div>
            <div class="profile-page__user-details">
              <h2 class="profile-page__user-name">{{ currentUser?.name }}</h2>
              <p class="profile-page__user-email">{{ currentUser?.email }}</p>
              <div class="profile-page__user-role">
                <span :class="userRoleClass">{{ userRoleLabel }}</span>
              </div>
            </div>
          </div>
          <div class="profile-page__edit-profile">
            <button class="profile-page__edit-btn" @click="editProfile">
              <i class="fas fa-pencil-alt"></i>
              Edit Profile
            </button>
          </div>
        </div>

        <!-- 账户操作菜单 -->
        <div class="profile-page__menu-section">
          <h3 class="profile-page__section-title">Account</h3>
          <div class="profile-page__menu">
            <div class="profile-page__menu-item" @click="navigateToOrders">
              <div class="profile-page__menu-icon">
                <i class="fas fa-file-invoice"></i>
              </div>
              <div class="profile-page__menu-text">
                <span class="profile-page__menu-label">My Orders</span>
                <span class="profile-page__menu-description">View your order history</span>
              </div>
              <div class="profile-page__menu-arrow">
                <i class="fas fa-chevron-right"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- 代理特定功能 -->
        <div v-if="isAgent" class="profile-page__menu-section">
          <h3 class="profile-page__section-title">Agent</h3>
          <div class="profile-page__menu">
            <div class="profile-page__menu-item" @click="navigateToAgentDashboard">
              <div class="profile-page__menu-icon agent">
                <i class="fas fa-chart-line"></i>
              </div>
              <div class="profile-page__menu-text">
                <span class="profile-page__menu-label">Agent Dashboard</span>
                <span class="profile-page__menu-description"
                  >Manage your region and distributors</span
                >
              </div>
              <div class="profile-page__menu-arrow">
                <i class="fas fa-chevron-right"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- 登出按钮 -->
        <div class="profile-page__logout">
          <button class="profile-page__logout-btn" @click="handleLogout">
            <i class="fas fa-sign-out-alt"></i>
            Logout
          </button>
        </div>
      </template>

      <!-- 编辑个人资料模态框 -->
      <EditProfileModal
        :visible="showEditProfileModal"
        :userData="userProfileData"
        @close="showEditProfileModal = false"
        @update="updateUserProfile"
      />
    </div>
  </PageContainer>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/modules/user'
import PageContainer from '@/components/layout/PageContainer.vue'
import EditProfileModal from '@/components/profile/EditProfileModal.vue'

// Router
const router = useRouter()

// User store
const userStore = useUserStore()
const isLoggedIn = computed(() => userStore.isLoggedIn)
const currentUser = computed(() => userStore.currentUser)
const isDistributor = computed(() => userStore.isDistributor)
const isAgent = computed(() => userStore.isAgent)

// Default avatar
const defaultAvatar = 'https://i.pravatar.cc/300'

// 编辑个人资料模态框
const showEditProfileModal = ref(false)
const userProfileData = computed(() => {
  return {
    name: currentUser.value?.name || '',
    email: currentUser.value?.email || '',
    phone: '123-456-7890', // 这里应该从用户数据中获取，这里只是示例
    avatar: currentUser.value?.avatar || defaultAvatar,
  }
})

// Computed user role class and label
const userRoleClass = computed(() => {
  if (isDistributor.value) return 'distributor'
  if (isAgent.value) return 'agent'
  return 'user'
})

const userRoleLabel = computed(() => {
  if (isDistributor.value) return 'Distributor'
  if (isAgent.value) return 'Agent'
  return 'User'
})

// Navigation methods
const navigateToLogin = () => {
  router.push('/login')
}

// 打开编辑个人资料模态框
const editProfile = () => {
  showEditProfileModal.value = true
}

// 更新用户个人资料
const updateUserProfile = (updatedData: {
  name: string
  email: string
  phone: string
  avatar?: string
}) => {
  // 这里应该调用API更新用户数据，这里只是示例
  console.log('Updated user profile:', updatedData)

  // 更新本地用户数据
  if (currentUser.value) {
    userStore.setUser({
      ...currentUser.value,
      name: updatedData.name,
      avatar: updatedData.avatar,
    })
  }

  // 关闭模态框
  showEditProfileModal.value = false
}

const navigateToOrders = () => {
  // 导航到订单页面
  router.push('/orders')
}

const navigateToAgentDashboard = () => {
  // This would navigate to agent dashboard
  alert('Agent dashboard would open here.')
}

const handleLogout = () => {
  userStore.logout()
  router.push('/')
}
</script>

<style lang="scss" scoped>
@use 'sass:color';
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.profile-page {
  padding: $spacing-md;
  background-color: #f9f9f9;
  min-height: 100vh;

  &__not-logged-in {
    background-color: $white;
    border-radius: $border-radius-lg;
    padding: $spacing-xl;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    transform: translateY(0);
    transition: transform 0.3s ease;

    &:active {
      transform: translateY(2px);
    }
  }

  &__not-logged-in-icon {
    font-size: 72px;
    color: $gray-400;
    margin-bottom: $spacing-md;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  &__not-logged-in-title {
    font-size: $font-size-xl;
    font-weight: $font-weight-bold;
    margin-bottom: $spacing-sm;
    color: $text-primary;
  }

  &__not-logged-in-text {
    font-size: $font-size-md;
    color: $text-secondary;
    margin-bottom: $spacing-lg;
    line-height: 1.5;
  }

  &__login-btn {
    width: 100%;
    height: 52px;
    background-color: $primary;
    color: $white;
    border: none;
    border-radius: $border-radius-md;
    font-size: $font-size-md;
    font-weight: $font-weight-semibold;
    cursor: pointer;
    transition: all $transition-base;
    box-shadow: 0 4px 10px rgba($primary, 0.3);
    position: relative;
    overflow: hidden;

    &:after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 5px;
      height: 5px;
      background: rgba(255, 255, 255, 0.5);
      opacity: 0;
      border-radius: 100%;
      transform: scale(1, 1) translate(-50%);
      transform-origin: 50% 50%;
    }

    &:focus:not(:active)::after {
      animation: ripple 1s ease-out;
    }

    &:hover {
      background-color: color.adjust($primary, $lightness: -8%);
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba($primary, 0.4);
    }

    &:active {
      transform: translateY(1px);
      box-shadow: 0 2px 5px rgba($primary, 0.3);
    }
  }

  &__user-card {
    background-color: $white;
    border-radius: $border-radius-lg;
    padding: $spacing-lg;
    margin-bottom: $spacing-md;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;

    &:hover {
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
    }
  }

  &__user-info {
    display: flex;
    align-items: center;
    margin-bottom: $spacing-md;
  }

  &__avatar {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: $spacing-md;
    border: 3px solid $white;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;

    &:hover {
      transform: scale(1.05);
    }

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;

      &:hover {
        transform: scale(1.1);
      }
    }
  }

  &__user-details {
    flex: 1;
  }

  &__user-name {
    font-size: $font-size-xl;
    font-weight: $font-weight-bold;
    margin: 0 0 $spacing-xxs;
    color: $text-primary;
  }

  &__user-email {
    font-size: $font-size-sm;
    color: $text-secondary;
    margin: 0 0 $spacing-xs;
  }

  &__user-role {
    span {
      display: inline-block;
      font-size: $font-size-xs;
      font-weight: $font-weight-medium;
      padding: 4px $spacing-sm;
      border-radius: $border-radius-pill;
      letter-spacing: 0.5px;

      &.user {
        background-color: $gray-200;
        color: $text-secondary;
      }

      &.distributor {
        background-color: rgba($warning, 0.1);
        color: $warning;
      }

      &.agent {
        background-color: rgba($success, 0.1);
        color: $success;
      }
    }
  }

  &__edit-profile {
    margin-top: $spacing-md;
  }

  &__edit-btn {
    width: 100%;
    height: 46px;
    background-color: $gray-100;
    color: $text-primary;
    border: none;
    border-radius: $border-radius-md;
    font-size: $font-size-md;
    font-weight: $font-weight-medium;
    cursor: pointer;
    transition: all $transition-base;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;

    i {
      margin-right: $spacing-sm;
      transition: transform 0.3s ease;
    }

    &:after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 5px;
      height: 5px;
      background: rgba(0, 0, 0, 0.1);
      opacity: 0;
      border-radius: 100%;
      transform: scale(1, 1) translate(-50%);
      transform-origin: 50% 50%;
    }

    &:focus:not(:active)::after {
      animation: ripple 1s ease-out;
    }

    &:hover {
      background-color: $gray-200;
      i {
        transform: rotate(45deg);
      }
    }

    &:active {
      transform: translateY(1px);
    }
  }

  &__menu-section {
    margin-bottom: $spacing-lg;
    animation: fadeInUp 0.5s ease forwards;
  }

  &__section-title {
    font-size: $font-size-md;
    font-weight: $font-weight-bold;
    margin: 0 0 $spacing-sm;
    color: $text-primary;
    padding: 0 $spacing-xs;
    letter-spacing: 0.5px;
  }

  &__menu {
    background-color: $white;
    border-radius: $border-radius-lg;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
  }

  &__menu-item {
    display: flex;
    align-items: center;
    padding: $spacing-md $spacing-md;
    cursor: pointer;
    transition: all $transition-base;
    position: relative;

    &:before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 3px;
      background-color: $primary;
      transform: scaleY(0);
      transition: transform 0.3s ease;
    }

    &:not(:last-child) {
      border-bottom: 1px solid $gray-200;
    }

    &:hover {
      background-color: rgba($gray-100, 0.7);
      padding-left: $spacing-lg;

      &:before {
        transform: scaleY(1);
      }

      .profile-page__menu-icon {
        transform: scale(1.1);
      }
    }

    &:active {
      background-color: $gray-200;
    }
  }

  &__menu-icon {
    width: 46px;
    height: 46px;
    background-color: $gray-100;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: $spacing-md;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);

    i {
      color: $primary;
      font-size: $font-size-md;
    }

    &.distributor {
      background-color: rgba($warning, 0.1);

      i {
        color: $warning;
      }
    }

    &.agent {
      background-color: rgba($success, 0.1);

      i {
        color: $success;
      }
    }
  }

  &__menu-text {
    flex: 1;
  }

  &__menu-label {
    display: block;
    font-size: $font-size-md;
    font-weight: $font-weight-semibold;
    margin-bottom: 3px;
    color: $text-primary;
  }

  &__menu-description {
    display: block;
    font-size: $font-size-sm;
    color: $text-secondary;
  }

  &__menu-arrow {
    color: $gray-400;
    transition: transform 0.3s ease;

    .profile-page__menu-item:hover & {
      transform: translateX(3px);
      color: $primary;
    }
  }

  &__logout {
    margin-top: $spacing-lg;
    margin-bottom: $spacing-xl;
  }

  &__logout-btn {
    width: 100%;
    height: 52px;
    background-color: $white;
    color: $danger;
    border: 1px solid $danger;
    border-radius: $border-radius-md;
    font-size: $font-size-md;
    font-weight: $font-weight-semibold;
    cursor: pointer;
    transition: all $transition-base;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 3px 8px rgba($danger, 0.1);

    i {
      margin-right: $spacing-sm;
      transition: transform 0.3s ease;
    }

    &:after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 5px;
      height: 5px;
      background: rgba(255, 255, 255, 0.5);
      opacity: 0;
      border-radius: 100%;
      transform: scale(1, 1) translate(-50%);
      transform-origin: 50% 50%;
    }

    &:focus:not(:active)::after {
      animation: ripple 1s ease-out;
    }

    &:hover {
      background-color: $danger;
      color: $white;
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba($danger, 0.2);

      i {
        transform: translateX(-3px);
      }
    }

    &:active {
      transform: translateY(1px);
      box-shadow: 0 2px 5px rgba($danger, 0.2);
    }
  }
}

@keyframes ripple {
  0% {
    transform: scale(0, 0);
    opacity: 0.5;
  }
  20% {
    transform: scale(25, 25);
    opacity: 0.5;
  }
  100% {
    opacity: 0;
    transform: scale(40, 40);
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
