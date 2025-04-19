<?php

use app\agent\controller\auth\Statistics;
use app\agent\controller\auth\User;
use app\agent\controller\Login;
use think\facade\Route;

const  LOGIN_CON = \app\admin\controller\Login::class;

Route::rule('login/index$', LOGIN_CON.'/index');//登陆
Route::rule('login/captcha$', LOGIN_CON.'/captcha');//验证码
Route::rule('login/captcha_check$', LOGIN_CON.'/captcha_check');//验证码

const  UPLOAD_CON = app\admin\controller\UploadData::class;

Route::rule('upload/image$', UPLOAD_CON.'/image');//上传图片
Route::rule('upload/video$', UPLOAD_CON.'/video');//都可以上传

const  INDEX_CON = app\admin\controller\Index::class;
Route::rule('/$', INDEX_CON.'/index');//后台首页
Route::rule('admin/list$', INDEX_CON.'/index');//后台用户列表
Route::rule('admin/add$', INDEX_CON.'/add');//后台用户添加

Route::rule('admin/edit$', INDEX_CON.'/edit');//后台用户修改
Route::rule('admin/detail$', INDEX_CON.'/detail');//后台用户信息查看
Route::rule('admin/del$', INDEX_CON.'/del');//后台用户删除

const  MENU_CON = \app\admin\controller\auth\Menu::class;

Route::rule('menu/list$', MENU_CON.'/index');//后台菜单列表
Route::rule('menu/add$', MENU_CON.'/add');//后台菜单添加
Route::rule('menu/edit$', MENU_CON.'/edit');//后台菜单修改
Route::rule('menu/detail$', MENU_CON.'/detail');//后台菜单修改
Route::rule('menu/del$', MENU_CON.'/del');//后台菜单删除
Route::rule('menu/column$', MENU_CON.'/lists');//后台表单列表
Route::rule('menu/status$', MENU_CON.'/status');//后台表单列表

const  ACTION_CON = \app\admin\controller\auth\Action::class;
const  ROLE_CON = \app\admin\controller\auth\Role::class;
Route::rule('action/list$', ACTION_CON.'/index');//后台控制列表
Route::rule('action/add$', ACTION_CON.'/add');//后台控制列表
Route::rule('action/edit$', ACTION_CON.'/edit');//后台控制列表
Route::rule('action/del$', 'ACTION_CON./del');//后台控制列表
Route::rule('action/status$', ACTION_CON.'/status');//后台控制列表
Route::rule('action/con$', ACTION_CON.'/list_con');//后台控制显示
Route::rule('role/list$', ROLE_CON.'/index');//角色列表
Route::rule('role/add$', ROLE_CON.'/add');//角色列表add
Route::rule('role/edit$', ROLE_CON.'/edit');//角色列表edit
Route::rule('role/del$', ROLE_CON.'/del');//角色列表del
Route::rule('role/status$', ROLE_CON.'/status');//角色列表

const  BRANCH_CON = \app\admin\controller\auth\BranchAuth::class;
Route::rule('auth/action$', BRANCH_CON.'/action_list');//控制器列表
Route::rule('auth/action_edit$', BRANCH_CON.'/action_edit');//控制器列表
Route::rule('auth/menu$', BRANCH_CON.'/menu_list');//菜单列表
Route::rule('auth/menu_edit$', BRANCH_CON.'/menu_edit');//菜单列表

const  ROLEMENU_CON = \app\admin\controller\auth\RoleMenu::class;
Route::rule('role_menu/list$', ROLEMENU_CON.'/index');//角色菜单列表分组
Route::rule('role_menu/add$', ROLEMENU_CON.'/add');//角色菜单列表添加
Route::rule('role_menu/edit$', ROLEMENU_CON.'/edit');//角色菜单列表
const  ROLEPOWER_CON = \app\admin\controller\auth\RolePower::class;
Route::rule('power/list$', ROLEPOWER_CON.'/index');//角色 api接口列表
Route::rule('power/add$', ROLEPOWER_CON.'/add');//角色 api接口列表
Route::rule('power/edit$', ROLEPOWER_CON.'/edit');//角色 api接口列表


const  LOGINLOG_CON = \app\admin\controller\log\LoginLog::class;
Route::rule('login/log$', LOGINLOG_CON.'/index');//登陆日志
Route::rule('money/log$', LOGINLOG_CON.'/index');//资金流动日志
Route::rule('examine/log$', LOGINLOG_CON.'/examine');//审查余额
Route::rule('admin/log$', LOGINLOG_CON.'/index');//后台操作日志


//提现
Route::rule('pay/list$', 'admin/log.PayCash/index');//提现列表日志
Route::rule('pay/pass$', 'admin/log.PayCash/setPass');//提现通过
Route::rule('pay/refuse$', 'admin/log.PayCash/refuse');//提现拒绝
Route::rule('pay/examine$', 'admin/log.PayCash/examine');//通过ID批量处理
Route::rule('pay/is_line$', 'admin/log.PayCash/is_line');//线上线下
Route::rule('pay/amount$', 'admin/log.PayCash/amount_edit');//修改金额
Route::rule('pay/test$', 'admin/log.PayCash/test');//提现列表日志



Route::rule('order/list$', 'admin/order.order/index');//订单列表
Route::rule('order/edit$', 'admin/order.order/edit');//订单状态



Route::rule('bank/list$', 'admin/PayBank/index');//银行卡列表
Route::rule('bank/del$', 'admin/PayBank/del');//银行卡删除
Route::rule('bank/default$', 'admin/PayBank/default');//银行卡修改默认卡

Route::rule('config/list$', 'admin/SysConfig/index');//后台配置文件列表
Route::rule('config/add$', 'admin/SysConfig/add');//后台添加
Route::rule('config/edit$', 'admin/SysConfig/edit');//后台修改
Route::rule('config/detail$', 'admin/SysConfig/detail');//配置详情
Route::rule('config/del$', 'admin/SysConfig/del');//配置删除



Route::rule('user/is_status$', 'admin/User/is_status');//用户是否虚拟账号设置
Route::rule('user/list$', 'admin/User/index');//用户列表
Route::rule('user/agent$', 'admin/User/agent_data');//代理商信息


Route::rule('user/edit$', 'admin/User/edit');//用户修改
Route::rule('user/add$', 'admin/User/add');//
Route::rule('user/del$', 'admin/User/del');//
Route::rule('user/detail$', 'admin/User/detail');//用户详情
Route::rule('user/status$', 'admin/User/status');//用户状态修改


//订单统计
Route::rule('order/all$', 'admin/count.Order/index');//今日订单与总订单列表全部
Route::rule('order/today$', 'admin/count.Order/today_order');//今日订单全部
Route::rule('order/total$', 'admin/count.Order/total_order');//总订单全部
Route::rule('order/today_pay$', 'admin/count.Order/today_pay_order');//今日订单 已经支付的
Route::rule('order/total_pay$', 'admin/count.Order/total_pay_order');//总订单　已经支付的
Route::rule('order/today_money$', 'admin/count.Order/today_pay');//今日订单金额 已支付
Route::rule('order/total_money$', 'admin/count.Order/total_pay');//总订单金额  已支付


//产品添加
Route::rule('product/index$', 'admin/product.TouziProduct/index');
Route::rule('product/add$', 'admin/product.TouziProduct/add');
Route::rule('product/edit$', 'admin/product.TouziProduct/edit');
Route::rule('product/del', 'admin/product.TouziProduct/del');
Route::rule('product/sel', 'admin/product.TouziProduct/page_value_list');
//产品分类添加
Route::rule('product/class_index$', 'admin/product.TouziProductClass/index');
Route::rule('product/class_add$', 'admin/product.TouziProductClass/add');
Route::rule('product/class_edit$', 'admin/product.TouziProductClass/edit');
Route::rule('product/class_del$', 'admin/product.TouziProductClass/del');

//轮播图
Route::rule('ads/index$', 'admin/TouziAds/index');
Route::rule('ads/add$', 'admin/TouziAds/add');
Route::rule('ads/edit$', 'admin/TouziAds/edit');
Route::rule('ads/del$', 'admin/TouziAds/del');


###### 代理页面，因前端无法更改 路由，所以 后台路由改在这里
Route::rule('login/agent$', Login::class . '/index');//代理商登录
Route::rule('/agent/user/list$', User::class . '/index');//代理用户列表
Route::rule('/agent/money/edit$', User::class . '/money_edit');//代理修改用户金额
Route::rule('/agent/user/status$', User::class . '/is_status');//用户状态修改
Route::rule('/agent/statistics$', Statistics::class . '/statistics');//用户状态修改
Route::rule('/agent/check/callback$' , Statistics::class . '/checkCallback');
Route::rule('/agent/invite/list$' , User::class .'/inviteList');
Route::rule('/agent/invite/list/delete$' , User::class .'/inviteDelete');
Route::rule('/agent/team/guarantee$' , User::class .'/teamGuarantee');
//Route::rule('/agent/menu/list$', Login::class.'/index');//后台菜单列表

