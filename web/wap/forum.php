<?php
require 'sys.php';

$page_id = PAGE_INDEX;

require 'lib.php';

$opt['cat_id'] = mo_get_get_opt('cat_id');

if ( !$opt['cat_id'] )
{
	mo_echo_header($mo_lang['forums'], 'index.php');
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['cat_not_exist']);
	mo_echo_paragraph_end();
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}


$rows = array();
$rows = mo_select_forums($opt['cat_id']);

$is_auth = array();
$is_auth = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata, $rows);


mo_echo_header($mo_lang['forums']);
mo_echo_paragraph_begin();

$num = 0;
for ( $i = 0; $i < count($rows); $i++ )
{
	if ( !$is_auth[$rows[$i]['forum_id']]['auth_view'] )
	{
		continue;
	}
	
	if ( $num )
	{
		mo_echo_br();
	}
	if ( !$is_auth[$rows[$i]['forum_id']]['auth_read'] && 
	    !$userdata['session_logged_in'] )
	{
		mo_echo_url(mo_prepare_message($rows[$i]['forum_name']), 'login.php', 
		    'redirect', 'topic.php', 'forum_id', $rows[$i]['forum_id']);
	}
	else
	{
		mo_echo_url(mo_prepare_message($rows[$i]['forum_name']), 'topic.php', 
		    'forum_id', $rows[$i]['forum_id']);
	}
	
	if ( $rows[$i]['forum_desc'] )
	{
		mo_echo_br();
		mo_echo_msg(mo_prepare_message($rows[$i]['forum_desc']));
	}
	
	$num++;
}

if ( !$num )
{
	mo_echo_msg($mo_lang['forums_empty']);
}

mo_echo_paragraph_end();
mo_echo_hr();
mo_echo_paragraph_begin();

mo_echo_url($mo_lang['menu'], 'menu.php');
mo_echo_sp();
mo_echo_url($mo_lang['home'], 'index.php');

mo_echo_paragraph_end();
mo_echo_footer();
?>
