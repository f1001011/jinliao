<?php

namespace app\service;

use app\model\AdsModel;
use app\model\GoodsCommentModel;
use app\model\GoodsLabelModel;
use app\model\GoodsModel;
use app\model\GoodsTypeModel;
use app\model\UserAllocationModel;
use app\model\UserConfigModel;

class GoodService extends BaseService
{
    //获取商品分类列表
    public function getGoodsTypeList($shopId=0)
    {
        $map[] = ['pid','=',0];
        $map[] = ['status','=',GoodsTypeModel::STATUS_SHOW];
        if ($shopId > 0){
            $shopClass =  UserConfigModel::pageOneValue(['user_id' => $shopId],'shop_class');
            if (!empty($shopClass)) {
                $shopClassIds = explode('|', $shopClass);
                $map[] = ['id','in',$shopClassIds];
            }
        }
        //获取一级分类
        return GoodsTypeModel::with('children')
            ->where($map)
            ->order('sort', 'desc')
            ->cache(60 * 10)
            ->field('id,type_name,image,pid')
            ->select();
    }

    //获取热门商品
    public function getHot($shopId, $limit)
    {
        $map[] = ['status', '=', GoodsModel::STATUS_SHOW];
        $map[] = ['is_hot', '=', GoodsModel::IS_HOT];
        //获取店铺配置的商品
        if ($shopId > 0) {
            $allocationIds = UserAllocationModel::pageOneColumn(['shop_id' => $shopId]);
            $map[] = ['id', 'in', $allocationIds];
        }

        return GoodsModel::with(['prices', 'goods_type'])
            ->where($map)
            ->order('sort', 'desc')
            ->cache(60 * 10)
            ->limit($limit)
            ->field('id,goods_type_id,goods_name,goods_money,head_img,bottom_img,bottom_text')
            ->select();
    }

    //获取banner图
    public function getBanner($shopId = 0)
    {
        //shopId =0 查询首页 banner，其他时候查询指定 分销商的id
        if ($shopId == 0) {
            return AdsModel::pageList(['status' => 1, 'position' => 0], 'id,img', ['sort' => 'desc'], 600);
        }
        //其他时候，查询经销商的
        $userBanner = UserConfigModel::pageOneValue(['user_id' => $shopId], 'user_banner', 180);
        $userBannerIds = [];
        if (!empty($userBanner)) {
            $userBannerIds = array_filter(explode('|', $userBanner));
        }

        if (empty($userBannerIds)) {
            return [];
        }
        return AdsModel::pageList([['status', '=', 1], ['id', 'in', $userBannerIds]], 'id,img', ['sort' => 'desc'], 180);
    }

    //特色餐厅
    public function getSpecialtyRestaurant()
    {
        //获取是特色餐厅 的分销商
        $list = UserConfigModel::pageList(['characteristic' => 1], '*', ['sort' => 'desc'], 180)->toArray();
        $data = [];
        if (empty($list)) {
            return $data;
        }
        foreach ($list as $key => $value) {
            //获取分销商的 封面图
            $data[$key] = $value;
            if (!empty($value['user_cover'])) {
                $userBannerIds = explode('|', $value['user_cover']);
                $data[$key]['cover_list'] = AdsModel::pageList([['status', '=', 1], ['id', 'in', array_filter($userBannerIds)]], 'id,img', ['sort' => 'desc'], 600);
            }
            //$userBannerIds             = UserAllocationModel::pageOneColumn(['shop_id' => $value['user_id'], 'type_class' => 3], 'class_id', 600);
            //获取分销商的 标签
            if (!empty($value['user_label'])) {
                $userLabelIds = explode('|', $value['user_banner']);
                $data[$key]['goods_label'] = GoodsLabelModel::pageList([['id', 'in', array_filter($userLabelIds)]], '*', ['sort' => 'desc'], 600);
            }
            //$userLabelIds              = UserAllocationModel::pageOneColumn(['shop_id' => $value['user_id'], 'type_class' => 4], 'class_id', 600);
            //$data[$key]['goods_label'] = GoodsLabelModel::pageList([['id', 'in', $userLabelIds]], '*', ['sort' => 'desc'], 600);
        }
        return $data;
    }

    //获取商品评论
    public function getGoodsComment($goodsId, $position, $page, $limit)
    {
        $map = [];
        // 1 如果 $position =0 的时候。默认给 首页的评论
        $map[] = ['position', '=', $position];//$position =0    //展示首页的
        if ($goodsId > 0) {
            $map[] = ['gid', '=', $goodsId];//展示商品的
        }
        return GoodsCommentModel::pageLimitList($map, $page, $limit, ['id' => 'desc'], 60);
    }

    //查询用户的 标签，分面图等
    public function getStoreGoodsList(mixed $typeId, mixed $page, mixed $limit)
    {
        $map = [];
        if ($typeId > 0) {
            $map[] = ['shop_class', 'like', '%|' . $typeId . '|%'];//展示商品的
        }
        //查询满足条件的用户
        return UserConfigModel::where($map)
            ->cache(10)
            ->paginate(
                [
                    'list_rows' => $limit,
                    'page' => $page,
                ])
            ->each(
                function ($item, $key) {
                    //1查询用户店铺商品封面图
                    if (!empty($item->user_cover)) {
                        $userCoverIds = explode('|', $item->user_cover);
                        $item->user_cover_list = AdsModel::pageList([['status', '=', 1], ['id', 'in', array_filter($userCoverIds)]], 'id,img', ['sort' => 'desc'], 600);
                    }
                    //2查询用户商品标签
                    if (!empty($item->user_label)) {
                        $userLabelIds = explode('|', $item->user_label);
                        $item->user_label_list = GoodsLabelModel::pageList([['id', 'in', array_filter($userLabelIds)]], 'id,title', ['sort' => 'desc'], 600);
                    }
                });
    }

    //获取商店的 商品列表
    public function getShopGoodsList($shopId, $classId, $page, $limit)
    {
        $map = [];
        //$map[] = ['shop_id', '=', $shopId];
        if ($classId > 0) {
            $map[] = ['class_id', '=', $classId];
        }
        //1 查询商店的商品数据
        $pageOneColumn = UserAllocationModel::pageOneColumn(['type_class' => 2, 'shop_id' => $shopId]);

        if (empty($pageOneColumn)) {
            return [];
        }

        //2 查询商品列表
        $list = GoodsModel::pageLimitList($map, $page, $limit, ['id' => 'desc'], 60);
        return $list;
    }

    //获取支付方式
    public function getPayList()
    {

    }

    public function getGoodsDetails(mixed $goodsId)
    {
       $data = GoodsModel::where(['id'=>$goodsId])->with(['goodsType','prices','goodsLabel'])->find();
       return $data;
    }
}