
<div class="jumbotron" style="padding:10px; min-height: 400px;">
	<div class="row">
		<div class="col-lg-7">				
			<h5>
				<i class="fa fa-list"></i> Fee Summary
				<select v-on:change='select_change($event)' name="fee_type" id="fee_type" class="pull-right input-sm" data-type='fee_type' v-model="fee_type">
					<option value="regular">Regular Fees</option>
                    <!-- <option value="special">Special Fees</option> -->
                    <option value="other">Special / Other Payments</option>
				</select>
				<hr style="border:0.5px solid #777 !important;">
			</h5>
	
			<div id="payment_content">
				<div class="table-responsive" v-if="fee_type == 'regular'">
					<table class="table table-hover" id="fee_summary_tbl">
						<thead>
							<th>School Year</th>
							<th>Semester</th>
							<th>Fee Type</th>
							<th>Assessment <small>/discount</small></th>
							<th>Paid</th>
							<th>Balance</th>
						</thead>
						<tbody>
							<tr v-if='fee_summary.length == 0'>
								<td colspan="6" class="text-center"> <i>*** Please select student ***</i> </td>
							</tr>
							<tr v-for="fs in fee_summary['regular_fees']" v-bind:class='regular_hide' class="fee_summary_row" v-bind:data-sy="fs.sy" v-bind:data-sem="fs.sem" v-on:click="view_summary_details($event)" data-toggle="modal" data-target="#summary_details_modal" @contextmenu="add_to_payments($event)" data-type="regular" v-bind:data-balance="fs.remaining_balance">
								<td> {{ fs.sy }} </td>
								<td> {{ fs.sem }} </td>
								<td> REGULAR </td>
								<td> {{ formatPrice(fs.total_2_discounted) }} <small v-if="fs.discount">(-{{fs.discount}})</small></td>
								<td> {{ formatPrice(fs.paid2) }} </td>
								<td> {{ formatPrice(fs.remaining_balance) }} </td>
							</tr>		
							<tr v-if="(has_selected) && (fee_summary['old_system'].total_amount > 0)" v-bind:class="regular_hide" class="fee_summary_row" data-toggle="modal" data-target="#old_acc_summary" @contextmenu="add_to_payments($event)" data-type="old_system">
								<td colspan="3">OLD SYSTEM</td>
								<td v-for="(fs, key) in fee_summary['old_system']" v-if="key != 'particulars' && key != 'payments'"> {{ formatPrice(fs) }} </td>
							</tr>
						</tbody>
					</table>
				</div>

				<div v-if="fee_type == 'other'">
					<table class="table table-hover">
						<thead>
							<th>Particular</th>
							<th>Price</th>
							<th>Quantity</th>
							<th>Subtotal</th>
							<th class="text-center">Action</th>
						</thead>
						<tbody>
							<tr v-for="(particular, key) in selected_particulars" v-if="selected_particulars.length">
								<td>{{particular.name}}</td>
								<td>{{particular.price}}</td>
								<td><input type="number" class="form-control input-xs" min="1" value="1" style="width:80px !important;" v-on:change="calc_otherp_total($event)" :data-id="particular.id"></td>
								<td>{{particular.subtotal}}</td>
								<td class="text-center">
									<button class="btn btn-default btn-xs" :data-key="key" v-on:click="remove_selectedp($event)" :data-price="particular.price">
										<span class="fa fa-minus"></span>
									</button>
								</td>
							</tr>
							<tr v-if="selected_particulars.length">
								<td colspan="3" class="text-right"><b>Total</b></td>
								<td colspan="2"><b>{{selected_particulars_total}}</b></td>
							</tr>
							<tr v-if="selected_particulars.length == 0">
								<td colspan="5" class="text-center"><i> <span class="fa fa-arrow-right" v-for="index in 3"></span> Select particulars first <span class="fa fa-arrow-right" v-for="index in 3"></span></i></td>
							</tr>
						</tbody>
					</table>
				</div>
	
			</div>
		</div>	
		<div class="col-lg-3">
			<div v-if="fee_type == 'regular'">
				<h5>
					<i class="fa fa-calendar"></i> Payment Schedule
					<hr style="border:0.5px solid #777 !important;">
				</h5>
				<div class="row" style="padding:0px 10px 0px 10px;">
					<div class="row">
						<div class="col-lg-4">
							<select name="school_year" id="school_year" class="form-control input-sm" data-type="sy" v-on:change='select_change($event)'>
								<!-- <option disabled selected v-if="!sy_regular.length && !sy_special.length"> School Year </option> -->
								<option disabled selected> School Year </option>
								<option v-for="sr in sy_regular" v-bind:class="regular_hide"> {{ sr }} </option>
								<!-- <option v-for="sr in sy_special" v-bind:class="special_hide"> {{ sr }} </option> -->
							</select>
						</div>
						<div class="col-lg-4">
							<select name="sem" id="sem" class="form-control input-sm" data-type="sem" v-on:change='select_change($event)'>
								<option value="1st">1st</option>
								<option value="2nd">2nd</option>
							</select>
						</div>
						<div class="col-lg-4">
							<select name="period" id="period" class="form-control input-sm" data-type="period" v-on:change='select_change($event)'>
								<option value="prelim">Prelim</option>
								<option value="midterm">Midterm</option>
								<option value="prefinal">Prefinal</option>
								<option value="Final">Final</option>
							</select>
						</div>
					</div>
					<div class="row" style="padding:10px 10px 0px 10px;">
						<table class="table table-hover" id="payment_sched_tbl">
							<tbody v-if="!has_selected">
								<tr>	
									<td class="text-center"><i>*** Please select student ***</i></td>
								</tr>
							</tbody>
							<tbody v-if="has_selected && ps_year == ''">
								<tr>
									<td class="text-center"><i>*** Please school year ***</i></td>
								</tr>
							</tbody>
							<tbody v-if="periods.is_empty === false && periods.is_current">
								<tr>
									<td class="w125p">Prelim</td>
									<td><b>&#8369; {{ formatPrice((parseFloat(periods.prelim)).toFixed(2)) }} </b></td>
								</tr>
								<tr>
									<td class="w125p">Midterm</td>
									<td><b>&#8369; {{ formatPrice((parseFloat(periods.midterm)).toFixed(2)) }} </b></td>
								</tr>
								<tr>
									<td class="w125p">Prefinal</td>
									<td><b>&#8369; {{ formatPrice((parseFloat(periods.prefinal)).toFixed(2)) }} </b></td>
								</tr>
								<tr>
									<td class="w125p">Final</td>
									<td><b>&#8369; {{ formatPrice((parseFloat(periods.final)).toFixed(2)) }} </b></td>
								</tr>
							</tbody>
							<tbody v-if="periods.is_empty === false && periods.is_current === false">
								<tr>
									<td class="w125p">Total Balance</td>
									<td><b>&#8369; {{ formatPrice((parseFloat(periods.total)).toFixed(2)) }}</b></td>
								</tr>
							</tbody>
							<tbody v-if="periods.is_empty === true">
								<tr>
									<td class="text-center"><i>*** Empty result ***</i></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div v-if="fee_type == 'other'">
				<h5>
					<i class="fa fa-list"></i> Particular list
					<hr style="border:0.5px solid #777 !important;">
				</h5>
				<div class="row" style="padding:0px 10px 0px 10px;">
					<div class="form-group">
						<div class="row">
							<div class="col-lg-6"></div>
							<div class="col-lg-6">
                                <input type="text" class="form-control" id="select_otherp" placeholder="Search particular here" v-on:keyup="view_particulars($event)">
							</div>
						</div>
					</div>
					<table class="table table-hover">
						<thead>
							<th>Particular</th>
							<th>Type</th>
							<th>Price</th>
							<th class="text-center">Action</th>
						</thead>
						<tbody>
							<tr v-for="particular in get_other_particulars">
								<td>{{particular.particularName}}</td>
								<td>{{capitalize(particular.billType)}}</td>
								<td>{{particular.amt2}}</td>
								<td class="text-center">
									<button class="btn btn-success btn-xs" v-on:click="submit_to_list($event)" :data-id="particular.particularId" :data-name="particular.particularName" :data-price="particular.amt2">
										<span class="fa fa-plus"></span>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-lg-2" style="border-left: solid #777 1px;min-height: 500px;">
			<div v-if="fee_type == 'regular'">
				<h5 class="text-center">
					<i class="fa fa-check"></i> Selected payments <i class="fa fa-check"></i><br>
					<hr style="border:0.5px solid #777 !important;">
				</h5>
				<div class="text-center">			
					<button class="btn btn-sm btn-success" v-if="has_current_bill == false && has_selected" v-on:click="add_to_payments($event)" data-type="down_payment" data-balance="1" data-sy="NA" data-sem="NA">Down Payment</button>
				</div><br>
				<div class="table-responsive" v-if="fee_type == 'regular'">
					<table class="table table-striped table-hover table-bordered table-condensed">
						<tr v-for="(fp, key) in formatted_payments" v-if="to_be_paid" @contextmenu="remove_from_payments($event)" v-bind:data-key="fp.fp_key">
							<td>{{fp.sy}}</td>
							<td>{{fp.sem}}</td>
						</tr>
					</table>
				</div>
			</div>
			<div v-else>
				<h5>
					<i class="fa fa-money"></i> Payment History<br>
					<hr style="border:0.5px solid #777 !important;">
				</h5>
				<div class="table-responsive pre-scrollable" style="min-height: 300px !important;">
					<table class="table table-hover table-condensed">
						<thead>
							<th>Particular</th>
							<th>Amount</th>
						</thead>
						<tbody>
							<tr v-for="sp in fee_summary.special_payments">
								<td>{{sp.particularName}}</td>
								<td>{{formatPrice(sp.paid_amount)}}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="modal" id="summary_details_modal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content" style="background: #eee !important;">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title text-center">Student Bill and Payment Transaction Records</h5>
					<h5 class="modal-title text-center"><b>{{selected_sum_sy}} | {{selected_sum_sem}}</b></h5>
					<select id="fs_breakdown" class="input-sm pull-right" v-on:change="summary_details_switch($event)">
						<option value="student_bill">Student Bills</option>
						<option value="payments">Payment Transactions</option>
					</select>
				</div>
		  		<div class="modal-body">

					<table class="table table-hover sd_detail_tbl" v-bind:class="bill_visibility">
						<thead>
							<th>Particular</th>
							<th>Price</th>
							<th>Paid Amount</th>
						</thead>
						<tbody>
							<tr v-for="(sd, index ) in summary_details_bills">
								<td v-html="sd.particular">{{sd.particular}}</td>
								<td>{{sd.price2}}</td>
								<td>{{sd.paid2}}</td>
							</tr>
						</tbody>
					</table>
					<table class="table table-hover" v-bind:class="payment_visibility">
						<thead>
							<th>OR No.</th>
							<th>Amount</th>
							<th>Printing Type</th>
							<th>Payment Date</th>
							<th>Payment Status</th>
						</thead>
						<tbody v-if="summary_details_payments.length == 0">
							<tr>
								<td colspan="6" class="text-center"><i>*** Empty result ***</i></td>
							</tr>
						</tbody>
						<tbody>
							<tr v-for="(sdp, index) in summary_details_payments" class="payment_row" v-on:click="or_detail_modal(index)">
								<td>{{index}}</td>
								<td>{{formatPrice(sdp.amount)}}</td>
								<td>{{sdp.printing_type}}</td>
								<td>{{sdp.payment_date}}</td>
								<td>{{sdp.payment_status}}</td>
							</tr>
						</tbody>
					</table>

		  		</div>
		  		<div class="modal-footer">
		    		<!-- <button type="button" class="btn btn-sm btn-danger btn-default" data-dismiss="modal">Close</button> -->
		  		</div>
			</div>
		</div>
	</div>

	<div id="or_details" class="modal" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
		  		<div class="modal-header">
			    	<button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="#summary_details_modal">&times;</button>
			    	<h4 class="modal-title"><small>OR</small> <b>#{{current_or}}</b> <small>Breakdown</small></h4>
			  	</div>
			  	<div class="modal-body">
					<table class="table table-striped table-hover">
						<thead>
							<th>Particular</th>
							<th>Paid Amount</th>
						</thead>
						<tbody>
							<tr v-for="(aod, index) in all_or_details">
								<td>{{ aod.particular }}</td>
								<td>{{ aod.paid2 }}</td>
							</tr>
						</tbody>
					</table>
			  	</div>
			  	<div class="modal-footer">
					<div class="btn-group">
				    	<button v-if="current_or_status != 'Cancelled'" type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Cancel OR</button>
				    	<div class="dropdown-menu">
							<ul class="list-unstyled text-center">
								<li>Are you sure?</li>
								<li><a class="dropdown-item" href="#" v-on:click="cancel_payment('Cancelled')">Yes</a></li>
								<li><a class="dropdown-item" href="#">No</a></li>
							</ul>
					  	</div>
				  	</div>
			  	</div>
			</div>
		</div>
	</div>

	<div class="modal" id="old_acc_summary" v-if="fee_type != 'other'">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" style="background: #eee !important;">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title text-center">Student Bill and Payment Transaction Records</h5>
					<h5 class="modal-title text-center"><b>Old System Account</b></h5>
				</div>
		  		<div class="modal-body">

					<ul class="nav nav-tabs nav-justified nav-justified-mobile" data-sortable-id="index-2">
						<li class="active"><a href="#os_bills" data-toggle="tab"><i class="fa fa-money m-r-5"></i> <span class="hidden-xs">Student Bills</span></a></li>
						<li class=""><a href="#os_payments" data-toggle="tab"><i class="fa fa-shopping-cart m-r-5"></i> <span class="hidden-xs">Payments</span></a></li>
					</ul>

					<div class="tab-content">
					  	<div id="os_bills" class="tab-pane fade in active">
    						<table class="table table-hover table-condensed">
								<thead>
									<th>A.Y</th>
									<th>Sem</th>
									<th>Particular</th>
									<th>Price</th>
									<th>Paid Amount</th>
								</thead>
								<tbody>
									<tr v-for="p in fee_summary['old_system'].particulars">
										<td>{{ p.oaSy }}</td>
										<td>{{ p.oaSem }}</td>
										<td>{{ p.oaParticularName.toUpperCase() }}</td>
										<td>{{ formatPrice(p.oaAmount) }}</td>
										<td>{{ formatPrice(p.total_paid) }}</td>
									</tr>
								</tbody>
							</table>
					  	</div>
					  	<div id="os_payments" class="tab-pane fade">
					    	<table class="table table-hover table-condensed">
								<thead>
									<th>OR No.</th>
									<th>Payment Date</th>
									<th>Receipt</th>
									<th>Paid Amount</th>
								</thead>
								<tbody>
									<tr v-for="(p, key) in fee_summary['old_system'].payments" class="payment_row" title="View breakdown" :data-or="key" @click="os_or_breakdown($event)">
										<td>{{ p.or }}</td>
										<td>{{ p.date }}</td>
										<td>{{ p.receipt }}</td>
										<td>{{ p.amount }}</td>
									</tr>
								</tbody>
							</table>
					  	</div>
					</div>

		  		</div>
		  		<div class="modal-footer">
		    		<!-- <button type="button" class="btn btn-sm btn-danger btn-default" data-dismiss="modal">Close</button> -->
		  		</div>
			</div>
		</div>
	</div>

	<div class="modal" id="payment_modal">
		<div class="modal-dialog">
			<div class="modal-content">
	
				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal" class="pull-right">&times;</button><br>
				</div>
	
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

</div>

<div class="modal" id="os_or_breakdown" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-body">
				<button type="button" class="close" @click="os_or_breakdown()">&times;</button>
				<h4>OR #{{selected_os_or.or}} Breakdown</h4>
				<hr>
				<table class="table table-hover">
					<thead>
						<th>Particular Name</th>
						<th>Price</th>
						<th>Paid Amount</th>
					</thead>
					<tbody>
						<tr v-for="(data, key) in selected_os_or['data']">
							<td>{{ucwords(data.oaParticularName)}}</td>
							<td>{{data.oaAmount}}</td>
							<td>{{data.paymentAmount}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="payment_distribution_modal" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
				<h4>Distribute Cash</h4>
				<hr>
					<h4>Cash: {{ cash }}</h4>
					<h4>Remaining: {{ distributed_cash_remaining }}</h4>
					<br>
					<table class="table table-hover table-bordered">
						<thead>
							<th>School year</th>
							<th>Sem</th>
							<th>Remaining Balance</th>
							<th>Distribution</th>
						</thead>
						<tbody>
							<tr v-for="(fp, key) in formatted_payments">
								<td>{{ fp.sy }}</td>
								<td>{{ fp.sem }}</td>
								<td>{{ formatPrice(fp.balance) }}</td>
								<td><input type="number" min="0" ref="all_payments" step="0.01" class="form-control input-sm distribution_inputs" v-on:keyup="process_distribution_amount()" value="0.00" v-bind:data-sy="fp.sy" v-bind:data-sem="fp.sem"></td>
							</tr>
						</tbody>
					</table>
				<hr>
				<div class="text-right">
					<button type="button" class="btn btn-success btn-sm" v-on:click="reg_final_payment()">Submit</button>				
				</div>
			</div>

		</div>
	</div>
</div>