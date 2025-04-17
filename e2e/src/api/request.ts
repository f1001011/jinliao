/* eslint-disable @typescript-eslint/no-explicit-any */
import axios, { type AxiosRequestConfig, type AxiosResponse } from 'axios'
import router from '@/router'
import { showToast } from 'vant'

// Create an Axios instance
const instance = axios.create({
  baseURL: 'https://jlapi.yulecheng.icu', // Replace with your API base URL
  timeout: 10000, // Request timeout in milliseconds
})

// Request interceptor
instance.interceptors.request.use(
  (config: any) => {
    // Add authorization token or other headers if needed
    const tokens = localStorage.getItem('token') || ''

    const times = Math.round(new Date().getTime() / 1000).toString()

    if (tokens) {
      config.headers = {
        ...config.headers,
        token: tokens,
        sign: 'xxx',
        time: times,
      }
    }
    return config
  },

  (error: any) => {
    // Handle request error
    return Promise.reject(error)
  },
)

// Response interceptor
instance.interceptors.response.use(
  (res: AxiosResponse) => {
    let responseData = res.data
    // 判断 res.data 是否为 JSON 格式
    try {
      if (typeof responseData === 'string') {
        responseData = JSON.parse(responseData)
      }
    } catch (error) {
      console.warn('Response data is not in JSON format:', responseData)
    }

    if (responseData.code == 0) {
      showToast(responseData.msg)
      return Promise.reject(responseData)
    }
    if (responseData.code == 204 || responseData.code == 205) {
      localStorage.removeItem('token')
      router.push('/login')
      return Promise.reject(responseData)
    } else if (responseData.code == 206) {
    } else if (responseData.code == 1) {
    }

    // Handle successful response
    return Promise.resolve(responseData)
  },

  (error: any) => {
    // Handle response error
    if (error.response) {
      // Server responded with a status other than 2xx
      console.error('Error response:', error.response)
    } else if (error.request) {
      // No response received
      console.error('No response received:', error.request)
    } else {
      // Other errors
      console.error('Error:', error.message)
    }
    return Promise.reject(error)
  },
)

// Encapsulated request method

const request = <T = any>(config: AxiosRequestConfig): Promise<T> => {
  return instance(config)
}

export default request
