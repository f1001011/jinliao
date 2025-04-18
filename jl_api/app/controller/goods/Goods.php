<?php

namespace app\controller\goods;

use app\common\Code;
use app\controller\Base;
use app\service\GoodService;

class Goods extends Base
{
    //获取商品分类列表
    public function GetGoodsTypeList()
    {
        $shopId = $this->request->param('shopId/d', 0);
        $list = GoodService::getInstance()->getGoodsTypeList($shopId);
        return show(Code::SUCCESS, $list);
    }

    //获取特色餐厅
    public function GetSpecialtyRestaurant()
    {
        $list = GoodService::getInstance()->getSpecialtyRestaurant();
        return show(Code::SUCCESS, $list);
    }

    //获取热门菜品
    public function GetHot()
    {
        $shopId = $this->request->param('shopId/d', 0);
        //$page = $this->request->param('page/d',1);
        $limit = $this->request->param('limit/d', 10);
        $list  = GoodService::getInstance()->getHot($shopId, $limit);
        return show(Code::SUCCESS, $list);
    }

    //获取商店列表
    //    public function GetStoresList(){
    //
    //    }
    //商店商品列表
    public function GetStoresList()
    {
        $typeId = $this->request->param('typeId/d', 0);
        $page   = $this->request->param('page/d', 1);
        $limit  = $this->request->param('limit/d', 10);
        $list   = GoodService::getInstance()->getStoreGoodsList($typeId, $page, $limit);
        return show(Code::SUCCESS, $list);
    }

    public function GetBanner()
    {
        $shopId = $this->request->param('shopId/d', 0);
        $list   = GoodService::getInstance()->getBanner($shopId);
        return show(Code::SUCCESS, $list);
    }

    #获取商品评论
    public function GetGoodsComment()
    {
        //首页获取评论
        $goodsId  = $this->request->param('goodsId/d', 0);
        $page     = $this->request->param('page/d', 1);
        $limit    = $this->request->param('limit/d', 10);
        $position = $this->request->param('position/d', 0);
        $list     = GoodService::getInstance()->getGoodsComment($goodsId, $position, $page, $limit);
        return show(Code::SUCCESS, $list);
    }

    //获取店铺商品列表
    public function GetShopGoodsList()
    {
        $shopId = $this->request->param('shopId/d', 0);
        $classId = $this->request->param('classId/d', 0);
        $page     = $this->request->param('page/d', 1);
        $limit    = $this->request->param('limit/d', 10);

        $list     = GoodService::getInstance()->getShopGoodsList($shopId, $classId, $page, $limit);
        return show(Code::SUCCESS, $list);
    }

    //获取店铺商品推荐列表
    public function GetShopGoodsHotList()
    {
        $shopId = $this->request->param('shopId/d', 0);
        $limit    = $this->request->param('limit/d', 10);
        $list     = GoodService::getInstance()->getShopGoodsHotList($shopId, $limit);
        return show(Code::SUCCESS, $list);
    }
    //获取商品详情
    public function GetGoodsDetails()
    {
        $goodsId = $this->request->param('goodsId/d', 0);
        $list     = GoodService::getInstance()->getGoodsDetails($goodsId);
        return show(Code::SUCCESS, $list);
    }
}