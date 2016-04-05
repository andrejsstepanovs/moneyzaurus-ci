<?php
/** @var array distinct_items */
if ($id) {
    echo '<h2>Edit</h2>';
}
?>


<form method="post" action="/transaction/save" class="pure-form pure-form-aligned">
	<input id="id" name="id" type="hidden" value="<?php echo $id; ?>" >
	<fieldset>
		<div class="pure-control-group">
			<label for="item">Item</label>
			<input id="item"
			       name="item"
			       type="text"
			       placeholder="Item"
			       value="<?php echo $item; ?>"
			       autocomplete="off">
		</div>

		<div class="pure-control-group">
			<label for="group">Group</label>
			<input id="group"
			       name="group"
			       type="text"
			       placeholder="Group"
			       value="<?php echo $group; ?>"
			       autocomplete="off">
			<span class="suggest" id="suggest-group"></span>
		</div>

		<div class="pure-control-group">
			<label for="price">Price</label>
			<input id="price"
			       name="price"
			       type="number"
			       step="any"
			       min="0"
			       placeholder="Price"
			       value="<?php echo $price; ?>"
			       autocomplete="off">
			<span class="suggest" id="suggest-price"></span>
		</div>

		<div class="pure-control-group">
			<label for="date">Date</label>
			<input id="date"
			       name="date"
			       type="date"
			       placeholder="Date"
			       value="<?php echo $date; ?>"
			       autocomplete="off">
		</div>

		<div class="pure-controls">
			<button type="submit" class="pure-button pure-button-primary">Save</button>
		</div>
	</fieldset>
</form>

<script>
$(window).bind("load", function() {

	var id           = $("#id");
	var item         = $("#item");
	var group        = $("#group");
	var price        = $("#price");
	var date         = $("#date");
	var suggestGroup = $("#suggest-group");

	var ajaxSuggestPredictGroups;
	var ajaxSuggestPredictPrice;

    var predict = id.val().length ? false : true;

    item.focus();

    new Awesomplete(
        item.get(0),
        {
            minChars: 0,
            autoFirst: true,
            list: <?php echo json_encode($distinct_items); ?>
        }
    );

    item.on('keydown', function(evt) {
        if (evt.keyCode === 13 && $(this).val().length) {
            group.focus();
            initGroupsPrediction();
        }
    });

    new Awesomplete(
        group.get(0),
        {
            minChars: 0,
            autoFirst: true,
            list: <?php echo json_encode($distinct_groups); ?>
        }
    );

	item.on('keyup change', function() {
		initGroupsPrediction();
	});
	group.on('keyup change focus', function() {
		initPricesPrediction();
	});

	function initGroupsPrediction() {
        if (!predict) {
            return;
        }
		var suggest = $("#suggest-group");
		if (ajaxSuggestPredictGroups != null){
			ajaxSuggestPredictGroups.abort();
			ajaxSuggestPredictGroups = null;
		}
		ajaxSuggestPredictGroups = $.ajax({
			url: 'ajax/predictGroups',
			dataType: 'json',
			data: {item: item.val()},
			success: function(data) {
				suggest.html("");
                var firstSelected = false;
				$.each(data, function(i, value){
                    if (firstSelected === false) {
                        group.val(value);
                        group.get(0).setSelectionRange(0, value.length);
                        firstSelected = true;
                    } else {
                        var btn = document.createElement("a");
                        btn.href = "javascript:void(null)";
                        btn.appendChild(document.createTextNode(value));
                        btn.setAttribute("class", "pure-button");
                        suggest.append(btn);
                    }
				});
				initSuggestionButtons();
			}
		});
	}

	function initPricesPrediction() {
        if (!predict) {
            return;
        }
		var suggest = $("#suggest-price");

		if (ajaxSuggestPredictPrice != null){
			ajaxSuggestPredictPrice.abort();
			ajaxSuggestPredictPrice = null;
		}
		ajaxSuggestPredictPrice = $.ajax({
			url: 'ajax/predictPrice',
			dataType: 'json',
			data: {item:item.val(), group:group.val()},
			success: function(data) {
				suggest.html("");
                var firstSelected = false;
                $.each(data, function(i, value){
                    var amount = value.amount / 100;
                    if (firstSelected === false) {
                        price.val(amount);
                        price.get(0).setSelectionRange(0, amount.length);
                        firstSelected = true;
                    } else {
                        var btn = document.createElement("a");
                        btn.href = "javascript:void(null)";
                        btn.appendChild(document.createTextNode(amount));
                        btn.setAttribute("class", "pure-button");
                        suggest.append(btn);
                    }
				});
                initSuggestionButtons();
			}
		});
	}

	function initSuggestionButtons() {
		var childrenGroup = $("#suggest-group").children();
		$.each(childrenGroup, function(index){
			$(this).click(function() {
				group.val(childrenGroup[index].innerHTML);
				price.focus();
				initPricesPrediction();
			});
		});

		var childrenPrice = $("#suggest-price").children();
		$.each(childrenPrice, function(index){
			$(this).click(function() {
				price.val(childrenPrice[index].innerHTML);
				date.focus();
			});
		});
	}
});
</script>