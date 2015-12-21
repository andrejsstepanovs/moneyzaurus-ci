<?php
/** @var array $data */
?>

<table class="pure-table">
	<thead>
		<tr>
			<th>Item</th>
			<th>Group</th>
			<th>Price</th>
			<th>Date</th>
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
				<td>
					<a href="transaction?id=<?php echo $row['id']; ?>">Edit</a>
				</td>
			<tr>
		<?php endforeach; ?>
	</tbody>
</table>