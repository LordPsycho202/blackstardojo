 
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
  <tr> 
	<td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
  </tr>
</table>

<table class="forumline" width="100%" cellspacing="1" cellpadding="3" border="0" align="center">
  <tr> 
	<th class="thHead" colspan="2" height="25" nowrap="nowrap">{L_VIEWING_PROFILE}</th>
  </tr>
  <tr> 
	<td class="catLeft" width="40%" height="28" align="center"><b><span class="gen">{L_AVATAR}</span></b></td>
	<td class="catRight" width="60%"><b><span class="gen">{L_ABOUT_USER}</span></b></td>
  </tr>
  <tr> 
	<td class="row1" height="6" valign="top" align="center">{AVATAR_IMG}<br /><span class="postdetails">{USER_RANK_01}{USER_RANK_01_IMG}{USER_RANK_02}{USER_RANK_02_IMG}{USER_RANK_03}{USER_RANK_03_IMG}{USER_RANK_04}{USER_RANK_04_IMG}{USER_RANK_05}{USER_RANK_05_IMG}</span></td>
	<td class="row1" rowspan="3" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="3">
		<tr> 
		  <td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_JOINED}:&nbsp;</span></td>
		  <td width="100%"><b><span class="gen">{JOINED}</span></b></td>
		</tr>
		<tr> 
		  <td valign="top" align="right" nowrap="nowrap"><span class="gen">{L_TOTAL_POSTS}:&nbsp;</span></td>
		  <td valign="top"><b><span class="gen">{POSTS}</span></b><br /><span class="genmed">[{POST_PERCENT_STATS} / {POST_DAY_STATS}]</span> <br /><span class="genmed"><a href="{U_SEARCH_USER}" class="genmed">{L_SEARCH_USER_POSTS}</a></span></td>
		</tr>
		<tr> 
		  <td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_LOCATION}:&nbsp;</span></td>
		  <td><b><span class="gen">{LOCATION}</span></b></td>
		</tr>
		<tr> 
		  <td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_WEBSITE}:&nbsp;</span></td>
		  <td><span class="gen"><b>{WWW}</b></span></td>
		</tr>
		<tr> 
		  <td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_OCCUPATION}:&nbsp;</span></td>
		  <td><b><span class="gen">{OCCUPATION}</span></b></td>
		</tr>
		<tr> 
		  <td valign="top" align="right" nowrap="nowrap"><span class="gen">{L_INTERESTS}:</span></td>
		  <td> <b><span class="gen">{INTERESTS}</span></b></td>
		</tr>
		<tr> 
		  <td valign="top" align="right" nowrap="nowrap"><span class="gen">{L_AUCTIONS}:</span></td>
		  <td> <b><span class="gen">{AUCTIONS_WON} {L_WON} {AUCTIONS_UNPAID}</span></b></td>
		</tr>
		<tr>
		  <td valign="top" align="right" nowrap="nowrap"><span class="gen">{L_Ri}</span></td>
		  <td><span class="gen">{Rarityi}</span></td>

		<tr> 
		  <td valign="top" align="right" nowrap="nowrap"><span class="gen">{INVENTORYLINK}</span></td>
		  <td> <span class="gen">{INVENTORYPICS}</span></td>
		</tr>

<!-- BEGIN switch_upload_limits -->
		<tr> 
			<td valign="top" align="right" nowrap="nowrap"><span class="gen">{L_UPLOAD_QUOTA}:</span></td>
			<td> 
				<table width="175" cellspacing="1" cellpadding="2" border="0" class="bodyline">
				<tr> 
					<td colspan="3" width="100%" class="row2">
						<table cellspacing="0" cellpadding="1" border="0">
						<tr> 
							<td bgcolor="{T_TD_COLOR2}"><img src="templates/subSilver/images/spacer.gif" width="{UPLOAD_LIMIT_IMG_WIDTH}" height="8" alt="{UPLOAD_LIMIT_PERCENT}" /></td>
						</tr>
						</table>
					</td>
				</tr>
				<tr> 
					<td width="33%" class="row1"><span class="gensmall">0%</span></td>
					<td width="34%" align="center" class="row1"><span class="gensmall">50%</span></td>
					<td width="33%" align="right" class="row1"><span class="gensmall">100%</span></td>
				</tr>
				</table>
				<b><span class="genmed">[{UPLOADED} / {QUOTA} / {PERCENT_FULL}]</span> </b><br />
				<span class="genmed"><a href="{U_UACP}" class="genmed">{L_UACP}</a></span></td>
			</td>
		</tr>
<!-- END switch_upload_limits -->

		<tr> 
		  <td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_USER_MEDAL}:&nbsp;</span></td> 
		  <td><b><span class="gen">{USER_MEDAL_COUNT}</span></b></td>
		</tr>

       
        
		<tr> 
		  <td valign="top" align="right" nowrap="nowrap"><span class="gen">{L_BIRTHDAY}:</span></td>
		  <td> <b><span class="gen">{BIRTHDAY}</span></b></td>
		</tr>

		{CASH}
		<!-- BEGIN switch_item_view -->
    <tr>
		  <td valign="top" align="right" nowrap="nowrap"><span class="gen">{L_USER_ITEMS}:</span></td>
		  <td> <b><span class="gen"><a href="{U_USER_ITEMS}" class="gen">{L_USER_ITEMS_GOTO}</a></span></b></td>
		</tr>
		<!-- END switch_item_view --> 
		<!-- BEGIN switch_user_sig_block -->
		<tr> 
		  <td valign="top" align="right" nowrap="nowrap"><span class="gen">{L_SIGNATURE}:&nbsp;</span></td>
		  <td> <span class="postbody">{USER_SIG}</span></td>
		</tr>
        <!-- END switch_user_sig_block -->	

	  </table>
	</td>
  </tr>
  <tr> 
	<td class="catLeft" align="center" height="28"><b><span class="gen">{L_CONTACT} {USERNAME} </span></b></td>
  </tr>
  <tr> 
	<td class="row1" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="3">
		<tr> 
		  <td valign="middle" align="right" nowrap="nowrap"><span class="gen">{L_EMAIL_ADDRESS}:</span></td>
		  <td class="row1" valign="middle" width="100%"><b><span class="gen">{EMAIL_IMG}</span></b></td>
		</tr>
		<tr> 
		  <td valign="middle" nowrap="nowrap" align="right"><span class="gen">{L_PM}:</span></td>
		  <td class="row1" valign="middle"><b><span class="gen">{PM_IMG}</span></b></td>
		</tr>
		<tr> 
		  <td valign="middle" nowrap="nowrap" align="right"><span class="gen">{L_MESSENGER}:</span></td>
		  <td class="row1" valign="middle"><span class="gen">{MSN}</span></td>
		</tr>
		<tr> 
		  <td valign="middle" nowrap="nowrap" align="right"><span class="gen">{L_YAHOO}:</span></td>
		  <td class="row1" valign="middle"><span class="gen">{YIM_IMG}</span></td>
		</tr>
		<tr> 
		  <td valign="middle" nowrap="nowrap" align="right"><span class="gen">{L_AIM}:</span></td>
		  <td class="row1" valign="middle"><span class="gen">{AIM_IMG}</span></td>
		</tr>
		<tr> 
		  <td valign="middle" nowrap="nowrap" align="right"><span class="gen">{L_ICQ_NUMBER}:</span></td>
		  <td class="row1"><script language="JavaScript" type="text/javascript"><!-- 

		if ( navigator.userAgent.toLowerCase().indexOf('mozilla') != -1 && navigator.userAgent.indexOf('5.') == -1 && navigator.userAgent.indexOf('6.') == -1 )
			document.write(' {ICQ_IMG}');
		else
			document.write('<table cellspacing="0" cellpadding="0" border="0"><tr><td nowrap="nowrap"><div style="position:relative;height:18px"><div style="position:absolute">{ICQ_IMG}</div><div style="position:absolute;left:3px;top:-1px">{ICQ_STATUS_IMG}</div></div></td></tr></table>');
		  
		  //--></script><noscript>{ICQ_IMG}</noscript></td>
		</tr>
	  </table>
	</td>
  </tr>
</table>

<br />
<!-- BEGIN medal -->

<table class="forumline" width="100%" cellspacing="1" cellpadding="3" border="0" align="center">
  <tr>
	<th class="thHead" height="25" valign="middle" colspan="4">{medal.L_MEDAL_INFORMATION}</th>
  </tr>
  <tr> 
	<td class="catLeft" align="center" nowrap="nowrap"><span class="cattitle">&nbsp;{medal.L_MEDAL_NAME}&nbsp;</span></td>
	<td class="catRight" align="center" nowrap="nowrap"><span class="cattitle">&nbsp;{medal.L_MEDAL_DETAIL}&nbsp;</span></td>
  </tr>
  <!-- BEGIN details -->
  <tr>
	<td class="row1" align="center" nowrap="nowrap"><span class="gen">{medal.details.MEDAL_NAME}</span><br />{medal.details.MEDAL_IMAGE}<br /><span class="gensmall">{medal.details.MEDAL_COUNT}</span></td>
	<td class="row1"><table width="100%" cellspacing="1" cellpadding="3" border="0" align="center">
		<tr><td><span class="gen">{medal.L_MEDAL_DESCRIPTION}<b>{medal.details.MEDAL_DESCRIPTION}</b></span></td></tr>
		<tr><td class="quote"><span class="genmed">{medal.details.MEDAL_ISSUE}</span></td></tr>
	</table></td>
  </tr>
  <!-- END details -->
</table>

<!-- END medal -->
<!-- BEGIN display_shares -->
<br clear="all" /> 
<table width="100%" border="1" cellspacing="1" cellpadding="1" class="forumline" align="center"> 
	<tr> 
		<th align="center" colspan="2">{L_ON_ACCOUNT}{ON_ACCOUNT}</th> 
	</tr> 
	<!-- BEGIN shares --> 
	<tr> 
		<td align="center" class="{display_shares.shares.SHARE_ROW}" width="65%" ><span class="gen">{display_shares.shares.SHARE_NAME}</span></td> 
		<td align="center" class="{display_shares.shares.SHARE_ROW}"><span class="gen">{display_shares.shares.SHARE_SUM}</span></td> 
	</tr> 
	<!-- END shares --> 
	<tr> 
		<th align="center" colspan="2">{L_LOAN}{LOAN}</th> 
	</tr> 
</table>
<!-- END display_shares -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
	<td align="right"><span class="nav"><br />{JUMPBOX}</span></td>

  </tr>
</table>