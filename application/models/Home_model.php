<?php

/**
 * 
 */
class Home_model extends CI_Model
{
	
	public $sy;
	public $sem;
	public $syId;
	public $semId;
	public $user_id;
	public $username;

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->library('session');	
		$this->sy  = $this->session->userdata('user_data')['sy'];
		$this->sem = $this->session->userdata('user_data')['sem'];
		$this->user_id = $this->session->userdata('user_data')['user_data']->userId;
		$this->syId = $this->sy_sem_id()['sy'];
		$this->semId = $this->sy_sem_id()['sem'];
		$this->username = $this->session->userdata()['user_data']['user_data']->username;
	}

	public function format_number($var){
		return number_format((double)$var, 2);
	}

	public function student_list($term){
		
		$sis_db = $this->load->database('sis_db', TRUE);
		$select = ['stud_per_info.spi_id', 'stud_per_info.fname', '.stud_per_info.lname', 'stud_per_info.mname', 'stud_sch_info.ssi_id', 'stud_sch_info.stud_id', 'phone_numbers.phone_number'];
		$query  = $sis_db
					->select($select)
					->like('stud_per_info.lname', $term)
					->or_like('stud_per_info.fname', $term)
					->join('stud_per_info', 'stud_sch_info.spi_id = stud_per_info.spi_id')
					->join('student_phone', 'student_phone.spi_id = stud_per_info.spi_id', 'left')
					->join('phone_numbers', 'phone_numbers.phone_id = student_phone.phone_id', 'left')
					->get('stud_sch_info', 5);
		return $query->result();
	}

	public function current_status($ssi_id){
		
		$sis_db = $this->load->database('sis_db', TRUE);
		$query = $sis_db->select('*')
					->where('year.sch_year', $this->sy)
					->where('year.semester', $this->sem)
					->where('year.ssi_id', $ssi_id)
					->get('year');
		return $query->row();
	}

	public function stud_address($spi_id){
		$sis_db = $this->load->database('sis_db', TRUE);
		$query  = $sis_db
					->select(['s_main_address.use_present_address', 'address.street', 'brgy.brgy_name', 'city.city_name'])
					->where('spi_id', $spi_id)
					->where('use_present_address', 'yes')
					->where('address_type', 'permanentAddress')
					->join('address', 'address.add_id = s_main_address.add_id')
					->join('brgy', 'address.brgy_id = brgy.brgy_id', 'left')
					->join('city', 'address.city_id = city.city_id', 'left')
					->get('s_main_address')
					->row();
		if($query){
			return $query;
		}
		return null;
	}

	public function other_payees($term){
		

		$query = $this->db
					->select('*')
					->like('payeeFirst', $term)
					->or_like('payeeLast', $term)
					->get('other_payee', 5);
		return $query->result();
	}

	public function enrollment_status($ssi_id){

		$sis_db = $this->load->database('sis_db', TRUE);

		$sis_db->select('*');
		$sis_db->where("ssi_id", $ssi_id);
		$sis_db->order_by('sch_year DESC, semester DESC');
		$query = $sis_db->get('student_enrollment_stat', 1);

		$status = "";
		if(!empty($query)){
			$result = $query->result()[0];

			if($result->sch_year == $this->sy && $result->semester == $this->sem){
				$status = $result->status;
			}
			else{
				$status = "not enrolled";
			}
		}
		else{
			$status = "not enrolled";
		}

		return $status;
	}

	public function course($ssi_id){

		$sis_db = $this->load->database('sis_db', TRUE);

		$query = $sis_db
					->select('program_list.*')
					->where('ssi_id', $ssi_id)
					->order_by('sch_year DESC, semester DESC')
					->join('program_list', 'program_list.pl_id = stud_program.pl_id')
					->get('stud_program', 5);

		$course = $query->result()[0]->prog_code;
		$course_type = $query->result()[0]->level;
		$year  = $this->course_year($ssi_id);

		return ["course" => $course, "year" => $year, "course_type" => $course_type];
	}

	public function course_year($ssi_id){
		
		$sis_db = $this->load->database('sis_db', TRUE);
		$query  = $sis_db
					->select('*')
					->order_by('sch_year DESC, semester DESC')
					->where('ssi_id', $ssi_id)
					->get("year", 15);

		return $query->result()[0];
	}

	public function breakdown_data($ssi_id){
		
		$array = array(
			"regular_fees" => $this->regular_summary($ssi_id),
			"special_payments" => $this->special_payments($ssi_id),
			"old_system" => $this->old_system_balance($ssi_id)
		);
		return $array;
	}

	public function old_system_balance($ssi_id){


		$query = "	SELECT
						oa_particular_amount.aoAmountId,
						oa_particular_amount.oaAmount,
						oa_particular_amount.oaSem,
						oa_particular_amount.oaSy,
						oa_particular_amount.oaStudentId,
						oa_particular_amount.oaParticularId,
						oa_particular_amount.oaSem,
						oa_particular_amount.oaSy,
						oa_particular.oaParticularName,
						oa_student.ssi_id,
						oa_payment.oa_payment_id,
						oa_payment.paymentOrNum,
						oa_payment.paymentDate,
						oa_payment.printingType,
						oa_payment.paymentAmount,
						SUM(oa_payment_distribution.paidAmount) as `total_paid`

					FROM
						oa_particular_amount

					INNER JOIN oa_particular
					ON oa_particular_amount.oaParticularId = oa_particular.oaParticularId

					INNER JOIN oa_student
					ON oa_particular_amount.oaStudentId = oa_student.oaStudentId

					LEFT JOIN oa_payment_distribution
					ON oa_payment_distribution.aoAmountId = oa_particular_amount.aoAmountId

					LEFT JOIN oa_payment
					ON oa_payment.oa_payment_id = oa_payment_distribution.oa_payment_id

					WHERE 
					oa_student.ssi_id = '{$ssi_id}'

					GROUP BY aoAmountId

					ORDER by 
					oa_particular_amount.oaSy,
					oa_particular_amount.oaSem";

		$exec  = $this->db->query($query);
		$payments = [];

		$balance = $exec->result();
		$total_amount = 0;
		$total_paid   = 0;

		foreach ($balance as $key => $value) {
			$total_amount = floatval($total_amount) + floatval($value->oaAmount);
			$total_paid   = floatval($total_paid) + floatval($value->total_paid);
			if($value->oa_payment_id){
				if(array_key_exists($value->paymentOrNum, $payments)){
					$payments[$value->paymentOrNum]["payment_breakdown"][] = $value;
				}
				else{
					$payments[$value->paymentOrNum] = [
						"or" => $value->paymentOrNum,
						"date" => $value->paymentDate,
						"receipt" => $value->printingType,
						"amount" => $value->paymentAmount,
						"payment_breakdown" => [$value]
					];
				}
			}
		}

		$remaining_balance = floatval($total_amount) - floatval($total_paid);

		return [
			"particulars" 		=> $balance,
			"remaining_balance" => $remaining_balance,
			"total_amount"		=> $total_amount,
			"total_paid"		=> $total_paid,
			"payments"			=> $payments
		];
	}

	public function regular_summary($ssi_id){

		$total_discount = 0;
		$discount = $this->db
						->where('discount.ssi_id', $ssi_id)
						->select('SUM(amt2) as total_discount, sy.sy, sem.sem')
						->join('sy', 'sy.syId = discount.syId')
						->join('sem', 'sem.semId = discount.semId')
						->group_by(['sy', 'sem'])
						->get('discount')
						->result();

		$select = ' assessment.assessmentId,
					assessment.ssi_id,
					assessment.feeType,
					sy.sy,
					sem.sem,
					assessment.particular,
					assessment.amt1 AS price1,
					assessment.amt2 AS price2,
					SUM(pd.amt1) AS paid1,
					SUM(pd.amt2) AS paid2';

		$where = array('assessment.ssi_id' => $ssi_id);

		$result = $this->db
					->where($where)
					->select($select)
					->join('sy', 'sy.syId = assessment.syId')
					->join('sem', 'sem.semId = assessment.semId')
					->join('paymentdetails pd', 'pd.assessmentId = assessment.assessmentId', 'LEFT')
					->join('payments', 'payments.paymentId = pd.paymentId', 'LEFT')
					->group_by(['assessmentId'])
					->order_by('sy, sem')
					->get('assessment')->result();

		$a = [];
		foreach ($result as $key => $value) {
			$value->discount = 0;
			foreach ($discount as $d_key => $d_value) {
				if($value->sy == $d_value->sy && $value->sem == $d_value->sem){
					$value->discount = $d_value->total_discount;
				}
			}
			$a[$value->sy . " " . $value->sem][] = $value;
		}
		$array = [];
		foreach ($a as $key => $value) {
			$sy = '';
			$sem = '';
			$assessment1 = 0.00;
			$assessment2 = 0.00;
			$discount = '';
			$paid1 = 0.00;
			$paid2 = 0.00;
			foreach ($value as $key1 => $value1) {
				$sy = $value1->sy;
				$sem = $value1->sem;
				$assessment1 += floatval($value1->price1); 
				$assessment2 += floatval($value1->price2); 
				$discount     = floatval($value1->discount);
				$paid1 		 += floatval($value1->paid1);
				$paid2 		 += floatval($value1->paid2);
			}

			$array[] = [
				'sy' => $sy,
				'sem' => $sem,
				'total_1' => $assessment1,
				'total_2' => $assessment2,
				'total_1_discounted' => $assessment1 - $discount,
				'total_2_discounted' => $assessment2 - $discount,
				'discount' => $discount,
				'paid1' => $paid1,
				'paid2' => $paid2,
				'remaining_balance' => ($assessment2 - $discount) - $paid2
			];
		}
		return $array;
	}

	public function special_payments($ssi_id){
		$select = ' sy.sy,
					sem.sem,
					payments.orNo,
					particulars.particularName,
					pd.amt2 AS paid_amount,
					payments.amt2 AS or_amount';
		$where = array('payments.ssi_id' => $ssi_id);

		$result = $this->db
					->where($where)
					->select($select)
					->join('sy', 'sy.syId = particulars.syId')
					->join('sem', 'sem.semId = particulars.semId')
					->join('paymentdetails pd', 'pd.particularId = particulars.particularId')
					->join('payments', 'payments.paymentId = pd.paymentId')
					->get('particulars')->result();
		return $result;
	}

	public function payment_schedule($data){
		$sem  = $data['sem'];
		$sy   = $data['sy'];
		$type = 'regular';
		$period = $data['period'];
		$ssi_id = $data['ssi_id'];

		$total = $this->regular_summary($ssi_id);
		$row   = [];

		foreach ($total as $key => $value) {
			if( $value['sem'] == $sem && $value['sy'] == $sy ){
				$row = $value;
				break;
			}
		}
		$ret = [];
		$ret['is_current'] = false;
		$ret['is_empty'] = true;
		if($row){
			$ret['is_empty'] = false;
			$row_total = $row['total_2'];

			if($type == 'regular'){
				if( $this->sy == $sy && $this->sem == $sem ){
					$ret['is_current'] = true;
					$ret['sem'] = $this->sem;
					$ret['sy']  = $this->sy;
					$ret['total'] = $row_total;
					switch ($period) {
						case 'prelim':
							
							$ret['prelim'] = ($row_total * .45);
							$row_total = (double)$row_total - (double)$ret['prelim'];

							$ret['midterm'] = ($row_total * .65);
							$row_total = (double)$row_total - (double)$ret['midterm'];

							$ret['prefinal'] = ($row_total * .85);
							$row_total = (double)$row_total - (double)$ret['prefinal'];

							$ret['final'] = $row_total;
							break;

						case 'midterm':
							
							$ret['prelim'] = 0;

							$ret['midterm'] = ($row_total * .65);
							$row_total = (double)$row_total - (double)$ret['midterm'];

							$ret['prefinal'] = ($row_total * .85);
							$row_total = (double)$row_total - (double)$ret['prefinal'];

							$ret['final'] = $row_total;
							break;

						case 'prefinal':

							$ret['prelim']  = 0;
							$ret['midterm'] = 0;

							$ret['prefinal'] = ($row_total * .85);
							$row_total = (double)$row_total - (double)$ret['prefinal'];

							$ret['final'] = $row_total;
							break;

						default:
							$ret['prelim']   = 0;
							$ret['midterm']  = 0;
							$ret['prefinal'] = 0;
							$ret['final'] = (double)$row_total;
							break;
					}

				}
				else{
					$ret['prelim'] = "";
					$ret['midterm'] = "";
					$ret['prefinal'] = "";
					$ret['final'] = "";
					$ret['total'] = (double)$row_total;
				}
			}	
		}

		return $ret;
	}

	public function payment_schedule_percentage($type, $ssi_id){
		
		$query = "	SELECT
						fee_schedule.*, a.feeScheduleId, a.packageType,
						fee_schedule_type.feeScheduleType
					FROM
						fee_schedule,
						fee_schedule_type,
						(
							SELECT DISTINCT
								fee_package.packageId,
								fee_package.feeScheduleId,
								fee_package.packageType
							FROM
								student_bill
							INNER JOIN fee_amount ON student_bill.feeAmountId = fee_amount.feeAmountId
							INNER JOIN fee ON fee_amount.feeId = fee.feeId
							INNER JOIN fee_package ON fee.packageId = fee_package.packageId
							LEFT JOIN payment_distribution ON payment_distribution.studentBillId = student_bill.studentBillId
							WHERE
								student_bill.ssi_id = '{$ssi_id}' AND 
								student_bill.billType = '{$type}' AND 
								fee_package.packageType = '{$type}'
						) AS a
					WHERE
						fee_schedule.packageId = a.packageId
					AND a.feeScheduleId = fee_schedule_type.feeScheduleId";
		$exec  = $this->db->query($query);
		return $exec->result();
	}

	public function regular_summary_details($ssi_id, $sem, $sy, $type){

		$select = ' assessment.assessmentId,
					assessment.ssi_id,
					assessment.feeType,
					sy.sy,
					sem.sem,
					assessment.particular,
					assessment.amt1 as price1,
					assessment.amt2 as price2,
					pd.amt1 as paid1,
					pd.amt2 as paid2,
					payments.amt1 as or_paid1,
					payments.amt2 as or_paid2,
					payments.orNo,
					payments.paymentId,
					payments.printingType,
					payments.paymentStatus,
					payments.paymentDate';
		$where  = array('assessment.ssi_id' => $ssi_id, 'sem.sem' => $sem, 'sy.sy' => $sy);

		$bills = $this->db
					->where($where)
					->select($select)
					->join('sy', 'sy.syId = assessment.syId')
					->join('sem', 'sem.semId = assessment.semId')
					->join('paymentdetails pd', 'pd.assessmentId = assessment.assessmentId', "LEFT")
					->join('payments', 'payments.paymentId = pd.paymentId', "LEFT")
					->get('assessment')
					->result();

		$payments = [];
		$bridging_bills = [];
		$non_bridging_bills = [];
		foreach ($bills as $key => $value) {
			// for payments
			if($value->orNo){
				$payments[$value->orNo]['amount'] = $value->or_paid2;
				$payments[$value->orNo]['payment_date'] = $value->paymentDate;
				$payments[$value->orNo]['printing_type'] = $value->printingType;
				$payments[$value->orNo]['payment_status'] = $value->paymentStatus;
				$payments[$value->orNo]['paymentId'] = $value->paymentId;
				$payments[$value->orNo]['details'][] = $value;
			}
			if( strtolower($value->feeType) == 'bridging'){
				$value->particular = "&nbsp;&nbsp;&nbsp;&nbsp;" . $value->particular;
				$bridging_bills[] = $value;
			}
			if( strtolower($value->feeType) != 'bridging'){
				$non_bridging_bills[] = $value;
			}
		}
		$bridging_row = [];
		if(!empty($bridging_bills)){
			$bridging_row = [
				'particular' => '<b>Bridging</b>',
				'price2' => '',
				'paid2' => ''
			];
			array_unshift($bridging_bills, $bridging_row);
		}
		
		$final_bills = array_merge($non_bridging_bills, $bridging_bills);

		$a = [
			'bills' => $final_bills,
			'payments' => $payments
		];
		return $a;
	}

	public function process_payment_summary($sem, $sy, $type, $ssi_id){

		$selections = 'student_bill.ssi_id, student_bill.studentBillId, payment_distribution.paymentId, payment.paymentDate, payment.paymentOrNum, payment.paymentAmount, payment.paymentType, payment.printingType, fee_package.sem, fee_package.sy';
		$where = array('student_bill.ssi_id' => $ssi_id, 'fee_package.sy' => $sy, 'fee_package.sem' => $sem, 'student_bill.billType' => $type);
		
		$result = $this->db
					->select($selections)
					->where($where)
					->join('payment_distribution', 'payment_distribution.studentBillId = student_bill.studentBillId')
					->join('payment', 'payment_distribution.paymentId = payment.paymentId')
					->join('fee_amount', 'student_bill.feeAmountId = fee_amount.feeAmountId')
					->join('fee', 'fee_amount.feeId = fee.feeId')
					->join('fee_package', 'fee.packageId = fee_package.packageId')
					->group_by('paymentOrNum')
					->get('student_bill')
					->result();
					
		return $result;
	}

	public function cancel_payment($or, $action, $paymentId){

		$this->db->set('paymentStatus', $action);
		$this->db->set('amt1', 0);
		$this->db->set('amt2', 0);
		$this->db->where('orNo', $or);
		$this->db->where('paymentId', $paymentId);
		$this->db->update('payments');
		if($this->db->affected_rows() == 1){

			$this->db->set('amt1', 0);
			$this->db->set('amt2', 0);
			$this->db->where('paymentId', $paymentId);
			$this->db->update('paymentdetails');
			return true;
		}
		else{
			return false;
		}
	}

	public function regular_payment($data){

		$or = $data->post('or');
		$payments = $data->post('payments');
		$fee_type = $data->post('fee_type');
		$to_pay   = $data->post('to_pay');
		$receipt  = $data->post('receipt');
		$date 	  = $data->post('date');
		$ssi_id   = $data->post('ssi_id');		
		$course   = $data->post('course');
		$current_status = $data->post('current_status');

		// CHECK IF PAYMENT INCLUDES DOWNPAYMENT
		foreach ($payments as $key => $value) {
			if($value['sy'] == 'DownPayment'){
				$dp_val = $value['value'];
				unset($payments[$key]);
				array_push($payments, ['sy' => $this->sy, 'sem' => $this->sem, 'value' => $dp_val]);
				$bills = $this->set_bills($ssi_id, $course);
			}
		}
		$roll_back_items = []; // roll back changes in the database if something went wrong

		$payment_rows = array(
		        'ssi_id' => $ssi_id,
		        'orNo' => $or,
		        'paymentDate' => $date,
		        'amt1' => '',
		        'amt2' => $to_pay,
		        'paymentMode' => 'cash',
		        'cashier' => $this->username,
		        'semId' => $this->semId,
		        'syId' => $this->syId,
		        'printingType' => $receipt,
		);
		$this->db->insert('payments', $payment_rows);
		$payment_id = $this->db->insert_id();

		$roll_back_items['payments'][] = $payment_id;

		$unpaid_particulars = []; // particulars having remaining balance more than 0
		$paid_particulars = []; // all particulars to be paid needed for printing the official receipt

		$total_amt1 = 0; // to be filled after inserting all rows in paymentDetails
		foreach ($payments as $key => $value) {

			$distribution_amount = floatval($value['value']);
			$select = ' assessment.assessmentId,
						assessment.ssi_id,
						sy.sy,
						sem.sem,
						assessment.particular,
						assessment.amt1 as price1,
						assessment.amt2 as price2,
						IFNULL(pd.amt1,0) as paid1,
						IFNULL(pd.amt2,0)as paid2,
						CAST(assessment.amt1 AS DECIMAL(9, 2)) - CAST(IFNULL(pd.amt1,0) AS DECIMAL(9, 2)) as remaining_balance1,
						CAST(assessment.amt2 AS DECIMAL(9, 2)) - CAST(IFNULL(pd.amt2,0) AS DECIMAL(9, 2)) as remaining_balance2';

			$result = $this->db
						->select($select)
						->where('assessment.ssi_id', $ssi_id)
						->where('(CAST(assessment.amt2 AS DECIMAL(9, 2)) - CAST(IFNULL(pd.amt2,0) AS DECIMAL(9, 2))) >' , 0)
						->where('sy.sy', $value['sy'])
						->where('sem.sem', $value['sem'])
						->join('sy', 'sy.syId = assessment.syId')
						->join('sem', 'sem.semId = assessment.semId')
						->join('paymentdetails pd', 'pd.assessmentId = assessment.assessmentId', 'LEFT')
						->get('assessment')->result();

			try {
				foreach ($result as $res_key => $res_value) {
					if($distribution_amount > 0){
						if( $distribution_amount >= $res_value->remaining_balance2 ){

							$paymentdetail_rows = array(
								'assessmentId' => $res_value->assessmentId,
								'amt1' => $res_value->remaining_balance1,
								'amt2' => $res_value->remaining_balance2,
								'paymentId' => $payment_id,
							);
							$this->db->insert('paymentdetails', $paymentdetail_rows);
							$total_amt1 += (double)$res_value->remaining_balance1;

							// item to be rolled back if ever
							$pd_id = $this->db->insert_id(); 
							$roll_back_items['paymentdetails'][] = $pd_id;

							$distribution_amount -= floatval($res_value->remaining_balance2);
							$particulars_tbp = [
								'particular' => $res_value->particular,
								'amount' => $res_value->remaining_balance2
							];
							array_push($paid_particulars, $particulars_tbp);
							continue;
						}
						if( $distribution_amount < $res_value->remaining_balance2 ){
							// get percentage then amount for amount1
							$percentage = ($distribution_amount / $res_value->price2);
							$amt1_val   = $res_value->price1 * $percentage;

							$paymentdetail_rows = array(
								'assessmentId' => $res_value->assessmentId,
								'amt1' => $amt1_val,
								'amt2' => $distribution_amount,
								'paymentId' => $payment_id,
							);

							$this->db->insert('paymentdetails', $paymentdetail_rows);
							$total_amt1 += (double)$amt1_val;

							// item to be rolled back if ever
							$pd_id = $this->db->insert_id(); 
							$roll_back_items['paymentdetails'][] = $pd_id;

							$particulars_tbp = [
								'particular' => $res_value->particular,
								'amount' => $distribution_amount
							];
							array_push($paid_particulars, $particulars_tbp);
							$distribution_amount = 0;
							break;
						}

					}
					else{
						break;
					}
				}
			} 
			catch (Exception $e) {

				foreach (array_reverse($roll_back_items) as $key => $value) {
					$id = $key == 'payments' ? 'paymentId' : 'paymentDetailsId';
					foreach ($value as $key1 => $value1) {
						$this->db->where($id, $value1);
						$this->db->delete($key);
					}
				}
				break;
				return false;	
			}
			
		}

		$new_amt1 = array(
		        'amt1' => $total_amt1
		);

		$this->db->where('paymentId', $payment_id);
		$this->db->update('payments', $new_amt1);

		return $paid_particulars;
	}

	public function set_bills($ssi_id, $course){

		$course = $course ? explode('-', $course)[0] : '';
		$sis_db = $this->load->database('sis_db', TRUE);

		$ct_qry = $this->db->select('courseType')->where('particularName', $course)->get('particulars')->row();
		$course_type = $ct_qry ? $ct_qry->courseType : null;

		$year = $sis_db
					->where('ssi_id', $ssi_id)
					->where('sch_year', $this->sy)
					->where('semester', $this->sem)
					->get('year')
					->row();

		if($year){ // if $year is empty, it means ssi_id is not enrolled during the current SY/SEM

			// particulars to bill
			$particulars = $this->db
				->where('syId', $this->syId)
				->where('semId', $this->semId)
				->where('feeType', 'Miscellaneous')
				->where('courseType', $course_type)
				->where('studentStatus', $year->current_stat)
				->get('particulars')->result();

			if($particulars){
				foreach ($particulars as $key => $value) {
					$assessment_rows = array(
				        'ssi_id' => $ssi_id,
				        'particular' => $value->particularName,
				        'amt1' => $value->amt1,
				        'amt2' => $value->amt2,
				        'feeType' => 'Miscellaneous',
				        'semId' => $this->semId,
				        'syId' => $this->syId
					);
					$this->db->insert('assessment', $assessment_rows);
				}
				return true;
			}
			else{
				return 'NO PARTICULARS'; // no particulars in current SY | SEM
			}
		}
		else{
			return 'NOT ENROLLED'; // not enrolled
		}
	}

	public function other_payment($data){
		$to_pay = $data['to_pay'];
		$or = $data['or'];
		$receipt = $data['receipt'];
		$date = $data['date'];
		$payee_type = $data['payee_type'];
		$ssi_id = $data['ssi_id'];
		$other_payee_id = $data['other_payee_id'];

		$data = $data['data'];

		$payment_rows = array(
		        'ssi_id' => $ssi_id,
		        'orNo' => $or,
		        'paymentDate' => $date,
		        'amt1' => $to_pay,
		        'amt2' => $to_pay,
		        'paymentMode' => 'cash',
		        'cashier' => $this->username,
		        'semId' => $this->semId,
		        'syId' => $this->syId,
		        'printingType' => $receipt,
		        'otherPayeeId' => $other_payee_id
		);
		$this->db->insert('payments', $payment_rows);
		$payment_id = $this->db->insert_id();

		foreach ($data as $key => $value) {
			$a = [
				'assessmentId' => null,
				'particularId' => $value['id'],
				'amt1' => $value['subtotal'],
				'amt2' => $value['subtotal'],
				'paymentId' => $payment_id
			];
			$this->db->insert('paymentdetails', $a);	
		}
		return $data;
	}

	public function sy_sem_id(){
		$syId = $this->db
					->select('syId')
					->where('sy', $this->sy)
					->limit(1)
					->get('sy')
					->result();
		$semId = $this->db
					->select('semId')
					->where('sem', $this->sem)
					->limit(1)
					->get('sem')
					->result();
		$a = [
			'sy' => $syId[0]->syId ? $syId[0]->syId : '',
			'sem' => $semId[0]->semId ? $semId[0]->semId : ''
		];
		return $a;
	}	

	public function test(){
		$ssi_id = '9304';
		$to_pay = 130;
		$data = [
			[
				"id" => "13",
				"name" => "ID Sling",
				"price" => "30",
				"quantity" => "1",
				"subtotal" => "30"
			],
			[
				"id" => "14",
				"name" => "Student Hand Book",
				"price" => "100",
				"quantity" => "1",
				"subtotal" => "100"
			]
		];

		foreach ($data as $key => $value) {
			echo "<pre>";
			print_r($value);
		}
	}
	public function testt($spi_id = '9304'){

		$sis_db = $this->load->database('sis_db', TRUE);
		$query  = $sis_db
					->select(['s_main_address.use_present_address', 'address.street', 'brgy.brgy_name', 'city.city_name'])
					->where('spi_id', $spi_id)
					->where('use_present_address', 'yes')
					->where('address_type', 'permanentAddress')
					->join('address', 'address.add_id = s_main_address.add_id')
					->join('brgy', 'address.brgy_id = brgy.brgy_id', 'left')
					->join('city', 'address.city_id = city.city_id', 'left')
					->get('s_main_address')
					->row();
		if($query){
			return $query;
		}
		return null;
	}

}

?>
