<h1>{HEADER}</h1>
<!-- BEGIN select_mode -->
<form action="{URL}" method="post">
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">	
	<tr>
		<th class="thTop" nowrap="nowrap" colspan="2">{SELECT_BY_TIME}</th>
	</tr>
	<tr>
		<td class="row1" align="left" width="50%"><span class="gen"><b><u>{SELECT_BY_TIME}</u></b><p>{SELECT_BY_TIME_EXP}</p></span></td>
		<td class="row2" align="center" valign="middle" width="50%">
			<table>
				<tr>
					<td>
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
		<td class="catBottom" align="center" colspan="2"><input type="submit" name="select_bet" value="{SUBMIT}" class="mainoption" /></td>
	</tr>
</table>
</form>
<!-- END select_mode -->

<!-- BEGIN edit_mode -->
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr>
		<th height="25" class="thCornerL" nowrap="nowrap" width="20%">{PROCESS_TIME}</th>
		<th class="thTop" nowrap="nowrap" width="20%">{PROCESS_MEETING}</th>
		<th class="thTop" nowrap="nowrap" width="25%">{PROCESS_SELECTION}</th>
		<th class="thTop" nowrap="nowrap" width="20%">{ODDS_ODDS}</th>
		<th class="thTop" nowrap="nowrap" width="10%">{WINNER}</th>
		<th class="thTop" nowrap="nowrap" width="5%">{PROCESS_GO}</th>
	</tr>
<!-- END edit_mode -->
<!-- BEGIN processbet -->
	<form action="{processbet.URL}" method="post">
	<tr> 
		<td class="{processbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet.TIME}</span></td>
		<td class="{processbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet.MEETING}</span></td>
		<td class="{processbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet.SELECTION}</span></td>
		<td class="{processbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet.ODDS}</span></td>
		<td class="{processbet.ROW_CLASS}" align="center" valign="middle">
			<table>
				<!-- BEGIN normal -->
				<tr>
					<td><input type="radio" name="{processbet.WINNER}" value="yes" {processbet.YES_CHECKED}/>
					<span class="gen">{L_YES}
					</span></td>
				</tr>
				<!-- END normal -->
				<!-- BEGIN eachway -->
				<tr>
					<td><input type="radio" name="{processbet.WINNER}" value="eww" {processbet.EWW_CHECKED} />
					<span class="gen">{L_EWW}</span></td>
				</tr>
				<tr>
					<td><input type="radio" name="{processbet.WINNER}" value="ewp" {processbet.EWP_CHECKED} />
					<span class="gen">{L_EWP}</span></td>
				</tr>
				<!-- END eachway -->
				<tr>
					<td><input type="radio" name="{processbet.WINNER}" value="no" {processbet.NO_CHECKED} />
					<span class="gen">{L_NO}</span></td>
				</tr>
				<tr>
					<td><input type="radio" name="{processbet.WINNER}" value="ref" {processbet.REF_CHECKED} />
					<span class="gen">{L_REF}</span></td>
				</tr>
			</table>
		</td>
		<td class="{processbet.ROW_CLASS}" align="center" valign="middle"><span class="gen"><input type="submit" name="{processbet.GO}" value="{PROCESS_GO}" class="mainoption" /></span></td>
	</tr>
	</form>
	<!-- END processbet -->
<!-- BEGIN edit_mode -->
</table>
<!-- END edit_mode -->


<br />
	<div align="center"><span class="copyright">Forum Bookmakers Mod {BOOKIE_VERSION} by Majorflam &copy 2004-2005 <a href="http://www.majormod.com" class="copyright">Major Mod - Software Modifications For phpBB2</a></span></div>
