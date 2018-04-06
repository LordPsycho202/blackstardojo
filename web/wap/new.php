<?php
require 'sys.php';

$page_id = PAGE_POSTING;

require 'lib.php';

$opt['forum_id'] = mo_get_get_opt('forum_id');
$opt['topic_index'] = mo_get_get_opt('topic_index');
$opt['subject'] = mo_prepare_message1(mo_get_post_opt('subject'));
$opt['message'] = mo_prepare_message(mo_get_post_opt('message'));
$opt['new'] = mo_get_get_opt('new');

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
$is_auth = auth(AUTH_POST, $opt['forum_id'], $userdata);

if ( !$is_auth['auth_post'] && !$userdata['session_logged_in'] )
{
	mo_echo_header($mo_lang['new'], 'login.php', $mo_var['refresh_timer'], 
	    'redirect', 'new.php', 'forum_id', $opt['forum_id'], 
	    'topic_index', $opt['topic_index'], 'new', $opt['new']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['login_entry']);
	mo_echo_br();
	mo_echo_url($mo_lang['login'], 'login.php', 'redirect', 'new.php', 
	    'forum_id', $opt['forum_id'], 'topic_index', $opt['topic_index'], 
	    'new', $opt['new']);
	mo_echo_paragraph_end();
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['topics'], 'topic.php', 'forum_id', $opt['forum_id'], 
	    'index', $opt['topic_index']);
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

if ( !$is_auth['auth_post'] )
{
	mo_echo_header($mo_lang['new']);
	mo_echo_paragraph_begin();
	if ( $auth_post == AUTH_REG )
	{
		mo_echo_msg($mo_lang['registered_post_only']);
		mo_echo_paragraph_end();
		mo_echo_hr();
		mo_echo_paragraph_begin();
	}
	elseif ( $auth_post == AUTH_ACL )
	{
		mo_echo_msg($mo_lang['granted_post_only']);
		mo_echo_paragraph_end();
		mo_echo_hr();
		mo_echo_paragraph_begin();
	}
	elseif ( $auth_post == AUTH_MOD )
	{
		mo_echo_msg($mo_lang['moderator_post_only']);
		mo_echo_paragraph_end();
		mo_echo_hr();
		mo_echo_paragraph_begin();
	}
	mo_echo_url($mo_lang['topics'], 'topic.php', 'forum_id', $opt['forum_id'], 
	    'index', $opt['topic_index']);
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

if ( !$opt['new'] )
{
	mo_echo_header($mo_lang['new']);
	mo_echo_new($opt['forum_id'], $opt['topic_index']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['topics'], 'topic.php', 'forum_id', $opt['forum_id'], 
	    'index', $opt['topic_index']);
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

if ( !$opt['subject'] )
{
	mo_echo_header($mo_lang['new']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['subject_empty']);
	mo_echo_paragraph_end();
	mo_echo_new($opt['forum_id'], $opt['topic_index'], 
	    $opt['subject'], $opt['message'], $opt['new']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['topics'], 'topic.php', 'forum_id', $opt['forum_id'], 
	    'index', $opt['topic_index']);
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

if ( !$opt['message'] )
{
	mo_echo_header($mo_lang['new']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['message_empty']);
	mo_echo_paragraph_end();
	mo_echo_new($opt['forum_id'], $opt['topic_index'], 
	    $opt['subject'], $opt['message'], $opt['new']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['topics'], 'topic.php', 'forum_id', $opt['forum_id'], 
	    'index', $opt['topic_index']);
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

$time = time();
if ( $time - mo_get_last_post_time() < $board_config['flood_interval'] )
{
	mo_echo_header($mo_lang['new']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['flood_error']);
	mo_echo_paragraph_end();
	mo_echo_new($opt['forum_id'], $opt['topic_index'], 
	    $opt['subject'], $opt['message'], $opt['new']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['topics'], 'topic.php', 'forum_id', $opt['forum_id'], 
	    'index', $opt['topic_index']);
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}


$topic_id = mo_insert_topics($opt['forum_id'], $opt['subject'], $time);
if ( !$topic_id )
{
	mo_echo_header($mo_lang['new']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['new_failed']);
	mo_echo_paragraph_end();
	mo_echo_new($opt['forum_id'], $opt['topic_index'], 
	    $opt['subject'], $opt['message'], $opt['new']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['topics'], 'topic.php', 'forum_id', $opt['forum_id'], 
	    'index', $opt['topic_index']);
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

$post_id = mo_insert_posts($topic_id, $opt['forum_id'], $time);
if ( !$post_id )
{
	mo_delete_topics($topic_id);
	
	mo_echo_header($mo_lang['new']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['new_failed']);
	mo_echo_paragraph_end();
	mo_echo_new($opt['forum_id'], $opt['topic_index'], 
	    $opt['subject'], $opt['message'], $opt['new']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['topics'], 'topic.php', 'forum_id', $opt['forum_id'], 
	    'index', $opt['topic_index']);
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

$post_text_id = mo_insert_posts_text($post_id, make_bbcode_uid(), $opt['subject'], 
    mo_post_from_mobile($opt['message']));
if ( !$post_text_id )
{
	mo_delete_topics($topic_id);
	mo_delete_posts($post_id);
	
	mo_echo_header($mo_lang['new']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['new_failed']);
	mo_echo_paragraph_end();
	mo_echo_new($opt['forum_id'], $opt['topic_index'], 
	    $opt['subject'], $opt['message'], $opt['new']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['topics'], 'topic.php', 'forum_id', $opt['forum_id'], 
	    'index', $opt['topic_index']);
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

add_search_words('single', $post_id, stripslashes($opt['message']), 
    stripslashes($opt['subject']));

mo_update_forums('new', $opt['forum_id'], $post_id);
mo_update_topics('new', $topic_id, $post_id, $post_id);
mo_update_users('new', $userdata['user_id']);


mo_echo_header($mo_lang['new'], 'post.php', $mo_var['refresh_timer'], 
    'topic_id', $topic_id);
mo_echo_paragraph_begin();

mo_echo_msg($mo_lang['new_successfully']);

mo_echo_br();
mo_echo_url($mo_lang['view'], 'post.php', 'topic_id', $topic_id);

mo_echo_paragraph_end();
mo_echo_hr();
mo_echo_paragraph_begin();

mo_echo_url($mo_lang['topics'], 'topic.php', 'forum_id', $opt['forum_id'], 
    'index', $opt['topic_index']);

mo_echo_br();
mo_echo_url($mo_lang['menu'], 'menu.php');
mo_echo_sp();
mo_echo_url($mo_lang['home'], 'index.php');

mo_echo_paragraph_end();
mo_echo_footer();
?>
