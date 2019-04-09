<?php

namespace addons\third\library;

use addons\third\model\Third;
use app\common\model\User;
use fast\Random;
use think\Db;
use think\exception\PDOException;

/**
 * 第三方登录服务类
 *
 * @author Karson
 */
class Service
{

    /**
     * 第三方登录
     * @param string $platform 平台
     * @param array $params 参数
     * @param array $extend 会员扩展信息
     * @param int $keeptime 有效时长
     * @return boolean
     */
    public static function connect($platform, $params = [], $extend = [], $keeptime = 0)
    {
        $time = time();
        $values = [
            'platform'      => $platform,
            'openid'        => $params['openid'],
            'openname'      => isset($params['userinfo']['nickname']) ? $params['userinfo']['nickname'] : '',
            'access_token'  => $params['access_token'],
            'refresh_token' => $params['refresh_token'],
            'expires_in'    => $params['expires_in'],
            'logintime'     => $time,
            'expiretime'    => $time + $params['expires_in'],
        ];
        $auth = \app\common\library\Auth::instance();

        $auth->keeptime($keeptime);
        $third = Third::get(['platform' => $platform, 'openid' => $params['openid']]);
        //已经注册过的用户
        if ($third) {
            $user = User::get($third['user_id']);
            if (!$user) {
                return FALSE;
            }
            $third->save($values);
            //插入user_token表
            return $auth->direct($user->id);
        } else {
            // 先随机一个用户名,随后再变更为u+数字id
            $username = Random::alnum(20);
            $password = Random::alnum(6);

            Db::startTrans();
            try {

                //如果新用户，给邀请人增加体验日
                if($share_id = $params['share_id'])
                {
                    $assitor = User::get($share_id);
                    $deadline = $assitor->deadline;
                    if(time() > $deadline)
                    {
                        $deadline = time()+86400;//增加一天
                    }
                    else
                    {
                        $deadline = $deadline + 86400;
                    }
                    Db::name('user')->where(['id'=>$share_id])->update(['dealine'=>$deadline]);
                }

                // 默认注册一个会员
                $result = $auth->register($username, $password, $username . '@fastadmin.net', '', $extend, $keeptime);
                if (!$result) {
                    return FALSE;
                }
                $user = $auth->getUser();
                $fields = ['username' => 'u' . $user->id, 'email' => 'u' . $user->id . '@fastadmin.net'];
                if (isset($params['userinfo']['nickname']))
                    $fields['nickname'] = $params['userinfo']['nickname'];
                if (isset($params['userinfo']['avatar']))
                    $fields['avatar'] = $params['userinfo']['avatar'];

                //初始用户赠送0.5元  相当于50积分  1:100
                $fields['score'] = 50;

                // 更新会员资料
                $user = User::get($user->id);
                $user->save($fields);

                // 保存第三方信息
                $values['user_id'] = $user->id;
                Third::create($values);
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $auth->logout();
                return FALSE;
            }
            //插入user_token表
            // 写入登录Cookies和Token
            return $auth->direct($user->id);
        }
    }

}
