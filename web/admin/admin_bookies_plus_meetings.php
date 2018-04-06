<?php
 //require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
*     begin                : Sun Oct 17 2004
*     copyright            : (C) 2004 Majorflam
*     email                : majorflam@blueyonder.co.uk
*
****************************************************************************/

define('IN_PHPBB', 1);

//
// First we do the setmodules stuff for the admin cp.
//
if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Bookmakers +']['Meetings'] = $filename;

	return;
}

//
// Load default header
//
$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Output the authorisation details
//
$template->set_filenames(array(
	'body' => 'admin/admin_bookies_plus_meetings.tpl'
	)
);

// Get language Variables
include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_bookmakers.' . $phpEx);

$mode=htmlspecialchars($HTTP_GET_VARS['mode']);

//
// Are we deleting checked?
//
if ( isset($HTTP_POST_VARS['delete_marked']) )
{
	//
	// OK, let's run through the meetings and delete where appropiate
	//
	$sql=" SELECT meeting_id FROM " . BOOKIE_MEETINGS_TABLE;
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error retrieving meetings', '', __LINE__, __FILE__, $sql); 
	}
	$deleted=0;
	while ( $row=$db->sql_fetchrow($result) )
	{
		$check_id='check_' . $row['meeting_id'];
		$this_check=intval($HTTP_POST_VARS[$check_id]);
		if ( $this_check )
		{
			$sql_a=" DELETE FROM " . BOOKIE_MEETINGS_TABLE . "
			WHERE meeting_id=" . $row['meeting_id'];
			if ( !$db->sql_query($sql_a) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error deleting meeting', '', __LINE__, __FILE__, $sql_a); 
			}
			$deleted++;
		}
	}
	if ( $deleted )
	{
		$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_plus_meetings.$phpEx?") . '">';
		$message=$deleted . '&nbsp;' . $lang['bookie_meeting_delete_success'] . $redirect;
		message_die(GENERAL_MESSAGE, $message);
	}
}
if ( isset($HTTP_POST_VARS['delete_all']) )
{
	$template->set_filenames(array(
	'body' => 'admin/admin_bookies_plus_selections_delete.tpl'
	));
		
	$url=append_sid("admin_bookies_plus_meetings.$phpEx?mode=deleteallconfirm");
	
	$template->assign_vars(array(
	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
	'DELETE_CONFIRM' => $lang['bookie_delete_meeting_all_confirm'],
	'URL' => $url,
	'THIS_TEMPL_NAME' => $lang['bookie_delete_all_meetings'],
	));
	
	include('./page_header_admin.'.$phpEx);

	$template->pparse('body');

	include('./page_footer_admin.'.$phpEx);
}
if ( $mode == 'deleteallconfirm' && isset($HTTP_POST_VARS['yes']) )
{
	$sql=" DELETE FROM " . BOOKIE_MEETINGS_TABLE;
	if ( !$db->sql_query($sql) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error deleting meeting', '', __LINE__, __FILE__, $sql); 
	}
	$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_plus_meetings.$phpEx?") . '">';
	$message=$lang['bookie_meeting_delete_all_success'] . $redirect;
	message_die(GENERAL_MESSAGE, $message);
}
if ( $mode == 'edit' )
{
	if ( !isset($HTTP_POST_VARS['edit']) )
	{
		$template->set_filenames(array(
		'body' => 'admin/admin_bookies_plus_meetings_edit.tpl'
		));
		
		$meeting_id=intval($HTTP_GET_VARS['meeting_id']);
		
		$sql=" SELECT meeting FROM " . BOOKIE_MEETINGS_TABLE . "
		WHERE meeting_id=$meeting_id
		";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error retrieving meetings', '', __LINE__, __FILE__, $sql); 
		}
		$row=$db->sql_fetchrow($result);
		$meeting_name=$row['meeting'];
		
		$url=append_sid("admin_bookies_plus_meetings.$phpEx?mode=edit&amp;meeting_id=$meeting_id");
	
		$template->assign_vars(array(
		'URL' => $url,
		'THIS_MEETING' => $meeting_name,
		'MEETING' => $lang['bookie_process_meeting'],
		'SUBMIT' => $lang['bookie_set_submitbuton'],
		'HEADER' => $lang['bookie_meetings_edit_header'],
		'HEADER_EXPLAIN' => $lang['bookie_meetings_edit_header_exp'],
		));
	
		include('./page_header_admin.'.$phpEx);

		$template->pparse('body');

		include('./page_footer_admin.'.$phpEx);
	}
	else
	{
		$meeting_id=intval($HTTP_GET_VARS['meeting_id']);
		$new_meeting=htmlspecialchars($HTTP_POST_VARS['edit_meeting']);
		
		$sql=" UPDATE " . BOOKIE_MEETINGS_TABLE . "
		SET meeting='" . str_replace("\'", "''", $new_meeting) . "'
		WHERE meeting_id=$meeting_id
		";
		if ( !$db->sql_query($sql) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error updating meeting', '', __LINE__, __FILE__, $sql); 
		}
		$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_plus_meetings.$phpEx?") . '">';
		$message=$lang['bookie_meeting_edit_success'] . $redirect;
		message_die(GENERAL_MESSAGE, $message);
	}
}
//
// retrieve the meetings
//
$sql=" SELECT * FROM " . BOOKIE_MEETINGS_TABLE . "
ORDER BY meeting ASC
";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error retrieving meetings', '', __LINE__, __FILE__, $sql); 
}
$x=1;
while ( $row = $db->sql_fetchrow($result) )
	{
		$meeting=$row['meeting'];
		$meeting_id=$row['meeting_id'];
		
		$edit_url=append_sid("admin_bookies_plus_meetings.$phpEx?&amp;mode=edit&amp;meeting_id=$meeting_id");
		
		$row_color = ( !($x % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
		$row_class = ( !($x % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
		$template->assign_block_vars('meetings', array(
		'ROW_COLOR' => '#' . $row_color, 
		'ROW_CLASS' => $row_class,
		'MEETING' => $meeting,
		'CHECK_NAME' => 'check_' . $meeting_id,
		'EDIT_IMG' => '<a href="' . $edit_url . '"><img src="' . $phpbb_root_path . $images['icon_edit'] . '" alt="' . $lang['bookie_edit_meeting'] . '" title="' . $lang['bookie_edit_meeting'] . '" border="0" /></a>',
		));
		$x++;
	}


$template->assign_vars(array(
'DELETE_ALL' => $lang['bookie_delete_all'],
'DELETE_MARKED' => $lang['bookie_delete_marked'],
'MEETING' => $lang['bookie_process_meeting'],
'EDIT' => $lang['bookie_edit_meeting'],
'DELETE' => $lang['bookie_delete_meeting'],
'HEADER' => $lang['bookie_meetings_header'],
'HEADER_EXPLAIN' => $lang['bookie_meetings_header_exp'],
'BOOKIE_VERSION' => $board_config['bookie_version'],
));

include('./page_header_admin.'.$phpEx);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>