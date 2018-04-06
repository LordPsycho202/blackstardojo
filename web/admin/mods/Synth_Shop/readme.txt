This shop has two main functions, synthing and desynthing. You can only desynth items that 
are within the shop 'Synthesize Shop'. This is added in the install, do NOT rename this 
shop. This shop is admin_only and should remain so. The other shop added, 'Synthesizing' 
may be renamed to anything you like, as it's display only.


Desynthing requires the item has synth items set, and has the "maxstock" value set to 1. 
If it's 0 it cannot be desynthed. There is a chance of losing items when desynthing 
items, by default it's 15% on each item it returns. You may change this by editting 
your shop_synth.php file. Variable is at the top.


Synthing is a little more complex. When you edit the 'Synthesize Shop', a new field will 
appear when editting items for 'Synths'. To add requirements for an item, simply add them 
with ; between the items. For example, dagger;gold;gold would mean it requires the items 
'dagger' and 2x 'gold'. There is no limit to the amount of items a synth can require.

If you want to -hide- an item until the user has the items needed to synth it, simply 
set the stock to something greater than 0.

Synth items can also cost gold/points/gil/etc. Simply set the cost to something greater 
than 0 and it will charge them too. (Non-refundable on desynthing.)

Items with a * next to them in the drop down list means the user owns it already.

The item name will become a link when it's available for synthing.