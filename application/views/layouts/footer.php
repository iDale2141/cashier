

	
	</div>
	
	</body>
	
	

	<script>
		$(document).ready(function() {
			App.init();
			// fee_sched_now();
		});

		function numberWithCommas(x) {
		    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ", ");
		}



		function fee_sched_now(){

			var sy  = "";
			var sem = "";

			var today = new Date();
			var mm = String(today.getMonth() + 1).padStart(2, '0') - 1; //January is 0!
			var y  = today.getFullYear();

			sy = y + "-" + (y+1);	

			if( mm <= 7 ){
				sem = "2nd";
			}
			else{
				sem = "1st";
			}
			$("#school_year option[value="+sy+"]").attr('selected', 'selected');
			$("#sem option[value="+sem+"]").attr('selected', 'selected');
		}
		

	</script>

</html>