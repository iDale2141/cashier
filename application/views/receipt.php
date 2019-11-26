
<html>
	<head>
		<title id="page_title"></title>
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
		$("#page_title").html(window.title);
		var total = 0.00;
		if(window.rows){
			var tr = "";
			$.each(window.rows, function(index, val) {
				total = parseFloat(val.amount) + parseFloat(total)
				tr +=  "<tr class='font12'>\
							 <td>" + val.particular + " </td>\
							 <td style='padding-left:20px;'>" + val.amount + " </td>\
						</tr>";
			});
			$("#particulars").html(tr);
		}
		$("#name").html(window.name)
		$("#address").html(window.address)
		// $("#amt").html(window.amount)
		$("#amt").html(total.toFixed(2))
		$("#date").html(window.date)
		// var amt_words = (numberToWords(window.amount)).toUpperCase() + "PESOS ONLY"
		var amt_words = (numberToWords(total)).toUpperCase() + "PESOS ONLY"
		$("#amt_words").html(amt_words)
		window.print();
	});

	function numberToWords(s){
		var th = ['','thousand','million', 'billion','trillion'];
				var dg = ['zero','one','two','three','four', 'five','six','seven','eight','nine'];
				var tn = ['ten','eleven','twelve','thirteen', 'fourteen','fifteen','sixteen', 'seventeen','eighteen','nineteen'];
				var tw = ['twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety']; 
				
				s = (s||'').toString(); s = s.replace(/[\, ]/g,''); if (s != parseFloat(s)) return 'not a number'; var x = s.indexOf('.'); if (x == -1) x = s.length; if (x > 15) return 'too big'; var n = s.split(''); var str = ''; var sk = 0; for (var i=0; i < x; i++) {if ((x-i)%3==2) {if (n[i] == '1') {str += tn[Number(n[i+1])] + ' '; i++; sk=1;} else if (n[i]!=0) {str += tw[n[i]-2] + ' ';sk=1;}} else if (n[i]!=0) {str += dg[n[i]] +' '; if ((x-i)%3==0) str += 'hundred ';sk=1;} if ((x-i)%3==1) {if (sk) str += th[(x-i-1)/3] + ' ';sk=0;}} if (x != s.length) {var y = s.length; str += 'point '; for (var i=x+1; i<y; i++) str += dg[n[i]] +' ';} return str.replace(/\s+/g,' ');
	}
	

</script>

<style>

	.font12 {
		font-size: 12px;
	}
</style>