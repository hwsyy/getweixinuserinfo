# getweixinuserinfo
get user infomation by the WeChat API<br/>
1、登陆微信公众号后台设置授权回调页面域名地址，如下图

2、获取公众号的APPID和APPSECRET
在开发=》基本配置中

3、跳转到微信登陆
header('location:https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=123&connect_redirect=1#wechat_redirect');
注意：这里的appid是通过步骤2获取的
注意：这里的redirect_uri是通过步骤1配置的你自己的服务器接口地址
注意：Scope为snsapi_base为静默登录（不弹窗提示）登陆
注意：Scope为snsapi_userinfo为弹窗提示登陆

4、在你的redirect_uri上通过接口获取token
注意：这里的appid是从步骤2中获取的
$token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code&scope=snsapi_userinfo';
$token = json_decode(file_get_contents($token_url));

5、获取access_token和open_id
$access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$token->refresh_token;
$access_token = json_decode(file_get_contents($access_token_url));
注意：这里的refresh_token是步骤3中获取到$token对象取得

6、获取用户信息
$user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token->access_token.'&openid='.$access_token->openid.'&lang=zh_CN';
$user_info = json_decode(file_get_contents($user_info_url),true);
注意：这里的access_token和openid是步骤4中的$access_token对象获取的

7、可以将open_id作为用户的唯一标识存入session，通过判断$_SESSION['openid']是否存在来知道该用户是否已经获取信息


