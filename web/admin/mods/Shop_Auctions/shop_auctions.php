<?php
/***************************************************************************
 *                             shop_auctions.php
 *                            -------------------
 *   Version              : 2.0.4
 *   website              : http://www.zarath.com
 *   credit               : rewrite of Moogie's shop Auctions
 *
 ***************************************************************************/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . '/includes/functions_cash.' . $phpEx); 

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management

$user_id = ( isset($HTTP_GET_VARS['user_id']) ) ? intval($HTTP_GET_VARS['user_id']) : 0;

$username = $userdata[username];

#
# Registration of Variables, because this script sucks!
#
$params = array(
	'action' => 'action',
	'mode' => 'mode',

	'items' => 'items', 
	'title' => 'title',
	'description' => 'description',
	'startprice' => 'startprice',
	'increment' => 'increment',
	'buyprice' => 'buyprice',
	'duration' => 'duration',

	'id' => 'id',
	'auctionid' => 'auctionid',

	'bidamt' => 'bidamt',
	'bid' => 'bid');
while( list($var, $param) = @each($params) )
{
	if ( !empty($HTTP_POST_VARS[$param]) || !empty($HTTP_GET_VARS[$param]) )
	{
		if ( !is_array($_REQUEST[$param]) )
		{
			$$var = ( !empty($HTTP_POST_VARS[$param]) ) ? htmlspecialchars($HTTP_POST_VARS[$param]) : htmlspecialchars($HTTP_GET_VARS[$param]);
		}
		else
		{
			$$var = ( !empty($HTTP_POST_VARS[$param]) ) ? $HTTP_POST_VARS[$param] : $HTTP_GET_VARS[$param];
		}
	}
	else
	{
		$$var = '';
	}
}
// Server Set Variables! //
$filename = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/'));
$forumurl = 'http://' . $board_config['server_name'] . $board_config['script_path'];
$auctionsname = $lang['auctions_name'];

$pointsname = ' ' . $board_config['points_name'];
#
# End registration of variables, because this script sucks!
#

//--------------------VARIABLES--------------------------//
$maxauctions = 3; // maximum auctions one user can run at a time
$maxitems = 5; // maximum items per auction
$itemrow = 5; // items per row on auction view page

//define('BANK_TABLE', $table_prefix.'bank'); // Uncomment this if your bank is installed, but it's an old version.
//--------------------END VARIABLES--------------------------//

// Extra code, to check for -version- of bank... Don't alter this! //
if ( defined('BANK_TABLE') )
{
	$sql = "SELECT *
		FROM " . BANK_TABLE . "
		LIMIT 1";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query auctions', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);

	$bank_version = ( isset($row['name']) ) ? 0 : 1;
}

//start of user item listing.

	if ( !$userdata['session_logged_in'] )
	{
		$redirect = $filename;
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}

//--------------------SHOW AUCTIONS--------------------------//
if ( empty($action) )
{
	$template->set_filenames(array(
		'body' => 'auction_body.tpl')
	);

	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;

	if ( isset($HTTP_GET_VARS['order']) || isset($HTTP_POST_VARS['order']) )
	{
		$sort_order = ( $HTTP_GET_VARS['order'] == 'ASC' || $HTTP_POST_VARS['order'] == 'ASC' ) ? 'ASC' : 'DESC';
	}
	else
	{
		$sort_order = 'ASC';
	}

	//
	// Transactions sorting
	//

	$mode_types_text = array("Auction ID", "Seller", "Title", "Items", "Price", "Bids" , "End Date");
	$mode_types = array('id', 'seller', 'title' , 'items', 'price', 'bids' , 'endtime');

	$select_sort_mode = '<select name="mode">';
	for($i = 0; $i < count($mode_types_text); $i++)
	{
		$selected = ( $mode == $mode_types[$i] ) ? ' selected="selected"' : '';
		$select_sort_mode .= '<option value="' . $mode_types[$i] . '"' . $selected . '>' . $mode_types_text[$i] . '</option>';
	}
	$select_sort_mode .= '</select>';

	$select_sort_order = '<select name="order">';
	if($sort_order == 'ASC')
	{
		$select_sort_order .= '<option value="ASC" selected="selected">' . $lang['Sort_Ascending'] . '</option><option value="DESC">' . $lang['Sort_Descending'] . '</option>';
	}
	else
	{
		$select_sort_order .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Sort_Descending'] . '</option>';
	}
	$select_sort_order .= '</select>';

	if ( isset($mode) )
	{
		switch( $mode )
		{
			case 'id':
				$order_by = "id $sort_order LIMIT $start, " . $board_config['topics_per_page'];
				break;
			case 'seller':
				$order_by = "seller $sort_order LIMIT $start, " . $board_config['topics_per_page'];
				break;
			case 'items':
				$order_by = "items $sort_order LIMIT $start, " . $board_config['topics_per_page'];
				break;
			case 'endtime':
				$order_by = "endtime $sort_order LIMIT $start, " . $board_config['topics_per_page'];
				break;
			case 'title':
        	                $order_by = "title $sort_order LIMIT $start, " . $board_config['topics_per_page'];
				break;
			case 'price':
        	                $order_by = "currentprice $sort_order LIMIT $start, " . $board_config['topics_per_page'];
				break;
			case 'bids':
				$order_by = "bids $sort_order LIMIT $start, " . $board_config['topics_per_page'];
				break;
			default:
				$order_by = "endtime ASC LIMIT $start, " . $board_config['topics_per_page'];
				break;
		}
	}
	else
	{
		$order_by = "endtime ASC LIMIT $start, " . $board_config['topics_per_page'];
	}

	$sql = "SELECT *
		FROM " . AUCTIONS_TABLE . "
		ORDER BY $order_by";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query auctions', '', __LINE__, __FILE__, $sql);
	}

	if ( $count = $db->sql_numrows($result) )
	{
		for ( $i = 0; $i < $count; $i++ )
		{
			$row = $db->sql_fetchrow($result);

			$noshow = 0;
			$tablerows = 6;

			$endtime = create_date('d/m/y H:i:s', $row['endtime'], $board_config['board_timezone']);
			$sellername = get_userdata(intval($row[seller]));

			$item_list2 = "'" . str_replace(':', "', '", $row['items']) . "'";
			$sql = "SELECT *
				FROM " . USER_ITEMS_TABLE . "
					WHERE worn = 4
						AND user_id = 0
						AND id IN (" . $item_list2 . ")";
			if ( !($result2 = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not get items', '', __LINE__, __FILE__, $sql);
			}

			$item_list = '';
			if ( $count2 = $db->sql_numrows($result2) )
			{
			     	for ($ii = 0; $ii < $count2; $ii++)
				{
					$row2 = $db->sql_fetchrow($result2);

					if ( $row2['wrapped'] )
					{
						$row2['item_name'] = $row2['wrapped'];
					}

					$item_list .= ( ( empty($item_list) ) ? '' : ', ' ) . $row2['item_name'];
				}
			}

			$currenttime = time();
			if ( $currenttime > $row['endtime'] ) { $endtime = 'OVER'; }
			elseif  ( $endtime != 'OVER' )
			{
				$timeleft = $row['endtime'] - time();
				$dur_left = duration($timeleft);
				if ( ( $timeleft < 3600 ) && ( $timeleft > 1800 ) ) { $dur_left = '<font color="darkyellow">' . $dur_left . '</font>'; }
				elseif ( $timeleft < 1800 ) { $dur_left = '<font color="darkred">' . $dur_left . '</font>'; }

				$endtime .= '<br />' . $dur_left . ' remaining.';
			}

			if ($row['bidhistory'] == "") {$highbidder = 'n/a';}
			if ($row['bids'] == "0") {$highbidder = 'n/a';} 
			if ($row['bidhistory'] != "")
			{
				$topbidders = substr($row[bidhistory],0,strpos($row[bidhistory],'1;')+1); 
				$bidders = explode(';',$topbidders);
				$bidderscount = count($bidders);
				$highbidderinfo = explode(',',$bidders[$bidderscount-1]);
				$highbiddername = get_userdata(intval($highbidderinfo[0]));
				$highbidder = $highbiddername[user_id];
			}
			$topbidders = $highbidder = (($highbidder == "") || ($highbidder == "n/a") || ($highbidderinfo[3] == ""))  ? 'n/a' : $highbidder; 

			$fixedtitle = str_replace("'", "\'", $row['title']);

			//if the auction is over and has a winner, send a message to the winning bidder
			if (($currenttime > $row['endtime']+180) && ($row[notify1] == 0) && ($highbidder != 'n/a'))
			{
				$sql2 = "UPDATE " . AUCTIONS_TABLE . "
					SET notify1 = '1'
					WHERE id = '{$row['id']}'";
				if ( !( $db->sql_query($sql2) ) )
				{
					message_die(GENERAL_ERROR, 'Could not update notify info', '', __LINE__, __FILE__, $sql);
				}

				$sql = "SELECT *
					FROM " . USERS_TABLE . "
					WHERE user_id = '$highbidder'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, sprintf($lang['shop_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
				}
				$owner_row = $db->sql_fetchrow($result);

				$privmsg_subject = 'Auction ' . $row['id'] . ': You have won!';
				$message = '[b]Automated Message:[/b]
You have won [url=' . $forumurl. 'shop_auctions.php?action=viewauction&id=' . $row[id] . ']Auction ID #' . $row[id] . '[/url] (' . $fixedtitle . ') for ' . $row['currentprice'] . $pointsname . '!

Please visit the auction to make your payment and receive the items. Payment is expected within 3 days of the auction end time, after that the seller may freely close the auction and reclaim their items.

The auction will be automatically closed one week after it ends.';
				cash_pm($owner_row, $privmsg_subject, $message);

			}

			//if the auction is over and has no winner, notify seller
			if (($currenttime > $row[endtime]+180) && ($row[notify1] == 0) && ($highbidder == 'n/a'))
			{
				$item_list2 = "'" . str_replace(':', "', '", $row['items']) . "'";
				$sql = "UPDATE " . USER_ITEMS_TABLE . "
					SET worn = 0,
						user_id = '{$row['seller']}'
					WHERE worn = 4
						AND user_id = 0
						AND id IN (" . $item_list2 . ")";
				if ( !($db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not get items', '', __LINE__, __FILE__, $sql);
				}

				$sql2a = "DELETE FROM " . AUCTIONS_TABLE . "
					WHERE id = '{$row['id']}'";
				if ( !($result2a = $db->sql_query($sql2a)) )
				{
					message_die(GENERAL_ERROR, 'Could not delete closed auction ID#'.$row[id], '', __LINE__, __FILE__, $sql);
				}

				$sql = "SELECT *
					FROM " . USERS_TABLE . "
					WHERE user_id = '{$row['seller']}'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, sprintf($lang['shop_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
				}
				$owner_row = $db->sql_fetchrow($result);

				$privmsg_subject = 'Auction ' . $row[id] . ': Expired with no bids';
				$message = '[b]Automated Message:[/b]
Your Auction, [url=' . $forumurl . 'shop_auctions.php?action=viewauction&id=' . $row['id'] . ']ID #' . $row['id'] . '[/url] (' . $fixedtitle . ') has ended without a winning bidder.

The auction has been automatically closed and your items have been returned to you.';
				cash_pm($owner_row , $privmsg_subject, $message);

				$noshow = 1;
			}

			//if the auction ended over 3 days ago and the bidder has not paid, notify seller
			if (($currenttime > $row[endtime]+259200) && ($row[notify1] == 1) && ($row[notify2] != 1) && ($highbidder != 'n/a'))
			{
				$sql = "UPDATE " . AUCTIONS_TABLE . "
					SET notify2 = '1'
					WHERE id = '{$row['id']}'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not update notify info', '', __LINE__, __FILE__, $sql);
				}

				$sql = "SELECT *
					FROM " . USERS_TABLE . "
					WHERE user_id = '{$row['seller']}'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, sprintf($lang['shop_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
				}
				$owner_row = $db->sql_fetchrow($result);


				$privmsg_subject = 'Auction ' . $row['id'] . ': Bidder has not yet paid!';
				$message = '[b]Automated Message:[/b]
Your Auction, [url=' . $forumurl . 'shop_auctions.php?action=viewauction&id=' . $row['id'] . ']ID #' . $row['id'] . '[/url] (' . $fixedtitle . ') ended 3 or more days ago and the winning bidder, ' . $highbiddername['username'] . ', has not yet paid the ' . $row['currentprice'] . $pointsname . '.

If you wish, you may close the auction at no cost, and your item will be returned to you.
Alternatively, you may wish to contact the winning bidder to request payment.

If the auction remains unpaid for a week after it has ended, it will be automatically closed and the items returned to you.';
				cash_pm($owner_row, $privmsg_subject, $message);
			}

			//if the auction is LOOONG over [7 days] and has not been paid, close auction, return items & notify seller
			if (($currenttime > $row[endtime]+604800) && ($row[notify1] == 1) && ($row[notify2] == 1))
			{
				$item_list2 = "'" . str_replace(':', "', '", $row['items']) . "'";
				$sql = "UPDATE " . USER_ITEMS_TABLE . "
					SET worn = 0,
						user_id = '{$row['seller']}'
					WHERE worn = 4
						AND user_id = 0
						AND id IN (" . $item_list2 . ")";
				if ( !($db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not get items', '', __LINE__, __FILE__, $sql);
				}

				if ($highbidder != 'n/a')
				{
					$sql2a = "UPDATE " . USERS_TABLE . "
						SET auctions_unpaid = auctions_unpaid+1
						WHERE user_id = '{$highbiddername['user_id']}'"; 
					if ( !($result2a = $db->sql_query($sql2a)) )
					{
						message_die(GENERAL_ERROR, 'Could not increment unpaid auctions!', '', __LINE__, __FILE__, $sql2a);
					} 
				} 

				$sql2a = "DELETE FROM " . AUCTIONS_TABLE . "
					WHERE id = '{$row['id']}'";
				if ( !($result2a = $db->sql_query($sql2a)) )
				{
					message_die(GENERAL_ERROR, 'Could not delete closed auction ID#'.$row[id], '', __LINE__, __FILE__, $sql);
				}

				$sql = "SELECT *
					FROM " . USERS_TABLE . "
					WHERE user_id = '{$row['seller']}'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, sprintf($lang['shop_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
				}
				$owner_row = $db->sql_fetchrow($result);

				$privmsg_subject = 'Auction ' . $row['id'] . ': Expired!';
				$message = '[b]Automated Message:[/b]
Your Auction, [url=' . $forumurl . 'shop_auctions.php?action=viewauction&id=' . $row['id'] . ']ID #' . $row['id'] . '[/url] (' . $fixedtitle . ') has remained unpaid by the bidder, ' . $highbiddername['username'] . ', for over a week since it ended.
The auction has been automatically closed and the items returned to you.';
				cash_pm($owner_row, $privmsg_subject, $message);
	
				$noshow = 1;
			}

			if ($row[bids] >= 10)
			{
				$bidcolor = 'style="color:red"';
				$hoticon = '<span class="gen" style="color:red"><b>HOT!</b></span> ';
			}
			else
			{
				$bidcolor = '';
				$hoticon = '';
			}

			$showbuyprice = ( ($row['bids'] == 0) && ($row['buyprice'] != 0) ) ? '<br /><span class="gensmall" style="color:#FFD700"><i>' . $row['buyprice'] . $pointsname . '</i></span>' : '';

			$highbidderdetails = ($highbidder != "n/a") ? '<br /><span class="gensmall">&nbsp &raquo <A HREF="' . append_sid('profile.php?mode=viewprofile&u=' . $highbiddername['user_id']) . '">' . $highbiddername['username'] . '</a></span>' : '';

			$newicon = ($currenttime < ($row['starttime'] + 21600) ) ? '<span class="gen" style="color:blue"><b>NEW:</b></span> ' : '';

			if ($noshow == 0)
			{
				$z++;

				$rownum = ( $i % 2 ) ? "row2" : "row1";

				$template->assign_block_vars('list_auctions', array(
					'ROW_CLASS' => $rownum,
					'ID' => $z,

					'SELLER_LINK' => append_sid('profile.php?mode=viewprofile&u=' . $row['seller']),
					'SELLER_NAME' => $sellername['username'],
					'HIGH_BIDDER' => $highbidderdetails,
					'CURRENT_PRICE' => $row['currentprice'],
					'BUY_PRICE' => $showbuyprice,
					'BID_COLOR' => $bidcolor,
					'BID_AMOUNT' => $row['bids'],
					'TIME_LEFT' => $endtime,

					'ICONS' => $hoticon . $newicon,
					'VIEW_AUCTION' => append_sid($filename.'?action=viewauction&id=' . $row['id']),
					'AUCTION_NAME' => $row['title'],
					'ITEM_LIST' => $item_list
				));
			}
		}
	}

	if ( $mode != 'topten' || $board_config['topics_per_page'] < 10 )
	{
		$sql = "SELECT count(*) AS total
			FROM " . AUCTIONS_TABLE;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error getting total auctions', '', __LINE__, __FILE__, $sql);
		}

		if ( $total = $db->sql_fetchrow($result) )
		{
			$total_auctions = $total['total'];

			$pagination = generate_pagination("$filename?mode=$mode&amp;order=$sort_order", $total_auctions, $board_config['topics_per_page'], $start). '&nbsp;';
		}
	}
	else
	{
		$pagination = '&nbsp;';
		$total_auctions = 10;
	}
	make_jumpbox('viewforum.'.$phpEx);

	$auctionlocation = ' -> <a href="' . append_sid('shop_auctions.php') . '" class="nav">' . $auctionsname . '</a>';
	$title = $auctionsname;
	$page_title = $auctionsname;


	$personal = '
		<tr>
		  <td class="row1" width="50%"><span class="gensmall"><a href="' . append_sid('shop.php?action=inventory&searchid=' . $userdata['user_id']) . '">Your Inventory</a></span></td>
		  <td class="row1" align="right" width="50%"><span class="gensmall">' . $userdata['user_points'] . ' Gold</span></td>
		</tr>';

	$template->assign_vars(array(
		'U_ACTION' => append_sid($filename),

		'AUCTION_SORTING' => '<form method="post" action="'.append_sid($filename).'"><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center"><tr><td align="left"></td><td align="right" nowrap="nowrap"><span class="gen">'.$lang['Select_sort_method'].':&nbsp;'.$select_sort_mode.'&nbsp;&nbsp;Order:&nbsp;'.$select_sort_order.'&nbsp;&nbsp;<input type="submit" name="submit" value="'.$lang['Sort'].'" class="liteoption" /></span></td></tr></table></form>',
		'PAGINATION' => $pagination,
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_auctions / $board_config['topics_per_page'] )),
		'L_GOTO_PAGE' => $lang['Goto_page'],

		'AUCTIONPERSONAL' => $personal,
		'AUCTIONLOCATION' => $auctionlocation,
		'L_AUCTION_TITLE' => $title,

		'L_CREATEAUCTION' => "Create a new Auction",
		'U_CREATEAUCTION' => append_sid($filename.'?action=createauction'),
		'L_BROWSEAUCTIONS' => "Browse Auctions",

		'USER_POINTS' => $userdata['user_points'],

		'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']),

		'L_PERSONAL_INFO' => 'Personal Information',
		'L_INVENTORY' => 'Your Inventory',
		'L_POINTS_NAME' => $board_config['points_name']
	));
	$template->assign_block_vars('', array());
}
//--------------------END SHOW AUCTIONS--------------------------//

//--------------------CREATE AUCTION---------------------------------//

if ( $action == 'createauction' )
{
	$template->set_filenames(array(
		'body' => 'auction_add.tpl')
	);

	$currenttime = time();
	//check how many auctions they already have
	$sql = "SELECT *
		FROM " . AUCTIONS_TABLE . "
		WHERE seller = '{$userdata['user_id']}'
			AND endtime > $currenttime"; 
	if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'Fatal Error Getting User Auctions!'); }

	if ( $db->sql_numrows($result) >= $maxauctions )
	{
		message_die(GENERAL_MESSAGE, 'You are already running enough auctions!');
	}

	//set useritems into variable 
	$sql = "SELECT *
		FROM " . USER_ITEMS_TABLE . "
		WHERE user_id = '{$userdata['user_id']}'
			AND worn = '0'
		ORDER BY `item_name`";
	if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'Fatal Error Getting User Items!'); }

	if ( !( $count = $db->sql_numrows($result)) )
	{
		$itemslist .= ( !($temp_var) ) ? '<option value="Nothing">Nothing</option>' : '';
	}

	for ($i = 0; $i < $count; $i++) 
	{ 
		$row = $db->sql_fetchrow($result);

		if ( $row['wrapped'] )
		{
			$row['item_name'] = $row['wrapped'];
		}

		$itemslist .= '<option value="' . $row['id'] . '">' . $row['item_name'] . '</option>';
		$temp_var = 1;
	} 

	make_jumpbox('viewforum.'.$phpEx);
	$auctionlocation = ' -> <a href="' . append_sid('shop_auctions.php') . '" class="nav">' . $auctionsname . '</a> -> Create a New Auction';
	$title = "Create a New Auction";
	$page_title = "Create a New Auction";

	$personal = '
		<tr>
		  <td class="row1" width="50%"><span class="gensmall"><a href="' . append_sid('shop.php?action=inventory&searchid=' . $userdata['user_id']) . '">Your Inventory</a></span></td>
		  <td class="row1" align="right" width="50%"><span class="gensmall">' . $userdata['user_points'] . ' Gold</span></td>
		</tr>';

	$template->assign_vars(array(
		'U_ACTION' => append_sid($filename),

		'AUCTIONPERSONAL' => $personal,
		'AUCTIONLOCATION' => $auctionlocation,
		'L_AUCTION_TITLE' => $title,
		'ITEMS' => $itemslist,

		'L_CREATEAUCTION' => "Create a new Auction",
		'U_CREATEAUCTION' => append_sid($filename.'?action=createauction'),
		'L_BROWSEAUCTIONS' => "Browse Auctions",

		'USER_POINTS' => $userdata['user_points'],

		'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']),

		'L_PERSONAL_INFO' => 'Personal Information',
		'L_INVENTORY' => 'Your Inventory',
		'L_POINTS_NAME' => $board_config['points_name']

	));
	$template->assign_block_vars('', array());
}


//--------------------END CREATE AUCTION--------------------------//

//--------------------ADD NEW AUCTION---------------------------------//

if ($action == "addnewauction")
{
	$currenttime = time();
	//check how many auctions they already have
	$sql = "SELECT *
		FROM " . AUCTIONS_TABLE . "
		WHERE seller = '{$userdata['user_id']}'
			AND endtime > $currenttime"; 
	if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'Fatal Error Getting User Auctions!'); }

	if ( $db->sql_numrows($result) >= $maxauctions )
	{
		message_die(GENERAL_MESSAGE, 'You are already running enough auctions!');
	}

	if ( ($startprice < 1) || (!is_numeric($startprice)) )
	{
		message_die(GENERAL_MESSAGE, 'You must specify a starting price!');
	}

	if ( ($increment < 1) || (!is_numeric($increment)) )
	{
		message_die(GENERAL_MESSAGE, 'You must specify a bid increment!');
	}

	if ( ($duration > 864000) || (!is_numeric($duration)) || ($duration < 3600) )
	{
		message_die(GENERAL_MESSAGE, 'You must specify an auction duration!');
	}

	if ( $items[0] == "Nothing" )
	{
		message_die(GENERAL_MESSAGE,"You must select at least one item!");
	}

	if ( count($items) < 1 )
	{
		message_die(GENERAL_MESSAGE,"You must select at least one item!");
	}

	if ( count($items) > $maxitems )
	{
		message_die(GENERAL_MESSAGE,"Too many items selected!");
	}

	if ($title == "")
	{
		message_die(GENERAL_MESSAGE,"You must enter a title!");
	}

	if ($description == '')
	{
		message_die(GENERAL_MESSAGE,"You must enter a description!");
	}

	//check they have the items!
	$count = count($items);

	for ($i = 0; $i < $count; $i++)
	{
		$sql = "SELECT *
			FROM " . USER_ITEMS_TABLE . " 
			WHERE id = '$items[$i]'
				AND user_id = '{$userdata['user_id']}'
				AND worn = '0'";
		if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'Fatal Error Getting User Items!'); }

		if ( !( $db->sql_numrows($result)) )
		{
			message_die(GENERAL_MESSAGE, 'You do not have all of those items! Press back and try again.');
		}
	}

	#
	# Now that items are checked, move items over!
	#
	for ($i = 0; $i < $count; $i++)
	{
		$sql = "UPDATE " . USER_ITEMS_TABLE . "
			SET worn = '4',
				user_id = '0'
			WHERE id = '$items[$i]'
				AND user_id = '{$userdata['user_id']}'
				AND worn = '0'";
		if ( !( $db->sql_query($sql) ) ) { message_die(GENERAL_MESSAGE, 'Fatal Error updating items, contact an administrator!'); }
	}

	//create items field in correct format
	for ($i = 0; $i < $count; $i++)
	{
		$auction_list .= ( ( empty($auction_list) ) ? ':' : '' ) . $items[$i] . ':';
	}

	$title = htmlspecialchars($title); 

	$description = str_replace("<", "&lt", $description); 
	$description = str_replace(">", "&gt", $description);

	$timenow = time();
	$timeend = $timenow+$duration;

	//set auction
	$sql="INSERT INTO " . AUCTIONS_TABLE . "
		(seller, items, startprice, increment, buyprice, starttime, endtime, description, title, currentprice)
		VALUES('{$userdata['user_id']}', '$auction_list', '$startprice', '$increment', '$buyprice', '$timenow', '$timeend', '$description', '$title', '$startprice')";
	if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'Fatal Error.'); }

	message_die(GENERAL_MESSAGE, 'You have successfully added your action!<br /><br />Click <a href="shop_auctions.php" class="gen">here</a> to return to the auction listing.<br />Click <a href="index.php" class="gen">here</a> to return to the index.');
}
//--------------------END ADD NEW AUCTION--------------------------//

//--------------------VIEW AUCTION--------------------------//
if ( $action == 'viewauction' )
{
	$template->set_filenames(array(
		'body' => 'auction_view.tpl')
	);

	$sql = "SELECT *
		FROM " . AUCTIONS_TABLE . "
		WHERE id = '" . intval($id) . "'";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query auction info', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);

	if ( !( $numrows = $db->sql_numrows($result)) )  { message_die(GENERAL_MESSAGE,"No such auction exists!"); }

	$item_list2 = "'" . str_replace(':', "', '", $row['items']) . "'";
	$sql = "SELECT *
		FROM " . USER_ITEMS_TABLE . "
			WHERE worn = 4
				AND user_id = 0
				AND id IN (" . $item_list2 . ")";
	if ( !($result2 = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not get items', '', __LINE__, __FILE__, $sql);
	}

	if ( $count2 = $db->sql_numrows($result2) )
	{
	     	for ($i = 0; $i < $count2; $i++)
		{
			$row2 = $db->sql_fetchrow($result2);
			$sql = "SELECT *
				FROM " . SHOP_ITEMS_TABLE ."
				WHERE id = '{$row2['item_id']}'";
			if ( !($result3 = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not get items', '', __LINE__, __FILE__, $sql);
			}
			$row3 = $db->sql_fetchrow($result3);

			$cost = ( $row3['cost'] < 5 ) ? 'N/A' : $row3['cost'];

			if ( $row2['wrapped'] )
			{
				$row2['item_name'] = $row2['wrapped'];
				$cost = 'N/A';
				$inc = 'wrapped/';
				$row2['item_l_desc'] = 'N/A';
			}
			else { $inc = ''; }

			$enchanted = ( strlen($row2['enchanted']) > 10 ) ? '<br /><br />' . $row2['enchanted'] : '';

			if ( file_exists('shop/images/' . $inc . $row2['item_name'] . '.jpg') ) { $itemfilext = "jpg"; }
			else { $itemfilext = "gif"; }

			$template->assign_block_vars('list_items', array(
				'ROW_CLASS' => $rownum,

				'IMAGE' => $inc . $row2['item_name'] . '.' . $itemfilext,
				'ITEM_NAME' => $row2['item_name'],
				'ITEM_PRICE' => $cost,
				'ITEM_DESCRIPTION' => $row2['item_l_desc'] . $enchanted
			));
		}
	}

	$sellername = get_userdata(intval($row['seller']));
	$starttime = create_date('d/m/y H:i:s', $row[starttime], $board_config['board_timezone']);
	$endtime = create_date('d/m/y H:i:s', $row[endtime], $board_config['board_timezone']);

	$bids = explode(';',$row[bidhistory]);
	$bidcount = count($bids);
	$bidhistory = '';
     	for ($xb = 0; $xb < $bidcount; $xb++)
	{
		$thisbid = explode(',',$bids[$xb]);
		$biddername = get_userdata(intval($thisbid[0]));
		$retractinfo = '';
		if (($thisbid[3] == '1') && ($userdata[user_id] != $row[seller])) { $retractinfo=''; }
		if (($thisbid[3] == '1') && ($userdata[user_id] == $thisbid[0])) { $retractinfo='<script LANGUAGE="JavaScript">function confirmSubmit() {var agree=confirm("Are you sure you wish to continue?"); if (agree) return true; else return false ;}</script><A HREF="'.append_sid($filename."?action=retractbid&bid=".$bids[$xb]."&auctionid=".$id).'" onClick="return confirmSubmit()"><span class="gensmall">[SEND REQUEST]</A></span>'; }
		if ($thisbid[3] == 'R1') { $retractinfo='<span class="gensmall">[REQUESTED]</span>'; }
		if ($thisbid[3] == 'C1') { $retractinfo='<span class="gensmall">[NPB]</span>'; }
		if ((($thisbid[3] == 'R1') || ($thisbid[3] == 'C1')) && ($userdata[user_id] == $row[seller])) { $retractinfo='<script LANGUAGE="JavaScript">function confirmSubmit() {var agree=confirm("Are you sure you wish to continue?"); if (agree) return true; else return false ;}</script><A HREF="'.append_sid($filename."?action=cancelbid&bid=".$bids[$xb]."&auctionid=".$id).'" onClick="return confirmSubmit()"><span class="gensmall">[CANCEL BID]</A></span>'; }
		if ($thisbid[3] == '0') { $retractinfo='<span class="gensmall" style="color:red">[RETRACTED]</span>'; }

		if ($bids[$xb] != NULL) { $bidhistory .= '<tr><td><span class="gen"><A HREF="'.append_sid("profile.php?mode=viewprofile&u=".$thisbid[0]).'">'.$biddername['username'].'</A></span></td><td><span class="gen">'.$thisbid[1].$pointsname.'</span></td><td><span class="gensmall">' .create_date('d/m/y H:i:s', $thisbid[2], $board_config['board_timezone']).'</span></td><td><span class="gen">'.$retractinfo.'</span></td></tr><tr><td colspan="4" height="1" class="row1"></td></tr>'; }
	}
	if ($bidhistory != '') {$bidhistory = '<tr><td><span class="gen"><b>User:</b></td><td><span class="gen"><b>Bid:</b></span></td><td><span class="gen"><b>Date/Time:</b></span></td><td><span class="gen"><b>Retraction:</b></span></td></tr><tr><td colspan="4" height="1" class="row1"></td></tr>'.$bidhistory;}
	if ($bidhistory == '') {$bidhistory = '<tr><td><span class="gen">No bids!</span></td></tr>';}

	if ($row[bidhistory] == "") {$highbidder = 'n/a';}
	if ($row[bids] == "0") {$highbidder = 'n/a';}
	if ($row[bidhistory] != "")
	{
		$topbidders = substr($row[bidhistory],0,strpos($row[bidhistory],'1;')+1); 
		$bidders = explode(';',$topbidders);
		$bidderscount = count($bidders);
		$highbidderinfo = explode(',',$bidders[$bidderscount-1]);
		$highbiddername = get_userdata(intval($highbidderinfo[0]));
		$highbidder = $highbiddername['username'];
		$currentprice = $highbidderinfo[1];
	}
	$highbidder = (($highbidder == "") || ($highbidder == "n/a"))  ? 'n/a' : '<A HREF="'.append_sid("profile.php?mode=viewprofile&u=".$highbiddername[user_id]).'">'.$highbidder.'</a>';
	$currentprice = ($currentprice == "") ? $row[startprice] : $currentprice;
	$minbid = (($currentprice == $row[startprice]) && ($highbidder == 'n/a')) ? $row['startprice'] : ($currentprice + $row['increment']);


	$buynow = ( ($row['buyprice'] != 0) && ($row['buyprice'] > $highbidderinfo[1]) ) ? '<hr size="1"><form method="post" action="'.append_sid($filename).'"><span class="gen"><input type="hidden" name="action" value="buynow" /><input type="hidden" name="auctionid" value="' . $id . '" /><b>Buy now:</b><br /><i>(available until bid goes over buyout)</i><br />' . $row[buyprice] . ' ' . $pointsname . ' <input type="submit" value="Buy Now!" /></span></form>' : '';

	//bid
	$timenow = time();
	if ( $timenow < $row['endtime'] )
	{
		$bidoption = $buynow.'<hr size="1"><form method="post" action="'.append_sid($filename).'"><span class="gen"><input type="hidden" name="action" value="placebid"><input type="hidden" name="auctionid" value="'.$id.'"><b>Place bid:</b> <i>(minimum '.$minbid.$pointsname.')<BR></i><input type="text" name="bidamt" value="'.$minbid.'" size="6" maxlength="6">'.$pointsname.' <input type="submit" value="BID!"></span></form><hr size="1">';
	}

	//over
	if ( $timenow >= $row['endtime'] )
	{
		$bidoption = '<hr size="1"><span style="color:red"><b>Sorry, this auction is now over and no more bids may be placed!</b></span><hr size="1">';
	}

	//figuring out winner
	if ( ($timenow >= $row['endtime']) && ($userdata['user_id'] == $highbiddername['user_id']) )
	{
		$bidoption = '<hr size="1"><b><span style="color:red">Sorry, this auction is now over and no more bids may be placed!</span><P><span color="blue">Currently determining auction winner.</span></b><hr size="1">';
	}

	//pay
	if ( ($timenow >= $row['endtime']+60) && ($userdata['user_id'] == $highbiddername['user_id']) )
	{
		$bidoption = '<hr size="1"><form method="post" action="'.append_sid($filename).'"><span class="gen"><input type="hidden" name="action" value="payauction"><input type="hidden" name="auctionid" value="'.$id.'"><b>Pay for this auction?</b><BR>'.$currentprice.$pointsname.' <input type="submit" value="PAY NOW!"></span></form><hr size="1">';
	}

	//close before finished
	if ( ($timenow < $row['endtime']) && ($userdata['user_id'] == $row['seller']) )
	{
		$bidoption = '<hr size="1"><script LANGUAGE="JavaScript">function confirmSubmit() {var agree=confirm("Are you sure you wish to continue?"); if (agree) return true; else return false ;}</script><form method="post" action="'.append_sid($filename).'"><span class="gen"><input type="hidden" name="action" value="closeearly"><input type="hidden" name="auctionid" value="'.$id.'"><b>Close this auction?</b><BR>Closing the auction early will cost you '.round($currentprice/10).$pointsname.'.<BR><input type="submit" value="CLOSE AUCTION" onClick="return confirmSubmit()"></span></form><hr size="1">';
	}

	//close before finished
	if ( ($timenow < $row['endtime']) && ($userdata['user_id'] == $row['seller']) && ($row['bidhistory'] == '') )
	{
		$bidoption = '<hr size="1"><script LANGUAGE="JavaScript">function confirmSubmit() {var agree=confirm("Are you sure you wish to continue?"); if (agree) return true; else return false ;}</script><form method="post" action="'.append_sid($filename).'"><span class="gen"><input type="hidden" name="action" value="closeearly"><input type="hidden" name="auctionid" value="'.$id.'"><b>Close this auction?</b><BR>As there have not yet been any bids, you will not be charged for closing this auction.<BR><input type="submit" value="CLOSE AUCTION" onClick="return confirmSubmit()"></span></form><hr size="1">';
	}


	//close
	//if the auction is over, and there is no high bidder, allow seller to close auction
	if ( ($timenow >= $row['endtime']) && ($userdata['user_id'] == $row['seller']) && ($highbidder == 'n/a') )
	{
		$bidoption = '<hr size="1"><script LANGUAGE="JavaScript">function confirmSubmit() {var agree=confirm("Are you sure you wish to continue?"); if (agree) return true; else return false ;}</script><form method="post" action="'.append_sid($filename).'"><span class="gen"><input type="hidden" name="action" value="closeauction"><input type="hidden" name="auctionid" value="'.$id.'"><b>Close this auction?</b><BR><span style="color:red">This auction is over, without a winning bidder. <BR>Closing the auction will return the items to you.</span><BR><input type="submit" value="CLOSE AUCTION" onClick="return confirmSubmit()"></span></form><hr size="1">';
	}

	//if it's less than three days since the end, and the buyer hasn't paid yet, seller may not close auction
	if ( ($timenow >= $row['endtime']) && ($userdata['user_id'] == $row['seller']) && ($highbidder != 'n/a') )
	{
		$bidoption = '<hr size="1"><span class="gen"><b>Close this auction?</b><BR><span style="color:red">This auction is over, but the buyer still has time to pay. If they do not pay within 3 days of the auction\'s end, you may close the auction.</span><hr size="1">';
	}

	//if it's more than 3 days since the end, and buyer hasn't paid, allow seller to close auction
	if ( ($timenow >= $row['endtime']+259200) && ($userdata['user_id'] == $row['seller']) )
	{
		$bidoption = '<hr size="1"><script LANGUAGE="JavaScript">function confirmSubmit() {var agree=confirm("Are you sure you wish to continue?"); if (agree) return true; else return false ;}</script><form method="post" action="'.append_sid($filename).'"><span class="gen"><input type="hidden" name="action" value="closeauction"><input type="hidden" name="auctionid" value="'.$id.'"><b>Close this auction?</b><BR><span color="blue">The winning bidder has not yet paid. Closing the auction will return the items to you.</span><BR><input type="submit" value="CLOSE AUCTION" onClick="return confirmSubmit()"></span></form><hr size="1">';
	}

	$timenow = time();
	if ($row['endtime'] > $timenow)
	{
		$timeleft = $row['endtime'] - $timenow;
		$remainingtime = duration($timeleft);
		if ( ( $timeleft < 3600 ) && ( $timeleft > 1800 ) ) { $remainingtime = '<font color="darkyellow">' . $remainingtime . '</font>'; }
		elseif ( $timeleft < 1800 ) { $remainingtime = '<font color="darkred">' . $remainingtime . '</font>'; }
	}
	else { $remainingtime = "This auction is over."; }

	$colspan = ($itemcount > $itemrow) ? $itemrow*3 : $itemcount*3;

	make_jumpbox('viewforum.'.$phpEx);

	$auctionlocation = " -> Viewing Auction";
	$title = "Viewing Auction: ".$row['title']." (".$currentprice.$pointsname.")";
	$page_title = "Viewing Auction";


	$personal = '
		<tr>
		  <td class="row1" width="50%"><span class="gensmall"><a href="' . append_sid('shop.php?action=inventory&searchid=' . $userdata['user_id']) . '">Your Inventory</a></span></td>
		  <td class="row1" align="right" width="50%"><span class="gensmall">' . $userdata['user_points'] . ' Gold</span></td>
		</tr>';

	$template->assign_vars(array(
		'U_ACTION' => append_sid($filename),

		'AUCTIONPERSONAL' => $personal,
		'AUCTIONLOCATION' => $auctionlocation,
		'L_AUCTION_TITLE' => $title,

		'L_CREATEAUCTION' => "Create a new Auction",
		'U_CREATEAUCTION' => append_sid($filename.'?action=createauction'),
		'L_BROWSEAUCTIONS' => "Browse Auctions",

		'U_SELLER' => append_sid('profile.php?mode=viewprofile&u=' . $row['seller']),
		'SELLER' => $sellername['username'],
		'HIGH_BIDDER' => $highbidder,
		'TOTAL_BIDS' => $row['bids'],
		'START_TIME' => $starttime,
		'END_TIME' => $endtime,
		'REMAINING_TIME' => $remainingtime,
		'START_PRICE' => $row['startprice'],
		'BID_INCREASE' => $row['increment'],
		'CURRENT_PRICE' => $currentprice,
		'BID_OPTION' => $bidoption,
		'BID_HISTORY' => $bidhistory,
		'DESCRIPTION' => $row['description'],

		'USER_POINTS' => $userdata['user_points'],

		'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']),

		'L_PERSONAL_INFO' => 'Personal Information',
		'L_INVENTORY' => 'Your Inventory',
		'L_POINTS_NAME' => $board_config['points_name']
		
	));
	$template->assign_block_vars('', array());
}

//--------------------END VIEW AUCTION--------------------------//

//--------------------PLACE BID--------------------------//
if ( $action == 'placebid' )
{

	$sql = "SELECT *
		FROM " . AUCTIONS_TABLE . "
		WHERE id = '" . intval($auctionid) . "'";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query auction info', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);

	if ( !($numrows = $db->sql_numrows($result)) ) { message_die(GENERAL_MESSAGE,"No such auction exists!"); }

	if ($userdata['user_id'] == $row['seller'])
	{
		message_die(GENERAL_ERROR, 'You cannot bid on your own auction!');
	}

	$currenttime = time();
	if ($currenttime > $row['endtime'])
	{
		message_die(GENERAL_ERROR, 'Sorry, this auction is already over!');
	}

	#
	# Make sure user has enough gold to make bid (max of 1.5x total gold on character)
	#

	if ( defined('BANK_TABLE') )
	{
		$sql = "SELECT *
			FROM " . BANK_TABLE . "
			WHERE " . ( ( $bank_version ) ? 'user_id' : 'name' ) . " = '{$userdata['user_id']}'";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query auction info', '', __LINE__, __FILE__, $sql);
		}
		$brow = $db->sql_fetchrow($result);
	}
	else
	{
		$brow['holding'] = '0';
	}

	if ( (($brow['holding'] + $userdata['user_points']) * 1.5) < $bidamt )
	{
		message_die(GENERAL_MESSAGE, 'You may only bid a maximum of 1.5x what you currently own.<br /><br />Click <a href="javascript: history(-1)" class="nav">here</a> to return to previous page.');
	}

	#
	# End check for allowed amount of gold
	#

	$minbid = ($row['currentprice'] > $row['startprice']) ? ($row['currentprice'] + $row['increment']) : $row['startprice'];

	if ((!is_numeric($bidamt)) || ($bidamt < $minbid) || ($bidamt == 0))
	{
		message_die(GENERAL_ERROR, 'You have entered an invalid bid amount.');
	}


	$item_list2 = "'" . str_replace(':', "', '", $row['items']) . "'";
	$sql = "SELECT *
		FROM " . USER_ITEMS_TABLE . "
			WHERE worn = 4
				AND user_id = 0
				AND id IN (" . $item_list2 . ")";
	if ( !($result2 = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not get items', '', __LINE__, __FILE__, $sql);
	}

	if ( $count2 = $db->sql_numrows($result2) )
	{
	     	for ($ii = 0; $ii < $count2; $ii++)
		{
			$row2 = $db->sql_fetchrow($result2);

			if ( $row2['wrapped'] )
			{
				$row2['item_name'] = $row2['wrapped'];
			}

			$item_list .= ( ( empty($item_list) ) ? '' : ', ' ) . $row2['item_name'];
		}
	}

	$action = '
		</td></tr>
		<tr>
		  <td class="row1" align="center"><br />
			<table width="100%">
				<tr>
				  <td align="center">
					<span class="gen"><b>Are you sure you want to place this bid?</b><p><b>' . $row['title'] . '</b><br />(<i>'.$item_list.'</i>)<p>' . $bidamt . $pointsname . '</span><p>
					<table width="450">
						<tr>
						  <td><span class="gensmall">Note: If you win this auction, you have 3 days in which to pay, after which time the seller may freely close the auction if you have not paid.<br />Payments must be made through the auction page, and you will receive the items immediately upon payment.</td>
						</tr>
					</table>
					</span>
					<form method="post" action="'.append_sid($filename).'">
						<input type="hidden" name="action" value="bidnow" />
						<input type="hidden" name="auctionid" value="' . $auctionid . '" />
						<input type="hidden" name="bidamt" value="' . $bidamt . '" />
						<input type="submit" value="Place Bid"> <input type="button" value="Cancel" onClick="document.location=\''.append_sid($filename."?action=viewauction&id=" . $auctionid) . '\';">
					</form>
				  </td>
				</tr>
			</table>
		  </span>';

	message_die(GENERAL_MESSAGE, $action);

}
//--------------------END PLACE BID--------------------------//

//--------------------BID NOW--------------------------//
if ($action == 'bidnow')
{
	$sql = "SELECT *
		FROM " . AUCTIONS_TABLE . "
		WHERE id = '" . intval($auctionid) . "'";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query auction info', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);

	if ( !($numrows = $db->sql_numrows($result)) ) {message_die(GENERAL_MESSAGE,"No such auction exists!");}

	if ($userdata['user_id'] == $row['seller'])
	{message_die(GENERAL_ERROR, 'You cannot bid on your own auction!');}

	$currenttime = time();
	if ($currenttime > $row['endtime'])
	{
		message_die(GENERAL_ERROR, 'Sorry, this auction is already over!');
	}

	#
	# Make sure user has enough gold to make bid (max of 1.5x total gold on character)
	#

	if ( defined('BANK_TABLE') )
	{
		$sql = "SELECT *
			FROM " . BANK_TABLE . "
			WHERE " . ( ( $bank_version ) ? 'user_id' : 'name' ) . " = '{$userdata['user_id']}'";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query auction info', '', __LINE__, __FILE__, $sql);
		}
		$brow = $db->sql_fetchrow($result);
	}
	else
	{
		$brow['holding'] = '0';
	}

	if ( (($brow['holding'] + $userdata['user_points']) * 1.5) < $bidamt )
	{
		message_die(GENERAL_MESSAGE, 'You may only bid a maximum of 1.5x what you currently own.<br /><br />Click <a href="javascript: history(-1)" class="nav">here</a> to return to previous page.');
	}

	#
	# End check for allowed amount of gold
	#


	//added the && bidhistory bit, might need to remove it.
	$minbid = (($row['currentprice'] > $row['startprice']) && ($row['bidhistory'] != "")) ? $row['currentprice']+$row['increment'] : $row['startprice'];

	if ((!is_numeric($bidamt)) || ($bidamt < $minbid) || ($bidamt == 0))
	{
		message_die(GENERAL_ERROR, 'You have entered an invalid bid amount.');
	}

	if ($userdata['auctions_unpaid'] > 0)
	{
		$npb_cancel = "C";
	}

	if ( ( $row['endtime'] - time() ) < 3600 ) { $sql_add = ', endtime = endtime + 300'; $msg_add = '<br />This auction has had its time increased by 5minutes due to having an hour left. This helps prevent "snipe" bidding.'; }

	$newbid = $userdata[user_id].','.$bidamt.','.$currenttime.','.$npb_cancel.'1;';

	$sql = "UPDATE " . AUCTIONS_TABLE . "
		SET bidhistory = CONCAT('$newbid',bidhistory),
			bids = bids+1,
			currentprice = $bidamt
			$sql_add
		WHERE id = '$auctionid'";
	if( !( $db->sql_query($sql) ) )
	{
		message_die(GENERAL_ERROR, 'Could not query auction info', '', __LINE__, __FILE__, $sql);
	}

	#
	# Send privaet message to person who was outbid...
	#

	if ($row['bidhistory'] != "")
	{
		$topbidders = substr($row['bidhistory'],0,strpos($row['bidhistory'],'1;')+1); 
		$bidders = explode(';',$topbidders);
		$bidderscount = count($bidders);
		$highbidderinfo = explode(',',$bidders[$bidderscount-1]);
		$highbiddername = get_userdata(intval($highbidderinfo[0]));
		$highbidder = $highbiddername['user_id'];

		$fixedtitle = stripslashes($row['title']);

		$sql = "SELECT *
			FROM " . USERS_TABLE . "
			WHERE user_id = '$highbidder'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['shop_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
		}
		$owner_row = $db->sql_fetchrow($result);

		$privmsg_subject = 'Auction ' . $row['id'] . ': You have been outbid!';
		$message = '[b]Automated Message:[/b]
			You have been outbid on [url=' . $forumurl. 'shop_auctions.php?action=viewauction&id=' . $row[id] . ']Auction ID #' . $row[id] . '[/url] (' . $fixedtitle . ') by ' . $userdata['username'] . ', who has bid ' . $bidamt . $pointsname . '!';
		cash_pm($owner_row, $privmsg_subject, $message);
	}
	message_die(GENERAL_MESSAGE, 'You have successfully added your bid!' . $msg_add . '<br /><br />Click <a href="shop_auctions.php" class="gen">here</a> to return to the auction listing.<br />Click <a href="index.php" class="gen">here</a> to return to the index.');
}
//--------------------END BID NOW--------------------------//

//--------------------RETRACT BID--------------------------//
if ($action == 'retractbid')
{
	$sql = "SELECT *
		FROM " . AUCTIONS_TABLE . "
		WHERE id = '" . intval($auctionid) . "'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query auction info', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);

	if ( !($numrows = $db->sql_numrows($result)) ) { message_die(GENERAL_MESSAGE,"No such auction exists!"); }

	if ( $row['bidhistory'] == '' )
	{
		message_die(GENERAL_MESSAGE,"There are no bids on this auction.");
	}

	$bidinfo = explode(',',$bid);
	$newbidinfo = $bidinfo[0].','.$bidinfo[1].','.$bidinfo[2].',R1;';

	$urow = get_userdata($bidinfo[0]);

	if ( $bidinfo[0] != $userdata['user_id'] )
	{
		message_die(GENERAL_ERROR, "You can only request to retract your own bids!");
	}

	$newbidderlist = str_replace($bid.";", $newbidinfo, $row['bidhistory']);


	$sql2 = "UPDATE " . AUCTIONS_TABLE . "
		SET bidhistory = '$newbidderlist'
		WHERE id=$auctionid";
	if ( !($result2 = $db->sql_query($sql2)) )
	{
		message_die(GENERAL_ERROR, 'Could not update bidders info', '', __LINE__, __FILE__, $sql);
	}

	//SEND MSG TO SELLER TO REQUEST BID CANCELLATION!

	$sql = "SELECT *
		FROM " . USERS_TABLE . "
		WHERE user_id = '{$row['seller']}'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
	}
	$owner_row = $db->sql_fetchrow($result);	

	$privmsg_subject = 'Auction ' . $auctionid . ': Bid Retraction Request';
	$message = '[b]Automated Message:[/b]
Please cancel my bid on [url=' . $forumurl . 'shop_auctions.php?action=viewauction&id=' . $auctionid . ']Auction ID #' . $auctionid . '[/url], thank you.

If you want further details, please message me.';
	cash_pm($owner_row, $privmsg_subject, $message);

	message_die(GENERAL_MESSAGE, 'Your request for bid cancellation has been sent!<br /><br />Click <a href="shop_auctions.php" class="gen">here</a> to return to the auction listing.<br />Click <a href="index.php" class="gen">here</a> to return to the index.');
}
//--------------------END RETRACT BID--------------------------//

//--------------------CANCEL BID--------------------------//
if ($action == 'cancelbid')
{
	$sql = "SELECT *
		FROM " . AUCTIONS_TABLE . "
		WHERE id = '" . intval($auctionid) . "'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query auction info', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);

	if ( !($numrows = $db->sql_numrows($result)) ) { message_die(GENERAL_MESSAGE,"No such auction exists!"); }

	if ( $row['bidhistory'] == '')
	{
		message_die(GENERAL_MESSAGE,"There are no bids on this auction.");
	}

	if ( $row['seller'] != $userdata['user_id'] )
	{
		message_die(GENERAL_ERROR, "You are not authorised to cancel bids on this auction.");
	}

	$bidinfo = explode(',',$bid);
	if (($bidinfo[3] != 'R1') && ($bidinfo[3] != 'C1'))
	{
		message_die(GENERAL_ERROR, "No retraction request has been made for this bid.<BR>This bid was not placed by a non-paying bidder.");
	}

	$newbidinfo = $bidinfo[0].','.$bidinfo[1].','.$bidinfo[2].',0;';

	$newbidderlist = str_replace($bid.";", $newbidinfo, $row['bidhistory']);

	$topbidders = substr($newbidderlist,0,strpos($newbidderlist,'1;'));
	$bidders = explode(';',$topbidders);
	$bidderscount = count($bidders);
	$highbidderinfo = explode(',',$bidders[$bidderscount-1]);
	$currentprice = $highbidderinfo[1];
	$currentprice = ($currentprice == "") ? $row['startprice'] : $currentprice;


	$sql2 = "UPDATE " . AUCTIONS_TABLE . "
		SET bidhistory = '$newbidderlist',
			currentprice = '$currentprice'
		WHERE id = '$auctionid'";
	if( !($result2 = $db->sql_query($sql2)) )
	{
		message_die(GENERAL_ERROR, 'Could not update bidders info', '', __LINE__, __FILE__, $sql);
	}

	message_die(GENERAL_MESSAGE, 'This bid has successfully been cancelled!<br /><br />Click <a href="shop_auctions.php" class="gen">here</a> to return to the auction listing.<br />Click <a href="index.php" class="gen">here</a> to return to the index.');
}
//--------------------END CANCEL BID--------------------------//

//--------------------CLOSE AUCTION--------------------------//
if ($action == 'closeauction')
{
	$sql = "SELECT *
		FROM " . AUCTIONS_TABLE . "
		WHERE id = '" . intval($auctionid) . "'";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query auction info', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);

	if ( !($numrows = $db->sql_numrows($result)) ) {message_die(GENERAL_MESSAGE,"No such auction exists!");}

	if ($row[bidhistory] == "") {$highbidder = 'n/a';}
	if ($row[bids] == "0") {$highbidder = 'n/a';}
	if ($row[bidhistory] != "")
	{
		$topbidders = substr($row[bidhistory],0,strpos($row[bidhistory],'1;')+1); 
		$bidders = explode(';',$topbidders);
		$bidderscount = count($bidders);
		$highbidderinfo = explode(',',$bidders[$bidderscount-1]);
		$highbiddername = get_userdata(intval($highbidderinfo[0]));
		$highbidder = $highbiddername[username];
	}
	$topbidders = $highbidder = (($highbidder == "") || ($highbidder == "n/a") || ($highbidderinfo[3] == ""))  ? 'n/a' : $highbidder; 

	$currenttime = time();

	if ( $userdata['user_id'] != $row['seller'] )
	{
		message_die(GENERAL_MESSAGE,"You are not authorised to close this auction!");
	}

	if ( $row['endtime'] > $currenttime )
	{
		message_die(GENERAL_MESSAGE,"You can't close an auction which is not yet over, for free!");
	}

	if ( ($currenttime < ($row['endtime'] + 259200)) && ($highbidder != 'n/a') )
	{
		message_die(GENERAL_MESSAGE,"The winning bidder has 3 days from the end of the auction, in which to pay.<BR>You cannot close this auction now, but if they do not pay in time, you can.");
	}

	$item_list2 = "'" . str_replace(':', "', '", $row['items']) . "'";
	$sql = "UPDATE " . USER_ITEMS_TABLE . "
		SET worn = 0,
			user_id = '{$row['seller']}'
		WHERE worn = 4
			AND user_id = 0
			AND id IN (" . $item_list2 . ")";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not get items', '', __LINE__, __FILE__, $sql);
	}

	$sql2a = "UPDATE " . USERS_TABLE . "
		SET auctions_unpaid = auctions_unpaid + 1
		WHERE user_id = '{$highbiddername['user_id']}'";
	if( !($result2a = $db->sql_query($sql2a)) )
	{
		message_die(GENERAL_ERROR, 'Could not increment unpaid auctions!', '', __LINE__, __FILE__, $sql2a);
	}

	$sql3 = "DELETE FROM " . AUCTIONS_TABLE . "
		WHERE id = '$auctionid'";
	if( !($result3 = $db->sql_query($sql3)) )
	{
		message_die(GENERAL_ERROR, 'Could not delete auction info', '', __LINE__, __FILE__, $sql3);
	}

	message_die(GENERAL_MESSAGE, 'Auction has successfully been closed!<br /><br />Click <a href="shop_auctions.php" class="gen">here</a> to return to the auction listing.<br />Click <a href="index.php" class="gen">here</a> to return to the index.');
}
//--------------------END CLOSE AUCTION--------------------------//

//--------------------CLOSE EARLY--------------------------//
if ($action == 'closeearly')
{
	$sql = "SELECT *
		FROM " . AUCTIONS_TABLE . "
		WHERE id = '$auctionid'";
	if( !($result = $db->sql_query($sql)) )
	{message_die(GENERAL_ERROR, 'Could not query auction info', '', __LINE__, __FILE__, $sql);}
	$row = $db->sql_fetchrow($result);

	if ( !($numrows = $db->sql_numrows($result)) ) {message_die(GENERAL_MESSAGE,"No such auction exists!");}

	$topbidders = substr($row[bidhistory],0,strpos($row[bidhistory],'1;')+1); 
	$bidders = explode(';',$topbidders);
	$bidderscount = count($bidders);
	$highbidderinfo = explode(',',$bidders[$bidderscount-1]);
	$currentprice = $highbidderinfo[1];
	$currentprice = ($currentprice == "") ? $row[startprice] : $currentprice;
	$closefee = round($currentprice/10);
	if ( $row['bidhistory'] == '' ) { $closefee = 0; }

	$currenttime = time();

	if ( $userdata['user_id'] != $row['seller'] )
	{
		message_die(GENERAL_MESSAGE, 'You are not authorised to close this auction!');
	}

	if ( $row['endtime'] < $currenttime )
	{
		message_die(GENERAL_MESSAGE,'The auction is over, and can be closed without a fee.<p>Please go back and try again.');
	}

	if ( $userdata['user_points'] < $closefee )
	{
		message_die(GENERAL_MESSAGE, 'You cannot afford to close this auction!');
	}

	$item_list2 = "'" . str_replace(':', "', '", $row['items']) . "'";
	$sql = "UPDATE " . USER_ITEMS_TABLE . "
		SET worn = 0,
			user_id = '{$row['seller']}'
		WHERE worn = 4
			AND user_id = 0
			AND id IN (" . $item_list2 . ")";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not get items', '', __LINE__, __FILE__, $sql);
	}

	$sql2 = "UPDATE " . USERS_TABLE . "
		SET user_points = user_points - $closefee
		WHERE user_id = '{$row['seller']}'";
	if( !($result2 = $db->sql_query($sql2)) )
	{
		message_die(GENERAL_ERROR, 'Could not reinstate items to seller!', '', __LINE__, __FILE__, $sql2);
	}

	$sql3 = "DELETE FROM " . AUCTIONS_TABLE . "
		WHERE id = '" . intval($auctionid) . "'";
	if( !($result3 = $db->sql_query($sql3)) )
	{
		message_die(GENERAL_ERROR, 'Could not delete auction info', '', __LINE__, __FILE__, $sql3);
	}

	message_die(GENERAL_MESSAGE, 'You have successfully closed this auction early!<br /><br />Click <a href="shop_auctions.php" class="gen">here</a> to return to the auction listing.<br />Click <a href="index.php" class="gen">here</a> to return to the index.');
}
//--------------------END CLOSE EARLY--------------------------//

//--------------------PAY AUCTION--------------------------//
if ($action == 'payauction')
{
	$sql = "SELECT *
		FROM " . AUCTIONS_TABLE . "
		WHERE id = '$auctionid'";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query auction info', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);

	if ( !($numrows = $db->sql_numrows($result)) ) {message_die(GENERAL_MESSAGE,"No such auction exists!");}

	$sql2 = "SELECT *
		FROM " . USERS_TABLE . "
		WHERE user_id = '{$userdata['user_id']}'";
	if( !($result2 = $db->sql_query($sql2)) )
	{
		message_die(GENERAL_ERROR, 'Could not query user info', '', __LINE__, __FILE__, $sql2);
	}
	$row2 = $db->sql_fetchrow($result2);

	if ( $row['bidhistory'] == '' ) { $highbidder = 'n/a'; }
	if ( $row['bids'] == 0 ) { $highbidder = 'n/a'; }
	if ( $row['bidhistory'] != '' )
	{
		$topbidders = substr($row[bidhistory],0,strpos($row['bidhistory'],'1;')+1); 
		$bidders = explode(';',$topbidders);
		$bidderscount = count($bidders);
		$highbidderinfo = explode(',',$bidders[$bidderscount-1]);
		$highbiddername = get_userdata(intval($highbidderinfo[0]));
		$highbidder = $highbiddername['user_id'];
		$currentprice = $highbidderinfo[1];
	}
	$currenttime = time();

	if ( $userdata['user_id'] != $highbidder )
	{
		message_die(GENERAL_MESSAGE, 'You are not the winning bidder!');
	}

	if ( $row['endtime'] > $currenttime )
	{
		message_die(GENERAL_MESSAGE,'This auction isn\'t over yet!');
	}

	if ( $row2['user_points'] < $currentprice )
	{
		message_die(GENERAL_MESSAGE, 'You don\'t have enough Gold to pay!');
	}

	$sellername = get_userdata(intval($row[seller]));

	$item_list2 = "'" . str_replace(':', "', '", $row['items']) . "'";
	$sql = "UPDATE " . USER_ITEMS_TABLE . "
		SET worn = 0,
			user_id = '$highbidder'
		WHERE worn = 4
			AND user_id = 0
			AND id IN (" . $item_list2 . ")";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not get items', '', __LINE__, __FILE__, $sql);
	}

	$sql3 = "UPDATE " . USERS_TABLE . "
		SET user_points = user_points - $currentprice,
			auctions_paid = auctions_paid + 1
		WHERE user_id = '$highbidder'";
	if( !($result3 = $db->sql_query($sql3)) )
	{
		message_die(GENERAL_ERROR, 'Could not give items to winner!', '', __LINE__, __FILE__, $sql3);
	}

	$sql3a = "UPDATE " . USERS_TABLE . "
		SET user_points = user_points + $currentprice
		WHERE user_id = '{$row['seller']}'";
	if( !($result3a = $db->sql_query($sql3a)) )
	{
		message_die(GENERAL_ERROR, 'Could not add payment notice to seller!', '', __LINE__, __FILE__, $sql3a);
	}

	$sql4 = "DELETE FROM " . AUCTIONS_TABLE . "
		WHERE id = '$auctionid'";
	if( !($result4 = $db->sql_query($sql4)) )
	{
		message_die(GENERAL_ERROR, 'Could not delete auction info', '', __LINE__, __FILE__, $sql4);
	}

	$fixedtitle = str_replace("'", "\'", $row['title']); 

	$sql = "SELECT *
		FROM " . USERS_TABLE . "
		WHERE user_id = '{$row['seller']}'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
	}
	$owner_row = $db->sql_fetchrow($result);

	$privmsg_subject = 'Auction ' . $auctionid . ': Paid!';
	$message = '[b]Automated Message:[/b]
The winning bidder, ' . $userdata['username'] . ' has now paid for [url=' . $forumurl . $filename . '?action=viewauction&id=' . $auctionid . ']Auction ID #' . $auctionid . '[/url] (' . $fixedtitle . ').

The ' . $currentprice . $pointsname . ' has been transferred to your account, and the items to the buyer.

The auction has been automatically closed.';
	cash_pm($owner_row, $privmsg_subject, $message);

	$sql = "INSERT INTO " . TRANS_TABLE . "
		(user_id, type, action, value, timestamp, ip)
		VALUES('{$userdata['user_id']}', 'auctions', 'paid', '" . addslashes($fixedtitle) . " :: " . $row['items'] . " for " . $currentprice . "', '".time()."', '{$_SERVER['REMOTE_ADDR']}')";
	if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'Fatal Error with Transaction Code!'); }
	$sql = "INSERT INTO " . TRANS_TABLE . "
		(user_id, type, action, value, timestamp, ip)
		VALUES('{$row['seller']}', 'auctions', 'sold', '" . addslashes($fixedtitle) . " :: " . $row['items'] . " for " . $currentprice . "', '".time()."', '{$_SERVER['REMOTE_ADDR']}')";
	if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'Fatal Error with Transaction Code!'); }


	message_die(GENERAL_MESSAGE, 'You have successfully paid for the auction and recieved the item!<br /><br />Click <a href="shop_auctions.php" class="gen">here</a> to return to the auction listing.<br />Click <a href="index.php" class="gen">here</a> to return to the index.');
}
//--------------------END PAY AUCTION--------------------------//

//--------------------BUY NOW--------------------------//
if ($action == 'buynow')
{
	$sql = "SELECT *
		FROM " . AUCTIONS_TABLE . "
		WHERE id = '" . intval($auctionid) . "'";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query auction info', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);

	if ( !($numrows = $db->sql_numrows($result)) ) { message_die(GENERAL_MESSAGE,"No such auction exists!"); }


	$currenttime = time();

	if ( $row['currentprice'] > $row['buyprice'] )
	{
		message_die(GENERAL_MESSAGE, "'Buy Now' option no longer available.");
	}

	if ( $row['endtime'] < $currenttime )
	{
		message_die(GENERAL_MESSAGE, 'This auction is already over!');
	}

	if ( $row['buyprice'] == '0' )
	{
		message_die(GENERAL_MESSAGE,"This auction does not have a 'Buy Now' option.");
	}

	if ( $userdata['user_points'] < $row['buyprice'] )
	{
		message_die(GENERAL_MESSAGE,"You don't have enough Gold to pay!");
	}

	$sellername = get_userdata(intval($row[seller]));

	$item_list2 = "'" . str_replace(':', "', '", $row['items']) . "'";
	$sql = "UPDATE " . USER_ITEMS_TABLE . "
		SET worn = 0,
			user_id = '{$userdata['user_id']}'
		WHERE worn = 4
			AND user_id = 0
			AND id IN (" . $item_list2 . ")";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not get items', '', __LINE__, __FILE__, $sql);
	}

	$sql = "UPDATE " . USERS_TABLE . "
		SET user_points = user_points - {$row['buyprice']},
			auctions_paid = auctions_paid + 1
		WHERE user_id = '{$userdata['user_id']}'";
	if ( !( $db->sql_query($sql) ) )
	{
		message_die(GENERAL_ERROR, 'Could not give items to buyer!', '', __LINE__, __FILE__, $sql);
	}


	$sql = "UPDATE " . USERS_TABLE . "
		SET user_points = user_points + {$row['buyprice']}
		WHERE user_id = '{$row['seller']}'";
	if( !( $db->sql_query($sql) ) )
	{
		message_die(GENERAL_ERROR, 'Could not add payment notice to seller!', '', __LINE__, __FILE__, $sql);
	}

	$sql = "DELETE FROM " . AUCTIONS_TABLE . "
		WHERE id = '$auctionid'";
	if( !( $db->sql_query($sql) ) )
	{
		message_die(GENERAL_ERROR, 'Could not delete auction info', '', __LINE__, __FILE__, $sql);
	}

	$sql = "SELECT *
		FROM " . USERS_TABLE . "
		WHERE user_id = '{$row['seller']}'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
	}
	$owner_row = $db->sql_fetchrow($result);

	$privmsg_subject = 'Auction ' . $row['title'] . ': Your auction has been bought out!';
	$message = '[b]Automated Message:[/b]
The auction has been bought out, ID #' . $auctionid . ' (' . htmlspecialchars($row['title']) . ') by ' . $userdata['username'] . '.

The ' . $row['buyprice'] . $pointsname . ' has been transferred to your account, and the items to the buyer.

The auction has been automatically closed.';
	cash_pm($owner_row, $privmsg_subject, $message);

	$sql = "INSERT INTO " . TRANS_TABLE . "
		(user_id, type, action, value, timestamp, ip)
		VALUES('{$userdata['user_id']}', 'auctions', 'paid', '" . addslashes(stripslashes($row['title'])) . " :: " . $row['items'] . " for " . $row['buyprice'] . "', '".time()."', '{$_SERVER['REMOTE_ADDR']}')";
	if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'Fatal Error with Transaction Code!'); }
	$sql = "INSERT INTO " . TRANS_TABLE . "
		(user_id, type, action, value, timestamp, ip)
		VALUES('{$row['seller']}', 'auctions', 'sold', '" . addslashes(stripslashes($row['title'])) . " :: " . $row['items'] . " for " . $row['buyprice'] . "', '".time()."', '{$_SERVER['REMOTE_ADDR']}')";
	if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'Fatal Error with Transaction Code!'); }

	message_die(GENERAL_MESSAGE, 'You have successfully bought out the auction and have now recieved the item!<br /><br />Click <a href="shop_auctions.php" class="gen">here</a> to return to the auction listing.<br />Click <a href="index.php" class="gen">here</a> to return to the index.');

}
//--------------------END BUY NOW--------------------------//


//
// Start output of page
//
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

//
// Generate the page
//
$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>