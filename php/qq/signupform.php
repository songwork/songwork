
<div id="signupform">
<h3>Interested?  Start with your info:</h3>
<form action="/signup" method="post"><fieldset>
<label for="name">Your Name:</label>
<input type="text" name="name" id="name" value="<?php print htmlspecialchars($name); ?>" size="20" />
<label for="email">Your Email:</label>
<input type="text" name="email" id="email" value="<?php print htmlspecialchars($email); ?>" size="20" />
<br /><br /><input type="submit" name="submit" class="submit" value="continue" />
</fieldset></form>
</div>

