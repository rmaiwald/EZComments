<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link https://github.com/zikula-modules/EZComments
 * @license See license.txt
 */

/**
 * Do the migration
 * 
 * With this function, the actual migration is done.
 * 
 * @return   boolean   true on sucessful migration, false else
 * @since    0.6
 */
function EZComments_migrateapi_reviews()
{
    // Security check
    if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerError('Reviews migration: Not Admin');
    } 

    // Get datbase setup
    ModUtil::dbInfoLoad('Reviews', 'Reviews', true);

    $tables = DBUtil::getTables();

    $Commentstable  = $tables['reviews_comments'];
    $Commentscolumn = $tables['reviews_comments_column'];
    $Reviewstable   = $tables['reviews'];
    $Reviewscolumn  = $tables['reviews_column'];

    if (version_compare(PN_VERSION_NUM, '1', '>=')) {
        EZComments_get76xcolumns_reviews($Commentstable, $Commentscolumn);
    }
    if (is_null($Commentstable) || is_null($Commentscolumn)) {
        return LogUtil::registerError('Reviews migration: Comments tables not found');
    }

    $Usertable  = $tables['users'];
    $Usercolumn = $tables['users_column'];
    $Objecttable  = $tables['objectdata_attributes'];
    $Objectcolumn = $tables['objectdata_attributes_column'];

    // note: there's nothing we can do with the score......
    $sql = "SELECT $Commentscolumn[cid],
                   $Commentscolumn[rid],
                   C.$Commentscolumn[date], 
                   $Usercolumn[uid], 
                   $Objectcolumn[object_id] AS 'owneruid',
                   $Commentscolumn[comments],
                   C.$Commentscolumn[score]
              FROM $Commentstable C
         LEFT JOIN $Usertable
                ON $Commentscolumn[userid] = $Usercolumn[uname]
         LEFT JOIN $Reviewstable
                ON $Commentscolumn[rid] = $Reviewscolumn[id]
         LEFT JOIN $Objecttable
                ON $Objectcolumn[object_type] = 'users'
               AND $Objectcolumn[attribute_name] = 'realname'
               AND $Reviewscolumn[reviewer] LIKE $Objectcolumn[value]";

    $result = DBUtil::executeSQL($sql);

    if ($result == false) {
        return LogUtil::registerError('Reviews migration: DB Error');
    }

    // loop through the old comments and insert them one by one into the DB
    $items = DBUtil::marshallObjects($result, array('cid', 'rid', 'date', 'uid', 'owneruid', 'comment', 'score'));

    foreach ($items as $item) {
        // set the correct user id for anonymous users
        if (empty($item['uid'])) {
            $item['uid'] = 1;
        }
        if (empty($item['owneruid'])) {
            $item['owneruid'] = 1;
        }

        $id = ModUtil::apiFunc('EZComments', 'user', 'create',
                           array('mod'      => 'Reviews',
                                 'objectid' => DataUtil::formatForStore($item['rid']),
                                 'useurl'      => ModUtil::url('Reviews', 'user', 'display', array('id' => $item['rid'])),
                                 'comment'  => $item['comment'],
                                 'subject'  => '',
                                 'uid'      => $item['uid'],
                                 'owneruid' => $item['owneruid'],
                                 'date'     => $item['date']));

        if (!$id) {
            return LogUtil::registerError('Reviews migration: Error creating comment');
        } 
    } 

    // activate the ezcomments hook for the Reviews module
    ModUtil::apiFunc('Modules', 'admin', 'enablehooks',
                     array('callermodname' => 'Reviews',
                           'hookmodname'   => 'EZComments'));

    return LogUtil::registerStatus('Reviews migration successful');
}

function EZComments_get76xcolumns_reviews(&$Commentstable, &$Commentscolumn)
{
    $Commentstable  = DBUtil::getLimitedTablename('reviews_comments');
    $Commentscolumn = array(
        'cid'      => 'pn_cid',
        'rid'      => 'pn_rid',
        'userid'   => 'pn_userid',
        'date'     => 'pn_date',
        'comments' => 'pn_comments',
        'score'    => 'pn_score'
    );
}
