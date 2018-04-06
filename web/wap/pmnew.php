<?php
require 'sys.php';

$page_id = PAGE_PRIVMSGS;

require 'lib.php';

$opt['user_id'] = mo_get_get_opt('user_id');
$opt['username'] = mo_prepare_message(mo_get_post_opt('username'));
$opt['subject'] = mo_prepare_message1(mo_get_post_opt('subject'));
$opt['message'] = mo_prepare_message(mo_get_post_opt('message'));
$opt['new'] = mo_get_get_opt('new');


if ( !$userdata['session_logged_in'] )
{
	mo_echo_header($mo_lang['pmnew'], 'login.php', $mo_var['refresh_timer'], 
	    'redirect', 'pmnew.php', 'user_id', $opt['user_id']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['login_entry']);
	mo_echo_br();
	mo_echo_url($mo_lang['login'], 'login.php', 'redirect', 'pmnew.php', 
	    'user_id', $opt['user_id']);
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


if ( $opt['user_id'] )
{
	list($opt['username'], $password, $email) = mo_get_users($opt['user_id']);
	$opt['subject'] = '';
	$opt['message'] = '';
	$opt['new'] = 0;
}


if ( !$opt['new'] )
{
	mo_echo_header($mo_lang['pmnew']);
	mo_echo_pmnew($opt['username'], $opt['subject'], $opt['message']);
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

if ( !$opt['username'] )
{
	mo_echo_header($mo_lang['pmnew']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['username_empty']);
	mo_echo_paragraph_end();
	mo_echo_pmnew($opt['username'], $opt['subject'], $opt['message'], 
	    $opt['new']);
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

if ( !$opt['subject'] )
{
	mo_echo_header($mo_lang['pmnew']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['subject_empty']);
	mo_echo_paragraph_end();
	mo_echo_pmnew($opt['username'], $opt['subject'], $opt['message'], 
	    $opt['new']);
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

if ( !$opt['message'] )
{
	mo_echo_header($mo_lang['pmnew']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['message_empty']);
	mo_echo_paragraph_end();
	mo_echo_pmnew($opt['username'], $opt['subject'], $opt['message'], 
	    $opt['new']);
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

$to_userid = mo_get_user_id($opt['username']);
if ( !$to_userid || $to_userid == ANONYMOUS )
{
	mo_echo_header($mo_lang['pmnew']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['user_not_exist']);
	mo_echo_paragraph_end();
	mo_echo_pmnew($opt['username'], $opt['subject'], $opt['message'], 
	    $opt['new']);
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
$last_post_time = mo_get_last_privmsgs_time();
if ( ( $current_time - $last_post_time ) < $board_config['flood_interval'] )
{
	mo_echo_header($mo_lang['pmnew']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['flood_error']);
	mo_echo_paragraph_end();
	mo_echo_pmnew($opt['username'], $opt['subject'], $opt['message'], 
	    $opt['new']);
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

$rows = array();
$rows = mo_select_privmsgs('privmsgs_inbox', $to_userid);

$num = count($rows);
if ( $num >= $board_config['max_inbox_privmsgs'] )
{
	mo_echo_header($mo_lang['pmnew']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['pm_inbox_full']);
	mo_echo_paragraph_end();
	mo_echo_pmnew($opt['username'], $opt['subject'], $opt['message'], 
	    $opt['new']);
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

$rows = mo_select_privmsgs('privmsgs_sentbox', $userdata['user_id']);

$num = count($rows);
if ( $num >= $board_config['max_sentbox_privmsgs'] )
{
	mo_echo_header($mo_lang['pmnew']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['pm_sentbox_full']);
	mo_echo_paragraph_end();
	mo_echo_pmnew($opt['username'], $opt['subject'], $opt['message'], 
	    $opt['new']);
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

$privmsgs_id = mo_insert_privmsgs(PRIVMSGS_NEW_MAIL, $opt['subject'], 
    $userdata['user_id'], $to_userid, $current_time);
if ( !$privmsgs_id )
{
	mo_echo_header($mo_lang['pmnew']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['pmnew_failed']);
	mo_echo_paragraph_end();
	mo_echo_pmnew($opt['username'], $opt['subject'], $opt['message'], 
	    $opt['new']);
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

$privmsgs_text_id = mo_insert_privmsgs_text($privmsgs_id, make_bbcode_uid(), 
    $opt['message']);
if ( !$privmsgs_text_id )
{
	mo_delete_privmsgs($privmsgs_id);
	
	mo_echo_header($mo_lang['pmnew']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['pmnew_failed']);
	mo_echo_paragraph_end();
	mo_echo_pmnew($opt['username'], $opt['subject'], $opt['message'], 
	    $opt['new']);
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

mo_update_users('privmsgs_new', $to_userid, $current_time);


mo_echo_header($mo_lang['pmnew'], 'pmview.php', $mo_var['refresh_timer'], 
    'privmsgs_id', $privmsgs_id);
mo_echo_paragraph_begin();

mo_echo_msg($mo_lang['pmnew_successfully']);

mo_echo_br();
mo_echo_url($mo_lang['view'], 'pmview.php', 'privmsgs_id', $privmsgs_id);

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
