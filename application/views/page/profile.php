<?php
/** @var array $data */

?>


<h2>Profile</h2>

<form method="post" action="/profile/save" class="pure-form pure-form-aligned">
	<fieldset>
		<div class="pure-control-group">
			<label for="date">Email</label>
			<input id="email"
			       name="email"
			       type="text"
			       placeholder="Email"
			       value="<?php echo $data['email']; ?>"
			       autocomplete="off"
			       disabled="disabled">
		</div>
		<div class="pure-control-group">
			<label for="date">Name</label>
			<input id="name"
			       name="name"
			       type="text"
			       placeholder="Name"
			       value="<?php echo $data['name']; ?>"
			       autocomplete="off">
		</div>
		<div class="pure-control-group">
			<label for="date">Language</label>
			<input id="language"
			       name="language"
			       type="text"
			       placeholder="Language"
			       value="<?php echo $data['language']; ?>"
			       autocomplete="off"
			       disabled="disabled">
		</div>
		<div class="pure-control-group">
			<label for="date">Locale</label>
			<input id="locale"
			       name="locale"
			       type="text"
			       placeholder="Locale"
			       value="<?php echo $data['locale']; ?>"
			       autocomplete="off"
			       disabled="disabled">
		</div>
		<div class="pure-control-group">
			<label for="date">Timezone</label>
			<input id="timezone"
			       name="timezone"
			       type="text"
			       placeholder="Timezone"
			       value="<?php echo $data['timezone']; ?>"
			       autocomplete="off"
			       disabled="disabled">
		</div>
		<div class="pure-controls">
			<button type="submit" class="pure-button pure-button-primary">Update</button>
		</div>
	</fieldset>
</form>

<hr/>
	<h2>Connections</h2>
	
<hr/>

<pre>API version: <?php echo $version; ?></pre>