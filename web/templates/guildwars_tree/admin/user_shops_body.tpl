<h1>{L_TITLE}</h1>
<p>{L_EXPLAIN}</p>
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_TABLE_TITLE_1}</th>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_TOTAL_SHOPS}</span></td>
	  <td class="row1"><span class="gensmall">{TOTAL_SHOPS}</span></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_TOTAL_ITEMS}</span></td>
	  <td class="row2"><span class="gensmall">{TOTAL_ITEMS}</span></td>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_TOTAL_POINTS}</span></td>
	  <td class="row1"><span class="gensmall">{TOTAL_HOLDING}</span></td>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_TOTAL_EARNT}</span></td>
	  <td class="row1"><span class="gensmall">{TOTAL_EARNT}</span></td>
	</tr>
  </table>

<br /><br />

<form action="{S_CONFIG_ACTION}" method="post">
<input type="hidden" name="action" value="update_vars" />
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_TABLE_TITLE_2}</th>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_USER_SHOPS}</span></td>
	  <td class="row1">
		<select name="status">
		  <option value="1" {SHOP_OPEN}>{L_OPEN}</option>
		  <option value="0" {SHOP_CLOSED}>{L_CLOSED}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_PERCENT_TAKEN}</span></td>
	  <td class="row1"><input type="text" name="tax_percent" size="5" value="{TAX_PERCENT}" maxlength="3" class="post" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_MAX_ITEMS}</span></td>
	  <td class="row2"><input type="text" name="max_items" size="10" value="{MAX_ITEMS}" maxlength="10" class="post" /></td>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_OPEN_COST}</span></td>
	  <td class="row1"><input type="text" name="open_cost" size="10" value="{OPEN_COST}" maxlength="15" class="post" /></td>
	</tr>
	<tr>
	  <td class="row1" colspan="2" align="center"><input type="submit" value="{L_UPDATE_CONFIG}" name="Update" class="liteoption" /></td>
	</tr>
  </table>
</form>

<br /><br />

<!-- BEGIN switch_are_shops -->
<form action="{S_CONFIG_ACTION}" method="post">
<input type="hidden" name="action" value="close_shop" />
  <table width="450" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_TABLE_TITLE_3}</th>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_SHOP_NAME}</span></td>
	  <td class="row1">
		<select name="id">
<!-- END switch_are_shops -->
<!-- BEGIN list_shops -->
			<option value="{list_shops.SHOP_ID}">{list_shops.STRING}</option>
<!-- END list_shops -->
<!-- BEGIN switch_are_shops -->
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_RETURN_ITEMS}:</span></td>
	  <td class="row2">
		<select name="items">
		  <option value="1">{L_ON}</option>
		  <option value="0">{L_OFF}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2" colspan="2" align="center"><input type="submit" value="{L_CLOSE_SHOP}" name="update" class="liteoption" /></td>
	</tr>
  </table>
</form>

<br />

<form action="{S_CONFIG_ACTION}" method="post">
<input type="hidden" name="action" value="close_shop" />
  <table width="450" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_TABLE_TITLE_4}</th>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_SHOP_OWNER}</span></td>
	  <td class="row1">
		<select name="id">
<!-- END switch_are_shops -->
<!-- BEGIN list_users -->
			<option value="{list_users.SHOP_ID}">{list_users.STRING}</option>
<!-- END list_users -->
<!-- BEGIN switch_are_shops -->
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_RETURN_ITEMS}:</span></td>
	  <td class="row2">
		<select name="items">
		  <option value="1">{L_ON}</option>
		  <option value="0">{L_OFF}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2" colspan="2" align="center"><input type="submit" value="{L_CLOSE_SHOP}" name="update" class="liteoption" /></td>
	</tr>
  </table>
</form>

<br /><br />
<!-- END switch_are_shops -->


  <table width="100%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
		<td width="100%" align="center" class="row2"><br /><span class="gensmall">User Shops Modification: Copyright © 2006, <a href="http://www.zarath.com" class="navsmall">Zarath Technologies</a>.</span><br /><br /></td>
	</tr>
  </table>
<br	clear="all" />
