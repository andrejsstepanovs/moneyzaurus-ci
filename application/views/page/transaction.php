<form method="post" action="/transaction/save" class="pure-form pure-form-aligned">

	<fieldset>
		<div class="pure-control-group">
			<label for="item">Item</label>
			<input id="item" name="item" type="text" placeholder="Item" value="<?php echo $item; ?>">
		</div>

		<div class="pure-control-group">
			<label for="group">Group</label>
			<input id="group" name="group" type="text" placeholder="Group" value="<?php echo $group; ?>">
		</div>

		<div class="pure-control-group">
			<label for="price">Price</label>
			<input id="price" name="price" type="text" placeholder="Price" value="<?php echo $price; ?>">
		</div>

		<div class="pure-control-group">
			<label for="date">Date</label>
			<input id="date" name="date" type="date" placeholder="Date" value="<?php echo $date; ?>">
		</div>

		<div class="pure-controls">
			<button type="submit" class="pure-button pure-button-primary">Save</button>
		</div>
	</fieldset>

</form>