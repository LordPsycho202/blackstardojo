<?php
require 'sys.php';

$page_id = PAGE_PROFILE;

require 'lib.php';

$opt['user_id'] = mo_get_get_opt('user_id');

if ( !$opt['user_id'] )
{
	mo_echo_header($mo_lang['profile'], 'index.php');
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['user_not_exist']);
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


list($username, $password, $email) = mo_get_users($opt['user_id']);
if ( !$username )
{
	mo_echo_header($mo_lang['profile'], 'index.php');
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['user_not_exist']);
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

$profiledata = get_userdata($opt['user_id']);
$email = ( $profiledata['user_viewemail'] || $userdata['user_level'] == ADMIN ) ? 
    $profiledata['user_email'] : '&nbsp;';
$msn = $profiledata['user_msnm'] ? $profiledata['user_msnm'] : '&nbsp;';
$yim = $profiledata['user_yim'] ? $profiledata['user_yim'] : '&nbsp;';
$aim = $profiledata['user_aim'] ? $profiledata['user_aim'] : '&nbsp;';
$icq = $profiledata['user_icq'] ? $profiledata['user_icq'] : '&nbsp;';


mo_echo_header($mo_lang['profile']);
mo_echo_paragraph_begin();

mo_echo_url($mo_lang['profile'] . ':');
mo_echo_br();
mo_echo_msg($username);

mo_echo_br();
mo_echo_url($mo_lang['email'] . ':');
mo_echo_br();
mo_echo_msg($email);

mo_echo_br();
mo_echo_url($mo_lang['pm'] . ':');
mo_echo_br();
mo_echo_url($mo_lang['pmnew'], 'pmnew.php', 'user_id', $opt['user_id']);

mo_echo_br();
mo_echo_url($mo_lang['msn'] . ':');
mo_echo_br();
mo_echo_msg($msn);

mo_echo_br();
mo_echo_url($mo_lang['yim'] . ':');
mo_echo_br();
mo_echo_msg($yim);

mo_echo_br();
mo_echo_url($mo_lang['aim'] . ':');
mo_echo_br();
mo_echo_msg($aim);

mo_echo_br();
mo_echo_url($mo_lang['icq'] . ':');
mo_echo_br();
mo_echo_msg($icq);

mo_echo_paragraph_end();
mo_echo_hr();
mo_echo_paragraph_begin();

mo_echo_url($mo_lang['menu'], 'menu.php');
mo_echo_sp();
mo_echo_url($mo_lang['home'], 'index.php');

mo_echo_paragraph_end();
mo_echo_footer();
?>
