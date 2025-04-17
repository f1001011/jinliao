# COKFOOD 后端接口配置文档

## 1. 概述

本文档详细描述了COKFOOD移动应用的后端API接口规范，包括接口路径、请求方法、参数格式、响应格式以及身份验证机制。这些接口将支持前端应用的所有功能，特别是核心的分销商折扣设置与代付邀请功能。

## 2. 基础配置

### 2.1 API基础路径

- **开发环境**: `/api`
- **生产环境**: `https://api.cokfood.com/api`

### 2.2 请求格式

所有请求默认使用JSON格式，除非特别说明（如文件上传）：

```
Content-Type: application/json
```

### 2.3 响应格式

所有API响应遵循以下统一格式：

```json
{
  "code": 200, // 状态码：200成功，非200表示错误
  "message": "Success", // 状态消息
  "data": {} // 响应数据，根据接口不同而变化
}
```

### 2.4 错误处理

当API请求失败时，响应格式如下：

```json
{
  "code": 400, // 错误状态码
  "message": "Invalid request", // 错误消息
  "errors": [] // 详细错误信息，可选
}
```

常见错误状态码：

- 400: 错误的请求参数
- 401: 未授权访问
- 403: 权限不足
- 404: 资源不存在
- 500: 服务器内部错误

### 2.5 身份验证

API使用JWT（JSON Web Token）进行身份验证：

- 登录成功后，服务器返回token
- 客户端需要在后续请求的Header中包含token：

```
Authorization: Bearer {token}
```

## 3. 用户认证接口

### 3.1 用户登录

**请求**:

- **路径**: `/auth/login`
- **方法**: POST
- **参数**:

```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**响应**:

```json
{
  "code": 200,
  "message": "Login successful",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "user": {
      "id": "123",
      "name": "John Doe",
      "email": "user@example.com",
      "role": "distributor",
      "avatar": "https://example.com/avatar.jpg"
    }
  }
}
```

### 3.2 用户注册

**请求**:

- **路径**: `/auth/register`
- **方法**: POST
- **参数**:

```json
{
  "name": "John Doe",
  "email": "user@example.com",
  "password": "password123",
  "confirmPassword": "password123"
}
```

**响应**:

```json
{
  "code": 200,
  "message": "Registration successful",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "user": {
      "id": "123",
      "name": "John Doe",
      "email": "user@example.com",
      "role": "user",
      "avatar": "https://example.com/default-avatar.jpg"
    }
  }
}
```

### 3.3 获取当前用户信息

**请求**:

- **路径**: `/auth/me`
- **方法**: GET
- **认证**: 需要

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "user": {
      "id": "123",
      "name": "John Doe",
      "email": "user@example.com",
      "role": "distributor",
      "avatar": "https://example.com/avatar.jpg",
      "phone": "123-456-7890",
      "createdAt": "2023-04-01T12:00:00Z"
    }
  }
}
```

### 3.4 退出登录

**请求**:

- **路径**: `/auth/logout`
- **方法**: POST
- **认证**: 需要

**响应**:

```json
{
  "code": 200,
  "message": "Logout successful",
  "data": null
}
```

## 4. 首页数据接口

### 4.1 获取首页数据

**请求**:

- **路径**: `/home/data`
- **方法**: GET
- **参数**: 无

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "banners": [
      {
        "id": 1,
        "title": "Summer Special",
        "subtitle": "Get 20% off on selected items",
        "image": "https://example.com/banner1.jpg",
        "link": "/category/1"
      }
    ],
    "categories": [
      {
        "id": 1,
        "name": "Burgers",
        "icon": "https://example.com/icons/burger.png"
      }
    ],
    "featuredRestaurants": [
      {
        "id": 1,
        "name": "Burger Palace",
        "image": "https://example.com/restaurants/burger-palace.jpg",
        "rating": 4.7,
        "deliveryTime": "25-35 min",
        "deliveryFee": "Free Delivery",
        "tags": ["Burgers", "American", "Fast Food"]
      }
    ],
    "popularDishes": [
      {
        "id": 101,
        "name": "Classic Cheeseburger",
        "image": "https://example.com/dishes/cheeseburger.jpg",
        "price": 8.99,
        "restaurantName": "Burger Palace",
        "restaurantId": 1,
        "rating": 4.7
      }
    ],
    "reviews": [
      {
        "id": 1,
        "name": "John Smith",
        "avatar": "https://example.com/avatars/john.jpg",
        "rating": 5,
        "comment": "Amazing food and fast delivery!"
      }
    ]
  }
}
```

## 5. 餐厅接口

### 5.1 获取餐厅列表

**请求**:

- **路径**: `/restaurants`
- **方法**: GET
- **参数**:
  - `page`: 页码，默认1
  - `limit`: 每页数量，默认10
  - `category`: 分类ID，可选
  - `sort`: 排序方式，可选（rating, popularity, price_asc, price_desc）

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "restaurants": [
      {
        "id": 1,
        "name": "Burger Palace",
        "image": "https://example.com/restaurants/burger-palace.jpg",
        "rating": 4.7,
        "deliveryTime": "25-35 min",
        "deliveryFee": "Free Delivery",
        "tags": ["Burgers", "American", "Fast Food"]
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 10,
      "total": 50,
      "pages": 5
    }
  }
}
```

### 5.2 获取餐厅详情

**请求**:

- **路径**: `/restaurants/{id}`
- **方法**: GET
- **参数**: 无

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "id": 1,
    "name": "Burger Palace",
    "coverImage": "https://example.com/restaurants/burger-palace-cover.jpg",
    "rating": 4.7,
    "deliveryTime": "25-35 min",
    "deliveryFee": "Free Delivery",
    "tags": ["Burgers", "American", "Fast Food"],
    "categories": [
      {
        "id": 1,
        "name": "Popular"
      },
      {
        "id": 2,
        "name": "Burgers"
      }
    ],
    "menu": [
      {
        "id": 101,
        "name": "Classic Cheeseburger",
        "description": "Juicy beef patty with melted cheese",
        "price": 8.99,
        "image": "https://example.com/dishes/cheeseburger.jpg",
        "categoryId": 1
      }
    ]
  }
}
```

### 5.3 获取餐厅评价

**请求**:

- **路径**: `/restaurants/{id}/reviews`
- **方法**: GET
- **参数**:
  - `page`: 页码，默认1
  - `limit`: 每页数量，默认10

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "reviews": [
      {
        "id": 1,
        "name": "John Smith",
        "avatar": "https://example.com/avatars/john.jpg",
        "rating": 5,
        "date": "2023-10-15T14:30:00Z",
        "comment": "Amazing food and fast delivery!"
      }
    ],
    "summary": {
      "averageRating": 4.7,
      "totalReviews": 123,
      "distribution": {
        "5": 80,
        "4": 30,
        "3": 10,
        "2": 2,
        "1": 1
      }
    },
    "pagination": {
      "page": 1,
      "limit": 10,
      "total": 123,
      "pages": 13
    }
  }
}
```

## 6. 商品接口

### 6.1 获取商品列表

**请求**:

- **路径**: `/products`
- **方法**: GET
- **参数**:
  - `page`: 页码，默认1
  - `limit`: 每页数量，默认20
  - `category`: 分类ID，可选
  - `restaurant`: 餐厅ID，可选
  - `sort`: 排序方式，可选（popularity, rating, price_asc, price_desc）

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "products": [
      {
        "id": 101,
        "name": "Classic Cheeseburger",
        "image": "https://example.com/dishes/cheeseburger.jpg",
        "price": 8.99,
        "rating": 4.7,
        "reviewCount": 123,
        "restaurantId": 1,
        "restaurantName": "Burger Palace"
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 20,
      "total": 100,
      "pages": 5
    }
  }
}
```

### 6.2 获取商品详情

**请求**:

- **路径**: `/products/{id}`
- **方法**: GET
- **参数**: 无

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "id": 101,
    "name": "Classic Cheeseburger",
    "description": "Juicy beef patty with melted cheese",
    "longDescription": "Our signature Classic Cheeseburger features a 100% pure beef patty...",
    "price": 8.99,
    "images": [
      "https://example.com/dishes/cheeseburger-1.jpg",
      "https://example.com/dishes/cheeseburger-2.jpg"
    ],
    "rating": 4.7,
    "reviewCount": 123,
    "nutrition": {
      "calories": 650,
      "protein": 35,
      "carbs": 40,
      "fat": 32
    },
    "allergens": ["Gluten", "Dairy", "Soy"],
    "ingredients": ["100% Pure Beef Patty", "American Cheese", "Sesame Seed Bun"],
    "spiceLevels": [
      { "name": "Mild", "value": 1 },
      { "name": "Medium", "value": 2 },
      { "name": "Spicy", "value": 3 },
      { "name": "Extra Spicy", "value": 4 }
    ],
    "restaurantId": 1,
    "restaurantName": "Burger Palace"
  }
}
```

### 6.3 获取商品评价

**请求**:

- **路径**: `/products/{id}/reviews`
- **方法**: GET
- **参数**:
  - `page`: 页码，默认1
  - `limit`: 每页数量，默认10

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "reviews": [
      {
        "id": 1,
        "name": "John Smith",
        "avatar": "https://example.com/avatars/john.jpg",
        "rating": 5,
        "date": "2023-10-15T14:30:00Z",
        "comment": "Best burger I've ever had!"
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 10,
      "total": 123,
      "pages": 13
    }
  }
}
```

### 6.4 获取相关商品

**请求**:

- **路径**: `/products/{id}/related`
- **方法**: GET
- **参数**:
  - `limit`: 数量限制，默认4

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "products": [
      {
        "id": 102,
        "name": "Double Bacon Burger",
        "description": "Two beef patties with crispy bacon",
        "price": 12.99,
        "image": "https://example.com/dishes/bacon-burger.jpg"
      }
    ]
  }
}
```

## 7. 搜索接口 (续)

### 7.1 搜索商品和餐厅 (续)

**请求参数** (续):

- `filters`: 筛选条件，JSON格式，可包含以下字段:
  - `priceRange`: 价格范围数组，如 ["$", "$$"]
  - `minRating`: 最低评分，如 4
  - `restaurants`: 餐厅ID数组，如 [1, 2, 3]
  - `dietary`: 饮食选项数组，如 ["Vegetarian", "Gluten-Free"]

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "restaurants": [
      {
        "id": 1,
        "name": "Burger Palace",
        "image": "https://example.com/restaurants/burger-palace.jpg",
        "rating": 4.7,
        "deliveryTime": "25-35 min",
        "deliveryFee": "Free Delivery",
        "tags": ["Burgers", "American", "Fast Food"]
      }
    ],
    "products": [
      {
        "id": 101,
        "name": "Classic Cheeseburger",
        "image": "https://example.com/dishes/cheeseburger.jpg",
        "price": 8.99,
        "rating": 4.7,
        "reviewCount": 123,
        "restaurantId": 1,
        "restaurantName": "Burger Palace"
      }
    ],
    "totalResults": 25,
    "pagination": {
      "page": 1,
      "limit": 20,
      "total": 25,
      "pages": 2
    }
  }
}
```

### 7.2 获取搜索建议

**请求**:

- **路径**: `/search/suggestions`
- **方法**: GET
- **参数**:
  - `query`: 搜索关键词，必填

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "suggestions": ["Burger", "Burger Palace", "Beef Burger", "Bacon Burger", "Best Burgers"]
  }
}
```

### 7.3 获取搜索历史

**请求**:

- **路径**: `/search/history`
- **方法**: GET
- **认证**: 需要

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "history": ["pizza", "burger", "sushi", "salad", "italian"]
  }
}
```

### 7.4 清除搜索历史

**请求**:

- **路径**: `/search/history`
- **方法**: DELETE
- **认证**: 需要

**响应**:

```json
{
  "code": 200,
  "message": "Search history cleared",
  "data": null
}
```

## 8. 分类接口

### 8.1 获取所有分类

**请求**:

- **路径**: `/categories`
- **方法**: GET
- **参数**: 无

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "categories": [
      {
        "id": 1,
        "name": "Burgers",
        "icon": "https://example.com/icons/burger.png"
      },
      {
        "id": 2,
        "name": "Pizza",
        "icon": "https://example.com/icons/pizza.png"
      }
    ]
  }
}
```

### 8.2 获取分类详情

**请求**:

- **路径**: `/categories/{id}`
- **方法**: GET
- **参数**: 无

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "id": 1,
    "name": "Burgers",
    "image": "https://example.com/categories/burgers.jpg",
    "description": "Delicious burgers from the best restaurants in town",
    "subcategories": [
      {
        "id": 101,
        "name": "All",
        "parentId": 1
      },
      {
        "id": 102,
        "name": "Beef Burgers",
        "parentId": 1
      }
    ]
  }
}
```

### 8.3 获取分类下的商品

**请求**:

- **路径**: `/categories/{id}/products`
- **方法**: GET
- **参数**:
  - `page`: 页码，默认1
  - `limit`: 每页数量，默认20
  - `subcategory`: 子分类ID，可选
  - `sort`: 排序方式，可选
  - `filters`: 筛选条件，JSON格式，同搜索接口

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "products": [
      {
        "id": 101,
        "name": "Classic Cheeseburger",
        "image": "https://example.com/dishes/cheeseburger.jpg",
        "price": 8.99,
        "rating": 4.7,
        "reviewCount": 123,
        "restaurantId": 1,
        "restaurantName": "Burger Palace",
        "badge": "Popular",
        "categoryId": 1,
        "subcategoryId": 102
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 20,
      "total": 50,
      "pages": 3
    }
  }
}
```

## 9. 购物车接口

### 9.1 获取购物车

**请求**:

- **路径**: `/cart`
- **方法**: GET
- **认证**: 需要

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "items": [
      {
        "id": "cart_item_1",
        "productId": 101,
        "name": "Classic Cheeseburger",
        "price": 8.99,
        "originalPrice": 10.99,
        "image": "https://example.com/dishes/cheeseburger.jpg",
        "quantity": 2,
        "restaurantId": 1,
        "restaurantName": "Burger Palace",
        "options": [
          {
            "name": "Spice Level",
            "value": "Medium"
          }
        ]
      }
    ],
    "subtotal": 17.98,
    "totalItems": 2
  }
}
```

### 9.2 添加商品到购物车

**请求**:

- **路径**: `/cart/items`
- **方法**: POST
- **认证**: 需要
- **参数**:

```json
{
  "productId": 101,
  "quantity": 2,
  "options": [
    {
      "name": "Spice Level",
      "value": "Medium"
    }
  ]
}
```

**响应**:

```json
{
  "code": 200,
  "message": "Item added to cart",
  "data": {
    "cartItem": {
      "id": "cart_item_1",
      "productId": 101,
      "name": "Classic Cheeseburger",
      "price": 8.99,
      "originalPrice": 10.99,
      "image": "https://example.com/dishes/cheeseburger.jpg",
      "quantity": 2,
      "restaurantId": 1,
      "restaurantName": "Burger Palace",
      "options": [
        {
          "name": "Spice Level",
          "value": "Medium"
        }
      ]
    },
    "cart": {
      "subtotal": 17.98,
      "totalItems": 2
    }
  }
}
```

### 9.3 更新购物车商品

**请求**:

- **路径**: `/cart/items/{itemId}`
- **方法**: PUT
- **认证**: 需要
- **参数**:

```json
{
  "quantity": 3
}
```

**响应**:

```json
{
  "code": 200,
  "message": "Cart item updated",
  "data": {
    "cartItem": {
      "id": "cart_item_1",
      "productId": 101,
      "name": "Classic Cheeseburger",
      "price": 8.99,
      "originalPrice": 10.99,
      "image": "https://example.com/dishes/cheeseburger.jpg",
      "quantity": 3,
      "restaurantId": 1,
      "restaurantName": "Burger Palace",
      "options": [
        {
          "name": "Spice Level",
          "value": "Medium"
        }
      ]
    },
    "cart": {
      "subtotal": 26.97,
      "totalItems": 3
    }
  }
}
```

### 9.4 删除购物车商品

**请求**:

- **路径**: `/cart/items/{itemId}`
- **方法**: DELETE
- **认证**: 需要

**响应**:

```json
{
  "code": 200,
  "message": "Cart item removed",
  "data": {
    "cart": {
      "subtotal": 0,
      "totalItems": 0
    }
  }
}
```

### 9.5 清空购物车

**请求**:

- **路径**: `/cart`
- **方法**: DELETE
- **认证**: 需要

**响应**:

```json
{
  "code": 200,
  "message": "Cart cleared",
  "data": null
}
```

## 10. 订单接口

### 10.1 创建订单

**请求**:

- **路径**: `/orders`
- **方法**: POST
- **认证**: 需要
- **参数**:

```json
{
  "address": {
    "name": "John Doe",
    "phone": "123-456-7890",
    "email": "john@example.com",
    "address1": "123 Main St",
    "address2": "Apt 4B",
    "city": "New York",
    "state": "NY",
    "zipCode": "10001",
    "instructions": "Leave at door"
  },
  "paymentMethod": "paypal",
  "notes": "Please include extra napkins"
}
```

**响应**:

```json
{
  "code": 200,
  "message": "Order created",
  "data": {
    "orderId": 1001,
    "status": "placed",
    "total": 26.96,
    "paymentUrl": "https://api.cokfood.com/payments/process/1001"
  }
}
```

### 10.2 获取订单列表

**请求**:

- **路径**: `/orders`
- **方法**: GET
- **认证**: 需要
- **参数**:
  - `status`: 订单状态过滤，可选（current, past）
  - `page`: 页码，默认1
  - `limit`: 每页数量，默认10

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "orders": [
      {
        "id": 1001,
        "restaurantName": "Burger Palace",
        "restaurantImage": "https://example.com/restaurants/burger-palace.jpg",
        "orderDate": "2023-05-01T14:30:00Z",
        "status": "preparing",
        "items": [
          {
            "name": "Classic Cheeseburger",
            "quantity": 2,
            "price": 8.99
          },
          {
            "name": "French Fries",
            "quantity": 1,
            "price": 3.99
          }
        ],
        "total": 26.96
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 10,
      "total": 5,
      "pages": 1
    }
  }
}
```

### 10.3 获取订单详情

**请求**:

- **路径**: `/orders/{id}`
- **方法**: GET
- **认证**: 需要

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "id": 1001,
    "restaurantName": "Burger Palace",
    "restaurantImage": "https://example.com/restaurants/burger-palace.jpg",
    "orderDate": "2023-05-01T14:30:00Z",
    "status": "preparing",
    "items": [
      {
        "name": "Classic Cheeseburger",
        "quantity": 2,
        "price": 8.99,
        "options": [
          {
            "name": "Spice Level",
            "value": "Medium"
          }
        ]
      },
      {
        "name": "French Fries",
        "quantity": 1,
        "price": 3.99
      }
    ],
    "subtotal": 21.97,
    "deliveryFee": 2.99,
    "tax": 1.76,
    "total": 26.72,
    "address": {
      "name": "John Doe",
      "phone": "123-456-7890",
      "email": "john@example.com",
      "address1": "123 Main St",
      "address2": "Apt 4B",
      "city": "New York",
      "state": "NY",
      "zipCode": "10001",
      "instructions": "Leave at door"
    },
    "paymentMethod": "paypal",
    "notes": "Please include extra napkins",
    "timeline": [
      {
        "status": "placed",
        "timestamp": "2023-05-01T14:30:00Z"
      },
      {
        "status": "preparing",
        "timestamp": "2023-05-01T14:35:00Z"
      }
    ]
  }
}
```

### 10.4 取消订单

**请求**:

- **路径**: `/orders/{id}/cancel`
- **方法**: POST
- **认证**: 需要

**响应**:

```json
{
  "code": 200,
  "message": "Order cancelled successfully",
  "data": {
    "id": 1001,
    "status": "cancelled",
    "cancelledAt": "2023-05-01T14:40:00Z"
  }
}
```

**响应** (续):

```json
{
  "code": 200,
  "message": "Items added to cart",
  "data": {
    "cart": {
      "items": [
        {
          "id": "cart_item_1",
          "productId": 101,
          "name": "Classic Cheeseburger",
          "price": 8.99,
          "originalPrice": 10.99,
          "image": "https://example.com/dishes/cheeseburger.jpg",
          "quantity": 2,
          "restaurantId": 1,
          "restaurantName": "Burger Palace",
          "options": [
            {
              "name": "Spice Level",
              "value": "Medium"
            }
          ]
        },
        {
          "id": "cart_item_2",
          "productId": 105,
          "name": "French Fries",
          "price": 3.99,
          "originalPrice": 3.99,
          "image": "https://example.com/dishes/fries.jpg",
          "quantity": 1,
          "restaurantId": 1,
          "restaurantName": "Burger Palace"
        }
      ],
      "subtotal": 21.97,
      "totalItems": 3
    },
    "redirectUrl": "/checkout"
  }
}
```

## 11. 分销商代付功能接口

### 11.1 生成代付链接 (分销商)

**请求**:

- **路径**: `/distributor/payment-links`
- **方法**: POST
- **认证**: 需要 (分销商角色)
- **参数**:

```json
{
  "productId": 101,
  "discountPrice": 12.99,
  "displayName": "John Smith"
}
```

**响应**:

```json
{
  "code": 200,
  "message": "Payment link generated successfully",
  "data": {
    "linkId": "pl_123456789",
    "paymentLink": "https://cokfood.com/pay/pl_123456789",
    "qrCodeUrl": "https://api.cokfood.com/qr-codes/pl_123456789.png",
    "product": {
      "id": 101,
      "name": "Classic Cheeseburger",
      "image": "https://example.com/dishes/cheeseburger.jpg",
      "originalPrice": 15.99,
      "discountPrice": 12.99
    },
    "commission": {
      "rate": 10,
      "amount": 0.3
    },
    "createdAt": "2023-05-01T15:30:00Z",
    "expiresAt": "2023-05-08T15:30:00Z"
  }
}
```

### 11.2 获取代付链接列表 (分销商)

**请求**:

- **路径**: `/distributor/payment-links`
- **方法**: GET
- **认证**: 需要 (分销商角色)
- **参数**:
  - `page`: 页码，默认1
  - `limit`: 每页数量，默认20
  - `status`: 状态过滤，可选 (active, expired, all)

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "links": [
      {
        "id": "pl_123456789",
        "paymentLink": "https://cokfood.com/pay/pl_123456789",
        "product": {
          "id": 101,
          "name": "Classic Cheeseburger",
          "image": "https://example.com/dishes/cheeseburger.jpg"
        },
        "originalPrice": 15.99,
        "discountPrice": 12.99,
        "displayName": "John Smith",
        "commission": 0.3,
        "status": "active",
        "usageCount": 2,
        "createdAt": "2023-05-01T15:30:00Z",
        "expiresAt": "2023-05-08T15:30:00Z"
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 20,
      "total": 5,
      "pages": 1
    }
  }
}
```

### 11.3 获取代付链接详情 (分销商)

**请求**:

- **路径**: `/distributor/payment-links/{id}`
- **方法**: GET
- **认证**: 需要 (分销商角色)

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "id": "pl_123456789",
    "paymentLink": "https://cokfood.com/pay/pl_123456789",
    "qrCodeUrl": "https://api.cokfood.com/qr-codes/pl_123456789.png",
    "product": {
      "id": 101,
      "name": "Classic Cheeseburger",
      "image": "https://example.com/dishes/cheeseburger.jpg",
      "originalPrice": 15.99,
      "discountPrice": 12.99
    },
    "displayName": "John Smith",
    "commission": {
      "rate": 10,
      "amount": 0.3
    },
    "status": "active",
    "usageCount": 2,
    "transactions": [
      {
        "id": "tr_987654321",
        "amount": 12.99,
        "commission": 0.3,
        "status": "completed",
        "payerIp": "123.45.67.89",
        "createdAt": "2023-05-02T10:15:00Z"
      },
      {
        "id": "tr_987654322",
        "amount": 12.99,
        "commission": 0.3,
        "status": "completed",
        "payerIp": "123.45.67.90",
        "createdAt": "2023-05-03T14:20:00Z"
      }
    ],
    "createdAt": "2023-05-01T15:30:00Z",
    "expiresAt": "2023-05-08T15:30:00Z"
  }
}
```

### 11.4 停用/启用代付链接 (分销商)

**请求**:

- **路径**: `/distributor/payment-links/{id}/toggle`
- **方法**: PUT
- **认证**: 需要 (分销商角色)
- **参数**:

```json
{
  "active": false
}
```

**响应**:

```json
{
  "code": 200,
  "message": "Payment link updated successfully",
  "data": {
    "id": "pl_123456789",
    "status": "inactive",
    "updatedAt": "2023-05-04T09:30:00Z"
  }
}
```

### 11.5 代付链接支付 (公开API)

**请求**:

- **路径**: `/pay/{linkId}`
- **方法**: GET
- **参数**: 无

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "linkId": "pl_123456789",
    "product": {
      "id": 101,
      "name": "Classic Cheeseburger",
      "image": "https://example.com/dishes/cheeseburger.jpg",
      "description": "Juicy beef patty with melted cheese",
      "price": 12.99,
      "originalPrice": 15.99
    },
    "displayName": "John Smith",
    "paymentMethods": ["paypal"],
    "paymentUrl": "https://api.cokfood.com/pay/pl_123456789/process"
  }
}
```

### 11.6 处理代付链接支付 (公开API)

**请求**:

- **路径**: `/pay/{linkId}/process`
- **方法**: POST
- **参数**:

```json
{
  "paymentMethod": "paypal",
  "quantity": 1
}
```

**响应**:

```json
{
  "code": 200,
  "message": "Payment initiated",
  "data": {
    "transactionId": "tr_987654323",
    "amount": 12.99,
    "paymentUrl": "https://www.paypal.com/checkoutnow?token=EC-123456789"
  }
}
```

### 11.7 代付支付成功回调 (公开API)

**请求**:

- **路径**: `/pay/{linkId}/callback`
- **方法**: GET
- **参数**:
  - `transactionId`: 交易ID
  - `status`: 支付状态 (success, cancel)

**响应**:

```json
{
  "code": 200,
  "message": "Payment processed successfully",
  "data": {
    "status": "success",
    "transactionId": "tr_987654323",
    "amount": 12.99,
    "helpingName": "John Smith",
    "product": {
      "name": "Classic Cheeseburger",
      "image": "https://example.com/dishes/cheeseburger.jpg"
    },
    "successUrl": "https://cokfood.com/pay/success/tr_987654323"
  }
}
```

### 11.8 获取代付支付成功页面 (公开API)

**请求**:

- **路径**: `/pay/success/{transactionId}`
- **方法**: GET
- **参数**: 无

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "transaction": {
      "id": "tr_987654323",
      "status": "completed",
      "amount": 12.99,
      "createdAt": "2023-05-04T15:30:00Z"
    },
    "product": {
      "name": "Classic Cheeseburger",
      "image": "https://example.com/dishes/cheeseburger.jpg"
    },
    "helpingName": "John Smith"
  }
}
```

## 12. 分销商佣金与提现接口

### 12.1 获取佣金概览 (分销商)

**请求**:

- **路径**: `/distributor/commissions/overview`
- **方法**: GET
- **认证**: 需要 (分销商角色)

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "totalCommission": 150.75,
    "withdrawnCommission": 100.0,
    "pendingCommission": 20.75,
    "availableCommission": 30.0,
    "todayEarnings": 5.25,
    "weekEarnings": 15.75,
    "monthEarnings": 45.5,
    "commissionRate": 10
  }
}
```

### 12.2 获取佣金历史 (分销商)

**请求**:

- **路径**: `/distributor/commissions`
- **方法**: GET
- **认证**: 需要 (分销商角色)
- **参数**:
  - `page`: 页码，默认1
  - `limit`: 每页数量，默认20
  - `status`: 状态过滤，可选 (pending, settled, all)
  - `dateFrom`: 开始日期，格式YYYY-MM-DD，可选
  - `dateTo`: 结束日期，格式YYYY-MM-DD，可选

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "commissions": [
      {
        "id": "com_123456789",
        "transactionId": "tr_987654321",
        "product": {
          "id": 101,
          "name": "Classic Cheeseburger",
          "image": "https://example.com/dishes/cheeseburger.jpg"
        },
        "originalPrice": 15.99,
        "discountPrice": 12.99,
        "commissionAmount": 0.3,
        "commissionRate": 10,
        "status": "settled",
        "createdAt": "2023-05-02T10:15:00Z",
        "settledAt": "2023-05-09T00:00:00Z"
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 20,
      "total": 15,
      "pages": 1
    }
  }
}
```

### 12.3 申请提现 (分销商)

**请求**:

- **路径**: `/distributor/withdrawals`
- **方法**: POST
- **认证**: 需要 (分销商角色)
- **参数**:

```json
{
  "amount": 30.0,
  "usdtAddress": "0x1234567890abcdef1234567890abcdef12345678"
}
```

**响应**:

```json
{
  "code": 200,
  "message": "Withdrawal request submitted successfully",
  "data": {
    "id": "wdr_123456789",
    "amount": 30.0,
    "status": "pending",
    "usdtAddress": "0x1234567890abcdef1234567890abcdef12345678",
    "createdAt": "2023-05-05T14:30:00Z",
    "estimatedProcessingTime": "3-5 business days"
  }
}
```

### 12.4 获取提现历史 (分销商)

**请求**:

- **路径**: `/distributor/withdrawals`
- **方法**: GET
- **认证**: 需要 (分销商角色)
- **参数**:
  - `page`: 页码，默认1
  - `limit`: 每页数量，默认20
  - `status`: 状态过滤，可选 (pending, approved, rejected, completed, all)

**响应**:
**响应** (续):

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "withdrawals": [
      {
        "id": "wdr_123456789",
        "amount": 30.0,
        "status": "pending",
        "usdtAddress": "0x1234567890abcdef1234567890abcdef12345678",
        "createdAt": "2023-05-05T14:30:00Z",
        "updatedAt": null,
        "completedAt": null,
        "remarks": null
      },
      {
        "id": "wdr_123456788",
        "amount": 100.0,
        "status": "completed",
        "usdtAddress": "0x1234567890abcdef1234567890abcdef12345678",
        "createdAt": "2023-04-20T10:15:00Z",
        "updatedAt": "2023-04-21T09:30:00Z",
        "completedAt": "2023-04-22T14:45:00Z",
        "remarks": "Payment sent to provided USDT address"
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 20,
      "total": 2,
      "pages": 1
    },
    "summary": {
      "totalWithdrawn": 100.0,
      "pendingWithdrawals": 30.0,
      "availableBalance": 20.75
    }
  }
}
```

### 12.5 获取提现详情 (分销商)

**请求**:

- **路径**: `/distributor/withdrawals/{id}`
- **方法**: GET
- **认证**: 需要 (分销商角色)

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "id": "wdr_123456789",
    "amount": 30.0,
    "status": "pending",
    "usdtAddress": "0x1234567890abcdef1234567890abcdef12345678",
    "createdAt": "2023-05-05T14:30:00Z",
    "updatedAt": null,
    "completedAt": null,
    "remarks": null,
    "timeline": [
      {
        "status": "pending",
        "timestamp": "2023-05-05T14:30:00Z",
        "description": "Withdrawal request submitted"
      }
    ],
    "estimatedCompletionDate": "2023-05-10"
  }
}
```

## 13. 区域代理接口

### 13.1 获取区域代理概览

**请求**:

- **路径**: `/agent/dashboard`
- **方法**: GET
- **认证**: 需要 (区域代理角色)

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "region": "Northeast",
    "distributorCount": 15,
    "activeDistributorCount": 12,
    "salesOverview": {
      "today": 256.75,
      "week": 1845.5,
      "month": 7890.25,
      "total": 45678.9
    },
    "commissionOverview": {
      "today": 25.68,
      "week": 184.55,
      "month": 789.03,
      "total": 4567.89,
      "pending": 120.45,
      "available": 245.78
    },
    "pendingWithdrawals": 3,
    "topDistributors": [
      {
        "id": "dist_123",
        "name": "John Smith",
        "sales": 1245.5,
        "commissions": 124.55
      }
    ],
    "topProducts": [
      {
        "id": 101,
        "name": "Classic Cheeseburger",
        "image": "https://example.com/dishes/cheeseburger.jpg",
        "sales": 89,
        "revenue": 801.11
      }
    ]
  }
}
```

### 13.2 获取区域内经销商列表

**请求**:

- **路径**: `/agent/distributors`
- **方法**: GET
- **认证**: 需要 (区域代理角色)
- **参数**:
  - `page`: 页码，默认1
  - `limit`: 每页数量，默认20
  - `status`: 状态过滤，可选 (active, inactive, all)
  - `sort`: 排序方式，可选 (name, sales, commissions, date_joined)

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "distributors": [
      {
        "id": "dist_123",
        "name": "John Smith",
        "email": "john@example.com",
        "phone": "123-456-7890",
        "avatar": "https://example.com/avatars/john.jpg",
        "status": "active",
        "commissionRate": 10,
        "sales": {
          "total": 1245.5,
          "month": 345.25,
          "week": 125.75
        },
        "commissions": {
          "total": 124.55,
          "month": 34.53,
          "week": 12.58
        },
        "paymentLinks": {
          "total": 15,
          "active": 8
        },
        "joinedAt": "2023-03-15T10:30:00Z",
        "lastActiveAt": "2023-05-05T16:45:00Z"
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 20,
      "total": 15,
      "pages": 1
    }
  }
}
```

### 13.3 获取经销商详情

**请求**:

- **路径**: `/agent/distributors/{id}`
- **方法**: GET
- **认证**: 需要 (区域代理角色)

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "id": "dist_123",
    "name": "John Smith",
    "email": "john@example.com",
    "phone": "123-456-7890",
    "avatar": "https://example.com/avatars/john.jpg",
    "address": "123 Main St, New York, NY 10001",
    "status": "active",
    "commissionRate": 10,
    "usdtAddress": "0x1234567890abcdef1234567890abcdef12345678",
    "statistics": {
      "totalSales": 1245.5,
      "totalCommissions": 124.55,
      "totalOrders": 35,
      "totalLinks": 15,
      "activeLinks": 8,
      "conversionRate": 23.5
    },
    "salesTrend": [
      {
        "date": "2023-04-29",
        "sales": 45.98,
        "commissions": 4.6
      },
      {
        "date": "2023-04-30",
        "sales": 32.97,
        "commissions": 3.3
      },
      {
        "date": "2023-05-01",
        "sales": 56.94,
        "commissions": 5.69
      }
    ],
    "topProducts": [
      {
        "id": 101,
        "name": "Classic Cheeseburger",
        "image": "https://example.com/dishes/cheeseburger.jpg",
        "sales": 12,
        "revenue": 107.88
      }
    ],
    "recentPaymentLinks": [
      {
        "id": "pl_123456789",
        "product": {
          "id": 101,
          "name": "Classic Cheeseburger",
          "image": "https://example.com/dishes/cheeseburger.jpg"
        },
        "originalPrice": 15.99,
        "discountPrice": 12.99,
        "usageCount": 2,
        "createdAt": "2023-05-01T15:30:00Z"
      }
    ],
    "joinedAt": "2023-03-15T10:30:00Z",
    "lastActiveAt": "2023-05-05T16:45:00Z"
  }
}
```

### 13.4 更新经销商佣金比例

**请求**:

- **路径**: `/agent/distributors/{id}/commission`
- **方法**: PUT
- **认证**: 需要 (区域代理角色)
- **参数**:

```json
{
  "commissionRate": 12
}
```

**响应**:

```json
{
  "code": 200,
  "message": "Commission rate updated successfully",
  "data": {
    "id": "dist_123",
    "name": "John Smith",
    "commissionRate": 12,
    "updatedAt": "2023-05-06T09:15:00Z"
  }
}
```

### 13.5 审核提现申请

**请求**:

- **路径**: `/agent/withdrawals/{id}/review`
- **方法**: PUT
- **认证**: 需要 (区域代理角色)
- **参数**:

```json
{
  "action": "approve",
  "remarks": "Approved for payment"
}
```

**响应**:

```json
{
  "code": 200,
  "message": "Withdrawal request approved",
  "data": {
    "id": "wdr_123456789",
    "status": "approved",
    "reviewer": "Agent Name",
    "reviewedAt": "2023-05-06T10:30:00Z",
    "remarks": "Approved for payment"
  }
}
```

## 14. 管理员接口

### 14.1 获取管理员仪表盘数据

**请求**:

- **路径**: `/admin/dashboard`
- **方法**: GET
- **认证**: 需要 (管理员角色)

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "userCounts": {
      "total": 1250,
      "distributors": 45,
      "agents": 5,
      "admins": 2,
      "newToday": 15
    },
    "salesData": {
      "today": 1256.75,
      "week": 8845.5,
      "month": 37890.25,
      "total": 145678.9
    },
    "orderStats": {
      "total": 3567,
      "today": 89,
      "pending": 12,
      "processing": 34,
      "completed": 3521
    },
    "commissionStats": {
      "total": 14567.89,
      "pendingWithdrawals": 1245.67,
      "processedWithdrawals": 13322.22
    },
    "topRestaurants": [
      {
        "id": 1,
        "name": "Burger Palace",
        "image": "https://example.com/restaurants/burger-palace.jpg",
        "orders": 456,
        "revenue": 5678.9
      }
    ],
    "topProducts": [
      {
        "id": 101,
        "name": "Classic Cheeseburger",
        "image": "https://example.com/dishes/cheeseburger.jpg",
        "orders": 345,
        "revenue": 3101.55
      }
    ],
    "topDistributors": [
      {
        "id": "dist_123",
        "name": "John Smith",
        "avatar": "https://example.com/avatars/john.jpg",
        "sales": 3456.78,
        "commissions": 345.68
      }
    ],
    "recentWithdrawals": [
      {
        "id": "wdr_123456789",
        "distributorName": "John Smith",
        "amount": 30.0,
        "status": "pending",
        "createdAt": "2023-05-05T14:30:00Z"
      }
    ]
  }
}
```

### 14.2 管理餐厅

#### 14.2.1 创建餐厅

**请求**:

- **路径**: `/admin/restaurants`
- **方法**: POST
- **认证**: 需要 (管理员角色)
- **参数**:

```json
{
  "name": "New Restaurant",
  "description": "A delicious new restaurant",
  "logo": "base64encoded_image_data",
  "coverImage": "base64encoded_image_data",
  "address": {
    "line1": "123 Main St",
    "line2": "Suite 4",
    "city": "New York",
    "state": "NY",
    "zipCode": "10001",
    "country": "USA"
  },
  "contactInfo": {
    "phone": "123-456-7890",
    "email": "contact@newrestaurant.com",
    "website": "https://newrestaurant.com"
  },
  "deliveryFee": "Free Delivery",
  "deliveryTime": "25-35 min",
  "tags": ["Italian", "Pizza", "Pasta"]
}
```

**响应**:

```json
{
  "code": 200,
  "message": "Restaurant created successfully",
  "data": {
    "id": 10,
    "name": "New Restaurant",
    "createdAt": "2023-05-06T11:30:00Z"
  }
}
```

#### 14.2.2 更新餐厅信息

**请求**:

- **路径**: `/admin/restaurants/{id}`
- **方法**: PUT
- **认证**: 需要 (管理员角色)
- **参数**: 同创建餐厅

**响应**:

```json
{
  "code": 200,
  "message": "Restaurant updated successfully",
  "data": {
    "id": 10,
    "name": "New Restaurant",
    "updatedAt": "2023-05-06T12:15:00Z"
  }
}
```

### 14.3 管理产品

#### 14.3.1 创建产品 (续)

**请求参数** (续):

```json
{
  "name": "New Product",
  "description": "A delicious new product",
  "longDescription": "Detailed description of the product...",
  "price": 12.99,
  "images": ["base64encoded_image_data", "base64encoded_image_data"],
  "nutrition": {
    "calories": 650,
    "protein": 35,
    "carbs": 40,
    "fat": 32
  },
  "allergens": ["Gluten", "Dairy", "Soy"],
  "ingredients": ["Ingredient 1", "Ingredient 2", "Ingredient 3"],
  "spiceLevels": [
    { "name": "Mild", "value": 1 },
    { "name": "Medium", "value": 2 },
    { "name": "Spicy", "value": 3 },
    { "name": "Extra Spicy", "value": 4 }
  ],
  "restaurantId": 1,
  "categoryId": 2,
  "subcategoryId": 102,
  "minDiscountPrice": 9.99
}
```

**响应**:

```json
{
  "code": 200,
  "message": "Product created successfully",
  "data": {
    "id": 201,
    "name": "New Product",
    "createdAt": "2023-05-06T14:30:00Z"
  }
}
```

#### 14.3.2 更新产品

**请求**:

- **路径**: `/admin/products/{id}`
- **方法**: PUT
- **认证**: 需要 (管理员角色)
- **参数**: 同创建产品

**响应**:

```json
{
  "code": 200,
  "message": "Product updated successfully",
  "data": {
    "id": 201,
    "name": "Updated Product Name",
    "updatedAt": "2023-05-06T15:45:00Z"
  }
}
```

#### 14.3.3 删除产品

**请求**:

- **路径**: `/admin/products/{id}`
- **方法**: DELETE
- **认证**: 需要 (管理员角色)

**响应**:

```json
{
  "code": 200,
  "message": "Product deleted successfully",
  "data": null
}
```

### 14.4 管理用户

#### 14.4.1 创建区域代理

**请求**:

- **路径**: `/admin/agents`
- **方法**: POST
- **认证**: 需要 (管理员角色)
- **参数**:

```json
{
  "name": "New Agent",
  "email": "agent@example.com",
  "phone": "123-456-7890",
  "password": "securePassword123",
  "region": "Northeast",
  "commissionRate": 15,
  "address": {
    "line1": "123 Main St",
    "line2": "Suite 4",
    "city": "New York",
    "state": "NY",
    "zipCode": "10001",
    "country": "USA"
  }
}
```

**响应**:

```json
{
  "code": 200,
  "message": "Agent created successfully",
  "data": {
    "id": "agent_123",
    "name": "New Agent",
    "email": "agent@example.com",
    "region": "Northeast",
    "commissionRate": 15,
    "createdAt": "2023-05-06T16:30:00Z"
  }
}
```

#### 14.4.2 创建分销商

**请求**:

- **路径**: `/admin/distributors`
- **方法**: POST
- **认证**: 需要 (管理员角色)
- **参数**:

```json
{
  "name": "New Distributor",
  "email": "distributor@example.com",
  "phone": "123-456-7890",
  "password": "securePassword123",
  "agentId": "agent_123",
  "commissionRate": 10,
  "address": {
    "line1": "123 Main St",
    "line2": "Suite 4",
    "city": "New York",
    "state": "NY",
    "zipCode": "10001",
    "country": "USA"
  }
}
```

**响应**:

```json
{
  "code": 200,
  "message": "Distributor created successfully",
  "data": {
    "id": "dist_456",
    "name": "New Distributor",
    "email": "distributor@example.com",
    "agentId": "agent_123",
    "commissionRate": 10,
    "createdAt": "2023-05-06T17:15:00Z"
  }
}
```

#### 14.4.3 更新用户状态

**请求**:

- **路径**: `/admin/users/{id}/status`
- **方法**: PUT
- **认证**: 需要 (管理员角色)
- **参数**:

```json
{
  "status": "inactive",
  "reason": "Account suspended due to policy violation"
}
```

**响应**:

```json
{
  "code": 200,
  "message": "User status updated successfully",
  "data": {
    "id": "dist_456",
    "status": "inactive",
    "updatedAt": "2023-05-06T18:00:00Z"
  }
}
```

### 14.5 管理佣金

#### 14.5.1 设置全局佣金比例

**请求**:

- **路径**: `/admin/settings/commissions`
- **方法**: PUT
- **认证**: 需要 (管理员角色)
- **参数**:

```json
{
  "defaultAgentRate": 15,
  "defaultDistributorRate": 10,
  "minDistributorRate": 5,
  "maxDistributorRate": 20
}
```

**响应**:

```json
{
  "code": 200,
  "message": "Commission rates updated successfully",
  "data": {
    "defaultAgentRate": 15,
    "defaultDistributorRate": 10,
    "minDistributorRate": 5,
    "maxDistributorRate": 20,
    "updatedAt": "2023-05-07T09:30:00Z"
  }
}
```

#### 14.5.2 设置特殊商品佣金比例

**请求**:

- **路径**: `/admin/products/{id}/commission`
- **方法**: PUT
- **认证**: 需要 (管理员角色)
- **参数**:

```json
{
  "specialCommissionRate": 15,
  "reason": "Promotional product"
}
```

**响应**:

```json
{
  "code": 200,
  "message": "Special commission rate set successfully",
  "data": {
    "productId": 201,
    "name": "Special Product",
    "standardRate": 10,
    "specialRate": 15,
    "updatedAt": "2023-05-07T10:15:00Z"
  }
}
```

### 14.6 管理提现

#### 14.6.1 查看所有提现请求

**请求**:

- **路径**: `/admin/withdrawals`
- **方法**: GET
- **认证**: 需要 (管理员角色)
- **参数**:
  - `page`: 页码，默认1
  - `limit`: 每页数量，默认20
  - `status`: 状态过滤，可选 (pending, approved, rejected, completed, all)
  - `dateFrom`: 开始日期，格式YYYY-MM-DD，可选
  - `dateTo`: 结束日期，格式YYYY-MM-DD，可选

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "withdrawals": [
      {
        "id": "wdr_123456789",
        "userId": "dist_456",
        "userName": "John Smith",
        "userType": "distributor",
        "amount": 30.0,
        "status": "approved",
        "usdtAddress": "0x1234567890abcdef1234567890abcdef12345678",
        "createdAt": "2023-05-05T14:30:00Z",
        "approvedAt": "2023-05-06T10:30:00Z",
        "completedAt": null,
        "approvedBy": "agent_123",
        "approverName": "Agent Name",
        "remarks": "Approved for payment"
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 20,
      "total": 45,
      "pages": 3
    }
  }
}
```

#### 14.6.2 处理提现请求

**请求**:

- **路径**: `/admin/withdrawals/{id}/process`
- **方法**: PUT
- **认证**: 需要 (管理员角色)
- **参数**:

```json
{
  "action": "complete",
  "transactionHash": "0xabcdef1234567890abcdef1234567890abcdef1234567890",
  "remarks": "Payment sent to USDT address"
}
```

**响应**:

```json
{
  "code": 200,
  "message": "Withdrawal request processed successfully",
  "data": {
    "id": "wdr_123456789",
    "status": "completed",
    "processedBy": "admin_1",
    "processorName": "Admin User",
    "processedAt": "2023-05-07T11:30:00Z",
    "transactionHash": "0xabcdef1234567890abcdef1234567890abcdef1234567890",
    "remarks": "Payment sent to USDT address"
  }
}
```

## 15. 公共数据接口

### 15.1 获取支持的支付方式

**请求**:

- **路径**: `/public/payment-methods`
- **方法**: GET
- **参数**: 无

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "paymentMethods": [
      {
        "id": "paypal",
        "name": "PayPal",
        "logo": "https://api.cokfood.com/images/payment/paypal.png",
        "description": "Pay with your PayPal account"
      },
      {
        "id": "credit-card",
        "name": "Credit Card",
        "logo": "https://api.cokfood.com/images/payment/credit-card.png",
        "description": "Pay with Visa, MasterCard",
        "supportedCards": ["visa", "mastercard", "amex"]
      }
    ]
  }
}
```

### 15.2 获取系统设置

**请求**:

- **路径**: `/public/settings`
- **方法**: GET
- **参数**: 无

**响应**:

```json
{
  "code": 200,
  "message": "Success",
  "data": {
    "appName": "COKFOOD",
    "logo": "https://api.cokfood.com/images/logo.png",
    "contactEmail": "support@cokfood.com",
    "currency": "USD",
    "currencySymbol": "$",
    "socialLinks": {
      "facebook": "https://facebook.com/cokfood",
      "instagram": "https://instagram.com/cokfood",
      "twitter": "https://twitter.com/cokfood"
    },
    "appStoreLink": "https://apps.apple.com/app/cokfood",
    "playStoreLink": "https://play.google.com/store/apps/details?id=com.cokfood"
  }
}
```

## 16. 错误代码参考

| 代码 | 说明           | HTTP状态码                |
| ---- | -------------- | ------------------------- |
| 200  | 成功           | 200 OK                    |
| 400  | 请求参数错误   | 400 Bad Request           |
| 401  | 未授权访问     | 401 Unauthorized          |
| 403  | 权限不足       | 403 Forbidden             |
| 404  | 资源不存在     | 404 Not Found             |
| 409  | 资源冲突       | 409 Conflict              |
| 422  | 无法处理的实体 | 422 Unprocessable Entity  |
| 429  | 请求过多       | 429 Too Many Requests     |
| 500  | 服务器内部错误 | 500 Internal Server Error |
| 503  | 服务不可用     | 503 Service Unavailable   |

### 错误响应示例

```json
{
  "code": 400,
  "message": "Invalid request parameters",
  "errors": [
    {
      "field": "email",
      "message": "Invalid email format"
    },
    {
      "field": "password",
      "message": "Password must be at least 8 characters"
    }
  ]
}
```

## 17. API请求限制

为保护服务器资源并防止滥用，API实施了请求速率限制：

- 公共API: 60次请求/分钟/IP
- 认证用户: 300次请求/分钟/用户
- 管理员: 600次请求/分钟/用户

超过限制将返回429状态码，并在响应头中包含以下信息：

- `X-RateLimit-Limit`: 允许的请求数量
- `X-RateLimit-Remaining`: 当前周期内剩余的请求数量
- `X-RateLimit-Reset`: 当前周期结束的时间戳

# COKFOOD 数据库设计规范

## 1. 数据库概述

COKFOOD平台使用关系型数据库(MySQL/PostgreSQL)作为主要数据存储解决方案，并结合Redis缓存以提高性能。本文档详细描述了数据库设计规范，包括表结构、字段定义、索引策略、关系模型和数据完整性约束。

## 2. 数据库基本配置

### 2.1 数据库环境

- **生产环境**: MySQL 8.0 / PostgreSQL 13+
- **开发环境**: 同上，或Docker容器化部署
- **字符集**: UTF-8
- **排序规则**: utf8mb4_unicode_ci (MySQL) / UTF8 (PostgreSQL)

### 2.2 连接配置

- **最大连接数**: 500
- **连接超时**: 60秒
- **空闲超时**: 600秒
- **连接池最小连接**: 10
- **连接池最大连接**: 100

### 2.3 性能配置

- **查询缓存**: 启用，大小256MB
- **索引缓冲区**: 256MB
- **事务隔离级别**: REPEATABLE READ
- **自动提交**: 启用

## 3. 表命名规范

- 表名使用小写单数形式
- 多个单词使用下划线连接
- 使用有意义的名称，避免缩写
- 表名前缀表示功能模块，如`user_`、`order_`、`product_`等

示例:

```
user_account
restaurant_info
product_detail
payment_transaction
```

## 4. 字段命名规范

- 字段名使用小写
- 多个单词使用下划线连接
- 主键统一命名为`id`
- 外键命名为`表名_id`
- 创建和更新时间字段统一命名为`created_at`和`updated_at`
- 状态字段命名为`status`
- 布尔类型字段使用`is_`前缀，如`is_active`

## 5. 数据表结构

### 5.1 用户相关表

#### 5.1.1 user_account

用户账户基本信息表

```sql
CREATE TABLE user_account (
    id VARCHAR(36) PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('user', 'distributor', 'agent', 'admin') NOT NULL DEFAULT 'user',
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    avatar VARCHAR(255),
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    last_login_at TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_email (email),
    INDEX idx_user_role (role),
    INDEX idx_user_created_at (created_at)
);
```

#### 5.1.2 user_profile

用户详细信息表

```sql
CREATE TABLE user_profile (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    address_line1 VARCHAR(255),
    address_line2 VARCHAR(255),
    city VARCHAR(100),
    state VARCHAR(100),
    zip_code VARCHAR(20),
    country VARCHAR(100),
    bio TEXT,
    date_of_birth DATE,
    preferences JSON,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user_account(id) ON DELETE CASCADE,
    INDEX idx_profile_user_id (user_id)
);
```

#### 5.1.3 distributor

分销商信息表

```sql
CREATE TABLE distributor (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    agent_id VARCHAR(36) NOT NULL,
    commission_rate DECIMAL(5,2) NOT NULL DEFAULT 10.00,
    usdt_address VARCHAR(255),
    total_sales DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    total_commission DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    available_commission DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    pending_commission DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    withdrawn_commission DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user_account(id) ON DELETE CASCADE,
    FOREIGN KEY (agent_id) REFERENCES agent(id) ON DELETE RESTRICT,
    INDEX idx_distributor_user_id (user_id),
    INDEX idx_distributor_agent_id (agent_id)
);
```

#### 5.1.4 agent

区域代理信息表

```sql
CREATE TABLE agent (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    region VARCHAR(100) NOT NULL,
    commission_rate DECIMAL(5,2) NOT NULL DEFAULT 15.00,
    usdt_address VARCHAR(255),
    total_sales DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    total_commission DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    available_commission DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    pending_commission DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    withdrawn_commission DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user_account(id) ON DELETE CASCADE,
    INDEX idx_agent_user_id (user_id),
    INDEX idx_agent_region (region)
);
```

### 5.2 餐厅与商品表

#### 5.2.1 restaurant

餐厅信息表

```sql
CREATE TABLE restaurant (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    logo VARCHAR(255),
    cover_image VARCHAR(255),
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255),
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    zip_code VARCHAR(20) NOT NULL,
    country VARCHAR(100) NOT NULL DEFAULT 'USA',
    phone VARCHAR(20),
    email VARCHAR(255),
    website VARCHAR(255),
    delivery_fee VARCHAR(100) DEFAULT 'Free Delivery',
    delivery_time VARCHAR(100) DEFAULT '30-45 min',
    rating DECIMAL(3,1) DEFAULT 0.0,
    review_count INT DEFAULT 0,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_restaurant_name (name),
    INDEX idx_restaurant_city_state (city, state),
    INDEX idx_restaurant_rating (rating)
);
```

#### 5.2.2 restaurant_tag

餐厅标签表

```sql
CREATE TABLE restaurant_tag (
    id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT NOT NULL,
    tag VARCHAR(50) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurant(id) ON DELETE CASCADE,
    UNIQUE KEY uk_restaurant_tag (restaurant_id, tag),
    INDEX idx_tag (tag)
);
```

#### 5.2.3 product_category

产品分类表

```sql
CREATE TABLE product_category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    icon VARCHAR(255),
    parent_id INT,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    display_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES product_category(id) ON DELETE SET NULL,
    INDEX idx_category_parent (parent_id),
    INDEX idx_category_order (display_order)
);
```

#### 5.2.4 product

产品信息表

```sql
CREATE TABLE product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    long_description TEXT,
    price DECIMAL(10,2) NOT NULL,
    min_discount_price DECIMAL(10,2),
    restaurant_id INT NOT NULL,
    category_id INT NOT NULL,
    subcategory_id INT,
    rating DECIMAL(3,1) DEFAULT 0.0,
    review_count INT DEFAULT 0,
    special_commission_rate DECIMAL(5,2),
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurant(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES product_category(id) ON DELETE RESTRICT,
    FOREIGN KEY (subcategory_id) REFERENCES product_category(id) ON DELETE SET NULL,
    INDEX idx_product_restaurant (restaurant_id),
    INDEX idx_product_category (category_id),
    INDEX idx_product_subcategory (subcategory_id),
    INDEX idx_product_price (price),
    INDEX idx_product_rating (rating)
);
```

#### 5.2.5 product_image

产品图片表

```sql
CREATE TABLE product_image (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    display_order INT NOT NULL DEFAULT 0,
    is_primary BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE,
    INDEX idx_product_image_product (product_id),
    INDEX idx_product_image_primary (is_primary)
);
```

#### 5.2.6 product_nutrition

产品营养信息表

```sql
CREATE TABLE product_nutrition (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    calories INT,
    protein DECIMAL(5,1),
    carbs DECIMAL(5,1),
    fat DECIMAL(5,1),
    sodium DECIMAL(5,1),
    fiber DECIMAL(5,1),
    sugar DECIMAL(5,1),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE,
    UNIQUE KEY uk_product_nutrition (product_id)
);
```

#### 5.2.7 product_allergen

产品过敏原信息表

```sql
CREATE TABLE product_allergen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    allergen VARCHAR(50) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE,
    UNIQUE KEY uk_product_allergen (product_id, allergen),
    INDEX idx_allergen (allergen)
);
```

#### 5.2.8 product_ingredient

产品配料表

```sql
CREATE TABLE product_ingredient (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    ingredient VARCHAR(100) NOT NULL,
    display_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE,
    INDEX idx_ingredient_product (product_id)
);
```

#### 5.2.9 product_spice_level

产品辣度级别表

```sql
CREATE TABLE product_spice_level (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    value INT NOT NULL,
    display_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE,
    INDEX idx_spice_product (product_id)
);
```

### 5.3 评价相关表

#### 5.3.1 product_review

产品评价表

```sql
CREATE TABLE product_review (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id VARCHAR(36),
    name VARCHAR(100) NOT NULL,
    avatar VARCHAR(255),
    rating DECIMAL(3,1) NOT NULL,
    comment TEXT,
    is_verified BOOLEAN NOT NULL DEFAULT FALSE,
    is_featured BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user_account(id) ON DELETE SET NULL,
    INDEX idx_review_product (product_id),
    INDEX idx_review_user (user_id),
    INDEX idx_review_rating (rating),
    INDEX idx_review_created (created_at)
);
```

#### 5.3.2 restaurant_review

餐厅评价表

```sql
CREATE TABLE restaurant_review (
    id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT NOT NULL,
    user_id VARCHAR(36),
    name VARCHAR(100) NOT NULL,
    avatar VARCHAR(255),
    rating DECIMAL(3,1) NOT NULL,
    comment TEXT,
    is_verified BOOLEAN NOT NULL DEFAULT FALSE,
    is_featured BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurant(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user_account(id) ON DELETE SET NULL,
    INDEX idx_review_restaurant (restaurant_id),
    INDEX idx_review_user (user_id),
    INDEX idx_review_rating (rating),
    INDEX idx_review_created (created_at)
);
```

### 5.4 订单相关表

#### 5.4.1 customer_address

客户地址表

```sql
CREATE TABLE customer_address (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(36),
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255),
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    zip_code VARCHAR(20) NOT NULL,
    country VARCHAR(100) NOT NULL DEFAULT 'USA',
    instructions TEXT,
    is_default BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user_account(id) ON DELETE CASCADE,
    INDEX idx_address_user (user_id)
);
```

#### 5.4.2 order

订单主表

```sql
CREATE TABLE `order` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    user_id VARCHAR(36),
    restaurant_id INT NOT NULL,
    status ENUM('placed', 'preparing', 'ready', 'delivered', 'cancelled') NOT NULL DEFAULT 'placed',
    subtotal DECIMAL(10,2) NOT NULL,
    delivery_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    tax DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    service_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    discount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
    payment_transaction_id VARCHAR(100),
    notes TEXT,
    address_id INT,
    address_name VARCHAR(100) NOT NULL,
    address_phone VARCHAR(20) NOT NULL,
    address_email VARCHAR(255) NOT NULL,
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255),
    address_city VARCHAR(100) NOT NULL,
    address_state VARCHAR(100) NOT NULL,
    address_zip VARCHAR(20) NOT NULL,
    address_country VARCHAR(100) NOT NULL DEFAULT 'USA',
    address_instructions TEXT,
    placed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    prepared_at TIMESTAMP,
    ready_at TIMESTAMP,
    delivered_at TIMESTAMP,
    cancelled_at TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user_account(id) ON DELETE SET NULL,
    FOREIGN KEY (restaurant_id) REFERENCES restaurant(id) ON DELETE RESTRICT,
    FOREIGN KEY (address_id) REFERENCES customer_address(id) ON DELETE SET NULL,
    INDEX idx_order_user (user_id),
    INDEX idx_order_restaurant (restaurant_id),
    INDEX idx_order_status (status),
    INDEX idx_order_payment_status (payment_status),
    INDEX idx_order_created (created_at)
);
```

#### 5.4.3 order_item

订单项表

```sql
CREATE TABLE order_item (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    product_image VARCHAR(255),
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    notes TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES `order`(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE RESTRICT,
    INDEX idx_order_item_order (order_id),
    INDEX idx_order_item_product (product_id)
);
```

#### 5.4.4 order_item_option

订单项选项表（如辣度、配料等）

```sql
CREATE TABLE order_item_option (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_item_id INT NOT NULL,
    option_name VARCHAR(100) NOT NULL,
    option_value VARCHAR(100) NOT NULL,
    price_adjustment DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_item_id) REFERENCES order_item(id) ON DELETE CASCADE,
    INDEX idx_option_order_item (order_item_id)
);
```

### 5.5 支付相关表

#### 5.5.1 payment

支付记录表

```sql
CREATE TABLE payment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    payment_link_id VARCHAR(50),
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    transaction_id VARCHAR(255),
    status ENUM('pending', 'processing', 'completed', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
    error_message TEXT,
    payer_email VARCHAR(255),
    payer_id VARCHAR(255),
    payer_ip VARCHAR(45),
    payment_data JSON,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES `order`(id) ON DELETE SET NULL,
    FOREIGN KEY (payment_link_id) REFERENCES payment_link(id) ON DELETE SET NULL,
    INDEX idx_payment_order (order_id),
    INDEX idx_payment_link (payment_link_id),
    INDEX idx_payment_status (status),
    INDEX idx_payment_created (created_at)
);
```

### 5.6 分销商代付功能表

#### 5.6.1 payment_link

代付链接表

```sql
CREATE TABLE payment_link (
    id VARCHAR(50) PRIMARY KEY,
    distributor_id VARCHAR(36) NOT NULL,
    product_id INT NOT NULL,
    original_price DECIMAL(10,2) NOT NULL,
    discount_price DECIMAL(10,2) NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    commission_rate DECIMAL(5,2) NOT NULL,
    commission_amount DECIMAL(10,2) NOT NULL,
    status ENUM('active', 'inactive', 'expired') NOT NULL DEFAULT 'active',
    usage_count INT NOT NULL DEFAULT 0,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (distributor_id) REFERENCES distributor(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE,
    INDEX idx_link_distributor (distributor_id),
    INDEX idx_link_product (product_id),
    INDEX idx_link_status (status),
    INDEX idx_link_created (created_at)
);
```

#### 5.6.2 payment_link_transaction

代付链接交易表

```sql
CREATE TABLE payment_link_transaction (
    id VARCHAR(50) PRIMARY KEY,
    payment_link_id VARCHAR(50) NOT NULL,
    payment_id INT NOT NULL,
    distributor_id VARCHAR(36) NOT NULL,
    agent_id VARCHAR(36) NOT NULL,
    product_id INT NOT NULL,
    original_price DECIMAL(10,2) NOT NULL,
    discount_price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    total_amount DECIMAL(10,2) NOT NULL,
    distributor_commission_rate DECIMAL(5,2) NOT NULL,
    distributor_commission_amount DECIMAL(10,2) NOT NULL,
    agent_commission_rate DECIMAL(5,2) NOT NULL,
    agent_commission_amount DECIMAL(10,2) NOT NULL,
    platform_commission_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'completed', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (payment_link_id) REFERENCES payment_link(id) ON DELETE CASCADE,
    FOREIGN KEY (payment_id) REFERENCES payment(id) ON DELETE CASCADE,
    FOREIGN KEY (distributor_id) REFERENCES distributor(id) ON DELETE CASCADE,
    FOREIGN KEY (agent_id) REFERENCES agent(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE,
    INDEX idx_transaction_link (payment_link_id),
    INDEX idx_transaction_payment (payment_id),
    INDEX idx_transaction_distributor (distributor_id),
    INDEX idx_transaction_agent (agent_id),
    INDEX idx_transaction_status (status),
    INDEX idx_transaction_created (created_at)
);
```

### 5.7 佣金相关表

#### 5.7.1 commission

佣金记录表

```sql
CREATE TABLE commission (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id VARCHAR(50) NOT NULL,
    user_id VARCHAR(36) NOT NULL,
    user_type ENUM('distributor', 'agent') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    rate DECIMAL(5,2) NOT NULL,
    status ENUM('pending', 'settled', 'cancelled') NOT NULL DEFAULT 'pending',
    settle_batch_id INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    settled_at TIMESTAMP,
    FOREIGN KEY (transaction_id) REFERENCES payment_link_transaction(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user_account(id) ON DELETE CASCADE,
    INDEX idx_commission_transaction (transaction_id),
    INDEX idx_commission_user (user_id, user_type),
    INDEX idx_commission_status (status),
    INDEX idx_commission_batch (settle_batch_id),
    INDEX idx_commission_created (created_at)
);
```

#### 5.7.2 commission_settlement_batch

佣金结算批次表

```sql
CREATE TABLE commission_settlement_batch (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_number VARCHAR(50) NOT NULL UNIQUE,
    total_amount DECIMAL(15,2) NOT NULL,
    commission_count INT NOT NULL,
    status ENUM('processing', 'completed', 'failed') NOT NULL DEFAULT 'processing',
    processed_by VARCHAR(36),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    completed_at TIMESTAMP,
    FOREIGN KEY (processed_by) REFERENCES user_account(id) ON DELETE SET NULL,
    INDEX idx_batch_status (status),
    INDEX idx_batch_created (created_at)
);
```

#### 5.7.3 withdrawal

提现申请表

```sql
CREATE TABLE withdrawal (
    id VARCHAR(50) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    user_type ENUM('distributor', 'agent') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    usdt_address VARCHAR(255) NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'completed') NOT NULL DEFAULT 'pending',
    approved_by VARCHAR(36),
    approved_at TIMESTAMP,
    processed_by VARCHAR(36),
    processed_at TIMESTAMP,
    transaction_hash VARCHAR(255),
    remarks TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user_account(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES user_account(id) ON DELETE SET NULL,
    FOREIGN KEY (processed_by) REFERENCES user_account(id) ON DELETE SET NULL,
    INDEX idx_withdrawal_user (user_id, user_type),
    INDEX idx_withdrawal_status (status),
    INDEX idx_withdrawal_created (created_at)
);
```

### 5.8 系统配置表

#### 5.8.1 system_setting

系统设置表

```sql
CREATE TABLE system_setting (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    setting_description TEXT,
    is_public BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_setting_key (setting_key),
    INDEX idx_setting_public (is_public)
);
```

#### 5.8.2 commission_setting

佣金设置表

```sql
CREATE TABLE commission_setting (
    id INT AUTO_INCREMENT PRIMARY KEY,
    default_agent_rate DECIMAL(5,2) NOT NULL DEFAULT 15.00,
    default_distributor_rate DECIMAL(5,2) NOT NULL DEFAULT 10.00,
    min_distributor_rate DECIMAL(5,2) NOT NULL DEFAULT 5.00,
    max_distributor_rate DECIMAL(5,2) NOT NULL DEFAULT 20.00,
    settlement_period_days INT NOT NULL DEFAULT 7,
    min_withdrawal_amount DECIMAL(10,2) NOT NULL DEFAULT 50.00,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 5.9 其他辅助表

#### 5.9.1 search_history

搜索历史表

```sql
CREATE TABLE search_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    query VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user_account(id) ON DELETE CASCADE,
    INDEX idx_search_user (user_id),
    INDEX idx_search_created (created_at)
);
```

#### 5.9.2 banner

首页轮播图表

```sql
CREATE TABLE banner (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255),
    image VARCHAR(255) NOT NULL,
    link VARCHAR(255) NOT NULL,
    display_order INT NOT NULL DEFAULT 0,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    start_date TIMESTAMP,
    end_date TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_banner_active (is_active),
    INDEX idx_banner_order (display_order)
);
```

#### 5.9.3 app_version

应用版本表

```sql
CREATE TABLE app_version (
    id INT AUTO_INCREMENT PRIMARY KEY,
    platform ENUM('ios', 'android') NOT NULL,
    version VARCHAR(20) NOT NULL,
    build_number INT NOT NULL,
    is_force_update BOOLEAN NOT NULL DEFAULT FALSE,
    update_message TEXT,
    release_notes TEXT,
    download_url VARCHAR(255),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_platform_version (platform, version),
    INDEX idx_version_platform (platform)
);
```

## 6. 数据库索引策略

### 6.1 索引类型选择

- **主键索引**: 每个表都有明确的主键，通常使用自增整数或UUID
- **唯一索引**: 用于确保字段值唯一性，如用户邮箱、订单号
- **普通索引**: 用于优化查询性能的常规索引
- **复合索引**: 针对多字段联合查询的索引
- **全文索引**: 用于搜索功能，如商品描述、餐厅名称等

### 6.2 索引命名规范

- 主键索引: 默认命名（PRIMARY）
- 唯一索引: `uk_表名_字段名`
- 普通索引: `idx_表名_字段名`
- 复合索引: `idx_表名_字段1_字段2`
- 全文索引: `ft_表名_字段名`

```sql

```
