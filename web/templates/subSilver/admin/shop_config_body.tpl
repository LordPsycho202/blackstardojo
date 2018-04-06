<h1>{L_SHOPTITLE}</h1>
<p>{L_SHOPEXPLAIN}</p>
<form action="{S_CONFIG_ACTION}" method="post">
 <input type="hidden" name="action" value="updateglobals" />
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_TABLE_TITLE}</th>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_SHOP_DISTRICTS}</span></td>
	  <td class="row2">
		<select name="shopdistricts">
		  <option value="on">{L_ON}</option>
		  <option value="off" {SELECT_DISTRICTS}>{L_OFF}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_MULTI_ITEMS}</span></td>
	  <td class="row2">
		<select name="multiitems">
		  <option value="on">{L_ON}</option>
		  <option value="off" {SELECT_M_BUY_OFF}>{L_OFF}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_SHOP_OWNERS}</span></td>
	  <td class="row2">
		<select name="shopowners">
		  <option value="on">{L_ON}</option>
		  <option value="off" {SELECT_SHOP_OWNERS_OFF}>{L_OFF}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_SHOP_ORDER}</span></td>
	  <td class="row2">
		<select name="orderby">
		  <option value="name">{L_NAME}</option>
		  <option value="cost" {SELECT_ORDER_2}>{L_COST}</option>
		  <option value="id" {SELECT_ORDER_2}>ID</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_SHOP_RESTOCKING}</span></td>
	  <td class="row2">
		<select name="shoprestock">
		  <option value="on">{L_ON}</option>
		  <option value="off" {SELECT_RESTOCK_OFF}>{L_OFF}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_POINTS_NAME}</span></td>
	  <td class="row2"><input name="pointsname" type="text" class="post" size="10" maxlength="20" value="{POINTS_NAME}" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_SHOP_SELL_RATE}</span></td>
	  <td class="row2"><input name="sellrate" type="text" class="post" size="4" maxlength="3" value="{SHOP_SELL_RATE}" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_USER_INV_LIMIT}</span></td>
	  <td class="row2"><input name="invlimit" type="text" class="post" size="4" maxlength="3" value="{SHOP_INV_LIMIT}" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_VIEWTOPIC_LIMIT}</span></td>
	  <td class="row2"><input name="topicdisplaynum" class="post" type="text" size="4" maxlength="3" value="{SHOP_VIEWTOPIC_LIM}" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_VIEWTOPIC_TYPE}</span></td>
	  <td class="row2">
		<select name="viewtopic">
		  <option value="images">{L_IMAGES}</option>
		  <option value="link" {SELECT_VIEWTOPIC}>{L_LINK}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_PROFILE_DISPLAY}</span></td>
	  <td class="row2">
		<select name="profiledisplay">
		  <option value="images">{L_IMAGES}</option>
		  <option value="link" {SELECT_PROFILE}>{L_LINK}</option>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_INV_TYPE}</span></td>
	  <td class="row2">
		<select name="inventorytype">
		  <option value="grouped">{L_GROUPED}</option>
		  <option value="normal" {SELECT_INVENTORY}>{L_NORMAL}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_GIVE}</span></td>
	  <td class="row2">
		<select name="shopgive">
		  <option value="on">{L_ON}</option>
		  <option value="off" {SELECT_GIVE}>{L_OFF}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_TRADE}</span></td>
	  <td class="row2">
		<select name="shoptrade">
		  <option value="on">{L_ON}</option>
		  <option value="off" {SELECT_TRADE}>{L_OFF}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_DISCARD}</span></td>
	  <td class="row2">
		<select name="shopdiscard">
		  <option value="on">{L_ON}</option>
		  <option value="off" {SELECT_DISCARD}>{L_OFF}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row1" colspan="2" align="center">
		<input type="submit" class="liteoption" value="{L_UPDATE}" />
	  </td>
	</tr>
  </table>
</form>

<br /><br />

<form action="{S_CONFIG_ACTION}" name="post" method="post">
  <input type="hidden" name="action" value="editinventory" />
  <table width="50%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
   <tr>
     <th class="thHead" colspan="2">{L_TABLE_TITLE2}</th>
   </tr>
   <tr>
     <td class="row1"><input type="text" class="post" name="username" maxlength="25" size="25" /> <input type="submit" class="liteoption" value="{L_EDIT_INV}" /></td>
     <td class="row1"><input type="submit" name="usersubmit" value="{L_FIND_USER}" class="liteoption" onClick="window.open('./../search.php?mode=searchuser', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" /></td>
   </tr>
  </table>
</form>

<br /><br />

<form action="{S_CONFIG_ACTION}" method="post">
  <input type="hidden" name="action" value="editshop" />
  <table width="50%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_TABLE_TITLE3}</th>
	</tr>
	<tr>
	  <td class="row2">
		<select name="shopid">
<!-- BEGIN shop_listrow -->
		  <option value="{shop_listrow.ID}">{shop_listrow.NAME}</option>
<!-- END shop_listrow -->
		</select>
	  </td>
	  <td class="row2"><input type="submit" class="liteoption" value="{L_EDIT}" /></td>
	</tr>
	</form>

	<tr><td colspan="2" class="row3"><br /></td></tr>
<form action="{S_CONFIG_ACTION}" method="post">
    <input type="hidden" name="action" value="createshop">
	<tr>
	  <td class="row2"><span class="gensmall">{L_SHOP_NAME}</span></td>
	  <td class="row2"><input type="text" class="post" name="shopname" size="32" maxlength="32" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_SHOP_TYPE}</span></td>
	  <td class="row2"><input type="text" class="post" name="shoptype" size="32"  maxlength="32" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_RESTOCK_TIME}</span></td>
	  <td class="row2"><input type="text" class="post" name="restocktime" size="10" maxlength="10" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_RESTOCK_AMT}</span></td>
	  <td class="row2"><input type="text" class="post" name="restockamount" size="10"  maxlength="10" /></td>
	</tr>
	<tr>
	  <td class="row1" colspan="2" align="center"><input type="submit" class="liteoption" value="{L_CREATE_SHOP}" /></td>
	</tr>
  </table>
</form>
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
		<td width="100%" align="center" class="row1"><br /><span class="gensmall">Shop Modification: Copyright © 2003, 2006 <a href="http://www.zarath.com" class="navsmall">Zarath Technologies</a>.</span><br /><br /></td>
	</tr>
  </table>
<br	clear="all" />
