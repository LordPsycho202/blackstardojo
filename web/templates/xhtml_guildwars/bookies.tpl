<form action="" method="post">
<table width="" cellspacing="2" cellpadding="2" border="0">
<tr>
	<td align="center">{NEW_BET}</td>
	<td align="center">{YOUR_STATS}</td>
	<td align="center">{ALL_STATS}</td>
	<td align="center">&nbsp;</td>
	<td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> :: {L_BOOKIES}</span></td>
</tr>
</table>
<!-- BEGIN switch_welcome_message -->
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr>
		<th height="25" class="thHead" nowrap="nowrap">{BOOKIES_HEADER}</th>
	</tr>
	<tr>
		<td class="row1" align="center"><span class="gen">{BOOKIES_WELCOME}</span></td>
	</tr>
</table>
<br />
<!-- END switch_welcome_message -->
<!-- Leaderboard -->
<!-- BEGIN switch_yes_stats -->
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr>
		<th height="25" class="thHead" nowrap="nowrap" colspan="6">{BOOKIES_LEADER_HEADER}</th>
	</tr>
	<tr>
		<td class="row1" align="center" colspan="6"><span class="gen">{LEADER_INFO}</span></td>
	</tr>
	<tr>
		<th height="25" class="thCornerL" nowrap="nowrap">#</th>
		<th class="thTop" nowrap="nowrap">{LEADER_USERNAME}</th>
		<th class="thTop" nowrap="nowrap">{LEADER_TOTALBETS}</th>
		<th class="thTop" nowrap="nowrap">{LEADER_TOTALWIN}</th>
		<th class="thTop" nowrap="nowrap">{LEADER_TOTALLOSE}</th>
		<th class="thCornerR" nowrap="nowrap">{LEADER_NETPOS}</th>		
	</tr>
<!-- END switch_yes_stats -->
<!-- BEGIN leader -->
	<tr> 
		<td class="{leader.ROW_CLASS}" align="center" valign="middle"><span class="gen"><b>{leader.POSITION}</b></span></td>
		<td class="{leader.ROW_CLASS}" align="left" valign="middle"><span class="gen">{leader.USERNAME}</span></td>
		<td class="{leader.ROW_CLASS}" align="center" valign="middle"><span class="gen">{leader.TOTALBETS}</span></td>
		<td class="{leader.ROW_CLASS}" align="center" valign="middle"><span class="gen">{leader.TOTALWIN}</span></td>
		<td class="{leader.ROW_CLASS}" align="center" valign="middle"><span class="gen">{leader.TOTALLOSE}</span></td>
		<td class="{leader.ROW_CLASS}" align="center" valign="middle"><span class="gen"><b>{leader.NETPOS}</b></span></td>
	</tr>
	<!-- END leader -->
<!-- BEGIN switch_yes_stats -->
	<tr>
		
		<th class="thTop" nowrap="nowrap" colspan="2">BOOKMAKER TOTALS</th>
		<th class="thTop" nowrap="nowrap">{BK_TOTALBETS}</th>
		<th class="thTop" nowrap="nowrap">{BK_LOSSES}</th>
		<th class="thTop" nowrap="nowrap">{BK_WINNINGS}</th>
		<th class="thCornerR" nowrap="nowrap">{BK_NET_POSITION}</th>		
	</tr>
</table>
<br />
<!-- END switch_yes_stats -->
<!-- Leaderboard finish -->
<!-- BEGIN switch_bets_pending -->
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr>
		<th height="25" class="thHead" nowrap="nowrap" colspan="6"><span class="name"><a name="{PENDING_ANCHOR}"></a></span>{PENDING_HEADER}</th>
	</tr>
	<tr>
		<td class="row1" align="center" colspan="6"><span class="gen">{PENDING_INFO}<br /><br />{POINTS_INFO}</span></td>
	</tr>
	<tr>
		<th height="25" class="thCornerL" nowrap="nowrap">~</th>
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_MEETING}</th>
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_DATE}</th>
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_SELECTION}</th>
		<th class="thTop" nowrap="nowrap">{BOOKIE_SLIP_ODDS}</th>
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_STAKE}</th>	
	</tr>
<!-- END switch_bets_pending -->
<!-- BEGIN pending -->
	<tr>
		<td class="{pending.ROW_CLASS}" align="center" valign="middle"><span class="gen">{pending.DEL_IMG}</span></td>
		<td class="{pending.ROW_CLASS}" align="center" valign="middle"><span class="gen">{pending.MEETING}</span></td>
		<td class="{pending.ROW_CLASS}" align="center" valign="middle"><span class="gen">{pending.DATE}</span></td>
		<td class="{pending.ROW_CLASS}" align="center" valign="middle"><span class="gen">{pending.SELECTION}</span></td>
		<td class="{pending.ROW_CLASS}" align="center" valign="middle"><span class="gen">{pending.ODDS}</span></td>
		<td class="{pending.ROW_CLASS}" align="center" valign="middle">
			<table>
				<tr>
					<td><span class="gen">{pending.STAKE_BOX_PEND}</span></td>
					<td><span class="gensmall">&nbsp;<b>{POINTS_NAME}</b></span></td>
				<tr>
			</table>
		</td>
	</tr>
	<!-- END pending -->
<!-- BEGIN switch_bets_pending -->
	<tr>
		<td class="catbottom" align="center" valign="middle" colspan="6"><input type="submit" name="change_stake" value="{BOOKIES_CHANGE_STAKE}" class="mainoption" />
		</td>
	</tr>
</table>
<table width="" cellspacing="2" cellpadding="2" border="0">
<tr>
	<td align="center">{NEW_BET}</td>
	<td align="center">{YOUR_STATS}</td>
	<td align="center">{ALL_STATS}</td>
	<td align="center">&nbsp;</td>
	<td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> :: {L_BOOKIES}</span></td>
</tr>
</table>
<!-- END switch_bets_pending -->
<br />
<div align="center"><span class="copyright">Forum Bookmakers Mod {BOOKIE_VERSION} by Majorflam &copy 2004-2005</span></div>
