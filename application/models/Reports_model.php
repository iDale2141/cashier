<?php

class Reports_model extends CI_Model	
{

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	public function generate_monthly_report($data){
		$month_year = explode('-', $data->post('month'));

		$this->db->from('collectionreport');
		$this->db->truncate();

		$select = ['payments.paymentDate', 'payments.orNo', 'payments.amt1', 'payments.ssi_id', 'payments.paymentId'];
		$payments = $this->db
						->select($select)
						->where('MONTH(payments.paymentDate)', $month_year[1])
						->where('YEAR(payments.paymentDate)', $month_year[0])
						->get('payments')->result();
		$array = [];
		foreach ($payments as $key => $value) {
			$payee = $this->get_payee_detail($value->ssi_id);
			$or_particulars = $this->or_particulars($value->paymentId);

			// ADJUSTED AMOUNTS
			$merchandise_amt = 0;
			$others_amt = 0;
			$unifast_amt = 0;
			$special_amt = 0;
			$scnl_amt = 0;
			$netR_amt = 0;

			$elearning_amt = 0;
			$nccUk_amt = 0;
			$msFee_amt = 0;
			$oracle_amt = 0;
			$hp_amt = 0;

			$studentServices_amt = 0;
			$sap_amt = 0;
			$stcab_amt = 0;
			$insurance_amt = 0;
			$office365_amt = 0;
			$shs_amt = 0;

			foreach ($or_particulars['or_details'] as $or_key => $or_val) {
				if($or_val->collectionReportGroup == 'merchandise'){
					$merchandise_amt += $or_val->amt1;
				}
				if($or_val->collectionReportGroup == 'others'){
					$others_amt += $or_val->amt1;
				}
				if($or_val->collectionReportGroup == 'unifast'){
					$unifast_amt += $or_val->amt1;
				}
				if($or_val->collectionReportGroup == 'specialExam'){
					$special_amt += $or_val->amt1;
				}
				if($or_val->collectionReportGroup == 'scnl'){
					$scnl_amt += $or_val->amt1;
				}
				if($or_val->collectionReportGroup == 'netR'){
					$netR_amt += $or_val->amt1;
				}

				if($or_val->collectionReportGroup == 'elearning'){
					$elearning_amt += $or_val->amt1;
				}	
				if($or_val->collectionReportGroup == 'nccUk'){
					$nccUk_amt += $or_val->amt1;
				}
				if($or_val->collectionReportGroup == 'msFee'){
					$msFee_amt += $or_val->amt1;
				}
				if($or_val->collectionReportGroup == 'oracle'){
					$oracle_amt += $or_val->amt1;
				}
				if($or_val->collectionReportGroup == 'hp'){
					$hp_amt += $or_val->amt1;
				}

				if($or_val->collectionReportGroup == 'studentServices'){
					$studentServices_amt += $or_val->amt1;
				}
				if($or_val->collectionReportGroup == 'sap'){
					$sap_amt += $or_val->amt1;
				}
				if($or_val->collectionReportGroup == 'stcab'){
					$stcab_amt += $or_val->amt1;
				}
				if($or_val->collectionReportGroup == 'insurance'){
					$insurance_amt += $or_val->amt1;
				}
				if($or_val->collectionReportGroup == 'office365'){
					$office365_amt += $or_val->amt1;
				}
				if($or_val->collectionReportGroup == 'shs'){
					$shs_amt += $or_val->amt1;
				}
			}
			$a = [
				'date' => $value->paymentDate,
				'or' => $value->orNo,
				'name' => $payee->lname . ", " . $payee->fname,
				'particular' => $or_particulars['particulars'],
				'grossReceipt' => $value->amt1,
				'merchandise' => $merchandise_amt ? $merchandise_amt : '-',
				'others' => $others_amt ? $others_amt : '-',
				'unifast' => $unifast_amt ? $unifast_amt : '-',
				'specialExam' => $special_amt ? $special_amt : '-',
				'scnl' => $scnl_amt ? $scnl_amt : '-',
				'elearning' => $elearning_amt ? $elearning_amt : '-',
				'nccUk' => $nccUk_amt ? $nccUk_amt : '-',
				'msFee' => $msFee_amt ? $msFee_amt : '-',
				'oracle' => $oracle_amt ? $oracle_amt : '-',
				'hp' => $hp_amt ? $hp_amt : '-',
				'studentServices' => $studentServices_amt ? $studentServices_amt : '-',
				'sap' => $sap_amt ? $sap_amt : '-',
				'stcab' => $stcab_amt ? $stcab_amt : '-',
				'culturalFee' => $culturalFee_amt ? $culturalFee_amt : '-',
				'insurance' => $insurance_amt ? $insurance_amt : '-',
				'office365' => $office365_amt ? $office365_amt : '-',
				'shs' => $shs_amt ? $shs_amt : '-',
				'netR' => $netR_amt ? $netR_amt : '-'
			];
			array_push($array, $a);
		}
		if($array){
			$this->db->insert_batch('collectionreport', $array); 
		}
		else{
			return false;
		}
		return true;
	}

	public function adjusted_monthly_report($data){
		
		$this->test();

	}

	public function get_payee_detail($ssi_id){
		
		$sis_db = $this->load->database('sis_db', TRUE);
		$query  = $sis_db
					->where('ssi_id', $ssi_id)
					->join('stud_per_info', 'stud_sch_info.spi_id = stud_per_info.spi_id')
					->get('stud_sch_info')->row();
		return $query;
	}

	public function or_particulars($payment_id){
		$particulars = '';
		$paymentdetails = $this->db
							->select(['paymentdetails.*', 'particulars.particularName', 'particulars.collectionReportGroup as p_collectionGroup', 'particulars.feeType', 'assessment.particular', 'assessment.collectionReportGroup as a_collectionGroup'])
							->where('paymentId', $payment_id)
							->join('particulars', 'particulars.particularId = paymentdetails.particularId', 'LEFT')
							->join('assessment', 'assessment.assessmentId = paymentdetails.assessmentId', 'LEFT')
							->get('paymentdetails')->result();
		foreach ($paymentdetails as $key => $value) {
			$particulars .= $value->particularName ? $value->particularName . "," : $value->particular . ",";
			
			if($value->p_collectionGroup){
				$value->collectionReportGroup = $value->p_collectionGroup;
			}
			else{
				$value->collectionReportGroup = $value->a_collectionGroup;
			}
			unset($value->p_collectionGroup);
			unset($value->a_collectionGroup);
		}

		return [
			'particulars' => $particulars,
			'or_details'  => $paymentdetails
		];
	}

	public function test($ssi_id = '9304'){
		$month_year = explode('-', '2019-9');
		$this->db->from('collectionreport');
		$this->db->truncate();

		$select = ['payments.paymentDate', 'payments.orNo', 'payments.amt1', 'payments.ssi_id', 'payments.paymentId'];
		$payments = $this->db
						->select($select)
						->where('MONTH(payments.paymentDate)', $month_year[1])
						->where('YEAR(payments.paymentDate)', $month_year[0])
						->get('payments')->result();
		$array = [];
		foreach ($payments as $key => $value) {
			$payee = $this->get_payee_detail($value->ssi_id);
			$or_particulars = $this->or_particulars($value->paymentId);

			// ADJUSTED AMOUNTS
			$merchandise_amt = 0;
			$others_amt = 0;
			$unifast_amt = 0;
			$special_amt = 0;
			$scnl_amt = 0;
			$netR_amt = 0;

			$elearning_amt = 0;
			$nccUk_amt = 0;
			$msFee_amt = 0;
			$oracle_amt = 0;
			$hp_amt = 0;

			$studentServices_amt = 0;
			$sap_amt = 0;
			$stcab_amt = 0;
			$culturalFee_amt = 0;
			$insurance_amt = 0;
			$office365_amt = 0;
			$shs_amt = 0;

			foreach ($or_particulars['or_details'] as $or_key => $or_val) {
				${$or_val->collectionReportGroup.'_amt'} += $or_val->amt1;
			}
			$a = [
				'date' => $value->paymentDate,
				'or' => $value->orNo,
				'name' => $payee->lname . ", " . $payee->fname,
				'particular' => $or_particulars['particulars'],
				'grossReceipt' => $value->amt1,
				'merchandise' => $merchandise_amt ? $merchandise_amt : '-',
				'others' => $others_amt ? $others_amt : '-',
				'unifast' => $unifast_amt ? $unifast_amt : '-',
				'specialExam' => $special_amt ? $special_amt : '-',
				'scnl' => $scnl_amt ? $scnl_amt : '-',
				'elearning' => $elearning_amt ? $elearning_amt : '-',
				'nccUk' => $nccUk_amt ? $nccUk_amt : '-',
				'msFee' => $msFee_amt ? $msFee_amt : '-',
				'oracle' => $oracle_amt ? $oracle_amt : '-',
				'hp' => $hp_amt ? $hp_amt : '-',
				'studentServices' => $studentServices_amt ? $studentServices_amt : '-',
				'sap' => $sap_amt ? $sap_amt : '-',
				'stcab' => $stcab_amt ? $stcab_amt : '-',
				'culturalFee' => $culturalFee_amt ? $culturalFee_amt : '-',
				'insurance' => $insurance_amt ? $insurance_amt : '-',
				'office365' => $office365_amt ? $office365_amt : '-',
				'shs' => $shs_amt ? $shs_amt : '-',
				'netR' => $netR_amt ? $netR_amt : '-'
			];
			array_push($array, $a);
		}
		$this->db->insert_batch('collectionreport', $array);
	}

}

?>