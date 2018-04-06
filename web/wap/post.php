<?php
require 'sys.php';

$page_id = PAGE_INDEX;

require 'lib.php';

$opt['topic_id'] = mo_get_get_opt('topic_id');
$opt['index'] = mo_get_get_opt('index');
$opt['topic_index'] = mo_get_get_opt('topic_index');

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


list($post_id, $poster_id) = mo_get_posts($opt['topic_id'], $opt['index']);
list($subject, $message) = mo_get_posts_text($post_id);
list($username, $password, $email) = mo_get_users($poster_id);
list($forum_id, $topic_replies) = mo_get_topics($opt['topic_id']);
list($cat_id, $forum_topics, $auth_read, $auth_post, $auth_reply) = 
    mo_get_forums($forum_id);

$is_auth = array();
$is_auth = auth(AUTH_READ, $forum_id, $userdata);

if ( !$is_auth['auth_read'] && !$userdata['session_logged_in'] )
{
	mo_echo_header($mo_lang['post'], 'login.php', $mo_var['refresh_timer'], 
	    'redirect', 'post.php', 'topic_id', $opt['topic_id'], 
	    'index', $opt['index'], 'topic_index', $opt['topic_index']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['login_entry']);
	mo_echo_br();
	mo_echo_url($mo_lang['login'], 'login.php', 'redirect', 'post.php', 
	    'topic_id', $opt['topic_id'], 'index', $opt['index'], 
	    'topic_index', $opt['topic_index']);
	mo_echo_paragraph_end();
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['topics'], 'topic.php', 'forum_id', $forum_id, 
	    'index', $opt['topic_index']);
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
	mo_echo_header($mo_lang['post']);
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
	mo_echo_url($mo_lang['topics'], 'topic.php', 'forum_id', $forum_id, 
	    'index', $opt['topic_index']);
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}


if ( !$opt['index'] || !$opt['topic_index'] )
{
	mo_update_topics('view', $opt['topic_id']);
}


mo_echo_header($mo_lang['post']);
mo_echo_paragraph_begin();

mo_echo_url($mo_lang['author'] . ':');
mo_echo_br();
if ( $poster_id != $userdata['user_id'] && $poster_id != ANONYMOUS )
{
	mo_echo_url($username, 'viewprofile.php', 'user_id', $poster_id);
}
else
{
	mo_echo_msg($username);
}
if ( $subject )
{
	mo_echo_br();
	mo_echo_url($mo_lang['subject'] . ':');
	mo_echo_br();
	mo_echo_msg($subject);
}
mo_echo_br();
mo_echo_url($mo_lang['message'] . ':');
mo_echo_br();
mo_echo_msg($message);

mo_echo_paragraph_end();
mo_echo_hr();
mo_echo_paragraph_begin();

if ( $opt['index'] < $topic_replies )
{
	mo_echo_url($mo_lang['next'], 'post.php', 'topic_id', $opt['topic_id'], 
	    'index', $opt['index']+1, 'topic_index', $opt['topic_index']);
	mo_echo_sp();
}
mo_echo_url($mo_lang['topics'], 'topic.php', 'forum_id', $forum_id, 
    'index', $opt['topic_index']);
if ( $opt['index'] > 0 )
{
	mo_echo_sp();
	mo_echo_url($mo_lang['prev'], 'post.php', 'topic_id', $opt['topic_id'], 
	    'index', $opt['index']-1, 'topic_index', $opt['topic_index']);
}

if ( $opt['index'] < $topic_replies && $topic_replies >= $mo_var['long_topic'] )
{
	mo_echo_br();
	mo_echo_url($mo_lang['last'], 'post.php', 'topic_id', $opt['topic_id'], 
	    'index', $topic_replies, 'topic_index', $opt['topic_index']);
}
if ( $opt['index'] > 0 && $topic_replies >= $mo_var['long_topic'] )
{
	if ( $opt['index'] < $topic_replies )
	{
		mo_echo_sp();
	}
	else
	{
		mo_echo_br();
	}
	mo_echo_url($mo_lang['first'], 'post.php', 'topic_id', $opt['topic_id'], 
	    'index', 0, 'topic_index', $opt['topic_index']);
}

mo_echo_br();
mo_echo_url($mo_lang['reply'], 'reply.php', 'topic_id', $opt['topic_id'], 
    'post_index', $opt['index'], 'topic_index', $opt['topic_index']);

mo_echo_br();
mo_echo_url($mo_lang['menu'], 'menu.php');
mo_echo_sp();
mo_echo_url($mo_lang['home'], 'index.php');

mo_echo_paragraph_end();
mo_echo_footer();
?>
