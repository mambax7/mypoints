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
// defined('XOOPS_ROOT_PATH') || exit('Restricted access.');
$modversion['version']       = '1.02';
$modversion['module_status'] = 'Beta 1';
$modversion['release_date']  = '2017/07/23';
$modversion['name']          = _MI_MYPOINTS_NAME;
$modversion['description']   = _MI_MYPOINTS_DSC;
$modversion['author']        = 'Trabis (Xuups)';
$modversion['credits']       = 'Trabis';
$modversion['help']          = 'page=help';
$modversion['license']       = 'GNU GPL 2.0 or later';
$modversion['license_url']   = 'www.gnu.org/licenses/gpl-2.0.html';
$modversion['official']      = 0; //1 indicates supported by XOOPS Dev Team, 0 means 3rd party supported
$modversion['image']         = 'assets/images/logoModule.png';
$modversion['dirname']       = basename(__DIR__);
$modversion['modicons16'] = 'assets/images/icons/16';
$modversion['modicons32'] = 'assets/images/icons/32';
$modversion['module_website_url']  = 'www.xoops.org';
$modversion['module_website_name'] = 'XOOPS';
$modversion['min_php']             = '5.5';
$modversion['min_xoops']           = '2.5.9';
$modversion['min_admin']           = '1.2';
$modversion['min_db']              = ['mysql' => '5.5'];

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file (without prefix!)
$i                        = 0;
$modversion['tables'][$i] = 'mypoints_user';
++$i;
$modversion['tables'][$i] = 'mypoints_plugin';
++$i;
$modversion['tables'][$i] = 'mypoints_relation';

// Admin things
$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

// Menu
$modversion['hasMain']     = 1;
$modversion['system_menu'] = 1;

// ------------------- Help files ------------------- //
$modversion['helpsection'] = [
    ['name' => _MI_MYPOINTS_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_MYPOINTS_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_MYPOINTS_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_MYPOINTS_SUPPORT, 'link' => 'page=support'],
];

// Templates
$i = 0;
++$i;
$modversion['templates'][$i]['file']        = 'mypoints_showall.tpl';
$modversion['templates'][$i]['description'] = '';
++$i;
$modversion['templates'][$i]['file']        = 'mypoints_mypoints.tpl';
$modversion['templates'][$i]['description'] = '';
++$i;
$modversion['templates'][$i]['file']        = 'mypoints_about.tpl';
$modversion['templates'][$i]['description'] = '';

//Menu
$i = 0;
global $xoopsUser;
if (is_object($xoopsUser) && $xoopsUser->getVar('uid') > 0) {
    ++$i;
    $modversion['sub'][$i]['name'] = _MI_MYPOINTS_MYPOINTS;
    $modversion['sub'][$i]['url']  = 'mypoints.php';
}

//Configs
$i = 0;
++$i;
$modversion['config'][$i]['name']        = 'displayname';
$modversion['config'][$i]['title']       = '_MI_MYPOINTS_NAMEDISPLAY';
$modversion['config'][$i]['description'] = '_MI_MYPOINTS_NAMEDISPLAY_DSC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
$modversion['config'][$i]['options']     = ['_MI_MYPOINTS_DISPLAYNAME1' => 1, '_MI_MYPOINTS_DISPLAYNAME2' => 2];

++$i;
$modversion['config'][$i]['name']        = 'refreshtime';
$modversion['config'][$i]['title']       = '_MI_MYPOINTS_REFRESHTIME';
$modversion['config'][$i]['description'] = '_MI_MYPOINTS_REFRESHTIME_DSC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 3600;

++$i;
$modversion['config'][$i]['name']        = 'memberstoshow';
$modversion['config'][$i]['title']       = '_MI_MYPOINTS_MEMBERSTOSHOW';
$modversion['config'][$i]['description'] = '_MI_MYPOINTS_MEMBERSTOSHOW_DSC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 50;

++$i;
$modversion['config'][$i]['name']        = 'countadmin';
$modversion['config'][$i]['title']       = '_MI_MYPOINTS_COUNTADMIN';
$modversion['config'][$i]['description'] = '_MI_MYPOINTS_COUNTADMIN_DSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;

++$i;
$modversion['config'][$i]['name']        = 'countsince';
$modversion['config'][$i]['title']       = '_MI_MYPOINTS_COUNTSINCE';
$modversion['config'][$i]['description'] = '_MI_MYPOINTS_COUNTSINCE_DSC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = date('Y-m-d', time());

// About stuff
$modversion['status_version']         = 'Final';
$modversion['developer_website_url']  = 'http://www.xuups.com';
$modversion['developer_website_name'] = 'Xuups';
$modversion['developer_email']        = 'lusopoemas@gmail.com';
$modversion['status']                 = 'Final';
$modversion['date']                   = '14/11/2009';

$modversion['people']['developers'][] = 'Trabis';
//$modversion['people']['testers'][] = "";
//$modversion['people']['translaters'][] = "";
//$modversion['people']['documenters'][] = "";
//$modversion['people']['other'][] = "";

$modversion['demo_site_url']     = 'http://www.xuups.com';
$modversion['demo_site_name']    = 'Xuups.com';
$modversion['support_site_url']  = 'http://www.xuups.com/modules/newbb';
$modversion['support_site_name'] = 'Xuups Support Forums';
$modversion['submit_bug']        = 'http://www.xuups.com/modules/newbb/viewforum.php?forum=26';
$modversion['submit_feature']    = 'http://www.xuups.com/modules/newbb/viewforum.php?forum=26';

//$modversion['author_word'] = "";
//$modversion['warning'] = "";
