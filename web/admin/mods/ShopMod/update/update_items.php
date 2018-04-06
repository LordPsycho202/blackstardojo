<?
ini_set("max_execution_time", -1);

define('IN_PHPBB', true);
$phpbb_root_path = './';
include_once($phpbb_root_path . 'extension.inc');
include_once($phpbb_root_path . 'common.' . $phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

if ( $userdata['user_level'] != 1 ) { message_die(GENERAL_MESSAGE, 'You must be an admin to run this script!'); }

if ( !($_REQUEST['confirm']) )
{
	message_die(GENERAL_MESSAGE, 'This script should only be run if you have not run sql_update.php.<br /><br />To run this script, click <a href="update_items.php?confirm=1" class="gen"><b>here</b></a>.');
}

$sql = "SELECT user_id, username, user_items
	FROM " . USERS_TABLE . "
	WHERE user_items != ''";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Error checking employed users!', '', __LINE__, __FILE__, $sql);
}

$sql_count = $db->sql_numrows($result);

for ( $i = 0; $i < $sql_count; $i++ )
{
	if (!( $row = $db->sql_fetchrow($result) ))
	{
		message_die(GENERAL_ERROR, "Error checking employed users!", '', __LINE__, __FILE__, $sql);
	}
	echo 'Working on User: ' . $row['username'] . '...<br />';

	$itempurge = str_replace("Þ", "", $row['user_items']);
	$itemarray = explode('ß',$itempurge);
	$itemcount = count($itemarray);

	for ( $ii = 0; $ii < $itemcount; $ii++ )
	{
		$sql = "SELECT *
			FROM " . SHOP_ITEMS_TABLE . "
			WHERE name = '" . addslashes($itemarray[$ii]) . "'";

		if ( !($result2 = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error checking user information!', '', __LINE__, __FILE__, $sql);
		}

		$item_data = $db->sql_fetchrow($result2);

		echo '->Adding Item ' . $item_data['name'] . '... ';

		$sql = "INSERT INTO " . USER_ITEMS_TABLE . "
			(user_id, item_id, item_name, item_l_desc, item_s_desc)
			VALUES ('{$row['user_id']}', '{$item_data['id']}', '" . addslashes($item_data['name']) . "', '" . addslashes($item_data['ldesc']) . "', '" . addslashes($item_data['sdesc']) . "')";

		if ( !empty($item_data['name']) )
		{
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Error checking user information!', '', __LINE__, __FILE__, $sql);
			}

			echo 'Complete!<br />';
		}
		else { echo 'Failed -- Empty!<br />'; }
	}

	$sql = "UPDATE " . USERS_TABLE . "
		SET user_items = ''
		WHERE user_id = '{$row['user_id']}'";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error clearing user\'s items!', '', __LINE__, __FILE__, $sql);
	}

	echo '<br /><br />';
}
?>