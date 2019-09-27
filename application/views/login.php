

<div class="container">
	
	<div class="row">
		
		<div class="col-lg-4"></div>
		<div class="col-lg-4">
			<div class="wrapper">
				<form method="POST" action="auth/verify/" class="form-signin">
					<h2 class="form-signin-heading">ACLC | Cashiering</h2>
					
					<div class="row">
						<div class="col-lg-6" style="padding-right:2px !important;">
							<select name="sy" id="sy" class="form-control">
								<option value="2016-2017">2016-2017</option>
								<option value="2017-2018">2017-2018</option>
								<option value="2018-2019">2018-2019</option>
								<option value="2019-2020">2019-2020</option>
								<option value="2020-2021">2020-2021</option>
								<option value="2021-2022">2021-2022</option>
								<option value="2022-2023">2022-2023</option>
								<option value="2023-2024">2023-2024</option>
							</select>
						</div>

						<div class="col-lg-6" style="padding-left:2px !important;">
							<select name="sem" id="sem" class="form-control">
								<option value="1st">1st</option>
								<option value="2nd">2nd</option>
							</select>
						</div>
					</div>

					<input type="text" class="form-control" name="username" placeholder="Username" required="required" autocomplete="off">
					<input type="password" class="form-control" name="password" placeholder="Password" required="required" autocomplete="new-password">
					<input type="submit" class="btn btn-lg btn-primary btn-block" value="Login">
					<?php if(isset($_SESSION['login_error'])): ?>
						<br>
						<div class="alert alert-danger text-center" role="alert">
							<strong>Error!</strong> Invalid Credentials
						</div>
					<?php endif; ?>
				</form>
			</div>

		</div>
		<div class="col-lg-4"></div>

	</div>

</div>

<style>

	#sy {
		margin-bottom: 10px;
	}

	.wrapper {	
		margin-top: 80px;
	  	margin-bottom: 80px;
	}

	.form-signin {
	  	max-width: 380px;
	  	padding: 15px 35px 45px;
	  	margin: 0 auto;
	  	background-color: #fff;
	  	border: 1px solid rgba(0,0,0,0.1);  
	}
  	.form-signin-heading, .checkbox {
	  	margin-bottom: 30px;
	}
/*
	.checkbox {
	  	font-weight: normal;
	}*/

	.form-control {
	  	position: relative;
	  	font-size: 16px;
	  	height: auto;
	  	padding: 10px;
	 	@include box-sizing(border-box);
		&:focus {
		  	z-index: 2;
		}
	}

	input[type="text"] {
	  	margin-bottom: -1px;
		border-bottom-left-radius: 0;
		border-bottom-right-radius: 0;
	}

	input[type="password"] {
		margin-bottom: 20px;
		border-top-left-radius: 0;
		border-top-right-radius: 0;
	}


</style>

<script>
	
	$(function(){

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
		$("#sy option[value="+sy+"]").attr('selected', 'selected');
		$("#sem option[value="+sem+"]").attr('selected', 'selected');
	})

</script>