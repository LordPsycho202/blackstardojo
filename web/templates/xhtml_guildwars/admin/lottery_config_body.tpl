<h1>{TITLE}</h1>
<p>{EXPLAIN}</p>
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_TABLE_TITLE}</th>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_ENTRIES_TOTAL}</span></td>
	  <td class="row1"><span class="gensmall">{L_TOTAL_ENTRIES}</span></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_DURATION_LEFT}</span></td>
	  <td class="row2"><span class="gensmall">{L_DURATION}</span></td>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_LEFT_TIME}</span></td>
	  <td class="row1"><span class="gensmall">{L_TIME_LEFT} [{L_SECONDS}]</span></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_LOTTERY_POOL}</span></td>
	  <td class="row2"><span class="gensmall">{L_POOL}</span></td>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_WON_LAST}</span></td>
	  <td class="row1"><span class="gensmall">{L_LAST_WON}</span></td>
	</tr>
  </table>

<br /><br />

<form action="{S_CONFIG_ACTION}" method="post">
<input type="hidden" name="action" value="update" />
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_CONFIG_TITLE}</th>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_LOTTERY_STATUS}</span></td>
	  <td class="row1">
		<select name="status">
		  <option value="1" {L_STATUS_ON}>On</option>
		  <option value="0" {L_STATUS_OFF}>Off</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_AUTO_RESTART}</span></td>
	  <td class="row2">
		<select name="restart">
		  <option value="1" {L_RESET_ON}>{L_ON}</option>
		  <option value="0" {L_RESET_OFF}>{L_OFF}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_NAME}</span></td>
	  <td class="row1"><input type="text" name="name" size="32" value="{V_L_NAME}" maxlength="32" class="post" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_BASE_AMOUNT}</span></td>
	  <td class="row2"><input type="text" name="baseamount" size="10" value="{V_L_BASE}" maxlength="15" class="post" /></td>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_ENTRY_COST}</span></td>
	  <td class="row1"><input type="text" name="ticketcost" size="10" value="{V_L_COST}" maxlength="15" class="post" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_DRAW_PERIODS}</span></td>
	  <td class="row2"><input type="text" name="drawperiod" value="{V_L_LENGTH}" size="10" maxlength="15" class="post" /> Seconds</td>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_TICKETS_ALLOWED}</span></td>
	  <td class="row1">
		<select name="tickets">
		  <option value="single" {L_TICKTYPE_SING}>{L_SINGLE}</option>
		  <option value="multiple" {L_TICKTYPE_MULT}>{L_MULTIPLE}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_MULT_TICKETS}</span></td>
	  <td class="row2">
		<select name="multi_tickets">
		  <option value="1" {L_MULTI_TICKETS_ON}>{L_ON}</option>
		  <option value="0" {L_MULTI_TICKETS_OFF}>{L_OFF}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_MULT_TICKETS} [{L_MAX}]</span></td>
	  <td class="row1"><input type="text" name="multi_tickets_max" value="{L_MULTI_TICKETS_MAX}" size="10" maxlength="15" class="post" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_FULL_DISPLAY}</span></td>
	  <td class="row2">
		<select name="full_display">
		  <option value="1" {L_DISPLAY_ON}>{L_ON}</option>
		  <option value="0" {L_DISPLAY_OFF}>{L_OFF}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_ITEM_POOL}</span></td>
	  <td class="row1">
		<select name="item_pool">
		  <option value="1" {L_ITEMS_ON}>{L_ON}</option>
		  <option value="0" {L_ITEMS_OFF}>{L_OFF}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_HISTORY}</span></td>
	  <td class="row2">
		<select name="history">
		  <option value="1" {L_HISTORY_ON}>{L_ON}</option>
		  <option value="0" {L_HISTORY_OFF}>{L_OFF}</option>
		</select>
	  </td>
	</tr>
<!-- BEGIN switch_cash -->
	<tr>
	  <td class="row1"><span class="gensmall">{L_CURRENCY}</span></td>
	  <td class="row1">
		<select name="cash_name">
<!-- END switch_cash -->
<!-- BEGIN cash_listrow -->
			<option value="{cash_listrow.CASH_NAME}">{cash_listrow.CASH_NAME}</option>
<!-- END cash_listrow -->
<!-- BEGIN switch_cash -->
		</select>
	  </td>
	</tr>
<!-- END switch_cash -->
	<tr>
	  <td class="row1" colspan="2" align="center"><input type="submit" value="{L_UPDATE}" name="Update" class="liteoption"></td>
	</tr>
  </table>
</form>

<br /><br />

<!-- BEGIN switch_enabled_items -->
<form action="{S_CONFIG_ACTION}" method="post">
<input type="hidden" name="action" value="rand_settings" />
  <table width="450" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_RAND_ITEMS_TITLE}</th>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_FROM_SHOP}</span></td>
	  <td class="row1">
		<select name="rand_shop">
		 	<option value="">{L_ALL_SHOPS}</option>
<!-- END switch_enabled_items -->
<!-- BEGIN rand_listrow -->
			<option value="{rand_listrow.SHOP_NAME}" {rand_listrow.SELECTED}>{rand_listrow.SHOP_NAME}</option>
<!-- END rand_listrow -->
<!-- BEGIN switch_enabled_items -->
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_MIN_COST}</span></td>
	  <td class="row2"><input type="text" name="rand_min_cost" value="{L_RAND_COST_MIN}" size="10" maxlength="15" class="post" /></td>
	</tr>
	<tr>
	  <td class="row1"><span class="gensmall">{L_MAX_COST}</span></td>
	  <td class="row1"><input type="text" name="rand_max_cost" value="{L_RAND_COST_MAX}" size="10" maxlength="15" class="post" /></td>
	</tr>
	<tr>
	  <td class="row2" colspan="2" align="center"><input type="submit" value="{L_UPDATE_SETTINGS}" name="update" class="liteoption"></td>
	</tr>
  </table>
</form>

<br /><br />

<form action="{S_CONFIG_ACTION}" method="post">
<input type="hidden" name="action" value="item_pool" />
  <table width="450" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_ITEMS_TITLE}</th>
	</tr>
<!-- END switch_enabled_items -->
<!-- BEGIN switch_pool_items -->
	<tr>
	  <td class="row1"><span class="gensmall">{L_CURRENT_ITEMS}</span></td>
	  <td class="row1">
		<select name="item_name">
<!-- END switch_pool_items -->
<!-- BEGIN pool_listrow -->
			<option value="{pool_listrow.ITEM_NAME}">{pool_listrow.ITEM_NAME}</option>
<!-- END pool_listrow -->
<!-- BEGIN switch_pool_items -->
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2" colspan="2" align="center"><input type="submit" value="{L_REMOVE_ITEM}" name="del_item" class="liteoption"></td>
	</tr>
	<tr>
	  <td class="row1" colspan="2"><br /></td>
	</tr>
<!-- END switch_pool_items -->
<!-- BEGIN switch_are_items -->
	<tr>
	  <td class="row2"><span class="gensmall">{L_ADD_ITEMS}</span></td>
	  <td class="row2">
		<select name="item_id">
			<option value="random">{L_RANDOM}</option>
<!-- END switch_are_items -->
<!-- BEGIN item_listrow -->
			<option value="{item_listrow.ITEM_ID}">{item_listrow.ITEM_NAME}</option>
<!-- END item_listrow -->
<!-- BEGIN switch_are_items -->
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row1" colspan="2" align="center"><input type="submit" value="{L_ADD_ITEM}" name="add_item" class="liteoption"></td>
	</tr>
<!-- END switch_are_items -->
<!-- BEGIN switch_no_items -->
	<tr>
	  <td class="row2" colspan="2" align="center"><span class="gen">{switch_no_items.MESSAGE}</span></td>
	</tr>
<!-- END switch_no_items -->
<!-- BEGIN switch_enabled_items -->
  </table>
</form>
<!-- END switch_enabled_items -->
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
		<td width="100%" align="center" class="row2"><br><span class="gensmall">Lottery Modification: Copyright © 2004, <a href="http://www.zarath.com/mods/" class="navsmall">Zarath Technologies</a>.</span><br><br></td>
	</tr>
  </table>
<br	clear="all" />
