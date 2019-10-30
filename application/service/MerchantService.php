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

    /**
     * 创建商户
     * @param array $data
     * @return array
     */
    public function createMerchant(array $data)
    {
        if (!$data) {
            return ['code' => -1, 'msg' => 'param error' , 'data' => []];
        }
        //创建商户
        $merchant_id = Db::name('merchant')->insertGetId([
            'name' => isset($data['merchant_name']) ? $data['merchant_name'] : '',
            'address' => isset($data['merchant_address']) ? $data['merchant_address'] : '',
            'merchant_status' => 0,
            'status' => 0,
            'level' => isset($data['merchant_level']) ? (int)$data['merchant_level'] : 0,
            'update_by' => isset($data['admin_id']) ? (int)$data['admin_id'] : 0,
            'update_time' => time(),
            'create_by' => isset($data['admin_id']) ? (int)$data['admin_id'] : 0,
            'create_time' => time(),
        ]);
        if (!$merchant_id) {
            return ['code' => -2, 'msg' => 'create1 error' , 'data' => []];
        }
        //创建merchant_admin数据
        $insert_merhant_admin_data = [
            'merchant_id' => (int)$merchant_id,
            'admin_id' => isset($data['admin_id']) ? (int)$data['admin_id'] : 0,
            'rules' => isset($data['admin_rules']) ? (int)$data['admin_rules'] : 0,
            'admin_status' => 0,
            'level' => isset($data['admin_level']) ? (int)$data['admin_level'] : 0,
            'update_by' => isset($data['admin_id']) ? (int)$data['admin_id'] : 0,
            'update_time' => time(),
            'create_by' => isset($data['admin_id']) ? (int)$data['admin_id'] : 0,
            'create_time' => time(),
        ];
        $merchant_admin_id = Db::name('merchant_admin')->insertGetId($insert_merhant_admin_data);
        if (!$merchant_admin_id) {
            return ['code' => -3, 'msg' => 'create2 error' , 'data' => []];
        }
        return ['code' => 0, 'msg' => 'access', 'data' => ['merchant_id' => $merchant_id, 'admin_id' => $data['admin_id']]];
    }

}