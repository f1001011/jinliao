<?php


namespace app\common\traites;


trait TraitModel
{
    /**
     * 直接删除
     * @param $id /主键
     * @return bool
     */
    public function del($id)
    {
        $find = $this->find($id);
        if (empty($find)) return false;
        return $find->delete();
    }
    public static function incUpdate($id,$money,$field)
    {
        return self::where('id',$id)->inc($field,$money)->update();
    }
    /**
     * 添加数据
     * @param $data /数据数组
     * @param bool $type /true 单条添加,false多条添加
     */
    public function add(array $data,bool $type=true)
    {
        //单条添加
        if ($type){
           return $this->insert($data);
        }
        //多条添加
        return $this->insertAll($data);
    }

    //软删除
    public function deletes($id)
    {
        return '';
    }

    public function setStatus($post)
    {
        $id = intval($post['id']);
        $status = intval($post['status']);
        //$status = $post['status'] == 1 ? 0 : 1;

        if ($id < 1) return false;
        $find = $this->find($id);
        return $find->save(['status' => $status]);
    }

    public function getThumbUrlAttr($value)
    {
        if (empty($value)) return '';
        if (is_array($value)) return '';
        $value = explode(',', $value);
        if (count($value) > 1) {
            foreach ($value as $key => $v) {
                $value[$key] = config('ToConfig.app_update.image_url') . $v;
            }
            return $value;
        }
        return config('ToConfig.app_update.image_url') . $value[0];
    }

    public function getVideoUrlAttr($value)
    {
        if (!empty($value)) return config('ToConfig.app_update.image_url') . $value;
        return '';
    }
    public function getImgPathAttr($value)
    {
        if (!empty($value)) return config('ToConfig.app_update.image_url') . $value;
        return '';
    }


    //代理商查看代理商推广的用户充值等 不排除自己
    public static function whereMap()
    {
        $map = [];
        //代理商 推广列表
       // if (session('admin_user.agent')) $map = ['agent_id_1|agent_id_2|agent_id_3|b.id' => session('admin_user.id')];
          //if (session('admin_user.role')==2)  $map = ['b.market_uid' => session('admin_user.id')];
        return $map;
    }
    //代理商查看用户代理。排除自己
    public static function whereMapUser()
    {
        $map = [];
        //代理商 推广列表
       // if (session('admin_user.agent')) $map = ['agent_id_1|agent_id_2|agent_id_3' => session('admin_user.id')];
           if (session('admin_user.role')==2)  $map = ['b.market_uid' => session('admin_user.id')];
        return $map;
    }

    public function mapSel()
    {
        $page = $this->request->param('page/d', 1);
        $limit = $this->request->param('limit/d', 20);

        if ($page <= 0 || $limit <= 0) {
            show(0, [], '分页条件错误');
        }
        return [$page, $limit];
    }

    public static function page_list($where,$limit,$page,$order='id asc')
    {
        // $map=self::whereMap();
        return self::where($where)
            //->where($map)
                   ->order($order)
                   ->paginate(['list_rows'=>$limit,'page'=>$page], false);
    }

    public static function page_one($map = []){
        return self::where($map)->find();
    }
}