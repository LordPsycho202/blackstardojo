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
	  <td class="row3" align="center"><span class="gensmall"><b>ID</b></span></td>
	  <td class="row3" align="center"><span class="gensmall"><b>Seller & High Bidder</b></span></td>
	  <td class="row3" align="center"><span class="gensmall"><b>Items</b></span></td>
	  <td class="row3" align="center"><span class="gensmall"><b>Price</b></span></td>
	  <td class="row3" align="center"><span class="gensmall"><b>Bids</b></span></td>
	  <td class="row3" align="center"><span class="gensmall"><b>Ends</b></span></td>
	</tr>
<!-- BEGIN list_auctions -->
	<tr>
	  <td class="row3" align="center"><span class="gensmall">#{list_auctions.ID}</span></td>
	  <td class="{list_auctions.ROW_CLASS}"><a href="{list_auctions.SELLER_LINK}"><span class="gen">{list_auctions.SELLER_NAME}</span></a>{list_auctions.HIGH_BIDDER}</td>
	  <td class="{list_auctions.ROW_CLASS}">{list_auctions.ICONS}<span class="gen"><a href="{list_auctions.VIEW_AUCTION}"><b><span class="gen">{list_auctions.AUCTION_NAME}</span></b></a><br /><span class="gensmall">{list_auctions.ITEM_LIST}</span></td>
	  <td class="{list_auctions.ROW_CLASS}" align="center"><span class="gen">{list_auctions.CURRENT_PRICE} Gold</span>{list_auctions.BUY_PRICE}</td>
	  <td class="{list_auctions.ROW_CLASS}" align="center"><span class="gen"{list_auctions.BID_COLOR}>{list_auctions.BID_AMOUNT}</span></td>
	  <td class="{list_auctions.ROW_CLASS}" align="center"><span class="gensmall">{list_auctions.TIME_LEFT}</span></td>
	</tr>
<!-- END list_auctions -->
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
			  <td width="100%"><span class="gensmall">[ {PAGE_NUMBER} ]<br>{PAGINATION}</span></td>
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