<?php
require 'sys.php';

$page_id = PAGE_INDEX;

require 'lib.php';

$opt['forum_id'] = mo_get_get_opt('forum_id');
$opt['index'] = mo_get_get_opt('index');

if ( !$opt['forum_id'] )
{
	mo_echo_header($mo_lang['topics'], 'index.php');
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['forum_not_exist']);
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


list($cat_id, $forum_topics, $auth_read, $auth_post, $auth_reply) = 
    mo_get_forums($opt['forum_id']);

$is_auth = array();
$is_auth = auth(AUTH_READ, $opt['forum_id'], $userdata);

if ( !$is_auth['auth_read'] && !$userdata['session_logged_in'] )
{
	mo_echo_header($mo_lang['topics'], 'login.php', $mo_var['refresh_timer'], 
	    'redirect', 'topic.php', 'forum_id', $opt['forum_id'], 
	    'index', $opt['index']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['login_entry']);
	mo_echo_br();
	mo_echo_url($mo_lang['login'], 'login.php', 'redirect', 'topic.php', 
	    'forum_id', $opt['forum_id'], 'index', $opt['index']);
	mo_echo_paragraph_end();
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['forums'], 'forum.php', 'cat_id', $cat_id);
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

if ( !$is_auth['auth_read'] )
{
	mo_echo_header($mo_lang['topics']);
	mo_echo_paragraph_begin();
	if ( $auth_read == AUTH_REG )
	{
		mo_echo_msg($mo_lang['registered_read_only']);
		mo_echo_paragraph_end();
		mo_echo_hr();
		mo_echo_paragraph_begin();
	}
	elseif ( $auth_read == AUTH_ACL )
	{
		mo_echo_msg($mo_lang['granted_read_only']);
		mo_echo_paragraph_end();
		mo_echo_hr();
		mo_echo_paragraph_begin();
	}
	elseif ( $auth_read == AUTH_MOD )
	{
		mo_echo_msg($mo_lang['moderator_read_only']);
		mo_echo_paragraph_end();
		mo_echo_hr();
		mo_echo_paragraph_begin();
	}
	mo_echo_url($mo_lang['forums'], 'forum.php', 'cat_id', $cat_id);
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}


$rows = array();
$rows = mo_select_topics($opt['forum_id'], $opt['index']);

$num = count($rows);
if ( !$num )
{
	mo_echo_header($mo_lang['topics']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['topics_empty']);
	mo_echo_paragraph_end();
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['forums'], 'forum.php', 'cat_id', $cat_id);
	mo_echo_br();
	mo_echo_url($mo_lang['new'], 'new.php', 'forum_id', $opt['forum_id'], 
	    'topic_index', $opt['index']);
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}


mo_echo_header($mo_lang['topics']);
mo_echo_paragraph_begin();

for ( $i = 0; $i < $num; $i++ )
{
	if ( $i )
	{
		mo_echo_br();
	}
	mo_echo_url($rows[$i]['topic_title'], 'post.php', 
	    'topic_id', $rows[$i]['topic_id'], 'topic_index', $opt['index']);
}

mo_echo_paragraph_end();
mo_echo_hr();
mo_echo_paragraph_begin();

if ( $opt['index'] < $forum_topics - $mo_var['row_size'] )
{
	mo_echo_url($mo_lang['next'], 'topic.php', 'forum_id', $opt['forum_id'], 
	    'index', $opt['index'] + $mo_var['row_size']);
	mo_echo_sp();
}
mo_echo_url($mo_lang['forums'], 'forum.php', 'cat_id', $cat_id);
if ( $opt['index'] >= $mo_var['row_size'] )
{
	mo_echo_sp();
	mo_echo_url($mo_lang['prev'], 'topic.php', 'forum_id', $opt['forum_id'], 
	    'index', $opt['index'] - $mo_var['row_size']);
}

if ( $opt['index'] < $forum_topics - $mo_var['row_size'] && 
    $forum_topics >= $mo_var['long_forum'] )
{
	mo_echo_br();
	mo_echo_url($mo_lang['last'], 'topic.php', 'forum_id', $opt['forum_id'], 
	    'index', mo_get_last($forum_topics));
}
if ( $opt['index'] >= $mo_var['row_size'] && $forum_topics >= $mo_var['long_forum'] )
{
	if ( $opt['index'] < $forum_topics - $mo_var['row_size'] )
	{
		mo_echo_sp();
	}
	else
	{
		mo_echo_br();
	}
	mo_echo_url($mo_lang['first'], 'topic.php', 'forum_id', $opt['forum_id'], 
	    'index', 0);
}

mo_echo_br();
mo_echo_url($mo_lang['new'], 'new.php', 'forum_id', $opt['forum_id'], 
    'topic_index', $opt['index']);

mo_echo_br();
mo_echo_url($mo_lang['menu'], 'menu.php');
mo_echo_sp();
mo_echo_url($mo_lang['home'], 'index.php');

mo_echo_paragraph_end();
mo_echo_footer();
?>
