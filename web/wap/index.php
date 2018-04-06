<?php
require 'sys.php';

$page_id = PAGE_INDEX;

require 'lib.php';


$rows = array();
$rows = mo_select_categories();


mo_echo_header($mo_lang['home']);
mo_echo_paragraph_begin();

if ( $userdata['session_logged_in'] && $userdata['user_new_privmsg'] )
{
	if ( $userdata['user_last_privmsg'] > $userdata['user_lastvisit'] )
	{
		mo_update_users('privmsgs_alert', $userdata['user_id'], 
		    $userdata['user_lastvisit']);
	}
	
	mo_echo_url($mo_lang['pm_alert'], 'pmin.php');
	mo_echo_paragraph_end();
	mo_echo_hr();
	mo_echo_paragraph_begin();
}

if ( $board_config['site_desc'] )
{
	mo_echo_desc();
	mo_echo_br();
}

$num = 0;
for ( $i = 0; $i < count($rows); $i++ )
{
	$forum_rows = array();
	$forum_rows = mo_select_forums($rows[$i]['cat_id']);
	
	$is_auth = array();
	$is_auth = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata, $forum_rows);
	
	$forum_num = 0;
	for ( $j = 0; $j < count($forum_rows); $j++ )
	{
		if ( !$is_auth[$forum_rows[$j]['forum_id']]['auth_view'] )
		{
			continue;
		}
		
		$forum_num++;
	}
	
	if ( !$forum_num )
	{
		continue;
	}
	
	if ( $num )
	{
		mo_echo_br();
	}
	mo_echo_url(mo_prepare_message($rows[$i]['cat_title']), 'forum.php', 
	    'cat_id', $rows[$i]['cat_id']);
	
	$num++;
}

mo_echo_paragraph_end();
mo_echo_hr();
mo_echo_paragraph_begin();

mo_echo_url($mo_lang['menu'], 'menu.php');
mo_echo_sp();
mo_echo_url($mo_lang['home']);

mo_echo_br();
mo_echo_base_link();

mo_echo_paragraph_end();
mo_echo_footer();
?>
