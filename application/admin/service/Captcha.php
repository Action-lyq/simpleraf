<?php
namespace app\admin\service;

use think\captcha\Captcha as CaptchaClass;
use think\facade\Config;

/**
* 验证码服务
*/
class Captcha
{
    /**
     * 验证码图片
     * @param  string $id 验证码ID
     * @return string     验证码图片
     */
    public function captcha($id = '')
    {
        $captcha = new CaptchaClass(Config::get('captcha.'));
        return $captcha->entry($id);
    }

    /**
     * 检查验证码
     * @param  string $code 验证码
     * @return boolean       是否验证通过
     */
    public function check($code = '')
    {
        $captcha = new CaptchaClass(Config::get('captcha.'));
        if (!$captcha->check($code)) {
            return false;
        } else {
            return true;
        }
    }
}
