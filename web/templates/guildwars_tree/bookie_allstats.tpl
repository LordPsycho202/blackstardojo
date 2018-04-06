<form action="" method="post">
<table width="" cellspacing="2" cellpadding="2" border="0">
<tr>
	<td align="center">{NEW_BET}</td>
	<td align="center">{YOUR_STATS}</td>
	<td align="center">&nbsp;</td>
	<td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> :: <a href="{U_BOOKIES}" class="nav">{L_BOOKIES}</a> :: {L_BOOKIE_ALLSTATS}</span></td>
	</tr>
</table>
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr>
		<th height="25" class="thHead" nowrap="nowrap" colspan="8">{BOOKIES_ALLSTATS_HEADER}</th>
	</tr>
	<!-- BEGIN stats_available -->
	<tr>
		<td class="row1" align="left" colspan="8"><span class="gen">{ALL_COMPLETE_STATS}</span></td>
	</tr>
	<tr>
		<td class="catBottom" colspan="8"><span class="genmed">&nbsp;</span></td>
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
		<th height="25" class="thCornerL" nowrap="nowrap">{BOOKIES_SLIP_USERNAME}</th>
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_TIME_STATS}</th>
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_MEETING}</th>
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_SELECTION}</th>
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_STAKE}</th>
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_ODDS}</th>
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_RESULT}</th>
		<th class="thCornerR" nowrap="nowrap">{BOOKIES_SLIP_WINLOSS}</th>		
	</tr>
	<!-- BEGIN allstats -->
	<tr> 
		<td class="{allstats.ROW_CLASS}" align="left" valign="middle"><span class="gen"><b>{allstats.USERNAME}</b></span></td>
		<td class="{allstats.ROW_CLASS}" align="center" valign="middle"><span class="gen">{allstats.TIME}</span></td>
		<td class="{allstats.ROW_CLASS}" align="center" valign="middle"><span class="gen">{allstats.MEETING}</span></td>
		<td class="{allstats.ROW_CLASS}" align="center" valign="middle"><span class="gen">{allstats.SELECTION}</span></td>
		<td class="{allstats.ROW_CLASS}" align="center" valign="middle"><span class="gen">{allstats.STAKE}</span></td>
		<td class="{allstats.ROW_CLASS}" align="center" valign="middle"><span class="gen">{allstats.ODDS}</span></td>
		<td class="{allstats.ROW_CLASS}" align="center" valign="middle"><span class="gen">{allstats.RESULT}</span></td>
		<td class="{allstats.ROW_CLASS}" align="center" valign="middle"><span class="gen"><b>{allstats.WINLOSS}</b></span></td>
	</tr>
	<!-- END allstats -->
	<tr>
		<td class="catbottom" align="center" colspan="8" nowrap="nowrap"><span class="gen">{SWITCH_INFO}</span></td>
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


