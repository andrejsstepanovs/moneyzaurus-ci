<?php
/** @var array $data */
$total = ['price' => 0];
?>

<table class="pure-table">
	<thead>
		<tr>
			<th>Item</th>
			<th>Group</th>
			<th>Price</th>
			<th>Date</th>
			<th>User</th>
			<th></th>
		</tr>
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