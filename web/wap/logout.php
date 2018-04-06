<?php
require 'sys.php';

$page_id = PAGE_LOGIN;

require 'lib.php';


$session_id = session_end($userdata['session_id'], $userdata['user_id']);


mo_echo_header($mo_lang['logout'], 'index.php');
mo_echo_paragraph_begin();

mo_echo_msg($mo_lang['logout_completely']);

mo_echo_paragraph_end();
mo_echo_hr();
mo_echo_paragraph_begin();
mo_echo_url($mo_lang['menu'], 'menu.php');
mo_echo_sp();
mo_echo_url($mo_lang['home'], 'index.php');

mo_echo_paragraph_end();
mo_echo_footer();
?>
