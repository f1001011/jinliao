<?php

use app\controller\goods\Goods;
use app\controller\Index;
use app\controller\login\Login;
use app\controller\user\User;
use app\controller\goods\ShopOrder;
use think\facade\Route;

const LOGIN_CLASS = Login::class;
const GOODS_CLASS = Goods::class;
const INDEX_CLASS = Index::class;

//不需要验证的接口
Route::group(
    'api', function () {
    Route::post('/login', LOGIN_CLASS . '/Login');
    Route::post('/register', LOGIN_CLASS . '/Register');
    Route::get('/setImageOrderNo/[:orderNo]/[:pwd]', LOGIN_CLASS . '/SetImageOrderNo');#后台请求接口生成二维码

    Route::get('/getProductClass/[:shopId]', GOODS_CLASS . '/GetGoodsTypeList');#获取商品分类列表
    Route::get('/getProductHot/[:shopId]', GOODS_CLASS . '/GetHot');#获取获取热门菜品
    Route::get('/getBanner/[:shopId]', GOODS_CLASS . '/GetBanner');#获取banner
    Route::get('/getSpecialtyRestaurant', GOODS_CLASS . '/GetSpecialtyRestaurant');#获取特色餐厅
    Route::get('/getGoodsComment/[:shopId]/[:page]/[:limit]/[:position]', GOODS_CLASS . '/GetGoodsComment');#获取商品评论
    Route::get('/getStoresList/[:typeId]/[:page]/[:limit]', GOODS_CLASS . '/GetStoresList');#获取商店列表
    Route::get('/getShopGoodsList/[:shopId]/[:classId]/[:page]/[:limit]', GOODS_CLASS . '/GetShopGoodsList');#获取商店中的商品列表
    Route::get('/getGoodsDetails/[:goodsId]', GOODS_CLASS . '/GetGoodsDetails');#获取商品详情
    Route::post('/setUpload', INDEX_CLASS . '/uploadImage');#上传图片接口
})->middleware(think\middleware\AllowCrossDomain::class);

const USER_CLASS       = User::class;
const SHOP_ORDER_CLASS = ShopOrder::class;
//需要验证的接口
Route::group(
    'api', function () {
    Route::get('/getUserAddress', USER_CLASS . '/GetUserAddress');//获取用户的 收货地址
    Route::post('/getUserAddressAdd', USER_CLASS . '/GetUserAddressAdd');//添加用户收货地址
    Route::post('/setUserPlaceOrder', SHOP_ORDER_CLASS . '/SetUserPlaceOrder');//用户下单页面
    Route::post('/getUserOrderData', SHOP_ORDER_CLASS . '/GetUserOrderData');//订单详情页面
    Route::post('/getUserOrderDataList', SHOP_ORDER_CLASS . '/GetUserOrderDataList');//订单里个表页面
});


Route::auto();