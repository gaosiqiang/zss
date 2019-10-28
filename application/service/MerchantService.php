<?php

namespace app\service;


use Couchbase\Exception;
use think\Db;

/**
 * 商户service
 * Class MerchantService
 * @package app\service
 */
class MerchantService
{

    const IS_AVAILABLE_STATUS_CODE = 0;//有效merchant_admin表数据状态

    /**
     * 获取管理员所属商户
     * @param int $admin_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getMerchantByAdminId(int $admin_id)
    {
        //校验参数
        if (!$admin_id) {
            return ['code' => -1, 'msg' => 'param error' , 'data' => []];
        }
        //通过merchant_admin表获取admin_id对应的数据
        $merchant_admin = Db::name('merchant_admin')->where(['admin_id' => $admin_id, 'admin_status' => self::IS_AVAILABLE_STATUS_CODE])->field('*')->find();
        if (!$merchant_admin) {
            return ['code' => -1, 'msg' => 'data empty', 'data' => []];
        }
        return ['code' => 0, 'msg' => 'access', 'data' => $merchant_admin];
    }

}