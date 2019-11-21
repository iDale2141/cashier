
<html>
	<head>
		<link href="<?= base_url('public/color-admin/assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" />
	</head>
	<body>
		<div style="padding-top: 120px; padding-left: 120px;">
			<table>
				<tr>
					<td>
						<span id="name"></span><br>
					</td>
					<td style="padding-left: 180px;">
						<span id="date"></span>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td>
						<span id="address" style="padding-top: 10px;"></span>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td>
						<span id="amt_words" style="padding-top: 10px;"></span>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td></td>
					<td>
						<span id="amt" style="padding-left: 180px;"></span>
					</td>
				</tr>
			</table>
			<br>
			<table id="particulars"></table>
		</div>

	</body>
	
	<script src="<?= base_url('public/color-admin/assets/plugins/jquery/jquery-1.9.1.min.js')?>"></script>
</html>


<script>
	$(function(){
		if(window.rows){
			var tr = "";
			$.each(window.rows, function(index, val) {
				tr +=  "<tr class='font12'>\
							 <td>" + val.particular + " </td>\
							 <td style='padding-left:20px;'>" + val.amount + " </td>\
						</tr>";
			});
			$("#particulars").html(tr);
		}
		$("#name").html(window.name)
		$("#address").html(window.address)
		$("#amt").html(window.amount)
		$("#date").html(window.date)
		var amt_words = (window.amt_words).toUpperCase() + "PESOS ONLY"
		$("#amt_words").html(amt_words)
		window.print();
	});
</script>

<style>

	.font12 {
		font-size: 12px;
	}
</style>