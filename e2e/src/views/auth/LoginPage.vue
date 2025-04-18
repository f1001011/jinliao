<template>
  <div class="login-page">
    <!-- 页面头部 -->
    <div class="login-page__header">
      <div class="login-page__logo" @click="navigateToHome">
        <span class="logo-text">COKFOOD</span>
      </div>
    </div>

    <!-- 登录/注册表单卡片 -->
    <div class="login-page__container">
      <div class="login-page__card">
        <div class="login-page__card-header">
          <div class="login-page__tabs">
            <button class="login-page__tab" :class="{ 'login-page__tab--active': activeTab === 'login' }"
              @click="activeTab = 'login'">
              Login
            </button>
            <button class="login-page__tab" :class="{ 'login-page__tab--active': activeTab === 'register' }"
              @click="activeTab = 'register'">
              Register
            </button>
          </div>
        </div>

        <div class="login-page__card-body">
          <!-- 登录表单 -->
          <form v-if="activeTab === 'login'" @submit.prevent="handleLogin" class="login-page__form">
            <div class="login-page__welcome">
              <h1 class="login-page__title">Welcome Back</h1>
              <p class="login-page__subtitle">Log in to your account to enjoy food ordering</p>
            </div>

            <div class="login-page__form-group">
              <label class="login-page__label">Phone</label>
              <div class="login-page__input-wrapper">
                <i class="fas fa-envelope"></i>
                <input v-model="loginForm.phone" type="number" class="login-page__input" placeholder="Enter your phone"
                  required />
              </div>
              <span v-if="errors.login.email" class="login-page__error">{{
                errors.login.email
              }}</span>
            </div>

            <div class="login-page__form-group">
              <div class="login-page__label-row">
                <label class="login-page__label">Password</label>
                <a href="#" class="login-page__forgot-link">Forgot password?</a>
              </div>
              <div class="login-page__input-wrapper">
                <i class="fas fa-lock"></i>
                <input v-model="loginForm.password" :type="showLoginPassword ? 'text' : 'password'"
                  class="login-page__input" placeholder="Enter your password" required />
                <button type="button" class="login-page__password-toggle"
                  @click="showLoginPassword = !showLoginPassword">
                  <!-- <i :class="showLoginPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i> -->
                </button>
              </div>
              <span v-if="errors.login.password" class="login-page__error">{{
                errors.login.password
              }}</span>
            </div>

            <div class="login-page__form-group">
              <label class="login-page__checkbox">
                <input type="checkbox" v-model="loginForm.rememberMe" />
                <span class="login-page__checkbox-label">Remember me</span>
              </label>
            </div>

            <button type="submit" class="login-page__submit-btn" :disabled="isLoginLoading">
              <span v-if="!isLoginLoading">Login</span>
              <i v-else class="fas fa-spinner fa-spin"></i>
            </button>
          </form>

          <!-- 注册表单 -->
          <form v-else @submit.prevent="handleRegister" class="login-page__form">
            <div class="login-page__welcome">
              <h1 class="login-page__title">Create New Account</h1>
              <p class="login-page__subtitle">Register to enjoy the full food experience</p>
            </div>



            <div class="login-page__form-group">
              <label class="login-page__label">Phone</label>
              <div class="login-page__input-wrapper">
                <i class="fas fa-envelope"></i>
                <input v-model="registerForm.phone" type="number" class="login-page__input"
                  placeholder="Enter your phone" required />
              </div>
             <!-- <span v-if="errors.register.phone" class="login-page__error">
                {{ errors.register.phone }}
              </span> -->
            </div>

            <div class="login-page__form-group">
              <label class="login-page__label">Password</label>
              <div class="login-page__input-wrapper">
                <i class="fas fa-lock"></i>
                <input v-model="registerForm.password" :type="showRegisterPassword ? 'text' : 'password'"
                  class="login-page__input" placeholder="Set your password" required />

              </div>
              <span v-if="errors.register.password" class="login-page__error">
                {{ errors.register.password }}
              </span>
            </div>

            <div class="login-page__form-group">
              <label class="login-page__label">Password Confrim</label>
              <div class="login-page__input-wrapper">
                <i class="fas fa-lock"></i>
                <input v-model="registerForm.apassword" :type="showRegisterPassword ? 'text' : 'password'"
                  class="login-page__input" placeholder="Set your password" required />

              </div>
              <span v-if="errors.register.password" class="login-page__error">
                {{ errors.register.password }}
              </span>
            </div>


            <div class="login-page__form-group">
              <label class="login-page__checkbox">
                <input type="checkbox" v-model="registerForm.agreeTerms" required />
                <span class="login-page__checkbox-label">I agree to the <a href="#" class="login-page__link">Terms of
                    Service</a> and
                  <a href="#" class="login-page__link">Privacy Policy</a></span>
              </label>
              <span v-if="errors.register.agreeTerms" class="login-page__error">{{
                errors.register.agreeTerms
              }}</span>
            </div>

            <button type="submit" class="login-page__submit-btn" :disabled="isRegisterLoading">
              <span v-if="!isRegisterLoading">Register</span>
              <i v-else class="fas fa-spinner fa-spin"></i>
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- 页脚版权信息 -->
    <div class="login-page__footer">
      <p>© {{ currentYear }} COKFOOD. All rights reserved.</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/modules/user'
import { login, register } from '@/api'

// Router
const router = useRouter()

// User store
const userStore = useUserStore()

// Current year for footer
const currentYear = computed(() => new Date().getFullYear())

// Active tab (login or register)
const activeTab = ref('login')

// Show/hide password toggles
const showLoginPassword = ref(false)
const showRegisterPassword = ref(false)

// Loading states
const isLoginLoading = ref(false)
const isRegisterLoading = ref(false)

// Login form data
const loginForm = reactive({
  email: '',
  phone: '',
  password: '',
  rememberMe: false,
})

// Register form data
const registerForm = reactive({
  name: '',
  email: '',
  phone: '',
  password: '',
  apassword: '',
  agreeTerms: false,
})

// Form validation errors
const errors = reactive({
  login: {
    email: '',
    password: '',
  },
  register: {
    name: '',
    email: '',
    password: '',
    agreeTerms: '',
  },
})

// Clear errors
const clearErrors = (formType: 'login' | 'register') => {
  if (formType === 'login') {
    errors.login = {
      email: '',
      password: '',
    }
  } else {
    errors.register = {
      name: '',
      email: '',
      password: '',
      agreeTerms: '',
    }
  }
}

// Validate login form
const validateLoginForm = () => {
  clearErrors('login')
  let isValid = true

  if (!loginForm.email) {
    errors.login.email = 'Please enter your email'
    isValid = false
  } else if (!/\S+@\S+\.\S+/.test(loginForm.email)) {
    errors.login.email = 'Please enter a valid email address'
    isValid = false
  }

  if (!loginForm.password) {
    errors.login.password = 'Please enter your password'
    isValid = false
  } else if (loginForm.password.length < 6) {
    errors.login.password = 'Password must be at least 6 characters'
    isValid = false
  }

  return isValid
}

// Validate register form
const validateRegisterForm = () => {
  clearErrors('register')
  let isValid = true

  if (!registerForm.name) {
    errors.register.name = 'Please enter your name'
    isValid = false
  }

  // if (!registerForm.phone) {
  //   errors.register.email = 'Please enter your email'
  //   isValid = false
  // } else if (!/\S+@\S+\.\S+/.test(registerForm.email)) {
  //   errors.register.email = 'Please enter a valid email address'
  //   isValid = false
  // }

  if (!registerForm.password) {
    errors.register.password = 'Please enter a password'
    isValid = false
  } else if (registerForm.password.length < 6) {
    errors.register.password = 'Password must be at least 6 characters'
    isValid = false
  }

  if (!registerForm.agreeTerms) {
    errors.register.agreeTerms = 'You must agree to the Terms of Service and Privacy Policy'
    isValid = false
  }

  return isValid
}

// Handle login submission
const handleLogin = async () => {
  if (!validateLoginForm()) return

  try {
    isLoginLoading.value = true

    // 登录逻辑 - 模拟API调用
    // 实际应用中需要替换为真实的登录API
    console.error('Logging in with:', {
      email: loginForm.email,
      password: loginForm.password,
    })

    let data = {
      phone: loginForm.email,
      pwd: loginForm.password,
      captcha: ""
    }

    login(data).then((res: any) => {
      console.error(res)
    })

    // 模拟登录成功
    // setTimeout(() => {
    //   // 假设登录成功后从API获取的用户信息
    //   const userData = {
    //     id: '123',
    //     name: 'Test User',
    //     email: loginForm.email,
    //     role: 'user' as 'user' | 'distributor' | 'agent' | 'admin',
    //     avatar: 'https://i.pravatar.cc/300',
    //   }

    //   // 更新用户状态
    //   userStore.setUser(userData)

    //   // 如果登录成功，跳转到主页
    //   router.push('/')
    // }, 1000)
  } catch (loginError) {
    // 处理登录错误
    errors.login.email = 'Email or password is incorrect'
  } finally {
    isLoginLoading.value = false
  }
}

// Handle register submission
const handleRegister = async () => {
  if (!validateRegisterForm()) return

  try {
    isRegisterLoading.value = true

    // 注册逻辑 - 模拟API调用
    // 实际应用中需要替换为真实的注册API
    console.log('Registering with:', {
      name: registerForm.name,
      email: registerForm.email,
      password: registerForm.password,
    })

    let data = {
      phone: registerForm.email,
      pwd: registerForm.password,
      captcha: "",
      upwd: "",
      // name: registerForm.name,
    }

    register(data).then((res: any) => {
      console.error(res)
    })

    // 模拟注册成功
    setTimeout(() => {
      // 如果注册成功，切换到登录tab
      activeTab.value = 'login'
      // 显示成功消息
      alert('Registration successful! Please login.')
    }, 1000)
  } catch (registerError) {
    // 处理注册错误
    errors.register.email = 'This email is already registered'
  } finally {
    isRegisterLoading.value = false
  }
}

// Navigate to home page
const navigateToHome = () => {
  router.push('/')
}
</script>

<style lang="scss" scoped>
@use 'sass:color';
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.login-page {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background-color: #f9f9fa;
  position: relative;

  &__header {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: $spacing-lg;
    background-color: $white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    position: relative;
    z-index: 10;
  }

  &__logo {
    display: flex;
    align-items: center;
    cursor: pointer;

    .logo-text {
      font-size: $font-size-xl;
      font-weight: $font-weight-bold;
      color: #ef4444;
      letter-spacing: 1px;
    }
  }

  &__container {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: $spacing-md;
    position: relative;
    overflow: hidden;

    &::before {
      content: '';
      position: absolute;
      top: -150px;
      right: -100px;
      width: 300px;
      height: 300px;
      border-radius: 50%;
      background-color: rgba(239, 68, 68, 0.03);
      z-index: 1;
    }

    &::after {
      content: '';
      position: absolute;
      bottom: -100px;
      left: -50px;
      width: 200px;
      height: 200px;
      border-radius: 50%;
      background-color: rgba(239, 68, 68, 0.05);
      z-index: 1;
    }
  }

  &__card {
    width: 100%;
    max-width: 480px;
    background-color: $white;
    border-radius: $border-radius-lg;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    position: relative;
    z-index: 2;
    animation: fadeIn 0.6s ease;
  }

  &__card-header {
    padding: $spacing-md;
    border-bottom: 1px solid $gray-100;
  }

  &__tabs {
    display: flex;
    gap: $spacing-sm;
  }

  &__tab {
    flex: 1;
    background: none;
    border: none;
    padding: $spacing-sm;
    font-size: $font-size-md;
    font-weight: $font-weight-medium;
    color: $gray-500;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;

    &:hover {
      color: $gray-800;
    }

    &--active {
      color: #ef4444;
      border-bottom-color: #ef4444;
      font-weight: $font-weight-semibold;
    }
  }

  &__card-body {
    padding: $spacing-lg;
  }

  &__welcome {
    margin-bottom: $spacing-xl;
    text-align: center;
  }

  &__title {
    font-size: $font-size-2xl;
    font-weight: $font-weight-bold;
    color: $text-primary;
    margin-bottom: $spacing-xs;
  }

  &__subtitle {
    font-size: $font-size-sm;
    color: $text-secondary;
  }

  &__form {
    display: flex;
    flex-direction: column;
    gap: $spacing-md;
  }

  &__form-group {
    margin-bottom: $spacing-sm;
  }

  &__label-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: $spacing-xs;
  }

  &__label {
    display: block;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    color: $text-primary;
    margin-bottom: $spacing-xs;
  }

  &__forgot-link {
    font-size: $font-size-xs;
    color: #ef4444;
    text-decoration: none;

    &:hover {
      text-decoration: underline;
    }
  }

  &__input-wrapper {
    position: relative;
    display: flex;
    align-items: center;

    i {
      position: absolute;
      left: $spacing-sm;
      color: $gray-400;
      font-size: $font-size-md;
    }
  }

  &__input {
    width: 100%;
    height: 48px;
    padding: 0 $spacing-md 0 $spacing-xl;
    border: 1px solid $gray-200;
    border-radius: $border-radius-md;
    font-size: $font-size-md;
    background-color: $white;
    transition: all 0.3s ease;

    &:focus {
      outline: none;
      border-color: #ef4444;
      box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    &::placeholder {
      color: $gray-400;
    }
  }

  &__password-toggle {
    position: absolute;
    right: $spacing-sm;
    background: none;
    border: none;
    color: $gray-400;
    cursor: pointer;
    transition: color 0.3s ease;

    &:hover {
      color: $gray-600;
    }
  }

  &__error {
    display: block;
    font-size: $font-size-xs;
    color: $danger;
    margin-top: $spacing-xs;
  }

  &__checkbox {
    display: flex;
    align-items: center;
    cursor: pointer;

    input[type='checkbox'] {
      width: 18px;
      height: 18px;
      margin-right: $spacing-xs;
      accent-color: #ef4444;
    }
  }

  &__checkbox-label {
    font-size: $font-size-sm;
    color: $text-secondary;
  }

  &__link {
    color: #ef4444;
    text-decoration: none;

    &:hover {
      text-decoration: underline;
    }
  }

  &__submit-btn {
    width: 100%;
    height: 50px;
    background-color: #ef4444;
    color: $white;
    border: none;
    border-radius: $border-radius-md;
    font-size: $font-size-md;
    font-weight: $font-weight-semibold;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: $spacing-md;
    position: relative;
    overflow: hidden;

    &:hover {
      background-color: color.adjust(#ef4444, $lightness: -5%);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
    }

    &:active {
      transform: translateY(0);
    }

    &:disabled {
      background-color: color.adjust(#ef4444, $lightness: 20%);
      cursor: not-allowed;
      transform: translateY(0);
      box-shadow: none;
    }

    &::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(to bottom right,
          rgba(255, 255, 255, 0.2),
          rgba(255, 255, 255, 0));
      pointer-events: none;
    }
  }

  &__footer {
    padding: $spacing-md;
    text-align: center;
    font-size: $font-size-xs;
    color: $text-secondary;
    background-color: $white;
    border-top: 1px solid $gray-100;
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

// 响应式调整
@media (max-width: 576px) {
  .login-page {
    &__container {
      padding: $spacing-sm;
    }

    &__card {
      box-shadow: none;
      background-color: transparent;
    }

    &__card-body {
      padding: $spacing-md;
    }

    &__title {
      font-size: $font-size-xl;
    }
  }
}
</style>
