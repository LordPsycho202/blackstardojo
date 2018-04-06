<form action="{URL}" method="post">
<h1>{EDIT_HEADER}</h1>
<p>{EDIT_HEADER_EXPLAIN}</p>
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">	
	<tr>
		<th height="25" class="thCornerL" nowrap="nowrap">{TIME}</th>
		<th class="thTop" nowrap="nowrap">{MEETING}</th>
		<th class="thTop" nowrap="nowrap">{SELECTION}</th>
		<th class="thTop" nowrap="nowrap">{ODDS}</th>	
	</tr>
	<!-- BEGIN editbet -->
	{editbet.SPLIT_ROW}
	<tr>
		<td class="{editbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">
 			<table width="100%" align="center" border="0">
				<tr>
					<td align="right">
						<table>
							<tr>
								<td><span class="gen"><select name="bet_day">{editbet.DAY_BOX}</span></td>
								<td><span class="gen"><b>&nbsp;-&nbsp;</b></span></td>
								<td><span class="gen"><select name="bet_month">{editbet.MONTH_BOX}</span></td>
								<td><span class="gen"><b>&nbsp;-&nbsp;</b></span></td>
								<td><span class="gen"><select name="bet_year">{editbet.YEAR_BOX}</span></td>
							</tr>
						</table>
					</td>
					<td align="center"><span class="gen">@</span></td>
					<td align="left">
						<table>
							<tr>
								<td><span class="gen"><select name="bet_hour">{editbet.BETHOUR_BOX}</span></td>
								<td><span class="gen"><b>&nbsp;:&nbsp;</b></span></td>
								<td><span class="gen"><select name="bet_minute">{editbet.BETMINUTE_BOX}</span></td>							
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center" colspan="3"><span class="gen">({editbet.CURRENT_TIME})</span></td>
				</tr>
			</table>
		</td>
		<td class="{editbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{editbet.MEETING}</span></td>
		<td class="{editbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{editbet.SELECTION}</span></td>
		<td class="{editbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{editbet.ODDS}</span></td>
	</tr>
	<tr> 
	  	<td class="row1" colspan="2"><span class="gen"><b><u>{editbet.CATEGORY}</u></b></span><br /><span class="gensmall"><p>{editbet.CATEGORY_EXPLAIN}</p></span></td>
	  	<td class="row1" colspan="2" align="center" valign="middle"><select name="edit_category">{editbet.CATEGORY_BOX}</td>
	</tr>
	<tr> 
	  	<td class="row2" colspan="2"><span class="gen"><b><u>{editbet.STAR_BET}</u></b></span><br /><span class="gensmall"><p>{editbet.STAR_BET_EXP}</p></span></td>
	  	<td class="row2" colspan="2" align="center" valign="middle"> 
		<input type="radio" name="starbet" value="1" {editbet.STAR_BET_ON} />
		<span class="gen">{editbet.L_YES}</span>&nbsp;&nbsp; 
		<input type="radio" name="starbet" value="0" {editbet.STAR_BET_OFF} />
		<span class="gen">{editbet.L_NO}</span></td>
	</tr>
	<!-- BEGIN eachway_allowed -->
	<tr> 
	  	<td class="row1" colspan="2"><span class="gen"><b><u>{editbet.EACHWAY_BET}</u></b></span><br /><span class="gensmall"><p>{editbet.EACHWAY_BET_EXP}</p></span></td>
	  	<td class="row1" colspan="2" align="center" valign="middle"> 
		<input type="radio" name="eachwaybet" value="1" {editbet.EACHWAY_BET_ON} />
		<span class="gen">{editbet.L_YES}</span>&nbsp;&nbsp; 
		<input type="radio" name="eachwaybet" value="0" {editbet.EACHWAY_BET_OFF} />
		<span class="gen">{editbet.L_NO}</span></td>
	</tr>
	<!-- END eachway_allowed -->
	<!-- END editbet -->
	<tr>
		<td class="catbottom" align="center" valign="middle" colspan="4">
			<table>
				<tr>
					<td><input type="submit" name="editbet" value="{SUBMIT}" class="mainoption" /></td>
					<td><input type="submit" name="cancel" value="{CANCEL}" class="mainoption" /></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br />
{SELECTION_REVIEW}
<br />
<div align="center"><span class="copyright">Forum Bookmakers Mod {BOOKIE_VERSION} by Majorflam &copy 2004-2005 <a href="http://www.majormod.com" class="copyright">Major Mod - Software Modifications For phpBB2</a></span></div>

