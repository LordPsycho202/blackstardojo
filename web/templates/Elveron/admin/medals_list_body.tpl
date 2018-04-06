
<h1>{L_MEDAL_TITLE}</h1>

<p class="gen">{L_MEDAL_EXPLAIN}</p>

<form method="post" action="{S_MEDAL_ACTION}"><table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr>
		<th class="thCornerL">{L_MEDAL_NAME}</th>
		<th class="thTop">{L_MEDAL_IMAGE}</th>
        	<th class="thTop">{L_MEDAL_DESCRIPTION}</th>
		<th class="thTop">{L_MEDAL_MOD}</th>
		<th class="thTop">{L_EDIT}</th>
		<th class="thCornerR">{L_DELETE}</th>
	</tr>
	<!-- BEGIN medals -->
	<tr>
		<td class="{medals.ROW_CLASS}" align="center"><span class="gen">{medals.MEDAL_NAME}</span></td>
		<td class="{medals.ROW_CLASS}" align="center"><span class="gen">{medals.MEDAL_IMAGE}</span></td>
	        <td class="{medals.ROW_CLASS}" align="center"><span class="gen">{medals.MEDAL_DESCRIPTION}</span></td>
		<td class="{medals.ROW_CLASS}" align="center"><span class="gen"><a href="{medals.U_MEDAL_MOD}">{L_MEDAL_MOD}</a></span></td>
		<td class="{medals.ROW_CLASS}" align="center"><span class="gen"><a href="{medals.U_MEDAL_EDIT}">{L_EDIT}</a></span></td>
		<td class="{medals.ROW_CLASS}" align="center"><span class="gen"><a href="{medals.U_MEDAL_DELETE}">{L_DELETE}</a></span></td>
	</tr>
	<!-- END medals -->			
	<tr>
		<td class="catBottom" align="center" colspan="6"><input type="submit" class="mainoption" name="add" value="{L_CREATE_NEW_MEDAL}" /></td>
	</tr>
</table></form>
