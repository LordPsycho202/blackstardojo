<?php
require 'sys.php';

$page_id = PAGE_REGISTER;

require 'lib.php';

$opt['username'] = mo_get_post_opt('username');
$opt['email'] = mo_get_post_opt('email');
$opt['password'] = mo_get_post_opt('password');
$opt['password_conf'] = mo_get_post_opt('password_conf');
$opt['register'] = mo_get_get_opt('register');


if ( !$mo_enable_register )
{
	mo_echo_header($mo_lang['register'], 'index.php');
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

if ( !$opt['register'] )
{
	mo_echo_header($mo_lang['register']);
	mo_echo_register();
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

if ( !$opt['username'] || !$opt['email'] || !$opt['password'] )
{
	mo_echo_header($mo_lang['register']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['field_required']);
	mo_echo_paragraph_end();
	mo_echo_register($opt['username'], $opt['email'], $opt['register']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

if ( $opt['password'] != $opt['password_conf'] )
{
	mo_echo_header($mo_lang['register']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['password_mismatch']);
	mo_echo_paragraph_end();
	mo_echo_register($opt['username'], $opt['email'], $opt['register']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

$user_id = mo_get_user_id($opt['username']);
if ( $user_id )
{
	mo_echo_header($mo_lang['register']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['username_taken']);
	mo_echo_paragraph_end();
	mo_echo_register($opt['username'], $opt['email'], $opt['register']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

$user_id = mo_get_user_id($opt['username'], $opt['password'], $opt['email']);
if ( $user_id )
{
	mo_echo_header($mo_lang['register']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['email_taken']);
	mo_echo_paragraph_end();
	mo_echo_register($opt['username'], $opt['email'], $opt['register']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}


$opt['password'] = md5($opt['password']);
$time = time();
if ( $mo_enable_nuke )
{
	$time = date("M d, Y", $time);
}
$user_id = mo_insert_users($opt['username'], $opt['password'], $time, $opt['email']);
if ( !$user_id )
{
	mo_echo_header($mo_lang['register']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['register_failed']);
	mo_echo_paragraph_end();
	mo_echo_register($opt['username'], $opt['email'], $opt['register']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

if ( !$mo_enable_nuke )
{
	$group_id = mo_insert_groups();
	if ( !$group_id )
	{
		mo_delete_users($user_id);
		
		mo_echo_header($mo_lang['register']);
		mo_echo_paragraph_begin();
		mo_echo_msg($mo_lang['register_failed']);
		mo_echo_paragraph_end();
		mo_echo_register($opt['username'], $opt['email'], $opt['register']);
		mo_echo_hr();
		mo_echo_paragraph_begin();
		mo_echo_url($mo_lang['menu'], 'menu.php');
		mo_echo_sp();
		mo_echo_url($mo_lang['home'], 'index.php');
		mo_echo_paragraph_end();
		mo_echo_footer();
		exit(0);
	}
	
	$user_group_id = mo_insert_user_group($user_id, $group_id);
	if ( !$user_group_id )
	{
		mo_delete_users($user_id);
		mo_delete_groups($group_id);
		
		mo_echo_header($mo_lang['register']);
		mo_echo_paragraph_begin();
		mo_echo_msg($mo_lang['register_failed']);
		mo_echo_paragraph_end();
		mo_echo_register($opt['username'], $opt['email'], $opt['register']);
		mo_echo_hr();
		mo_echo_paragraph_begin();
		mo_echo_url($mo_lang['menu'], 'menu.php');
		mo_echo_sp();
		mo_echo_url($mo_lang['home'], 'index.php');
		mo_echo_paragraph_end();
		mo_echo_footer();
		exit(0);
	}
}


mo_echo_header($mo_lang['register'], 'index.php', $mo_var['refresh_timer']);
mo_echo_paragraph_begin();

mo_echo_msg($mo_lang['register_successfully']);

mo_echo_paragraph_end();
mo_echo_hr();
mo_echo_paragraph_begin();

mo_echo_url($mo_lang['menu'], 'menu.php');
mo_echo_sp();
mo_echo_url($mo_lang['home'], 'index.php');

mo_echo_paragraph_end();
mo_echo_footer();
?>
