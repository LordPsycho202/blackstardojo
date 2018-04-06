<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center"> 
   <tr> 
     <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a>{AUCTIONLOCATION}</span></td> 
   </tr> 
  </table> 
<P>
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline"> 
	<tr> 
	  <th class="thHead" colspan="6">{L_AUCTION_TITLE}</th> 
	</tr> 
	<tr>
	  <td class="row1" align="center">
		<br />
		  <form method="post" action="{U_ACTION}">
			<table width="400">
				<tr valign="top">
				  <td><span class="gen"><b>Item(s):</b></span><br /><span class="gensmall">Hold CTRL to select<br />multiple items.<BR>(maximum 10)</span></td>
				  <td>
					<select name="items[]" size="10" multiple>
						{ITEMS}
					</select>
				  </td>
				  </td>
				</tr>
				<tr>
				  <td><span class="gen"><b>Title:</b></span></td>
					  <td><input type="text" name="title" maxlength="32" size="20"><span class="gen"></span></td>
				</tr>
				<tr valign="top">
				  <td><span class="gen"><b>Description:</b></span></td>
				  <td><textarea name="description" rows="3" cols="40" wrap="physical"></textarea></td>
				</tr>
				<tr>
				  <td><span class="gen"><b>Start Price:</b></span></td>
				  <td><input type="text" name="startprice" maxlength="6" size="10"><span class="gen"> {L_POINTS_NAME}</span></td>
				</tr>
				<tr>
				  <td><span class="gen"><b>Bid Increment:</b></span></td>
				  <td><input type="text" name="increment" maxlength="6" size="10"><span class="gen"> {L_POINTS_NAME}</span></td>
				</tr>
				<tr>
				  <td><span class="gen"><b>Buy Price:</b></span></td>
				  <td><input type="text" name="buyprice" maxlength="6" size="10" value="0"><span class="gen"> {L_POINTS_NAME}</span> <span class="gensmall">(leave as 0 for none)</span></td>
				</tr>
				<tr>
				  <td><span class="gen"><b>Duration:</b></span></td>
				  <td>
					<select name="duration">
						<option value="3600">1 hour</option>
						<option value="10800">3 hours</option>
						<option value="21600">6 hours</option>
						<option value="43200">12 hours</option>
						<option value="86400">1 day</option>
						<option value="172800">2 days</option>
						<option value="259200">3 days</option>
						<option value="432000">5 days</option>
						<option value="604800">7 days</option>
						<option value="864000">10 days</option>
					</select>
				  </td>
				</tr>
				<tr>
				  <td colspan="2"><span class="gensmall"><u>Notes:</u><br />Any items you auction will be removed from your collection & stored in the auction until it ends, at which time they will be transferred to the winning bidder (upon receipt of payment), or returned to you if you close the auction.<br />You can close an auction at any time while it is running, though there is a fee based on the current price.<br />When an auction ends, if the buyer does not pay within 3 days, you may close the auction at no charge.</span></td>
				</tr>
				<tr>
				  <td><br /></td>
				  <td><input type="submit" value="Begin Auction"><input type="hidden" name="action" value="addnewauction"> <input type="reset" value="Reset Form"></td>
				</tr>
			</table>
		</form>
	  </td>
	</tr>
	<tr> 
		<td colspan="6" class="row3"><span class="gen"><center><a href="{U_ACTION}">{L_BROWSEAUCTIONS}</a> | <a href="{U_CREATEAUCTION}">{L_CREATEAUCTION}</a></center></span></td>
	</tr>
  </table> 
<P>
  <table cellpadding="0" cellspacing="0" border="0" width="99%" class="darkblue" align="center">
	<tr>
	  <td>
		<table cellpadding="3" cellspacing="1" border="0" width="100%">
			<tr class="yellow">
			  <td width="100%"><br /></td>
			  <td align="right" valign="bottom" class="yellow" nowrap>{JUMPBOX}</td>
			</tr>
		</table>
	  </td>
	</tr>
  </table>
<br   clear="all" /> <br>
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">Personal Information</th>
	</tr>
	{AUCTIONPERSONAL}
  </table>