<h1>{L_SHOPTITLE}</h1>
<p>{L_SHOPEXPLAIN}</p>
<form action="{S_CONFIG_ACTION}" method="post">
 <input type="hidden" name="shopid" value="{SHOP_ID}" />
 <input type="hidden" name="action" value="updateshop" />
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_TABLE_TITLE}</th>
	</tr>
	<tr>
 	  <td class="row2"><span class="gensmall">{L_SHOP_NAME}</span></td>
	  <td class="row2"><input type="text" class="post" name="name" value="{SHOP_NAME}" size="32" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_SHOP_OWNER}</span></td>
	  <td class="row2"><input type="text" class="post" name="shop_owner" value="{SHOP_OWNER}" size="32" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_SHOP_TYPE}</span></td>
	  <td class="row2"><input type="text" class="post" name="shoptype" value="{SHOP_TYPE}" size="32" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_DISTRICT_TYPE}</span></td>
	  <td class="row2"><input type="text" class="post" name="shop_dtype" value="{SHOP_DTYPE}" size="10" maxlength="5" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_DISTRICT_NUM}</span></td>
	  <td class="row2"><input type="text" class="post" name="shop_district" value="{SHOP_DISTRICT}" size="10" maxlength="5" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_RESTOCK_TIME}</span></td>
	  <td class="row2"><input type="text" class="post" name="restocktime" value="{RESTOCK_TIME}" size="10" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_RESTOCK_AMT}</span></td>
	  <td class="row2"><input type="text" class="post" name="restockamount" value="{RESTOCK_AMOUNT}" size="10" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_MAIN_TEMPLATE}</span></td>
	  <td class="row2"><input type="text" class="post" name="main_template" value="{SHOP_MAIN_TEMPLATE}" size="32" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_ITEM_TEMPLATE}</span></td>
	  <td class="row2"><input type="text" class="post" name="item_template" value="{SHOP_ITEM_TEMPLATE}" size="32" /></td>
	</tr>
	<tr>
	  <td class="row1" align="center"><input type="submit" class="liteoption" value="{L_UPDATE_SHOP}" /></td>
</form>
<form action="{S_CONFIG_ACTION}" method="post">
  <input type="hidden" name="shopid" value="{SHOP_ID}" />
  <input type="hidden" name="action" value="deleteshop" />
	  <td class="row1" align="center"><input type="submit" class="liteoption" value="{L_DELETE_SHOP}" /></td>
	</tr>
  </table>
</form>

<br /><br />

<form action="{S_CONFIG_ACTION}" method="post">
 <input type="hidden" name="action" value="edititem" />
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_TABLE_TITLE2}</th>
	</tr>
<!-- BEGIN switch_no_items -->
	<tr>
	  <td class="row2" colspan="2"><span class="gensmall">{L_NO_ITEMS}</span></td>
	</tr>
<!-- END switch_no_items -->
<!-- BEGIN switch_has_items -->
	<tr>
	  <td class="row2">
		<select name="itemid">
<!-- END switch_has_items -->
<!-- BEGIN list_shop_items -->
		  <option value="{list_shop_items.ID}">{list_shop_items.NAME}</option>
<!-- END list_shop_items -->
<!-- BEGIN switch_has_items -->
		</select>
	  </td>
	  <td class="row2"><input type="submit" class="liteoption" value="{L_EDIT_ITEM}" /></td>
	</tr>
<!-- END switch_has_items -->
</form>
	<tr>
	  <td class="row3" colspan="2"><br /></td>
	</tr>
<form action="{S_CONFIG_ACTION}" method="post">
  <input type="hidden" name="action" value="additem" />
  <input type="hidden" name="shopid" value="{SHOP_ID}" />
	<tr>
	  <td class="row2"><span class="gensmall">{L_ITEM_NAME}</span></td>
	  <td class="row2"><input type="text" class="post" name="item" size="32"  maxlength="32" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_SHORT_DESC}</span></td>
	  <td class="row2"><input type="text" class="post" name="shortdesc" size="32"  maxlength="80" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_LONG_DESC}</span></td>
	  <td class="row2"><input type="text" class="post" name="longdesc" size="32" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_PRICE}</span></td>
	  <td class="row2"><input type="text" class="post" name="price" size="32" maxlength="20" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_STOCK}</span></td>
	  <td class="row2"><input type="text" class="post" name="stock" size="32" maxlength="3" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_MAX_STOCK}</span></td>
	  <td class="row2"><input type="text" class="post" name="maxstock" size="32" maxlength="3" /></td>
	</tr>
	<tr>
	  <td class="row2" colspan="2" align="center"><input type="submit" class="liteoption" value="{L_ADD_ITEM}" /></td>
	</tr>
  </table>
</form>
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
		<td width="100%" align="center" class="row1"><br /><span class="gensmall">Shop Modification: Copyright © 2003, 2006 <a href="http://www.zarath.com" class="navsmall">Zarath Technologies</a>.</span><br /><br /></td>
	</tr>
  </table>
<br	clear="all" />
