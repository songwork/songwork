<h2>Price References</h2>
<p>
Since SongWork is an international multi-currency site, we set prices for everything using a price-reference (aka priceref), instead doing the currency exchange exactly (where non-USD people would be paying awkward amounts).
</p><p>
You can add or edit these price references here, then when setting the price of a document or service, just choose from one of them in a pull-down menu. (It'll reference the US Dollar amount, but then adapt for international browsers.)
</p>

<br /><table>
<?php
foreach($pricerefs as $code => $moneys)
	{
	print '<tr>';
	print '<th>';
	if(isset($_GET['edit']) && $_GET['edit'] == $code)
		{
		print 'CODE=' . $code . '.<br />Save individually!';
		}
	else
		{
		print '<a href="/a/priceref?edit=' . $code . '">edit</a> ' . $code;
		}
	print '</th>';
	foreach($moneys as $id => $money)
		{
		print '<td>';
		if(isset($_GET['edit']) && $_GET['edit'] == $code)
			{
			print '<form action="/a/priceref/' . $id . '" method="post"><fieldset>';
			print '<label for="' . $id . '">' . $money->code . ' millicents</label>';
			print '<input type="text" name="millicents" id="' . $id . '" value="' . $money->millicents . '" size="7" />';
			print '<input type="submit" name="action" value="update" />';
			print '</fieldset></form>';
			}
		else
			{
			print $money->show_with_code();
			if($money->code !== 'USD')
				{
				print '<span class="small">';
				$usd = $money->converted_to('USD');
				print '<br />= ' . $usd->show_with_code();
				print '</span>';
				}
			}
		print '</td>';
		}
	print '</tr>';
	}
?>
</table>

<h3>Add new PriceRef</h3>
<form action="/a/priceref" method="post"><fieldset>
<label for="newcode">Unused letter code:</label>
<input type="text" name="code" id="newcode" value="" size="1" />
<label for="newusd">USD millicents:</label>
<input type="text" name="millicents" id="newusd" value="" size="8" />
<input type="submit" name="action" value="add" />
</fieldset></form>
