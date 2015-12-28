<?php
$wxservice = new helper_wechat($appid, $appsecret);
$user_info = $wxservice->user_info;
var_dump($user_info);
