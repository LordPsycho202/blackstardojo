<table width="" cellspacing="2" cellpadding="2" border="0">
<tr>
	<td align="center">{YOUR_STATS}</td>
	<td align="center">{ALL_STATS}</td>
	<td align="center">&nbsp;</td>
	<td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> :: <a href="{U_BOOKIES}" class="nav">{L_BOOKIES}</a> :: {L_PLACE_BET}</span></td>
</tr>
</table>
<!-- BEGIN switch_user_bets_on -->
<br />
<form action="" method="post">
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr>
		<th height="25" class="thHead" nowrap="nowrap" colspan="2">{ENTER_DETAILS}</th>
	</tr>
	<!-- BEGIN timezone_warning -->
	<tr>
		<td class="row1" colspan="2"><span class="genmed">{WARNING}</span></td>
	</tr>
	<!-- END timezone_warning -->
	<tr>
		<td class="row2" width="50%" align="left"><span class="gen"><b><u>{TIME_MEETING}</b></u></span>
		<br /><br /><span class="genmed">{TIME_MEETING_EXPLAIN}</span></td>
		<td class="row2" width="50%" align="center">
			<table width="100%" align="center" border="0">
				<tr>
					<td align="right">
						<table>
							<tr>
								<td><span class="gen"><select name="bet_day">{DAY_BOX}</span></td>
								<td><span class="gen"><b>&nbsp;-&nbsp;</b></span></td>
								<td><span class="gen"><select name="bet_month">{MONTH_BOX}</span></td>
								<td><span class="gen"><b>&nbsp;-&nbsp;</b></span></td>
								<td><span class="gen"><select name="bet_year">{YEAR_BOX}</span></td>
							</tr>
						</table>
					</td>
					<td align="center"><span class="gen">@</span></td>
					<td align="left">
						<table>
							<tr>
								<td><span class="gen"><select name="bet_hour">{BETHOUR_BOX}</span></td>
								<td><span class="gen"><b>&nbsp;:&nbsp;</b></span></td>
								<td><span class="gen"><select name="bet_minute">{BETMINUTE_BOX}</span></td>							
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="row1" width="50%" align="left"><span class="gen"><b><u>{MEETING}</u></b></span>
		<br /><br /><span class="genmed">{MEETING_EXPLAIN}</span></td>
		<td class="row1" width="50%" align="center" valign="middle">
			<table>
				<tr>
					<td width="100%" align="center"><input class="post" type="text" style="width: 300px" maxlength="50" size="11" name="bet_meeting" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="row2" width="50%" align="left"><span class="gen"><b><u>{SELECTION}</u></b></span>
		<br /><br /><span class="genmed">{SELECTION_EXPLAIN}</span></td>
		<td class="row2" width="50%" align="center" valign="middle">
			<table>
				<tr>
					<td width="100%" align="center"><input class="post" type="text" style="width: 300px" maxlength="150" size="11" name="bet_selection" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="row1" width="50%" align="left"><span class="gen"><b><u>{STAKE}</u></b></span>
		<br /><br /><span class="genmed">{STAKE_EXPLAIN}</span></td>
		<td class="row1" width="50%" align="center" valign="middle">
			<table>
				<tr>
					<td width="100%" align="center"><input class="post" type="text" style="width: 50px" maxlength="6" size="11" name="bet_stake" /><span class="genmed">&nbsp;<b>{POINTS_NAME}</b>&nbsp;({ON_HAND})</span></td>
				</tr>
			</table>
		</td>
	</tr>
	<!-- BEGIN switch_eachway_allowed -->
	<tr> 
	  	<td class="row2" width="50%"><span class="gen"><b><u>{EACHWAY_BET}</u></b></span>
		<br /><br /><span class="genmed">{EACHWAY_BET_EXP}</span></td>
	  	<td class="row2" width="50%" align="center" valign="middle"> 
		<input type="radio" name="eachwaybet" value="1" />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
		<input type="radio" name="eachwaybet" value="0" checked="checked" />
		<span class="gen">{L_NO}</span></td>
	</tr>
	<!-- END switch_eachway_allowed -->
	<tr>
		<td class="catBottom" align="center" colspan="2"><input type="submit" name="user_placebet" value="{BOOKIES_PLACE_BET}" class="mainoption" /></td>
	</tr>
</table>
</form>
<br />
<!-- END switch_user_bets_on -->
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<!-- BEGIN switch_yes_bets -->
	<tr>
		<th height="25" class="thHead" nowrap="nowrap" colspan="5">{BET_HEADER}</th>
	</tr>
	<tr>
		<td class="row1" align="center" colspan="5"><span class="gen">{BET_INSTR}{USER_BET_INSTR}<br /><br />{POINTS_INFO}</span></td>
	</tr>
	<!-- END switch_yes_bets -->
	<!-- BEGIN switch_no_bets -->
	<tr>
		<th height="25" class="thHead" nowrap="nowrap" colspan="">{BET_HEADER_NONE_TITLE}</th>
	</tr>
	<tr>
		<td class="row1" align="center"><span class="gen"><br />&nbsp;<br />{BET_HEADER_NONE}&nbsp;{USER_BET_INSTR_DEFAULT}<br /><br />&nbsp;</span></td>
	</tr>
		<!-- END switch_no_bets -->
	<!-- BEGIN switch_yes_bets -->
	<form action="" method="post">
	<tr>
		<th height="25" class="thCornerL" nowrap="nowrap">{BOOKIES_SLIP_MEETING}</th>
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_DATE}</th>
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_SELECTION}</th>
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_STAKE}</th>
	<!-- BEGIN switch_each_way -->
		<th class="thTop" nowrap="nowrap">{BOOKIES_SLIP_EACH_WAY}</th>
	<!-- END switch_each_way -->	
	</tr>
	<!-- END switch_yes_bets -->
	<!-- BEGIN cats -->
	<tr>
		<td class="catLeft" colspan="5"><span class="name"><a name="{cats.ANCHOR}"></a></span><span class="cattitle">{cats.CAT}</span></td>
	</tr>
	<!-- BEGIN bets -->
	<tr> 
		<td class="{cats.bets.ROW_CLASS}" align="left" valign="middle">
			<table>
				<tr>
					<td align="center">{cats.bets.STAR}</td>
					<td align="left"><span class="gen"><b>{cats.bets.MEETING}</b></span></td>
				<tr>
			</table>
		</td>
		<td class="{cats.bets.ROW_CLASS}" align="center" valign="middle"><span class="gen"><b>{cats.bets.DATE}</b></span></td>
		<td class="{cats.bets.ROW_CLASS}" align="center" valign="middle"><span class="gen"><select name="{cats.bets.SELECT_NAME}">{cats.bets.SELECTION}</span></td>
		<td class="{cats.bets.ROW_CLASS}" align="center" valign="middle">
			<table>
				<tr>
					<td align="center">{cats.bets.STAKE_BOX}</td>
					<td align="center"><span class="genmed"><b>{POINTS_NAME}</b></span></td>
				</tr>
			</table>
		</td>
		<!-- BEGIN switch_this_each_way -->
		<td class="{cats.bets.ROW_CLASS}" align="center" valign="middle">{cats.bets.EACH_WAY_CHECKBOX}</td>
		<!-- END switch_this_each_way -->
	</tr>
	<!-- END bets -->
	<!-- END cats -->
	<!-- BEGIN switch_yes_bets -->
	<tr>
		<td class="catbottom" align="center" valign="middle" colspan="5">
		<!-- BEGIN catview -->
		<input type="submit" name="placebet" value="{BOOKIES_PLACE_BET}" class="mainoption" />
		<!-- END catview -->
		</td>
	</tr>
	</form>
	<!-- END switch_yes_bets -->
</table>
</form>
<br />
<div align="center"><span class="copyright">Forum Bookmakers Mod {BOOKIE_VERSION} by Majorflam &copy 2004-2005</span></div>