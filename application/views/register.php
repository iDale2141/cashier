<div class="container">
	
	<div class="row">
		
		<div class="col-lg-4"></div>
		<div class="col-lg-4">
			<div class="wrapper">
				<form method="POST" action="<?= base_url('auth/register'); ?>" class="form-signin">
					<h2 class="form-signin-heading">Register</h2>
					<input type="text" class="form-control" name="username" placeholder="Username" required="required" autocomplete="off" value="<?php echo set_value('username'); ?>">
					<input type="password" class="form-control" name="password" placeholder="Password" required="required" autocomplete="new-password">
					<input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required="required" autocomplete="new-password">
					<!-- <input type="password" class="form-control" name="admin_password" placeholder="Admin Password" required="required" autocomplete="new-password"> -->
					<input type="submit" class="btn btn-lg btn-primary btn-block" value="Login" <?= ($status == true) ? 'disabled' : '' ?> >
					
					<?php if(validation_errors()): ?>
						<div class="alert alert-danger" role="alert">
							<?php echo validation_errors(); ?>
						</div>
					<?php endif; ?>
				</form>	
			</div>

		</div>
		<div class="col-lg-4"></div>

	</div>

</div>

<style>

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

	.checkbox {
	  	font-weight: normal;
	}

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

	input {
		margin-bottom: 5px;
	}

</style>