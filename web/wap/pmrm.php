<?php
require 'sys.php';

$page_id = PAGE_PRIVMSGS;

require 'lib.php';

$opt['privmsgs_id'] = mo_get_get_opt('privmsgs_id');
$opt['privmsgs_box'] = mo_get_get_opt('privmsgs_box');

if ( !$opt['privmsgs_id'] )
{
	mo_echo_header($mo_lang['pmrm'], 'index.php');
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
	mo_echo_header($mo_lang['pmrm'], 'login.php', $mo_var['refresh_timer'], 
	    'redirect', 'pmrm.php', 'privmsgs_id', $opt['privmsgs_id'], 
	    'privmsgs_box', $opt['privmsgs_box']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['login_entry']);
	mo_echo_br();
	mo_echo_url($mo_lang['login'], 'login.php', 'redirect', 'pmrm.php', 
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


list($type, $subject, $from_userid, $to_userid, $time) 
    = mo_get_privmsgs($opt['privmsgs_id']);
if ( $type == '' )
{
	mo_echo_header($mo_lang['pmrm']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['pmrm_failed']);
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

mo_delete_privmsgs($opt['privmsgs_id']);
mo_delete_privmsgs_text($opt['privmsgs_id']);

$current_time = time();
if ( $type == PRIVMSGS_NEW_MAIL )
{
	mo_update_users('privmsgs_remove_new', $to_userid, $current_time);
}
elseif ( $type == PRIVMSGS_UNREAD_MAIL && $from_userid == $userdata['user_id'] )
{
	mo_update_users('privmsgs_remove_unread', $to_userid, $current_time);
}
else
{
	mo_update_users('privmsgs', $userdata['user_id'], $current_time);
}


mo_echo_header($mo_lang['pmrm'], $opt['privmsgs_box'] . '.php', $mo_var['refresh_timer']);
mo_echo_paragraph_begin();

mo_echo_msg($mo_lang['pmrm_successfully']);

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
