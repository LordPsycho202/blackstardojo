<?php
require 'sys.php';

$page_id = PAGE_VIEWONLINE;

require 'lib.php';


$time = time();
$rows = array();
$rows = mo_select_online_status($time);
list($registered, $hidden, $guest, $registered_users) = mo_get_online_status($rows);


mo_echo_header($mo_lang['who_is_online']);
mo_echo_paragraph_begin();

mo_echo_url($mo_lang['online_users'] . ': ');
mo_echo_msg($registered + $hidden + $guest);

mo_echo_br();
mo_echo_url($mo_lang['registered'] . ': ');
mo_echo_msg($registered);

mo_echo_br();
mo_echo_url($mo_lang['hidden'] . ': ');
mo_echo_msg($hidden);

mo_echo_br();
mo_echo_url($mo_lang['guest'] . ': ');
mo_echo_msg($guest);

mo_echo_br();
mo_echo_url($mo_lang['registered_users'] . ':');
	
foreach ( $registered_users as $value )
{
	mo_echo_br();
	mo_echo_msg($value['username']);
}

if ( !$registered )
{
	mo_echo_br();
	mo_echo_msg($mo_lang['none']);
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
