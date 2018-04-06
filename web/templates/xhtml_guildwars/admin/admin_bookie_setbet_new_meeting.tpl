<form action="{URL}" method="post">
	<h1>{HEADER}</h1>
	<p>{EXPLAIN}</p>
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr>
		<th height="25" class="thHead" nowrap="nowrap" colspan="2">{ENTER_DETAILS}</th>
	</tr>
	<tr>
		<td class="row1" width="50%" align="left"><span class="gen"><b><u>{CATEGORY}</u></b></span>
		<p>{CATEGORY_EXPLAIN}</p></td>
		<td class="row2" width="50%" align="center" valign="middle"><select name="selected_category">{CATEGORY_BOX}</td>
	</tr>
	<tr>
		<td class="row1" width="50%" align="left"><span class="gen"><b><u>{TIME_MEETING}</b></u></span>
		<p>{TIME_MEETING_EXPLAIN}</p></td>
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
		<p>{MEETING_EXPLAIN}</p></td>
		<td class="row2" width="50%" align="center" valign="middle">
			<table>
				<tr>
					<td width="100%" align="center"><input class="post" type="text" style="width: 300px" maxlength="50" size="11" name="bet_meeting" /></td>
				</tr>
				<tr>
					<td width="100%" align="center"><select name="selected_meeting">{MEETING_BOX}</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr> 
	  	<td class="row1" width="50%"><span class="gen"><b><u>{STAR_BET}</u></b></span><br /><span class="gensmall"><p>{STAR_BET_EXP}</p></span></td>
	  	<td class="row2" width="50%" align="center" valign="middle"> 
		<input type="radio" name="starbet" value="1" />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
		<input type="radio" name="starbet" value="0" checked="checked" />
		<span class="gen">{L_NO}</span>
		</td>
	</tr>
	<!-- BEGIN eachway_allowed -->
	<tr> 
	  	<td class="row1" width="50%"><span class="gen"><b><u>{EACHWAY_BET}</u></b></span><br /><span class="gensmall"><p>{EACHWAY_BET_EXP}</p></span></td>
	  	<td class="row2" width="50%" align="center" valign="middle"> 
		<input type="radio" name="eachwaybet" value="1" />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
		<input type="radio" name="eachwaybet" value="0" checked="checked" />
		<span class="gen">{L_NO}</span></td>
	</tr>
	<!-- END eachway_allowed -->
	<tr>
		<td class="catBottom" align="center" colspan="2"><input type="submit" name="submit" value="{SUBMIT}" class="mainoption" /></td>
	</tr>
</table>
</form>
<br />
<div align="center"><span class="copyright">Forum Bookmakers Mod {BOOKIE_VERSION} by Majorflam &copy 2004-2005 <a href="http://www.majormod.com" class="copyright">Major Mod - Software Modifications For phpBB2</a></span></div>

