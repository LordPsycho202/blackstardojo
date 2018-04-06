  <table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr> 
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a>{SHOPLOCATION}</span></td>
	</tr>
  </table>
  <table width="100%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="4">{L_SHOP_TITLE}</th>
	</tr>
	<tr>
	  <td class="row3" align="center"><span class="gensmall"><b>{L_DRINK}</b></span></td>
	  <td class="row3" align="center"><span class="gensmall"><b>{L_DESCRIPTION}</b></span></td>
	  <td class="row3" align="center" valign="top"><span class="gensmall"><b>{L_COST}</b></span></td>
	  <td class="row3" align="center" valign="top"><span class="gensmall"><b>{L_STOCK}</b></span></td>
	</tr>
<!-- BEGIN list_items -->
	<tr>
	  <td class="{list_items.ROW_CLASS}" valign="top"><a href="{list_items.ITEM_URL}" title="{L_BUY} {list_items.ITEM_NAME}" class="nav"><b>{list_items.ITEM_NAME}</b></a></td>
	  <td class="{list_items.ROW_CLASS}"><span class="gensmall">{list_items.ITEM_DESC}<br /><b>{L_INTOX_DUR}: {list_items.ITEM_LENGTH}</span></td>
	  <td class="{list_items.ROW_CLASS}"><span class="gensmall">{list_items.ITEM_COST}</span></td>
	  <td class="{list_items.ROW_CLASS}"><span class="gensmall">{list_items.ITEM_STOCK}</span></td>
	</tr>
<!-- END list_items -->
<!-- BEGIN switch_no_drinks -->
	<tr>
	  <td class="row3" colspan="4"><span class="gensmall"><b>{L_NO_DRINKS}</b></span></td>
	</tr>
<!-- END switch_no_drinks -->
<!-- BEGIN switch_is_drunk -->
	<tr>
	  <td class="row3" colspan="4"><span class="gensmall"><b>{switch_is_drunk.L_TIME_LEFT}</b></span></td>
	</tr>
<!-- END switch_is_drunk -->
  </table>
<br />

<table width="100%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_PERSONAL_INFO}</th>
	</tr>
	<tr>
	  <td class="row1" width="50%"><span class="gensmall"><a href="{U_INVENTORY}" class="navsmall">{L_INVENTORY}</a></span></td><td class="row1" align="right" width="50%"><span class="gensmall">{USER_POINTS} {L_POINTS_NAME}</span></td>
	</tr> 
</table>
<table width="100%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
		<td width="100%" align="center" class="row3"><br /><span class="gensmall">Bar Shop Addon: Copyright © 2006, <a href="http://www.zarath.com" class="navsmall">Zarath Technologies</a>.</span><br /><br /></td>
	</tr>
</table>
<br	clear="all" />
