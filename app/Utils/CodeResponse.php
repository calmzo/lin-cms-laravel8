<?php

namespace App\Utils;

class CodeResponse
{
    //通用
    const SUCCESS = [201, '成功'];
    const FAIL = [-1, '错误'];


    const REPEAT = [400, '资源已存在'];
    const FORBIDDEN = [403, '权限不足，请联系管理员'];
    const PARAMETER_EXCEPTION = [10030, '参数错误'];
    const REPEAT_EXCEPTION = [10071, '资源已存在'];
    const FILEEXCEPTION = [6000, '文件体积过大'];
    const OPERATION_EXCEPTION = [10001, '权限不足，请联系管理员'];
    const FORBIDDEN_EXCEPTION = [10002, '权限不足，请联系管理员'];
    const VALIDATE_EXCEPTION = [10073, '参数错误'];
    const DEPLOY_EXCEPTION = [50000, '请修改php.ini配置：opcache.save_comments=1或直接注释掉此配置(无效请在 etc/php.d/ext-opcache.ini 文件中修改)'];

    const EXCEPTION = [10021, '异常'];
    const NOT_FOUND_EXCEPTION = [10021, '资源不存在'];

    const AUTHFAILED = [10021, '密码错误，请重新输入'];
    const PERMISSION_NOT_EXIST = [10231, '分配了不存在的权限'];


    const UN_LOGIN = [501, '请登录'];
    const UPDATE_DATA_FAILED = [505, '更新数据失败'];

    const DATA_NULL = [506, '数据不存在'];


    //业务返回码
    const AUTH_INVALID_ACCOUNT = [700, '账号不存在'];
    const AUTH_CAPTCHA_UNSUPPORT = [701, ''];
    const AUTH_CAPTCHA_FREQUENCY = [702, '验证码未超时1分钟，不能发送'];
    const AUTH_CAPTCHA_UNMATCH = [703, ''];
    const AUTH_NAME_REGISTERED = [704, ''];
    const AUTH_MOBILE_REGISTERED = [705, ''];
    const AUTH_MOBILE_UNREGISTERED = [706, ''];
    const AUTH_INVALID_MOBILE = [707, ''];
    const AUTH_OPENID_UNACCESS = [708, ''];
    const AUTH_OPENID_BINDED = [709, ''];

    const GOODS_UNSHELVE = [710, ''];
    const GOODS_NO_STOCK = [711, ''];
    const GOODS_UNKNOWN = [712, ''];
    const GOODS_INVALID = [713, ''];

    const ORDER_UNKNOWN = [720, ''];
    const ORDER_INVALID = [721, ''];
    const ORDER_CHECKOUT_FAIL = [722, ''];
    const ORDER_CANCEL_FAIL = [723, ''];
    const ORDER_PAY_FAIL = [724, ''];
    // 订单当前状态下不支持用户的操作，例如商品未发货状态用户执行确认收货是不可能的。
    const ORDER_INVALID_OPERATION = [725, ''];
    const ORDER_COMMENTED = [726, ''];
    const ORDER_COMMENT_EXPIRED = [727, ''];

    const GROUPON_EXPIRED = [730, ''];
    const GROUPON_OFFLINE = [731, ''];
    const GROUPON_FULL = [732, ''];
    const GROUPON_JOIN = [733, ''];

    const COUPON_EXCEED_LIMIT = [740, ''];
    const COUPON_RECEIVE_FAIL= [741, ''];
    const COUPON_CODE_INVALID= [742, ''];

    const AFTERSALE_UNALLOWED = [750, ''];
    const AFTERSALE_INVALID_AMOUNT = [751, ''];
    const AFTERSALE_INVALID_STATUS = [752, ''];

}
