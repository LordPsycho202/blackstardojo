<?php
require 'sys.php';

$page_id = PAGE_POSTING;

require 'lib.php';

$opt['topic_id'] = mo_get_get_opt('topic_id');
$opt['post_index'] = mo_get_get_opt('post_index');
$opt['topic_index'] = mo_get_get_opt('topic_index');
$opt['subject'] = mo_prepare_message1(mo_get_post_opt('subject'));
$opt['message'] = mo_prepare_message(mo_get_post_opt('message'));
$opt['reply'] = mo_get_get_opt('reply');

if ( !$opt['topic_id'] )
{
	mo_echo_header($mo_lang['post'], 'index.php');
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['topic_not_exist']);
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


list($forum_id, $topic_replies) = mo_get_topics($opt['topic_id']);
list($cat_id, $forum_topics, $auth_read, $auth_post, $auth_reply) = 
    mo_get_forums($forum_id);

$is_auth = array();
$is_auth = auth(AUTH_REPLY, $forum_id, $userdata);

if ( !$is_auth['auth_reply'] && !$userdata['session_logged_in'] )
{
	mo_echo_header($mo_lang['reply'], 'login.php', $mo_var['refresh_timer'], 
	    'redirect', 'reply.php', 'topic_id', $opt['topic_id'], 
	    'post_index', $opt['post_index'], 'topic_index', $opt['topic_index'], 
	    'reply', $opt['reply']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['login_entry']);
	mo_echo_br();
	mo_echo_url($mo_lang['login'], 'login.php', 'redirect', 'reply.php', 
	    'topic_id', $opt['topic_id'], 'post_index', $opt['post_index'], 
	    'topic_index', $opt['topic_index'], 'reply', $opt['reply']);
	mo_echo_paragraph_end();
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['post'], 'post.php', 'topic_id', $opt['topic_id'], 
	    'index', $opt['post_index'], 'topic_index', $opt['topic_index']);
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

if ( !$is_auth['auth_reply'] )
{
	mo_echo_header($mo_lang['reply']);
	mo_echo_paragraph_begin();
	if ( $auth_reply == AUTH_REG )
	{
		mo_echo_msg($mo_lang['registered_reply_only']);
		mo_echo_paragraph_end();
		mo_echo_hr();
		mo_echo_paragraph_begin();
	}
	elseif ( $auth_reply == AUTH_ACL )
	{
		mo_echo_msg($mo_lang['granted_reply_only']);
		mo_echo_paragraph_end();
		mo_echo_hr();
		mo_echo_paragraph_begin();
	}
	elseif ( $auth_reply == AUTH_MOD )
	{
		mo_echo_msg($mo_lang['moderator_reply_only']);
		mo_echo_paragraph_end();
		mo_echo_hr();
		mo_echo_paragraph_begin();
	}
	mo_echo_url($mo_lang['post'], 'post.php', 'topic_id', $opt['topic_id'], 
	    'index', $opt['post_index'], 'topic_index', $opt['topic_index']);
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

if ( !$opt['reply'] )
{
	mo_echo_header($mo_lang['reply']);
	mo_echo_reply($opt['topic_id'], $opt['post_index'], $opt['topic_index']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['post'], 'post.php', 'topic_id', $opt['topic_id'], 
	    'index', $opt['post_index'], 'topic_index', $opt['topic_index']);
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
	mo_echo_header($mo_lang['reply']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['message_empty']);
	mo_echo_paragraph_end();
	mo_echo_reply($opt['topic_id'], $opt['post_index'], $opt['topic_index'], 
	    $opt['subject'], $opt['message'], $opt['reply']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['post'], 'post.php', 'topic_id', $opt['topic_id'], 
	    'index', $opt['post_index'], 'topic_index', $opt['topic_index']);
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
	mo_echo_header($mo_lang['reply']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['flood_error']);
	mo_echo_paragraph_end();
	mo_echo_reply($opt['topic_id'], $opt['post_index'], $opt['topic_index'], 
	    $opt['subject'], $opt['message'], $opt['reply']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['post'], 'post.php', 'topic_id', $opt['topic_id'], 
	    'index', $opt['post_index'], 'topic_index', $opt['topic_index']);
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}


$post_id = mo_insert_posts($opt['topic_id'], $forum_id, $time);
if ( !$post_id )
{
	mo_echo_header($mo_lang['reply']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['reply_failed']);
	mo_echo_paragraph_end();
	mo_echo_reply($opt['topic_id'], $opt['post_index'], $opt['topic_index'], 
	    $opt['subject'], $opt['message'], $opt['reply']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['post'], 'post.php', 'topic_id', $opt['topic_id'], 
	    'index', $opt['post_index'], 'topic_index', $opt['topic_index']);
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
	mo_delete_posts($post_id);
	
	mo_echo_header($mo_lang['reply']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['reply_failed']);
	mo_echo_paragraph_end();
	mo_echo_reply($opt['topic_id'], $opt['post_index'], $opt['topic_index'], 
	    $opt['subject'], $opt['message'], $opt['reply']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['post'], 'post.php', 'topic_id', $opt['topic_id'], 
	    'index', $opt['post_index'], 'topic_index', $opt['topic_index']);
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

mo_update_forums('reply', $forum_id, $post_id);
mo_update_topics('reply', $opt['topic_id'], $post_id);
mo_update_users('reply', $userdata['user_id']);


mo_echo_header($mo_lang['reply'], 'post.php', $mo_var['refresh_timer'], 
    'topic_id', $opt['topic_id'], 'index', $topic_replies+1);
mo_echo_paragraph_begin();

mo_echo_msg($mo_lang['reply_successfully']);

mo_echo_br();
mo_echo_url($mo_lang['view'], 'post.php', 'topic_id', $opt['topic_id'], 
    'index', $topic_replies+1);

mo_echo_paragraph_end();
mo_echo_hr();
mo_echo_paragraph_begin();

mo_echo_url($mo_lang['post'], 'post.php', 'topic_id', $opt['topic_id'], 
    'index', $opt['post_index'], 'topic_index', $opt['topic_index']);

mo_echo_br();
mo_echo_url($mo_lang['menu'], 'menu.php');
mo_echo_sp();
mo_echo_url($mo_lang['home'], 'index.php');

mo_echo_paragraph_end();
mo_echo_footer();
?>
