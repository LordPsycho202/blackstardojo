<form action="" method="post">
<table width="" cellspacing="2" cellpadding="2" border="0">
<tr>
	<td align="center">{NEW_BET}</td>
	<!-- BEGIN viewmode -->
	<td align="center">{YOUR_STATS}</td>
	<!-- END viewmode -->
	<td align="center">{ALL_STATS}</td>
	<td align="center">&nbsp;</td>
	<td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> :: <a href="{U_BOOKIES}" class="nav">{L_BOOKIES}</a> :: {L_BOOKIE_YOURSTATS}</span></td>
	</tr>
</table>
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr>
		<th height="25" class="thHead" nowrap="nowrap" colspan="7">{BOOKIES_YOURSTATS_HEADER}&nbsp;({USER_NAME})</th>
	</tr>
	<!-- BEGIN stats_available -->
	<tr>
		<td class="row1" align="left" colspan="7"><span class="gen">{YOUR_COMPLETE_STATS}</span></td>
	</tr>
	<tr>
		<td class="catBottom" colspan="7"><span class="genmed">&nbsp;</span></td>
	</tr>
</table>
<table width="100%" cellspacing="0" cellpadding="3" border="0"> 
	<tr> 
		<td><span class="gen">{PAGE_NUMBER}</span></td>
		<td align="right" nowrap="nowrap"><span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;
		<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" />
		</span>
		</td>
		<td align="right"><span class="gen">{PAGINATION}</span></td> 
	</tr> 
</table> 
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<!-- END stats_available -->
	<tr>
		<th height="25" class="thCornerL" nowrap="nowrap">{BOOKIES_SLIP_TIME_STATS}</th>
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_MEETING}</th>
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_SELECTION}</th>
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_STAKE}</th>
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_ODDS}</th>
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_RESULT}</th>
		<th class="thCornerR" nowrap="nowrap">{BOOKIES_SLIP_WINLOSS}</th>		
	</tr>

	<!-- BEGIN yourstats -->
	<tr> 
		<td class="{yourstats.ROW_CLASS}" align="left" valign="middle"><span class="gen">{yourstats.TIME}</span></td>
		<td class="{yourstats.ROW_CLASS}" align="center" valign="middle"><span class="gen">{yourstats.MEETING}</span></td>
		<td class="{yourstats.ROW_CLASS}" align="center" valign="middle"><span class="gen">{yourstats.SELECTION}</span></td>
		<td class="{yourstats.ROW_CLASS}" align="center" valign="middle"><span class="gen">{yourstats.STAKE}</span></td>
		<td class="{yourstats.ROW_CLASS}" align="center" valign="middle"><span class="gen">{yourstats.ODDS}</span></td>
		<td class="{yourstats.ROW_CLASS}" align="center" valign="middle"><span class="gen">{yourstats.RESULT}</span></td>
		<td class="{yourstats.ROW_CLASS}" align="center" valign="middle"><span class="gen"><b>{yourstats.WINLOSS}</b></span></td>
	</tr>
	<!-- END yourstats -->
	<tr>
		<td class="catbottom" colspan="7" nowrap="nowrap">&nbsp;</td>
	</tr>
</table>
<table width="100%" cellspacing="0" cellpadding="3" border="0"> 
	<tr> 
		<td><span class="gen">{PAGE_NUMBER}</span></td> 
		<td align="right"><span class="gen">{PAGINATION}</span></td> 
	</tr> 
</table>
</form>
<br />
<div align="center"><span class="copyright">Forum Bookmakers Mod {BOOKIE_VERSION} by Majorflam &copy 2004-2005</span></div>
