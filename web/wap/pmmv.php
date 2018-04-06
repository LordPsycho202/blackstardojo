<?php
require 'sys.php';

$page_id = PAGE_PRIVMSGS;

require 'lib.php';

$opt['privmsgs_id'] = mo_get_get_opt('privmsgs_id');
$opt['privmsgs_box'] = mo_get_get_opt('privmsgs_box');

if ( !$opt['privmsgs_id'] )
{
	mo_echo_header($mo_lang['pmmv'], 'index.php');
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['pm_not_exist']);
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


if ( !$userdata['session_logged_in'] )
{
	mo_echo_header($mo_lang['pmmv'], 'login.php', $mo_var['refresh_timer'], 
	    'redirect', 'pmmv.php', 'privmsgs_id', $opt['privmsgs_id'], 
	    'privmsgs_box', $opt['privmsgs_box']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['login_entry']);
	mo_echo_br();
	mo_echo_url($mo_lang['login'], 'login.php', 'redirect', 'pmmv.php', 
	    'privmsgs_id', $opt['privmsgs_id'], 'privmsgs_box', $opt['privmsgs_box']);
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
$rows = mo_select_privmsgs('privmsgs_savebox', $userdata['user_id']);

$num = count($rows);
if ( $num >= $board_config['max_savebox_privmsgs'] )
{
	mo_echo_header($mo_lang['pmmv']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['pm_savebox_full']);
	mo_echo_paragraph_end();
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['pmin'], 'pmin.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['pmsent'], 'pmsent.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['pmsave'], 'pmsave.php');
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

list($type, $subject, $from_userid, $to_userid, $date) 
    = mo_get_privmsgs($opt['privmsgs_id']);
if ( $type == '' )
{
	mo_echo_header($mo_lang['pmmv']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['pmmv_failed']);
	mo_echo_paragraph_end();
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['pmin'], 'pmin.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['pmsent'], 'pmsent.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['pmsave'], 'pmsave.php');
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

if ( $type == PRIVMSGS_SENT_MAIL )
{
	mo_update_privmsgs('privmsgs_sent', $opt['privmsgs_id']);
}
elseif ( $type == PRIVMSGS_READ_MAIL )
{
	mo_update_privmsgs('privmsgs_received', $opt['privmsgs_id']);
}

mo_update_users('privmsgs', $userdata['user_id'], time());


mo_echo_header($mo_lang['pmmv'], $opt['privmsgs_box'] . '.php', $mo_var['refresh_timer']);
mo_echo_paragraph_begin();

mo_echo_msg($mo_lang['pmmv_successfully']);

mo_echo_br();
mo_echo_url($mo_lang['continue'], $opt['privmsgs_box'] . '.php');

mo_echo_paragraph_end();
mo_echo_hr();
mo_echo_paragraph_begin();

mo_echo_url($mo_lang['pmin'], 'pmin.php');
mo_echo_sp();
mo_echo_url($mo_lang['pmsent'], 'pmsent.php');
mo_echo_sp();
mo_echo_url($mo_lang['pmsave'], 'pmsave.php');

mo_echo_br();
mo_echo_url($mo_lang['menu'], 'menu.php');
mo_echo_sp();
mo_echo_url($mo_lang['home'], 'index.php');

mo_echo_paragraph_end();
mo_echo_footer();
?>
