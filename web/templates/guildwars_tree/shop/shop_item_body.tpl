  <table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr> 
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a>{SHOPLOCATION}</span></td>
	</tr>
  </table>
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="6">{L_SHOP_TITLE}</th>
	</tr>
	<tr>
	  <td width="2%" class="row3"><span class="gensmall"><b>{L_ICON}</b></span></td>
	  <td class="row3" nowrap="nowrap"><span class="gen"><b>{L_ITEM_NAME}</b></span></td>
	  <td class="row3"><span class="gen"><b>{L_DESCRIPTION}</b></span></td>
	  <td class="row3"><span class="gen"><b>{L_STOCK}</b></span></td>
	  <td class="row3"><span class="gen"><b>{L_COST}</b></span></td>
	  <td class="row3"><span class="gen"><b>{L_OWNED}</b></span></td>
	</tr>
	<tr>
	  <td class="row1"><img src="shop/images/{ITEM_NAME}.{FILE_EXT}" title="{ITEM_NAME}" alt="{ITEM_NAME}"></td>
	  <td class="row2"><span class="gensmall"><b>{ITEM_NAME}</a><b></span></td>
	  <td class="row2"><span class="gensmall">{ITEM_LDESC}</span></td>
	  <td class="row2"><span class="gensmall">{ITEM_STOCK}</span></td>
	  <td class="row2"><span class="gensmall">{ITEM_COST}</span></td>
	  <td class="row2"><span class="gensmall">{USER_AMOUNT}</span></td>
	</tr>

<!-- BEGIN switch_multi_items -->
	<tr>
	  <td colspan="6" class="row1"><span class="gen"><b><a href="{switch_multi_items.BUY_URL}" title="{L_BUY} {switch_multi_items.NAME}">{L_BUY} {switch_multi_items.NAME}</a></b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><a href="{switch_multi_items.SELL_URL}" title="{L_SELL} {switch_multi_items.NAME}">{L_SELL} {switch_multi_items.NAME}</a></b></td>
	</tr>
<!-- END switch_multi_items -->
<!-- BEGIN switch_multi_ops -->
	<tr>
	  <td colspan="6" class="row1"><span class="gen"><b><a href="{switch_multi_ops.URL}" title="{switch_multi_ops.ACTION} {switch_multi_ops.NAME}">
<!-- END switch_multi_ops -->
<!-- BEGIN switch_multi_ops_buy -->
Buy
<!-- END switch_multi_ops_buy -->
<!-- BEGIN switch_multi_ops_sell -->
Sell
<!-- END switch_multi_ops_sell -->
<!-- BEGIN switch_multi_ops -->
 {switch_multi_ops.NAME}</a></b></span></td>
	</tr>
<!-- END switch_multi_ops -->
  </table>
<br />
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_PERSONAL_INFO}</th>
	</tr>
	<tr>
	  <td class="row1" width="50%"><span class="gensmall"><a href="{USER_INVENTORY}" class="gensmall">{L_INVENTORY}</a></span></td>
	  <td class="row1" align="right" width="50%"><span class="gensmall">{USER_POINTS} {POINTS_NAME}</span></td>
	</tr> 
  </table>
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
		<td width="100%" align="center" class="row1"><br /><span class="gensmall">Shop Modification: Copyright © 2003, 2006 <a href="http://www.zarath.com" class="navsmall">Zarath Technologies</a>.</span><br /><br /></td>
	</tr>
  </table>
<br	clear="all" />
