// 轮播图模型
export interface Banner {
  id: number
  title: string
  subtitle: string
  image: string
  link: string
}

// 食品分类模型
export interface Category {
  id: number
  name: string
  icon: string
}

// 餐厅模型
export interface Restaurant {
  id: number
  name: string
  image: string
  rating: number
  deliveryTime: string
  deliveryFee: string
  tags: string[]
}

// 菜品模型
export interface Dish {
  id: number
  name: string
  image: string
  price: number
  restaurantName: string
  restaurantId: number
  rating: number
}

// 用户评价模型
export interface Review {
  name: string
  avatar: string
  rating: number
  comment: string
}

// 菜单项类型
export interface MenuItem {
  id: number
  name: string
  description: string
  price: number
  image: string
  categoryId: number
}

// 菜单分类类型
export interface MenuCategory {
  id: number
  name: string
}

// 餐厅详情类型
export interface RestaurantDetail {
  id: number
  name: string
  coverImage: string
  rating: number
  deliveryTime: string
  deliveryFee: string
  tags: string[]
  menu: MenuItem[]
  categories: MenuCategory[]
}

// 商品营养信息
export interface ProductNutrition {
  calories: number
  protein: number
  carbs: number
  fat: number
}

// 辣度等级
export interface SpiceLevel {
  name: string
  value: number
}

// 商品详情
export interface ProductDetail {
  id: number
  name: string
  description: string
  longDescription: string
  price: number
  images: string[]
  rating: number
  reviewCount: number
  nutrition: ProductNutrition
  allergens: string[]
  ingredients: string[]
  spiceLevels?: SpiceLevel[]
  restaurantId: number
  restaurantName: string
}

// 相关商品
export interface RelatedProduct {
  id: number
  name: string
  description: string
  price: number
  image: string
}

// 商品评价
export interface ProductReview {
  id: number
  name: string
  avatar: string
  rating: number
  date: string
  comment: string
}

// 地址表单
export interface AddressForm {
  name: string
  phone: string
  email: string
  address1: string
  address2: string
  city: string
  state: string
  zipCode: string
  instructions: string
}

// 购物车商品
export interface CartItem {
  id: number
  name: string
  price: number
  quantity: number
  image: string
  restaurantName: string
  restaurantId?: number
  options?: {
    name: string
    value: string
  }[]
}

// 订单项
export interface OrderItem {
  id: number
  name: string
  quantity: number
  price: number
  image?: string
  options?: {
    name: string
    value: string
  }[]
}

// 订单
export interface Order {
  id: number
  restaurantName: string
  restaurantImage: string
  orderDate: string
  status: 'placed' | 'preparing' | 'ready' | 'delivered' | 'cancelled'
  items: OrderItem[]
  total: number
  isPaidForSomeone?: boolean
  payerName?: string
}
