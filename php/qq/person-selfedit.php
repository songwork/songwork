<?php
print '<form action="/person" method="post"><fieldset>';
print '<input type="hidden" name="action" value="UPDATE" />';
print '<label for="name">Name:</label>';
print '<input type="text" name="name" id="name" value="' . htmlspecialchars($p->name()) . '" />';
print '<label for="email">Email:</label>';
print '<input type="text" name="email" id="email" value="' . htmlspecialchars($p->email()) . '" />';
print '<label for="fullname">Company/Payee:</label>';
print '<input type="text" name="fullname" id="fullname" value="' . htmlspecialchars($p->fullname()) . '" />';
print '<label for="address">Mailing Address:</label>';
print '<input type="text" name="address" id="address" value="' . htmlspecialchars($p->address()) . '" />';
print '<label for="city">City:</label>';
print '<input type="text" name="city" id="city" value="' . htmlspecialchars($p->city()) . '" size="15" />';
print '<label for="state">State/Province:</label>';
print '<input type="text" name="state" id="state" value="' . htmlspecialchars($p->state()) . '" size="10" />';
print '<label for="postalcode">Postalcode:</label>';
print '<input type="text" name="postalcode" id="postalcode" value="' . htmlspecialchars($p->postalcode()) . '" size="8" />';
print '<label for="country">Country:</label>';
print formselect_country_poptop('country', Location::$iso_countries, $p->country());
print '<label for="phone">Phone:</label>';
print '<input type="text" name="phone" id="phone" value="' . htmlspecialchars($p->phone()) . '" size="15" />';
# id', 'email', 'hashpass', 'lopass', 'newpass', 'name', 'fullname', 'address', 'city', 'state', 'postalcode', 'country', 'phone',
print '<br /><br />Please make any changes above, then click: <input type="submit" name="submit" value="UPDATE" />';
print '<hr />';
print '<label for="password">To change your password, type new one here: (<strong>leave blank</strong> otherwise)</label>';
print '<input type="password" name="password" id="password" value="" />';
print '<input type="submit" name="submit2" value="UPDATE" />';
print '</fieldset></form>';
?>
