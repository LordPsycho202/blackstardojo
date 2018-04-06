<h1>{L_SHOPTITLE}</h1>
<p>{L_SHOPEXPLAIN}</p>
<form action="{S_CONFIG_ACTION}" method="post">
 <input type="hidden" name="subaction" value="delete" />
 <input type="hidden" name="action" value="updateinv" />
 <input type="hidden" name="username" value="{USER_ID}" />
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_SHOPTABLETITLE}</th>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_DELETE_ITEM}: </span></td>
	  <td class="row2">
		<select name="itemname">
<!-- BEGIN list_user_items -->
		  <option value="{list_user_items.ID}">{list_user_items.ITEM_NAME}</option>
<!-- END list_user_items -->
		</select> 
		 <input type="submit" class="liteoption" value="{L_DELETE_ITEM}" />
	  </td>
	</tr>
</form>
<form action="{S_CONFIG_ACTION}" method="post" />
 <input type="hidden" name="action" value="updateinv" />
 <input type="hidden" name="subaction" value="add" />
 <input type="hidden" name="username" value="{USER_ID}" />
	<tr>
	  <td class="row2"><span class="gensmall">{L_ADD_ITEM}: </span></td>
	  <td class="row2">
		<select name="itemname">
<!-- BEGIN list_shop_items -->
		  <option value="{list_shop_items.ID}">{list_shop_items.ITEM_NAME}</option>
<!-- END list_shop_items -->
		</select> 
		 
	  </td>
	</tr>
	<tr>
	 <td class="row2"><span class="gensmall">{L_QUANTITY}</span></td>
	 <td class="row2"><input type="text" size="2" maxlength="32" name="quantity" class="post" />&nbsp;&nbsp;<input type="submit" class="liteoption" value="{L_ADD_ITEM}" /></td>
</form>


<form action="{S_CONFIG_ACTION}" method="post">
 <input type="hidden" name="subaction" value="clear" />
 <input type="hidden" name="action" value="updateinv" />
 <input type="hidden" name="username" value="{USER_ID}" />
	<tr>
	  <td class="row2"><span class="gensmall">{L_CLEAR_ITEMS}: </span></td>
	  <td class="row2"><input type="submit" class="liteoption" value="{L_DELETE_INV}" /></td>
	</tr>
  </table>
</form>
<br />
<form action="{S_CONFIG_ACTION}" method="post">
 <input type="hidden" name="subaction" value="invsize" />
 <input type="hidden" name="action" value="updateinv" />
 <input type="hidden" name="username" value="{USER_ID}" />
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
	  <th class="thHead" colspan="2">{L_INV_SIZE}</th>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_INV_SIZE_2}</span></td>
	  <td class="row1"><input type="text" value="{C_INV_SIZE}" size="20" maxlength="3" name="inv_size" class="post">&nbsp;&nbsp;<input type="submit" class="liteoption" value="Change" /></td>
	</tr>
  <table>
</form>
<br />
<form action="{S_CONFIG_ACTION}" method="post">
 <input type="hidden" name="subaction" value="unique_item" />
 <input type="hidden" name="action" value="updateinv" />
 <input type="hidden" name="username" value="{USER_ID}" />
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_CUSTOM_ITEM}</th>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_ITEM_NAME}: </span></td>
	  <td class="row1"><input type="text" size="20" maxlength="32" name="item_name" class="post" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_ITEM_ID}: </span></td>
	  <td class="row2"><input type="text" size="5" maxlength="10" name="item_id" class="post" value="-1" /></td>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_SHORT_DESC}: </span></td>
	  <td class="row1"><input type="text" size="20" maxlength="100" name="item_sdesc" class="post" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_LONG_DESC}: </span></td>
	  <td class="row2"><input type="text" size="20" name="item_ldesc" class="post" /></td>
	</tr>
	<tr>
	  <td class="row3" colspan="2" align="center"><input type="submit" class="liteoption" value="{L_ADD_ITEM}" /></td>
	</tr>
  </table>
</form>


  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
		<td width="100%" align="center" class="row1"><br /><span class="gensmall">Shop Modification: Copyright © 2003, 2006 <a href="http://www.zarath.com" class="navsmall">Zarath Technologies</a>.</span><br /><br /></td>
	</tr>
  </table>
<br	clear="all" />
