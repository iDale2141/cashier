

<div class="container-fluid">
	
	<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#view_particular_modal"><span class="fa fa-search"></span> View Particulars</button>
	<hr class="hr-1">
	
	<div class="container">
		<div class="row">
			<div class="col-lg-6">

				<form class="form-horizontal" id="add_payee_form">
					<h4 class="text-center"><b> Registration Form </b></h4><br>
					<h5>Personal Information</h5>
					<hr>
		            <div class="form-group">
		                <label class="control-label col-md-3 col-sm-3" for="first">First Name <span style="color:red">*</span> :</label>
		                <div class="col-md-6 col-sm-6">
		                    <input class="form-control reg_op_field" type="text" id="add_fname" name="first" placeholder="First Name" required v-model="other_payees_form.fname"/>
		                </div>
		            </div>
		            <div class="form-group">
		                <label class="control-label col-md-3 col-sm-3" for="middle">Middle Name:</label>
		                <div class="col-md-6 col-sm-6">
		                    <input class="form-control reg_op_field" type="text" id="add_mname" name="middle" placeholder="Middle Name" v-model="other_payees_form.mname"/>
		                </div>
		            </div>
		            <div class="form-group">
		                <label class="control-label col-md-3 col-sm-3" for="last">Last Name <span style="color:red">*</span> :</label>
		                <div class="col-md-6 col-sm-6">
		                    <input class="form-control reg_op_field" type="text" id="add_lname" name="last" placeholder="Last Name"  required v-model="other_payees_form.lname"/>
		                </div>
		            </div>
		            <div class="form-group">
		                <label class="control-label col-md-3 col-sm-3" for="ext">Extension:</label>
		                <div class="col-md-6 col-sm-6">
		                    <input class="form-control reg_op_field" type="text" id="add_ext" name="ext" placeholder="Extension (eg., Sr., Jr.)" v-model="other_payees_form.ext"/>
		                </div>
		            </div>
		            <div class="form-group">
		                <label class="control-label col-md-3 col-sm-3" for="address">Address <span style="color:red">*</span> :</label>
		                <div class="col-md-6 col-sm-6">
		                    <input class="form-control reg_op_field" type="text" id="add_addr" name="address" placeholder="Address"  required v-model="other_payees_form.address"/>
		                </div>
		            </div>
		            <div class="form-group" v-if="other_payees_form.isValid === false">					
		                <label class="control-label col-md-3 col-sm-3" for="first"></label>
		                <div class="col-md-6 col-sm-6" style="text-align: right;">
							<span class="text-danger">Please fill up required fields.</span>
		                </div>
					</div>
		            <div class="form-group">
		                <label class="control-label col-md-3 col-sm-3"></label>
		                <div class="col-md-6 col-sm-6">
		                    <button type="submit" class="btn btn-sm btn-success pull-right" v-on:click="add_payee()">
		                        Submit <i class="fa fa-arrow-circle-right"></i>
		                    </button>
		                </div>
		            </div>
				</form>

			</div>
			<div class="col-lg-6">
				<h4 class="text-center"><b>Registered Payess</b></h4><br>
				<h5>&nbsp;</h5>
				<hr>
				<input type="text" class="pull-right input-sm form-control" placeholder="Type to search ..." style="width:35% !important;" v-on:keyup="reg_payees_dtable($event)"><br><br>
				<table class="table table-striped" id="payees_table">
					<thead>
						<th>Last Name</th>
						<th>First Name</th>
						<th>Middle Name</th>
						<th>Address</th>
					</thead>
					<tbody>
						<tr v-if="!other_payees.length">
							<td colspan="5" class="text-center"><i>*** Empty result ***</i></td>
						</tr>
						<tr data-toggle="modal" data-target="#view_payee" class="other_payee_row" v-for="op in other_payees" title="Click to view" :data-ext="op.payeeExt" :data-address="op.payeeAddress" :data-mname="op.payeeMiddle" :data-fname="op.payeeFirst" :data-lname="op.payeeLast" :data-id="op.otherPayeeId" v-on:click="payee_details($event)">
							<td>{{op.payeeLast}}</td>
							<td>{{op.payeeFirst}}</td>
							<td>{{op.payeeMiddle}}</td>
							<td>{{op.payeeAddress}}</td>
						</tr>
					</tbody>
				</table>

			</div>
		</div>
	</div>
</div>

<div class="modal" id="view_payee">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3" for="lname">Last Name <span style="color:red">*</span> :</label>
		                <div class="col-md-6 col-sm-6">
		                    <input class="form-control" type="text" name="lname" v-model="other_payees_modal.lname"/>
		                </div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3" for="fname">First Name <span style="color:red">*</span> :</label>
		                <div class="col-md-6 col-sm-6">
		                    <input class="form-control" type="text" name="fname" v-model="other_payees_modal.fname"/>
		                </div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3" for="mname">Middle Name:</label>
		                <div class="col-md-6 col-sm-6">
		                    <input class="form-control" type="text" name="mname" v-model="other_payees_modal.mname"/>
		                </div>
					</div>	
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3" for="ext">Ext. :</label>
		                <div class="col-md-6 col-sm-6">
		                    <input class="form-control" type="text" name="ext" v-model="other_payees_modal.ext"/>
		                </div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3" for="address">Address<span style="color:red">*</span> :</label>
		                <div class="col-md-6 col-sm-6">
		                    <input class="form-control" type="text" name="address" v-model="other_payees_modal.address"/>
		                </div>
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-sm" v-on:click="update_payee()">Update</button>
				<button type="button" class="btn btn-danger btn-sm" v-on:click="delete_payee()">Delete</button>
			</div>
		</div>
	</div>
</div>


<div class="modal" id="view_particular_modal">
	<div class="modal-dialog">

		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4>Particulars</h4><hr>
				<input type="text" class="form-control pull-right input-sm" placeholder="Search particular" style="width:30%;" v-on:keyup="view_particulars($event)">
				<table class="table table-hover table-bordered" style="margin-top: 70px;">
					<thead>
						<th>Particular Name</th>
						<th>Price</th>
					</thead>
					<tbody>
						<tr v-for="particular in get_other_particulars">
							<td>{{particular.particularName}}</td>
							<td>{{particular.amt2}}</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="modal-footer">
				<button class="btn btn-success btn-sm" v-on:click="add_particular_modal()"><span class="fa fa-plus"></span> Add Particular</button>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="add_particular_modal">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4>Add Particular</h4>
				<form class="form-horizontal"><hr>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3" for="particular">Particular Type<span style="color:red">*</span> :</label>
						<div class="col-md-6 col-sm-6">
							<select name="particular_type" id="particular_type" class="form-control" v-model="add_particular_form.particular_type">
								<option value="special">Special</option>
								<option value="other">Other</option>
							</select>
		                </div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3" for="particular">Particular Name <span style="color:red">*</span> :</label>
						<div class="col-md-6 col-sm-6">
		                    <input class="form-control" type="text" name="particular" v-model="add_particular_form.particular" placeholder="Type particular here" />
		                </div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3" for="price">Price <span style="color:red">*</span> :</label>
						<div class="col-md-6 col-sm-6">
		                    <input class="form-control" min="0" type="number" name="price" v-model="add_particular_form.price"/>
		                </div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-sm" v-on:click="add_particular()">Submit</button>
			</div>
		</div>
	</div>
</div>