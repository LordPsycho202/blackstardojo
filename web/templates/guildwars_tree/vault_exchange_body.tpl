<form action="{S_VAULT_ACTION}" method="post">
<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
	<tr> 
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_VAULT}" class="nav">{L_VAULT}</a> -> {L_STOCK_EXCHANGE_ACTIONS}</span></td>
		</span></td>
	</tr>
<tr>						
						
                 				<td colspan="2" align="center" width="100%" valign="middle">
						Stock Exchange - Latest changes
						<marquee scrollamount="3" direction="left" loop="true" onmouseover="this.stop()" onmouseout="this.start()"><a href="vault.php" class="mainmenu">| <b>BLACKSTAR MARKET INDEX:</b> {STOCK_INDEX} ({STOCK_INDEX_DIFF}) |</a>
<!-- BEGIN stock_tick -->
<a href="vault.php" class="mainmenu">| <b>{stock_tick.STOCK_NAME}:</b> {stock_tick.STOCK_VALUE} ({stock_tick.STOCK_DIFF}) | </a>
<!-- END stock_tick -->

                				
                				</marquee></span></td>
						</tr>
  </table>

<table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline" width="100%">
	<tr>
		<td class="catHead" colspan="12" align="center"><span class="cattitle"><b>{L_STOCK_EXCHANGE_ACTIONS}</b>&nbsp;|&nbsp;{L_OWNER_POINTS} : {POINTS} {L_POINTS}</span> </td>
	</tr>
	<tr>
		<th class="thCornerL">{L_STOCK_NAME}</th>
		<th class="thTop">{L_STOCK_AMOUNT}</th>
		<th class="thTop">{L_STOCK_PREVIOUS}</th>
		<th class="thTop">{L_STOCK_WORST}</th>
		<th class="thTop">{L_STOCK_BEST}</th>
		<th class="thTop">{L_STOCK_OWNED}</th>
		<th class="thTop">Total</th>
		<th class="thTop">Percent</th>
		<th class="thTop">{L_STOCK_MAX}</th>
      		<th class="thTop">{L_STOCK_BOUGHT}</th>

   

		<th class="thTop">{L_STOCK_BUY}</th>
		<th class="thCornerR">{L_STOCK_SELL}</th>
	</tr>
	<!-- BEGIN exchange -->
	<tr>
		<td class="{exchange.ROW_CLASS}" align="center"><span class="gen">{exchange.STOCK_NAME}</span><br /><span class="gensmall">{exchange.STOCK_DESC}</span></td>
		<td class="{exchange.ROW_CLASS}" align="center"><span class="gen"><b>{exchange.STOCK_AMOUNT}</b></span></td>
		<td class="{exchange.ROW_CLASS}" align="center"><span class="gen">{exchange.STOCK_PREVIOUS}</span></td>
		<td class="{exchange.ROW_CLASS}" align="center"><span class="gen">{exchange.STOCK_WORST}</span></td>
		<td class="{exchange.ROW_CLASS}" align="center"><span class="gen">{exchange.STOCK_BEST}</span></td>
		
		<td class="{exchange.ROW_CLASS}" align="center"><span class="gen">{exchange.STOCK_OWNED}</span></td>
		<td class="{exchange.ROW_CLASS}" align="center"><span class="gen">{exchange.STOCK_TOTAL}</span></td>
		<td class="{exchange.ROW_CLASS}" align="center"><span class="gen">{exchange.STOCK_PERCENT}</span></td>
		<td class="{exchange.ROW_CLASS}" align="center"><span class="gen">{exchange.STOCK_CAP}</span></td>
 	<td class="{exchange.ROW_CLASS}" align="center"><span class="gen">{exchange.STOCK_BOUGHT}</span></td>



		<td class="{exchange.ROW_CLASS}" align="center"><span class="gen">{exchange.STOCK_BUY}</span></td>
		<td class="{exchange.ROW_CLASS}" align="center"><span class="gen">{exchange.STOCK_SELL}</span></td>
	</tr>
	<!-- END exchange -->
	<tr>
		<td class="catBottom" colspan="12" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="exchange_submit" value="{L_SUBMIT}" class="mainoption" /></td>
	</tr>
</table>
</form>
<br clear="all" />


