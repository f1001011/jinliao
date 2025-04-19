<?php


namespace app\admin\controller\log;


use app\admin\controller\Base;
use app\common\model\MoneyLog as models;
use \app\common\model\UserModel;
use app\common\traites\TraitModel;

class MoneyLog extends Base
{
    protected $model;
    use TraitModel;

    /**
     * 资金流动控制器
     */
    public function initialize()
    {
        $this->model = new models();
        parent::initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 列表
     */
    public function index()
    {

        //当前页
        $page = $this->request->post('page', 1);
        //每页显示数量
        $limit = $this->request->post('limit', 10);
        //查询搜索条件
        $post = $this->request->post();
        $map = [];

        isset($post['pay_type']) && $post['pay_type'] != '' && $map[] = ['a.pay_type', '=', $post['pay_type']]; //6 充值钱包  5余额钱包
        isset($post['class']) && $post['class'] != '' && $map[] = ['a.class', '=', $post['class']];
        isset($post['uid']) && $post['uid'] != '' && $map[] = ['uid', '=', $post['uid']];
        !empty($post['user_name']) && $map[] = ['b.user_name|b.phone', 'like', '%' . $post['user_name'] . '%'];
        !empty($post['status']) && $map[] = ['a.status', '=', $post['status']];
        isset($post['type'])  && !empty($post['type']) && $map[] = ['a.type', '=', $post['type']];

        $order = 'a.id desc';
        $date = [];
        isset($post['create_time_sort']) && $order = 'a.id ' . $post['create_time_sort'];
        if (isset($post['start']) && !empty($post['start']) && isset($post['end'])&& !empty($post['end'])) {
            $date['start'] = $post['start'];
            $date['end'] = $post['end'];
        }

        $list = $this->model->page_list($map, $limit, $page,$order, $date);
        return $this->success($list);

    }


    //审核 数据库 金额 是否存在差异
//    public function examine()
//    {
//        $type = $this->request->param('type/d', 0);
//        $user_name = $this->request->param('user_name', '');
//        $uid = $this->request->param('uid/d', 0);
//        if (empty($user_name) && $uid <=0) {
//            return $this->failed('请输入用户名');
//        }
//        if (!empty($user_name)){
//            $find = UserModel::where('user_name', $user_name)->find();
//        }else{
//            $find = UserModel::where('id', $uid)->find();
//
//        }
//        if (empty($find)) {
//            return $this->failed('用户不存在');
//        }
//
//        //1 充值，2 提现，3 绿币，4 个人获得的返佣，5 可提现金额，6 团队佣金 ，7 用户股权，8 碳票，9 公益捐款
//
//
//        $list = [];
//        switch ($type) {
//            case 1:
//                $list = $this->pay_recharge($find);
//                break;
//            case 2:
//                $list = $this->pay_withdraw($find);
//                break;
//            case 3:
//                $list = $this->green($find);
//                break;
//            case 4:
//                $list = $this->hire($find);
//                break;
//            case 5:
//                $list = $this->approve($find);
//                break;
//            case 6:
//                $list = $this->team($find);
//                break;
//            case 7:
//                $list = $this->thigh($find);
//                break;
//            case 8:
//                $list = $this->vote($find);
//                break;
//            case 9:
//                $list = $this->welfare($find);
//                break;
//        }
//        return $this->success($list);
//    }

    private function pay_recharge($user){
        //详细类型 101充值   201 提现
        $status = [101];
        $list = $this->difference($user, $status);
        return $list;
    }


    private function pay_withdraw($user){
        //详细类型 101充值   201 提现
        $status = [201];
        $list = $this->difference($user, $status);
        return $list;
    }

    //余额
    private function balance($user)
    {
        $status = [103, 205,201,217,218];
        $list = $this->difference($user, $status);
        return $list;
    }



    //积分
    private function integral($user)
    {
        $status = [111, 102, 120,221,222];
        $list = $this->difference($user, $status);
        return $list;
    }

    //绿币
//    private function green($user)
//    {
//        $status = [121,223,224];
//        $list = $this->difference($user, $status);
//        return $list;
//    }
//
//    //佣金
//    private function hire($user)
//    {
//        $status = [104,225,226,227,228,125,202];
//        $list = $this->difference($user, $status);
//        return $list;
//    }
//
//    //可提现
//    private function approve($user)
//    {
//        $status = [203,216,116];
//        $list = $this->difference($user, $status);
//        return $list;
//    }
//
//    //团队佣金
//    private function team($user)
//    {
//
//        $status = [227,228,125,202];
//        $list = $this->difference($user, $status);
//        return $list;
//    }
//
//    //股权
//    private function thigh($user)
//    {
//        $status = [122];
//        $list = $this->difference($user, $status);
//        return $list;
//    }
//    //碳票
//    private function vote($user)
//    {
//        $status = [123,229,230];
//        $list = $this->difference($user, $status);
//        return $list;
//    }
//    //公益捐款
//    private function welfare($user)
//    {
//        $status = [203,204];
//        $list = $this->difference($user, $status);
//        return $list;
//    }
    private function difference($user, $status = [])
    {
        list($page, $limit) = $this->mapSel();
        //查询余额变动，余额可用于提现
        $list = $this->model->where('uid', $user['id'])
            ->where('status', 'in', $status)
            ->order('id desc')
            ->paginate(['list_rows' => $limit, 'page' => $page], false)->toArray();
//           ->each(function($item, $key){
//            return $item;
//        });
        //查询上一条数据的结束金额，是不是下一句的开始金额

        if (!isset($list['data'])) {
            return $list;
        }
        if (empty($list['data'])) {
            return $list;
        }
        $arr = $list['data'];
        $array = [];
        foreach ($arr as $key => $value) {
            $value['user_name'] = $user['user_name'];
            // 判断当前数据和下一条数据是否存在
            if (isset($arr[$key + 1])) {
                // 判断相邻两条数据某个字段是否一样
                if ($value['money_before'] == $arr[$key + 1]['money_end']) {
                    // 处理一样的情况
                    $value['difference'] = 0;
                } else {
                    // 处理不一样的情况
                    $value['difference'] = $value['money_before'] - $arr[$key + 1]['money_end'];
                }
            } else {
                $value['difference'] = 0;
            }
            $array[$key] = $value;
        }
        $list['data'] = $array;
        return $list;
    }

}