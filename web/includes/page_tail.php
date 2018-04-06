<?php
/***************************************************************************
 *                              page_tail.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: page_tail.php,v 1.27.2.4 2005/09/14 18:14:30 acydburn Exp $
 *
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

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
}

global $do_gzip_compress;

//
// Show the overall footer.
//
$admin_link = ( $userdata['user_level'] == ADMIN ) ? '<a href="admin/index.' . $phpEx . '?sid=' . $userdata['session_id'] . '">' . $lang['Admin_panel'] . '</a><br /><br />' : ( ( $userdata['user_level'] == MOD ) ? '<a href="modcp/index.' . $phpEx . '?sid=' . $userdata['session_id'] . '">' . $lang['Mod_CP'] . '</a><br /><br />' : '' );
/*******************
** MOD: Report Posts
*******************/
// BEGIN : AFTER, ADD
include_once($phpbb_root_path . 'includes/functions_report.'.$phpEx);

$affiliate_Info = '<font size=6><b style="color:#' . $theme['fontcolor2'] . '"> Blackstar Affiliates </b><br /> <a href="http://theavatarrp.proboards27.com" ><img src="http://i89.photobucket.com/albums/k238/AshikadaNanashi/AtLA%20Site/RPG%20Site%20Graphics/AangAffiliateBanner.jpg" /></a></font>';

if ( $userdata['user_level'] >= ADMIN )
{
	$open_reports = reports_count();
	if ( $open_reports == 0 )
	{
		$open_reports = sprintf($lang['Post_reports_none_cp'],$open_reports);
	}
	else 
	{
		$open_reports = sprintf(( ($open_reports == 1) ? $lang['Post_reports_one_cp'] : $lang['Post_reports_many_cp']), $open_reports);
		$open_reports = '<b style="color:#' . $theme['fontcolor2'] . '">' . $open_reports . '</b>';
	}

	$report_link = '&nbsp; <a href="' . append_sid($phpbb_root_path . 'viewpost_reports.'.$phpEx) . '">' . $open_reports . '</a> &nbsp;';
}
else
{
	$report_link = '';
}
// END : AFTER, ADD

$template->set_filenames(array(
	'overall_footer' => ( empty($gen_simple_header) ) ? 'overall_footer.tpl' : 'simple_footer.tpl')
);

$template->assign_vars(array(
	'TRANSLATION_INFO' => (isset($lang['TRANSLATION_INFO'])) ? $lang['TRANSLATION_INFO'] : ((isset($lang['TRANSLATION'])) ? $lang['TRANSLATION'] : ''),
/*******************
** MOD: Report Posts
*******************/
// BEGIN : BEFORE, ADD
	'REPORT_LINK' => $report_link,
// END : BEFORE, ADD
	'ADMIN_LINK' => $admin_link,
	'AFFILIATES' => $affiliate_Info)
);

$template->pparse('overall_footer');

//
// Close our DB connection.
//
$db->sql_close();

//
// Compress buffered output if required and send to browser
//
if ( $do_gzip_compress )
{
	//
	// Borrowed from php.net!
	//
	$gzip_contents = ob_get_contents();
	ob_end_clean();

	$gzip_size = strlen($gzip_contents);
	$gzip_crc = crc32($gzip_contents);

	$gzip_contents = gzcompress($gzip_contents, 9);
	$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

	echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
	echo $gzip_contents;
	echo pack('V', $gzip_crc);
	echo pack('V', $gzip_size);
}

exit;

?>
