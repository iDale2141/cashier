

<script>

	var sb = new Vue({
		el: '#app',
		data: {
			// payments
			ses_sy : "<?= $this->session->get_userdata('user_data')['user_data']['sy'] ?>",
			ses_sem : "<?= $this->session->get_userdata('user_data')['user_data']['sem'] ?>",
			has_selected: false,
			name : 'Please select student',
			ssi_id: '',
			stud_id : '',
			enrollment_status : '',
			course : '',
			current_status: '',
			course_type : '',
			phone_number : '',
			fee_summary : {
				old_system: []				
			},
			has_current_bill: false,
			fee_type: 'regular',
			regular_hide: '',
			special_hide: '',
			sy_regular: [],
			sy_special: [],
			ps_sem: '1st',
			ps_year: '',
			ps_period: 'prelim',
			selected_sum_sy: '',
			selected_sum_sem: '',
			periods: {
				prelim: '',
				midterm: '',
				prefinal: '',
				final: '',
				total: '',
				is_current: '',
				is_empty: ''
			},
			summary_details_bills: {},
			summary_details_payments: {},
			all_or_details: {},
			current_or: '',
			current_or_status: '',
			current_or_id: '',
			tab_key: 0,
			bill_visibility: '',
			payment_visibility: 'hidden',
			// other payees
			other_payees: {},
			other_payees_form : {
				fname: '',
				mname: '',
				lname: '',
				ext: '',
				address: '',
				isValid: '',
			},
			other_payees_modal:{
				id: '',
				fname: '',
				mname: '',
				lname: '',
				ext: '',
				address: '',
			},
			// other particulars
			add_particular_form: {
				particular_type: 'special',
				particular: '',
				price: 0,
			},
			get_other_particulars: {},
			selected_particulars: [],
			selected_particulars_total: 0,
			selected_os_or: {
				or: "",
				data: []
			},
			// payment_form
			or_served: 'sample',
			to_pay: '0',
			cash: '0',
			change: '0',
			distributed_cash_remaining: '0',
			to_be_paid: [],
			formatted_payments: [],
			final_payments: [],
			receipt: 'OR',
			payment_date: "<?php echo date('Y-m-d'); ?>"
		},
		created: function () {
			this.autocomplete();
			this.select_change();
			this.reg_payees_dtable();
			this.view_particulars();
		},
		methods: {
			defaultData: function(){
				var a = {
					ses_sy : "<?= $this->session->get_userdata('user_data')['user_data']['sy'] ?>",
					ses_sem : "<?= $this->session->get_userdata('user_data')['user_data']['sem'] ?>",
					has_selected: false,
					name : 'Please select student',
					ssi_id: '',
					stud_id : '',
					enrollment_status : '',
					course : '',
					phone_number : '',
					fee_summary : [],
					fee_type: 'regular',
					regular_hide: '',
					special_hide: '',
					sy_regular: [],
					sy_special: [],
					ps_sem: '1st',
					ps_year: '',
					ps_period: 'prelim',
					selected_sum_sy: '',
					selected_sum_sem: '',
					periods: {
						prelim: '',
						midterm: '',
						prefinal: '',
						final: '',
						total: '',
						is_current: '',
						is_empty: ''
					},
					summary_details_bills: {},
					summary_details_payments: {},
					all_or_details: {},
					current_or: '',
					current_or_status: '',
					current_or_id: '',
					tab_key: 0,
					bill_visibility: '',
					payment_visibility: 'hidden'
				};
				Object.assign(this.$data, a);
			},
			capitalize: function (value) {
		    	if (!value) return ''
		    	value = value.toString()
		    	return value.charAt(0).toUpperCase() + value.slice(1)
		  	},
		    formatPrice(value) {
		    	if(value){
			        value = parseFloat(value).toFixed(2);
			    	let val = (value/1).toFixed(2).replace(',', '.')
	        		return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
		    	}
		    	return value;
		    },
		    ucwords: function(str){
				return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
			},
			autocomplete: function(){
				var $this = this;

				$("#selected_student").autocomplete({
					minLength: 2,
					source: function(request, response){
						$.ajax( {
				          	url: '<?= base_url("home/student_list") ?>',
							type: 'GET',
							dataType: 'JSON',
			         	 	data: {
			            		term: request.term,
			            		fee_type: this.fee_type,
			            		sched_sy: $("#school_year").val(),
			            		sched_sem: $("#sem").val()
			          		},
				          	success: function( data ) {
				            	response(data);
				          	}
				        } );
					},
					select: function( event, ui ) {
						$this.defaultData();
						$this.select_change();
						if(ui.item.type == 'student'){
							$this.has_selected = true;
							$this.ssi_id = ui.item.ssi_id;
							$this.name = ui.item.value;
							$this.stud_id = ui.item.stud_id;
							$this.enrollment_status = ui.item.enrollment_status;
							$this.course = ui.item.course;
							$this.course_type = ui.item.course_type;
							$this.phone_number = ui.item.phone_number;
							$this.fee_summary = ui.item.data;
							$this.current_status = ui.item.current_status;
							$this.school_years_distinct();
							$this.check_old_balances();
							// $this.submit_payment();
						}
						else{
							$this.fee_type = 'other';
							$this.name = ui.item.label;
						}
					},	
			        focus: function() {
			            return false;
			        },
				});
			},
		    select_change: function(event = ""){
		    	if(event){
			    	var e = event.currentTarget;
			    	var type = e.getAttribute('data-type');
			    	var value = event.target.value;

			    	if(type == 'sy') this.ps_year = value;
			    	if(type == 'sem') this.ps_sem = value;
			    	if(type == 'period') this.ps_period = value;
			    	if(type == 'fee_type') this.fee_type = value;
		    	}
		    	if(this.fee_type == 'regular'){
		    		this.regular_hide = '';
		    		this.special_hide = 'hidden';
		    	}
		    	if(this.fee_type == 'special'){
		    		this.regular_hide = 'hidden';
		    		this.special_hide = '';
		    	}
		    	if(this.fee_type == 'other'){
					// this.other_particular_autocomplete();
		    	}
		    	if(this.ps_year != '') this.process_payment_schedule()
		    },
		    process_payment_schedule: function(){
		    	$this = this.periods;
	    		$.getJSON('<?= base_url("home/payment_schedule") ?>', {sem: this.ps_sem, sy: this.ps_year, fee_type: this.fee_type, period: this.ps_period, ssi_id: this.ssi_id}, function(json, textStatus) {
	    			$this.prelim = json.prelim;
	    			$this.midterm = json.midterm;
	    			$this.prefinal = json.prefinal;
	    			$this.final = json.final;
	    			$this.total = json.total;
	    			$this.is_current = json.is_current;
	    			$this.is_empty = json.is_empty;
			  	});
		    },
		    school_years_distinct: function(){
		    	var regular = this.fee_summary.regular_fees;
		    	var r = [];
		    	var s = [];
		    	$this = this;
		    	$.each(regular, function(index, val) {
		    		if(!r.includes(val.sy)){
		    			r.push(val.sy)
		    		}
		    		// check if there is a bill for the current year and semester
		    		if(val.sy == $this.ses_sy && val.sem == $this.ses_sem){
		    			$this.has_current_bill = true;
		    		}
		    	});
		    	this.sy_regular = r;
		    },
		    summary_details_switch: function(event){
		    	var value = event.target.value;
		    	if( value == 'student_bill' ){
		    		this.bill_visibility = '';
		    		this.payment_visibility = 'hidden';
		    	}
		    	else{
		    		this.bill_visibility = 'hidden';
		    		this.payment_visibility = '';
		    	}
		    },
		    view_summary_details: function(event = ""){
		    	var e, sy, sem;
		    	if(event){
			    	e = event.currentTarget;
			    	sy = e.getAttribute('data-sy');
			    	sem = e.getAttribute('data-sem');
			    	this.selected_sum_sy = sy;
			    	this.selected_sum_sem = sem;
		    	}
		    	$this = this;
		    	$.getJSON('<?= base_url("home/view_summary_details") ?>', {sy: this.selected_sum_sy, sem: this.selected_sum_sem, ssi_id: $this.ssi_id, type: $this.type }, function(json, textStatus) {
		    		$this.summary_details_bills = json.bills;
		    		$this.summary_details_payments = json.payments;
		    	});
		    },
		    or_detail_modal: function(or){
		    	this.current_or = or;
		    	this.current_or_id = this.summary_details_payments[or].paymentId;	
		    	this.all_or_details = this.summary_details_payments[or].details;
		    	this.current_or_status = this.summary_details_payments[or].payment_status;
		    	$("#or_details").modal('toggle')
		    	$("#summary_details_modal").modal('toggle')
		    },
		    cancel_payment: function(action){
		    	$this = this;
		    	$.post('<?= base_url("home/cancel_payment") ?>', {or: this.current_or, action: action, paymentId: this.current_or_id }, function(data, textStatus, xhr) {
			    	swal("Success!", "Cancelation complete.", "success")
			    	$("#or_details").modal('toggle')
		    		$("#summary_details_modal").modal('toggle')
		    		$this.view_summary_details('')
		    	});
		    },
		    reg_payees_dtable: function(event){
		    	var $this = this;
		    	if(event){
			    	var val = event.target.value;
		    	}
	    		$.getJSON('<?= base_url("others/other_payees") ?>', {term: val}, function(json, textStatus) {
	    			$this.other_payees = json;
			  	});
		    },
		    add_payee: function(){
		    	event.preventDefault()
		    	var $this = this;
		    	if(this.other_payees_form.fname == '' || this.other_payees_form.lname == '' || this.other_payees_form.address == ''){
		    		this.other_payees_form.isValid = false;
		    	}
		    	else{
		    		this.other_payees_form.isValid = '';
			    	$.post('<?= base_url("others/add_payee") ?>', {data: this.other_payees_form}, function(data, textStatus, xhr) {
			    		if(data == 1){
		    				$this.other_payees_form.isValid = '';
			    			$this.other_payees_form.lname = '';
			    			$this.other_payees_form.fname = '';
			    			$this.other_payees_form.mname = '';
			    			$this.other_payees_form.ext = '';
			    			$this.other_payees_form.address = '';
			    			swal("Success!", "You just added a new payee!", "success")
			    		}
	    				$this.reg_payees_dtable('')
			    	}, 'JSON');
		    	}
		    },
		    payee_details: function(event){
		    	this.other_payees_modal.id = event.currentTarget.getAttribute('data-id');
		    	this.other_payees_modal.fname = event.currentTarget.getAttribute('data-fname');
		    	this.other_payees_modal.mname = event.currentTarget.getAttribute('data-mname');
		    	this.other_payees_modal.lname = event.currentTarget.getAttribute('data-lname');
		    	this.other_payees_modal.ext   = event.currentTarget.getAttribute('data-ext');
		    	this.other_payees_modal.address = event.currentTarget.getAttribute('data-address');
		    },
		    update_payee: function(){
		    	$this = this;
		    	if(!this.other_payees_modal.fname || !this.other_payees_modal.lname || !this.other_payees_modal.address) {
	    			swal("Oops!", "Please fill up fields with asterisk (*)", "error");
		    	}
		    	else{
		    		$.post('<?= base_url("others/update_payee") ?>', {data: this.other_payees_modal}, function(json, textStatus, xhr) {
		    			swal("Success!", "Update complete!", "success")
		    			$this.reg_payees_dtable('')
		    		}, "JSON");
		    	}
		    },
		    delete_payee: function(){
		    	$this = this;
		    	swal({
				  	title: "Are you sure?",
				  	text: "Confirm delete",
				  	icon: "warning",
				  	dangerMode: true
				})
				.then(willDelete => {
					if (willDelete) {
		    			$.post('<?= base_url("others/delete_payee") ?>', {id: this.other_payees_modal.id}, function(json, textStatus, xhr) {
			    			swal("Success!", "Data has been deleted.", "success")
			    			$this.reg_payees_dtable('')
				    		$("#view_payee").modal('toggle')
			    		}, "JSON");
					}
				});
		    },
		    view_particulars: function(event = ""){
		    	var key = event ? event.target.value : "";
		    	$this = this;
		    	$.getJSON('<?= base_url("others/get_other_particulars") ?>', {key: key}, function(json, textStatus) {
		    		$this.get_other_particulars = json;
		    	});
		    },
		    add_particular_modal: function(){
		    	$("#view_particular_modal").modal('toggle')
		    	$("#add_particular_modal").modal('toggle')
		    },
		    add_particular: function(){
		    	if(!this.add_particular_form.particular){
		    		swal("Ooops!", "Particular Name is required.", "error")
		    	}
		    	else{
		    		if(this.add_particular_form.price == 0){
			    		swal("Ooops!", "We don't give things for free. Don't we?", "error")
		    		}
		    		else{	
		    			$.post('<?= base_url("others/add_particular") ?>', {particular: this.add_particular_form.particular, price: this.add_particular_form.price, particular_type: this.add_particular_form.particular_type}, function(json, textStatus, xhr) {
		    				if(json == "error"){
				    			swal("Oops!", "Something went wrong. Please contact support.", "error")
		    				}
		    				else{
			    				swal("Success!", "You just added a new particular.", "success")
		    				}
			    		}, "JSON");
		    		}
		    	}
		    },
		    submit_to_list: function(event){
		    	var e = event.currentTarget;
		    	var array = {
		    		id: e.getAttribute('data-id'),
		    		name: e.getAttribute('data-name'),
		    		price: parseFloat(e.getAttribute('data-price')),
		    		quantity: parseFloat(1),
		    		subtotal: parseFloat(e.getAttribute('data-price'))
		    	};
		    	this.selected_particulars.push(array);
		    	this.selected_particulars_total = parseFloat(this.selected_particulars_total) + parseFloat(e.getAttribute('data-price'))
		    	// this.calc_otherp_total();
		    },
		    remove_selectedp: function(event){
		    	var e = event.currentTarget;
		    	var k = e.getAttribute('data-key')
		    	var p = e.getAttribute('data-price')
		    	this.selected_particulars_total = parseFloat(this.selected_particulars_total) - parseFloat(p);
		    	this.$delete(this.selected_particulars, k)
		    },
		    calc_otherp_total: function(){
		    	var id = event.currentTarget.getAttribute('data-id')
		    	var q  = event.target.value;
		    	var total = 0;
		    	$.each(this.selected_particulars, function(index, val) {
		    		if(val.id == id){
		    			val.quantity = q;
		    			val.subtotal = parseFloat(val.price * val.quantity)
		    		}
	    			total += parseFloat(val.subtotal)
		    	});
		    	this.selected_particulars_total = total;
		    },
		    os_or_breakdown: function(event = ""){
		    	$("#old_acc_summary").modal('toggle')
		    	$("#os_or_breakdown").modal('toggle')

		    	if(event){
			    	var e = event.currentTarget;
			    	var or = e.getAttribute('data-or');
			    	var breakdown = this.fee_summary['old_system']['payments'][or].payment_breakdown
			    	this.selected_os_or.or = or;
			    	this.selected_os_or.data = breakdown;
		    	}
		    },
		    check_old_balances: function(){
		    	var old_system_balance = parseFloat(this.fee_summary.old_system.remaining_balance);
	    		var notice = "";
	    		var has_old = false;
	    		
		    	if(old_system_balance > 0){
		    		has_old = true;
		    		notice += "\n • Old System Balance";
		    	}
		    	if(has_old){
					swal({
						title: "Payee has still following old balance(s)!", 
						text: notice, 
						type: "info"
					});
		    	}
		    },
		    add_to_payments: function(event) {
		        event.preventDefault();
		        var e = event.currentTarget;
	    		var type = e.getAttribute('data-type')
		    	var balance = e.getAttribute('data-balance');
		    	var key = e.getAttribute('data-sy') + " " + e.getAttribute('data-sem') + " " + balance
		    	var a;
		    	if(balance > 0){

			    	if(type == 'regular'){
			    		var a = key;
			    	}
			    	if(type == 'down_payment'){
			    		var a = 'DownPayment';
			    	}
			    	if(type == 'old_system'){
			    		var a = 'OldSystem';
			    	}

			    	if(!this.to_be_paid.includes(a)){
				    	this.to_be_paid.push(a)
			    	}
		    	}
		    	else{
		    		console.log("Already full paid")
		    	}
		    	this.format_payments();
		    },
		    format_payments: function(){
		    	this.formatted_payments = [];
		    	var array = [];
		    	$.each(this.to_be_paid, function(index, val) {
		    		var x = val.split(" ")
		    		var a = {
		    			sy: x[0],
		    			sem: x[1],
		    			fp_key: index,
		    			balance: x[2]
		    		}
		    		array.push(a)
		    	});
		    	this.formatted_payments = array;
		    },
		    remove_from_payments: function(event){
		    	event.preventDefault();
		        var e = event.currentTarget;
	    		var key = e.getAttribute('data-key')
	    		var items = this.to_be_paid;
	    		items.splice(key, 1);
	    		this.format_payments();
		    },
		    submit_payment: function(){		
		    	if(this.fee_type == 'regular'){
			    	if(this.to_be_paid.length > 0){
			    		var vpf = this.validate_payment_fields();
			    		if(vpf){
			    			this.distribute_payment();
			    		}
			    	}
			    	else{
			    		swal("Ooops!", "Select payments first" , "warning");
			    	}
		    	}
		    	else{
		    		alert('no other payment yet')
		    	}
		    },
		    validate_payment_fields: function(){
		    	if(!this.or_served ){
			    	swal("Ooops!", "Please fill up required fields." , "warning");
		    		return false;
		    	}
		    	if(this.to_pay <= 0){
		    		swal("Ooops!", "Please input amount to be paid." , "warning");
		    		return false;
		    	}
		    	if(this.change < 0){
		    		swal("Ooops!", "Insufficient funds." , "warning");
		    		return false;
		    	}
		    	else{
			    	return true;
		    	}
		    },
		    calc_change: function(){
		    	this.distributed_cash_remaining = parseFloat(this.to_pay) 
		    	var change = parseFloat(this.cash) - parseFloat(this.to_pay)
		    	this.change = change;
		    },
		    distribute_payment: function(){
		    	$("#payment_distribution_modal").modal('toggle')
		    },
		    process_distribution_amount: function(){
		    	var di = $(".distribution_inputs");
		    	var total = 0;
		    	$.each(di, function(index, val) {
		    		var amt = $(val).val() ? parseFloat($(val).val()) : 0
		    		total = parseFloat(total) + amt;
		    	});
		    	this.distributed_cash_remaining = parseFloat(this.to_pay) - parseFloat(total);
		    },
		    reg_final_payment: function(){
		    	var $this = this;
		    	var el = this.$refs.all_payments;
		    	$this.final_payments = [];
		    	var dcr = parseFloat(this.distributed_cash_remaining);

		    	if( dcr > 0 || dcr < 0 ){
		    		dcr > 0 ? alert("Please expend remaining cash.") : alert("Insufficient funds."); 
		    	}
		    	else{
		    		$.each(el, function(index, val) {
			    		$this.final_payments.push({
			    			'sy' : this.getAttribute('data-sy'),
			    			'sem': this.getAttribute('data-sem'),
			    			'value': this.value
			    		});
			    	});
			    	$.post('<?= base_url("home/submit_payment") ?>', 
		    			{
		    				payments: $this.final_payments, 
		    				fee_type: $this.fee_type, 
		    				to_pay: $this.to_pay, 
		    				or: $this.or_served, 
		    				receipt: $this.receipt, 
		    				date: $this.payment_date, 
		    				ssi_id: $this.ssi_id, 
		    				course_type: $this.course_type,
		    				course: $this.course,
		    				current_status: $this.current_status
		    			},
		    			function(data, textStatus, xhr) {
				    		console.log(data)
			    		}
			    	);
		    	}

		    }
	  	}
	});
</script>

<style>
	
	.w125p {
		width:200px !important;
		min-width: 200px !important;
		max-width: 200px !important;
	}

	.fee_summary_row:hover, .payment_row:hover, .other_payee_row:hover {
		cursor: pointer;
	}

	.sd_detail_tbl tbody tr td{
		padding-bottom: 5px !important;
		padding-top: 5px !important;
	}

	.pl5 {
		padding-left:30px !important;
	}

	.mb5 {
		margin-bottom: 5px !important;
	}

</style>