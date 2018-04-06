  <table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr> 
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a>{SHOPLOCATION}</span></td>
	</tr>
  </table>
<!-- BEGIN switch_is_shops -->
  <table width="30%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
<form method="get" action="shop_users.php">
	<tr>
	  <th class="thHead" colspan="2">{L_SEARCH_TITLE}</th>
	</tr>
	<tr>
	  <td class="row1" align="center"><input type="text" name="search_string" size="25" maxlength="32" class="post" /></td>
	  <td class="row1" align="center"><input type="submit" value="{L_FIND_ITEM}" /></td>
	</tr>
</form>
  </table>
<!-- END switch_is_shops -->
  <table width="100%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="4">{L_SHOP_TITLE}</th>
	</tr>
<!-- BEGIN switch_is_shops -->
	<tr>
	  <td class="row3"><span class="gensmall"><b>{L_SHOP_NAME}</b></span></a></td>
	  <td class="row3"><span class="gensmall"><b>{L_SHOP_TYPE}</b></span></td>
	  <td class="row3"><span class="gensmall"><b>{L_SHOP_OWNER}</b></span></td>
	</tr>
<!-- END switch_is_shops -->
<!-- BEGIN shop_row -->
	<tr>
	  <td class="{shop_row.ROW_CLASS}"><a href="{shop_row.SHOP_URL}" title="{shop_row.SHOP_NAME}" class="nav">{shop_row.SHOP_NAME}</a></td>
	  <td class="{shop_row.ROW_CLASS}"><span class="gensmall">{shop_row.SHOP_TYPE}</span></td>
	  <td class="{shop_row.ROW_CLASS}"><span class="gensmall">{shop_row.SHOP_OWNER}</span></td>
	</tr>
<!-- END shop_row -->
<!-- BEGIN switch_no_shops -->
	<tr>
	  <td class="row1" colspan="4"><span class="gensmall">{switch_no_shops.MSG}</span></td>
	</tr>
<!-- END switch_no_shops -->
<!-- BEGIN switch_user_shop -->
	<tr>
	  <td class="row1" colspan="4"><br /></td>
	</tr>
	<tr>
	  <td class="row3" colspan="4"><span class="gensmall">{switch_user_shop.USER_MSG}</span></td>
	</tr>
<!-- END switch_user_shop -->
  </table>
<br>
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
