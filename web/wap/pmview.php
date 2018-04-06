<?php
require 'sys.php';

$page_id = PAGE_PRIVMSGS;

require 'lib.php';

$opt['privmsgs_id'] = mo_get_get_opt('privmsgs_id');
$opt['privmsgs_box'] = mo_get_get_opt('privmsgs_box', 'pmin');

if ( !$opt['privmsgs_id'] )
{
	mo_echo_header($mo_lang['pmview'], 'index.php');
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
	mo_echo_header($mo_lang['pmview'], 'login.php', $mo_var['refresh_timer'], 
	    'redirect', 'pmview.php', 'privmsgs_id', $opt['privmsgs_id'], 
	    'privmsgs_box', $opt['privmsgs_box']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['login_entry']);
	mo_echo_br();
	mo_echo_url($mo_lang['login'], 'login.php', 'redirect', 'pmview.php', 
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
	mo_echo_header($mo_lang['pmview']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['pmview_failed']);
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

list($from_username, $password, $email) = mo_get_users($from_userid);
list($to_username, $password, $email) = mo_get_users($to_userid);

list($bbcode_uid, $message) = mo_get_privmsgs_text($opt['privmsgs_id']);
if ( $bbcode_uid == '' )
{
	mo_echo_header($mo_lang['pmview']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['pmview_failed']);
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


$current_time = time();
if ( $type == PRIVMSGS_UNREAD_MAIL && $to_userid == $userdata['user_id'] )
{
	$privmsgs_id = mo_insert_privmsgs(PRIVMSGS_SENT_MAIL, $subject, 
	    $from_userid, $to_userid, $time);
	if ( !$privmsgs_id )
	{
		mo_echo_header($mo_lang['pmview']);
		mo_echo_paragraph_begin();
		mo_echo_msg($mo_lang['pmview_failed']);
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
	
	$privmsgs_text_id = mo_insert_privmsgs_text($privmsgs_id, $bbcode_uid, $message);
	if ( !$privmsgs_text_id )
	{
		mo_delete_privmsgs($privmsgs_id);
		
		mo_echo_header($mo_lang['pmview']);
		mo_echo_paragraph_begin();
		mo_echo_msg($mo_lang['pmview_failed']);
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
	
	mo_update_privmsgs('privmsgs_view', $opt['privmsgs_id']);
	mo_update_users('privmsgs_view_unread', $userdata['user_id'], $current_time);
	
	$type = PRIVMSGS_READ_MAIL;
}
else
{
	mo_update_users('privmsgs', $userdata['user_id'], $current_time);
}


mo_echo_header($mo_lang['pmview']);
mo_echo_paragraph_begin();

mo_echo_url($mo_lang['from'] . ':');
mo_echo_br();
if ( $from_userid != $userdata['user_id'] && $from_userid != ANONYMOUS )
{
	mo_echo_url($from_username, 'viewprofile.php', 'user_id', $from_userid);
}
else
{
	mo_echo_msg($from_username);
}
mo_echo_br();
mo_echo_url($mo_lang['to'] . ':');
mo_echo_br();
if ( $to_userid != $userdata['user_id'] && $to_userid != ANONYMOUS )
{
	mo_echo_url($to_username, 'viewprofile.php', 'user_id', $to_userid);
}
else
{
	mo_echo_msg($to_username);
}
mo_echo_br();
mo_echo_url($mo_lang['subject'] . ':');
mo_echo_br();
mo_echo_msg($subject);
mo_echo_br();
mo_echo_url($mo_lang['message'] . ':');
mo_echo_br();
mo_echo_msg($message);

mo_echo_paragraph_end();
mo_echo_hr();
mo_echo_paragraph_begin();

mo_echo_url($mo_lang['pmin'], 'pmin.php');
mo_echo_sp();
mo_echo_url($mo_lang['pmsent'], 'pmsent.php');
mo_echo_sp();
mo_echo_url($mo_lang['pmsave'], 'pmsave.php');

if ( $to_userid == $userdata['user_id'] )
{
	mo_echo_br();
	mo_echo_url($mo_lang['pmreply'], 'pmreply.php', 
	    'privmsgs_id', $opt['privmsgs_id']);
}
if ( $type == PRIVMSGS_READ_MAIL || $type == PRIVMSGS_SENT_MAIL )
{
	mo_echo_br();
	mo_echo_url($mo_lang['pmmv'], 'pmmv.php', 'privmsgs_id', $opt['privmsgs_id'], 
	    'privmsgs_box', $opt['privmsgs_box']);
}
if ( $type == PRIVMSGS_READ_MAIL || $type == PRIVMSGS_SENT_MAIL )
{
	mo_echo_sp();
}
else
{
	mo_echo_br();
}
mo_echo_url($mo_lang['pmrm'], 'pmrm.php', 'privmsgs_id', $opt['privmsgs_id'], 
    'privmsgs_box', $opt['privmsgs_box']);

mo_echo_br();
mo_echo_url($mo_lang['menu'], 'menu.php');
mo_echo_sp();
mo_echo_url($mo_lang['home'], 'index.php');

mo_echo_paragraph_end();
mo_echo_footer();
?>
