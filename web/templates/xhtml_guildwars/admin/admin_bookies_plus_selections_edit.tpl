<form action="{URL}" method="post">
<h1>{HEADER}</h1>
<p>{HEADER_EXPLAIN}</p>
<table width="75%" align="center" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr>
		<th height="25" class="thCornerL" nowrap="nowrap" colspan="2">{SELECTION}</th>
	</tr>
	<tr>
		<td class="row1" align="left" width="50%"><span class="gen"><b><u>{TEMPL_NAME_INPUT}</u></b><p>{TEMPL_NAME_INPUT_EXP}</p></td>
		<td class="row2" align="center"><input class="post" type="text" style="width: 200px" maxlength="25" size="11" name="templ_name" value="{THIS_TEMPL_NAME}"/></td>
	</tr>
	<!-- BEGIN templ_selections -->
	<tr>
		<td class="{templ_selections.ROW_CLASS}" align="center" valign="middle" colspan="2"><textarea name="{templ_selections.SELECTION_NAME}" style="width: 150px"  rows="5" cols="10" class="post">{templ_selections.SELECTION_VALUE}</textarea></td>
	</tr>
	<!-- END templ_selections -->
	<tr>
		<td class="catbottom" align="center" valign="middle" colspan="2"><input type="submit" name="build_templ" value="{SUBMIT}" class="mainoption" /></td>
	</tr>
</table>
</form>
<br />
<div align="center"><span class="copyright">Forum Bookmakers Mod {BOOKIE_VERSION} by Majorflam &copy 2004-2005 <a href="http://www.majormod.com" class="copyright">Major Mod - Software Modifications For phpBB2</a></span></div>

