  <table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr> 
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a>{SHOPLOCATION}</span></td>
	</tr>
  </table>
<form method="post" action="shop_users_edit.php">
<input type="hidden" name="action" value="update_config" />
  <table width="100%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_SHOP_TITLE}</th>
	</tr>
<!-- BEGIN switch_are_shops -->
	<tr>
	  <td class="row3" colspan="2" align="center"><span class="gensmall"><b>{L_SHOP_SETTINGS}</b></span></td>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall"><b>{L_SHOP_NAME}</b></span></td>
	  <td class="row1"><input type="text" name="shop_name" class="post" size="25" maxlength="32" value="{switch_are_shops.SHOP_NAME}" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall"><b>{L_SHOP_TYPE}</b></span></td>
	  <td class="row2"><input type="text" name="shop_type" class="post" size="25" maxlength="32" value="{switch_are_shops.SHOP_TYPE}" /></td>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall"><b>{L_SHOP_STATUS}</b></span></td>
	  <td class="row1">
		<select name="shop_status">
		  <option value="0" {switch_are_shops.STATUS_SELECT_1}>{L_SHOP_OPEN}</option>
		  <option value="1" {switch_are_shops.STATUS_SELECT_2}>{L_SHOP_CLOSED}</option>
		  <option value="2" {switch_are_shops.STATUS_SELECT_3}>{L_SHOP_RESTOCK}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall"><b>{L_SHOP_OPENED}</b></span></td>
	  <td class="row2"><span class="gensmall">{switch_are_shops.SHOP_OPENED}</span></td>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall"><b>{L_POINTS_NAME} {L_EARNED}</b></span></td>
	  <td class="row1"><span class="gensmall">{switch_are_shops.SHOP_EARNT}</span></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall"><b>{L_POINTS_NAME} {L_HOLDING}</b></span></td>
	  <td class="row2"><span class="gensmall">{switch_are_shops.SHOP_HOLDING}
<!-- END switch_are_shops -->
<!-- BEGIN switch_withdraw_holdings -->
&nbsp;&nbsp;&nbsp;[<a href="{switch_withdraw_holdings.WITHDRAW_URL}" class="navsmall">{L_WITHDRAW}</a>]
<!-- END switch_withdraw_holdings -->
<!-- BEGIN switch_are_shops -->
</span></td>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall"><b>{L_ITEMS_LEFT}</b></span></td>
	  <td class="row1"><span class="gensmall">{switch_are_shops.SHOP_ITEMS_LEFT}</span></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall"><b>{L_ITEMS_SOLD}</b></span></td>
	  <td class="row2"><span class="gensmall">{switch_are_shops.SHOP_ITEMS_SOLD}</span></td>
	</tr>
	<tr>
	  <td class="row1" colspan="2" align="center"><input type="submit" value="{L_UPDATE_SETTINGS}" class="liteoption" /></td>
	</tr>
<!-- END switch_are_shops -->
  </table>
</form>
<!-- BEGIN switch_are_shops -->
  <table width="100%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="5">{L_EDIT_ITEMS_TITLE}</th>
	</tr>
<!-- END switch_are_shops -->
<!-- BEGIN switch_are_items -->
	<tr> 
	  <td class="row3"><span class="gensmall"><b>{L_ITEM_NAME}</b></span></td>
	  <td class="row3"><span class="gensmall"><b>{L_SELLER_NOTES}</b></span></td>
	  <td class="row3"><span class="gensmall"><b>{L_COST}</b></span></td>
	  <td class="row3" colspan="2" align="center"><span class="gensmall"><b>{L_ACTIONS}</b></span></td>
	</tr>
<!-- END switch_are_items -->
<!-- BEGIN switch_edit_item -->
<form action="{switch_edit_item.UPDATE_URL}" method="post">
	<tr> 
	  <td class="row3" valign="top"><span class="gensmall"><b>{switch_edit_item.ITEM_NAME}</b></span></td>
	  <td class="row3" valign="top"><textarea name="item_notes" cols="35" rows="2" class="post">{switch_edit_item.ITEM_NOTES}</textarea></td>
	  <td class="row3" valign="top"><input type="text" size="5" maxlength="10" name="item_cost" class="post" value="{switch_edit_item.ITEM_COST}" /></td>
	  <td class="row3" colspan="2" align="center" valign="top"><input type="submit" class="liteoption" value="{L_UPDATE_ITEM}" /></td>
	</tr>
</form>
<!-- END switch_edit_item -->
<!-- BEGIN list_items -->
	<tr>
	  <td class="{list_items.ROW_CLASS}"><span class="gensmall">{list_items.ITEM_NAME}</span></td>
	  <td class="{list_items.ROW_CLASS}"><span class="gensmall">{list_items.ITEM_NOTES}</span></td>
	  <td class="{list_items.ROW_CLASS}"><span class="gensmall">{list_items.ITEM_COST}</span></td>
	  <td class="{list_items.ROW_CLASS}"><a href="{list_items.EDIT_URL}" class="nav">{L_EDIT}</a></td>
	  <td class="{list_items.ROW_CLASS}"><a href="{list_items.DELETE_URL}" class="nav">{L_REMOVE}</a></td>
	</tr>
<!-- END list_items -->
<!-- BEGIN switch_no_items -->
	<tr> 
	  <td class="row3" colspan="5"><span class="gensmall">{L_NO_ITEMS}</span></td>
	</tr>
<!-- END switch_no_items -->
<!-- BEGIN switch_are_shops -->
  </table>
<!-- END switch_are_shops -->
<!-- BEGIN switch_are_a_items -->
<br /><br />
<form method="post" action="shop_users_edit.php">
  <input type="hidden" name="action" value="change_items" />
  <input type="hidden" name="sub_action" value="add_item" />
  <table width="45%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_ADD_ITEM}</th>
	</tr>
	<tr>
	  <td class="row1" width="40%"><span class="gensmall"><b>{L_ITEM_NAME}</b></span></td><td class="row1">
		<select name="item_id">
		  <option value="-1">{L_NONE}</option>
<!-- END switch_are_a_items -->
<!-- BEGIN list_add_items -->
		  <option value="{list_add_items.ITEM_ID}">{list_add_items.ITEM_NAME}</option>
<!-- END list_add_items -->
<!-- BEGIN switch_are_a_items -->
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2" width="40%"><span class="gensmall"><b>{L_COST}</b></span></td>
	  <td class="row2"><input type="text" name="item_cost" class="post" size="5" maxlength="10" /></td>
	</tr>
	<tr>
	  <td class="row2" width="40%"><span class="gensmall"><b>Quantity (Coming Soon)</b></span></td>
	  <td class="row2"><input type="text" name="item_num" class="post" size="5" maxlength="10" /></td>
	<tr>
	  <td class="row1" width="40%" valign="top"><span class="gensmall"><b>{L_SELLER_NOTES}</b></span></td>
	  <td class="row1"><textarea name="item_notes" cols="35" rows="5" class="post"></textarea></td>
	</tr>
	<tr>
	  <td class="row2" colspan="2" align="center"><input type="submit" value="{L_ADD_ITEM}" class="liteoption" /></td>
	</tr>
  </table>
<!-- END switch_are_a_items -->
<br />
<table width="100%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_PERSONAL_INFO}</th>
	</tr>
	<tr>
	  <td class="row1" width="50%"><a href="{U_INVENTORY}" class="gensmall">{L_INVENTORY}</a></td>
	  <td class="row1" align="right" width="50%"><span class="gensmall">{USER_POINTS} {L_POINTS_NAME}</span></td>
	</tr>
</table>
<table width="100%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
		<td width="100%" align="center" class="row3"><br /><span class="gensmall">User Shop Addon: Copyright © 2006, <a href="http://www.zarath.com" class="navsmall">Zarath Technologies</a>.</span><br /><br /></td>
	</tr>
</table>
<br	clear="all" />
