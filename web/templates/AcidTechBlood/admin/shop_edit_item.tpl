<h1>{L_SHOPTITLE}</h1>
<p>{L_SHOPEXPLAIN}</p>
<form action="{S_CONFIG_ACTION}" method="post">
 <input type="hidden" name="itemid" value="{ITEM_ID}">
 <input type="hidden" name="action" value="updateitem">
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_SHOPTABLETITLE}</th>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_ITEM_NAME}</span></td>
	  <td class="row2"><input name="item" type="text" class="post" size="32" maxlength="32" value="{ITEM_NAME}" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_SHOP_NAME}</span></td>
	  <td class="row2"><input name="shop" type="text" class="post" size="32" maxlength="32" value="{ITEM_SHOP}" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_SHORT_DESC}</span></td>
	  <td class="row2"><input name="shortdesc" type="text" class="post" size="32" maxlength="80" value="{ITEM_SDESC}" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_LONG_DESC}</span></td>
	  <td class="row2"><textarea name="longdesc" columns="20" rows="10" class="post">{ITEM_LDESC}</textarea></td>
	</tr>
<!-- BEGIN synth_shop -->
	<tr>
	  <td class="row2"><span class="gensmall">Synth Info</span></td>
	  <td class="row2"><input name="synth" type="text" class="post" size="32" value="{synth_shop.SYNTH_INFO}"></td>
	</tr>
<!-- END synth_shop -->
	<tr>
	  <td class="row2"><span class="gensmall">{L_PRICE}</span></td>
	  <td class="row2"><input name="price" type="text" class="post" size="32" maxlength="20" value="{ITEM_COST}" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_STOCK}</span></td>
	  <td class="row2"><input name="stock" type="text" class="post" size="32" maxlength="3" value="{ITEM_STOCK}" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_MAX_STOCK}</span></td>
	  <td class="row2"><input name="maxstock" type="text" class="post" size="32" maxlength="3" value="{ITEM_MAX_STOCK}" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_SOLD}</span></td>
	  <td class="row2"><input name="sold" type="text" class="post" size="32" maxlength="5" value="{ITEM_SOLD}" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_SPECIAL_LINK}</span></td>
	  <td class="row2"><input name="special_link" type="text" class="post" size="32" maxlength="255" value="{ITEM_SPECIAL_LINK}" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_AMMO}</span> put % signs on either side of ammo</td>
	  <td class="row2"><input type="text" class="post" name="ammo" size="32" maxlength="16" value="{ITEM_AMMO}"></td>
	</tr>
	  <td class="row2"><span class="gensmall">{L_PASS}</span> %U will be substituted by user's name, %T by supplied target text</td>
	  <td class="row2"><input type="text" class="post" name="pass" size="32" maxlength="128" value="{ITEM_PASS}"></td>
	</tr>
	</tr>
	  <td class="row2"><span class="gensmall">{L_FAIL}</span> %U will be substituted by user's name, %T by supplied target text</td>
	  <td class="row2"><input type="text" class="post" name="fail" size="32" maxlength="128" value="{ITEM_FAIL}"></td>
	</tr>
	</tr>
	  <td class="row2"><span class="gensmall">{L_BREAK}</span></td>
	  <td class="row2"><input type="text" class="post" name="btext" size="32" maxlength="128" value="{ITEM_BTEXT}"></td>
	</tr>
	</tr>
	  <td class="row2"><span class="gensmall">{L_PROB}</span></td>
	  <td class="row2"><input type="text" class="post" name="prob" size="32" maxlength="3" value="{ITEM_PROB}"></td>
	</tr>
	</tr>
	  <td class="row2"><span class="gensmall">{L_BPROB}</span></td>
	  <td class="row2"><input type="text" class="post" name="break" size="32" maxlength="3" value="{ITEM_BREAK}"></td>
	</tr>
	<tr>
	  <td class="row2" align="center"><input type="submit" class="liteoption" value="{L_UPDATE_ITEM}" /></td>
</form>
<form action="{S_CONFIG_ACTION}" method="post">
 <input type="hidden" name="action" value="deleteitem">
 <input type="hidden" name="itemid" value="{ITEM_ID}">
	 <td class="row2" align="center"><input type="submit" class="liteoption" value="{L_DELETE_ITEM}" /></td>
	</tr>
  </table>
</form>

<br />

<!-- BEGIN user_owned -->
<form action="{S_CONFIG_ACTION}" method="post">
 <input type="hidden" name="action" value="editinventory" />
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead">{L_OWNED_BY}</th>
	</tr>
	<tr>
	  <td align="center" class="row3">
		<select name="username">
<!-- END user_owned -->
<!-- BEGIN list_users -->
		  <option value="{list_users.USER_ID}">{list_users.USERNAME}</option>
<!-- END list_users -->
<!-- BEGIN user_owned -->
		</select>
		<input type="submit" class="liteoption" value="{L_EDIT_INV}" />
	  </td>
	</tr>
  </table>
</form>
<!-- END user_owned -->

  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
		<td width="100%" align="center" class="row1"><br /><span class="gensmall">Shop Modification: Copyright © 2003, 2006 <a href="http://www.zarath.com" class="navsmall">Zarath Technologies</a>.</span><br /><br /></td>
	</tr>
  </table>
<br	clear="all" />
