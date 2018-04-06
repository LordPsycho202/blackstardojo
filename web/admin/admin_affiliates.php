<?php
 //require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['General']['Affiliates'] = $file;
	return;
}

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
if($HTTP_GET_VARS['mode'] == "edit") {
	if(@$HTTP_POST_VARS['confirmed'] != "true") {
		if(!isset($HTTP_GET_VARS['affiliate'])) {
			$template->set_filenames(array("body"=>"admin/affiliates_edit.tpl"));
			$template->assign_vars(array(
				'ID'=>"-1",
				'L_SITENAME' => "Site Name",
				'L_SITEADDRESS' => "Site Address",
				'L_SITEBANNER' => "Site banner",
				'S_CONFIG_ACTION' => append_sid("admin_affiliates.php?mode=edit")
			));
			$template->pparse("body");
		} else {
			$q = $db->sql_query("SELECT * FROM phpbb_affiliates WHERE id = '".$HTTP_GET_VARS['affiliate']."'");
			if(!$q) {
				message_die(GENERAL_ERROR,"Fail",__FILE__,__LINE__);
			}
			$row = $db->sql_fetchrow($q);
			$template->set_filenames(array("body"=>"admin/affiliates_edit.tpl"));
			$template->assign_vars(array(
				'ID'=>$row['id'],
				'NAME'=>$row['sitename'],
				'ADDRESS'=>$row['siteaddress'],
				'IMAGE'=>$row['imageurl'],
				'L_SITENAME' => "Site Name",
				'L_SITEADDRESS' => "Site Address",
				'L_SITEBANNER' => "Site banner",
				'S_CONFIG_ACTION' => append_sid("admin_affiliates.php?mode=edit")
			));
			$template->pparse("body");
		}
	} else {
		$template->set_filenames(array("body"=>"admin/admin_message_body.tpl"));
		if($HTTP_POST_VARS['id'] == "-1") {
			//create
			if(!$db->sql_query("INSERT INTO phpbb_affiliates (sitename,siteaddress,imageurl) VALUES('".$HTTP_POST_VARS['sitename']."','".$HTTP_POST_VARS['address']."','".$HTTP_POST_VARS['banner']."')")) {
				$template->assign_vars(array(
				'MESSAGE_TITLE' => 'Couldn\'t add affiliate.',
				'MESSAGE_TEXT' => '<br />Click <a href="'.append_sid('admin_affiliates.php').'">here</a> to go back to the affiliates list.'
				));
			} else {
				$template->assign_vars(array(
				'MESSAGE_TITLE' => 'Successfuly added affiliate.',
				'MESSAGE_TEXT' => '<br />Click <a href="'.append_sid('admin_affiliates.php').'">here</a> to go back to the affiliates list.'
				));
			}
		} else {
			if(!$db->sql_query("UPDATE phpbb_affiliates SET sitename= '".$HTTP_POST_VARS['sitename']."', siteaddress= '".$HTTP_POST_VARS['address']."' ,imageurl = '".$HTTP_POST_VARS['banner']."' WHERE id = '".$HTTP_POST_VARS['id']."'")) {
				$template->assign_vars(array(
				'MESSAGE_TITLE' => 'Couldn\'t update affiliate.',
				'MESSAGE_TEXT' => '<br />Click <a href="'.append_sid('admin_affiliates.php').'">here</a> to go back to the affiliates list.'
				));
			} else {
				$template->assign_vars(array(
				'MESSAGE_TITLE' => 'Successfuly updated affiliate.',
				'MESSAGE_TEXT' => '<br />Click <a href="'.append_sid('admin_affiliates.php').'">here</a> to go back to the affiliates list.'
				));
			}
		}
		$template->pparse("body");
	}
} else if($HTTP_GET_VARS['mode']=="delete"){
	$template->set_filenames(array("body"=>"admin/admin_message_body.tpl"));
	if(!$db->sql_query("DELETE FROM phpbb_affiliates WHERE id = '".$HTTP_GET_VARS['affiliate']."'")) {
		$template->assign_vars(array(
		'MESSAGE_TITLE' => 'Couldn\'t delete affiliate.',
		'MESSAGE_TEXT' => '<br />Click <a href="'.append_sid('admin_affiliates.php').'">here</a> to go back to the affiliates list.'
		));
	} else {
		$template->assign_vars(array(
		'MESSAGE_TITLE' => 'Successfuly deleted affiliate.',
		'MESSAGE_TEXT' => '<br />Click <a href="'.append_sid('admin_affiliates.php').'">here</a> to go back to the affiliates list.'
		));
	}
	$template->pparse('body');
} else {
	$template->set_filenames(array("body"=>"admin/affiliates.tpl"));
	$template->assign_vars(array(
		'L_SITENAME' => "Site Name",
		'L_SITEADDRESS' => "Site Address",
		'L_SITEBANNER' => "Site banner",
		'L_EDIT' => "Edit",
		'U_CREATE' => append_sid("admin_affiliates.php?mode=edit")
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
			'BANNER' => $row['imageurl'],
			'EDIT' => append_sid("admin_affiliates.php?mode=edit&affiliate=".$row['id']),
			'DELETE' => append_sid("admin_affiliates.php?mode=delete&affiliate=".$row['id'])
		));
	}
	$template->pparse("body");
}

include('./page_footer_admin.'.$phpEx);
?>