<?php
 //require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                                admin/admin_prune_users.php
 *                            -------------------
 *   begin                : Sunday, Sep 3, 2006
 *   copyright            : (C) 2006 Omar Ramadan
 *   email                : princeomz2004@hotmail.com
 *
 *   
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
   $filename = basename(__FILE__);
	$module['Users']['Prune_Inactive_Users'] = $filename;
	$module['Users']['Pruned_Users_List'] = $filename . "?mode=list";
   return;
}

//
// Load default header
//
$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

	// Fetch data for inactive users
	function users_inactive()
	{
		global $db, $lang, $board_config, $_POST;
		$inactive_users = array();
		
		if ( $_POST['registered_check'] )
		{
			$sql_registered = " " . str_replace("\'", "''", $_POST['user_registered_condition']) . " `user_regdate`  " . str_replace("\'", "''", $_POST['user_regdate_sign']) . " '" . ( int ) str_replace("\'", "''", ( ( !$_POST['user_registered'] ) ? $_POST['user_registered'] : time() - $_POST['user_registered'] ) ) . "'";
		}
		if ( $_POST['login_check'] )
		{
		 $condition = $_POST['user_lastvisit'] == 0 ? "=" : "> 0 AND `user_lastvisit` <=";
		 $sql_login = " " . str_replace("\'", "''", $_POST['user_lastvisit_condition']) . " `user_lastvisit` " . $condition . " '" . ( int ) str_replace("\'", "''", $_POST['user_lastvisit']) . "'";
		} 
		if ( $_POST['login_check'] )
		{
			$sql_login = " " . str_replace("\'", "''", $_POST['user_lastvisit_condition']) . " `user_lastvisit` " . str_replace("\'", "''", $_POST['user_lastvisit_sign']) . " '" . ( int ) str_replace("\'", "''", ( ( !$_POST['user_lastvisit'] ) ? $_POST['user_lastvisit'] : time() - $_POST['user_lastvisit'] ) ) . "'";
		}
		if ( $_POST['active_check'] )
		{
			$sql_active = " " . str_replace("\'", "''", $_POST['user_active_condition']) . " `user_active` = '" . ( int ) str_replace("\'", "''", $_POST['user_active']) . "'";
		}
		if ( $_POST['posts_check'] )
		{
			$sql_posts = " " . str_replace("\'", "''", $_POST['user_posts_condition']) . " `user_posts` " . str_replace("\'", "''", $_POST['user_posts_sign']) . " '" . ( int ) str_replace("\'", "''", $_POST['user_posts']) . "'";
		}
		if ( $_POST['flagged_check'] )
		{
			$sql_flagged = " " . str_replace("\'", "''", $_POST['user_flagged_condition']) . " `user_prune_flagged` = '" . ( int ) str_replace("\'", "''", $_POST['user_flagged']) . "'";
		}		

		// User is not Guest or Admin
		$sql_non_anonymous = " AND user_id <> 2 AND user_id <> " . ANONYMOUS;

		// Build conditions array
		$conditions = array(
			'user_regdate' 		 => array( 'check' => 'registered_check', 'variable' => 'sql_registered', 'condition' => str_replace("\'", "''", $_POST['user_registered_condition'] ) ),
			'user_lastvisit' 	 => array( 'check' => 'login_check', 'variable' => 'sql_login', 'condition' => str_replace("\'", "''", $_POST['user_lastvisit_condition']) ),
			'user_active' 	 	 => array( 'check' => 'active_check', 'variable' => 'sql_active', 'condition' => str_replace("\'", "''", $_POST['user_active_condition']) ),
			'user_posts' 	 	 => array( 'check' => 'posts_check', 'variable' => 'sql_posts', 'condition' => str_replace("\'", "''", $_POST['user_posts_condition']) ),
			'user_prune_flagged' => array( 'check' => 'flagged_check', 'variable' => 'sql_flagged', 'condition' => str_replace("\'", "''", $_POST['user_flagged_condition']) ),
			'non_anonymous' 	 => array( 'variable' => 'sql_non_anonymous', 'condition' => 'AND' )
		);

		$sql_or = '';
		$sql_and = '';
		$sql_selects = 'user_id, username, user_last_login_try, user_last_notified, ';

		// Sort query
		while ( list ( $key, $array ) = @each ( $conditions ) )
		{
			if ( !empty($array['condition']) && $array['condition'] == 'OR' )
			{
				$sql_or .= $$array['variable'];
			}
			elseif ( !empty($array['condition']) && $array['condition'] == 'AND' )
			{
				$sql_and .= $$array['variable'];		
			}
			
			// Build sql selects
			if ( $key !== 'non_anonymous' && !empty($array['check']) )
			{
					$sql_selects .= " " . $key . ","; 
			}
		}

		// touch up query parts
		if ( !empty( $sql_or ) )
		{
			$sql_or = '(' . $sql_or;
			$sql_or .= ' )';
		}
		else
		{
			$sql_and = substr( $sql_and, 4, strlen($sql_and) );
		}
		$sql_or = str_replace( '( OR', '( ', $sql_or );
		$sql_selects = substr( $sql_selects, 0, ( strlen($sql_selects) - 1 ) );

		// put query together
		$sql = "SELECT " . $sql_selects . " \n FROM " . USERS_TABLE . " \n WHERE " . $sql_or . " \n " . $sql_and . " \n ORDER BY user_id;";
		//echo $sql;
		if (! $result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, $lang['sql_error'], "", __LINE__, __FILE__, $sql);
		}
		while ( $row = $db->sql_fetchrow($result) )
		{
			$inactive_users[] = $row;
		}
		$db->sql_freeresult($result);
		return $inactive_users;
	}
	
	if ( $_GET['mode'] == 'delete' )
	{
		if ( $_POST['submit'] )
		{
			foreach ( $_POST['inactive_users'] as $user_id )
			{
				
				// is it board founder?
				if ( $user_id == '2' )
				{
					message_die(GENERAL_ERROR, 'You can not delete the board founder.');
				}
				
				$sql = "SELECT user_id, username, user_email, user_regdate, user_lastvisit, user_last_login_try, user_active, user_posts
							FROM " . USERS_TABLE . " 
								WHERE user_id = $user_id";
								
					if( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Could not obtain group information for this user', '', __LINE__, __FILE__, $sql);
					}

					$inactive_info = $db->sql_fetchrow($result);
					
				$sql = "INSERT INTO " . PRUNED_USERS_TABLE . " ( deleted_by, delete_time, data) VALUES ( '{$userdata['user_id']}', '" . time() . "', '" . addslashes( serialize( $inactive_info ) ) . "');";
					
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not insert data into pruned users table', '', __LINE__, __FILE__, $sql);
				}
					
				$sql = "SELECT g.group_id 
						FROM " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g  
						WHERE ug.user_id = $user_id 
							AND g.group_id = ug.group_id 
							AND g.group_single_user = 1";
				if( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain group information for this user', '', __LINE__, __FILE__, $sql);
				}

				$row = $db->sql_fetchrow($result);				

				$sql = "UPDATE " . POSTS_TABLE . "
					SET poster_id = " . DELETED . ", post_username = '" . str_replace("\\'", "''", str_replace("\'", "''", $this_userdata['username'])) . "' 
					WHERE poster_id = $user_id";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not update posts for this user', '', __LINE__, __FILE__, $sql);
				}

				$sql = "UPDATE " . TOPICS_TABLE . "
					SET topic_poster = " . DELETED . " 
					WHERE topic_poster = $user_id";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not update topics for this user', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "UPDATE " . VOTE_USERS_TABLE . "
					SET vote_user_id = " . DELETED . "
					WHERE vote_user_id = $user_id";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not update votes for this user', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "SELECT group_id
					FROM " . GROUPS_TABLE . "
					WHERE group_moderator = $user_id";
				if( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not select groups where user was moderator', '', __LINE__, __FILE__, $sql);
				}
				
				while ( $row_group = $db->sql_fetchrow($result) )
				{
					$group_moderator[] = $row_group['group_id'];
				}
				
				if ( count($group_moderator) )
				{
					$update_moderator_id = implode(', ', $group_moderator);
					
					$sql = "UPDATE " . GROUPS_TABLE . "
						SET group_moderator = " . $userdata['user_id'] . "
						WHERE group_moderator IN ($update_moderator_id)";
					if( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not update group moderators', '', __LINE__, __FILE__, $sql);
					}
				}

				$sql = "DELETE FROM " . USERS_TABLE . "
					WHERE user_id = $user_id";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete user', '', __LINE__, __FILE__, $sql);
				}

				$sql = "DELETE FROM " . USER_GROUP_TABLE . "
					WHERE user_id = $user_id";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete user from user_group table', '', __LINE__, __FILE__, $sql);
				}

				if( !empty( $row['group_id'] ) )
				{
					$sql = "DELETE FROM " . GROUPS_TABLE . "
						WHERE group_id = " . $row['group_id'];
					if( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not delete group for this user', '', __LINE__, __FILE__, $sql);
					}

					$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . "
						WHERE group_id = " . $row['group_id'];
					if( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not delete group for this user', '', __LINE__, __FILE__, $sql);
					}
				}
				
				$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
					WHERE user_id = $user_id";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete user from topic watch table', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "DELETE FROM " . BANLIST_TABLE . "
					WHERE ban_userid = $user_id";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete user from banlist table', '', __LINE__, __FILE__, $sql);
				}
				$sql = "DELETE FROM " . SESSIONS_TABLE . "
					WHERE session_user_id = $user_id";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete sessions for this user', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "DELETE FROM " . SESSIONS_KEYS_TABLE . "
					WHERE user_id = $user_id";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete auto-login keys for this user', '', __LINE__, __FILE__, $sql);
				}



				$sql = "SELECT privmsgs_id
					FROM " . PRIVMSGS_TABLE . "
					WHERE privmsgs_from_userid = $user_id 
						OR privmsgs_to_userid = $user_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not select all users private messages', '', __LINE__, __FILE__, $sql);
				}

				// This little bit of code directly from the private messaging section.
				while ( $row_privmsgs = $db->sql_fetchrow($result) )
				{
					$mark_list[] = $row_privmsgs['privmsgs_id'];
				}
				
				if ( count($mark_list) )
				{
					$delete_sql_id = implode(', ', $mark_list);
					
					$delete_text_sql = "DELETE FROM " . PRIVMSGS_TEXT_TABLE . "
						WHERE privmsgs_text_id IN ($delete_sql_id)";
					$delete_sql = "DELETE FROM " . PRIVMSGS_TABLE . "
						WHERE privmsgs_id IN ($delete_sql_id)";
					
					if ( !$db->sql_query($delete_sql) )
					{
						message_die(GENERAL_ERROR, 'Could not delete private message info', '', __LINE__, __FILE__, $delete_sql);
					}
					
					if ( !$db->sql_query($delete_text_sql) )
					{
						message_die(GENERAL_ERROR, 'Could not delete private message text', '', __LINE__, __FILE__, $delete_text_sql);
					}
				}
			}
			
				$message = $lang['User_deleted'] . '<br /><br />' . sprintf($lang['Click_return_userprune'], '<a href="' . append_sid("admin_prune_users.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
		}
		else if ( $_POST['notify'] )
		{

			include($phpbb_root_path . 'includes/emailer.'.$phpEx);
			$emailer = new emailer($board_config['smtp_delivery']);
			
			foreach ( $_POST['inactive_users'] as $user_id )
			{
				
				$sql = "SELECT user_id, username, user_email, user_active, user_lang 
				FROM " . USERS_TABLE . " 
				WHERE user_id = '" . str_replace("\'", "''", $user_id ) . "'";
				
				if ( $result = $db->sql_query($sql) )
				{
					if ( $row = $db->sql_fetchrow($result) )
					{

						$emailer->from($board_config['board_email']);
						$emailer->replyto($board_config['board_email']);

						$emailer->use_template('user_inactive_notify', $row['user_lang']);
						$emailer->email_address($row['user_email']);
						$emailer->set_subject($lang['Your_account_is_inactive']);

						$emailer->assign_vars(array(
							'SITENAME' => $board_config['sitename'], 
							'USERNAME' => $row['username'],
							'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '' 

						));
						if ( !$emailer->send() )
						{
							message_die(GENERAL_MESSAGE, 'Notification emails failed to send');			
						}
						
						$sql = "UPDATE " . USERS_TABLE . " SET user_last_notified = '" . time() . "' 
							WHERE user_id = '" . str_replace("\'", "''", $user_id ) . "'";
							
						if( !$db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, 'Could not update last notification time for this user', '', __LINE__, __FILE__, $sql);
						}
							
						// reset
						$emailer->reset();
					}
					else
					{
						message_die(GENERAL_MESSAGE, 'This user does not exist');
					}
					
				}
				else
				{
					message_die(GENERAL_ERROR, 'Could not obtain user information to notify the user', '', __LINE__, __FILE__, $sql);
				}
			}

			// Messages sent
			message_die(GENERAL_MESSAGE, 'Notification emails sent <br> <a href="javascript:history.back();">Back</a>');
		}
		else if ( $_POST['flag'] )
		{
			foreach ( $_POST['inactive_users'] as $user_id )
			{
				
				$sql = "SELECT user_id, user_prune_flagged 
				FROM " . USERS_TABLE . " 
				WHERE user_id = '" . str_replace("\'", "''", $user_id ) . "'";
				
				if ( $result = $db->sql_query($sql) )
				{
					if ( $row = $db->sql_fetchrow($result) )
					{

						$flagged = ( $row['user_prune_flagged'] ) ? '0' : '1';
				
						$sql = "UPDATE " . USERS_TABLE . " SET user_prune_flagged = '" . $flagged . "' 
							WHERE user_id = '" . str_replace("\'", "''", $row['user_id'] ) . "'";
						
						if( !$db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, 'Could not flag/unflag users', '', __LINE__, __FILE__, $sql);
						}

					}
					else
					{
						message_die(GENERAL_MESSAGE, 'This user does not exist');
					}
					
				}
				else
				{
					message_die(GENERAL_ERROR, 'Could not obtain user information to flag the user', '', __LINE__, __FILE__, $sql);
				}
			}

			// users flagged/unflagged
			message_die(GENERAL_MESSAGE, 'Users Flagged/Unflagged <br> <a href="javascript:history.back();">Back</a>');
		}		
	}
	elseif ( $_GET['mode'] == 'fetch' && isset ( $_POST['fetch'] ) )
	{
	
		// save query?
		if ( isset ( $_POST['save_query'] ) )
		{
		
			$save_data_array = array(
				'posts_check' 				=> array( 'name' => 'posts_check', 'value' => $_POST['posts_check'], 'type' => 'check' ),
				'user_posts_condition'		=> array( 'name' => 'user_posts_condition', 'value' => $_POST['user_posts_condition'], 'type' => 'select' ),
				'user_posts_sign' 			=> array( 'name' => 'user_posts_sign', 'value' => $_POST['user_posts_sign'], 'type' => 'select' ),
				'user_posts' 				=> array( 'name' => 'user_posts', 'value' => $_POST['user_posts'], 'type' => 'text' ),
				
				'active_check' 				=> array( 'name' => 'active_check', 'value' => $_POST['active_check'], 'type' => 'check' ),
				'user_active_condition' 	=> array( 'name' => 'user_active_condition', 'value' => $_POST['user_active_condition'], 'type' => 'select' ),
				'user_active' 				=> array( 'name' => 'user_active', 'value' => (string) $_POST['user_active'], 'type' => 'button' ),
				
				'login_check' 				=> array( 'name' => 'login_check', 'value' => $_POST['login_check'], 'type' => 'check' ),
				'user_lastvisit_condition' 	=> array( 'name' => 'user_lastvisit_condition', 'value' => $_POST['user_lastvisit_condition'], 'type' => 'select' ),
				'user_lastvisit_sign'		=> array( 'name' => 'user_lastvisit_sign', 'value' => $_POST['user_lastvisit_sign'], 'type' => 'select' ),
				'user_lastvisit' 			=> array( 'name' => 'user_lastvisit', 'value' => $_POST['user_lastvisit'], 'type' => 'select' ),
				
				'registered_check' 			=> array( 'name' => 'registered_check', 'value' => $_POST['registered_check'], 'type' => 'check' ),
				'user_registered_condition' => array( 'name' => 'user_registered_condition', 'value' => $_POST['user_registered_condition'], 'type' => 'select' ),
				'user_regdate_sign'			=> array( 'name' => 'user_regdate_sign', 'value' => $_POST['user_regdate_sign'], 'type' => 'select' ),
				'user_registered' 			=> array( 'name' => 'user_registered', 'value' => $_POST['user_registered'], 'type' => 'select' ),
				
				'flagged_check' 			=> array( 'name' => 'flagged_check', 'value' => $_POST['flagged_check'], 'type' => 'check' ),
				'user_flagged_condition'    => array( 'name' => 'user_flagged_condition', 'value' => $_POST['user_flagged_condition'], 'type' => 'select' ),
				'user_flagged' 		    	=> array( 'name' => 'user_flagged', 'value' => $_POST['user_flagged'], 'type' => 'button' ) );
				
				$save_data = serialize( $save_data_array );
				
				$sql = "UPDATE " . CONFIG_TABLE . " SET
					config_value = '" . str_replace("\'", "''", $save_data) . "'
					WHERE config_name = 'prune_users_default'";
					
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not update default query', '', __LINE__, __FILE__, $sql);
				}
		}
		
		// fetch inactive users list
		$inactive_users = users_inactive();
		
		// set up template	
		$template->set_filenames(array(
			'body' => 'admin/prune_users.tpl')
		);
				
		for($i = 0; $i < count ( $inactive_users ); $i++)
		{
			$user_id = $inactive_users[$i]['user_id'];
			$username = $inactive_users[$i]['username'];
			$user_lastvisit = ( !$inactive_users[$i]['user_lastvisit'] ) ? $lang['Never'] : create_date($board_config['default_dateformat'], $inactive_users[$i]['user_lastvisit'], $board_config['board_timezone']);
			$user_lastvisit_raw = $inactive_users[$i]['user_lastvisit'];
			$user_last_login_try = ( !$inactive_users[$i]['user_last_login_try'] ) ? $lang['Never'] : create_date($board_config['default_dateformat'], $inactive_users[$i]['user_last_login_try'], $board_config['board_timezone']);
			$user_last_login_try_raw = $inactive_users[$i]['user_last_login_try'];
			$user_regdate = ( !$inactive_users[$i]['user_regdate'] ) ? $lang['Never'] : create_date($board_config['default_dateformat'], $inactive_users[$i]['user_regdate'], $board_config['board_timezone']);
			$user_last_notified = ( !$inactive_users[$i]['user_last_notified'] ) ? $lang['Never'] : create_date($board_config['default_dateformat'], $inactive_users[$i]['user_last_notified'], $board_config['board_timezone']);			
			
			
			$user_regdate_raw = $inactive_users[$i]['user_regdate'];
			$user_last_notified_raw = $inactive_users[$i]['user_last_notified'];
			$user_active = ( !$inactive_users[$i]['user_active'] ) ? $lang['No'] : $lang['Yes'];
			$user_posts = $inactive_users[$i]['user_posts'];
			$user_flagged = $inactive_users[$i]['user_prune_flagged'];
			
			$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
			$template->assign_block_vars("inactive_users", array(
				"ROW_COLOR" 	  => "#" . $row_color,
				"ROW_CLASS"		  => $row_class,
				"USER_ID" 		  => $user_id,
				"USERNAME" 		  => $username,
				
				"USER_LAST_VISIT" 	  => $user_lastvisit,
				"USER_LAST_LOGIN_TRY" => $user_last_login_try,
				"USER_REGDATE" 	  	  => $user_regdate,
				"USER_LAST_NOTIFIED"  => $user_last_notified,
				"USER_ACTIVE" 	  	  => $user_active,
				"USER_POSTS" 	  	  => $user_posts,
				"USER_FLAGGED" 	  	  => ( $user_flagged ) ? 'user_unflag.gif' : 'user_flag.gif',
				"L_USER_FLAGGED" 	  => ( $user_flagged ) ? $lang['User_Unflag'] : $lang['Flag_User'],
				
				"USER_LAST_VISIT_RAW" 	  => $user_lastvisit_raw,
				"USER_LAST_LOGIN_TRY_RAW" => $user_last_login_try_raw,
				"USER_LAST_NOTIFIED_RAW"  => $user_last_notified_raw,
				"USER_REGDATE_RAW" 	 	  => $user_regdate_raw,
				"USER_ACTIVE_RAW" 	 	  => $user_active_raw,
				"USER_POSTS_RAW" 	 	  => $user_posts_raw,
				
				"U_USER_PROFILE" => append_sid($phpbb_root_path . "profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id"),
				"U_NOTIFY_USER" => append_sid("admin_prune_users.$phpEx?mode=notify&amp;" . POST_USERS_URL . "=$user_id"),
				"U_FLAG_USER" => append_sid("admin_prune_users.$phpEx?mode=flag&amp;flagged=" . (int) $user_flagged . "&amp;" . POST_USERS_URL . "=$user_id")
				
				)
			);

		}


		
		if ( ! count ( $inactive_users ) )
		{
			$template->assign_block_vars("no_inactive_users", array("L_NONE" => $lang['Acc_None'] ) );
		}
		
		$template->assign_vars(array(
			'L_PAGE_TITLE' => $lang['Prune_users_page_title'],
			'L_PAGE_EXPLAIN' => $lang['Prune_users_page_explain'],
			
			'L_NOTIFY_MESSAGE' => $lang['Email_Confirm_message'],
			'L_FLAG_MESSAGE' => $lang['Flag_Confirm_message'],
			'L_CONFIRM_MESSAGE' => $lang['Confirm_message'],
			'L_CONFIRM_NOTIFY_SELECTED' => $lang['Notify_selected'],
			'L_CONFIRM_FLAG_SELECTED' => $lang['Flag_selected'],
			'L_USERNAME' => $lang['Username'],
			'L_SELECT_ALL_NONE' => $lang['Select_all_none'],
			'L_SELECTED' => $lang['Selected'],
			'L_SUBMIT' => $lang['Delete'],
			'L_NOTIFY_SELECTED' => $lang['Notify_user'],
			'L_FLAG_SELECTED' => $lang['Flag'],
			'L_EMAIL' => $lang['Email'],
			'L_NOTIFY_USER' => $lang['Notify_user'],
			'L_FLAG_USER' => $lang['Flagged'],
			'L_LAST_NOTIFIED' => $lang['Last_Notified'],

			
			'L_USER_LAST_LOGIN_TRY'	  => $lang['Last_login_try'],
			'L_USER_LAST_VISIT'		  => $lang['Last_visit'],
			'L_USER_REGDATE' 	 	  => $lang['user_regdate'],
			'L_USER_ACTIVE' 		  => $lang['user_active'],
			'L_USER_POSTS' 	 	 	  => $lang['user_posts'],
			
			'S_FORM_ACTION' => append_sid("admin_prune_users.$phpEx?mode=delete"))
		);
		
		include('./page_header_admin.'.$phpEx);
		
		$template->pparse('body');

		include('./page_footer_admin.'.$phpEx);
	}
	elseif ( $_GET['mode'] == 'notify' && !empty($_GET[POST_USERS_URL] ) )
	{
			$sql = "SELECT user_id, username, user_email, user_active, user_lang 
		FROM " . USERS_TABLE . " 
		WHERE user_id = '" . str_replace("\'", "''",  $_GET[POST_USERS_URL] ) . "'";
		
		if ( $result = $db->sql_query($sql) )
		{
			if ( $row = $db->sql_fetchrow($result) )
			{

				include($phpbb_root_path . 'includes/emailer.'.$phpEx);
				$emailer = new emailer($board_config['smtp_delivery']);

				$emailer->from($board_config['board_email']);
				$emailer->replyto($board_config['board_email']);

				$emailer->use_template('user_inactive_notify', $row['user_lang']);
				$emailer->email_address($row['user_email']);
				$emailer->set_subject($lang['Your_account_is_inactive']);

				$emailer->assign_vars(array(
					'SITENAME' => $board_config['sitename'], 
					'USERNAME' => $row['username'],
					'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '' 

				));
				
				$sql = "UPDATE " . USERS_TABLE . " SET user_last_notified = '" . time() . "' 
					WHERE user_id = '" . str_replace("\'", "''", $_GET[POST_USERS_URL] ) . "'";
					
					
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not update last notification time for this user', '', __LINE__, __FILE__, $sql);
				}
				
				if ( $emailer->send() )
				{
					message_die(GENERAL_MESSAGE, 'Notification email sent <br> <a href="javascript:history.back();">Back</a>');
				}
				else
				{
					message_die(GENERAL_MESSAGE, 'Notification email failed to send');			
				}
				
				
				// reset
				$emailer->reset();
			}
			else
			{
				message_die(GENERAL_MESSAGE, 'This user does not exist');
			}
		}
		else
		{
			message_die(GENERAL_ERROR, 'Could not obtain user information to notify the user', '', __LINE__, __FILE__, $sql);
		}
	
	}
	elseif ( $_GET['mode'] == 'flag' && !empty($_GET[POST_USERS_URL] ) && isset($_GET['flagged'] ) )
	{
				$flagged = ( $_GET['flagged'] ) ? '0' : '1';
				
				$sql = "UPDATE " . USERS_TABLE . " SET user_prune_flagged = '" . $flagged . "' 
					WHERE user_id = '" . str_replace("\'", "''", $_GET[POST_USERS_URL] ) . "'";
				
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not flag/unflag this user', '', __LINE__, __FILE__, $sql);
				}

				$l_flagged = ( $flagged ) ? 'Flagged' : 'Unflagged';
				message_die(GENERAL_MESSAGE, 'User ' . $l_flagged . ' <br> <a href="javascript:history.back();">Back</a>');

	
	}	
	elseif ( $_GET['mode'] == 'list' )
	{

		// set up template	
		$template->set_filenames(array(
			'body' => 'admin/prune_users_list.tpl')
		);
		
		$sql = "SELECT * FROM " . PRUNED_USERS_TABLE;
				
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain group information for this user', '', __LINE__, __FILE__, $sql);
		}

		while ( $row = $db->sql_fetchrow($result) )
		{
			$pruned_list[] = $row;
		}
		
		if ( !count ( $pruned_list ) )
		{
			$template->assign_block_vars("no_inactive_users", array("L_NONE" => $lang['Acc_None'] ) );
		}
		
		for($i = 0; $i < count ( $pruned_list ); $i++)
		{

			$inactive_info = array_merge( $pruned_list[$i], unserialize( stripslashes( $pruned_list[$i]['data'] ) ) );
			$user_id = $inactive_info['deleted_by'];
			$sql = "SELECT username FROM " . USERS_TABLE . " WHERE user_id = '$user_id'";
			if( !($result2 = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain deleted by username', '', __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result2);
			$username = $inactive_info['username'];
			$user_email = ( !$inactive_info['user_email'] ) ? $lang['Old_Version'] : $inactive_info['user_email'];
			$deleted_by = $row['username'];
			
			$delete_date = create_date($board_config['default_dateformat'], $inactive_info['delete_time'], $board_config['board_timezone']);
			$delete_date_raw = $inactive_info['delete_time'];
			$user_lastvisit = ( !$inactive_info['user_lastvisit'] ) ? $lang['Never'] : create_date($board_config['default_dateformat'], $inactive_info['user_lastvisit'], $board_config['board_timezone']);
			$user_lastvisit_raw = $inactive_info['user_lastvisit'];
			$user_last_login_try = ( !$inactive_info['user_last_login_try'] ) ? $lang['Never'] : create_date($board_config['default_dateformat'], $inactive_info['user_last_login_try'], $board_config['board_timezone']);
			$user_last_login_try_raw = $inactive_info['user_last_login_try'];			
			$user_regdate = ( !$inactive_info['user_regdate'] ) ? $lang['Never'] : create_date($board_config['default_dateformat'], $inactive_info['user_regdate'], $board_config['board_timezone']);
			$user_regdate_raw = $inactive_info['user_regdate'];
			$user_active = ( !$inactive_info['user_active'] ) ? $lang['No'] : $lang['Yes'];
			$user_posts = $inactive_info['user_posts'];
			
			$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
			$template->assign_block_vars("inactive_users", array(
				"ROW_COLOR" 	  => "#" . $row_color,
				"ROW_CLASS"		  => $row_class,
				"USER_ID" 		  => $user_id,
				"USERNAME" 		  => $username,
				"USER_EMAIL" 		  => $user_email,
				
				"USER_LAST_LOGIN_TRY" => $user_last_login_try,				
				"USER_LAST_LOGIN_TRY_RAW" => $user_last_login_try_raw,				
				"USER_LAST_VISIT_RAW" => $user_lastvisit_raw,
				"USER_LAST_VISIT" => $user_lastvisit,
				"USER_REGDATE_RAW" 	  => $user_regdate_raw,
				"USER_REGDATE" 	  => $user_regdate,
				"USER_ACTIVE_RAW" 	  => $user_active_raw,
				"USER_ACTIVE" 	  => $user_active,
				"USER_DELETED_BY" 	  => $deleted_by,
				"USER_DELETE_DATE" 	  => $delete_date,
				"USER_DELETE_DATE_RAW" 	  => $delete_date_raw,
				"USER_POSTS" 	  => $user_posts,
				"DELETED_BY_PROFILE" => append_sid($phpbb_root_path . "profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $inactive_info['deleted_by'])
				
				)
			);
		}
		$template->assign_vars(array(
			'L_PAGE_TITLE' => $lang['Pruned_users_page_title'],
			'L_PAGE_EXPLAIN' => $lang['Pruned_users_page_explain'],
			
			'L_TOTAL_PRUNED_USERS' => $lang['Total_pruned_users'],
			'L_DELETED_BY' => $lang['Deleted_By'],
			'L_DELETE_DATE' => $lang['Delete_Date'],
			'L_USERNAME' => $lang['Username'],
			'L_USER_EMAIL' => $lang['Email'],
			
			'L_USER_LAST_LOGIN_TRY'	  => $lang['Last_login_try'],
			'L_USER_LAST_VISIT'	  => $lang['Last_visit'],
			'L_USER_REGDATE' 	  => $lang['user_regdate'],
			'L_USER_ACTIVE' 	  => $lang['user_active'],
			'L_USER_POSTS' 	 	  => $lang['user_posts'],
			
			'TOTAL_PRUNDED_USERS' 	 	  => count( $pruned_list ))
		);
		
		include('./page_header_admin.'.$phpEx);	
		$template->pparse('body');		
		include('./page_footer_admin.'.$phpEx);
	}
	else
	{
	
		$default_data_array = unserialize( $board_config['prune_users_default'] );
		$onload_javascript  = '';
		
		foreach ( $default_data_array as $data_array )
		{
			if ( isset ( $data_array['value'] ) )
			{
				switch ( $data_array['type'] )
				{
					case "select": $onload_javascript .=  " selectValueFromSelect(document.getElementsByName(\"" . addslashes( $data_array['name'] ) . "\")[0], \"" . addslashes( $data_array['value'] ) . "\");"; break;
					case "check":  $onload_javascript .=  " selectValueFromCheckBox(document.getElementsByName(\"" . addslashes( $data_array['name'] ) . "\"), \"" . addslashes( $data_array['value'] ) . "\");"; break;
					case "button": $onload_javascript .=  " selectValueFromButton(document.getElementsByName(\"" . addslashes( $data_array['name'] ) . "\"), \"" . addslashes( $data_array['value'] ) . "\");"; break;
					case "text":   $onload_javascript .=  " selectValueFromText(document.getElementsByName(\"" . addslashes( $data_array['name'] ) . "\"), \"" . addslashes( $data_array['value'] ) . "\");"; break;
				}
			}
		}
		
		// set up template	
		$template->set_filenames(array(
			'body' => 'admin/prune_users_sql.tpl')
		);
	
		$template->assign_vars(array(
			'ONLOAD_JAVASCRIPT' => $onload_javascript,
			
			'L_PAGE_TITLE' => $lang['Prune_users_page_title'],
			'L_PAGE_EXPLAIN' => $lang['Prune_users_sql_explain'],
			
			'L_BUILD_QUERY' => $lang['Build_Query'],
			'L_BUILD_YOUR_QUERY' => $lang['Build_Your_Query'],
			'L_SAVE_SETTINGS' => $lang['Save_Settings'],
			
			'L_USER_LAST_VISIT'	  =>  $lang['Last_visit'],
			'L_USER_REGDATE' 	  =>  $lang['user_regdate'],
			'L_USER_ACTIVE' 	  =>  $lang['user_active'],
			'L_USER_POSTS' 	 	  =>  $lang['user_posts'],
			'L_USER_FLAGGED' 	  =>  $lang['Flagged'],
			'L_REMEMBER_SETTINGS' =>  $lang['Remember_settings'],
			'L_SHOW_ALL' 	 	  =>  $lang['Show_all'],
			'L_YES' 	 	 	  =>  $lang['Yes'],
			'L_NO' 	 	 		  =>  $lang['No'],

			'L_SHOW_ALL_EXPLAIN'	 	  =>  $lang['Show_all_explain'],
			'L_USER_LAST_VISIT_EXPLAIN'	  =>  $lang['last_visit_explain'],
			'L_USER_REGDATE_EXPLAIN' 	  =>  $lang['user_regdate_explain'],
			'L_USER_ACTIVE_EXPLAIN' 	  =>  $lang['user_active_explain'],
			'L_USER_POSTS_EXPLAIN' 	 	  =>  $lang['user_posts_explain'],
			'L_USER_FLAGGED_EXPLAIN' 	  =>  $lang['users_flagged_explain'],
			'L_SAVE_SETTINGS_EXPLAIN' 	  =>  $lang['users_settings_explain'],
			
			'SEVEN_DAYS' 	 	 		  =>  ( 60 * 60 * 24 * 7 ),
			'TEN_DAYS' 	 	 			  =>  ( 60 * 60 * 24 * 10 ),
			'TWO_WEEKS' 	 	 		  =>  ( 60 * 60 * 24 * 14 ),
			'ONE_MONTH' 	 			  =>  ( 60 * 60 * 24 * 30 ),
			'TWO_MONTHS' 	 			  =>  ( 60 * 60 * 24 * 60 ),
			'THREE_MONTHS' 	 			  =>  ( 60 * 60 * 24 * 91 ),
			'SIX_MONTHS' 	 			  =>  ( 60 * 60 * 24 * 182 ),
			'ONE_YEAR' 	 			 	  =>  ( 60 * 60 * 24 * 365 ),
						
			'L_PRIOR'	  => $lang['Prior'],			
			'L_WITHIN'	  => $lang['Within'],
			
			'L_NEVER' 	 			 	  =>  $lang['Never'],
			'L_ALL_TIME' 	 			  =>  $lang['All_Time'],
			'L_SEVEN_DAYS' 	 	 		  =>  $lang['Seven_days'],
			'L_TEN_DAYS' 	 	 		  =>  $lang['Ten_days'],
			'L_TWO_WEEKS' 	 	 		  =>  $lang['Two_weeks'],
			'L_ONE_MONTH' 	 			  =>  $lang['One_month'],
			'L_TWO_MONTHS' 	 			  =>  $lang['Two_months'],
			'L_THREE_MONTHS' 	 		  =>  $lang['Three_months'],
			'L_SIX_MONTHS' 	 			  =>  $lang['Six_months'],
			'L_ONE_YEAR' 	 			  =>  $lang['One_year'],
			
			'S_FORM_ACTION' => append_sid("admin_prune_users.$phpEx?mode=fetch"))
		);
		
		include('./page_header_admin.'.$phpEx);
		
		$template->pparse('body');

		include('./page_footer_admin.'.$phpEx);
	}


?>