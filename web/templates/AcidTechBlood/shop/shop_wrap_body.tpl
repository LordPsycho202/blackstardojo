  <table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr> 
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a>{SHOPLOCATION}</span></td>
	</tr>
  </table>
  <table width="100%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="5">{L_SHOP_TITLE}</th>
	</tr>
<!-- BEGIN switch_can_wrap -->
	<tr>
	  <td class="row2" align="left" colspan="4"><span class="gen"><b>{L_WRAP_PAPER}</b></span><span class="gensmall"> [{L_COST}: {PAPER_COST} {L_POINTS_NAME}]</span></td>
	</tr>
<form method="post" action="{U_ACTION}">
<input type="hidden" name="action" value="wrap" />
<input type="hidden" name="type" value="normal" />
	<tr>
	  <td class="row3" align="center" width="10%"> </td>
	  <td class="row1" align="left"><span class="gensmall"><b>{L_ITEM}: </b></span><select name="item_id"><option value="0">{L_NONE}</option>{ITEM_LIST}</select></td>
	  <td class="row1" align="center"><span class="gensmall"><b>{L_COLOR}: </b></span><select name="color">{COLOR_LIST}</select></td>
	  <td class="row1" align="center"><input type="submit" value="{L_WRAP_ITEM}" class="post" /></td>
	</tr>
</form>
	<tr>
	  <td colspan="4" class="row3"><br /></td>
	</tr>
	<tr>
	  <td class="row2" align="left" colspan="4"><span class="gen"><b>{L_WRAP_BOW}</b></span><span class="gensmall"> [{L_COST}: {BOW_COST} {L_POINTS_NAME}]</span></td>
	</tr>
<form method="post" action="{U_ACTION}">
<input type="hidden" name="action" value="wrap" />
<input type="hidden" name="type" value="bow" />
	<tr>
	  <td class="row3" align="center" width="10%"> </td>
	  <td class="row1" align="left"><span class="gensmall"><b>{L_ITEM}: </b></span><select name="item_id"><option value="0">{L_NONE}</option>{ITEM_LIST}</select></td>
	  <td class="row1" align="center"><span class="gensmall"><b>{L_COLOR}: </b></span><select name="color">{COLOR_LIST}</select></td>
	  <td class="row1" align="center"><input type="submit" value="{L_WRAP_ITEM}" class="post" /></td>
	</tr>
</form>
<!-- END switch_can_wrap -->

<!-- BEGIN switch_can_sell -->
<form method="post" action="shop_wrap.php">
<input type="hidden" name="action" value="sell" />
	<tr>
	  <td colspan="4" class="row3"><br /></td>
	</tr>
	<tr>
	  <td class="row2" align="left" colspan="4"><span class="gen"><b>{L_SELL_BOW}</b></span></td>
	</tr>
	<tr>
	  <td class="row1" align="center" colspan="2"><select name="item_id">{switch_can_sell.SELL_ITEMS}</select></td>
	  <td class="row1" align="center" colspan="2"><input type="submit" value="{L_SELL_BOW}" class="post" /></td>
	</tr>
</form>
<!-- END switch_can_sell -->

<!-- BEGIN switch_no_items -->
	<tr>
	  <td class="row3"><span class="gen"><b>{switch_no_items.L_UNABLE_WRAP}</b></span></td>
	</tr>
<!-- END switch_no_items -->
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
		<td width="100%" align="center" class="row3"><br /><span class="gensmall">Wrapping Shop Addon: Copyright © 2006, <a href="http://www.zarath.com" class="navsmall">Zarath Technologies</a>.</span><br /><br /></td>
	</tr>
  </table>
<br	clear="all" />
