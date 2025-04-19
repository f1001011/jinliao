<?php


namespace app\common\model;


use app\common\traites\TraitModel;
use think\Model;

class AdminModel extends Model
{
    use TraitModel;
    public $name = 'common_admin';

    public function getPwdAttr($value)
    {
        return '';
    }
    public static function page_list($map,$limit, $page)
    {
        return self::alias('a')
            ->where($map)
            ->where('a.id','>',0)
            ->join('common_admin_role b', 'a.role = b.id', 'left')
//            ->join('common_market_level c', 'c.id = a.market_level', 'left')
//            ->join('ntp_common_market_relation d', 'a.id = d.aid', 'left')
            ->field('a.*,b.name')
            ->order('id desc')
            ->paginate(['list_rows' => $limit, 'page' => $page], false) ->each(function ($item, $key) {
                $item->tg_url_google='';
                //!empty($item->invitation_code) && $item->tg_url_google = captchaUrl($item->invitation_code);
            });
    }

}