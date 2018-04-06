<?php 
// standard hack prevent 
define('IN_PHPBB', true); $phpbb_root_path = './'; 
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx);


function inarray($needle, $haystack)
{ 
	for($i = 0; $i < sizeof($haystack); $i++ )
	{ 
		if( $haystack[$i] == $needle )
		{ 
			return true; 
		} 
	} 
	return false; 
}
// standard session management 
	$userdata = session_pagestart($user_ip, PAGE_WIO); 
	init_userprefs($userdata); 

// set page title 
	$page_title = 'Who Is Online'; 

// standard page header 
	include($phpbb_root_path . 'includes/page_header.'.$phpEx); 

// assign template 

	$template->set_filenames(array(         'body' => 'wio.tpl') ); 

//
	// Get users online information.
	//
	$sql = "SELECT u.user_id, u.user_avatar_type, u.user_allowavatar, u.user_level, u.user_avatar, u.user_allow_viewonline, u.username, u.user_session_time, u.user_session_page, s.session_logged_in, s.session_start 
		FROM " . USERS_TABLE . " u, " . SESSIONS_TABLE . " s
		WHERE s.session_logged_in = " . TRUE . " 
			AND u.user_id = s.session_user_id 
			AND u.user_id <> " . ANONYMOUS . " 
			AND s.session_time >= " . ( time() - 300 ) . " 
		ORDER BY u.user_session_time DESC";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't obtain regd user/online information.", "", __LINE__, __FILE__, $sql);
	}
	$onlinerow_reg = $db->sql_fetchrowset($result);

	$sql = "SELECT session_page, session_logged_in, session_ip, session_time, session_start   
		FROM " . SESSIONS_TABLE . "
		WHERE session_logged_in = 0
			AND session_time >= " . ( time() - 300 ) . "
		ORDER BY session_time DESC";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't obtain guest user/online information.", "", __LINE__, __FILE__, $sql);
	}
	$onlinerow_guest = $db->sql_fetchrowset($result);

	$hidden_users = 0;
	
	for($i = 0; $i < count($onlinerow_reg); $i++)
	{
		if ( !($onlinerow_reg[$i]['user_allow_viewonline']))
		{
			$hidden_users++;
		}
	}

	$num_reg_users = 'There are ' . count($onlinerow_reg) . ' users online, ' . $hidden_users . ' of which are hidden.';
	$num_guest_users = 'There are ' . count($onlinerow_guest) . ' guests online.';




	$template->assign_vars(array(
		"L_WHO_IS_ONLINE" => $lang['Who_is_Online'],
		"L_USERNAME" => $lang['Username'],
		"L_LOCATION" => $lang['Location'],
		"L_STARTED" => $lang['Login'],
		"L_LAST_UPDATE" => $lang['Last_updated'],
		"L_FORUM_LOCATION" => $lang['Forum_Location'],
		"L_REG_ONLINE" => $num_reg_users,
		"L_GUEST_ONLINE" => $num_guest_users)
);

	$sql = "SELECT forum_name, forum_id
		FROM " . FORUMS_TABLE;
	if($forums_result = $db->sql_query($sql))
	{
		while($forumsrow = $db->sql_fetchrow($forums_result))
		{
			$forum_data[$forumsrow['forum_id']] = $forumsrow['forum_name'];
		}
	}
	else
	{
		message_die(GENERAL_ERROR, "Couldn't obtain user/online forums information.", "", __LINE__, __FILE__, $sql);
	}

	$reg_userid_ary = array();

	if( count($onlinerow_reg) )
	{
		$registered_users = 0;
		$hidden_users = 0;

		for($i = 0; $i < count($onlinerow_reg); $i++)
		{
			if( ($onlinerow_reg[$i]['user_allow_viewonline']) || ($onlinerow_reg[$i]['user_level']== USER &&( $userdata['user_level'] == MOD || $userdata['user_level'] == ADMIN )) || (($onlinerow_reg[$i]['user_level']== MOD || $onlinerow_reg[$i]['user_level']== ADMIN )&& $userdata['user_level'] == ADMIN ))
			{	
				if( !inarray($onlinerow_reg[$i]['user_id'], $reg_userid_ary) )
				{
					$reg_userid_ary[] = $onlinerow_reg[$i]['user_id'];

					$username = $onlinerow_reg[$i]['username'];
					if ( $onlinerow_reg[$i]['user_level'] == ADMIN )
					{
						
						$username = '<i><b style="color:#' . $theme['fontcolor3'] . '">' . $username . '</i></b>';
					}
					elseif( $onlinerow_reg[$i]['user_level'] == MOD )
					{
			
						$username = '<b style="color:#' . $theme['fontcolor2'] . '">' . $username . '</b>';
					}
					$poster_avatar = "";
					if ( $onlinerow_reg[$i]['user_avatar_type'] && $user_id != ANONYMOUS && $onlinerow_reg[$i]['user_allowavatar']  )
					{
						switch( $onlinerow_reg[$i]['user_avatar_type'] )
						{
							case USER_AVATAR_UPLOAD:
								$poster_avatar = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $onlinerow_reg[$i]['user_avatar'] . '" alt="" border="0" />' : '';
								break;
							case USER_AVATAR_REMOTE:
								$poster_avatar = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $onlinerow_reg[$i]['user_avatar'] . '" alt="" border="0" />' : '';
								break;
							case USER_AVATAR_GALLERY:
								$poster_avatar = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $onlinerow_reg[$i]['user_avatar'] . '" alt="" border="0" />' : '';
								break;
							default:
								$poster_avatar = "";
						}
					}

		
		
				
					if( $onlinerow_reg[$i]['user_allow_viewonline'] )
					{
						$registered_users++;
						$hidden = FALSE;
					}
					else
					{
						$hidden_users++;
						$hidden = TRUE;
						$username = '<strike>' . $username . '</strike>';	
					}

					if( $onlinerow_reg[$i]['user_session_page'] < 1 )
					{
						switch($onlinerow_reg[$i]['user_session_page'])
						{
							case PAGE_INDEX:
								$location = $lang['Forum_index'];
								$location_url = "index.$phpEx";
								break;
							case PAGE_POSTING:
								$location = $lang['Posting_message'];
								$location_url = "index.$phpEx";
								break;
							case PAGE_LOGIN:
								$location = $lang['Logging_on'];
								$location_url = "index.$phpEx";
								break;
							case PAGE_SEARCH:
								$location = $lang['Searching_forums'];
								$location_url = "index.$phpEx";
								break;
							case PAGE_PROFILE:
								$location = $lang['Viewing_profile'];
								$location_url = "index.$phpEx";
								break;
							case PAGE_VIEWONLINE:
								$location = $lang['Viewing_online'];
								$location_url = "index.$phpEx";
								break;
							case PAGE_VIEWMEMBERS:
								$location = $lang['Viewing_member_list'];
								$location_url = "index.$phpEx";
								break;
							case PAGE_PRIVMSGS:
								$location = $lang['Viewing_priv_msgs'];
								$location_url = "index.$phpEx";
								break;
							case PAGE_FAQ:
								$location = $lang['Viewing_FAQ'];
								$location_url = "index.$phpEx";
								break;
							case PAGE_WIO:								$location = $lang['WIO'];								$location_url = "wio.$phpEx";								break;
							case PAGE_JRCHAT:								$location = $lang['JRCHAT'];								$location_url = "JRChat.$phpEx";								break;
							case PAGE_JRCHATRP:								$location = $lang['JRCHATRP'];								$location_url = "JRChatRP.$phpEx";								break;
							case PAGE_BOOKIES:
								$location = $lang['bookies'];
								$location_url = "bookies.$phpEx";
								break;
							
							case PAGE_BOOKIE_YOURSTATS:
								$location = $lang['bookie_yourstats'];
								$location_url = "bookie_yourstats.$phpEx";
								break;
							
							case PAGE_BOOKIE_ALLSTATS:
								$location = $lang['bookie_allstats'];
								$location_url = "bookie_allstats.$phpEx";
								break;
							
							default:
								$location = $lang['Forum_index'];
								$location_url = "index.$phpEx";
						}
					}
					else
					{
						$location_url = "index.$phpEx";
						$location = $forum_data[$onlinerow_reg[$i]['user_session_page']];
					}

					$row_color = ( $registered_users % 2 ) ? $theme['td_color1'] : $theme['td_color2'];
					$row_class = ( $registered_users % 2 ) ? $theme['td_class1'] : $theme['td_class2'];
					

					$template->assign_block_vars("reg_user_row", array(
						"ROW_COLOR" => "#" . $row_color,
						"ROW_CLASS" => $row_class,
						"USERNAME" => $username, 
						"STARTED" => create_date($board_config['default_dateformat'], $onlinerow_reg[$i]['session_start'], $board_config['board_timezone']), 
						"LASTUPDATE" => create_date($board_config['default_dateformat'], $onlinerow_reg[$i]['user_session_time'], $board_config['board_timezone']),
						"FORUM_LOCATION" => $location,
						"AVATAR" => $poster_avatar, 
						"U_USER_PROFILE" => append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $onlinerow_reg[$i]['user_id']),
						"U_FORUM_LOCATION" => append_sid($location_url))
					);
				}
			}
		}

	}
	else
	{
		$template->assign_vars(array(
			"L_NO_REGISTERED_USERS_BROWSING" => $lang['No_users_browsing'])
		);
	}

	//
	// Guest users
	//
	if( count($onlinerow_guest) )
	{
		$guest_users = 0;
		$prev_ip = 0;
		for($i = 0; $i < count($onlinerow_guest); $i++)
		{
		    if($prev_ip != $onlinerow_guest[$i]['session_ip'])
		    {
			$prev_ip = $onlinerow_guest[$i]['session_ip'];
		   
			$guest_userip_ary[] = $onlinerow_guest[$i]['session_ip'];
			$guest_users++;

			if( $onlinerow_guest[$i]['session_page'] < 1 )
			{
				switch( $onlinerow_guest[$i]['session_page'] )
				{
					case PAGE_INDEX:
						$location = $lang['Forum_index'];
						$location_url = "index.$phpEx";
						break;
					case PAGE_POSTING:
						$location = $lang['Posting_message'];
						$location_url = "index.$phpEx";
						break;
					case PAGE_LOGIN:
						$location = $lang['Logging_on'];
						$location_url = "index.$phpEx";
						break;
					case PAGE_SEARCH:
						$location = $lang['Searching_forums'];
						$location_url = "index.$phpEx";
						break;
					case PAGE_PROFILE:
						$location = $lang['Viewing_profile'];
						$location_url = "index.$phpEx";
						break;
					case PAGE_VIEWONLINE:
						$location = $lang['Viewing_online'];
						$location_url = "index.$phpEx";
						break;
					case PAGE_VIEWMEMBERS:
						$location = $lang['Viewing_member_list'];
						$location_url = "index.$phpEx";
						break;
					case PAGE_PRIVMSGS:
						$location = $lang['Viewing_priv_msgs'];
						$location_url = "index.$phpEx";
						break;
					case PAGE_FAQ:
						$location = $lang['Viewing_FAQ'];
						$location_url = "index.$phpEx";
						break;
					case PAGE_WIO:						$location = $lang['WIO'];						$location_url = "wio.$phpEx";						break;
					case PAGE_JRCHAT:						$location = $lang['JRCHAT'];						$location_url = "JRChat.$phpEx";						break;
					case PAGE_JRCHATRP:						$location = $lang['JRCHATRP'];						$location_url = "JRChatRP.$phpEx";						break;
					case PAGE_BOOKIES:
						$location = $lang['bookies'];
						$location_url = "bookies.$phpEx";
						break;
							
					case PAGE_BOOKIE_YOURSTATS:
						$location = $lang['bookie_yourstats'];
						$location_url = "bookie_yourstats.$phpEx";
						break;
							
					case PAGE_BOOKIE_ALLSTATS:
						$location = $lang['bookie_allstats'];
						$location_url = "bookie_allstats.$phpEx";
						break;
//------------------------------------------------------------------------// Prillian - Begin Code Addition//					case PAGE_PRILLIAN:						include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_prillian.' . $phpEx);						$location = $lang['Prillian'];						$location_url = "index.$phpEx";						break;					case PAGE_CONTACT:						include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_contact.' . $phpEx);						$location = $lang['Contact_Management'];						$location_url = "index.$phpEx";						break;//// Prillian - End Code Addition//------------------------------------------------------------------------
					default:
						$location = $lang['Forum_index'];
						$location_url = "index.$phpEx";
				}
			}
			else
			{
				$location_url = "index.$phpEx";
				$location = $forum_data[$onlinerow_guest[$i]['session_page']];
			}

			$row_color = ( $guest_users % 2 ) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = ( $guest_users % 2 ) ? $theme['td_class1'] : $theme['td_class2'];
			


			$template->assign_block_vars("guest_user_row", array(
				"ROW_COLOR" => "#" . $row_color,
				"ROW_CLASS" => $row_class,
				"USERNAME" => $lang['Guest'],
				"STARTED" => create_date($board_config['default_dateformat'], $onlinerow_guest[$i]['session_start'], $board_config['board_timezone']), 
				"LASTUPDATE" => create_date($board_config['default_dateformat'], $onlinerow_guest[$i]['session_time'], $board_config['board_timezone']),
				"FORUM_LOCATION" => $location,

				"U_WHOIS_IP" => "http://network-tools.com/default.asp?host=$guest_ip", 
				"U_FORUM_LOCATION" => append_sid($location_url))
			);
		}
	    }
	}
	else
	{
		$template->assign_vars(array(
			"L_NO_GUESTS_BROWSING" => $lang['No_users_browsing'])
		);
	}

$template->pparse('body'); 
// standard page footer 
include($phpbb_root_path . 'includes/page_tail.'.$phpEx); 
?>
