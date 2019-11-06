
<html>
	<head>
		<link href="<?= base_url('public/color-admin/assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" />
	</head>
	<body>
		
		<span id="name"></span>
		<span id="address"></span>
	
		<table>
			<tbody id="particulars"></tbody>
		</table>

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
			$("#particulars").html(tr);
		}
		$("#name").text(window.name)
		$("#address").text(window.address)
	});
</script>