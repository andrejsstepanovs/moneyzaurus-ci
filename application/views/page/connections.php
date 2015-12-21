<hr/>
<h2>Connections</h2>

<table class="pure-table">
	<thead>
		<tr>
			<th>ID</th>
			<th>Email</th>
			<th>Parent</th>
			<th>State</th>
			<th>Created</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($connections_child as $row) : ?>
			<tr>
				<td><?php echo $row['id']; ?></td>
				<td><?php echo $row['email']; ?></td>
				<td><?php echo $row['parent']; ?></td>
				<td><?php echo $row['state']; ?></td>
				<td><?php echo $row['created']; ?></td>
				<td>

				</td>
			</tr>
		<?php endforeach; ?>
		<?php foreach ($connections_parent as $row) : ?>
			<tr>
				<td><?php echo $row['id']; ?></td>
				<td><?php echo $row['email']; ?></td>
				<td><?php echo $row['parent']; ?></td>
				<td><?php echo $row['state']; ?></td>
				<td><?php echo $row['created']; ?></td>
				<td>
					<?php if ($row['state'] == 'rejected'): ?>
						<form method="post" action="/profile/acceptConnection">
							<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
							<button type="submit" class="pure-button pure-button-primary">Accept</button>
						</form>
					<?php else: ?>
						<form method="post" action="/profile/declineConnection">
							<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
							<button type="submit" class="pure-button pure-button-primary">Decline</button>
						</form>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<form method="post" action="/profile/invite" class="pure-form pure-form-aligned">
	<fieldset>
		<div class="pure-control-group">
			<label for="invite-email">Email</label>
			<input id="invite-email" name="email" type="email" placeholder="Email" value="">
		</div>
		<div class="pure-controls">
			<button type="submit" class="pure-button pure-button-primary">Invite</button>
		</div>
	</fieldset>
</form>
