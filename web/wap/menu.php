<?php
require 'sys.php';

$page_id = PAGE_INDEX;

require 'lib.php';


mo_echo_header($mo_lang['menu']);
mo_echo_paragraph_begin();

if ( !$userdata['session_logged_in'] )
{
	if ( $mo_enable_register )
	{
		mo_echo_url($mo_lang['register'], 'register.php');
		mo_echo_br();
	}
	mo_echo_url($mo_lang['pm']);
	mo_echo_br();
	mo_echo_url($mo_lang['login'], 'login.php');
}
else
{
	if ( $mo_enable_register )
	{
		mo_echo_url($mo_lang['profile'], 'updateprofile.php');
		mo_echo_br();
	}
	mo_echo_url($mo_lang['pm'], 'pmin.php');
	mo_echo_br();
	mo_echo_url($mo_lang['logout'] . '&nbsp;[&nbsp;' . $userdata['username'] . 
	    '&nbsp;]', 'logout.php');
	mo_echo_br();
	mo_echo_url($mo_lang['search_new'], 'search.php', 'search', 'newposts');
	mo_echo_br();
	mo_echo_url($mo_lang['search_your'], 'search.php', 'search', 'egosearch');
}
mo_echo_br();
mo_echo_url($mo_lang['search_unanswered'], 'search.php', 'search', 'unanswer');
mo_echo_br();
mo_echo_url($mo_lang['who_is_online'], 'online.php');

mo_echo_paragraph_end();
mo_echo_hr();
mo_echo_paragraph_begin();
mo_echo_url($mo_lang['menu']);
mo_echo_sp();
mo_echo_url($mo_lang['home'], 'index.php');

mo_echo_paragraph_end();
mo_echo_footer();
?>
