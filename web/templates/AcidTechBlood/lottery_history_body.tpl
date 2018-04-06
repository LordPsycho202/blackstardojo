<form method="post" action="{S_MODE_ACTION}">
  <table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr> 
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a>{LOCATION}</span></td>
	</tr>
  </table>
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
<!-- BEGIN switch_title_info -->
	<tr>
	  <th class="thHead"><span class="gensmall"><b>{L_ID}</b></span></th><th class="thHead"><span class="gensmall"><b>{L_WINNER}</b></span></th><th class="thHead"><span class="gensmall"><b>{L_AMOUNT_WON}</b></span></th><th class="thHead"><span class="gensmall"><b>{L_TIME_WON}</b></span></th>
	</tr>
<!-- END switch_title_info -->
<!-- BEGIN listrow -->
	<tr>
	  <td class="row4" align="center"><span class="gen">{listrow.HISTORY_NUM}</span></td><td class="{listrow.ROW_CLASS}" align="center"><span class="gen">{listrow.HISTORY_WINNER}</span></td><td class="{listrow.ROW_CLASS}" align="center"><span class="gen">{listrow.HISTORY_AMOUNT} {listrow.HISTORY_CURRENCY}</span></td><td class="{listrow.ROW_CLASS}" align="center"><span class="gen">{listrow.HISTORY_TIME}</span></td>
	</tr>
<!-- END listrow -->
<!-- BEGIN switch_no_history -->
	<tr>
	  <td class="row1" align="center"><span class="gensmall">{switch_no_history.MESSAGE}</span></td>
	</tr>
<!-- END switch_no_history -->
  </table>
<!-- BEGIN switch_title_info -->
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
	  <td class="row3" align="center"><span class="gensmall">{L_TOTAL_HISTORY}</span></td>
	</tr>
  </table>

  <table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr> 
	  <td><span class="nav">{PAGE_NUMBER}</span></td>
	  <td align="right"><span class="gensmall">{S_TIMEZONE}</span><br /><span class="nav">{PAGINATION}</span></td>
	</tr>
  </table>
<!-- END switch_title_info -->
</form>
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
		<td width="100%" align="center" class="row2"><br><span class="gensmall">Lottery Modification: Copyright © 2004, <a href="http://www.zarath.com/mods/" class="navsmall">Zarath Technologies</a>.</span><br><br></td>
	</tr>
  </table>
<br	clear="all" />

