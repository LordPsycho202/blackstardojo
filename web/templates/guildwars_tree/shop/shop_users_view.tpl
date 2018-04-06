  <table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr> 
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a>{SHOPLOCATION}</span></td>
	</tr>
  </table>
  <table width="100%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
<!-- BEGIN switch_main_list -->
	<tr> 
	  <th class="thHead" colspan="5">{L_SHOP_TITLE}</th>
	</tr>
	<tr>
	  <td class="row3" align="center" nowrap="nowrap"><span class="gensmall"><b>{L_ICON}</b></span></td>
	  <td class="row3" align="center" nowrap="nowrap"><span class="gensmall"><b>{L_ITEM_NAME}</b></span></td>
	  <td class="row3" align="center" nowrap="nowrap"><span class="gensmall"><b>{L_S_DESC}</b></span></td>
	  <td class="row3" align="center"><span class="gensmall"><b>{L_COST}</b></span></td>
	  <td class="row3" align="center"><span class="gensmall"><b>{L_LEFT}</b></span></td>
	</tr>
<!-- END switch_main_list -->
<!-- BEGIN list_items -->
	<tr>
	  <td class="{list_items.ROW_CLASS}" align="center" valign="top"><img src="shop/images/{list_items.ITEM_NAME}" title="{list_items.ITEM_NAME}" alt="{list_items.ITEM_NAME}"></td>
	  <td class="{list_items.ROW_CLASS}" valign="center"><a href="{list_items.ITEM_URL}" title="Item Information on {list_items.ITEM_NAME}" class="nav"><b>{list_items.ITEM_NAME}</b></a></td>
	  <td class="{list_items.ROW_CLASS}"><span class="gensmall">{list_items.ITEM_S_DESC} <i>[{L_NOTES}: {list_items.ITEM_NOTES}]</i></span></td>
	  <td class="{list_items.ROW_CLASS}" valign="center"><span class="gensmall">{list_items.ITEM_COST}</span></td>
	  <td class="{list_items.ROW_CLASS}" valign="center"><span class="gensmall">{list_items.ITEM_STOCK}</span></td>
	</tr>
<!-- END list_items -->
<!-- BEGIN switch_item_info -->
	<tr> 
	  <th class="thHead" colspan="6">{L_TABLE_TITLE}</th>
	</tr>
	<tr>
	  <td width="2%" class="row3"><span class="gensmall"><b>{L_ICON}</b></span></td>
	  <td class="row3" nowrap="nowrap"><span class="gensmall"><b>{L_ITEM_NAME}</b></span></td>
	  <td class="row3"><span class="gensmall"><b>{L_DESCRIPTION}</b></span></td>
	  <td class="row3"><span class="gensmall"><b>{L_COST}</b></span></td>
	  <td class="row3"><span class="gensmall"><b>{L_OWNED}</b></span></td>
	</tr>
	<tr>
	  <td class="row1"><img src="shop/images/{switch_item_info.ITEM_NAME}" title="{switch_item_info.ITEM_NAME}" alt="{switch_item_info.ITEM_NAME}"></td>
	  <td class="row1" valign="top"><span class="gensmall"><b>{switch_item_info.ITEM_NAME}</b></span></td>
	  <td class="row1" valign="top"><span class="gensmall">{switch_item_info.ITEM_L_DESC}<br /><i>[{L_NOTES}: {switch_item_info.ITEM_NOTES}]</i></span></td>
	  <td class="row1" valign="top"><span class="gensmall">{switch_item_info.ITEM_COST}</span></td>
	  <td class="row1" valign="top"><span class="gensmall">{switch_item_info.ITEM_OWNED}</span></td>
	</tr>
	<tr>
	  <td colspan="6" class="row2"><b><a href="{switch_item_info.ITEM_URL}" title="{L_BUY} {switch_item_info.ITEM_NAME}" class="nav">{L_BUY} {switch_item_info.ITEM_NAME}</a></b></span></td>
	</tr>
<!-- END switch_item_info -->
  </table>
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
