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
<!-- BEGIN switch_select_items -->
<form name="post" method="post" action="{switch_select_items.U_GIVE}">
	<tr>
	  <td class="row2" width="50%"><span class="gensmall"><b>{L_YOUR_ITEMS}:</b></span></td>
	  <td class="row1">
		<select name="itemname[]" multiple="multiple" class="textfield" size="5">
			{switch_select_items.USER_ITEMS}
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall"><b>{L_GIVE_TO}:</b></span></td>
	  <td class="row1"><input type="text" class="post" name="username" /> <input type="submit" name="usersubmit" value="{L_FIND_USER}" class="liteoption" onClick="window.open('./search.php?mode=searchuser', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" /></select></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall"><b>{L_MESSAGE}:</b></span></td>
	  <td class="row1"><textarea name="message" rows="8" cols="25" wrap="virtual" style="width:400px" class="post"></textarea></td>
	</tr>
	<tr>
	  <td class="row2" colspan="2" align="center"><input type="submit" value="{L_EXECUTE}" class="liteoption" /></td>
	</tr>
</form>
<!-- END switch_select_items -->
<!-- BEGIN switch_confirm_give -->
<form name="post" method="post" action="{switch_confirm_give.U_GIVE}">
  <input type="hidden" name="itemlist" value="{switch_confirm_give.ITEM_LIST}" />
  <input type="hidden" name="item_name" value="{switch_confirm_give.ITEM_NAMES}" />
  <input type="hidden" name="username" value="{switch_confirm_give.USERNAME}" />
  <input type="hidden" name="message" value="{switch_confirm_give.MESSAGE}" />
	<tr>
	  <td class="row2" align="center"><input type="submit" value="{L_YES}" class="liteoption" /> <input type="button" value="{L_NO}" onclick="document.location='shop_actions.php'"  class="liteoption" /></td>
	</tr>
</form>
<!-- END switch_confirm_give -->
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
