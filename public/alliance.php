<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-2010, XNova Support Team <http://www.xnova-ng.org>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing XNova.
 *
 */

define('INSIDE' , true);
define('INSTALL' , false);
require_once dirname(__FILE__) .'/common.php';

includeLang('alliance');

$readConnection = Nova::getSingleton('core/database_connection_pool')
    ->getConnection('core_read');

if ($user['ally_id'] == 0 && $user['ally_request'] == 0) {

    $modeArray = array('search', 'create', 'apply', 'ainfo');
    $mode = (isset($_GET['mode']) && in_array($_GET['mode'], $modeArray)) ? $_GET['mode'] : '';

    switch ($mode) {

    /** @deprecated - use ainfo.php */
    case 'ainfo':

        header("Location: ainfo.php?tag={$_GET['tag']}");
        die();

        break;

    case 'create':

        if ($user['ally_request'] != 0) {
            message($lang['Alliance_HaveAlreadyAnAlly'], $lang['Alliance_Alliance'], 'alliance.php');
            break;
        }

        if ($_POST) {

            if (!isset($_POST['atag'])	|| $_POST['atag'] == '' ||
                !isset($_POST['aname'])	|| $_POST['aname'] == '' )
            {
                message($lang['Alliance_HaveNoName'], $lang['Alliance_MakeAlliance']);
                break;
            }

            $allyExists = $readConnection->select()
               ->from($readConnection->getDeprecatedTable('alliance'), array ('id' => 'id'))
               ->where('ally_tag =?', $_POST['atag'])
               ->orWhere('ally_name =?', $_POST['aname'])
               ->query()
               ->fetch()
             ;

            if ($allyExists) {
                $alreadyExist = sprintf($lang['Alliance_AlreadyExist'], $_POST['aname']);
                message($alreadyExist, $lang['Alliance_MakeAlliance']);
                    break;
            }

            $allyRank[0] = array('name' => $lang['Alliance_Novate'], 'mails' => 0, 'kick' => 0, 'seeRequest' => 0,
                    'admin' => 0, 'adminRequest' => 0, 'memberlist' => 0, 'onlinestatus' => 0, 'rightHand' => 0 );
            $allyRank[1] = array('name' => 'Leader', 'mails' => 1, 'kick' => 1, 'seeRequest' => 1,
                    'admin' => 1, 'adminRequest' => 1, 'memberlist' => 1, 'onlinestatus' => 1, 'rightHand' => 1 );
                    $allyRank = serialize($allyRank);

            $allyData = array(
                'ally_name' => $_POST['aname'],
                'ally_tag' => $_POST['atag'],
                'ally_owner' => $user['id'],
                'ally_ranks' => $allyRank,
                'ally_members' => '1',
                'ally_register_time' => new Zend_Db_Expr('UNIX_TIMESTAMP()')
              );

            $readConnection->insert($readConnection->getDeprecatedTable('alliance'), $allyData);
                $allyId = $readConnection->lastInsertId($readConnection->getDeprecatedTable('alliance'));

            $userAlly = array (
                'ally_id' => $allyId,
                'ally_name' => $_POST['aname'],
                'ally_rank_id' => '1',
                'ally_register_time' => new Zend_Db_Expr('UNIX_TIMESTAMP()')
             );

             $readConnection->update($readConnection->getDeprecatedTable('users'), $userAlly, array ('id =?' => $user['id']));

             $title = sprintf($lang['Alliance_AllyCreated'], $_POST['atag']);
             $message = sprintf($lang['Alliance_AllyHasBeedCreated'], $_POST['aname']);

             $page = MessageForm($title, $message, 'alliance.php?mode=ally', $lang['Alliance_Ok']);

        } else
            $page = parsetemplate(gettemplate('alliance_make'), $lang);

        display ($page, $lang['Alliance_MakeAlliance']);

        break;

    case 'search':

        $parse = $lang;
        $page = parsetemplate(gettemplate('alliance_searchform'), $lang);

        if (isset($_POST['searchtext'])) {
                   
            $request = $readConnection->select()
                ->from($readConnection->getDeprecatedTable('alliance'))
                ->where('ally_name LIKE ?', '%' . $_POST['searchtext'] . '%')
                ->orWhere('ally_tag LIKE ?', '%' . $_POST['searchtext'] . '%')
                ->query()->fetchAll()
             ;

             if (!empty($request)) {

                $template = gettemplate('alliance_searchresult_row');

                foreach ($request as $id => $s) {

                    $row = array();
                    $row['ally_tag'] = "[<a href=\"alliance.php?mode=apply&allyid={$s['id']}\">{$s['ally_tag']}</a>]";
                    $row['ally_name'] = $s['ally_name'];
                    $row['ally_members'] = $s['ally_members'];

                    $parse['result'] .= parsetemplate($template, $row);
                        unset($row);
                }

                $page .= parsetemplate(gettemplate('alliance_searchresult_table'), $parse);
            }

        }

        display($page, $lang['Alliance_SearchAlly']);

        break;

    case 'apply':

        if ( isset($_GET['allyid'])) {

            $allyRow = $readConnection->select()
                ->from($readConnection->getDeprecatedTable('alliance'), array (
                  'id' => 'id',
                  'allyTag' => 'ally_tag',
                  'allyRequest' => 'ally_request',
                  'allyDescription' => 'ally_description'
                ))
                ->where('id =?', $_GET['allyid'])
                ->query()
                ->fetch()
            ;

            if ($allyRow) {

                if ( isset($_POST['further'])  && $_POST['further'] == $lang['Alliance_Send']) {

                    $requestData = array (
                        'ally_request' => $allyRow['id'],
                        'ally_request_text' => $_POST['text'],
                        'ally_register_time' => new Zend_Db_Expr('UNIX_TIMESTAMP()')
                    );

                    $readConnection->update($readConnection->getDeprecatedTable('users'), $requestData, array ('id =?' => $user['id']));
                        message($lang['Alliance_ApplyRegistered'], $lang['Alliance_Apply']);
                }

                $parse = $lang;

                    if ( isset($_POST['further'])  && $_POST['further'] == $lang['Alliance_Reload'] ) {
                        $parse['text_apply'] = $allyRow['allyDescription'];
                    } else {
                        $parse['text_apply'] = $lang['Alliance_NoApplyText'];
                    }

                    $parse['allyid'] = $allyRow['id'];
                    $parse['chars_count'] = strlen($parse['text_apply']);
                    $parse['Write_to_alliance'] = sprintf($lang['Alliance_SendApply'], $allyRow['allyTag']);

                    $page = parsetemplate(gettemplate('alliance_applyform'), $parse);
                        display($page, $lang['Alliance_Apply']);

            }
        }

        $page = parsetemplate(gettemplate('alliance_defaultmenu'), $lang);
            display($page, $lang['Alliance_Apply']);

        break;

    default:

        $page = parsetemplate(gettemplate('alliance_defaultmenu'), $lang);
            display($page, $lang['Alliance_AllianceInformation']);

        break;
} 	

} elseif ($user['ally_request'] != 0) {

    if (isset($_POST['bcancel'])) {
            $readConnection->update($readConnection->getDeprecatedTable('users'), array ('ally_request' => '0'), array ('id =?' => $user['id']));
    }

    $allyRequest = $readConnection->select()
       ->from($readConnection->getDeprecatedTable('alliance'), array ('ally_tag' => 'ally_tag'))
       ->where('id =?', $user['ally_request'])
       ->query()->fetch()
     ;
    if (isset($_POST['bcancel'])) {
            $lang['request_text'] = sprintf($lang['Alliance_RequestCanceled'], $allyRequest['ally_tag']);
            $lang['button_text'] = $lang['Alliance_Ok'];
    } else {
            $lang['request_text'] = sprintf($lang['Alliance_RequestAlreadySended'], $allyRequest['ally_tag']);
            $lang['button_text'] = $lang['Alliance_DeleteRequest'];
    }

    $page = parsetemplate(gettemplate('alliance_apply_waitform'), $lang);
            display($page, $lang['Alliance_YourRequest']);

} elseif ($user['ally_id'] !== 0) {

    $modeArray = array('exit', 'memberslist', 'circular', 'admin');
    $mode = ( isset($_GET['mode']) && in_array($_GET['mode'], $modeArray) ) ? $_GET['mode'] : '';

    $allyRow = $readConnection->select()
       ->from($readConnection->getDeprecatedTable('alliance'))
       ->where('id =?', $user['ally_id'])
       ->query()->fetch()
      ;

    if (!$allyRow) {
        $readConnection->update($readConnection->getDeprecatedTable('users'), array ('ally_name'=> '', 'ally_id'=> 0, 'ally_register_time' => ''), array ('id =?' => $user['id']));
            message($lang['Alliance_ally_notexist'], $lang['Alliance_your_alliance'], 'alliance.php', 1);
    }

    $allyRank = unserialize($allyRow['ally_ranks']);
    $userRank = $allyRank[$user['ally_rank_id']];

    $parse = $lang;
    $page = '';

    switch ($mode) {

case 'exit':

            if ($user['id'] != $allyRow['ally_owner']) {

                    if (isset($_POST['submit'])) {
                        $readConnection->update($readConnection->getDeprecatedTable('users'), array ('ally_name'=> '', 'ally_id'=> 0, 'ally_register_time' => ''), array ('id =?' => $user['id']));
                            $page = MessageForm($allyRow['ally_name'], $lang['Alliance_AllyLeaved'], 'alliance.php', $lang['Alliance_Ok']);
                    } else {
                            $page = MessageForm($allyRow['ally_name'], $lang['Alliance_WantLeaveQuestion'], 'alliance.php?mode=exit', $lang['Alliance_Ok']);
                    }

                    display($page, $lang['Alliance_LeaveAlly']);

            } else
                    message($lang['Alliance_OwnerCantLeave'], $lang['Alliance_LeaveAlly']);

            break;

    case 'memberslist':

        if ($userRank['memberlist']) {

            $arraySort = array ('onlinetime', 'username', 'ally_register_time');
            $sortBy = (isset($_GET['sortby']) && in_array($_GET['sortby'], $arraySort)) ? $_GET['sortby'] : 'id';

            $sortOrderNumber = ( isset($_GET['sortorder'])) ? (int) $_GET['sortorder'] : '1';
                $sortOrder = ($sortOrderNumber == 2) ? ' DESC' : ' ASC';

            $allyMembers = $readConnection->select()
               ->from($readConnection->getDeprecatedTable('users'))
               ->where('ally_id =?', $allyRow['id'])
                ->order("$sortBy $sortOrder")
               ->query()->fetchAll()
              ;
            
            $sql = $readConnection->select()
                ->from($readConnection->getDeprecatedTable('statpoints'), array ('id_owner', 'total_points'))
                ->where('id_ally =?', $allyRow['id'])
            ;
            
                $allyUsersPoints = $readConnection->fetchPairs($sql);

            $i = 0; $template = gettemplate('alliance_memberslist_row'); $pageList = '';
            foreach ($allyMembers as $id => $allyUser) {
                $row = array();

                $row['u'] = $i++;
                $row['username'] = $allyUser['username'];
                $row['id'] = $allyUser['id'];

                if ($userRank['onlinestatus']){
                    if ($allyUser['onlinetime'] + 600 >= time())
                        $row['onlinetime'] = "lime>{$lang['Alliance_Online']}<";
                    elseif ($allyUser['onlinetime'] + 1200 >= time())
                        $row['onlinetime'] = "yellow>{$lang['Alliance_15minAgo']}<";
                    else
                        $row['onlinetime'] = "red>{$lang['Alliance_Offline']}<";
                } else
                    $row['onlinetime'] = 'orange>-<';

                $row['ally_range'] = $allyRank[$allyUser['ally_rank_id']]['name'];

                $row['dpath']	= $dpath;
                $row['points']	= $allyUsersPoints[$allyUser['id']];

                $row['galaxy'] = $allyUser['galaxy'];
                $row['system'] = $allyUser['system'];
                $row['planet'] = $allyUser['planet'];

                $row['ally_register_time'] = ($allyUser['ally_register_time'] > 0) ? date("Y-m-d h:i:s", $allyUser['ally_register_time']) : '-';

                $pageList .= parsetemplate($template, $row);
                        unset($row);
            }

            if ($i != $allyRow['ally_members']) {
                $readConnection->update($readConnection->getDeprecatedTable('alliance'), array ('ally_members' => $i), array ('id =?' => $allyRow['id']));
            }

            $parse = $lang;
                $parse['i'] = $i;
                $parse['s'] = ($sortOrderNumber == 2) ? 1 : 2;
                $parse['list'] = $pageList;

            $page = parsetemplate(gettemplate('alliance_memberslist_table'), $parse);
                display($page, $lang['Alliance_MembersList']);

        } else
            message($lang['Alliance_AccessDenied'], $lang['Alliance_MembersList']);

        break;

    case 'circular':

        if ($userRank['mails']) {

            if ($_POST) {
                $messageInfos = array (
                    'to'        => (isset ($_POST['r'])) ? (int) $_POST['r'] : 0,
                    'message'	=> ( isset($_POST['text']) ) ? mysql_real_escape_string($_POST['text']) : 'No message'
                 );

                 if ($messageInfos['to'] == 0) {
                    $sql = $readConnection->select()
                        ->from($readConnection->getDeprecatedTable('users'), array ('id' => 'id', 'username' => 'username'))
                        ->where('ally_id =?', $allyRow['id'])
                     ;
                 } else {
                    $sql = $readConnection->select()
                        ->from($readConnection->getDeprecatedTable('users'), array ('id' => 'id', 'username' => 'username'))
                        ->where('ally_id =?', $allyRow['id'])
                        ->where('ally_rank_id =?', $messageInfos['to'])
                      ;
                 }
                $sendUsersArray = $readConnection->fetchPairs($sql);

                if (!empty($sendUsersArray)) {

                    $list = '';
                    $messageArray = array (
                        'message_sender' => $user['id'],
                        'message_time' => new Zend_Db_Expr('UNIX_TIMESTAMP()'),
                        'message_type' => 2,
                        'message_from' => $allyRow['ally_tag'],
                        'message_subject' => $user['username'],
                        'message_text' => $messageInfos['message'],
                    );
                                    
                    foreach ($sendUsersArray as $id => $username) {
                        $messageArray['message_owner'] = $id;
                        $readConnection->insert($readConnection->getDeprecatedTable('messages'), $messageArray);
                        $list .= "<br> {$username}";
                    }

                    $page = MessageForm($lang['Alliance_CircularSended'], $list, "alliance.php", $lang['Alliance_Ok'], true);

                } else
                    $page = MessageForm($lang['Alliance_NoMessage'], $lang['Alliance_NoRecipient'], 'alliance.php', $lang['Alliance_Ok']);

                    display($page, $lang['Alliance_SendCircular']);
            }

            $lang['r_list'] = "<option value=\"0\">{$lang['Alliance_AllPlayers']}</option>";

            foreach($allyRank as $id => $array) {
                $lang['r_list'] .= "<option value=\"{$id}\">" . $array['name'] . "</option>";
            }

            $page = parsetemplate(gettemplate('alliance_circular'), $lang);
                display($page, $lang['Alliance_SendCircular']);

        } else {
            message($lang['Alliance_AccessDenied'], $lang['Alliance_SendCircular']);
        }

        break;

    case 'admin':
            $adminArray = array('rights', 'give', 'members', 'requests', 'editName', 'destroy');
            $admin = (isset($_GET['edit']) && in_array($_GET['edit'], $adminArray)) ? $_GET['edit'] : '';

            switch ($admin) {

            case 'rights':

                    if ($userRank['rightHand']) {

                            if (isset($_POST['newrangname'])) {

                                    $rankName = mysql_escape_string($_POST['newrangname']);

                                    $allyRank[] = array(
                                            'name' => $rankName, 'mails' => 0, 'kick' => 0, 'seeRequest' => 0,
                                            'admin' => 0, 'adminRequest' => 0, 'memberlist' => 0, 'onlinestatus' => 0, 'rightHand' => 0
                                    );

                                    $readConnection->update($readConnection->getDeprecatedTable('alliance'), array ('ally_ranks' => serialize($allyRank)), array ('id =?' => $allyRow['id']));
                                            header('Location: alliance.php?mode=admin&edit=rights');
                                                    die();
                    } elseif ( isset($_POST['id']) && is_array($_POST['id'])) {

                                    foreach ($_POST['id'] as $id) {

                                            $allyRank[$id]['kick'] = (isset($_POST['u' . $id . 'r1'])) ? 1 : 0;
                                            $allyRank[$id]['seeRequest'] = (isset($_POST['u' . $id . 'r2'])) ? 1 : 0;
                                            $allyRank[$id]['memberlist'] = (isset($_POST['u' . $id . 'r3'])) ? 1 : 0;
                                            $allyRank[$id]['adminRequest'] = (isset($_POST['u' . $id . 'r4'])) ? 1 : 0;
                                            $allyRank[$id]['admin'] = (isset($_POST['u' . $id . 'r5'])) ? 1 : 0;
                                            $allyRank[$id]['onlinestatus'] = (isset($_POST['u' . $id . 'r6'])) ? 1 : 0;
                                            $allyRank[$id]['mails'] = (isset($_POST['u' . $id . 'r7'])) ? 1 : 0;
                                            $allyRank[$id]['rightHand'] = (isset($_POST['u' . $id . 'r8'])) ? 1 : 0;

                                    }
                                    $readConnection->update($readConnection->getDeprecatedTable('alliance'), array ('ally_ranks' => serialize($allyRank)), array ('id =?' => $allyRow['id']));
                                            header('Location: alliance.php?mode=admin&edit=rights');
                                            die();
                            }

                            if ( isset($_GET['d'])) {
                                    $deleteRank = (int) $_GET['d'];
                                    if ( isset($allyRank[$deleteRank]) && $userRank['admin'] == 1 && $userRank['name'] != $allyRank[$deleteRank]['name'] ) {
                                            unset($allyRank[$deleteRank]);
                                            $readConnection->update($readConnection->getDeprecatedTable('alliance'), array ('ally_ranks' => serialize($allyRank)), array ('id =?' => $allyRow['id']));
                                            header('Location: alliance.php?mode=admin&edit=rights');
                                                    die();
                                    }
                            }

                            if ( empty($allyRank) ) {
                                    $list = "<th>{$lang['Alliance_There_is_not_range']}</th>";
                            } else {
                                    $list = parsetemplate(gettemplate('alliance_admin_laws_head'), $lang);
                                    $template = gettemplate('alliance_admin_laws_row');

                                    foreach ($allyRank as $id => $rank) {
                                            $lang['id'] = $id;
                                            $lang['delete'] = ($rank['name'] != $userRank['name']) ?
                                                    "<a href=\"alliance.php?mode=admin&edit=rights&d={$id}\"><img src=\"{$dpath}pic/abort.gif\" alt=\"{$lang['Alliance_DeleteRange']}\" border=0></a>" : '&nbsp;';
                                            $lang['a'] = $id;

                                            $lang['r0'] = $rank['name'];
                                            $lang['r1'] = "<input type=checkbox name=\"u{$id}r1\"" . (($rank['kick'] == 1)?' checked="checked"':'') . ">";
                                            $lang['r2'] = "<input type=checkbox name=\"u{$id}r2\"" . (($rank['seeRequest'] == 1)?' checked="checked"':'') . ">";
                                            $lang['r3'] = "<input type=checkbox name=\"u{$id}r3\"" . (($rank['memberlist'] == 1)?' checked="checked"':'') . ">";
                                            $lang['r4'] = "<input type=checkbox name=\"u{$id}r4\"" . (($rank['adminRequest'] == 1)?' checked="checked"':'') . ">";
                                            $lang['r5'] = "<input type=checkbox name=\"u{$id}r5\"" . (($rank['admin'] == 1)?' checked="checked"':'') . ">";
                                            $lang['r6'] = "<input type=checkbox name=\"u{$id}r6\"" . (($rank['onlinestatus'] == 1)?' checked="checked"':'') . ">";
                                            $lang['r7'] = "<input type=checkbox name=\"u{$id}r7\"" . (($rank['mails'] == 1)?' checked="checked"':'') . ">";
                                            $lang['r8'] = "<input type=checkbox name=\"u{$id}r8\"" . (($rank['rightHand'] == 1)?' checked="checked"':'') . ">";
                                                    $list .= parsetemplate($template, $lang);
                                    }

                                    $list .= parsetemplate(gettemplate('alliance_admin_laws_feet'), $lang);
                            }

                            $lang['list'] = $list;
                            $lang['dpath']= $user['dpath'];

                            $page = parsetemplate(gettemplate('alliance_admin_laws'), $lang);
                                            display($page, $lang['Alliance_SettingRights']);

                    } else {
                            message($lang['Alliance_AccessDenied'], $lang['Alliance_SettingRights']);
                    }

                    break;

    case 'members':
                    if ($userRank['admin']) {

                            if ( isset($_GET['kick'])) {

                                    $kick = (int) $_GET['kick'];
                                $userExist = $readConnection->select()
                                   ->from($readConnection->getDeprecatedTable('users'), array ('id' => 'id', 'ally_id' => 'ally_id'))
                                   ->where('id =?', $kick)
                                   ->query()->fetch()
                                 ;

                                    if ($userExist['ally_id'] == $allyRow['id']) {
                    $readConnection->update($readConnection->getDeprecatedTable('users'), array ('ally_id' => '0'), array ('id =?' => $kick));
                }

            } elseif ( isset($_GET['id']) && isset($_POST['newrang'])) {

                                    $id = (int) $_GET['id'];
                                    $newRang = (int) $_POST['newrang'];

                                $userExist = $readConnection->select()
                                   ->from($readConnection->getDeprecatedTable('users'), array ('username' => 'id', 'ally_id' => 'ally_id'))
                                   ->where('id =?', $kick)
                                   ->query()->fetch()
                                 ;
                                    if ($userExist && isset($allyRank[$newRang]['name'])) {
                                        $readConnection->update($readConnection->getDeprecatedTable('users'), array ('ally_rank_id' => $newRang), array ('id =?' => $id));
                                    }
                            }

                            $template = gettemplate('alliance_admin_members_row');
                            $functionTemplate = gettemplate('alliance_admin_members_function');

                            $arraySort = array ('onlinetime', 'username', 'ally_register_time');
                                    $sortBy = (isset($_GET['sortby']) && in_array($_GET['sortby'], $arraySort)) ? $_GET['sortby'] : 'id';

                        $sortOrderNumber = ( isset($_GET['sortorder'])) ? (int) $_GET['sortorder'] : '1';
                                    $sortOrder = ($sortOrderNumber == 2) ? ' DESC' : ' ASC';

                        $allyMembers = $readConnection->select()
                            ->from($readConnection->getDeprecatedTable('users'))
                            ->where('ally_id =?', $allyRow['id'])
                            ->order("$sortBy $sortOrder")
                            ->query()->fetchAll()
                         ;

                            $rank = (isset($_GET['rank'])) ? (int) $_GET['rank'] : 0;

                    $sql = $readConnection->select()
                       ->from($readConnection->getDeprecatedTable('statpoints'), array ('id_owner' => 'id_owner', 'total_points' => 'total_points'))
                       ->where('id_ally =?', $allyRow['id'])
                     ;
                    $allyUsersPoints = $readConnection->fetchPairs($sql);

                            $i = 0; $pageList = '';
                            foreach ($allyMembers as $id => $allyUser) {
                                    $row = array();

                                    $row['dpath'] = $dpath;
                                    $row['points'] = $allyUsersPoints[$allyUser['id']];
                                    $row['u'] = $i++;
                                    $row['username'] = $allyUser['username'];
                                    $row['id'] = $allyUser['id'];

                            $days = floor(round(time() - $allyUser['onlinetime']) / 3600 % 24);
                                    $row['onlinetime'] = $days . ' d';

                                    $row['ally_range'] = ( isset($allyRank[$allyUser['ally_rank_id']]) ) ? $allyRank[$allyUser['ally_rank_id']]['name'] : $lang['Alliance_Novate'];
                                    $row['ally_register_time'] = ($allyUser['ally_register_time'] > 0) ? date("Y-m-d h:i:s", $allyUser['ally_register_time']) : '-';

                                    $row['galaxy'] = $allyUser['galaxy'];
                                    $row['system'] = $allyUser['system'];
                                    $row['planet'] = $allyUser['planet'];

                                    if ($userRank['kick'] && $allyUser['id'] != $allyRow['ally_owner']) {
                                            $f = $lang;
                                            $f['dpath'] = $dpath;
                                            $f['id'] = $allyUser['id'];
                                            $row['functions'] = parsetemplate($functionTemplate, $f);
                            } else
                                            $row['functions'] = '';

                                    $pageList .= parsetemplate($template, $row);
                                            unset($row);

                                     if ($rank == $allyUser['id']) {
                                            $r = $lang;
                                            $r['Rank_for'] = sprintf($lang['Alliance_RankFor'], $allyUser['username']);
                                            $r['options'] = "<option value=\"0\">{$lang['Alliance_Novate']}</option>";

                                            foreach($allyRank as $rankId => $rankArray) {
                                                    $r['options'] .= "<option value=\"{$rankId}\"";
                                                    $r['options'] .= ($allyUser['ally_rank_id'] == $rankId) ? ' selected=selected' : '';
                                                    $r['options'] .= ">{$rankArray['name']}</option>";
                                            }
                                            $r['Save'] = $lang['Alliance_Save'];
                                            $r['id'] = $allyUser['id'];
                                            $pageList .= parsetemplate(gettemplate('alliance_admin_members_row_edit'), $r);

                                     }
                            }

                            $parse = $lang;
                                    $lang['memberslist'] = $pageList;
                                    $lang['s'] = ($sortOrderNumber == 2) ? 1 : 2;

                                    $page = parsetemplate(gettemplate('alliance_admin_members_table'), $lang);
                                        display($page, $lang['Alliance_AdminMembersList']);

                    } else
                            message($lang['Alliance_AccessDenied'], $lang['Alliance_AdminMembersList']);

                    break;

            case 'requests':

                    if ($userRank['adminRequest']) {

                            $showRequest = (isset($_GET['show'])) ? (int) $_GET['show'] : 0 ;
                            $parse = $lang;

                            if ($showRequest != 0 && isset($_POST['action'])) {

                                $text = mysql_real_escape_string($_POST['text']);

                                $userCheck = $readConnection->select()
                                    ->from($readConnection->getDeprecatedTable('users'), array ('username' => 'username', 'ally_request' => 'ally_request'))
                                    ->where('id =?', $showRequest)
                                    ->query()->fetch()
                                ;

                                    if ($userCheck['ally_request'] != $allyRow['id'])
                                            $userCheck = false;

                                    if ($userCheck) {

                                            if ($_POST['action'] == $lang['Alliance_Accept']) {

                                                $userRequestAnswer = array (
                                                    'ally_name' => $allyRow['ally_name'],
                                                    'ally_request_text' => '',
                                                    'ally_request' => '0',
                                                    'ally_rank_id' => '0',
                                                    'ally_id' => $allyRow['id'],
                                                    'new_message' => 'new_message+1',
                                                    'mnl_alliance' => 'mnl_alliance+1'
                                                  );

                                            $messageText = 	"Hi!<br>L'Alliance <b>{$allyRow['ally_name']}</b> a acceptee votre candidature! Message : {$text}.";
                                                    $subject = "[{$allyRow['ally_name']}] {$lang['Alliance_RequestAccepted']}";

                                            } elseif ($_POST['action'] == $lang['Alliance_Refuse']) {

                                                $userRequestAnswer = array (
                                                    'ally_request_text' => '',
                                                    'ally_request' => '0',
                                                    'new_message' => 'new_message+1',
                                                    'mnl_alliance' => 'mnl_alliance+1'
                                                  );

                                                    $messageText = "Hi!<br>L'Alliance <b>{$ally['ally_name']}</b> a refusee votre candidature! Text:<br> {$text}.";
                                                    $subject = "[{$allyRow['ally_name']}] {$lang['Alliance_RequestRefused']}";
                                            }

                                            $messageData = array (
                                                'message_owner' => $showRequest,
                                                'message_sender' => $user['id'],
                                                'message_time' => new Zend_Db_Expr('UNIX_TIMESTAMP()'),
                                                'message_type' => '2',
                                                'message_from' => $allyRow['ally_tag'],
                                                'message_subject' => $subject,
                                                'message_text' => $messageText
                                              );
                                            $readConnection->insert($readConnection->getDeprecatedTable('messages'), $messageData);
                                            $readConnection->update($readConnection->getDeprecatedTable('users'), $userRequestAnswer, array ('id =?' => $showRequest));

                                            header('Location:alliance.php?mode=admin&edit=requests');
                                                    die();
                                    }
                            }

                            $i = 0; $row = gettemplate('alliance_admin_request_row');

                            $sql = $readConnection->select()
                               ->from($readConnection->getDeprecatedTable('users'), array(
                                   'id' => 'id',
                                   'username' => 'username',
                                   'ally_request_text' => 'ally_request_text',
                                   'ally_register_time' => 'ally_register_time'))
                               ->where('ally_request =?', $allyRow['id'])
                              ;

            $allyRequest = $readConnection->fetchAssoc($sql);

                            foreach ($allyRequest as $id => $userRequest) {

                                    if ($userRequest['id'] == $showRequest) {
                                            $request['username'] = $userRequest['username'];
                                            $request['ally_request_text'] = nl2br($userRequest['ally_request_text']);
                                    }

                                    $displayRequests['id'] = $userRequest['id'];
                                    $displayRequests['username'] = $userRequest['username'];
                                    $displayRequests['time'] = date("Y-m-d h:i:s", $userRequest['ally_register_time']);
                                    $parse['list'] .= parsetemplate($row, $displayRequests);

                            $i++;

                            }

                            $parse['list'] = ($i == 0) ? '<tr><th colspan=2>Il ne reste plus aucune candidature</th></tr>' : $parse['list'];

                            if ($showRequest !== 0 && isset($request['username'])) {
                                $parse['requestFrom'] = sprintf($lang['Alliance_RequestFrom'], $request['username']);
                                    $parse['allyRequestText'] = $request['ally_request_text'];
                                    $parse['id'] = $showRequest;
                                    $parse['request'] = parsetemplate(gettemplate('alliance_admin_request_form'), $parse);
                                    $parse['request'] = parsetemplate($parse['request'], $lang);
                            } else
                                    $parse['request'] = '';

                            $parse['ally_tag'] = $allyRow['ally_tag'];
                            $parse['RequestsNumber'] = $i;

                            $page = parsetemplate(gettemplate('alliance_admin_request_table'), $parse);
                                    display($page, $lang['Alliance_AdminRequests']);

                    } else
                                            message($lang['Alliance_AccessDenied'], $lang['Alliance_AdminRequests']);

                    break;

            case 'editName':

                    if ($userRank['admin']) {

                            if ( isset($_POST['newtag']) || isset($_POST['newname'])) {

                                    $allyRow['ally_tag'] = (isset($_POST['newtag'])) ? mysql_real_escape_string($_POST['newtag']) : $allyRow['ally_tag'];
                            $allyRow['ally_name'] = (isset($_POST['newname'])) ? mysql_real_escape_string($_POST['newname']) : $allyRow['ally_name'];

                    $readConnection->update($readConnection->getDeprecatedTable('alliance'), $allyRow, array ('id =?' => $allyRow['id']));
                            }

                            $lang['allyName'] = $allyRow['ally_name'];
                            $lang['allyTag'] = $allyRow['ally_tag'];

                            $page = parsetemplate(gettemplate('alliance_admin_rename'), $lang);
                                    display($page, $lang['Alliance_RenameAlly']);

                    } else
                            message($lang['Alliance_AccessDenied'], $lang['Alliance_RenameAlly']);

                    break;

    case 'destroy':

        if ($user['id'] == $allyRow['ally_owner']) {
            $allyDestroy = array (
                                    'ally_name'=> '',
                                    'ally_request' => '0',
                                    'ally_id'=> 0,
                                    'ally_register_time' => '',
                                    'ally_rank_id' => '0'
            );

            $readConnection->update($readConnection->getDeprecatedTable('users'), $allyDestroy, array ('ally_id =?' => $allyRow['id']));
            $readConnection->update($readConnection->getDeprecatedTable('statpoints'), array ('id_ally' => 0), array ('id_ally =?' => $allyRow['id']));
            $readConnection->delete($readConnection->getDeprecatedTable('alliance'), array ('id =?' => $allyRow['id']));

                header('Location: alliance.php');
                   die();

        } else
            message($lang['Alliance_AccessDenied'], $lang['Alliance_DestroyAlly']);

            break;

            case 'give':

                    if ($user['id'] == $allyRow['ally_owner']) {

                            if (isset($_POST['id'])) {
                                    $newOwner = (int) $_POST['id'];
                                    $readConnection->update($readConnection->getDeprecatedTable('alliance'), array ('ally_owner' => $newOwner), array ('id =?' => $allyRow['id']));
                                    $readConnection->update($readConnection->getDeprecatedTable('users'), array ('ally_rank_id' => 1), array ('id =?' => $newOwner));
                                            message($lang['Alliance_AllyGiven'], $lang['Alliance_TransferSuccess']);
                            }

                            $sql = $readConnection->select()
                               ->from($readConnection->getDeprecatedTable('users'), array ('id' => 'id', 'username' => 'username'))
                               ->where('ally_id =?', $allyRow['id'])
                             ;
                $allyMember = $readConnection->fetchPairs($sql);

            $select = '';
            foreach ($allyMember as $id => $username) {
                                $select .= "<option value=\"{$id}\">{$username}</option>";
                            }

                            $page = <<<EOF
                                    <form method="post" action="alliance.php?mode=admin&edit=give"><table width="600" border="0" cellpadding="0" cellspacing="1" align="center">
                                    <tr><td class="c" colspan="4" align="center">{$lang['Alliance_WhoDoYouWant']}</td></tr>
                                    <tr><th colspan=\"3\">{$lang['Alliance_ChooseThePlayer']}</th><th colspan=\"1\"><select name="id">{$select}</select></th></tr>
                                    <tr><th colspan="4"><input type="submit" value="{$lang['Alliance_Give']}"></th></tr>
EOF;
                    }
                                    //break intentionally omitted

            default:

                    if ($userRank['admin']) {

                            $textFor = (isset($_GET['t'])) ? (int) $_GET['t'] : 1;

                            if ($_POST) {

                                    if (isset($_POST['options'])) {

                                            $option = array (
                                                    'ally_owner_range'	=> (isset($_POST['owner_range'])) ? mysql_escape_string(strip_tags($_POST['owner_range'])) : '',
                                                    'ally_web'		=> (isset($_POST['web'])) ? mysql_escape_string(strip_tags($_POST['web'])) : '',
                                                    'ally_image'		=> (isset($_POST['image'])) ?  mysql_escape_string(strip_tags($_POST['image'])) : '',
                                                    'ally_request_notallow'	=> (isset($_POST['request_notallow'])) ?  mysql_escape_string(strip_tags($_POST['request_notallow'])) : '',
                                            );

                                            $readConnection->update($readConnection->getDeprecatedTable('alliance'), $option, array ('id =?' => $allyRow['id']));

                                    } elseif (isset($_POST['t'])) {

                                            $text = htmlspecialchars($_POST['text']);

                                            if ($textFor == 3) {
                                                    $allyRow['ally_request'] = $text;
                                                    $subQuery = array ('ally_request' => $text);
                                            } elseif ($textFor == 2) {
                                                    $allyRow['ally_text'] = $text;
                                                    $subQuery = array ('ally_text' => $text);
                                            } elseif ($textFor == 1) {
                                                    $allyRow['ally_description'] = $text;
                                                    $subQuery = array ('ally_description' => $text);
                                            } else
                                                    die();

                                            $readConnection->update($readConnection->getDeprecatedTable('alliance'), $subQuery, array ('id =?' => $allyRow['id']));

                                    }

                            }

                            if ($textFor == 3) {
                                    $lang['text'] = $allyRow['ally_request'];
                                    $lang['typeText'] = $lang['Alliance_RequestText'];
                            } elseif ($textFor == 2) {
                                    $lang['text'] = $allyRow['ally_text'];
                                    $lang['typeText'] = $lang['Alliance_InternalText'];
                            } else {
                                    $lang['text'] = $allyRow['ally_description'];
                                    $lang['typeText'] = $lang['Alliance_ExternalText'];
                            }

                            $lang['t'] = $textFor;

                            $lang['ally_web'] = (isset($option['allyWeb'])) ? $option['allyWeb'] : $allyRow['ally_web'];
                            $lang['ally_image'] = (isset($option['allyImage'])) ? $option['allyImage'] : $allyRow['ally_image'];
                            $lang['ally_request_notallow_0'] = ($allyRow['ally_request_notallow'] == 1) ? ' selected="selected"' : '';
                            $lang['ally_request_notallow_1'] = ($allyRow['ally_request_notallow'] == 0) ? ' selected="selected"' : '';
                            $lang['ally_owner_range'] = (isset($option['ownerRange'])) ? $option['ownerRange'] : $allyRow['ally_owner_range'];
                            $lang['Transfer_alliance'] = MessageForm($lang['Alliance_GiveAlly'], "", "?mode=admin&edit=give", $lang['Alliance_Continue']);
                            $lang['Disolve_alliance'] = MessageForm($lang['Alliance_DestroyAlly'], "", "?mode=admin&edit=destroy", $lang['Alliance_Continue']);

                            $page .= parsetemplate(gettemplate('alliance_admin'), $lang);
                                    display($page, $lang['Alliance_AdministrationAlly']);

                    } else
                            message($lang['Alliance_AccessDenied'], $lang['Alliance_AdministrationAlly']);

                    break;
                    }

            break;

    default:

            $parse = $lang;

            if ($allyRow['ally_image'] !== '') {
                    $parse['ally_image'] = "<tr><td colspan=2><img src=\"{$allyRow['ally_image']}\"></td></tr>";
            }

            $parse['ally_members'] = $allyRow['ally_members'];
            $parse['ally_members'] .= ($userRank['memberlist']) ? " (<a href=\"?mode=memberslist\">{$lang['Alliance_MembersList']}</a>)" : '';

            $parse['ally_tag'] = $allyRow['ally_tag'];
            $parse['ally_name'] = $allyRow['ally_name'];
            $parse['range'] = $userRank['name'];
            $parse['range'] .= ($userRank['admin']) ? " (<a href=\"?mode=admin&edit=ally\">{$lang['Alliance_AdminLink']}</a>)" : '';

            $parse['send_circular_mail'] = ($userRank['mails']) ? "<tr><th>{$lang['Alliance_CircularMessage']}</th><th><a href=\"?mode=circular\">{$lang['Alliance_SendCircularMail']}</a></th></tr>" : '';
            $parse['ally_web'] = $allyRow['ally_web'];

            if ($userRank['seeRequest']) {
                $sql = $readConnection->select()
                   ->from($readConnection->getDeprecatedTable('users'), array ('id' => 'id'))
                   ->where('ally_request =?', $allyRow['id'])
                 ;
                $userRequests = $readConnection->fetchAll($sql);

                    if (count($userRequests) !== 0) {
                            $parse['requests'] = "<tr><th>{$lang['Alliance_Requests']}</th>
                                    <th><a href=\"alliance.php?mode=admin&edit=requests\">".count($userRequests)."</a></th></tr>";
                    }
            }

            $parse['ally_description'] = bbcode($allyRow['ally_description']);
            $parse['ally_text'] = bbcode($allyRow['ally_text']);

            $parse['exit'] = ($user['id'] != $allyRow['ally_owner']) ?
                    "<th colspan=\"2\"><a href=\"alliance.php?mode=exit\">{$lang['Alliance_LeaveAlly']}</a></th>" : '';

            $page = parsetemplate(gettemplate('alliance_frontpage'), $parse);
                    display($page, $lang['Alliance_YourAlliance']);

            break;
    }

} 
