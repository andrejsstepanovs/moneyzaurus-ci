<?php
/** @var array $data */

$str = [];
foreach ($data['data'] as $month => $groupsData) {
	$str[] = '[\'' . $month . '\', ' . implode(', ', $groupsData) . ']' . PHP_EOL;
}
$str = '['.implode(',', $str).']';
?>

<form method="get" action="/chart" class="pure-form pure-form-aligned">
	<fieldset>
		<div class="pure-control-group">
			<label for="date">From</label>
			<input name="from"
			       type="date"
			       placeholder="From"
			       value="<?php echo $from; ?>"
			       autocomplete="off">
		</div>
		<div class="pure-control-group">
			<label for="date">Till</label>
			<input name="till"
			       type="date"
			       placeholder="Till"
			       value="<?php echo $till; ?>"
			       autocomplete="off">
		</div>
		<div class="pure-control-group">
			<label for="groups">Groups</label>
			<select multiple="multiple" name="groups[]" id="groups">
				<?php foreach ($data['groups'] as $group): ?>
					<?php $selected = in_array($group, $data['selected']) !== false ? 'selected="selected"' : ''; ?>
					<option value="<?php echo $group; ?>" <?php echo $selected ; ?>>
						<?php echo $group == '__total__' ? 'Total' : $group; ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>


		<div class="pure-controls">
			<button type="submit" class="pure-button pure-button-primary">Show</button>
		</div>
	</fieldset>
</form>
<div id="chart_div" style="margin:15px;"></div>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load('visualization', '1.1', {packages: ['line']});
	google.setOnLoadCallback(
		function() {
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Month');
			<?php foreach ($data['selected'] as $group): ?>
				data.addColumn('number', '<?php echo $group == '__total__' ? 'Total' : $group; ?>');
			<?php endforeach; ?>

			data.addRows(<?php echo $str; ?>);
			var options = {
				chart: {
					title: '<?php echo implode(' ', $data['selected']); ?>',
					subtitle: 'Chart <?php echo $from . ' -> ' . $till; ?>'
				},
				width: 900,
				height: 500
			};

			var chart = new google.charts.Line($('#chart_div').get(0));

			chart.draw(data, options);
		}
	);
</script>