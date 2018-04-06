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
	<tr>
		<td class="{editbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{editbet.CURRENT_TIME}</span></td>
		<td class="{editbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{editbet.MEETING}</span></td>
		<td class="{editbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{editbet.SELECTION}</span></td>
		<td class="{editbet.ROW_CLASS}" align="center" valign="middle"><span class="gen">{editbet.ODDS}</span></td>
	</tr>
	<!-- END editbet -->
	<tr>
		<td class="catbottom" align="center" valign="middle" colspan="4">
			<table>
				<tr>
					<td><input type="submit" name="deletebet" value="{SUBMIT}" class="mainoption" /></td>
					<td><input type="submit" name="cancel" value="{CANCEL}" class="mainoption" /></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br />
<div align="center"><span class="copyright">Forum Bookmakers Mod {BOOKIE_VERSION} by Majorflam &copy 2004-2005 <a href="http://www.majormod.com" class="copyright">Major Mod - Software Modifications For phpBB2</a></span></div>

