<?php 
/**
 * $Id$
 * 
 * * EZComments *
 * 
 * Attach comments to any module calling hooks
 * 
 * 
 * * License *
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License (GPL)
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 *
 * @author      Joerg Napp <jnapp@users.sourceforge.net>
 * @version     0.2
 * @link        http://lottasophie.sourceforge.net Support and documentation
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package     Postnuke
 * @subpackage  EZComments
 */

 
/**
 * Return to index page
 * 
 * This is the default function called when EZComments is called 
 * as a module. As we do not intend to output anything, we just 
 * redirect to the start page.
 */
function EZComments_user_main($args)
{
	pnredirect(pnGetBaseUrl());
	return true;
}


/**
 * Display comments for a specific item
 * 
 * This function provides the main user interface to the comments
 * module. 
 * 
 * @param    $args['objectid']     ID of the item to display comments for
 * @param    $args['extrainfo']    URL to return to if user chooses to comment
 * @return   output                the comments
 */
function EZComments_user_view($args)
{
	$modname = pnModGetName();
	$objectid = $args['objectid'];

	if (!pnSecAuthAction(0, 'EZComments::', "$modname:$objectid: ", ACCESS_OVERVIEW)) {
		return _EZCOMMENTS_NOAUTH;
	} 

	if (!pnModAPILoad('EZComments', 'user')) {
		return _LOADFAILED;
	}

	$items = pnModAPIFunc('EZComments',
			              'user',
			              'getall',
			               compact('modname', 'objectid'));

	if ($items === false) {
		return _EZCOMMENTS_FAILED;
	} 

	$comments = EZComments_prepareCommentsForDisplay($items);

	require_once dirname(__FILE__) . '/ezcsmarty.php';
	$smarty = new EZComments_Smarty;

	$smarty->assign('comments',     $comments);
	$smarty->assign('authid',       pnSecGenAuthKey('EZComments'));
	$smarty->assign('allowadd',     pnSecAuthAction(0, 'EZComments::', "$modname:$objectid: ", ACCESS_COMMENT));
	$smarty->assign('delurl',       pnModURL('EZComments', 'user', 'delete'));
	$smarty->assign('addurl',       pnModURL('EZComments', 'user', 'create'));
	$smarty->assign('commenturl',   pnModURL('EZComments', 'user', 'comment'));
	$smarty->assign('redirect',     pnVarPrepForDisplay($args['extrainfo']));
	$smarty->assign('modname',      pnVarPrepForDisplay($modname));
	$smarty->assign('objectid',     pnVarPrepForDisplay($objectid));
	
	if ($smarty->template_exists($modname . '.htm')) {
		return $smarty->fetch($modname . '.htm');
	} else {
		return $smarty->fetch('default.htm');
	}

} 


/**
 * Display a comment form 
 * 
 * This function displays a comment form, if you do not want users to
 * comment on the same page as the item is.
 * 
 * @param    $EZComments_comment     the comment (taken from HTTP put)
 * @param    $EZComments_modname     the name of the module the comment is for (taken from HTTP put)
 * @param    $EZComments_objectid    ID of the item the comment is for (taken from HTTP put)
 * @param    $EZComments_redirect    URL to return to (taken from HTTP put)
 * @todo     Check out it this function can be merged with _view!
 */
function EZComments_user_comment($args)
{
	list($EZComments_comment,
		 $EZComments_modname,
		 $EZComments_objectid,
		 $EZComments_redirect) = pnVarCleanFromInput('EZComments_comment',
                        					 		 'EZComments_modname',
                        					 		 'EZComments_objectid',
                        					 		 'EZComments_redirect');
	if (!pnModAPILoad('EZComments', 'user')) {
		return _LOADFAILED;
	}

	$items = pnModAPIFunc('EZComments',
			              'user',
			              'getall',
			               array('modname'  => $EZComments_modname, 
						         'objectid' => $EZComments_objectid));

	if ($items === false) {
		return _EZCOMMENTS_FAILED;
	} 

	$comments = EZComments_prepareCommentsForDisplay($items);

	require_once dirname(__FILE__) . '/ezcsmarty.php';
	$smarty = new EZComments_Smarty;

	$smarty->assign('comments',     $comments);
	$smarty->assign('authid',   pnSecGenAuthKey('EZComments'));
	$smarty->assign('allowadd', pnSecAuthAction(0, 'EZComments::', "$modname:$objectid: ", ACCESS_COMMENT));
	$smarty->assign('addurl',   pnModURL('EZComments', 'user', 'create'));
	$smarty->assign('redirect', $EZComments_redirect);
	$smarty->assign('modname',  pnVarPrepForDisplay($EZComments_modname));
	$smarty->assign('objectid', pnVarPrepForDisplay($EZComments_objectid));
	
	if ($smarty->template_exists($EZComments_modname . '_comment.htm')) {
		return $smarty->fetch($EZComments_modname . '_comment.htm');
	} else {
		return $smarty->fetch('default_comment.htm');
	}
}



/**
 * Create a comment for a specific item
 * 
 * This is a standard function that is called with the results of the
 * form supplied by EZComments_user_view to create a new item
 * 
 * @param    $EZComments_comment     the comment (taken from HTTP put)
 * @param    $EZComments_modname     the name of the module the comment is for (taken from HTTP put)
 * @param    $EZComments_objectid    ID of the item the comment is for (taken from HTTP put)
 * @param    $EZComments_redirect    URL to return to (taken from HTTP put)
 * @param    $EZComments_subject     The subject of the comment (if any) (taken from HTTP put)
 * @param    $EZComments_replyto     The ID of the comment for which this an anser to (taken from HTTP put)
 */
function EZComments_user_create($args)
{
	list($EZComments_modname,
		 $EZComments_objectid,
		 $EZComments_redirect,
		 $EZComments_comment,
		 $EZComments_subject,
		 $EZComments_replyto) = pnVarCleanFromInput('EZComments_modname',
                        					 		 'EZComments_objectid',
                        					 		 'EZComments_redirect',
													 'EZComments_comment',
													 'EZComments_subject',
													 'EZComments_replyto');
	// Confirm authorisation code.
	if (!pnSecConfirmAuthKey()) {
		pnSessionSetVar('errormsg', _BADAUTHKEY);
		pnRedirect($EZComments_redirect);
		return true;
	} 
	// Load API
	if (!pnModAPILoad('EZComments', 'user')) {
		pnSessionSetVar('errormsg', _LOADFAILED);
		pnRedirect($EZComments_redirect);
		return false;
	} 

	$id = pnModAPIFunc('EZComments',
        			   'user',
        			   'create',
        			   array('modname'  => $EZComments_modname,
      	        			 'objectid' => $EZComments_objectid,
      			        	 'url'	    => $EZComments_redirect,
              				 'comment'  => $EZComments_comment,
							 'subject'  => $EZComments_subject,
							 'replyto'  => $EZComments_replyto));

	if ($id != false) {
		// Success
		pnSessionSetVar('statusmsg', _EZCCOMMENTSCREATED);
	} 

	pnRedirect($EZComments_redirect);
	return true;
} 


/**
 * Delete a comment
 * 
 * This is a standard function that is called with the results of the
 * form supplied by EZComments_user_view to delete a comment
 * 
 * @param $EZComments_id ID of the the comment to delete (taken from HTTP put)
 * @param $EZComments_redirect URL to return to (taken from HTTP put)
 */
function EZComments_user_delete($args)
{
	list($EZComments_id,
	 $EZComments_redirect) = pnVarCleanFromInput('EZComments_id',
                        						 'EZComments_redirect'); 

	// Confirm authorisation code.
	if (!pnSecConfirmAuthKey()) {
		pnSessionSetVar('errormsg', _BADAUTHKEY);
		pnRedirect($return_url);
		return true;
	} 
	// Load API
	if (!pnModAPILoad('EZComments', 'user')) {
		pnSessionSetVar('errormsg', _LOADFAILED);
		return false;
	} 
	$id = pnModAPIFunc('EZComments',
              		   'user',
			           'delete',
        			   array('id' => $EZComments_id));

	if ($id != false) {
		pnSessionSetVar('statusmsg', _EZCCOMMENTSDELETED);
	} 

	pnRedirect($EZComments_redirect);
	return true;
} 



/**
 * Prepare comments to be displayed
 * 
 * We loop through the "raw data" returned from the API to prepare these data
 * to be displayed. 
 * We check for necessary rights, and derive additional information (e.g. user
 * data) drom other modules.
 * 
 * @param    $items    An array of comment items as returned from the API
 * @return   array     An array to display (augmented information / perm. check)
 */
function EZComments_prepareCommentsForDisplay($items)
{
	$comments = array();
	foreach ($items as $item) {
		if (pnSecAuthAction(0, 'EZComments::', "$modname:$objectid:$item[id]", ACCESS_READ)) {
			$comment = $item;

			if ($item['uid'] > 0) {
				$userinfo = pnUserGetVars($item['uid']);
				$comment['uname'] = $userinfo['uname'];
			} else {
				$comment['uname'] = pnConfigGetVar('Anonymous');
			}

			list($item['comment']) = pnModCallHooks('item', 'transform', 'x', array($item['comment']));

			$comment['comment'] = pnVarPrepHTMLDisplay(pnVarCensor(nl2br($item['comment'])));
			$comment['del'] = (pnSecAuthAction(0, 'EZComments::', "$modname:$objectid:$item[id]", ACCESS_DELETE));
			
			$comments[] = $comment;
		} 
	}
	return $comments;
}
?>
