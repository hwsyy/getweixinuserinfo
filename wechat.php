<?php
class helper_wechat {
    protected $app_id;
    protected $app_secret;
    protected $token;
    protected $access_token;
    protected $open_id;
    public $user_info;
    public function __construct($app_id,$app_secret){
        $this->app_id = $app_id;
        $this->app_secret = $app_secret;
        $this->_getToken();
        $this->_getAccessToken();
        $this->_getUserInfo();
    }
    /**
     * 用户同意授权，获取code
     * @return boolean
     */
    protected function _getToken(){
        $code = $_GET['code'];
        if (empty($code)) {
            echo json_encode(array('error'=>1,'msg'=>'授权失败'));
            return false;
        }
        $appid = $this->app_id;
        $appsecret = $this->app_secret;
        $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code&scope=snsapi_userinfo';
        $token = json_decode(file_get_contents($token_url),true);
        if (isset($token['errcode'])) {
            echo json_encode(array('error'=>$token['errcode'],'msg'=>$token['errmsg']));
            return false;
        }
        $this->token = $token['refresh_token'];
    }
    /**
     * 通过code换取网页授权access_token/open_id
     * @return boolean
     */
    protected function _getAccessToken(){
        $appid = $this->app_id;
        $refresh_token = $this->token;
        $access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$refresh_token;
        $access_token = json_decode(file_get_contents($access_token_url),true);
        if (isset($access_token['errcode'])) {
            echo json_encode(array('error'=>$access_token['errcode'],'msg'=>$access_token['errmsg']));
            return false;
        }
        $this->access_token = $access_token['access_token'];
        $this->open_id = $access_token['openid'];
    }
    /**
     * 拉取用户信息(需scope为 snsapi_userinfo)
     * @return boolean
     */
    protected function _getUserInfo(){
        $access_token = $this->access_token;
        $openid = $this->open_id;
        $user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $user_info = json_decode(file_get_contents($user_info_url),true);
        if (isset($user_info['errcode'])) {
            echo json_encode(array('error'=>$user_info['errcode'],'msg'=>$user_info['errmsg']));
            return false;
        }
        $this->user_info = $user_info;
    }
}

