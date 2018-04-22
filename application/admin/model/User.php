<?php
namespace app\admin\model;

use think\Model;

/**
* 用户模型
*/
class User extends Model
{
    public function roles()
    {
        return $this->belongsToMany('AuthGroup', 'auth_group_access', 'group_id', 'uid');
    }
}
