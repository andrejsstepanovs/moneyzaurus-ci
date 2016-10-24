<?php
/** @var array $data */
$str = [];
$rows = array_combine(array_column($data, 'groupName'), array_column($data, 'price'));
foreach ($rows as $key => $data) {
	$str[] = '[\'' . $key . '\', ' . $data . ']';
}
$str = '['.implode(',', $str).']';
?>

<form method="get" action="/chart/pie" class="pure-form pure-form-aligned">
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

		<div class="pure-controls">
			<button type="submit" class="pure-button pure-button-primary">Show</button>
		</div>
	</fieldset>
</form>
<div id="chart_div"></div>

<script type="text/javascript" src="//www.google.com/jsapi"></script>
<script type="text/javascript">
jQuery(function($) {
    function drawCharts() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Group');
        data.addColumn('number', 'Amount');
        data.addRows(<?php echo $str; ?>);
        var chart = new google.visualization.PieChart($('#chart_div').get(0));
        chart.draw(
            data,
            {
                'title': 'Pie chart <?php echo $from . ' -> ' . $till; ?>',
                'width': 800,
                'height': 500
            }
        );
    }
    google.load('visualization', '1.0', {'packages':['corechart'], 'callback': drawCharts});
});
</script>