<?php
// 全局中间件定义文件
return [
    // 多语言加载
    \think\middleware\LoadLangPack::class,
//    \app\middleware\LogMiddleware::class,
    // 全局请求缓存
    // \think\middleware\CheckRequestCache::class,
    // Session初始化
    // \think\middleware\SessionInit::class
    \app\middleware\LogSqlMiddleware::class,
//    think\middleware\AllowCrossDomain::class,
];
