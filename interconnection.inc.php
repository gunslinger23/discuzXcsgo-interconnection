<?php

if(!defined('IN_DISCUZ')){
	exit('Access Denied');
}

if(!$_G['uid']){

    showmessage('to_login', null, array(), array('showmsg' => true, 'login' => 1));

}

require_once __DIR__ . '/configs.php';
require_once __DIR__ . '/function.php';

$steam64 = -1;
$steam32 = -1;
$steamid = -1;
$coinnum = -1;
$credits = -1;
$players = -1;
$storeid = -1;

$dzusers = DB::fetch_first("SELECT * FROM " . DB::table('steam_users') . " WHERE uid = $_G[uid]");

$database = mysqli_connect($db_host, $db_user, $db_pswd, $db_name);

if($dzusers['steamID64']){

    $dzusers['name'] = htmlspecialchars($dzusers['steamNickname']);
    $dzusers['avatar_full'] = str_replace(".jpg", "_full.jpg", $dzusers['avatar']);
	$steam64 = $dzusers['steamID64'];
    $steam32 = SteamID64ToSteamID32($steam64, true);
    $steamid = SteamID64ToSteamID32($steam64, false);
    
    if($dzusers['lastupdate'] < time()-1800){

        if(UpdateSteamProfiles($database, $api_key, $steam64, $_G['uid'])){

            showmessage("已更新您的Steam账户数据", 'plugin.php?id=interconnection');
            
        }
    }

}else{

    showmessage("请您先关联您的Steam账户", 'home.php?mod=spacecp&ac=plugin&id=sq_steam_bind:steam_settings');

}

$module = $_GET['mod'] ? $_GET['mod'] : 'main';

$file = __DIR__ . '/module/'.$module.'.inc.php';
//LogMessage("Mod: $file");

if(!file_exists($file)){

    showmessage('系统正在建设... 离完善还有一段时日...');

}

$coinnum = C::t('common_member_count')->fetch($_G['uid'])['extcredits1'];

require_once $file;
require_once template('interconnection:template');

mysqli_close($database);

?>