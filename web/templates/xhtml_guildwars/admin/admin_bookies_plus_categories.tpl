<form action="{URL}" method="post">
<h1>{HEADER}</h1>
<p>{HEADER_EXPLAIN}</p>
<table width="75%" align="center" cellpadding="3" cellspacing="1" border="0">
	<tr>
		<td align="left">{IMG_NEW_CATEGORY}</td>
	</tr>
</table>
<table width="75%" align="center" cellpadding="3" cellspacing="1" border="0" class="forumline">	
	<tr>
		<th height="25" class="thCornerL" nowrap="nowrap" width="80%">{CATEGORY}</th>
		<th class="thTop" nowrap="nowrap" width="10%">{EDIT}</th>
		<th class="thTop" nowrap="nowrap" width="10%">{DELETE}</th>
	</tr>
	<!-- BEGIN cats -->
	<tr>
		<td class="{cats.ROW_CLASS}" align="left" valign="middle"><span class="gen">{cats.CAT}</span></td>
		<td class="{cats.ROW_CLASS}" align="center" valign="middle">{cats.EDIT_IMG}</td>
		<td class="{cats.ROW_CLASS}" align="center" valign="middle">
		<!-- BEGIN delete_allow -->
		<input type="checkbox" name="{cats.CHECK_NAME}" value="1" />
		<!-- END delete_allow -->
		</td>
	<!-- END cats -->
	<tr>
		<td class="catBottom" align="left"><input type="submit" name="delete_all" value="{DELETE_ALL}" class="mainoption" /></td>
		<td class="catBottom" colspan="2" align="right"><input type="submit" name="delete_marked" value="{DELETE_MARKED}" class="mainoption" /></td>
	</tr>
</table>
<br />
</form>
<div align="center"><span class="copyright">Forum Bookmakers Mod {BOOKIE_VERSION} by Majorflam &copy 2004-2005 <a href="http://www.majormod.com" class="copyright">Major Mod - Software Modifications For phpBB2</a></span></div>


