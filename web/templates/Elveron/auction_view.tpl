<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center"> 
   <tr> 
     <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a>{AUCTIONLOCATION}</span></td> 
   </tr> 
  </table> 
<P>
{AUCTION_SORTING}
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline"> 
   <tr> 
     <th class="thHead" colspan="6">{L_AUCTION_TITLE}</th> 
   </tr> 
	<tr>
	  <td class="row1" align="center">
		<table width="100%">
			<tr>
			  <td>
				<table align="center" width="75%" class="forumline" cellpadding="3" cellspacing="1" border="0">
					<tr>
					  <td colspan="3" align="center" class="row3"><span class="gen"><b>Items in this auction:</b></span></td>
					</tr>
<!-- BEGIN list_items -->
					<tr>
					  <td class="row3" rowspan="4" align="center"><img src="shop/images/{list_items.IMAGE}" ALT="{list_items.ITEM_NAME}"></td>
					  <td class="row1"><span class="gen"><b>Item Name: </b></span></td>
					  <td class="row1"><span class="gen">{list_items.ITEM_NAME}</span></td>
					</tr>
					<tr>
					  <td class="row2"><span class="gen"><b>Shop Price: </b></span></td>
					  <td class="row2"><span class="gen">{list_items.ITEM_PRICE}</span></td>
					</tr>
					<tr>
					  <td class="row1"><span class="gen"><b>Item Description: </b></span></td>
					  <td class="row1"><span class="gen">{list_items.ITEM_DESCRIPTION}</span></td>
					</tr>
					<tr>
					  <td colspan="2" class="row3"><br /></td>
					</tr>
<!-- END list_items -->
				</table>
				<hr size="1">
				<span class="gen"><b>Description:</b><br />{DESCRIPTION}</span>
			  </td>
			</tr>
			<tr>
			  <td>
				<hr size="1">
				<table width="100%">
					<tr valign="top">
					  <td width="35%">
						<span class="gen">
						<b>Seller:</b> <a href="{U_SELLER}">{SELLER}</a><br />
						<b>High Bidder:</b> {HIGH_BIDDER}<br />
						<b>Total Bids:</b> {TOTAL_BIDS}<br />
						<b>Start:</b> {START_TIME}<br />
						<b>End:</b> {END_TIME}<br />
						<b>Time Remaining:</b> {REMAINING_TIME}<br />
						<b>Starting Price:</b> {START_PRICE} {L_POINTS_NAME}<br />
						<b>Bid Increment:</b> {BID_INCREASE} {L_POINTS_NAME}<br />
						<b>Current Price:</b> {CURRENT_PRICE} {L_POINTS_NAME}<p>{BID_OPTION}
						</span>
					  </td>
					  <td width="20"></td>
					  <td>
						<span class="gen"><u><b>BID HISTORY</b></u></span><br />
						<table width="100%">{BID_HISTORY}</table>
					  </td>
					</tr>
				</table>
			  </td>
			</tr>
		</table>
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