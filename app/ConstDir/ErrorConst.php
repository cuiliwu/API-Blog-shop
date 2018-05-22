<?php
/**
 * 错误设置
 *
 */
namespace App\ConstDir;
class ErrorConst
{
    const BASE_ERROR = 200;
    const BASE_ERROR_MSG = '错误';

    const SUCCESS_CODE = 0;
    const SUCCESS_CODE_MSG = '成功';

    const FAILED_CODE = 1;
    const FAILED_CODE_MSG = '失败';
    
    const JWT_TOKEN_EXPIRE_CODE = 10;
    const JWT_TOKEN_EXPIRE_MSG = 'token已过期';

    const JWT_TOKEN_INVALID_CODE = 11;
    const JWT_TOKEN_INVALID_MSG = 'token无效';

    const JWT_TOKEN_CREATE_CODE = 12;
    const JWT_TOKEN_CREATE_MSG = '生成token出错或登陆记录入库错误';

    //参数缺少
    const ERROR_CODE = 100;
    const ERROR_CODE_MSG = '缺少参数';
    //校验错误
    const CHECK_ERROR_CODE = 101;
    const CHECK_ERROR_CODE_MSG = '校验错误';
    //解析错误
    const RESOLVE_ERROR_CODE = 102;
    const RESOLVE_ERROR_CODE_MSG = '解析错误';
    //数据库写入错误
    const RESOLVE_DATA_CODE = 103;
    const RESOLVE_DATA_CODE_MSG = '数据库写入错误';

    //非法操作,重复执行
    const REPEATE_OPERATE_CODE=104;
    const REPEATE_OPERATE_CODE_MSG='非法操作，不能重复执行';

    const NO_DATA_CODE = 105;
    const NO_DATA_CODE_MSG = '数据不存在';

    const NOT_LOGIN = 900;
    const NOT_LOGIN_MSG = '未登录';

    const PRODUCT_LOW_STOCKS = 1242; //商品库存不足
    const PRODUCT_LOW_STOCKS_MSG = '商品库存不足';


    const PRODUCT_SOLD_OUT = 1243; //订单中存在已下架商品
    const PRODUCT_SOLD_OUT_MSG = '订单中存在已下架商品';

    const LOGIN_ERROR_CODE = 1; //验证登录错误
    const LOGIN_ERROR_MSG = '账号密码错误或账号不存在';

    const LOGIN_ERROR_STATUS_CODE = 1; //账号被禁用
    const LOGIN_ERROR_STATUS_MSG = '账号已被禁用，请联系客服';

    const PHONE_ERROR_STATUS_CODE = 901; //验证手机号码是否存在（存在但是被禁用了）
    const PHONE_ERROR_STATUS_MSG = '手机号码已经存在，但是已被禁用';

    const PHONE_CHECK_YES_CODE = 902; //手机号码已经存在（正常能使用）
    const PHONE_CHECK_YES_MSG = '手机号码已经存在';

    const PHONE_CHECK_NO_CODE = 903; //手机号码不存在
    const PHONE_CHECK_NO_MSG = '手机号码不存在';

    const SMS_CODE_NO_CODE = 1; //验证码失效
    const SMS_CODE_NO_MSG = '验证码失效或不存在，请重新获取';

    const SMS_CODE_ERROR_CODE = 1; //手机号码不存在
    const SMS_CODE_ERROR_MSG = '输入验证码错误';

    const COMPANY_MONEY_ERROR_CODE = 1; //手机号码不存在
    const COMPANY_MONEY_ERROR_MSG = '验证错误，如遇问题请联系客服';

    const INVOICE_ERROR_CODE    = 801; //发票类型已存在
    const INVOICE_ADD_ERROR_MSG = '发票类型已存在';

    const INVOICE_UPDATE_ERROR_CODE = 802; //发票类型已存在
    const INVOICE_UPDATE_ERROR_MSG  = '不可修改发票类型';

    const PAY_VERIFY_FAILED_CODE = 301;
    const PAY_VERIFY_FAILED_MSG = '验签失败';
}