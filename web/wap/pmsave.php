<?php
require 'sys.php';

$page_id = PAGE_PRIVMSGS;

require 'lib.php';

$opt['index'] = mo_get_get_opt('index');


if ( !$userdata['session_logged_in'] )
{
	mo_echo_header($mo_lang['pmsave'], 'login.php', $mo_var['refresh_timer'], 
	    'redirect', 'pmsave.php', 'index', $opt['index']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['login_entry']);
	mo_echo_br();
	mo_echo_url($mo_lang['login'], 'login.php', 'redirect', 'pmsave.php', 
	    'index', $opt['index']);
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
$rows = mo_select_privmsgs('privmsgs_savebox', $userdata['user_id'], $opt['index']);

$num = count($rows);
if ( !$num )
{
	mo_echo_header($mo_lang['pmsave']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['pm_empty']);
	mo_echo_paragraph_end();
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['pmin'], 'pmin.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['pmsent'], 'pmsent.php');
	mo_echo_br();
	mo_echo_url($mo_lang['pmnew'], 'pmnew.php');
	mo_echo_br();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

mo_update_users('privmsgs', $userdata['user_id'], time());


mo_echo_header($mo_lang['pmsave']);
mo_echo_paragraph_begin();

$num1 = ( $num > $mo_var['row_size'] ) ? $mo_var['row_size'] : $num;
for ( $i = 0; $i < $num1; $i++ )
{
	if ( $i )
	{
		mo_echo_br();
	}
	mo_echo_url($rows[$i]['privmsgs_subject'], 'pmview.php', 
	    'privmsgs_id', $rows[$i]['privmsgs_id'], 'privmsgs_box', 'pmsave');
}

mo_echo_paragraph_end();
mo_echo_hr();
mo_echo_paragraph_begin();

if ( $num > $mo_var['row_size'] )
{
	mo_echo_url($mo_lang['next'], 'pmsave.php', 
	    'index', $opt['index'] + $mo_var['row_size']);
}
if ( $opt['index'] >= $mo_var['row_size'] )
{
	if ( $num > $mo_var['row_size'] )
	{
		mo_echo_sp();
	}
	mo_echo_url($mo_lang['prev'], 'pmsave.php', 
	    'index', $opt['index'] - $mo_var['row_size']);
}

if ( $num > $mo_var['row_size'] || $opt['index'] >= $mo_var['row_size'] )
{
	mo_echo_br();
}
mo_echo_url($mo_lang['pmin'], 'pmin.php');
mo_echo_sp();
mo_echo_url($mo_lang['pmsent'], 'pmsent.php');

mo_echo_br();
mo_echo_url($mo_lang['pmnew'], 'pmnew.php');

mo_echo_br();
mo_echo_url($mo_lang['menu'], 'menu.php');
mo_echo_sp();
mo_echo_url($mo_lang['home'], 'index.php');

mo_echo_paragraph_end();
mo_echo_footer();
?>
