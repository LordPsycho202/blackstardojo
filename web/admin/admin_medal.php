<?php
 //require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                             admin_medal.php
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

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Medals']['Manage'] = "$file";
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);


if( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ($HTTP_GET_VARS['mode']) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else 
{
	//
	// These could be entered via a form button
	//
	if( isset($HTTP_POST_VARS['add']) )
	{
		$mode = "add";
	}
	else if( isset($HTTP_POST_VARS['save']) )
	{
		$mode = "save";
	}
	else
	{
		$mode = "";
	}
}

if( $mode != "" )
{
	if( $mode == "edit" || $mode == "add" )
	{
		$medal_id = ( isset($HTTP_GET_VARS['id']) ) ? intval($HTTP_GET_VARS['id']) : 0;
		$s_hidden_fields = "";
		
		if( $mode == "edit" )
		{
			if( empty($medal_id) )
			{
				message_die(GENERAL_MESSAGE, $lang['Must_select_medal']);
			}

			$sql = "SELECT * FROM " . MEDAL_TABLE . "
				WHERE medal_id = $medal_id";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't obtain medal data", "", __LINE__, __FILE__, $sql);
			}
			
			$medal_info = $db->sql_fetchrow($result);
			$s_hidden_fields .= '<input type="hidden" name="id" value="' . $medal_id . '" />';

		}

		$s_hidden_fields .= '<input type="hidden" name="mode" value="save" />';

		$template->set_filenames(array(
			"body" => "admin/medals_edit_body.tpl")
		);

		$template->assign_vars(array(
			"MEDAL_NAME" => $medal_info['medal_name'],
			"MEDAL_DESCRIPTION" => $medal_info['medal_description'],
			"IMAGE" => ( $medal_info['medal_image'] != "" ) ? $medal_info['medal_image'] : "",
			"IMAGE_DISPLAY" => ( $medal_info['medal_image'] ) ? '<img src="../' . $medal_info['medal_image'] . '" />' : "",
			
			"L_MEDAL_TITLE" => $lang['Medal_admin'],
			"L_MEDAL_EXPLAIN" => $lang['Medal_admin_explain'],
			"L_MEDAL_NAME" => $lang['medal_name'],
			"L_MEDAL_DESCRIPTION" => $lang['medal_description'],
			"L_MEDAL_IMAGE" => $lang['medal_image'],
			"L_MEDAL_IMAGE_EXPLAIN" => $lang['medal_image_explain'],
			"L_SUBMIT" => $lang['Submit'],
			"L_RESET" => $lang['Reset'],
			
			"S_MEDAL_ACTION" => append_sid("admin_medal.$phpEx"),
			"S_HIDDEN_FIELDS" => $s_hidden_fields)
		);
	}
	else if( $mode == "save" )
	{
		//
		// Ok, they sent us our info, let's update it.
		//
		$medal_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : 0;
		$medal_name = ( isset($HTTP_POST_VARS['medal_name']) ) ? trim($HTTP_POST_VARS['medal_name']) : "";
		$medal_description = ( isset($HTTP_POST_VARS['medal_description']) ) ? trim($HTTP_POST_VARS['medal_description']) : "";
		$medal_image = ( (isset($HTTP_POST_VARS['medal_image'])) ) ? trim($HTTP_POST_VARS['medal_image']) : "";

		if ( $medal_name == '' )
		{
			message_die(GENERAL_MESSAGE, $lang['No_medal_name']);
		}

		if ( $medal_description == '' )
		{
			message_die(GENERAL_MESSAGE, $lang['No_medal_description']);
		}

		if ( $medal_image == '' )
		{
			message_die(GENERAL_MESSAGE, $lang['No_medal_image']);
		}

		if( $medal_image != '' )
		{
			if ( !preg_match("/(\.gif|\.png|\.jpg)$/is", $medal_image))
			{
				$medal_image = '';
			}
		}

		if ($medal_id)
		{			
			$sql = "UPDATE " . MEDAL_TABLE . "
				SET medal_name = '" . str_replace("\'", "''", $medal_name) . "', medal_description = '" . str_replace("\'", "''", $medal_description) . "', medal_image = '" . str_replace("\'", "''", $medal_image) . "'
				WHERE medal_id = $medal_id";

			$message = $lang['Updated_medal'];
		}
		else
		{
			$sql = "INSERT INTO " . MEDAL_TABLE . " (medal_name, medal_description, medal_image)
				VALUES ('" . str_replace("\'", "''", $medal_name) . "', '" . str_replace("\'", "''", $medal_description) . "', '" . str_replace("\'", "''", $medal_image) . "')";

			$message = $lang['Added_new_medal'];
		}
		
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't update/insert into medal table", "", __LINE__, __FILE__, $sql);
		}

		$message .= "<br /><br />" . sprintf($lang['Click_return_medaladmin'], "<a href=\"" . append_sid("admin_medal.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);

	}
	else if( $mode == "delete" )
	{	
		if( isset($HTTP_POST_VARS['id']) || isset($HTTP_GET_VARS['id']) )
		{
			$medal_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);
		}
		else
		{
			$medal_id = 0;
		}
		
		if( $medal_id )
		{
			$sql = "DELETE FROM " . MEDAL_TABLE . "
				WHERE medal_id = $medal_id";
			
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete medal data", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Deleted_medal'] . "<br /><br />" . sprintf($lang['Click_return_medaladmin'], "<a href=\"" . append_sid("admin_medal.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Must_select_medal']);
		}
	}
	else if( $mode == "moderator" )
	{	
		$medal_id = ( isset($HTTP_GET_VARS['id']) ) ? intval($HTTP_GET_VARS['id']) : 0;
		$s_hidden_fields = "";

		if( empty($medal_id) )
		{
			message_die(GENERAL_MESSAGE, $lang['Must_select_medal']);
		}

		$s_hidden_fields .= '<input type="hidden" name="id" value="' . $medal_id . '" />';

		$sql = "SELECT * FROM " . MEDAL_TABLE . "
			WHERE medal_id = $medal_id";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't obtain medal data", "", __LINE__, __FILE__, $sql);
		}
			
		$medal_info = $db->sql_fetchrow($result);

		$sql = "SELECT m.mod_id, u.user_id, u.username
			FROM " . MEDAL_MOD_TABLE . " m, " . USERS_TABLE . " u
			WHERE u.user_id = m.user_id
				AND m.medal_id = $medal_id
				AND m.user_id <> 0
				AND u.user_id <> " . ANONYMOUS . "
			ORDER BY u.user_id ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not select current user_id medal moderator list', '', __LINE__, __FILE__, $sql);
		}

		$user_list = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		
		$userlist = '';
		$select_userlist = '';
		for($i = 0; $i < count($user_list); $i++)
		{
			$select_userlist .= '<option value="' . $user_list[$i]['mod_id'] . '">' . $user_list[$i]['username'] . '</option>';
			$userlist .= ( $userlist != '' ) ? ', ' . '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $user_list[$i]['user_id']) . '">' . $user_list[$i]['username'] . '</a>' : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $user_list[$i]['user_id']) . '">' . $user_list[$i]['username'] . '</a>';
		}

		$userlist = ( $userlist ) ? $userlist : $lang['No_medal_mod'];

		if( $select_userlist == '' )
		{
			$select_userlist = '<option value="-1">' . $lang['No_medal_mod'] . '</option>';
		}

		$select_userlist = '<select name="unmod_user[]" multiple="multiple" size="5" style="width: 250px;">' . $select_userlist . '</select>';
	
		$s_hidden_fields .= '<input type="hidden" name="mode" value="submit" />';

		$template->set_filenames(array(
			"body" => "admin/medals_moderator_body.tpl")
		);

		$template->assign_vars(array(
			"MEDAL_NAME" => $medal_info['medal_name'],
			"MEDAL_DESCRIPTION" => $medal_info['medal_description'],
			"MEDAL_MODERATORS" => $userlist,
			
			"L_MEDAL_DESCRIPTION" => $lang['medal_description'],
			"L_MEDAL_MOD_TITLE" => $lang['Medal_mod_admin'],
			"L_MEDAL_MOD_EXPLAIN" => $lang['Medal_mod_admin_explain'],
			"L_MEDAL_NAME" => $lang['medal_name'],
			"L_MEDAL_DESCRIPTION" => $lang['medal_description'],
			"L_MEDAL_MOD" => $lang['Medal_mod'],
			"L_MOD_USER" => $lang['Medal_mod_username'],
			"L_UNMOD_USER" => $lang['Medal_unmod_username'],
			"L_UNMOD_USER_EXPLAIN" => $lang['Medal_unmod_username_explain'],
			"L_USERNAME" => $lang['Username'], 
			"L_LOOK_UP" => $lang['Look_up_User'],
			"L_FIND_USERNAME" => $lang['Find_username'],
			'L_SUBMIT' => $lang['Submit'],
			'L_RESET' => $lang['Reset'],
			'U_SEARCH_USER' => append_sid("./../search.$phpEx?mode=searchuser"),
		
			"S_UNMOD_USERLIST_SELECT" => $select_userlist,
			"S_MEDAL_ACTION" => append_sid("admin_medal.$phpEx"),
			"S_HIDDEN_FIELDS" => $s_hidden_fields)
		);
	}
	else if ( isset($HTTP_POST_VARS['submit']) )
	{
		$medal_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : 0;
		$user_modsql = '';

		$user_list = array();
		if ( !empty($HTTP_POST_VARS['username']) )
		{
			$this_userdata = get_userdata(trim($HTTP_POST_VARS['username']), true);
			if( !$this_userdata )
			{
				message_die(GENERAL_MESSAGE, $lang['No_user_id_specified'] );
			}
			$user_list[] = $this_userdata['user_id'];
		}
	
		$sql = "SELECT * FROM " . MEDAL_MOD_TABLE . "
			WHERE medal_id = $medal_id";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't obtain medal moderator list information", "", __LINE__, __FILE__, $sql);
		}

		$current_modlist = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
	
		for($i = 0; $i < count($user_list); $i++)
		{
			$in_modlist = false;

			for($j = 0; $j < count($current_modlist); $j++)
			{
				if ( $user_list[$i] == $current_modlist[$j]['user_id'] )
				{
					$in_modlist = true;
				}
			}

			if ( !$in_modlist )
			{
				$sql = "INSERT INTO " . MEDAL_MOD_TABLE . " (medal_id, user_id)
					VALUES (" . $medal_id . ", " . $user_list[$i] . ")";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't insert user_id info into database", "", __LINE__, __FILE__, $sql);
				}
			}
		}

		$where_sql = '';

		if ( isset($HTTP_POST_VARS['unmod_user']) )
		{
			$user_list = $HTTP_POST_VARS['unmod_user'];

			for($i = 0; $i < count($user_list); $i++)
			{
				if ( $user_list[$i] != -1 )
				{	
					$where_sql .= ( ( $where_sql != '' ) ? ', ' : '' ) . intval($user_list[$i]);
				}
			}
		}

		if ( $where_sql != '' )
		{
			$sql = "DELETE FROM " . MEDAL_MOD_TABLE . "
				WHERE mod_id IN ($where_sql)";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete medal moderator info from database", "", __LINE__, __FILE__, $sql);
			}
		}

		$message = $lang['Medal_mod_update_sucessful'] . '<br /><br />' . sprintf($lang['Click_return_medal_mod_admin'], '<a href="' . append_sid("admin_medal.$phpEx?mode=moderator&id=$medal_id") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		$template->set_filenames(array(
			"body" => "admin/medals_list_body.tpl")
		);
		
		$sql = "SELECT * FROM " . MEDAL_TABLE . "
			ORDER BY medal_name";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't obtain medal data", "", __LINE__, __FILE__, $sql);
		}
		
		$medal_rows = $db->sql_fetchrowset($result);
		$medal_count = count($medal_rows);
		
		$template->assign_vars(array(
			"L_MEDAL_TITLE" => $lang['Medal_admin'],
			"L_MEDAL_EXPLAIN" => $lang['Medal_admin_explain'],
			"L_MEDAL_NAME" => $lang['medal_name'],
			"L_MEDAL_DESCRIPTION" => $lang['medal_description'],
			"L_MEDAL_IMAGE" => $lang['medal_image'],
			"L_MEDAL_MOD" => $lang['Medal_mod'],
			"L_EDIT" => $lang['Edit'],
			"L_DELETE" => $lang['Delete'],
			"L_CREATE_NEW_MEDAL" => $lang['New_medal'],
			
			"S_MEDAL_ACTION" => append_sid("admin_medal.$phpEx"))
		);
		
		for( $i = 0; $i < $medal_count; $i++)
		{
			$medal_name = $medal_rows[$i]['medal_name'];
			$medal_description = $medal_rows[$i]['medal_description'];
			$medal_id = $medal_rows[$i]['medal_id'];
			$medal_image = ( $medal_rows[$i]['medal_image'] ) ? '<img src="' . $phpbb_root_path .'/' . $medal_rows[$i]['medal_image'] . '" border="0" />' : "";
			
			$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
	
			$template->assign_block_vars("medals", array(
				"ROW_COLOR" => "#" . $row_color,
				"ROW_CLASS" => $row_class,
				"MEDAL_NAME" => $medal_name,
				"MEDAL_DESCRIPTION" => $medal_description,
				"MEDAL_IMAGE" => $medal_image,
				
				"U_MEDAL_MOD" => append_sid("admin_medal.$phpEx?mode=moderator&amp;id=$medal_id"),
				"U_MEDAL_EDIT" => append_sid("admin_medal.$phpEx?mode=edit&amp;id=$medal_id"),
				"U_MEDAL_DELETE" => append_sid("admin_medal.$phpEx?mode=delete&amp;id=$medal_id"))
			);
		}
	}
}
else
{
	//
	// Show the default page
	//
	$template->set_filenames(array(
		"body" => "admin/medals_list_body.tpl")
	);
	
	$sql = "SELECT * FROM " . MEDAL_TABLE . "
		ORDER BY medal_name";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain medal data", "", __LINE__, __FILE__, $sql);
	}
	$medal_count = $db->sql_numrows($result);

	$medal_rows = $db->sql_fetchrowset($result);
	
	$template->assign_vars(array(
		"L_MEDAL_TITLE" => $lang['Medal_admin'],
		"L_MEDAL_EXPLAIN" => $lang['Medal_admin_explain'],
		"L_MEDAL_NAME" => $lang['medal_name'],
		"L_MEDAL_DESCRIPTION" => $lang['medal_description'],
		"L_MEDAL_IMAGE" => $lang['medal_image'],
		"L_MEDAL_MOD" => $lang['Medal_mod'],
		"L_EDIT" => $lang['Edit'],
		"L_DELETE" => $lang['Delete'],
		"L_CREATE_NEW_MEDAL" => $lang['New_medal'],
			
		"S_MEDAL_ACTION" => append_sid("admin_medal.$phpEx"))
	);
	
	for($i = 0; $i < $medal_count; $i++)
	{
		$medal_name = $medal_rows[$i]['medal_name'];
		$medal_description = $medal_rows[$i]['medal_description'];
		$medal_id = $medal_rows[$i]['medal_id'];
		$medal_image = ( $medal_rows[$i]['medal_image'] ) ? '<img src="' . $phpbb_root_path .'/' . $medal_rows[$i]['medal_image'] . '" border="0" />' : "";

		$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
		$template->assign_block_vars("medals", array(
			"ROW_COLOR" => "#" . $row_color,
			"ROW_CLASS" => $row_class,
			"MEDAL_NAME" => $medal_name,
			"MEDAL_DESCRIPTION" => $medal_description,
			"MEDAL_IMAGE" => $medal_image,
			
			"U_MEDAL_MOD" => append_sid("admin_medal.$phpEx?mode=moderator&amp;id=$medal_id"),
			"U_MEDAL_EDIT" => append_sid("admin_medal.$phpEx?mode=edit&amp;id=$medal_id"),
			"U_MEDAL_DELETE" => append_sid("admin_medal.$phpEx?mode=delete&amp;id=$medal_id"))
		);
	}
}

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
