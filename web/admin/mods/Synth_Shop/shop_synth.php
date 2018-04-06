<?php
/***************************************************************************
 *                              shop_synth.php
 *                            -------------------
 *   Version              : 1.0.0
 *   forums               : http://forums.zarath.com
 *   website		  : http://www.zarath.com
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   copyright (C) 2004  Zarath
 *
 *   This program is free software; you can redistribute it and/or
 *   modify it under the terms of the GNU General Public License
 *   as published by the Free Software Foundation; either version 2
 *   of the License, or (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   http://www.gnu.org/copyleft/gpl.html
 *
 ***************************************************************************/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//
//
// Start page Variables
//
if (!(@include($phpbb_root_path . 'language/lang_' . $userdata['user_lang'] . '/lang_shop.' . $phpEx))) { include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_shop.' . $phpEx); }

if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) ) { $action = ( isset($HTTP_POST_VARS['action']) ) ? $HTTP_POST_VARS['action'] : $HTTP_GET_VARS['action']; }
else { $action = ''; }

$lose_chance = '15'; // This variable is the chance of losing an item on desynthing. % number, so 15% of losing item default chance.
//
// End page variables
//

#
# Set User's items into a variable!
#
$user_items = '';

$sql = "SELECT *, count(item_name) as amount
	FROM " . USER_ITEMS_TABLE . "
	WHERE user_id='{$userdata['user_id']}'
		AND worn = 0
	GROUP BY `item_name`";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Error getting synth items!", '', __LINE__, __FILE__, $sql);
}
$sql_count = $db->sql_numrows($result);

for ( $i = 0; $i < $sql_count; $i++ )
{
	if (!( $row = $db->sql_fetchrow($result) ))
	{
		message_die(GENERAL_ERROR, "Error getting synth items!", '', __LINE__, __FILE__, $sql);
	}
	$item = $row['item_name'];
	$user_items[$item] = $row['amount'];
}

# Start of main shop
if ( empty($action) )
{
	$template->set_filenames(array(
		'body' => 'shop/shop_synth_body.tpl')
	);

	$sql = "SELECT *
		FROM " . SHOP_ITEMS_TABLE . "
		WHERE shop = 'Synthesize Shop'
			AND synth != ''
			ORDER BY `cost`, `name`";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Error getting synth items!", '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);
	if ( $sql_count )
	{
		for ($i = 0; $i < $sql_count; $i++)
		{
			if (!( $row = $db->sql_fetchrow($result) ))
			{
				message_die(GENERAL_ERROR, $lang['sshop_no_sitems'] . "<br />Error: " . mysql_error(), '', __LINE__, __FILE__, $sql);
			}
			$money = ( $row['cost'] <= $userdata['user_points'] ) ? 1 : 0;
			$synth = 1;

			# Find Requirements!
			$requires = explode(';', $row['synth']);
			$reqs = array();
			$req = '';
			$count = count($requires);
			for ( $ii = 0; $ii < $count; $ii++)
			{
				$reqs[$requires[$ii]]++;
			}
			$keys = array_keys($reqs);
			$count = count($keys);
			for ( $ii = 0; $ii < $count; $ii++ )
			{
				if ( $user_items[$keys[$ii]] >= $reqs[$keys[$ii]] )
				{
					$has_items = 1;
				}
				else
				{
					$has_items = 0;
					$synth = 0;
				}
				$req .= ( ( $has_items ) ? '<option>*' : '<option>' ) . '(' . $reqs[$keys[$ii]] . ') ' . $keys[$ii] . '</option>';

				#
				# Secondary check for synthable item list...
				#
				if ( $user_items[$keys[$ii]] && empty($items[$keys[$ii]]) )
				{
					$items[$keys[$ii]] = $user_items[$keys[$ii]];
				}
			}

			$rownum = ( $i % 2 ) ? "row1" : "row2";

			$name = ( $synth && $money ) ? '<a href="' . append_sid('shop_synth.php?action=synth&item_id=' . $row['id']) . '" title="' . $lang['sshop_synthesize'] . ' ' . $row['name'] . '" class="navsmall"><b>' . $row['name'] . '</b></a>' : $row['name'];

			if (file_exists("shop/images/".$row['name'].".jpg")) { $itemfilext = "jpg"; }
			elseif (file_exists("shop/images/".$row['name'].".png")) { $itemfilext = "png"; }
			else { $itemfilext = "gif"; }

			$decay_time .= ( $row['decay'] > 0 ) ? '<br /><i>*' . $lang['shop_will_decay'] . duration($row['decay']) . '!</i>' : '';

			if ( !($row['stock']) || ( $synth ) )
			{
				$template->assign_block_vars('synth_items', array(
					'ROW_CLASS' => $rownum,
					'ITEM_IMG' => 'shop/images/' . $row['name'] . '.' . $itemfilext,
					'ITEM_NAME' => $name,
					'ITEM_DESC' => $row['ldesc'],
					'ITEM_REQUIRES' => $req,
					'ITEM_DIE' => $decay_time,
					'ITEM_COST' => $row['cost'])
				);
			}
		}
	}

	if ( count($items) > 0 )
	{
		$template->assign_block_vars('switch_has_items', array());

		$keys = array_keys($items);
		$count = count($keys);
		for ( $i = 0; $i < $count; $i++ )
		{
			$template->assign_block_vars('list_items', array(
				'ITEM' => '(' . $items[$keys[$i]] . ') ' . $keys[$i])
			);
		}
	}
	else
	{
		$template->assign_block_vars('switch_no_items', array());
	}

	// Build desynthable list through switch list //
	$sql = "SELECT a.*
		FROM " . USER_ITEMS_TABLE . " a, " . SHOP_ITEMS_TABLE . " b
		WHERE a.user_id = '{$userdata['user_id']}'
			AND a.item_id = b.id
			AND b.maxstock = 1
			AND b.shop = 'Synthesize Shop'
			AND b.synth != ''";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, $lang['sshop_no_desynth'], '', __LINE__, __FILE__, $sql);
	}
	if ( $sql_count = $db->sql_numrows($result) )
	{
		for ( $i = 0; $i < $sql_count; $i++ )
		{
			if (!( $row = $db->sql_fetchrow($result) ))
			{
				message_die(GENERAL_ERROR, $lang['sshop_no_desynth'] . "<br />Error: " . mysql_error(), '', __LINE__, __FILE__, $sql);
			}

			$template->assign_block_vars('list_desynths', array(
				'ITEM_NAME' => $row['item_name'],
				'ITEM_ID' => $row['id']
			));
		}
	}
	if ( !($sql_count) )
	{
		$template->assign_block_vars('switch_no_desynth', array());
	}

	// End desynthable item list building //

	$sql = "SELECT *
		FROM " . SHOP_TABLE . "
		WHERE url = 'shop_synth.php'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Cannot find shop name!', '', __LINE__, __FILE__, $sql);
	}
	if (!( $row = $db->sql_fetchrow($result) ))
	{
		message_die(GENERAL_ERROR, 'Cannot find shop name!' . "<br />Error: " . mysql_error(), '', __LINE__, __FILE__, $sql);
	}

	$page_title = $row['shopname'];
	$title = $lang['sshop_avail_synths'];
	$shoplocation = ' -> <a href="' . append_sid("shop_synth.$phpEx") . '" class="nav">' . $page_title . '</a>';


	$template->assign_vars(array(
		'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']),

		'USER_POINTS' => $userdata['user_points'],
		'SHOPLOCATION' => $shoplocation,

		'L_PERSONAL_INFO' => $lang['shop_personal_info'],
		'L_POINTS_NAME' => $board_config['points_name'],
		'L_SHOP_TITLE' => $title,
		'L_INVENTORY' => $lang['shop_your_inv'],

		'L_IMAGE' => $lang['sshop_image'],
		'L_ITEM_NAME' => $lang['sshop_item_name'],
		'L_DESC' => $lang['sshop_description'],
		'L_REQUIRES' => $lang['sshop_requires'],
		'L_COST' => $lang['sshop_cost'],
		'L_SYNTH' => $lang['sshop_synth'],
		'L_DESYNTH' => $lang['sshop_but_desynth'],
		'L_NONE' => $lang['sshop_none'],

		'U_ACTION' => append_sid("shop_synth.$phpEx")
	));
	$template->assign_block_vars('', array());

}

# Synth Action
elseif ( $action == 'synth' )
{
	if ( isset($HTTP_GET_VARS['item_id']) || isset($HTTP_POST_VARS['item_id']) ) { $item_id = ( isset($HTTP_POST_VARS['item_id']) ) ? $HTTP_POST_VARS['item_id'] : $HTTP_GET_VARS['item_id']; }
	else { $item_id = ''; }

	if ( !$userdata['session_logged_in'] )
	{
		$redirect = 'shop_synth.'.$phpEx.'&action=synth&item_id=' . $item_id;
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}

	if ( !is_numeric($item_id) || $item_id < 0 ) { message_die(GENERAL_MESSAGE, $lang['sshop_invalid']); }

	$sql = "SELECT *
		FROM " . SHOP_ITEMS_TABLE . "
		WHERE id = '$item_id'
			AND shop = 'Synthesize Shop'";

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Error getting synth items!", '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);
	if ( $sql_count )
	{
		if (!( $row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, "Error getting synthesize item information", '', __LINE__, __FILE__, $sql);
		}

		# Make sure user has items required & gold
		if ( $row['cost'] > $userdata['user_points'] ) { $error .= $lang['sshop_no_gold'] . '<br />'; }

		# Find Requirements!
		$requires = explode(';', $row['synth']);
		$reqs = array();
		$sql = array();
		$count = count($requires);
		for ( $ii = 0; $ii < $count; $ii++)
		{
			$reqs[$requires[$ii]]++;
		}
		$keys = array_keys($reqs);
		$count = count($keys);
		for ( $ii = 0; $ii < $count; $ii++ )
		{
			if ( $user_items[$keys[$ii]] < $reqs[$keys[$ii]] )
			{
				$error .= $lang['sshop_missing_item'] . ' "' . $keys[$ii] . '".<br />';
			}
			else
			{
				# Remove synthed items
				$sql[] = "DELETE FROM " . USER_ITEMS_TABLE . "
					WHERE item_name = '" . addslashes($keys[$ii]) . "'
						AND user_id = '{$userdata['user_id']}'
					LIMIT " . $reqs[$keys[$ii]];

			}
			$req .= ( ( $has_items ) ? '<option>*' : '<option>' ) . '(' . $reqs[$keys[$ii]] . ') ' . $keys[$ii] . '</option>';
		}

		if ( $error )
		{
			$sql = '';
			message_die(GENERAL_MESSAGE, $error . sprintf($lang['sshop_return'], '<a href="' . append_sid('shop_synth.php') . '" class="nav">', '</a>'));
		}
		else
		{
			$decay_time = ( $row['decay'] ) ? (time() + $row['decay']) : 0;

			# SQL to add item!
			$sql[] = "INSERT INTO " . USER_ITEMS_TABLE . "
				(user_id, item_id, item_name, item_s_desc, item_l_desc, die_time)
				VALUES('{$userdata['user_id']}', '{$row['id']}', '" . addslashes($row['name']) . "', '" . addslashes($row['sdesc']) . "', '" . addslashes($row['ldesc']) . "', '$decay_time')";

			$sql[] = "INSERT INTO " . TRANS_TABLE  . "
				(user_id, type, action, value, timestamp, ip)
				values('{$userdata['user_id']}', 'shop', 'synthed', '" . addslashes($row['name']) . "', '".time()."', '{$_SERVER['REMOTE_ADDR']}')";

			$sql[] = "UPDATE " . USERS_TABLE . "
				SET user_points = user_points - {$row['cost']}
				WHERE user_id = '{$userdata['user_id']}'";


			$count = count($sql);
			for ( $i = 0; $i < $count; $i++ )
			{
				if ( !($db->sql_query($sql[$i])) )
				{
					message_die(GENERAL_ERROR, "Error running SQL query!", '', __LINE__, __FILE__, $sql);
				}
			}
		}

		message_die(GENERAL_MESSAGE, sprintf($lang['sshop_synth_item'], $row['name']) . '!<br /><br />' . sprintf($lang['sshop_return'], '<a href="' . append_sid("shop_synth.$phpEx") . '" class="nav">', '</a>'));
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['sshop_not_in_shop']);
	}
}

elseif ( $action == 'desynth' )
{
	if ( isset($HTTP_GET_VARS['item_id']) || isset($HTTP_POST_VARS['item_id']) ) { $item_id = ( isset($HTTP_POST_VARS['item_id']) ) ? $HTTP_POST_VARS['item_id'] : $HTTP_GET_VARS['item_id']; }
	else { $item_id = ''; }

	if ( !$userdata['session_logged_in'] )
	{
		$redirect = 'shop_synth.'.$phpEx.'&action=desynth&item_id=' . $item_id;
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}

	if ( !is_numeric($item_id) || $item_id < 0 ) { header("Location: shop_synth.php"); }

	$sql = "SELECT *
		FROM " . USER_ITEMS_TABLE . "
		WHERE id = '$item_id'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Error getting synth items!", '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);
	if ( $sql_count )
	{
		if (!( $row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, "Error getting user item information", '', __LINE__, __FILE__, $sql);
		}

		// Get synth item list from database //
		$sql = "SELECT *
			FROM " . SHOP_ITEMS_TABLE . "
			WHERE id = '{$row['item_id']}'
				AND shop = 'Synthesize Shop'
				AND synth != ''";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Error getting synth items!", '', __LINE__, __FILE__, $sql);
		}
		if (!( $item_row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, "Error getting synthesize item information", '', __LINE__, __FILE__, $sql);
		}

		if ( !($item_row['maxstock']) ) { message_die(GENERAL_MESSAGE, $lang['sshop_no_desynth']); }

		$items = explode(';', $item_row['synth']);
		$count = count($items);
		$new_items = '';

		// Loop through item list to readd items, with a 15% chance //
		for ( $i = 0; $i < $count; $i++ )
		{
			if ( rand(1, 100) > $lose_chance )
			{
				$sql = "SELECT *
					FROM " . SHOP_ITEMS_TABLE . "
					WHERE name = '$items[$i]'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, "Error getting synth items!", '', __LINE__, __FILE__, $sql);
				}

				if (!( $item_row = $db->sql_fetchrow($result) ))
				{
					message_die(GENERAL_ERROR, "Error getting synthesize item information", '', __LINE__, __FILE__, $sql);
				}

				$decay_time = ( $row['decay'] ) ? (time() + ($row['decay'] / 2)) : 0;
				$sql = "INSERT INTO " . USER_ITEMS_TABLE . "
					(user_id, item_id, item_name, item_s_desc, item_l_desc, die_time)
					VALUES('{$userdata['user_id']}', '{$item_row['id']}', '" . addslashes($item_row['name']) . "', '" . addslashes($item_row['sdesc']) . "', '" . addslashes($item_row['ldesc']) . "', '$decay_time')";
				if ( !($db->sql_query($sql)) )
				{
					message_die(GENERAL_MESSAGE, 'Fatal Error: '.mysql_error());
				}

				$new_items .= ( ( !empty($new_items) ) ? ', ' : '' ) . $item_row['name'];
			}
		}

		$sql = "DELETE FROM " . USER_ITEMS_TABLE . "
			WHERE user_id = '{$userdata['user_id']}'
				AND id = '$item_id'
			LIMIT 1";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_MESSAGE, 'Fatal Error: '.mysql_error());
		}

		$sql = "INSERT INTO " . TRANS_TABLE  . "
			(user_id, type, action, value, timestamp, ip)
			values('{$userdata['user_id']}', 'shop', 'de-synthed', '" . addslashes($row['item_name']) . "', '".time()."', '{$_SERVER['REMOTE_ADDR']}')";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_MESSAGE, 'Fatal Error: '.mysql_error());
		}

		message_die(GENERAL_MESSAGE, sprintf($lang['sshop_desynth'], $row['item_name'], $new_items) . '!<br />' . sprintf($lang['sshop_return'], '<a href="' . append_sid("shop_synth.$phpEx") . '" class="nav">', '</a>'));
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['sshop_dont_own']);
	}

}

else 
{
	message_die(GENERAL_MESSAGE, 'This is not a valid command!');
}

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