
<div class="content" id="app">
	
	<div class="container-fluid">
		<div class="row">
			<div class="profile-section">
                <div class="profile-highlight">

                	<h3 v-if='!has_selected'><i class="fa fa-id-card">&nbsp;</i> <b>{{ name }}</b></h3>
                    <div id="payee_details" v-if='has_selected'>

						<div class="row">
							<div class="col-lg-1" style="padding: 20px 10px 0px 20px;">
		                        <img class="img-responsive img-thumbnail" src="<?= base_url('public/img/superuser.jpg')?>" class="img-responsive" style="min-height: 120px; max-width: 200px; height:100%; width:100%;">
							</div>
							
							<div class="col-lg-10" style="padding-left:20px;">	
							
		                    	<div class="row">
	                    			<h3><i class="fa fa-id-card">&nbsp;</i> <b>{{ name }}</b></h3>
	                    			<hr>
	                    			<div>
			                    		<div class="col-lg-2">
					                        <h4><i class="fa fa-info-circle">&nbsp;</i> <b>School Information</b></h4>
					                        <div class="m-l-5 m-b-0">
					                            <label>•  {{ stud_id }} </label>
					                        </div>
					                        <div class="m-l-5 m-b-0">
					                            <label>•  {{ enrollment_status }} </label>
					                        </div>
					                        <div class="m-l-5 m-b-0">
					                            <label>•  {{ course }}</label>
					                        </div>
					                        <div class="m-l-5 m-b-0" v-if="current_status">
					                            <label>•  {{ current_status }}</label>
					                        </div>
			                    		</div>
			                    		<div class="col-lg-2">
					                        <h4><i class="fa fa-phone-square">&nbsp;</i> <b>Contact Information</b></h4>
					                        <div class="m-l-5 m-b-0">
					                        	<label>• <span v-if="phone_number"> {{ phone_number }} </span> <span v-else> No contact information </span> </label>
					                        </div>
			                    		</div>
			                    		<div class="col-lg-8"></div>
	                    			</div>
		                    	</div>


							</div>


						</div>

                    </div>
                </div>
			</div>
			<ol class="breadcrumb" style="margin-top:30px;">
				<li><a href="javascript:;">Home</a></li>
				<li><a href="javascript:;">Cashier</a></li>
			</ol>
		</div>
	</div>

	<div class="widget-chart with-sidebar bdg-green">		
		<!-- MAIN CONTENT -->
        <div class="widget-chart-content">
            </h3>
            <div style="min-height: 500px;margin-top: 25px;">
				<!-- tabs -->
        		<ul class="nav nav-tabs nav-justified nav-justified-mobile" data-sortable-id="index-2">
					<li class="active"><a href="#payment_tab" data-toggle="tab"><i class="fa fa-money m-r-5"></i> <span class="hidden-xs">Payments</span></a></li>
					<li class=""><a href="#reports_ab" data-toggle="tab"><i class="fa fa-shopping-cart m-r-5"></i> <span class="hidden-xs">Reports</span></a></li>
					<li class=""><a href="#others_tab" data-toggle="tab"><i class="fa fa-envelope m-r-5"></i> <span class="hidden-xs">Other (Payee/Particulars)</span></a></li>
				</ul>
				<!-- tab content -->
				<div class="tab-content" data-sortable-id="index-3">
					<div class="tab-pane fade active in" id="payment_tab">
						<?php $this->load->view('pages/payments'); ?>
					</div>
					<div class="tab-pane fade" id="reports_ab">
						<?php $this->load->view('pages/reports'); ?>	
					</div>
					<div class="tab-pane fade" id="others_tab">
						<?php $this->load->view('pages/others'); ?>
					</div>
				</div>
            </div>
        </div>
        <div class="widget-chart-sidebar bdg-greener" style="padding:10px !important;">

            <div id="visitors-donut-chart" style="height: 160px">

				<div class="wrapper" style="padding:10px 0px 0px !important;">
					<form>
						<h5 class="text-white"><span class="fa fa-calculator"></span> Payment</h5>
						<br>
						<div class="mb5">
							<input type="text" class="form-control input-sm" placeholder="OR NUMBER" v-model="or_served">
						</div>
						<div class="mb5">
							<select class="form-control input-sm" v-model="receipt">
								<option value="OR">Official Receipt</option>
								<option value="AR">Acknowledgement Receipt</option>
								<option value="TR">Temporary Receipt</option>
							</select>
						</div>
						<div class="mb5">
							<input type="date" class="form-control input-sm" v-model="payment_date">
						</div>
						<hr>
						<div class="form-group mb5">
						  	<label for="to_pay">Amount to pay</label>
							<input type="text" class="form-control input-sm" id="to_pay" v-model="to_pay" v-on:keyup="calc_change()">
						</div>
						<div class="form-group mb5">
						  	<label for="to_pay">Cash</label>
							<input type="text" class="form-control input-sm" id="cash" v-model="cash" v-on:keyup="calc_change()">
						</div>
						<div class="form-group mb5">
						  	<label for="to_pay">Change</label>
							<input type="text" disabled class="form-control input-sm" id="change" v-model="change">
						</div><br>
						<div class="form-group mb5">
							<button type="button" class="btn btn-lg btn-primary form-control" style="min-height: 70px; font-size: 20px;" v-on:click="submit_payment()">PAY</button>
						</div>
					</form>

				</div>

            </div>
            <ul class="chart-legend">
                <li><i class="fa fa-circle-o fa-fw text-success m-r-5"></i> 34.0% <span>New Visitors</span></li>
                <li><i class="fa fa-circle-o fa-fw text-primary m-r-5"></i> 56.0% <span>Return Visitors</span></li>
            </ul>
        </div>
		<!-- tab content -->
    </div>

	
</div>
