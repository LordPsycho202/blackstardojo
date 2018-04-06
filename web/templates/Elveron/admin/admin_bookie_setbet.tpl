<form action="" method="post">
<h1>{SET_HEADER}</h1>
<p>{SET_HEADER_EXPLAIN}</p>
<p>{DEF_DATE_EXPLAIN}</p>
<table width="100%" cellpadding="3" cellspacing="1" border="0">
	<tr>
		<td align="left">{IMG_NEW_MEETING}</td>
	</tr>
</table>
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr>
		<th class="thTop" nowrap="nowrap" colspan="2">{DEFAULT_DATE}</th>
	</tr>
	<tr>
		<td class="row1" width="50%" align="left"><span class="gen"><b><u>{DEFAULT_VARS}</b></u></span>
		<br /><br /><span class="genmed">{DEFAULT_DATE_EXPLAIN}</span></td>
		<td class="row2" width="50%" align="center">
			<table width="100%" align="center" border="0">
				<tr>
					<td align="right">
						<table>
							<tr>
								<td><span class="gen"><select name="def_day">{DAY_BOX}</span></td>
								<td><span class="gen"><b>&nbsp;-&nbsp;</b></span></td>
								<td><span class="gen"><select name="def_month">{MONTH_BOX}</span></td>
								<td><span class="gen"><b>&nbsp;-&nbsp;</b></span></td>
								<td><span class="gen"><select name="def_year">{YEAR_BOX}</span></td>
							</tr>
						</table>
					</td>
					<td align="center"><span class="gen">@</span></td>
					<td align="left">
						<table>
							<tr>
								<td><span class="gen"><select name="def_hour">{BETHOUR_BOX}</span></td>
								<td><span class="gen"><b>&nbsp;:&nbsp;</b></span></td>
								<td><span class="gen"><select name="def_minute">{BETMINUTE_BOX}</span></td>							
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="row1" width="50%" align="left"><span class="gen"><b><u>{DEFAULT_CAT}</b></u></span>
		<br /><br /><span class="genmed">{DEFAULT_CAT_EXPLAIN}</span></td>
		<td class="row2" width="50%" align="center"><span class="gen"><select name="def_cat">{CATEGORY_BOX}</span></td>
	</tr>
	<tr>
		<td class="catBottom" align="center" colspan="2"><input type="submit" name="update_def_date" value="{UPDATE}" class="mainoption" /></td>
	</tr>
</table>
</form>
<!-- BEGIN switch_no_bets -->
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr>
		<th class="thTop" nowrap="nowrap">{INFORMATION}</th>
	</tr>	
	<tr>
		<td class="row1" align="center" width="100%"><span class="gen"><br /><br /><b>{NO_BETS}<br /><br />&nbsp;</b></td>
	</tr>
	<tr>
		<td class="catBottom">&nbsp;</td>
	</tr>
</table>
<!-- END switch_no_bets -->
<!-- BEGIN switch_bets_set -->
<h1>{CURRENT_BETS}</h1>
<p>{CURRENT_BETS_EXPLAIN}</p>
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">	
	<tr>
		<th height="25" class="thCornerL" nowrap="nowrap">{CATEGORY}</th>
		<th class="thTop" nowrap="nowrap" colspan="3">{TIME} - {MEETING} -> <i>{SELECTION}</i></th>
		<th class="thTop" nowrap="nowrap">{ODDS}</th>	
		<th class="thTop" nowrap="nowrap" colspan="2">{EDIT_DELETE}</th>
	</tr>
	<!-- END switch_bets_set -->
	<!-- BEGIN processbet -->
	<tr>
		<td class="catLeft" height="28"><span class="cattitle"><a href="{processbet.EXPAND_URL}" class="cattitle">{processbet.CATEGORY}</a></span></td>
		<td class="catLeft" colspan="3" height="28"><span class="name"><a name="{processbet.ANCHOR}"></a></span>
			<table width="100%" border="0">
				<tr>
					<td align="center">{processbet.STAR_IMAGE}</td>
					<td><span class="cattitle"><a href="{processbet.EXPAND_URL}" class="cattitle">{processbet.TIME} - {processbet.MEETING}</a></span></td>
				</tr>
			</table>
		</td>
		<td class="rowpic" height="28" align="center" colspan="3">{processbet.ADD_IMAGE}&nbsp;&nbsp;{processbet.DROP_IMAGE}</td>
	</tr>
	<!-- BEGIN expansion -->
	<tr> 
		<td class="{processbet.expansion.ROW_CLASS}" align="left" valign="middle"><span class="gen"><b>{processbet.expansion.CATEGORY}</b></span></td>
		<td class="{processbet.expansion.ROW_CLASS}" align="center" valign="middle" colspan="3"><span class="gen">{processbet.expansion.SELECTION}</span></td>
		<td class="{processbet.expansion.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet.expansion.ODDS}</span></td>
		<td class="{processbet.expansion.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet.expansion.EDIT}</span></td>
		<td class="{processbet.expansion.ROW_CLASS}" align="center" valign="middle"><span class="gen">{processbet.expansion.DELETE}</span></td>
	</tr>
	<!-- END expansion -->
	<!-- END processbet -->
	<!-- BEGIN switch_bets_set -->
	<tr>
		<td class="catBottom" colspan="7"><span class="gen">&nbsp;</span></td>
	</tr>
</table>
<!-- END switch_bets_set -->
<br />
<div align="center"><span class="copyright">Forum Bookmakers Mod {BOOKIE_VERSION} by Majorflam &copy 2004-2005 <a href="http://www.majormod.com" class="copyright">Major Mod - Software Modifications For phpBB2</a></span></div>

