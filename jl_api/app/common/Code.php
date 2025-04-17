<?php

namespace app\common;

class Code
{
    const ERROR           = 0;//失败
    const WARNING         = 500;//警告
    const SUCCESS         = 200;//成功
    const DISTANCE        = 505;//用户配送距离不对，返回特殊 code
    const TOKEN_ERROR     = 433;
    const TOKEN_EXPIRE    = 434;
    const TOKEN_NOT_FOUND = 435;
    const SIGN_ERROR      = 410;
    const USER_PWD_ERROR  = 420;
    const USER_NAME_ERROR = 421;


}