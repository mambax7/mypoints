<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       The XUUPS Project http://www.xuups.com
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         Mypoints
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 */

require_once __DIR__ . '/../../mainfile.php';

$uid = 0;

if (is_object($xoopsUser) && $xoopsUser->getVar('uid') > 0) {
    $uid      = $xoopsUser->getVar('uid');
    $thisUser =& $xoopsUser;
}

if (isset($_GET['uid'])) {
    $getuid  = (int)$_GET['uid'];
    $getUser = new xoopsUser($getuid);
    if (is_object($getUser) && $getUser->isActive()) {
        $uid      = $getuid;
        $thisUser =& $getUser;
    } else {
        $uid = 0;
    }
}

if (0 == $uid) {
    redirect_header(XOOPS_URL . '/modules/mypoints/index.php', 2, _NOPERM);
}

require_once XOOPS_ROOT_PATH . '/modules/mypoints/include/functions.php';

$GLOBALS['xoopsOption']['template_main'] = 'mypoints_mypoints.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

$pluginHandler   = xoops_getModuleHandler('plugin');
$relationHandler = xoops_getModuleHandler('relation');
$userHandler     = xoops_getModuleHandler('user');

$refreshtime = $xoopsModuleConfig['refreshtime'];
$since       = strtotime($xoopsModuleConfig['countsince']);
$countwebm   = $xoopsModuleConfig['countadmin'];
$limit       = $xoopsModuleConfig['memberstoshow'];

$xoopsTpl->assign('topmessage', sprintf(_MA_MYPOINTS_USERTOPMESSAGE, $thisUser->getVar('uname')));

if ($refreshtime < 60) {
    $refreshtimes = $refreshtime;
    $message      = 1 == $refreshtimes ? _MA_MYPOINTS_LSECOND : _MA_MYPOINTS_LSECONDS;
} elseif ($refreshtime < 3600) {
    $refreshtimes = (int)($refreshtime / 60);
    $message      = 1 == $refreshtimes ? _MA_MYPOINTS_LMINUTE : _MA_MYPOINTS_LMINUTES;
} elseif ($refreshtime < 86400) {
    $refreshtimes = (int)($refreshtime / 3600);
    $message      = 1 == $refreshtimes ? _MA_MYPOINTS_LHOUR : _MA_MYPOINTS_LHOURS;
} else {
    $refreshtimes = (int)($refreshtime / 86400);
    $message      = 1 == $refreshtimes ? _MA_MYPOINTS_LDAY : _MA_MYPOINTS_LDAYS;
}

$xoopsTpl->assign('updatemessage', sprintf(_MA_MYPOINTS_UPDATEMESSAGE, $refreshtimes, $message));
$xoopsTpl->assign('sincemessage', sprintf(_MA_MYPOINTS_SINCEMESSAGE, formatTimestamp($since, 'm', $xoopsConfig['server_TZ'])));

$criteria = new CriteriaCompo(new Criteria('pluginisactive', 1));
$criteria->setSort('pluginmulti');
$criteria->setOrder('DESC');
$plugins = $pluginHandler->getObjects($criteria);
unset($criteria);

$user = $userHandler->get($uid);
if (is_object($user)) {
    $i = 0;
    foreach ($plugins as $plugin) {
        $relation                        = $relationHandler->getByPluginUid($plugin->getVar('pluginid'), $uid);
        $points                          = is_object($relation) ? $relation->getVar('relationpoints') : 0;
        $myuser['plugins'][$i]['items']  = $points;
        $myuser['plugins'][$i]['points'] = $points * $plugin->getVar('pluginmulti');
        $myuser['plugins'][$i]['name']   = $plugin->getVar('pluginname');
        $myuser['plugins'][$i]['multi']  = $plugin->getVar('pluginmulti');
        ++$i;
    }
    $myuser['points'] = $user->getVar('userpoints');
} else {
    $myuser['points'] = 0;
}

$xoopsTpl->assign('user', $myuser);

$message = '';
foreach ($plugins as $plugin) {
    $message .= $plugin->getVar('pluginname') . ' : ';
    $points  = 1 == $plugin->getVar('pluginmulti') ? _MA_MYPOINTS_LPOINT : _MA_MYPOINTS_LPOINTS;
    $message .= $plugin->getVar('pluginmulti') . ' ' . $points . '<br>';
}

$xoopsTpl->assign('howtoearnmessage', $message);
mypoints_updatePoints();

require_once XOOPS_ROOT_PATH . '/footer.php';
