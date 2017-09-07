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
 * @package         MyPoints
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @param        $dirname
 * @param        $items
 * @param        $since
 * @param string $func
 * @return array|bool
 */

// defined('XOOPS_ROOT_PATH') || exit("XOOPS root path not defined");

function mypoints_pluginExecute($dirname, $items, $since, $func = 'useritems_count')
{
    global $xoopsUser, $xoopsConfig, $xoopsDB;

    $ret          = [];
    $plugins_path = XOOPS_ROOT_PATH . '/modules/mypoints/plugins';
    $plugin_info  = mypoints_getPluginInfo($dirname, $func);

    if (empty($plugin_info) || empty($plugin_info['plugin_path'])) {
        return false;
    }

    require_once $plugin_info['plugin_path'];

    // call the plugin
    if (function_exists(@$plugin_info['func'])) {
        // get the list of items
        $ret = $plugin_info['func']($items, $since);
    }

    return $ret;
}

function mypoints_getPluginInfo($dirname, $func = 'useritems_count')
{
    global $xoopsConfig;
    $language = $xoopsConfig['language'];
    // get $mytrustdirname for D3 modules
    $mytrustdirname = '';
    if (defined('XOOPS_TRUST_PATH') && file_exists(XOOPS_ROOT_PATH . "/modules/{$dirname}/mytrustdirname.php")) {
        @include XOOPS_ROOT_PATH . "/modules/{$dirname}/mytrustdirname.php";
        $d3module_plugin_file = XOOPS_TRUST_PATH . "/modules/{$mytrustdirname}/include/mypoints.plugin.php";
    }

    $module_plugin_file  = XOOPS_ROOT_PATH . "/modules/{$dirname}/include/mypoints.plugin.php";
    $builtin_plugin_file = XOOPS_ROOT_PATH . "/modules/mypoints/plugins/{$dirname}.php";

    if (file_exists($module_plugin_file)) {
        // module side (1st priority)
        $ret = [
            'plugin_path' => $module_plugin_file,
            'func'        => $dirname . '_' . $func,
            'type'        => 'module'
        ];
    } elseif (!empty($mytrustdirname) && file_exists($d3module_plugin_file)) {
        // D3 module's plugin under xoops_trust_path (2nd priority)
        $ret = [
            'plugin_path' => $d3module_plugin_file,
            'func'        => $mytrustdirname . '_' . $func,
            'type'        => 'module (D3)'
        ];
    } elseif (file_exists($builtin_plugin_file)) {
        // built-in plugin under modules/mypoints (3rd priority)
        $ret = [
            'plugin_path' => $builtin_plugin_file,
            'func'        => $dirname . '_' . $func,
            'type'        => 'built-in'
        ];
    } else {
        $ret = [];
    }

    return $ret;
}

//////
// Update the Users Scores (refresh table)
//////
function mypoints_updatePoints($force = 0)
{
    global $xoopsDB, $xoopsModuleConfig;

    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler   = xoops_getHandler('module');
    $pluginHandler   = xoops_getModuleHandler('plugin');
    $userHandler     = xoops_getModuleHandler('user');
    $relationHandler = xoops_getModuleHandler('relation');

    $refreshtime = $xoopsModuleConfig['refreshtime'];
    $since       = strtotime($xoopsModuleConfig['countsince']);
    $countwebm   = $xoopsModuleConfig['countadmin'];

    $user      = $userHandler->get(0);
    $timestamp = 0;
    if (is_object($user)) {
        $timestamp = $user->getVar('useruname');
    }

    if (((time() - $timestamp) >= $refreshtime) || $force == 1) {
        // Timer expired, update table
        // Set date of update
        $userHandler->deleteAll();
        $relationHandler->deleteAll();

        $user = $userHandler->create();
        $user->setVar('useruid', 0);
        $user->setVar('useruname', time());
        $user->setVar('userpoints', 0);
        $userHandler->insert($user);

        // Prep to calculate user points
        if ($countwebm == 0) {
            $query = $xoopsDB->query('SELECT uid, uname FROM ' . $xoopsDB->prefix('users') . " WHERE rank = '0' ORDER BY uid");
        } else {
            $query = $xoopsDB->query('SELECT uid, uname FROM ' . $xoopsDB->prefix('users') . ' ORDER BY uid');
        }
        $users = [];
        while (list($uid, $uname) = $xoopsDB->fetchRow($query)) {

            // Calculate User Points
            $points   = 0;
            $criteria = new CriteriaCompo(new Criteria('pluginisactive', 1));
            //$criteria->add(new Criteria('plugintype', 'items'), 'AND');
            $plugins = $pluginHandler->getObjects($criteria);
            foreach ($plugins as $plugin) {
                $moduleid = $plugin->getVar('pluginmid');
                $module   = $moduleHandler->get($moduleid);
                $count    = mypoints_pluginExecute($module->getVar('dirname'), $uid, $since, 'user' . $plugin->getVar('plugintype') . '_count');
                if ($count > 0) {
                    $relation = $relationHandler->create();
                    $relation->setVar('relationuid', $uid);
                    $relation->setVar('relationpid', $plugin->getVar('pluginid'));
                    $relation->setVar('relationpoints', $count);
                    $relationHandler->insert($relation);
                    unset($relation);
                    $points = $points + ($count * $plugin->getVar('pluginmulti'));
                }
                unset($module);
            }

            if ($points > 0) {
                $user = $userHandler->create();
                $user->setVar('useruid', $uid);
                $user->setVar('useruname', $uname);
                $user->setVar('userpoints', $points);
                $userHandler->insert($user);
                unset($user);
            }
        }
    }
}
