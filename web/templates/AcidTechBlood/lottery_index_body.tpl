  <table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr> 
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{S_CONFIG_ACTION}" class="nav">{L_NAME}</a> {LAST_LOCATION}

</span></td>
	</tr>
  </table>
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_INFO_TITLE}</th>
	</tr>
	<tr>
	  <td class="row1"><span class="gen">{L_TICKET_OWNED}</span></td>
	  <td class="row1"><span class="gen">{TICKETS_OWNED}</span></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gen">{L_TICKETS_COST}</span></td>
	  <td class="row2"><span class="gen">{L_TICKET_COST} {L_CURRENCY}</span></td>
	</tr>
	<tr>
	  <td class="row1"><span class="gen">{L_BASE_POOL}</span></td>
	  <td class="row1"><span class="gen">{L_PRIZE_BASE} {L_CURRENCY}</span></td>
	</tr>
<!-- BEGIN switch_full_display -->
	<tr>
	  <td class="row2"><span class="gen">{L_CURRENT_POOL}</span></td>
	  <td class="row2"><span class="gen">{L_CURRENT_ENTRIES}</span></td>
	</tr>
	<tr>
	  <td class="row1"><span class="gen">{L_TOTAL_POOL}</span></td>
	  <td class="row1"><span class="gen">{L_TOTAL_PRIZE} {L_CURRENCY}</span></td>
	</tr>
<!-- END switch_full_display -->
<!-- BEGIN switch_items -->
	<tr>
	  <td class="row2"><span class="gen">{L_ITEM_DRAW}</span></td>
	  <td class="row2"><span class="gen">{L_ITEM_PRIZE}</span></td>
	</tr>
<!-- END switch_items -->
	<tr>
	  <td class="row2"><span class="gen">{L_TIME_DRAW}</span></td>
	  <td class="row2"><span class="gen">{L_DURATION}</span></td>
	</tr>
<!-- BEGIN switch_last_winner -->
	<tr>
	  <td class="row1"><span class="gen">{L_LAST_WINNER}</span></td>
	  <td class="row1"><span class="nav"><b>{switch_last_winner.WINNER_NAME}</b></span></td>
	</tr>
<!-- END switch_last_winner -->
  </table>

<br /><br />
<!-- BEGIN switch_are_actions -->
<form method="post" action="{S_MODE_CONFIG}">
<input type="hidden" name="action" value="options" />
  <table width="455" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_ACTIONS_TITLE}</th>
	</tr>
<!-- END switch_are_actions -->
<!-- BEGIN switch_tickets_single -->
	<tr>
	  <td class="row2" align="center" colspan="2"><input type="submit" name="buy_ticket" value="{I_BUY_TICKET}" class="liteoption" /></td
	</tr>
<!-- END switch_tickets_single -->
<!-- BEGIN switch_tickets_multi -->
	<tr>
	  <td class="row1" align="center" colspan="2"><input type="hidden" name="amount" value="1" /> <input type="submit" name="buy_tickets" value="{I_BUY_TICKETS}" class="liteoption" /></td>
	</tr>
<!-- END switch_tickets_multi -->
<!-- BEGIN switch_view_history -->
	<tr>
	  <td class="row2" align="center" colspan="2"><input type="submit" name="view_history" value="{I_VIEW_HISTORY}" class="liteoption" /></td
	</tr>
<!-- END switch_view_history -->
<!-- BEGIN switch_view_personal -->
	<tr>
	  <td class="row1" align="center" colspan="2"><input type="submit" name="view_personal" value="{I_VIEW_PHISTORY}" class="liteoption" /></td
	</tr>
<!-- END switch_view_personal -->
<!-- BEGIN switch_are_actions -->
  </table>
</form>
<!-- END switch_are_actions -->
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
		<td width="100%" align="center" class="row2"><br><span class="gensmall">Lottery Modification: Copyright © 2004, <a href="http://www.zarath.com/mods/" class="navsmall">Zarath Technologies</a>.</span><br><br></td>
	</tr>
  </table>
<br	clear="all" />