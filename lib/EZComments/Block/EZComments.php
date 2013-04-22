<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link https://github.com/zikula-modules/EZComments
 * @license See license.txt
 */

class EZComments_Block_EZComments extends Zikula_Controller_AbstractBlock
{
    /**
     * initialise block
     */
    public function init()
    {
        // Security
        SecurityUtil::registerPermissionSchema('EZComments:EZCommentsblock:', 'Block ID::');
    }

    /**
     * get information on block
     *
     * @return array       The block information
     */
    public function info()
    {
        return array('module'          => 'EZComments',
                'text_type'       => $this->__('Comments'),
                'text_type_long'  => $this->__('Show latest comments'),
                'allow_multiple'  => true,
                'form_content'    => false,
                'form_refresh'    => false,
                'show_preview'    => true,
                'admin_tableless' => true);

    }

    /**
     * display block
     *
     * @param array       $blockinfo     a blockinfo structure
     * @return output      the rendered bock
     */
    public function display($blockinfo)
    {
        // Security check
        if (!SecurityUtil::checkPermission('EZComments:EZCommentsblock:', "$blockinfo[bid]::", ACCESS_READ)) {
            return false;
        }

        if (!ModUtil::load('EZComments')) {
            return false;
        }

        // Get variables from content block
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // Defaults
        if (!isset($vars['numentries'])) {
            $vars['numentries'] = 5;
        }

        if (!isset($vars['numdays'])) {
            $vars['numdays'] = 0;
        }

        if (!isset($vars['showdate'])) {
            $vars['showdate'] = 0;
        }

        if (!isset($vars['showusername'])) {
            $vars['showusername'] = 0;
        }

        if (!isset($vars['linkusername'])) {
            $vars['linkusername'] = 0;
        }

        $options = array('numitems' => $vars['numentries']);

        if (isset($vars['mod']) && $vars['mod'] != '*') {
            $options['mod'] = $vars['mod'];
        }

        if (!isset($vars['showpending']) || $vars['showpending'] == 0) {
            // don't show pending comments
            $options['status'] = 0;
        }

        // filter comments posted in last number of days
        if ($vars['numdays'] > 0) {
            // date for filtering in format: yyyy-mm-dd hh:mm:ss
            $options['addwhere'] = "date>='".DateUtil::getDatetime_NextDay(-$vars['numdays'])."'";
        }

        // get the comments
        $items = ModUtil::apiFunc('EZComments', 'user', 'getall', $options);

        // augment the info
        $comments = ModUtil::apiFunc('EZComments', 'user', 'prepareCommentsForDisplay', $items);

        $this->view->assign($vars);
        $this->view->assign('comments', $comments);

        // Populate block info and pass to theme
        $blockinfo['content'] = $this->view->fetch('ezcomments_block_ezcomments.tpl');

        return BlockUtil::themesideblock($blockinfo);
    }

    /**
     * modify block settings
     *
     * @param array $blockinfo a blockinfo structure
     * @return output the bock form
     */
    public function modify($blockinfo)
    {
        if (!SecurityUtil::checkPermission('EZComments:EZCommentsblock:', "$blockinfo[bid]::", ACCESS_ADMIN)) {
            return false;
        }

        // Get current content
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // Defaults
        if (!isset($vars['numentries'])) {
            $vars['numentries'] = 5;
        }

        if (!isset($vars['numdays'])) {
            $vars['numdays'] = 0;
        }

        if (!isset($vars['showdate'])) {
            $vars['showdate'] = 0;
        }

        if (!isset($vars['showusername'])) {
            $vars['showusername'] = 0;
        }

        if (!isset($vars['linkusername'])) {
            $vars['linkusername'] = 0;
        }

        $options = array('numitems' => $vars['numentries']);

        if (isset($vars['mod']) && $vars['mod'] != '*') {
            $options['mod'] = $vars['mod'];
        }

        if (!isset($vars['showpending']) || $vars['showpending'] == 0) {
            // don't show pending comments
            $options['status'] = 0;
        }

        // get all modules with EZComments active
        $usermods = ModUtil::apiFunc('Modules', 'admin', 'gethookedmodules', array('hookmodname'=> 'EZComments'));

        // assign the block vars
        $this->view->assign($vars);

        $this->view->assign('usermods', array_keys($usermods));

        // Return the output that has been generated by this function
        return $this->view->fetch('ezcomments_block_ezcomments_modify.tpl');
    }

    /**
     * update block settings
     *
     * @param array       $blockinfo     a blockinfo structure
     * @return $blockinfo  the modified blockinfo structure
     */
    public function update($blockinfo)
    {
        // Get current content
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // alter the corresponding variable
        $vars['mod']          = (string)FormUtil::getPassedValue('mod', '', 'POST');
        $vars['numentries']   =    (int)FormUtil::getPassedValue('numentries', 5, 'POST');
        $vars['numdays']      =    (int)FormUtil::getPassedValue('numdays', 0, 'POST');
        $vars['showusername'] =   (bool)FormUtil::getPassedValue('showusername', false, 'POST');
        $vars['linkusername'] =   (bool)FormUtil::getPassedValue('linkusername', false, 'POST');
        $vars['showdate']     =   (bool)FormUtil::getPassedValue('showdate', false, 'POST');
        $vars['showpending']  =   (bool)FormUtil::getPassedValue('showpending', false, 'POST');

        // write back the new contents
        $blockinfo['content'] = BlockUtil::varsToContent($vars);

        // clear the block cache
        $this->view->clear_cache('ezcomments_block_ezcomments.tpl');

        return $blockinfo;
    }
}