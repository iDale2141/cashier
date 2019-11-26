<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
	
	<head>
		<title>ACLC | Cashier</title>
		<link rel="icon" href="<?php echo base_url('public/img/logo/acs_logo.ico')?>" type="image/png">
		<meta charset="utf-8">
		
		<!-- ================== BEGIN BASE CSS STYLE ================== -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
		<link href="<?= base_url('public/color-admin/assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css') ?>" rel="stylesheet" />
		<link href="<?= base_url('public/color-admin/assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" />
		<link href="<?= base_url('public/color-admin/assets/plugins/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet" />
		<link href="<?= base_url('public/color-admin/assets/css/animate.min.css') ?>" rel="stylesheet" />
		<link href="<?= base_url('public/color-admin/assets/css/style.min.css') ?>" rel="stylesheet" />
		<link href="<?= base_url('public/color-admin/assets/css/style-responsive.min.css') ?>" rel="stylesheet" />
		<link href="<?= base_url('public/color-admin/assets/css/theme/default.css') ?>" rel="stylesheet" id="theme" />
		<link href="<?= base_url('public/css/tableexport.min.css') ?>" rel="stylesheet"/>
		<!-- ================== END BASE CSS STYLE ================== -->
		
		<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
		<link href="<?= base_url('public/color-admin/assets/plugins/jquery-jvectormap/jquery-jvectormap.css') ?>" rel="stylesheet" />
		<link href="<?= base_url('public/color-admin/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') ?>" rel="stylesheet" />
	    <link href="<?= base_url('public/color-admin/assets/plugins/gritter/css/jquery.gritter.css') ?>" rel="stylesheet" />
		<!-- ================== END PAGE LEVEL STYLE ================== -->
		
		<link href="<?= base_url('public/css/jquery-confirm.min.css') ?>" rel="stylesheet" />

	</head>


	<!-- ================== BEGIN BASE JS ================== -->
	<script src="<?= base_url('public/color-admin/assets/plugins/pace/pace.min.js') ?>"></script>
	<script src="<?= base_url('public/color-admin/assets/plugins/jquery/jquery-1.9.1.min.js')?>"></script>
	<script src="<?= base_url('public/color-admin/assets/plugins/jquery/jquery-migrate-1.1.0.min.js')?>"></script>
	<script src="<?= base_url('public/color-admin/assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js')?>"></script>
	<script src="<?= base_url('public/color-admin/assets/plugins/bootstrap/js/bootstrap.min.js') ?>"></script>

	<script src="<?= base_url('public/color-admin/assets/plugins/slimscroll/jquery.slimscroll.min.js')?>"></script>
	<script src="<?= base_url('public/color-admin/assets/plugins/jquery-cookie/jquery.cookie.js') ?>"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="<?= base_url('public/color-admin/assets/plugins/gritter/js/jquery.gritter.js') ?>"></script>
	<script src="<?= base_url('public/color-admin/assets/plugins/flot/jquery.flot.min.js') ?>"></script>
	<script src="<?= base_url('public/color-admin/assets/plugins/flot/jquery.flot.time.min.js') ?>"></script>
	<script src="<?= base_url('public/color-admin/assets/plugins/flot/jquery.flot.resize.min.js') ?>"></script>
	<script src="<?= base_url('public/color-admin/assets/plugins/flot/jquery.flot.pie.min.js') ?>"></script>
	<script src="<?= base_url('public/color-admin/assets/plugins/sparkline/jquery.sparkline.js') ?>"></script>
	<script src="<?= base_url('public/color-admin/assets/plugins/jquery-jvectormap/jquery-jvectormap.min.js') ?>"></script>
	<script src="<?= base_url('public/color-admin/assets/plugins/jquery-jvectormap/jquery-jvectormap-world-mill-en.js') ?>"></script>
	<script src="<?= base_url('public/color-admin/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') ?>"></script>
	<script src="<?= base_url('public/color-admin/assets/plugins/jquery-autocomplete/jquery-autocomplete.min.js') ?>"></script>
	<script src="<?= base_url('public/color-admin/assets/js/dashboard.min.js') ?>"></script>
	<script src="<?= base_url('public/color-admin/assets/js/apps.min.js') ?>"></script>
	<script src="<?= base_url('public/js/jquery-confirm.min.js') ?>"></script>
	<script src="<?= base_url('public/js/vue.js') ?>"></script>
	<script src="<?= base_url('public/js/axios.min.js') ?>"></script>
	<script src="<?= base_url('public/js/jquery.dataTables.min.js') ?>"></script>
	<script src="<?= base_url('public/js/sweetalert.min.js') ?>"></script>
	<script src="<?= base_url('public/js/xlsx.core.min.js') ?>"></script>
	<script src="<?= base_url('public/js/FileSaver.js') ?>"></script>
	<script src="<?= base_url('public/js/tableexport.min.js') ?>"></script>
	<!-- <script src="https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.js"></script> -->
	<!-- <script src="https://unpkg.com/axios/dist/axios.min.js"></script> -->
	<!-- <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> -->
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script> -->
	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<style>
		#ui-id-1 {
			z-index: 9999 !important;
		}
		.tooltip {
		  position: relative;
		  display: inline-block;
		  border-bottom: 1px dotted black;
		}

		.tooltip .tooltiptext {
		  visibility: hidden;
		  width: 120px;
		  background-color: black;
		  color: #fff;
		  text-align: center;
		  border-radius: 6px;
		  padding: 5px 0;

		  /* Position the tooltip */
		  position: absolute;
		  z-index: 1;
		}

		.tooltip:hover .tooltiptext {
		  visibility: visible;
		}

		.hr-1 {
			border:1px #eee solid !important;
		}

		.bdg-green {
			background-color:#31A3A3 !important;
		}
		.bdg-greener {
			background-color:#1D8888 !important;
		}

	</style>

	<body style="background: #eee !important;">
		<?php if($this->session->has_userdata('user_data')): ?>
			<?php 
				$user_data = $this->session->userdata('user_data');
				$username  = ucfirst($user_data['user_data']->username);
			?>
			<?php if(isset($class) != 'reports'): ?>
				<div id="page-container" class="fade page-without-sidebar page-header-fixed">				
					<div id="header" class="header navbar navbar-default navbar-fixed-top">
						<div class="container-fluid">
							<div class="navbar-header">
								<a href="#" class="navbar-brand"><span class="navbar-logo"></span><b>ACLC Cashiering</b></a>
								<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
							</div>
							<ul class="nav navbar-nav navbar-right">
								<li>
									<form class="navbar-form full-width">
										<div class="form-group">
											<input type="text" class="form-control" id="selected_student" placeholder="Search student here" autofocus>
											<button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
										</div>
									</form>
								</li>
								<li class="dropdown navbar-user"> 
									<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
										<img src="<?= base_url('public/color-admin/assets/img/user-13.jpg')?>" alt=""> 
										<span class="hidden-xs"><?= $username; ?></span> <b class="caret"></b>
									</a>
									<ul class="dropdown-menu animated fadeInLeft">
										<li class="arrow"></li>
										<!-- <li><a href="javascript:;">Edit Profile</a></li>
										<li><a href="javascript:;"><span class="badge badge-danger pull-right">2</span> Inbox</a></li>
										<li><a href="javascript:;">Calendar</a></li>
										<li><a href="javascript:;">Setting</a></li>
										<li class="divider"></li> -->
										<li><a href="/cashier2/auth/logout">Log Out</a></li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
			<?php endif; ?>
		<?php endif; ?>