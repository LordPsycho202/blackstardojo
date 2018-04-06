<?php
require 'sys.php';

$page_id = PAGE_LOGIN;

require 'lib.php';

$opt['username'] = mo_prepare_message1(mo_get_post_opt('username'));
$opt['password'] = md5(mo_get_post_opt('password'));
$opt['login'] = mo_get_get_opt('login');
$opt['redirect'] = mo_get_get_opt('redirect', '');


if ( !$opt['login'] )
{
	if ( !$opt['redirect'] )
	{
		mo_echo_header($mo_lang['login']);
		mo_echo_login();
	}
	else
	{
		$redirect_opt = get_redirect_opt($HTTP_SERVER_VARS['QUERY_STRING']);
		if ( $redirect_opt )
		{
			$opt['redirect'] .= '&amp;' . $redirect_opt;
		}
		mo_echo_header($mo_lang['login']);
		mo_echo_login('', 0, $opt['redirect']);
	}
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

$user_id = mo_get_user_id($opt['username'], $opt['password']);
if ( !$user_id )
{
	if ( !$opt['redirect'] )
	{
		mo_echo_header($mo_lang['login']);
		mo_echo_paragraph_begin();
		mo_echo_msg($mo_lang['login_error']);
		mo_echo_paragraph_end();
		mo_echo_login($opt['username'], $opt['login']);
	}
	else
	{
		$redirect_opt = get_redirect_opt($HTTP_SERVER_VARS['QUERY_STRING']);
		if ( $redirect_opt )
		{
			$opt['redirect'] .= '&amp;' . $redirect_opt;
		}
		mo_echo_header($mo_lang['login']);
		mo_echo_paragraph_begin();
		mo_echo_msg($mo_lang['login_error']);
		mo_echo_paragraph_end();
		mo_echo_login($opt['username'], $opt['login'], $opt['redirect']);
	}
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}

$session_id = session_begin($user_id, $user_ip, PAGE_INDEX);


if ( !$opt['redirect'] )
{
	mo_echo_header($mo_lang['login'], 'index.php');
	mo_echo_paragraph_begin();
	
	mo_echo_msg($mo_lang['login_successfully']);
}
else
{
	$redirect_opt = get_redirect_opt($HTTP_SERVER_VARS['QUERY_STRING']);
	if ( $redirect_opt )
	{
		$opt['redirect'] .= '?' . $redirect_opt;
	}
	
	mo_echo_header($mo_lang['login'], $opt['redirect']);
	mo_echo_paragraph_begin();
	
	mo_echo_msg($mo_lang['login_successfully']);
	
	mo_echo_br();
	mo_echo_url($mo_lang['continue'], $opt['redirect']);
}

mo_echo_paragraph_end();
mo_echo_hr();
mo_echo_paragraph_begin();

mo_echo_url($mo_lang['menu'], 'menu.php');
mo_echo_sp();
mo_echo_url($mo_lang['home'], 'index.php');

mo_echo_paragraph_end();
mo_echo_footer();
?>
