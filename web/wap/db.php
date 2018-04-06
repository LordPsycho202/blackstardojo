<?php
function mo_select_categories()
{
	global $db;
	
	$rows = array();
	
	$sql = "SELECT cat_id, cat_title 
	    FROM " . CATEGORIES_TABLE . " 
	    ORDER BY cat_order";
	if ( !( $result = $db->sql_query($sql) ) )
	{
		mo_log("Select CATEGORIES_TABLE failed.\n\t$sql");
		return;
	}
	$rows = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	
	return $rows;
}

function mo_select_forums($cat_id)
{
	global $db;
	
	$rows = array();
	
	$sql = "SELECT forum_id, forum_name, forum_desc, auth_view, auth_read 
	    FROM " . FORUMS_TABLE . " 
	    WHERE cat_id = $cat_id 
	    ORDER BY forum_order";
	if ( !( $result = $db->sql_query($sql) ) )
	{
		mo_log("Select FORUMS_TABLE failed.\n\t$sql");
		return;
	}
	$rows = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	
	return $rows;
}

function mo_select_topics($forum_id, $index)
{
	global $db;
	global $mo_var;
	
	$rows = array();
	
	$sql = "SELECT topic_id, topic_title 
	    FROM " . TOPICS_TABLE . " 
	    WHERE forum_id = $forum_id 
	    ORDER BY topic_last_post_id DESC 
	    LIMIT $index, " . $mo_var['row_size'];
	if ( !( $result = $db->sql_query($sql) ) )
	{
		mo_log("Select TOPICS_TABLE failed.\n\t$sql");
		return;
	}
	$rows = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	
	return $rows;
}

function mo_select_online_status($time)
{
	global $db;
	
	$rows = array();
	
	$sql = "SELECT u.user_id, u.username, u.user_allow_viewonline, 
	        s.session_ip, s.session_logged_in 
	    FROM " . USERS_TABLE . " u, " . SESSIONS_TABLE . " s 
	    WHERE u.user_id = s.session_user_id 
	    AND s.session_time >= ($time - 300) 
	    ORDER BY u.username, s.session_ip";
	if ( !( $result = $db->sql_query($sql) ) )
	{
		mo_log("Select USERS_TABLE and SESSIONS_TABLE failed.\n\t$sql");
		return;
	}
	$rows = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	
	return $rows;
}

function mo_select_privmsgs($case, $user_id, $index = -1)
{
	global $db;
	global $mo_var;
	
	if ( $case == 'privmsgs_inbox' )
	{
		$where_sql = "( privmsgs_type = " . PRIVMSGS_NEW_MAIL . " 
		    OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . " 
		    OR privmsgs_type = " . PRIVMSGS_READ_MAIL . " ) 
		    AND privmsgs_to_userid = $user_id";
	}
	elseif ( $case == 'privmsgs_sentbox' )
	{
		$where_sql = "( privmsgs_type = " . PRIVMSGS_NEW_MAIL . " 
		    OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . " 
		    OR privmsgs_type = " . PRIVMSGS_SENT_MAIL . " ) 
		    AND privmsgs_from_userid = $user_id";
	}
	elseif ( $case == 'privmsgs_savebox' )
	{
		$where_sql = "( privmsgs_type = " . PRIVMSGS_SAVED_IN_MAIL . " 
		    AND privmsgs_to_userid = $user_id ) 
		    OR ( privmsgs_type = " . PRIVMSGS_SAVED_OUT_MAIL . " 
		    AND privmsgs_from_userid = $user_id )";
	}
	else
	{
		return;
	}
	
	$which_sql = "";
	if ( $index != -1 )
	{
		$row_size1 = $mo_var['row_size']+1;
		$which_sql = "LIMIT $index, $row_size1";
	}
	
	$rows = array();
	
	$sql = "SELECT privmsgs_id, privmsgs_subject 
	    FROM " . PRIVMSGS_TABLE . " 
	    WHERE $where_sql 
	    ORDER BY privmsgs_date DESC 
	    $which_sql";
	if ( !( $result = $db->sql_query($sql) ) )
	{
		mo_log("Select PRIVMSGS_TABLE failed.\n\t$sql");
		return;
	}
	$rows = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	
	return $rows;
}

function mo_search($case, $index)
{
	global $db;
	global $mo_var;
	global $userdata;
	
	if ( $case == 'newposts' )
	{
		$from_sql = TOPICS_TABLE . " t, " . POSTS_TABLE . " p";
		$where_sql = "t.topic_last_post_id = p.post_id 
		    AND p.post_time >= '" . $userdata['user_lastvisit'] . "'";
	}
	elseif ( $case == 'egosearch' )
	{
		$from_sql = TOPICS_TABLE . " t, " . POSTS_TABLE . " p";
		$where_sql = "t.topic_id = p.topic_id 
		    AND p.poster_id = '" . $userdata['user_id'] . "'";
	}
	elseif ( $case == 'unanswer' )
	{
		$from_sql = TOPICS_TABLE . " t";
		$where_sql = "t.topic_replies = 0 
		    AND t.topic_moved_id = 0";
	}
	else
	{
		return;
	}
	
	
	$is_auth = auth(AUTH_READ, AUTH_LIST_ALL, $userdata);
	
	$ignore_forum_sql = '';
	while( list($key, $value) = each($is_auth) )
	{
		if ( !$value['auth_read'] )
		{
			$ignore_forum_sql .= ( $ignore_forum_sql ? ', ' : '' ) . $key;
		}
	}
	
	if ( $ignore_forum_sql )
	{
		$from_sql .= ", " . FORUMS_TABLE . " f";
		$where_sql .= " 
		    AND f.forum_id = t.forum_id 
		    AND f.forum_id NOT IN ($ignore_forum_sql)";
	}
	
	
	$rows = array();
	$row_size1 = $mo_var['row_size']+1;
	
	$sql = "SELECT DISTINCT t.topic_id, t.topic_title 
	    FROM $from_sql 
	    WHERE $where_sql 
	    ORDER BY t.topic_last_post_id DESC 
	    LIMIT $index, $row_size1";
	if ( !( $result = $db->sql_query($sql) ) )
	{
		mo_log("Select TOPICS_TABLE failed.\n\t$sql");
		return;
	}
	$rows = $db->sql_fetchrowset($query);
	$db->sql_freeresult($result);
	
	return $rows;
}


function mo_get_forums($forum_id)
{
	global $db;
	
	$sql = "SELECT cat_id, forum_topics, auth_read, auth_post, auth_reply 
	    FROM " . FORUMS_TABLE . " 
	    WHERE forum_id = $forum_id";
	if ( !( $result = $db->sql_query($sql) ) )
	{
		mo_log("Get FORUMS_TABLE failed.\n\t$sql");
		return;
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	return array($row['cat_id'], $row['forum_topics'], 
	    $row['auth_read'], $row['auth_post'], $row['auth_reply']);
}

function mo_get_topics($topic_id)
{
	global $db;
	
	$sql = "SELECT forum_id, topic_replies 
	    FROM " . TOPICS_TABLE . " 
	    WHERE topic_id = $topic_id";
	if ( !( $result = $db->sql_query($sql) ) )
	{
		mo_log("Get TOPICS_TABLE failed.\n\t$sql");
		return;
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	return array($row['forum_id'], $row['topic_replies']);
}

function mo_get_posts($topic_id, $index)
{
	global $db;
	
	$sql = "SELECT post_id, poster_id 
	    FROM " . POSTS_TABLE . " 
	    WHERE topic_id = $topic_id 
	    ORDER BY post_time 
	    LIMIT $index, 1";
	if ( !( $result = $db->sql_query($sql) ) )
	{
		mo_log("Get POSTS_TABLE failed.\n\t$sql");
		return;
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	return array($row['post_id'], $row['poster_id']);
}

function mo_get_posts_text($post_id)
{
	global $db;
	
	$sql = "SELECT post_subject, post_text 
	    FROM " . POSTS_TEXT_TABLE . " 
	    WHERE post_id = $post_id";
	if ( !( $result = $db->sql_query($sql) ) )
	{
		mo_log("Get POSTS_TEXT_TABLE failed.\n\t$sql");
		return;
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$post_text = str_replace("\n", '<br/>', $row['post_text']);
	
	return array($row['post_subject'], $post_text);
}

function mo_get_last_post_time()
{
	global $db;
	global $userdata;
	global $user_ip;
	
	$where_sql = ( $userdata['user_id'] == ANONYMOUS ) ? 
	    "poster_ip = '" . $user_ip . "'" : 
	    "poster_id = " . $userdata['user_id'];
	
	$sql = "SELECT MAX(post_time) AS last_post_time 
	    FROM " . POSTS_TABLE . " 
	    WHERE $where_sql";
	if ( !( $result = $db->sql_query($sql) ) )
	{
		mo_log("Get POSTS_TABLE failed.\n\t$sql");
		return;
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	return $row['last_post_time'];
}

function mo_get_users($user_id)
{
	global $db;
	global $mo_lang;
	
	if ( $user_id == ANONYMOUS )
	{
		return array($mo_lang['guest'], '', '');
	}
	
	$sql = "SELECT username, user_password, user_email 
	    FROM " . USERS_TABLE . " 
	    WHERE user_id = $user_id";
	if ( !( $result = $db->sql_query($sql) ) )
	{
		mo_log("Get USERS_TABLE failed.\n\t$sql");
		return;
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	return array($row['username'], $row['user_password'], $row['user_email']);
}

function mo_get_user_id($username, $password = '', $email = '')
{
	global $db;
	global $mo_lang;
	
	if ( $email )
	{
		$where_sql = "user_email = '" . $email . "'";
	}
	elseif ( $password )
	{
		$where_sql = "username = '" . $username . "' 
		    AND user_password = '" . $password . "'";
	}
	else
	{
		$where_sql = "username = '" . $username . "'";
	}
	
	if ( $username == $mo_lang['guest'] )
	{
		return ANONYMOUS;
	}
	
	$sql = "SELECT user_id 
	    FROM " . USERS_TABLE . " 
	    WHERE $where_sql";
	if ( !( $result = $db->sql_query($sql) ) )
	{
		mo_log("Get USERS_TABLE failed.\n\t$sql");
		return;
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	return $row['user_id'];
}

function mo_get_privmsgs($privmsgs_id)
{
	global $db;
	global $userdata;
	
	$sql = "SELECT privmsgs_type, privmsgs_subject, 
	        privmsgs_from_userid, privmsgs_to_userid, privmsgs_date 
	    FROM " . PRIVMSGS_TABLE . " 
	    WHERE privmsgs_id = $privmsgs_id 
	    AND ( ( ( privmsgs_type = " . PRIVMSGS_NEW_MAIL . " 
	    OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . " 
	    OR privmsgs_type = " . PRIVMSGS_SENT_MAIL . " 
	    OR privmsgs_type = " . PRIVMSGS_SAVED_OUT_MAIL . " )
	    AND privmsgs_from_userid = " . $userdata['user_id'] . " ) 
	    OR ( ( privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . " 
	    OR privmsgs_type = " . PRIVMSGS_READ_MAIL . " 
	    OR privmsgs_type = " . PRIVMSGS_SAVED_IN_MAIL . " ) 
	    AND privmsgs_to_userid = " . $userdata['user_id'] . " ) )";
	if ( !( $result = $db->sql_query($sql) ) )
	{
		mo_log("Get PRIVMSGS_TABLE failed.\n\t$sql");
		return;
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	return array($row['privmsgs_type'], $row['privmsgs_subject'], 
	    $row['privmsgs_from_userid'], $row['privmsgs_to_userid'], 
	    $row['privmsgs_date']);
}

function mo_get_privmsgs_text($privmsgs_id)
{
	global $db;
	
	$sql = "SELECT privmsgs_bbcode_uid, privmsgs_text 
	    FROM " . PRIVMSGS_TEXT_TABLE . " 
	    WHERE privmsgs_text_id = $privmsgs_id";
	if ( !( $result = $db->sql_query($sql) ) )
	{
		mo_log("Get PRIVMSGS_TEXT_TABLE failed.\n\t$sql");
		return;
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	return array($row['privmsgs_bbcode_uid'], $row['privmsgs_text']);
}

function mo_get_last_privmsgs_time()
{
	global $db;
	global $userdata;
	
	$sql = "SELECT MAX(privmsgs_date) AS last_post_time 
	    FROM " . PRIVMSGS_TABLE . " 
	    WHERE privmsgs_from_userid = " . $userdata['user_id'];
	if ( !( $result = $db->sql_query($sql) ) )
	{
		mo_log("Get PRIVMSGS_TABLE failed.\n\t$sql");
		return;
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	return $row['last_post_time'];
}


function mo_update_forums($case, $forum_id, $post_id)
{
	global $db;
	
	if ( $case == 'new' )
	{
		$set_sql = "forum_topics = forum_topics + 1, 
		    forum_posts = forum_posts + 1, 
		    forum_last_post_id = $post_id";
	}
	elseif ( $case == 'reply' )
	{
		$set_sql = "forum_posts = forum_posts + 1, 
		    forum_last_post_id = $post_id";
	}
	else
	{
		return;
	}
	
	$sql = "UPDATE " . FORUMS_TABLE . " 
	    SET $set_sql 
	    WHERE forum_id = $forum_id";
	if ( !$db->sql_query($sql) )
	{
		mo_log("Update FORUMS_TABLE failed.\n\t$sql");
	}
}

function mo_update_topics($case, $topic_id, $post_id = 0)
{
	global $db;
	
	if ( $case == 'new' )
	{
		$set_sql = "topic_first_post_id = $post_id, 
	            topic_last_post_id = $post_id";
	}
	elseif ( $case == 'reply' )
	{
		$set_sql = "topic_replies = topic_replies + 1, 
	            topic_last_post_id = $post_id";
	}
	elseif ( $case == 'view' )
	{
		$set_sql = "topic_views = topic_views + 1";
	}
	else
	{
		return;
	}
	
	$sql = "UPDATE " . TOPICS_TABLE . " 
	    SET $set_sql 
	    WHERE topic_id = $topic_id";
	if ( !$db->sql_query($sql) )
	{
		mo_log("Update TOPICS_TABLE failed.\n\t$sql");
	}
}

function mo_update_privmsgs($case, $privmsgs_id = 0)
{
	global $db;
	global $userdata;
	
	if ( $case == 'privmsgs_inbox' )
	{
		$set_sql = "privmsgs_type = " . PRIVMSGS_UNREAD_MAIL;
		$where_sql = "privmsgs_type = " . PRIVMSGS_NEW_MAIL . " 
		    AND privmsgs_to_userid = " . $userdata['user_id'];
	}
	elseif ( $case == 'privmsgs_view' )
	{
		$set_sql = "privmsgs_type = " . PRIVMSGS_READ_MAIL;
		$where_sql = "privmsgs_id = $privmsgs_id 
		    AND privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . " 
		    AND privmsgs_to_userid = " . $userdata['user_id'];
	}
	elseif ( $case == 'privmsgs_received' )
	{
		$set_sql = "privmsgs_type = " . PRIVMSGS_SAVED_IN_MAIL;
		$where_sql = "privmsgs_id = $privmsgs_id 
		    AND privmsgs_type = " . PRIVMSGS_READ_MAIL . " 
		    AND privmsgs_to_userid = " . $userdata['user_id'];
	}
	elseif ( $case == 'privmsgs_sent' )
	{
		$set_sql = "privmsgs_type = " . PRIVMSGS_SAVED_OUT_MAIL;
		$where_sql = "privmsgs_id = $privmsgs_id 
		    AND privmsgs_type = " . PRIVMSGS_SENT_MAIL . " 
		    AND privmsgs_from_userid = " . $userdata['user_id'];
	}
	else
	{
		return;
	}
	
	$sql = "UPDATE " . PRIVMSGS_TABLE . " 
	    SET $set_sql 
	    WHERE $where_sql";
	if ( !$db->sql_query($sql) )
	{
		mo_log("Update PRIVMSGS_TABLE failed.\n\t$sql");
	}
}

function mo_update_users($case, $user_id, $time = 0, $password = '', $email = '')
{
	global $db;
	global $userdata;
	
	if ( $case == 'new' || $case == 'reply' )
	{
		$set_sql = "user_posts = user_posts + 1";
	}
	elseif ( $case == 'privmsgs_new' || $case == 'privmsgs_reply' )
	{
		$set_sql = "user_new_privmsg = user_new_privmsg + 1, 
		    user_last_privmsg = $time";
	}
	elseif ( $case == 'privmsgs_alert' )
	{
		$set_sql = "user_last_privmsg = $time";
	}
	elseif ( $case == 'privmsgs_inbox' )
	{
		$set_sql = "user_unread_privmsg = user_unread_privmsg 
		        + user_new_privmsg, user_new_privmsg = 0, 
		    user_last_privmsg = $time";
	}
	elseif ( $case == 'privmsgs_view_unread' || $case == 'privmsgs_remove_unread' )
	{
		$set_sql = "user_unread_privmsg = user_unread_privmsg - 1, 
		    user_last_privmsg = $time";
	}
	elseif ( $case == 'privmsgs_remove_new' )
	{
		$set_sql = "user_new_privmsg = user_new_privmsg - 1, 
		    user_last_privmsg = $time";
	}
	elseif ( $case == 'privmsgs' )
	{
		$set_sql = "user_last_privmsg = $time";
	}
	elseif ( $case == 'profile' )
	{
		$set_sql = "user_password = '" . $password . "', 
		    user_email = '" . $email . "'";
	}
	else
	{
		return;
	}
	
	$sql = "UPDATE " . USERS_TABLE . " 
	    SET $set_sql 
	    WHERE user_id = $user_id";
	if ( !$db->sql_query($sql) )
	{
		mo_log("Update USERS_TABLE failed.\n\t$sql");
	}
}


function mo_insert_topics($forum_id, $topic_title, $topic_time)
{
	global $db;
	global $userdata;
	
	$sql = "INSERT INTO " . TOPICS_TABLE . " 
	        ( forum_id, topic_title, topic_poster, topic_time ) 
	    VALUES ( $forum_id, '" . $topic_title . "', 
	        " . $userdata['user_id'] . ", $topic_time )";
	if ( !$db->sql_query($sql) )
	{
		mo_log("Insert into TOPICS_TABLE failed.\n\t$sql");
		return;
	}
	$topic_id = $db->sql_nextid();
	
	return $topic_id;
}

function mo_insert_posts($topic_id, $forum_id, $post_time)
{
	global $db;
	global $userdata;
	global $user_ip;
	
	list($enable_bbcode, $enable_html, $enable_smilies, $enable_sig) = 
	    get_user_enable();
	
	$sql = "INSERT INTO " . POSTS_TABLE . " 
	        ( topic_id, forum_id, poster_id, post_time, 
	        poster_ip, post_username, enable_bbcode, enable_html, 
	        enable_smilies, enable_sig ) 
	    VALUES ( $topic_id, $forum_id, " . $userdata['user_id'] . ", $post_time, 
	        '" . $user_ip . "', '', $enable_bbcode, $enable_html, 
	        $enable_smilies, $enable_sig )";
	if ( !$db->sql_query($sql) )
	{
		mo_log("Insert into POSTS_TABLE failed.\n\t$sql");
		return;
	}
	$post_id = $db->sql_nextid();
	
	return $post_id;
}

function mo_insert_posts_text($post_id, $bbcode_uid, $post_subject, $post_text)
{
	global $db;
	
	$sql = "INSERT INTO " . POSTS_TEXT_TABLE . " 
	        ( post_id, bbcode_uid, post_subject, post_text ) 
	    VALUES ( $post_id, '" . $bbcode_uid . "', 
	        '" . $post_subject . "', '" . $post_text . "' )";
	if ( !$db->sql_query($sql) )
	{
		mo_log("Insert into POSTS_TEXT_TABLE failed.\n\t$sql");
		return;
	}
	
	return $post_id;
}

function mo_insert_privmsgs($type, $subject, $from_userid, $to_userid, $date)
{
	global $db;
	global $userdata;
	
	list($enable_bbcode, $enable_html, $enable_smilies, $enable_sig) = 
	    get_user_enable();
	
	$sql = "INSERT INTO " . PRIVMSGS_TABLE . " 
	        ( privmsgs_type, privmsgs_subject, 
	        privmsgs_from_userid, privmsgs_to_userid, 
	        privmsgs_date, privmsgs_ip, 
	        privmsgs_enable_bbcode, privmsgs_enable_html, 
	        privmsgs_enable_smilies, privmsgs_attach_sig ) 
	    VALUES ( $type, '" . $subject . "', $from_userid, $to_userid, 
	        $date, '" . $userdata['session_ip'] . "', $enable_bbcode, $enable_html, 
	        $enable_smilies, $enable_sig )";
	if ( !$db->sql_query($sql) )
	{
		mo_log("Insert into PRIVMSGS_TABLE failed.\n\t$sql");
		return;
	}
	$privmsgs_id = $db->sql_nextid();
	
	return $privmsgs_id;
}

function mo_insert_privmsgs_text($text_id, $bbcode_uid, $text)
{
	global $db;
	
	$sql = "INSERT INTO " . PRIVMSGS_TEXT_TABLE . " 
	        ( privmsgs_text_id, privmsgs_bbcode_uid, privmsgs_text ) 
	    VALUES ( $text_id, '" . $bbcode_uid . "', '" . $text . "' )";
	if ( !$db->sql_query($sql) )
	{
		mo_log("Insert into PRIVMSGS_TEXT_TABLE failed.\n\t$sql");
		return;
	}
	
	return $text_id;
}

function mo_insert_users($username, $password, $time, $email)
{
	global $db;
	global $board_config;
	global $mo_enable_nuke;
	
	$sql = "SELECT MAX(user_id) AS total 
	    FROM " . USERS_TABLE;
	if ( !( $result = $db->sql_query($sql) ) )
	{
		mo_log("Get USERS_TABLE failed.\n\t$sql");
		return;
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$user_id = $row['total']+1;
	
	if ( $mo_enable_nuke )
	{
		$sql = "INSERT INTO " . USERS_TABLE . " 
		        ( user_id, username, user_password, user_regdate, 
		        user_timezone, user_lang, user_dateformat, user_viewemail, 
		        user_allowhtml, user_allowbbcode, user_allowsmile, user_notify, 
		        user_avatar, user_email, user_icq, user_website, 
		        user_from, user_sig, user_aim, user_yim, 
		        user_msnm, user_occ, user_interests ) 
		    VALUES ( $user_id, '" . $username . "', '" . $password . "', 
		        '" . $time . "', 
		        '" . $board_config['board_timezone'] . "', 
		        '" . $board_config['default_lang'] . "', 
		        '" . $board_config['default_dateformat'] . "', '0', 
		        '" . $board_config['allow_html'] . "', 
		        '" . $board_config['allow_bbcode'] . "', 
		        '" . $board_config['allow_smilies'] . "', '0', 
		        'blank.gif', '" . $email . "', '', '', 
		        '', '', '', '', 
		        '', '', '' )";
	}
	else
	{
		$sql = "INSERT INTO " . USERS_TABLE . " 
		        ( user_id, username, user_password, user_regdate, 
		        user_timezone, user_style, user_lang, user_dateformat, 
		        user_viewemail, user_attachsig, user_allowhtml, user_allowbbcode, 
		        user_allowsmile, user_notify, user_notify_pm, user_popup_pm, 
		        user_avatar, user_email, user_icq, user_website, 
		        user_from, user_sig, user_sig_bbcode_uid, user_aim, 
		        user_yim, user_msnm, user_occ, user_interests, 
		        user_actkey ) 
		    VALUES ( $user_id, '" . $username . "', '" . $password . "', $time, 
		        '" . $board_config['board_timezone'] . "', 
		        '" . $board_config['default_style'] . "', 
		        '" . $board_config['default_lang'] . "', 
		        '" . $board_config['default_dateformat'] . "', 
		        '0', '" . $board_config['allow_sig'] . "', 
		        '" . $board_config['allow_html'] . "', 
		        '" . $board_config['allow_bbcode'] . "', 
		        '" . $board_config['allow_smilies'] . "', '0', '1', '1', 
		        '', '" . $email . "', '', '', 
		        '', '', '', '', 
		        '', '', '', '', 
		        '' )";
	}
	if ( !$db->sql_query($sql) )
	{
		mo_log("Insert into USERS_TABLE failed.\n\t$sql");
		return;
	}
	
	return $user_id;
}

function mo_insert_groups()
{
	global $db;
	
	$sql = "INSERT INTO " . GROUPS_TABLE . " 
	        ( group_name, group_description, group_single_user, group_moderator ) 
	    VALUES ( '', 'Personal User', '1', '0' )";
	if ( !$db->sql_query($sql) )
	{
		mo_log("Insert into GROUPS_TABLE failed.\n\t$sql");
		return;
	}
	$group_id = $db->sql_nextid();
	
	return $group_id;
}

function mo_insert_user_group($user_id, $group_id)
{
	global $db;
	
	$sql = "INSERT INTO " . USER_GROUP_TABLE . " 
	        ( user_id, group_id, user_pending ) 
	    VALUES ( $user_id, $group_id, '0' )";
	if ( !$db->sql_query($sql) )
	{
		mo_log("Insert into USER_GROUP_TABLE failed.\n\t$sql");
		return;
	}
	
	return $user_id;
}


function mo_delete_topics($topic_id)
{
	global $db;
	
	$sql = "DELETE FROM " . TOPICS_TABLE . " 
	    WHERE topic_id = $topic_id";
	if ( !$db->sql_query($sql) )
	{
		mo_log("Delete TOPICS_TABLE failed.\n\t$sql");
	}
}

function mo_delete_posts($post_id)
{
	global $db;
	
	$sql = "DELETE FROM " . POSTS_TABLE . " 
	    WHERE post_id = $post_id";
	if ( !$db->sql_query($sql) )
	{
		mo_log("Delete POSTS_TABLE failed.\n\t$sql");
	}
}

function mo_delete_privmsgs($privmsgs_id)
{
	global $db;
	global $userdata;
	
	$sql = "DELETE FROM " . PRIVMSGS_TABLE . " 
	    WHERE privmsgs_id = $privmsgs_id";
	if ( !$db->sql_query($sql) )
	{
		mo_log("Delete PRIVMSGS_TABLE failed.\n\t$sql");
	}
}

function mo_delete_privmsgs_text($privmsgs_id)
{
	global $db;
	
	$sql = "DELETE FROM " . PRIVMSGS_TEXT_TABLE . " 
	    WHERE privmsgs_text_id = $privmsgs_id";
	if ( !$db->sql_query($sql) )
	{
		mo_log("Delete PRIVMSGS_TEXT_TABLE failed.\n\t$sql");
	}
}

function mo_delete_users($user_id)
{
	global $db;
	
	$sql = "DELETE FROM " . USERS_TABLE . " 
	    WHERE user_id = $user_id";
	if ( !$db->sql_query($sql) )
	{
		mo_log("Delete USERS_TABLE failed.\n\t$sql");
	}
}

function mo_delete_groups($group_id)
{
	global $db;
	
	$sql = "DELETE FROM " . GROUPS_TABLE . " 
	    WHERE group_id = $group_id";
	if ( !$db->sql_query($sql) )
	{
		mo_log("Delete GROUPS_TABLE failed.\n\t$sql");
	}
}
?>
