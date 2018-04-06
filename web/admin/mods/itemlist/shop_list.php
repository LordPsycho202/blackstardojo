<?php 

// standard hack prevent 
define('IN_PHPBB', true); 
$phpbb_root_path = './'; 
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx); 

// standard session management 
$userdata = session_pagestart($user_ip, PAGE_TEMPLATE); 
init_userprefs($userdata); 

// set page title 
$page_title = 'Items List'; 

// standard page header 
include($phpbb_root_path . 'includes/page_header.'.$phpEx); 

// assign template 
$template->set_filenames(array( 
        'body' => 'shop_list.tpl') 
); 

$sql = "SELECT * FROM ". SHOP_ITEMS_TABLE;

if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Fetch Error retrieving item List.'); }

$icount = $db->sql_numrows($result);
$psql = "SELECT *
	FROM " . USER_ITEMS_TABLE;
if ( !($presult = $db->sql_query($psql)) ){message_die(GENERAL_MESSAGE, 'SQL Fetch Error retrieving user items.'); }

$total = $db->sql_numrows($presult);
$max = $total;

for($k = 0;$k<$icount;$k++)
{
	$rownum = ( $icount % 2 ) ? "row1" : "row2";
	$row = $db->sql_fetchrow($result);
	$tsql = "SELECT * 
 		 FROM ". USER_ITEMS_TABLE ."
		 WHERE `item_id` = ".$row['item_id'];
	
	if ( !($tresult = $db->sql_query($tsql)) ) { message_die(GENERAL_MESSAGE, 'SQL Fetch Error retrieving user items.'); }
	$temp = $db->sql_numrows($tresult);
	if(($temp > 0) || $userdata['user_level'] == ADMIN){
			
		$temp = $total/$temp;
		$IRI = $temp/$max;
		$pos = strpos($IRI,".");
		$IRI = substr($IRI,0,$pos+3);
	
		if (file_exists("shop/images/".$row['name'].".jpg")) { $itemfilext = "jpg"; }
		elseif (file_exists("shop/images/".$row['name'].".png")) { $itemfilext = "png"; }
		else { $itemfilext = "gif"; }

		$tsql = "SELECT `id` FROM ". SHOP_TABLE ." WHERE `shopname` = ".$row['shop'];
		if ( !($tresult = $db->sql_query($tsql)) ) { message_die(GENERAL_MESSAGE, 'SQL Fetch Error retrieving shop info.'); }
		$row2 = $db->sql_fetchrow($tresult);
		$template->assign_block_vars('listrow', array(
			'ROW_CLASS' => $rownum,
			'NAME' => $row['name'],
			'SDESC' => $row['sdesc'],
			'COST' => $row['cost'],
			'SHOP' => $row['shop'],
			'IRI' => $IRI,
			'URL' => append_sid('shop_inventory.'.$phpEx.'?action=shoplist&shop='.$row2['id'],
			'FILE_EXT' => $itemfilext)
		);
	}	
}
$shoplocation = ' -> <a href="'.append_sid("shop.$phpEx").'" class="nav">' . $lang['shop_districts'] . '</a>';
$template->assign_vars(array(
	'L_ITEM_LIST' => 'Item List',
	'L_ITEM_NAME' => $lang['name'],
	'L_S_DESC' => $lang['item_s_desc'],
	'L_COST' => $lang['item_cost'],
	'L_SHOP_NAME' => $lang['shop_name'],
	'L_ICON' => $lang['icon'],
	'L_IRI' => "Item's Rarity Index",
	'SHOPLOCATION' => $shoplocation));
	

$template->pparse('body'); 

// standard page footer 
include($phpbb_root_path . 'includes/page_tail.'.$phpEx); 

?>
