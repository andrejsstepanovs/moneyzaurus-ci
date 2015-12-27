<?php
/** @var array $data */

?>

<h2 style="width: 30%;">Current month manager</h2>

<form method="get" action="/manager" class="pure-form pure-form-aligned">
    <fieldset>
        <div class="pure-control-group">
            <label for="money">Money</label>
            <input
                id="money"
                name="money"
                type="number"
                step="1"
                min="0"
                placeholder="Current money in account"
                value="<?php echo $money; ?>">
        </div>
        <div class="pure-control-group">
            <label for="date">Date</label>
            <input
                id="date"
                name="date"
                type="date"
                placeholder="Next salary date"
                value="<?php echo $date; ?>"
                >
        </div>
        <div class="pure-controls">
            <button type="submit" class="pure-button pure-button-primary">Update</button>
        </div>
    </fieldset>
</form>



<h4>Prediction</h4>
<table class="pure-table">
    <tr>
        <th>Days till next salary:</th>
        <td><?php echo $days_till_next_salary; ?></td>
    </tr>
    <tr>
        <th>Possible in day:</th>
        <td><?php echo $possible_in_day; ?></td>
    </tr>
    <tr>
        <th>Saved if spending 10 eur in day</th>
        <td>0</td>
    </tr>
    <tr>
        <th>Saved if spending 20 eur in day</th>
        <td>0</td>
    </tr>
    <tr>
        <th>Saved if spending 30 eur in day</th>
        <td>0</td>
    </tr>
</table>





<h4>Monthly expenses</h4>
<table class="pure-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Dzīvoklis</td>
            <td><?php echo 0; ?></td>
        </tr>
    </tbody>
    <thead>
        <tr>
            <td>Total</td>
            <td><?php echo 0; ?></td>
        </tr>
        <tr>
            <td>Payed</td>
            <td><?php echo 0; ?></td>
        </tr>
        <tr>
            <td>Left</td>
            <td><?php echo 0; ?></td>
        </tr>
    </thead>
</table>


