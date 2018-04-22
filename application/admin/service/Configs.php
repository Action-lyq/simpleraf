<?php
namespace app\admin\service;

use think\Db;
use think\facade\Request;

/**
* 配置服务
*/
class Configs
{
    /**
     * 获取配置列表
     */
    public function getConfigs()
    {
        return Db::name('configs')->column('config_value', 'config_name');
    }

    /**
     * 更新配置
     */
    public function saveConfigs()
    {
        $data = Request::post();

        if (isset($data['smtp_pwd']) && trim($data['smtp_pwd']) === '') {
            unset($data['smtp_pwd']);
        }

        $ids = '\''.implode('\',\'', array_keys($data)).'\'';

        $sql = 'UPDATE '.config('datebase.prefix').'configs SET `config_value` = CASE `config_name` ';

        foreach ($data as $name => $value) {
            $sql .= sprintf("WHEN '%s' THEN '%s' ", $name, $value);
        }

        $sql .= "END WHERE `config_name` IN ($ids)";

        $update = DB::execute($sql);

        if ($update === false) {
            return build_api(0, '更新失败');
        } else {
            return build_api(1, '更新成功');
        }
    }
}
