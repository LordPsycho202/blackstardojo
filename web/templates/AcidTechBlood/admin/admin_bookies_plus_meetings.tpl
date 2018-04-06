<form action="{URL}" method="post">
<h1>{HEADER}</h1>
<p>{HEADER_EXPLAIN}</p>
<table width="75%" align="center" cellpadding="3" cellspacing="1" border="0" class="forumline">	
	<tr>
		<th height="25" class="thCornerL" nowrap="nowrap" width="80%">{MEETING}</th>
		<th class="thTop" nowrap="nowrap" width="10%">{EDIT}</th>
		<th class="thTop" nowrap="nowrap" width="10%">{DELETE}</th>
	</tr>
	<!-- BEGIN meetings -->
	<tr>
		<td class="{meetings.ROW_CLASS}" align="left" valign="middle"><span class="gen">{meetings.MEETING}</span></td>
		<td class="{meetings.ROW_CLASS}" align="center" valign="middle">{meetings.EDIT_IMG}</td>
		<td class="{meetings.ROW_CLASS}" align="center" valign="middle"><input type="checkbox" name="{meetings.CHECK_NAME}" value="1" /></td>
	<!-- END meetings -->
	<tr>
		<td class="catBottom" align="left"><input type="submit" name="delete_all" value="{DELETE_ALL}" class="mainoption" /></td>
		<td class="catBottom" colspan="2" align="right"><input type="submit" name="delete_marked" value="{DELETE_MARKED}" class="mainoption" /></td>
	</tr>
</table>
<br />
</form>
<div align="center"><span class="copyright">Forum Bookmakers Mod {BOOKIE_VERSION} by Majorflam &copy 2004-2005 <a href="http://www.majormod.com" class="copyright">Major Mod - Software Modifications For phpBB2</a></span></div>


