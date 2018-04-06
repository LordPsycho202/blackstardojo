<?php
 //require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                             admin_abilities.php
 *                            -------------------
 *   Version              : 1.0.0
 *   website              : http://www.blackstardojo.org
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   copyright (C) 2007  Blackstar RP Dojo
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

define('IN_PHPBB', 1);

if(	!empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['General']['Abilities'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = '../';
require($phpbb_root_path . 'extension.inc');
require('pagestart.' . $phpEx);

//
//check for userlevel
//
if( !$userdata['session_logged_in'] )
{
	header('Location: ' . append_sid("login.$phpEx?redirect=admin_shop.$phpEx", true));
}

if( $userdata['user_level'] != ADMIN )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}
if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) ) { $action = ( isset($HTTP_POST_VARS['action']) ) ? $HTTP_POST_VARS['action'] : $HTTP_GET_VARS['action']; }
else { $action = ''; }
//end check

//abilities pages
//main page
if ( empty($action) )
{
	$template->set_filenames(array(
		'body' => 'admin/abilities_main_body.tpl')
	);

	# Get a list of abilities!
	$sql = "SELECT * FROM ".ABILITIES_TABLE;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf('There was an error requesting abilities Data'), '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	for ( $i = 0; $i < $sql_num_rows; $i++ )
	{
		$row = $db->sql_fetchrow($result);
		

		$sql = "SELECT `name`
			FROM " . SHOP_ITEMS_TABLE . "
			WHERE  `id` = ".$row['trade_item'];

		if ( !($result2 = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf('There was an error requesting abilities data'), '', __LINE__, __FILE__, $sql);
		}

		$trade = $db->sql_fetchrow($result2);		


		if ($row['pre1'] != '-1')
		{
			$sql = "SELECT `name` FROM " . ABILITIES_TABLE." WHERE `id` = '".$row['pre1']."'";

			if ( !($result2 = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf('There was an error requesting abilities data'), '', __LINE__, __FILE__, $sql);
			}
			$pre1 = $db->sql_fetchrow($result2);
			$pre1d=$pre1['name'];
		}
		else
		{
			$pre1d = 'None';
		}
		

		if ($row['pre2'] != '-1')
		{
			$sql = 'SELECT `name` FROM '.ABILITIES_TABLE .' WHERE `id` = '.$row['pre2'];

			if ( !($result2 = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf('There was an error requesting abilities data'), '', __LINE__, __FILE__, $sql);
			}
			$pre2 = $db->sql_fetchrow($result2);
			$pre2d = $pre2['name'];
		}
		else
		{
			$pre2d = 'None';
		}
						
		$template->assign_block_vars('list_abilities', array(
			'id' => $row['id'],
			'name' => $row['name'],
			'start' => $row['start'],
			'max' => $row['max'],
			'inc' => $row['inc'],
			'pre1' => $pre1d,
			'pre2' => $pre2d,
			'trade' => $trade['name'],
			"U_ABILITY_EDIT" => append_sid("admin_abilities.$phpEx?action=edit&amp;id=" . $row['id']),
			"U_ABILITY_DELETE" => append_sid("admin_abilities.$phpEx?action=delete&amp;id=" . $row['id'])
		));

		
	}

	$off = '';
	$admin = '';
	$on = '';

	switch($board_config['abilities_enabled'])
	{
		case 2:
			$on = "SELECTED";
			break;
		case 1:
			$admin = "SELECTED";
			break;
		default:
			$off = "SELECTED";
			break;
	}

	$template->assign_vars(array(
		'OFF' => $off,
		'ADMIN' => $admin,
		'ON' => $on,
		'S_CONFIG_ACTION' => append_sid('admin_abilities.' . $phpEx),
		'L_UPDATE' => 'Update'

	));
}

//change global settings
elseif ( $action == 'updatestatus' )
{

	if ( isset($HTTP_GET_VARS['status']) || isset($HTTP_POST_VARS['status']) ) { $status = ( isset($HTTP_POST_VARS['status']) ) ? intval($HTTP_POST_VARS['status']) : intval($HTTP_GET_VARS['status']); }
	else { $status = '-1'; }


	if ( $board_config[abilities_enabled] != $status )
	{
		$gsql = "UPDATE " . CONFIG_TABLE . "
			SET `config_value` = '$status'
			WHERE `config_name` = 'abilities_enabled'";
		if ( !($result = $db->sql_query($gsql)) ) { message_die(CRITICAL_ERROR, 'ERROR: Getting Global Variables!'); }
	}

	$message = 'Abilities Status updated<br /><br /><a href="' . append_sid("admin_abilities.".$phpEx) . '">Return to abilities page</a><br /><br /><a href="' . append_sid("index.$phpEx?pane=right") .'">Return to Admin Index</a>';
	message_die(GENERAL_MESSAGE, $message);

}

elseif ( $action == 'new' )
{
	$template->set_filenames(array(
		'body' => 'admin/abilities_edit_body.tpl')
	);

	//parse template variables
	$template->assign_vars(array(
		'S_CONFIG_ACTION' => append_sid('admin_abilities.' . $phpEx),
		'ACTION' => 'add',

		'L_ABILITYTITLE' => 'Create New Ability',
		'L_ABILITY_NAME' => 'Ability Name',
		'L_ABILITY_DESC' => 'Ability Description',
		'L_START' => 'Start Accuracy Level',
		'L_MAX' => 'Max Accuracy Level',
		'L_MASTER' => 'Master Accuracy level',
		'L_INC' => 'Accuracy Increment per successful use',
		'L_PREREQ1' => 'Prerequesite Skill 1',
		'L_PREREQ2' => 'Prerequesite Skill 2',
		'L_TRADE' => 'Item to Trade for Skill', 
		'L_UPDATE_ABILITY' => 'Create Ability',
		'L_PASS' => 'Success text',
		'L_FAIL' => 'Fail Text',
		
		'NSELECT' => 'SELECTED'
	));

	$sql = "SELECT `name`, `id` FROM ".ABILITIES_TABLE;

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf('There was an error requesting abilities Data'), '', __LINE__, __FILE__, $sql);
	}
	
	while ($row = $db->sql_fetchrow($result))
	{
		$template->assign_block_vars('list_pre1', array(
			'id' => $row['id'],
			'name' => $row['name'],
			'select' => ''
		));

		$template->assign_block_vars('list_pre2', array(
			'id' => $row['id'],
			'name' => $row['name'],
			'select' => ''
		));
	}

		$sql = "SELECT `id`, `name`
		FROM " . SHOP_ITEMS_TABLE . "
		ORDER BY `name`";
	if ( !($result2 = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'Fatal Error Getting All Items!');
	}

	$count = $db->sql_numrows($result2);
  	for ($i = 0; $i < $count; $i++)
  	{
		$row = $db->sql_fetchrow($result2);

		$template->assign_block_vars('list_items', array(
			'id' => $row['id'],
			'name' => $row['name'],
			'select' => '')
		);
	}
}

elseif ( $action == 'edit' )
{

	if ( isset($HTTP_GET_VARS['id']) || isset($HTTP_POST_VARS['id']) ) { $id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']); }
	else { $id = ''; }

	$sql = "SELECT * FROM ".ABILITIES_TABLE ." WHERE `id` = '$id'";
	
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf('There was an error requesting abilities Data'), '', __LINE__, __FILE__, $sql);
	}

	$ability = $db->sql_fetchrow($result);

	$template->set_filenames(array(
		'body' => 'admin/abilities_edit_body.tpl')
	);

	//parse template variables
	$template->assign_vars(array(
		'S_CONFIG_ACTION' => append_sid('admin_abilities.' . $phpEx),
		'ACTION' => 'update',
		'ABILITY_ID' => '<input type="hidden" name="aid" value="'.$id.'">',

		'L_ABILITYTITLE' => 'Create New Ability',
		'L_ABILITY_NAME' => 'Ability Name',
		'L_ABILITY_DESC' => 'Ability Description',
		'L_START' => 'Start Accuracy Level',
		'L_MAX' => 'Max Accuracy Level',
		'L_MASTER' => 'Master Accuracy level',
		'L_INC' => 'Accuracy Increment per successful use',
		'L_PREREQ1' => 'Prerequesite Skill 1',
		'L_PREREQ2' => 'Prerequesite Skill 2', 
		'L_TRADE' => 'Item to trade for skill',
		'L_UPDATE_ABILITY' => 'Edit Ability',
		'L_PASS' => 'Success text',
		'L_FAIL' => 'Fail Text',
		
		'NAME' => $ability['name'],
		'DESC' => $ability['desc'],
		'START' => $ability['start'],
		'MAX' => $ability['max'],
		'MASTER' => $ability['master'],
		'INC' => $ability['inc'],
		'PASS' => $ability['pass'],
		'FAIL' => $ability['fail']
	));

	$zql = "SELECT `name`, `id` FROM ".ABILITIES_TABLE;

	if ( !($rezult = $db->sql_query($zql)) )
	{
		message_die(GENERAL_ERROR, sprintf('There was an error requesting abilities Data'), '', __LINE__, __FILE__, $zql);
	}
	
	while ($zow = $db->sql_fetchrow($rezult))
	{
		$select = '';
		if ($ability['pre1'] = $zow['id'])
		{
			$select = 'SELECTED';
		}
		$template->assign_block_vars('list_pre1', array(
			'id' => $zow['id'],
			'name' => $zow['name'],
			'select' => ''
		));
		
		$select = '';
		if ($ability['pre2'] = $zow['id'])
		{
			$select = 'SELECTED';
		}
		$template->assign_block_vars('list_pre2', array(
			'id' => $zow['id'],
			'name' => $zow['name'],
			'select' => ''
		));
	}

	$sql = "SELECT `id`, `name`
		FROM " . SHOP_ITEMS_TABLE . "
		ORDER BY `name`";
	if ( !($result2 = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'Fatal Error Getting All Items!');
	}

	$count = $db->sql_numrows($result2);
  	for ($i = 0; $i < $count; $i++)
  	{
		$row = $db->sql_fetchrow($result2);
		$select = '';
		if ($ability['trade_item'] = $row['id'])
		{
			$select = 'SELECTED';
		}

		$template->assign_block_vars('list_items', array(
			'id' => $row['id'],
			'name' => $row['name'],
			'select' => $select)
		);
	}
}
elseif ( $action == 'add' || $action == 'update' )
{
	// Register Variables!
	if ( isset($HTTP_GET_VARS['name']) || isset($HTTP_POST_VARS['name']) ) { $name = ( isset($HTTP_POST_VARS['name']) ) ? $HTTP_POST_VARS['name'] : $HTTP_GET_VARS['name']; }
	else { $name = ''; }

	if ( isset($HTTP_GET_VARS['desc']) || isset($HTTP_POST_VARS['desc']) ) { $desc = ( isset($HTTP_POST_VARS['desc']) ) ? $HTTP_POST_VARS['desc'] : $HTTP_GET_VARS['desc']; }
	else { $desc = ''; }

	if ( isset($HTTP_GET_VARS['start']) || isset($HTTP_POST_VARS['start']) ) { $start = ( isset($HTTP_POST_VARS['start']) ) ? intval($HTTP_POST_VARS['start']) : intval($HTTP_GET_VARS['start']); }
	else { $start = '25'; }

	if ( isset($HTTP_GET_VARS['max']) || isset($HTTP_POST_VARS['max']) ) { $max = ( isset($HTTP_POST_VARS['max']) ) ? intval($HTTP_POST_VARS['max']) : intval($HTTP_GET_VARS['max']); }
	else { $max = '75'; }

	if ( isset($HTTP_GET_VARS['master']) || isset($HTTP_POST_VARS['master']) ) { $master = ( isset($HTTP_POST_VARS['master']) ) ? intval($HTTP_POST_VARS['master']) : intval($HTTP_GET_VARS['master']); }
	else { $master = '65'; }

	if ( isset($HTTP_GET_VARS['inc']) || isset($HTTP_POST_VARS['inc']) ) { $inc = ( isset($HTTP_POST_VARS['inc']) ) ? intval($HTTP_POST_VARS['inc']) : intval($HTTP_GET_VARS['inc']); }
	else { $inc = '5'; }

	if ( isset($HTTP_GET_VARS['pre1']) || isset($HTTP_POST_VARS['pre1']) ) { $pre1 = ( isset($HTTP_POST_VARS['pre1']) ) ? intval($HTTP_POST_VARS['pre1']) : intval($HTTP_GET_VARS['pre1']); }
	else { $pre1 = '-1'; }

	if ( isset($HTTP_GET_VARS['pre2']) || isset($HTTP_POST_VARS['pre2']) ) { $pre2 = ( isset($HTTP_POST_VARS['pre2']) ) ? intval($HTTP_POST_VARS['pre2']) : intval($HTTP_GET_VARS['pre2']); }
	else { $pre2 = '-1'; }

	if ( isset($HTTP_GET_VARS['trade']) || isset($HTTP_POST_VARS['trade']) ) { $trade = ( isset($HTTP_POST_VARS['trade']) ) ? intval($HTTP_POST_VARS['trade']) : intval($HTTP_GET_VARS['trade']); }
	else { $trade = '-1'; }

	if (isset($HTTP_GET_VARS['pass']) || isset($HTTP_POST_VARS['pass']) ) { $pass = (isset($HTTP_POST_VARS['pass']) ) ? $HTTP_POST_VARS['pass'] : $HTTP_GET_VARS['pass']; }
	else { $pass = '';}

	if (isset($HTTP_GET_VARS['fail']) || isset($HTTP_POST_VARS['fail']) ) { $fail = (isset($HTTP_POST_VARS['fail']) ) ? $HTTP_POST_VARS['fail'] : $HTTP_GET_VARS['fail']; }
	else { $fail = '';}


	if ( $action == 'add' )
	{
		
		$sql = "INSERT INTO " . ABILITIES_TABLE . "
			(`name`, `desc`, `start`, `max`, `master`, `inc`, `pre1`, `pre2`, `trade_item`,`pass`,`fail`)
			VALUES('" . str_replace("\'", "''", $name) . "',  '" . str_replace("\'", "''", $desc) . "', $start, $max, $master, $inc, $pre1, $pre2, $trade, '".str_replace("\'", "''", $pass)."', '".str_replace("\'", "''", $fail)."')";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_MESSAGE, 'SQL Error adding item to shops!<br>'.$sql);
		}

		$message = 'Created Ability '. $name . '<br /><br />' . '<a href="' . append_sid("admin_abilities.".$phpEx) . '">Return to Abilities page</a>' . '<br /><br />' . '<a href="' . append_sid("index.$phpEx?pane=right") .'">Return to Admin Index</a>';
		message_die(GENERAL_MESSAGE, $message);
	}
	elseif ( $action == 'update' )
	{
		if ( isset($HTTP_GET_VARS['aid']) || isset($HTTP_POST_VARS['aid']) ) { $aid = ( isset($HTTP_POST_VARS['aid']) ) ? intval($HTTP_POST_VARS['aid']) : intval($HTTP_GET_VARS['aid']); }
		else { $aid = '-1'; }

		# Update User abilities
  		$sql = "UPDATE " .  ABILITIES_USER_TABLE . "
			SET `name` = '" . str_replace("\'", "''", $name) . "'
			where aid = $aid";
  		if ( !($db->sql_query($sql)) )
  		{
  			message_die(GENERAL_MESSAGE, 'Fatal Error: ' . $sql);
  		}
  		$sql = "UPDATE " .  ABILITIES_USER_TABLE . "
			SET `desc` = '" . str_replace("\'", "''", $desc) . "'
			where aid = $aid";
  		if ( !($db->sql_query($sql)) )
  		{
  			message_die(GENERAL_MESSAGE, 'Fatal Error: ' . $sql);
  		}
  		$sql = "UPDATE " .  ABILITIES_USER_TABLE . "
			SET `max` = $max
			where aid=$aid";
  		if ( !($db->sql_query($sql)) )
  		{
  			message_die(GENERAL_MESSAGE, 'Fatal Error: ' . $sql);
  		}
		$sql = "UPDATE " .  ABILITIES_USER_TABLE . "
			SET `master` = $master
			where aid=$aid";
  		if ( !($db->sql_query($sql)) )
  		{
  			message_die(GENERAL_MESSAGE, 'Fatal Error: ' . $sql);
  		}

		$sql = "UPDATE " .  ABILITIES_USER_TABLE . "
			SET `start` = $start
			where aid=$aid";
  		if ( !($db->sql_query($sql)) )
  		{
  			message_die(GENERAL_MESSAGE, 'Fatal Error: ' . $sql);
  		}
		$sql = "UPDATE " .  ABILITIES_USER_TABLE . "
			SET `inc` = $inc
			where aid=$aid";
  		if ( !($db->sql_query($sql)) )
  		{
  			message_die(GENERAL_MESSAGE, 'Fatal Error: ' . $sql);
  		}
		$sql = "UPDATE " .  ABILITIES_USER_TABLE . "
			SET `pre1` = $pre1
			where aid=$aid";
  		if ( !($db->sql_query($sql)) )
  		{
  			message_die(GENERAL_MESSAGE, 'Fatal Error: ' . $sql);
  		}
		$sql = "UPDATE " .  ABILITIES_USER_TABLE . "
			SET `pre2` = $pre2
			where aid=$aid";
  		if ( !($db->sql_query($sql)) )
  		{
  			message_die(GENERAL_MESSAGE, 'Fatal Error: ' . $sql);
  		}
		$sql = "UPDATE " .  ABILITIES_USER_TABLE . "
			SET `trade_item` = $trade
			where aid=$aid";
  		if ( !($db->sql_query($sql)) )
  		{
  			message_die(GENERAL_MESSAGE, 'Fatal Error: ' . $sql);
  		}

		$sql = "UPDATE " .  ABILITIES_USER_TABLE . "
			SET `pass` = '".str_replace("\'", "''", $pass)."'
			where aid=$aid";
  		if ( !($db->sql_query($sql)) )
  		{
  			message_die(GENERAL_MESSAGE, 'Fatal Error: ' . $sql);
  		}
		$sql = "UPDATE " .  ABILITIES_USER_TABLE . "
			SET `fail` = '".str_replace("\'", "''", $fail)."'
			where aid=$aid";
  		if ( !($db->sql_query($sql)) )
  		{
  			message_die(GENERAL_MESSAGE, 'Fatal Error: ' . $sql);
  		}

		$sql = "UPDATE " . ABILITIES_TABLE . " 
			SET name = '" . str_replace("\'", "''", $name) . "', 
				`desc` = '" . str_replace("\'", "''", $desc) . "', 
				`max` = $max, 
				`master` = $master, 
				`start` = $start, 
				`inc` = $inc,
				`pre1` = $pre1,
				`pre2` = $pre2,
				`trade_item` = $trade,
				`pass` = '".str_replace("\'", "''", $pass)."',
				`fail` = '".str_replace("\'", "''", $fail)."' 
			WHERE `id` = $aid";
		if ( !$db->sql_query($sql) )
  		{
  			message_die(GENERAL_MESSAGE, 'Fatal Error: ' . $sql);
  		}


		$message = 'Ability '.$name.' updated' . '<br /><br />' .  '<a href="' . append_sid("admin_abilities.".$phpEx) . '">Return to Abilities page</a>' . '<br /><br />' . '<a href="' . append_sid("index.$phpEx?pane=right") .'">Return to Admin Index</a>';
		message_die(GENERAL_MESSAGE, $message);
	}
}

//delete pages
elseif ( $action == 'delete' )
{
	if ( isset($HTTP_GET_VARS['id']) || isset($HTTP_POST_VARS['id']) ) { $id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']); }
	else { $id = ''; }

	
	$sql = "DELETE FROM " . ABILITIES_USER_TABLE . "
		WHERE aid=$id";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'SQL Error in trying to delete items from shop!');
	}

	$sql = "DELETE FROM " . ABILITIES_TABLE . "
		WHERE id = $id";
	if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error in trying to delete the shop!'); }

	
	$message = "Ability Baleeted!" . '<br /><br />' .  '<a href="' . append_sid("admin_abilities.".$phpEx) . '">Return to Abilities page</a>' . '<br /><br />' . '<a href="' . append_sid("index.$phpEx?pane=right") .'">Return to Admin Index</a>';
	message_die(GENERAL_MESSAGE, $message);
}

else 
{ 
	$message = $lang['invalid_command'].", ". $action."blah"; 
	message_die(GENERAL_MESSAGE, $message ); 
}

//
// Generate the page
//
$template->pparse('body');

include('page_footer_admin.' . $phpEx);


?>
