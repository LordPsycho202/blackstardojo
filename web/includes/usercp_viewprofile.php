<?php
/***************************************************************************
 *                           usercp_viewprofile.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: usercp_viewprofile.php,v 1.5.2.6 2005/09/14 18:14:30 acydburn Exp $
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
 *
 ***************************************************************************/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
	exit;
}

if ( empty($HTTP_GET_VARS[POST_USERS_URL]) || $HTTP_GET_VARS[POST_USERS_URL] == ANONYMOUS )
{
	message_die(GENERAL_MESSAGE, $lang['No_user_id_specified']);
}
$profiledata = get_userdata($HTTP_GET_VARS[POST_USERS_URL]);

// Medal MOD
$sql = "SELECT m.medal_id, mu.user_id
	FROM " . MEDAL_TABLE . " m, " . MEDAL_USER_TABLE . " mu
	WHERE mu.user_id = '" . $profiledata['user_id'] . "'
	AND m.medal_id = mu.medal_id
	ORDER BY m.medal_name";
	
if($result = $db->sql_query($sql))
{
	$medal_list = $db->sql_fetchrowset($result);
	$medal_count = count($medal_list);

	if ( $medal_count )
	{
		$medal_count = '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&" . POST_USERS_URL . "=" . $profiledata['user_id'] ) .'#medal" class="gensmall">' . $medal_count . '</a>';

		$template->assign_block_vars('medal', array(
			'L_MEDAL_NAME' => $lang['Medal_name'],
			'L_MEDAL_DETAIL' => $lang['Medal_details'],
			'L_MEDAL_DESCRIPTION' => $lang['Medal_description'] . ':&nbsp;',
			'L_MEDAL_INFORMATION' => $lang['Medal_Information'] . ' :: ' . $profiledata['username'])
		);
	} else { $medal_count = 0; }
}

$sql = "SELECT m.medal_id, m.medal_name,m.medal_description, m.medal_image, mu.issue_reason, mu.issue_time
	FROM " . MEDAL_TABLE . " m, " . MEDAL_USER_TABLE . " mu
	WHERE mu.user_id = '" . $profiledata['user_id'] . "'
	AND m.medal_id = mu.medal_id
	ORDER BY m.medal_name, mu.issue_time";

if ($result = $db->sql_query($sql))
{
	$rowset = array();
	$medal_time = $lang['Medal_time'] . ':&nbsp;';
	$medal_reason = $lang['Medal_reason'] . ':&nbsp;';
	while ($row = $db->sql_fetchrow($result))
	{
		if (empty($rowset[$row['medal_name']]))
		{
			$rowset[$row['medal_name']]['medal_description'] .= $row['medal_description'];
			$rowset[$row['medal_name']]['medal_image'] = $row['medal_image'];
			$row['issue_reason'] = ( $row['issue_reason'] ) ? $row['issue_reason'] : $lang['Medal_no_reason'];
			$rowset[$row['medal_name']]['medal_issue'] = $medal_time . create_date($board_config['default_dateformat'], $row['issue_time'], $board_config['board_timezone']) . '<br />' . $medal_reason . $row['issue_reason']  . '<br /><br />';
			$rowset[$row['medal_name']]['medal_count'] = '1';
		}
		else
		{
			$row['issue_reason'] = ( $row['issue_reason'] ) ? $row['issue_reason'] : $lang['Medal_no_reason'];
			$rowset[$row['medal_name']]['medal_issue'] .= $medal_time . create_date($board_config['default_dateformat'], $row['issue_time'], $board_config['board_timezone']) . '<br />' . $medal_reason . $row['issue_reason'] . '<br /><br />';
			$rowset[$row['medal_name']]['medal_count'] += '1';
		}
	}

	while (list($medal_name, $data) = @each($rowset))
	{
		$template->assign_block_vars('medal.details', array(
			'MEDAL_NAME' => $medal_name,
			'MEDAL_DESCRIPTION' => $data['medal_description'],
			'MEDAL_IMAGE' => '<img src="' . $data['medal_image'] . '" border="0" alt="' . $medal_name . '" />',
			'MEDAL_ISSUE' => $data['medal_issue'],
			'MEDAL_COUNT' => $lang['Medal_amount'] . $data['medal_count'])
		);
	}
}


if (!$profiledata)
{
	message_die(GENERAL_MESSAGE, $lang['No_user_id_specified']);
}

// Mighty Gorgon - Multiple Ranks - BEGIN
require_once($phpbb_root_path . 'includes/functions_mg_ranks.'.$phpEx);
$ranks_sql = query_ranks();
// Mighty Gorgon - Multiple Ranks - END

//
// Output page header and profile_view template
//
$template->set_filenames(array(
	'body' => 'profile_view_body.tpl')
);

make_jumpbox('viewforum.'.$phpEx);

//
// Calculate the number of days this user has been a member ($memberdays)
// Then calculate their posts per day
//
$regdate = $profiledata['user_regdate'];
$memberdays = max(1, round( ( time() - $regdate ) / 86400 ));
$posts_per_day = $profiledata['user_posts'] / $memberdays;

// Get the users percentage of total posts
if ( $profiledata['user_posts'] != 0  )
{
	$total_posts = get_db_stat('postcount');
	$percentage = ( $total_posts ) ? min(100, ($profiledata['user_posts'] / $total_posts) * 100) : 0;
}
else
{
	$percentage = 0;
}

$avatar_img = '';
if ( $profiledata['user_avatar_type'] && $profiledata['user_allowavatar'] )
{
	switch( $profiledata['user_avatar_type'] )
	{
		case USER_AVATAR_UPLOAD:
			$avatar_img = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $profiledata['user_avatar'] . '" alt="" border="0" />' : '';
			break;
		case USER_AVATAR_REMOTE:
			$avatar_img = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $profiledata['user_avatar'] . '" alt="" border="0" />' : '';
			break;
		case USER_AVATAR_GALLERY:
			$avatar_img = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $profiledata['user_avatar'] . '" alt="" border="0" />' : '';
			break;
	}
}

	// Mighty Gorgon - Multiple Ranks - BEGIN
	$user_ranks = generate_ranks($profiledata, $ranks_sql);

	$user_rank_01 = ($user_ranks['rank_01'] == '') ? '' : ($user_ranks['rank_01'] . '<br />');
	$user_rank_01_img = ($user_ranks['rank_01_img'] == '') ? '' : ($user_ranks['rank_01_img'] . '<br />');
	$user_rank_02 = ($user_ranks['rank_02'] == '') ? '' : ($user_ranks['rank_02'] . '<br />');
	$user_rank_02_img = ($user_ranks['rank_02_img'] == '') ? '' : ($user_ranks['rank_02_img'] . '<br />');
	$user_rank_03 = ($user_ranks['rank_03'] == '') ? '' : ($user_ranks['rank_03'] . '<br />');
	$user_rank_03_img = ($user_ranks['rank_03_img'] == '') ? '' : ($user_ranks['rank_03_img'] . '<br />');
	$user_rank_04 = ($user_ranks['rank_04'] == '') ? '' : ($user_ranks['rank_04'] . '<br />');
	$user_rank_04_img = ($user_ranks['rank_04_img'] == '') ? '' : ($user_ranks['rank_04_img'] . '<br />');
	$user_rank_05 = ($user_ranks['rank_05'] == '') ? '' : ($user_ranks['rank_05'] . '<br />');
	$user_rank_05_img = ($user_ranks['rank_05_img'] == '') ? '' : ($user_ranks['rank_05_img'] . '<br />');
	// Mighty Gorgon - Multiple Ranks - END

$temp_url = append_sid("privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL . "=" . $profiledata['user_id']);
$pm_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" border="0" /></a>';
$pm = '<a href="' . $temp_url . '">' . $lang['Send_private_message'] . '</a>';

if ( !empty($profiledata['user_viewemail']) || $userdata['user_level'] == ADMIN )
{
	$email_uri = ( $board_config['board_email_form'] ) ? append_sid("profile.$phpEx?mode=email&amp;" . POST_USERS_URL .'=' . $profiledata['user_id']) : 'mailto:' . $profiledata['user_email'];

	$email_img = '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" border="0" /></a>';
	$email = '<a href="' . $email_uri . '">' . $lang['Send_email'] . '</a>';
}
else
{
	$email_img = '&nbsp;';
	$email = '&nbsp;';
}

$www_img = ( $profiledata['user_website'] ) ? '<a href="' . $profiledata['user_website'] . '" target="_userwww"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" border="0" /></a>' : '&nbsp;';
$www = ( $profiledata['user_website'] ) ? '<a href="' . $profiledata['user_website'] . '" target="_userwww">' . $profiledata['user_website'] . '</a>' : '&nbsp;';

$birthday = '&nbsp;';
if ( !empty($profiledata['user_birthday']) )
{
	preg_match('/(..)(..)(....)/', sprintf('%08d',$profiledata['user_birthday']), $bday_parts);
	$bday_month = $bday_parts[1];
	$bday_day = $bday_parts[2];
	$bday_year = $bday_parts[3];
	// the next line converts $lang['DATE_FORMAT'] to something that'll work with years, as this MOD encodes them.  ', Y' is replaced with '' when the year isn't specified to account
	// for date formats that would result in strings like 'October 31, 2005'
	$birthday_format = ($bday_year != 0) ? str_replace(array('y','Y'),array($bday_year % 100,$bday_year),$lang['DATE_FORMAT']) : str_replace(array(', Y','y','Y'),'',$lang['DATE_FORMAT']);
	$birthday = create_date($birthday_format, gmmktime(0,0,0,$bday_month,$bday_day), 0);
}

if ( !empty($profiledata['user_icq']) )
{
	$icq_status_img = '<a href="http://wwp.icq.com/' . $profiledata['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $profiledata['user_icq'] . '&img=5" width="18" height="18" border="0" /></a>';
	$icq_img = '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $profiledata['user_icq'] . '"><img src="' . $images['icon_icq'] . '" alt="' . $lang['ICQ'] . '" title="' . $lang['ICQ'] . '" border="0" /></a>';
	$icq =  '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $profiledata['user_icq'] . '">' . $lang['ICQ'] . '</a>';
}
else
{
	$icq_status_img = '&nbsp;';
	$icq_img = '&nbsp;';
	$icq = '&nbsp;';
}

$aim_img = ( $profiledata['user_aim'] ) ? '<a href="aim:goim?screenname=' . $profiledata['user_aim'] . '&amp;message=Hello+Are+you+there?"><img src="' . $images['icon_aim'] . '" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" border="0" /></a>' : '&nbsp;';
$aim = ( $profiledata['user_aim'] ) ? '<a href="aim:goim?screenname=' . $profiledata['user_aim'] . '&amp;message=Hello+Are+you+there?">' . $lang['AIM'] . '</a>' : '&nbsp;';

$msn_img = ( $profiledata['user_msnm'] ) ? $profiledata['user_msnm'] : '&nbsp;';
$msn = $msn_img;

$yim_img = ( $profiledata['user_yim'] ) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . $profiledata['user_yim'] . '&amp;.src=pg"><img src="' . $images['icon_yim'] . '" alt="' . $lang['YIM'] . '" title="' . $lang['YIM'] . '" border="0" /></a>' : '';
$yim = ( $profiledata['user_yim'] ) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . $profiledata['user_yim'] . '&amp;.src=pg">' . $lang['YIM'] . '</a>' : '';

$temp_url = append_sid("search.$phpEx?search_author=" . urlencode($profiledata['username']) . "&amp;showresults=posts");
$search_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_search'] . '" alt="' . sprintf($lang['Search_user_posts'], $profiledata['username']) . '" title="' . sprintf($lang['Search_user_posts'], $profiledata['username']) . '" border="0" /></a>';
$search = '<a href="' . $temp_url . '">' . sprintf($lang['Search_user_posts'], $profiledata['username']) . '</a>';
if ( $board_config['vault_display_profile'] )
{
	$template->assign_block_vars('display_shares',array());

	$sql = " SELECT e.* , eu .* FROM " . VAULT_EXCHANGE_TABLE . " e 
		LEFT JOIN " . VAULT_EXCHANGE_USERS_TABLE . " eu ON ( eu.user_id =  " . $profiledata['user_id'] . " AND e.stock_id = eu.stock_id ) "; 
	if( !($result = $db->sql_query($sql))) 
	{ 
		message_die(GENERAL_ERROR, 'Could not obtain accounts information', "", __LINE__, __FILE__, $sql); 
	} 
	$shares = $db->sql_fetchrowset($result); 

	for ( $i = 0 ; $i < count($shares) ; $i ++ ) 
	{ 
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2']; 

		$template->assign_block_vars('display_shares.shares' , array( 
			'SHARE_ROW' => $row_class,
			'SHARE_NAME' => vault_get_lang($shares[$i]['stock_name']),  
			'SHARE_SUM' => intval($shares[$i]['stock_amount']), 
		)); 
	} 

	$sql = " SELECT * FROM " . VAULT_USERS_TABLE . " 
		WHERE owner_id = " . $profiledata['user_id']; 
	if( !($result = $db->sql_query($sql))) 
	{ 
		message_die(GENERAL_ERROR, 'Could not obtain accounts information', "", __LINE__, __FILE__, $sql); 
	} 
	$accounts = $db->sql_fetchrow($result); 

	$on_account = ( $accounts['account_protect'] && $userdata['user_level'] != ADMIN && $accounts['owner_id'] != $userdata['user_id'] ) ? $lang['Vault_confidential'] : intval($accounts['account_sum']).'&nbsp;'.$board_config['points_name'];
	$loan = ( $accounts['loan_protect'] && $userdata['user_level'] != ADMIN && $accounts['owner_id'] != $userdata['user_id'] ) ? $lang['Vault_confidential'] : intval($accounts['loan_sum']).'&nbsp;'.$board_config['points_name'];
}
$user_sig = '';
if ( $profiledata['user_attachsig'] && $board_config['allow_sig'] )
{
    include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
    $user_sig = $profiledata['user_sig'];
    $user_sig_bbcode_uid = $profiledata['user_sig_bbcode_uid'];
	if ( $user_sig != '' )
    {
        if ( !$board_config['allow_html'] && $profiledata['user_allowhtml'] )
       	{
       		$user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $user_sig);
       	}
    	if ( $board_config['allow_bbcode'] && $user_sig_bbcode_uid != '' )
   		{
   			$user_sig = ( $board_config['allow_bbcode'] ) ? bbencode_second_pass($user_sig, $user_sig_bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $user_sig);
   		}
   		$user_sig = make_clickable($user_sig);

        if (!$userdata['user_allowswearywords'])
        {
            $orig_word = array();
            $replacement_word = array();
            obtain_word_list($orig_word, $replacement_word);
            $user_sig = preg_replace($orig_word, $replacement_word, $user_sig);
        }
        if ( $profiledata['user_allowsmile'] )
        {
            $user_sig = smilies_pass($user_sig);
        }
        $user_sig = str_replace("\n", "\n<br />\n", $user_sig);
    }
    $template->assign_block_vars('switch_user_sig_block', array());
}

$auctions_won = $profiledata['auctions_paid']+$profiledata['auctions_unpaid']; 
$auctions_unpaid = ($profiledata['auctions_unpaid'] > 0) ? ', <span style="color:red">'.$profiledata['auctions_unpaid'].' ' . $lang['auctions_unpaid'] . '</font>' : "";

//
// Generate page
//
$page_title = $lang['Viewing_profile'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

display_upload_attach_box_limits($profiledata['user_id']);


if (function_exists('get_html_translation_table'))
{
	$u_search_author = urlencode(strtr($profiledata['username'], array_flip(get_html_translation_table(HTML_ENTITIES))));
}
else
{
	$u_search_author = urlencode(str_replace(array('&amp;', '&#039;', '&quot;', '&lt;', '&gt;'), array('&', "'", '"', '<', '>'), $profiledata['username']));
}

//
// Shop Code
//
if ( $board_config['viewprofile'] == 'images' )
{
	$sql = "SELECT *
		FROM " . USER_ITEMS_TABLE . "
		WHERE user_id='{$profiledata['user_id']}'
			AND ( worn = 0 or worn = 1 )
		GROuP BY `item_name`
		ORDER BY `id`";
	if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Fetch Error retrieving user items.'); }
	$num_rows = $db->sql_numrows($result);

	$user_items = '<br />';

	for ( $iz = 0; $iz < $num_rows; $iz++ )
	{
		$row = $db->sql_fetchrow($result);
		$user_item .= $row['item_name']. " " .$row['wrapped'];
		if ($row['wrapped'] != '0') 
		{			
			if (file_exists('shop/images/wrapped/' . $row['wrapped'] . '.jpg'))
			{
					$user_items .= ' <img src="shop/images/wrapped/' . $row['wrapped'] . '.jpg" title="' . $row['wrapped'] . '" alt="' . $row['wrapped'] . '" />';
			}
			elseif (file_exists('shop/images/wrapped/' . $row['wrapped'] . '.png'))
			{
				$user_items .= ' <img src="shop/images/wrapped/' . $row['wrapped'] . '.png" title="' . $row['wrapped'] . '" alt="' . $row['wrapped'] . '" />';
			}
			elseif (file_exists('shop/images/wrapped/' . $row['wrapped'] . '.gif'))
			{
				$user_items .= ' <img src="shop/images/wrapped/' . $row['wrapped'] . '.gif" title="' . $row['wrapped'] . '" alt="' . $row['wrapped'] . '" />';
			}	
		}
		else
		{
			if (file_exists('shop/images/' . $row['item_name'] . '.jpg'))
			{
					$user_items .= ' <img src="shop/images/' . $row['item_name'] . '.jpg" title="' . $row['item_name'] . '" alt="' . $row['item_name'] . '" />';
			}
			elseif (file_exists('shop/images/' . $row['item_name'] . '.png'))
			{
				$user_items .= ' <img src="shop/images/' . $row['item_name'] . '.png" title="' . $row['item_name'] . '" alt="' . $row['item_name'] . '" />';
			}
			elseif (file_exists('shop/images/' . $row['item_name'] . '.gif'))
			{
				$user_items .= ' <img src="shop/images/' . $row['item_name'] . '.gif" title="' . $row['item_name'] . '" alt="' . $row['item_name'] . '" />';
			}
		}				
	}

	$usernameurl = '<a href="'.append_sid('shop.'.$phpEx.'?action=inventory&searchid='.$profiledata['user_id'], true).'" class="gensmall"><b>' . $lang['items'] . '</b></a>: ';
}
elseif ( $board_config['viewprofile'] == 'link' )
{
	$usernameurl = '<a href="'.append_sid('shop.'.$phpEx.'?action=inventory&searchid='.$profiledata['user_id'], true).'" class="gensmall"><b>' . $lang['items'] . '</b></a>';
}

$template->assign_vars(array(
	'USERNAME' => $profiledata['username'],
	'L_USER_MEDAL' =>$lang['Medals'],	// Medal MOD
	'USER_MEDAL_COUNT' => $medal_count,	// Medal MOD
	'MEDAL_BODY' => $medal,			// Medal MOD

	'JOINED' => create_date($lang['DATE_FORMAT'], $profiledata['user_regdate'], $board_config['board_timezone']),
	// Mighty Gorgon - Multiple Ranks - BEGIN
	'USER_RANK_01' => $user_rank_01,
	'USER_RANK_01_IMG' => $user_rank_01_img,
	'USER_RANK_02' => $user_rank_02,
	'USER_RANK_02_IMG' => $user_rank_02_img,
	'USER_RANK_03' => $user_rank_03,
	'USER_RANK_03_IMG' => $user_rank_03_img,
	'USER_RANK_04' => $user_rank_04,
	'USER_RANK_04_IMG' => $user_rank_04_img,
	'USER_RANK_05' => $user_rank_05,
	'USER_RANK_05_IMG' => $user_rank_05_img,
	// Mighty Gorgon - Multiple Ranks - END
	'POSTS_PER_DAY' => $posts_per_day,
	'POSTS' => $profiledata['user_posts'],
	'PERCENTAGE' => $percentage . '%', 
	'POST_DAY_STATS' => sprintf($lang['User_post_day_stats'], $posts_per_day), 
	'POST_PERCENT_STATS' => sprintf($lang['User_post_pct_stats'], $percentage), 

	'SEARCH_IMG' => $search_img,
	'SEARCH' => $search,
	'L_ON_ACCOUNT' => $lang['Vault_on_account'],
	'L_LOAN' => $lang['Vault_loan_account'],
	'ON_ACCOUNT' => $on_account,
	'LOAN' => $loan,
	'PM_IMG' => $pm_img,
	'PM' => $pm,
	'EMAIL_IMG' => $email_img,
	'EMAIL' => $email,
	'WWW_IMG' => $www_img,
	'WWW' => $www,
	'ICQ_STATUS_IMG' => $icq_status_img,
	'ICQ_IMG' => $icq_img, 
	'ICQ' => $icq, 
	'AIM_IMG' => $aim_img,
	'AIM' => $aim,
	'MSN_IMG' => $msn_img,
	'MSN' => $msn,
	'YIM_IMG' => $yim_img,
	'YIM' => $yim,
	'INVENTORYLINK' => $usernameurl,
	'INVENTORYPICS' => $user_items,


	'BIRTHDAY' => $birthday,
	'LOCATION' => ( $profiledata['user_from'] ) ? $profiledata['user_from'] : '&nbsp;',
	'OCCUPATION' => ( $profiledata['user_occ'] ) ? $profiledata['user_occ'] : '&nbsp;',
	'INTERESTS' => ( $profiledata['user_interests'] ) ? $profiledata['user_interests'] : '&nbsp;',
	'L_SIGNATURE' => $lang['Signature'],
    'USER_SIG' => $user_sig,

    
	'AUCTIONS_WON' => $auctions_won, 
	'AUCTIONS_UNPAID' => $auctions_unpaid, 


	'AVATAR_IMG' => $avatar_img,

	'L_VIEWING_PROFILE' => sprintf($lang['Viewing_user_profile'], $profiledata['username']), 
	'L_ABOUT_USER' => sprintf($lang['About_user'], $profiledata['username']), 
	'L_AVATAR' => $lang['Avatar'], 
	'L_POSTER_RANK' => $lang['Poster_rank'], 
	'L_JOINED' => $lang['Joined'], 
	'L_TOTAL_POSTS' => $lang['Total_posts'], 
	'L_SEARCH_USER_POSTS' => sprintf($lang['Search_user_posts'], $profiledata['username']), 
	'L_CONTACT' => $lang['Contact'],
	'L_EMAIL_ADDRESS' => $lang['Email_address'],
	'L_EMAIL' => $lang['Email'],
	'L_PM' => $lang['Private_Message'],
	'L_ICQ_NUMBER' => $lang['ICQ'],
	'L_YAHOO' => $lang['YIM'],
	'L_AIM' => $lang['AIM'],
	'L_MESSENGER' => $lang['MSNM'],
	'L_WEBSITE' => $lang['Website'],
	'L_BIRTHDAY' => $lang['Birthday'],
	'L_LOCATION' => $lang['Location'],
	'L_OCCUPATION' => $lang['Occupation'],
	'L_INTERESTS' => $lang['Interests'],
	'L_AUCTIONS' => $lang['auctions'],
	'L_WON' => $lang['auctions_won'],

	'L_ITEMS' => $lang['items'],


	'U_SEARCH_USER' => append_sid("search.$phpEx?search_author=" . $u_search_author),

  'U_USER_ITEMS' => append_sid("store.$phpEx?mode=user_items&id=" . $profiledata['user_id']),
  'L_USER_ITEMS' => $lang['Store_users_items'],
  'L_USER_ITEMS_GOTO' => $lang['Store_users_items_goto'],
  
	'S_PROFILE_ACTION' => append_sid("profile.$phpEx"))
);


if ( $board_config['store_view_profile'] )
{
  $template->assign_block_vars('switch_item_view', array());
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>