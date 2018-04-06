<?php
require 'sys.php';

$page_id = PAGE_PROFILE;

require 'lib.php';

$opt['email'] = mo_get_post_opt('email');
$opt['password'] = mo_get_post_opt('password');
$opt['password_new'] = mo_get_post_opt('password_new');
$opt['password_conf'] = mo_get_post_opt('password_conf');
$opt['profile'] = mo_get_get_opt('profile');


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

if ( !$userdata['session_logged_in'] )
{
	mo_echo_header($mo_lang['profile'], 'login.php', $mo_var['refresh_timer'], 
	    'redirect', 'updateprofile.php');
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['login_entry']);
	mo_echo_br();
	mo_echo_url($mo_lang['login'], 'login.php', 'redirect', 'updateprofile.php');
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

list($username, $password, $email) = mo_get_users($userdata['user_id']);
if ( !$opt['profile'] )
{
	mo_echo_header($mo_lang['profile']);
	mo_echo_profile($username, $email);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

if ( !$opt['email'] || !$opt['password'] || !$opt['password_new'] )
{
	mo_echo_header($mo_lang['profile']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['field_required']);
	mo_echo_paragraph_end();
	mo_echo_profile($username, $opt['email'], $opt['profile']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

if ( $opt['password_new'] != $opt['password_conf'] )
{
	mo_echo_header($mo_lang['profile']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['password_mismatch']);
	mo_echo_paragraph_end();
	mo_echo_profile($username, $opt['email'], $opt['profile']);
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
if ( $password != $opt['password'] )
{
	mo_echo_header($mo_lang['profile']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['password_wrong']);
	mo_echo_paragraph_end();
	mo_echo_profile($username, $opt['email'], $opt['profile']);
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}


$opt['password_new'] = md5($opt['password_new']);
mo_update_users('profile', $userdata['user_id'], 0, $opt['password_new'], $opt['email']);


mo_echo_header($mo_lang['profile'], 'index.php', $mo_var['refresh_timer']);
mo_echo_paragraph_begin();

mo_echo_msg($mo_lang['profile_completely']);

mo_echo_paragraph_end();
mo_echo_hr();
mo_echo_paragraph_begin();

mo_echo_url($mo_lang['menu'], 'menu.php');
mo_echo_sp();
mo_echo_url($mo_lang['home'], 'index.php');

mo_echo_paragraph_end();
mo_echo_footer();
?>
