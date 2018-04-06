<?php
/***************************************************************************
 *                             scratch_card.php
 *                            ------------------------
 *   Version              : 1.0.3
 *   forums               : http://forums.zarath.com
 *   website		  : http://www.zarath.com
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   copyright (C) 2007  Zarath Technologies
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

$i = 0;
while ( !file_exists($phpbb_root_path . 'extension.inc') && ($i++ < 4) )
{
	$phpbb_root_path .= '../';
}
if ( $i > 4 )
{
	message_die(CRITICAL_ERROR, 'Unable to find extension.inc, terminating. Please move this file into your main/"root" phpbb directory and try again.'); 
}

include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

$gen_simple_header = TRUE;

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

#*                         *#
#* Scratch Card Variables! *#
#*                         *#
// Percentages of winning or losing //
// Default: 40, 25, 15, 10, 7, 3 -- 50% chance of loss, 20% chance of recieving cost back, 30% chance of making money //
// Note: These should add up to 100... //
$item_chance = array(55, 20, 15, 5, 2, 1);

// Amount of points/gold etc to win on each item //
// Default: 0, 10, 15, 20, 30, 50 -- 0 for loss, 10 for 25% win, etc //
$item_win = array(0, 25, 30, 50, 100, 250);

// Item Name //
$item_name = 'Scratch Card';

//
// Decay Time?
// Set this variable to 0 for none... something else if you want it to decay after it's been "initialized"
// Default of a week.
//
$decay_time = 604800;

#*                             *#
#* End Scratch Card Variables! *#
#*                             *#

//
// See if user has a scratch card...
//
$sql = "SELECT *
	FROM " . USER_ITEMS_TABLE . "
	WHERE user_id = {$userdata['user_id']}
		AND item_name = '" . str_replace("'", "''", $item_name) . "'";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Error getting scratch card info!", '', __LINE__, __FILE__, $sql);
}
if ( !($db->sql_numrows($result)) )
{
	message_die(GENERAL_MESSAGE, $lang['scratch_no_card']);
}
else
{
	$row = $db->sql_fetchrow($result);
}

// Generate Scratch Card if it hasn't been done yet... //
if ( empty($row['scratch_card']) )
{
	// Force Seed for random, for older PHP versions... //
	list($usec, $sec) = explode(' ', microtime());
	$seed = (float) $sec + ((float) $usec * 100000);
	srand($seed);
	// End seeding for Random //

	$rand = rand(1, 100);
	$check = $item_chance[0];
	$i = 0;

	while ( 1 )
	{
		if ( $rand <= $check )
		{
			$win = $i;
			break;
		}
		else
		{
			$i++;
			$check += $item_chance[$i];
		}
	}
	$win = ( $win > 5 || $win < 0 ) ? 0 : $win;

	// Generate icon listing -- don't modify this unless you know what you're doing...
	$fields = array();
	$fields_added = array();

	while ( count($fields) < 9 )
	{
		if ( count($fields) < 3 && $win )
		{
			$fields[] = $win . ':0';
			continue;
		}

		$rand = rand(1, 5);
		// Check if field has been added twice or the winning number
		if ( $fields_added[$rand] == 2 || $rand == $win )
		{
			continue;
		}
		else
		{
			$fields[] = $rand . ':0';
			$fields_added[$rand]++;
		}
	}
	shuffle($fields);
	$fields = implode('|', $fields);

	//
	// Generation complete... add it to the scratch card info!
	//
	$decay_time = ( !($decay_time) ) ? 0 : (time() + $decay_time);

	$sql = "UPDATE " . USER_ITEMS_TABLE . "
		SET scratch_card = '$fields',
			die_time = $decay_time
		WHERE user_id = {$userdata['user_id']}
			AND id = {$row['id']}";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Error updating scratch card fields!", '', __LINE__, __FILE__, $sql);
	}

	$row['scratch_card'] = $fields;
}


#
# Set User's items into a variable!
#
# Start of main display
if ( empty($action) )
{
	$template->set_filenames(array(
		'body' => 'shop/item_scratch_card.tpl')
	);

	$fields = explode('|', $row['scratch_card']);
	$fields_scratched = array();
	$revealed = 0;
	$won = 0;

	// Positions for scratch panel //
	$x = array(165, 207, 249, 165, 207, 249, 165, 207, 249);
	$y = array(27, 27, 27, 69, 69, 69, 111, 111, 111);

	if ( substr_count($_SERVER['HTTP_USER_AGENT'], 'MSIE') )
	{
		for ( $i = 0; $i < count($x); $i++ )
		{
			$x[$i] += 3;
			$y[$i] += 6;
		}
	}


	for ( $i = 0; $i < count($fields); $i++ )
	{
		if ( $fields[$i]{2} )
		{
			$fields_scratched[$fields[$i]{0}]++;

			if ( $fields_scratched[$fields[$i]{0}] == 3 )
			{
				$won = $fields[$i]{0};
			}

			$template->assign_block_vars('list_layers', array(
				'U_ACTION' => append_sid("scratch_card.$phpEx?action=scratch&amp;field_id=" . $i),

				'ICON_ID' => $fields[$i]{0},
				'SQUARE_ID' => $i,

				'POS_X' => $x[$i],
				'POS_Y' => $y[$i]
			));

			$revealed++;
		}
		else
		{
			$template->assign_block_vars('list_empty', array(
				'U_ACTION' => append_sid("scratch_card.$phpEx?action=scratch&amp;field_id=" . $i),

				'POS_X' => $x[$i],
				'POS_Y' => $y[$i]
			));
		}
	}

	if ( $won || $revealed > 8 )
	{
		$message = ( $won ) ? $lang['scratch_you_won'] . '<br />' . sprintf($lang['scratch_claim'], $item_win[$won], $board_config['points_name']) : $lang['scratch_no_win'] . '<br />' . $lang['scratch_discard'];

		$template->assign_block_vars('switch_trade_in', array(
			'U_ACTION' => append_sid("scratch_card.$phpEx?action=exchange"),

			'X_POS' => ( ( substr_count($_SERVER['HTTP_USER_AGENT'], 'MSIE') ) ? 165 : 162 ),
			'Y_POS' => ( ( substr_count($_SERVER['HTTP_USER_AGENT'], 'MSIE') ) ? 167 : 162 ),

			'MESSAGE' => $message
		));
	}

	for ( $i = 1; $i < 6; $i++ )
	{
		$template->assign_block_vars('list_icons', array(
			'Y_POS' => (( ( substr_count($_SERVER['HTTP_USER_AGENT'], 'MSIE') ) ? 106 : 103 ) + (($i - 1) * 18)),

			'ICON_ID' => $i,
			'PAYOUT' => $item_win[$i]
		));
	}


	$page_title = $item_name;

	$template->assign_vars(array(
		'L_POINTS_NAME' => $board_config['points_name'],

		'U_ACTION' => append_sid("scratch_card.$phpEx")
	));
	$template->assign_block_vars('', array());

}

elseif ( $action == 'scratch' )
{
	if ( isset($HTTP_GET_VARS['field_id']) || isset($HTTP_POST_VARS['field_id']) ) { $field_id = ( isset($HTTP_POST_VARS['field_id']) ) ? intval($HTTP_POST_VARS['field_id']) : intval($HTTP_GET_VARS['field_id']); }
	else { $field_id = ''; }

	// Update the scratched field //
	$fields = explode('|', $row['scratch_card']);
	if ( !($fields[$field_id]{2}) )
	{
		$fields[$field_id] = $fields[$field_id]{0} . ':1';
	}
	else
	{
		redirect(append_sid("shop/scratch_card.$phpEx", true));
	}
	$new_fields = implode('|', $fields);

	$sql = "UPDATE " . USER_ITEMS_TABLE . "
		SET scratch_card = '$new_fields'
		WHERE user_id = {$userdata['user_id']}
			AND id = {$row['id']}";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Error updating scratch ticket for scratch!", '', __LINE__, __FILE__, $sql);
	}

	redirect(append_sid("shop/scratch_card.$phpEx", true));
}

elseif ( $action == 'exchange' )
{
	$fields = explode('|', $row['scratch_card']);
	$fields_scratched = array();
	$not_revealed = 0;
	$won = 0;
	for ( $i = 0; $i < count($fields); $i++ )
	{
		if ( !($fields[$i]{2}) )
		{
			$not_revealed++;
		}
		elseif ( $fields[$i]{2} )
		{
			$fields_scratched[$fields[$i]{0}]++;

			if ( $fields_scratched[$fields[$i]{0}] == 3 )
			{
				$won = $fields[$i]{0};
			}
		}
	}

	if ( $won )
	{
		// Delete scratch card first //
		$sql = "DELETE FROM " . USER_ITEMS_TABLE . "
			WHERE user_id = {$userdata['user_id']}
				AND id = {$row['id']}
			LIMIT 1";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Error removing scratch card!", '', __LINE__, __FILE__, $sql);
		}

		// Add the amount the user has won //
		$sql = "UPDATE " . USERS_TABLE . "
			SET user_points = (user_points + $item_win[$won])
			WHERE user_id = {$userdata['user_id']}";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Error adding points!", '', __LINE__, __FILE__, $sql);
		}

		message_die(GENERAL_MESSAGE, $lang['scratch_you_won'] . '<br />' . sprintf($lang['scratch_awarded_points'], $item_name, $item_win[$won], $board_config['points_name']) . '<br /><br />' . sprintf($lang['scratch_close_window'], '<a href="javascript: window.close();" class="gen">', '</a>'));
	}
	elseif ( $not_revealed )
	{
		redirect(append_sid("shop/scratch_card.$phpEx", true));
	}
	else
	{
		// Delete scratch card first //
		$sql = "DELETE FROM " . USER_ITEMS_TABLE . "
			WHERE user_id = {$userdata['user_id']}
				AND id = {$row['id']}
			LIMIT 1";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Error removing scratch card!", '', __LINE__, __FILE__, $sql);
		}

		message_die(GENERAL_MESSAGE, $lang['scratch_no_win'] . '<br /><br />' . sprintf($lang['scratch_close_window'], '<a href="javascript: window.close();" class="gen">', '</a>'));
	}
}

else 
{
	message_die(GENERAL_MESSAGE, $lang['scratch_no_action']);
}

//
// Start output of page
//
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

//
// Generate the page
//
$template->pparse('body');

exit;
?>