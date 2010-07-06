<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link http://code.zikula.org/ezcomments
 * @license See license.txt
 */

class Content_Version extends Zikula_Version
{
    public function getMetaData()
    {
        $meta = array();
        // Information for the modules admin
        $meta['displayname']    = __('Comments', $dom);
        $meta['description']    = __('Attach comments to every kind of content using hooks', $dom);
        //! module url in lowercase and different to displayname
        $meta['url']            = __('comments', $dom);
        $meta['version']        = '2.1.0';
        $meta['contact']        = 'http://code.zikula.org/ezcomments/';
        $meta['securityschema'] = array(
                'EZComments::'          => 'Module:Item ID:Comment ID',
                'EZComments::trackback' => 'Module:Item ID:',
                'EZComments::pingback'  => 'Module:Item ID:'
        );

        // recommended and required modules
        $meta['dependencies'] = array(
                array('modname'    => 'akismet',
                        'minversion' => '1.0', 'maxversion' => '',
                        'status'     => PNMODULE_DEPENDENCY_RECOMMENDED),
                array('modname'    => 'ContactList',
                        'minversion' => '1.0', 'maxversion' => '',
                        'status'     => PNMODULE_DEPENDENCY_RECOMMENDED),
                array('modname'    => 'MyProfile',
                        'minversion' => '1.2', 'maxversion' => '',
                        'status'     => PNMODULE_DEPENDENCY_RECOMMENDED),
                array('modname'    => 'InterCom',
                        'minversion' => '2.1', 'maxversion' => '',
                        'status'     => PNMODULE_DEPENDENCY_RECOMMENDED)
        );
        return $meta;
    }
}