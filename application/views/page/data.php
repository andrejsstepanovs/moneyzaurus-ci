<?php
/** @var array $data */
$total = ['price' => 0];
?>

<table class="pure-table">
	<thead>
		<form method="get" action="" style="margin:0;">
			<tr>
				<th style="padding:5px;">
					<input name="item"
					       type="text"
					       placeholder="Item"
					       value="<?php echo $filter['item']; ?>"
					       autocomplete="off"
					       style="width:90px;">
				</th>
				<th style="padding:5px;">
					<input name="group"
					       type="text"
					       placeholder="Group"
					       value="<?php echo $filter['group']; ?>"
					       autocomplete="off"
					       style="width:90px;">
				</th>
				<th style="padding:5px;">
					<input name="price"
					       type="text"
					       placeholder="Price"
					       value="<?php echo $filter['price']; ?>"
					       autocomplete="off"
					       style="width:90px;">
				</th>
				<th style="padding:5px;">
					<input name="from"
					       type="date"
					       placeholder="From"
					       value="<?php echo $filter['from']; ?>"
					       autocomplete="off"
					       style="width:90px;">
					<input name="till"
					       type="date"
					       placeholder="Till"
					       value="<?php echo $filter['till']; ?>"
					       autocomplete="off"
					       style="width:90px;">
				</th>
				<th>User</th>
				<th style="padding:5px;">
					<button type="submit" class="pure-button pure-button-primary">Search</button>
				</th>
			</tr>
		</form>
	</thead>
	<tbody>
		<?php foreach ($data as $row) : ?>
			<tr>
				<td><?php echo $row['itemName']; ?></td>
				<td><?php echo $row['groupName']; ?></td>
				<td><?php echo $row['money']; ?></td>
				<td><?php echo $row['date']; ?></td>
				<td><?php echo $row['userName']; ?></td>
				<td>
					<a class="pure-button" href="transaction?id=<?php echo $row['id']; ?>">Edit</a>
					<a class="pure-button" href="transaction/delete?id=<?php echo $row['id']; ?>">Delete</a>
				</td>
			<tr>
			<?php
			$total['price'] += $row['amount'] / 100;
			?>
		<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="2">TOTAL</td>
			<td><?php echo number_format($total['price'], 2, '.', ''); ?></td>
			<td>COUNT</td>
			<td><?php echo count($data); ?></td>
			<td></td>
		</tr>
	</tfoot>
</table>