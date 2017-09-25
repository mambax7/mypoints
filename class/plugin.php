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
 */
// defined('XOOPS_ROOT_PATH') || exit("XOOPS root path not defined");

class MypointsPlugin extends XoopsObject
{
    /**
     * constructor
     */
    public function __construct()
    {
        $this->initVar('pluginid', XOBJ_DTYPE_INT);
        $this->initVar('pluginmid', XOBJ_DTYPE_INT);
        $this->initVar('pluginname', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('plugintype', XOBJ_DTYPE_TXTBOX, 'items');
        $this->initVar('pluginmulti', XOBJ_DTYPE_INT, 1);
        $this->initVar('pluginisactive', XOBJ_DTYPE_INT, 1);
    }
}

class MypointspluginHandler extends XoopsPersistableObjectHandler
{
    /**
     * constructor
     * @param XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db)
    {
        parent::__construct($db, 'mypoints_plugin', 'MypointsPlugin', 'pluginid', 'pluginmid');
    }

    public function getByModuleType($mid, $type)
    {
        $plugin = false;
        $mid    = (int)$mid;
        if ($mid > 0) {
            $sql = 'SELECT * FROM ' . $this->db->prefix('mypoints_plugin') . ' WHERE pluginmid=' . $mid . ' AND plugintype=' . $this->db->quoteString($type);
            if (!$result = $this->db->query($sql)) {
                return $plugin;
            }
            $numrows = $this->db->getRowsNum($result);
            if (1 == $numrows) {
                $plugin = new Mypointsplugin();
                $plugin->assignVars($this->db->fetchArray($result));
            }
        }

        return $plugin;
    }
}
