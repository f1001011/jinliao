import request from './request'

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export const getProductClass = (data: any) => {
  return request({
    url: `/api/getProductClass`,
    method: 'GET',
	  params: data,
  })
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export const getStoresList = (data: any) => {
  return request({
    url: `/api/getStoresList`,
    method: 'GET',
    params: data,
  })
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export const getRestRant = () => {
  return request({
    url: `api/getSpecialtyRestaurant`,
    method: 'GET',
  })
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export const getHotList = () => {
  return request({
    url: `/api/getProductHot`,
    method: 'GET',
  })
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export const getBannerList = (data: any) => {
  return request({
    url: `/api/getBanner`,
    method: 'GET',
	params: data,
  })
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export const getShopGoodsList = (data: any) => {
  return request({
    url: `/api/getShopGoodsList`,
    method: 'GET',
	params: data,
  })
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export const getGoodsDetails = (data: any) => {
  return request({
    url: `/api/getGoodsDetails`,
    method: 'GET',
	params: data,
  })
}
// eslint-disable-next-line @typescript-eslint/no-explicit-any
export const login = (data: any) => {
  return request({
    url: `/api/login`,
    method: 'POST',
    data,
  })
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export const register = (data: any) => {
  return request({
    url: `/api/register`,
    method: 'POST',
    data,
  })
}
