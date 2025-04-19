<?php


namespace app\admin\controller\log;


use app\admin\controller\Base;


use app\common\model\PayRecharge as models;
use app\common\model\GoodsOrder;
use \app\common\model\MoneyLog;
use app\common\model\UserModel;
use app\common\service\Excel;
use app\common\traites\PublicCrudTrait;
use think\exception\ValidateException;
use think\facade\Db;

class PayRecharge extends Base
{
    protected $model;
    use PublicCrudTrait;

    /**
     * 充值控制器
     */
    public function initialize()
    {
        $this->model = new models();
        $this->GoodsOrder = new GoodsOrder();
        $this->UserModel = new UserModel();


        parent::initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 菜单栏目树
     */
    public function index()
    {
        //当前页
        $page = $this->request->post('page', 1);
        //每页显示数量
        $limit = $this->request->post('limit', 10);
        //查询搜索条件
        $post = $this->request->post();
        $map= $date = [];

        !empty($post['type'])  && $map[] = ['a.type', '=', $post['type']];
        !empty($post['user_name'])   && $map[] = ['b.user_name|b.phone', 'like', '%' . $post['user_name'] . '%'];
        !empty($post['order_on']) && $map[] = ['a.order_no', '=', $post['order_on']];
        !empty($post['uid']) &&  $map[] = ['a.uid', '=', $post['uid']];
        $tMap = $map;
        if (isset($post['status']) && $post['status'] !=''){
            $map[] = ['a.status', '=', $post['status']];
            $tMap[] = ['a.status', '=', $post['status']];
        }else{
            $tMap[] = ['a.status', '=', 2];
        }

        if (isset($post['start']) && isset($post['end'])) {
            $date['start'] = $post['start'];
            $date['end'] = $post['end'];
        }

        $list = $this->model->page_list($map, $limit, $page, $date);

        $sun_money = $this->model->getDateTimeTotal($tMap, $date);
        $list = $list->toArray();

        $list['total_sum'] = $sun_money;
        return $this->success($list);
    }

    /**
     *
     * 通过
     * */
    //提现通过
//    public function pass()
//    {
//        //过滤数据
//        $postField = 'id';
//        $post = $this->request->only(explode(',', $postField), 'post', null);
//        //检测订单
//        $find = $this->model->where('id', $post['id'])->where('status', 0)->find();
//        if (!$find){
//            return $this->failed('该提现订单已处理或不存在');
//        }
//        $find = $find->toArray();
//        CacheRepeatClick($post['id']);
//        $mUser = new UserModel();
//        //修改当前订单状态
//        $this->model->where('id', $post['id'])->update(['status'=>1]);
//        //修改用户 总提现
//        $mUser->add_total_withdraw($find['u_id'], $find['money']);
//        return $this->success([]);
//    }

    /**
     * 拒绝
     * */
    public function refuse()
    {
        //过滤数据
        $postField = 'id';
        $post = $this->request->only(explode(',', $postField), 'post', null);
        //检测订单
        $find = $this->model->where('id', (int)$post['id'])->where('status', 0)->find();
        if (!$find) return $this->failed('该充值订单已处理或不存在');
        $post['status'] = 3;
        $post['success_time'] = date('Y-m-d H:i:s');
        $post['admin_uid'] = session('admin_user.id');

        //执行修改数据
        $save = $this->model->update($post);
        if ($save) return $this->success([]);
        return $this->failed('失败');
    }


    private function excel($data)
    {
        $excel = new Excel();

        $field_title = [
            'create_time' => '充值时间',
            'success_time' => '到账时间(审核时间)',
            'money' => '充值金额',
            'uid' => '用户ID',
            'u_city' => '地区',
            'sys_bank_id' => '收款账号',
            'u_bank_name' => '打款银行名',
            'u_bank_user_name' => '打款用户名',
            'u_bank_card' => '打款银行卡号',
            'status' => '充值订单状态',
            'trilateral_order' => '三方订单号',

        ];








        $fields = [
            'create_time',
            'success_time',
            'money',
            'uid',
            'u_city',
            'sys_bank_id',
            'u_bank_name',
            'u_bank_user_name',
            'u_bank_card',
            'status',
            'trilateral_order',
        ];
        $filename = '充值导出' . date('YmdHis');
        return $excel->export($field_title, $fields, $data, $filename);
    }

}