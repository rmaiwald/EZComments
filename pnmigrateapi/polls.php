<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link http://code.zikula.org/ezcomments
 * @version $Id$
 * @license See license.txt
 */

/**
 * Do the migration
 * 
 * With this function, the actual migration is done.
 * 
 * @return   boolean   true on sucessful migration, false else
 * @since    0.2
 */
function EZComments_migrateapi_polls()
{
    // Security check
    if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerError('Polls migration: Not Admin');
    } 

    // Get datbase setup
    $dbconn = pnDBGetConn(true);
    $pntable = pnDBGetTables();

    $EZCommentstable  = $pntable['EZComments'];
    $EZCommentscolumn = &$pntable['EZComments_column']; 

    $Commentstable = $pntable['pollcomments'];
    $Commentscolumn = $pntable['pollcomments_column'];

    $Usertable = $pntable['users'];
    $Usercolumn = $pntable['users_column'];

    $sql = "SELECT $Commentscolumn[tid], 
                   $Commentscolumn[pollid],
                   $Commentscolumn[date], 
                   $Usercolumn[uid], 
                   $Commentscolumn[comment],
                   $Commentscolumn[subject],
                   $Commentscolumn[pid]
             FROM  $Commentstable LEFT JOIN $Usertable
               ON $Commentscolumn[name] = $Usercolumn[uname]";

    $result = $dbconn->Execute($sql); 
    if ($dbconn->ErrorNo() != 0) {
        return LogUtil::registerError('Polls migration: DB Error');
    }

    // array to rebuild the patents
    $comments = array(0 => array('newid' => -1));

    // loop through the old comments and insert them one by one into the DB
    for (; !$result->EOF; $result->MoveNext()) {
        list($tid, $sid, $date, $uid, $comment, $subject, $replyto) = $result->fields;

        // set the correct user id for anonymous users
        if (empty($uid)) {
            $uid = 1;
        }

        $id = pnModAPIFunc('EZComments',
                           'user',
                           'create',
                           array('mod'  => 'Polls',
                                   'objectid' => pnVarPrepForStore($sid),
                                   'url'        => 'name=Polls&req=results&pollID=' . $pollid,
                                   'comment'  => $comment,
                                 'subject'  => $subject,
                                 'uid'      => $uid,
                                 'date'     => $date));

        if (!$id) {
            return LogUtil::registerError('Polls migration: Error creating comment');
        }

        $comments[$tid] = array('newid' => $id, 
                                'pid'   => $replyto);
        
    } 
    $result->Close(); 

    // rebuild the links to the parents
    $tids = array_keys($comments);
    foreach ($tids as $tid) {
        if ($tid != 0) {
            $v = $comments[$tid];
            $sql = "UPDATE $EZCommentstable 
                       SET $EZCommentscolumn[replyto] = " . $comments[$v['pid']]['newid'] . "
                     WHERE $EZCommentscolumn[id] = $v[newid]";

            $result = $dbconn->Execute($sql); 
        }
    }

    // activate the ezcomments hook for the news module
    pnModAPIFunc('Modules', 'admin', 'enablehooks',
                 array('callermodname' => 'Polls',
                       'hookmodname' => 'EZComments'));

    LogUtil::registerStatus('Polls migration successful');
}