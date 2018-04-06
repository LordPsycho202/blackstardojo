  <table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr> 
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a>{SHOPLOCATION}</span></td>
	</tr>
  </table>
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="5">{L_SHOP_TITLE}</th>
	</tr>
	<tr>
	  <td colspan="2" class="row1" align="center"><span class="gensmall">{L_EXPLAIN}</span></td>
	</tr>
<!-- BEGIN switch_select_discard -->
<form name="post" method="post" action="{switch_select_discard.U_DISCARD}">
	<tr>
	  <td class="row2" width="50%"><span class="gensmall">{L_CURRENT_ITEMS}: </span></td>
	  <td class="row1"><select name="itemid[]" multiple="multiple">{switch_select_discard.USER_ITEMS}</select></td>
	</tr>
	<tr>
	  <td class="row2" colspan="2" align="center"><input type="submit" value="{L_EXECUTE}" class="liteoption" /></td>
	</tr>
</form> 
<!-- END switch_select_discard -->
<!-- BEGIN switch_confirm_discard -->
<form name="post" method="post" action="{switch_confirm_discard.U_DISCARD}">
  <input type="hidden" name="itemid" class="liteoption" value="{switch_confirm_discard.ITEM_LIST}" />
	<tr>
	  <td class="row2" align="center"><input type="submit" value="{L_YES}" class="liteoption" /> <input type="button" value="{L_NO}" onclick="document.location='shop.php'" class="liteoption" /></td>
	</tr>
</form> 

<!-- END switch_confirm_discard -->
  </table>
<br />
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_PERSONAL_INFO}</th>
	</tr>
	<tr>
	  <td class="row1" width="50%"><a href="{U_INVENTORY}" class="gensmall">{L_INVENTORY}</a></td>
	  <td class="row1" align="right" width="50%"><span class="gensmall">{USER_POINTS} {L_POINTS_NAME}</span></td>
	</tr>
  </table>
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
		<td width="100%" align="center" class="row1"><br /><span class="gensmall">Shop Modification: Copyright © 2003, 2006 <a href="http://www.zarath.com" class="navsmall">Zarath Technologies</a>.</span><br /><br /></td>
	</tr>
  </table>
<br	clear="all" />
