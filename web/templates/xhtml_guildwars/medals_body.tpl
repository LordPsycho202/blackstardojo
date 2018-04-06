
<table width="100%" cellspacing="2" cellpadding="2" border="0">
  <tr> 
	<td align="left" valign="middle" width="100%"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> 
	  -> {L_MEDALS}</span></td>
  </tr>
</table>


<table border="0" cellpadding="4" cellspacing="1" class="forumline" width="100%">
		<tr>
			<th class="thHead" height="25" valign="middle" colspan="4">{L_MEDAL_INFORMATION}</th>
		</tr>
		<tr>
			<td class="catLeft" align="center"><span class="cattitle">&nbsp;{L_MEDAL_NAME}&nbsp;</span></td>
			<td class="cat" align="center"><span class="cattitle">&nbsp;{L_MEDAL_DESCRIPTION}&nbsp;</span></td>
			<td class="cat" align="center" width="200"><span class="cattitle">&nbsp;{L_USERS_LIST}&nbsp;</span></td>
			<td class="catRight" nowrap="nowrap" align="center"><span class="cattitle">&nbsp;{L_MEDAL_MODERATOR}&nbsp;</span></td>
		</tr>
		<!-- BEGIN medals -->
		<tr>
			<td class="{medals.ROW_CLASS}" align="center" nowrap="nowrap">
				<span class="gen">{medals.MEDAL_NAME}</span><br />{medals.MEDAL_IMAGE}
			</td>
			<td class="{medals.ROW_CLASS}" valign="center" align="center">
				<span class="gen">{medals.MEDAL_DESCRIPTION}</span>
			</td>
			<td class="{medals.ROW_CLASS}" valign="center">
				<span class="gensmall">&nbsp;{medals.USERS_LIST}</span>
			</td>
			<td class="{medals.ROW_CLASS}" valign="center" align="center">
				<span class="gensmall">{medals.MEDAL_MOD}</span>
				<!-- BEGIN switch_mod_option -->
				<br/><span class="gensmall"><a href="{medals.U_MEDAL_CP}" class="gensmall">{L_LINK_TO_CP}</a></span>
				<!-- END switch_mod_option -->
			</td>
		</tr>
		<!-- END medals -->
		<!-- BEGIN nomedal -->
		<tr>
			<td class="row1" align="center" nowrap="nowrap" colspan="4">
				<span class="gen">{nomedal.L_NO_MEDAL}</span>
			</td>
		</tr>
		<!-- END nomedal -->
</table>
