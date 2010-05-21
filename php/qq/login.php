<h2>Log in to Songwork</h2>
<div id="signupform">
<p>(No password? <a href="/signup">sign up</a>, instead)</p>
<form action="/login" method="post"><fieldset>
<label for="email">Your email address:</label>
<input type="text" name="email" id="email" value="<?php print $email; ?>" size="24" />
<label for="password">Your password:</label>
<input type="password" name="password" id="password" value="" size="12" />
<br /><br /><input type="submit" name="action" value="login" class="submit" />
</fieldset></form>
</div>
