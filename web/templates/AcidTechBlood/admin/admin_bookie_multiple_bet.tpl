<form action="{URL}" method="post">
<h1>{BUILD_HEADER}</h1>
<p>{BUILD_EXPLAIN}</p>
<table width="400" align="center" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr>
		<th height="25" class="thCornerL" nowrap="nowrap">{SELECTION}</th>
		<th class="thTop" nowrap="nowrap">{ODDS}</th>	
	</tr>
	<!-- BEGIN selections -->
	<tr>
		<td class="{selections.ROW_CLASS}" align="center" valign="middle"><textarea name="{selections.SELECTION_NAME}" style="width: 150px"  rows="5" cols="10" class="post">{selections.SELECTION_VALUE}</textarea></td>
		<td class="{selections.ROW_CLASS}" align="center" valign="middle"><span class="gen">{selections.ODDS}</td>
	</tr>
	<!-- END selections -->
	<tr>
		<td class="catbottom" align="center" valign="middle" colspan="2"><input type="submit" name="build_bet" value="{SUBMIT}" class="mainoption" /></td>
	</tr>
</table>
</form>
<br />
{SELECTION_REVIEW}
<br />
	<div align="center"><span class="copyright">Forum Bookmakers Mod {BOOKIE_VERSION} by Majorflam &copy 2004-2005 <a href="http://www.majormod.com" class="copyright">Major Mod - Software Modifications For phpBB2</a></span></div>

