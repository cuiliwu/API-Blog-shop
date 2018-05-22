<?php
/**
 * 基本配置文件
 *
 */
namespace App\ConstDir;

class ConfigConst{

    //买家退款理由
    const BUYER_REFUND_REASON_IDS=[
            2 => '未按约定时间发货',
            1 => '其他'
    ];
    //买家退货理由
    const BUYER_RETURN_REASON_IDS=[

            6 => '未收到货',
            5 => '收到商品破损',
            4 => '收到商品与描述不符',
            3 => '商品质量问题',
            2 => '7天无理由退货',
            1 => '其他'
    ];
    //买家申请小二介入理由
    const BUYER_DISPUTE_RENSON_LIST=[
        1 => '错发/漏发',
        2 => '描述不符',
        3 => '假冒品牌',
        4 => '实物与描述不符',
        5 => '运费问题',
        6 => '活体死亡',
        7 => '商品破损',
        8 => '未发货',
        9 => '7天无理由退货'
    ];
    //卖家申请小二介入理由
    const SELLER_DISPUTE_RENSON_LIST=[
        1 => '退回拍品损坏',
        2 => '未退回',
        3 => '运费问题',
        4 => '活体退回死亡'
    ];
    //日志文件位置
    const LOG_PATH='./logs/';


}