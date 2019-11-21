<!-- <h3>BUTUAN INFORMATION TECHNOLOGY SERVICES INC.</h3>
<p>Franchisee of ACLC College, 999 HDS Bldg. J.C. Aquino Avenue, Butuan City</p> -->

<html>

	<head>

		<link href="<?= base_url('public/color-admin/assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" />

	</head>

	<body>
		<div style="padding-left: 30px;">
			
			<table>
				<tr>
					<td style="padding-top: 10px;">
						<h4><b>BUTUAN INFORMATION TECHNOLOGY SERVICES INC.</b></h4>
					</td>
					<td width="300" style="text-align: center;"><h3><b id="or_served"></b></h3></td>
				</tr>
				<tr style="font-size: 13px; position: absolute; margin-top: -10px;">
					<td><p>Franchisee of ACLC College, 999 HDS Bldg. J.C. Aquino Avenue, Butuan City</p></td>
					<td width="300" style="text-align: center;">Nov. 04, 2019</td>
				</tr>
			</table>

			<h4 class="text-center" style="padding-top: 30px;"><b>ACKNOWLEDGEMENT RECEIPT</b></h4><br>
			
			<div class="row">
				<div class="col-xs-3">
					<table style="font-size: 12px;" id="particularsack">
						<tr>
							<td width="120">Certification</td>
							<td>50.00</td>
						</tr>
					</table>
				</div>	
				<div class="col-xs-9">
					<table>
						<tr><td><b>Received from:</b></td></tr>
						<tr><td class="p5" id="name"></td></tr>
						<tr><td><b>Amount:</b> </td></tr>
						<tr><td><span id="amt_words"></span>(P <span class="amount"></span>)</td></tr>
					</table>
				</div>
			</div>
			<div class="row" style="padding-top: 30px;">
				<div class="col-xs-3">
					<table style="font-size: 12px;">
						<tr>
							<td width="120"><b>Total</b></td>
							<td class="amount"></td>
						</tr>
					</table>
				</div>	
				<div class="col-xs-4"></div>
				<div class="col-xs-5">
					<br><br>
					<b style="border-top:1px solid black; padding:0px 100px 0px 100px;">Cashier</b>

				</div>
			</div>
		</div>
	</body>

	<script src="<?= base_url('public/color-admin/assets/plugins/jquery/jquery-1.9.1.min.js')?>"></script>

</html>

<script>
	$(function(){
		if(window.rows){
			var tr = "";
			$.each(window.rows, function(index, val) {
				tr +=  "<tr>\
							 <td>" + val.particular + " </td>\
							 <td style='padding-left:20px;'>" + val.amount + " </td>\
						</tr>";
			});
			$("#particularsack").html(tr);
		}
		$("#name").html(window.name);
		$("#or_served").html(window.or_served);
		$(".amount").html(window.amount);
		var amt_words = (window.amt_words).toUpperCase() + "PESOS ONLY"
		$("#amt_words").html(amt_words)
		window.print();
	})
</script>

<style>
		
	.p5 {
		padding-bottom: 5px !important;
	}

</style>