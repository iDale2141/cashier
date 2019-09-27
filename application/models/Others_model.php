<?php 

/**
 * 
 */
class Others_model extends CI_Model
{
	
	public $sy;
	public $syId;
	public $sem;
	public $semId;
	public $user_id;

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->sy  = $this->session->userdata('user_data')['sy'];
		$this->sem = $this->session->userdata('user_data')['sem'];
		$this->user_id = $this->session->userdata('user_data')['user_data']->userId;
		$this->syId = $this->sy_sem_id()['sy'];
		$this->semId = $this->sy_sem_id()['sem'];
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

	public function other_payees($term){

		$payees = $this->db
					->like('payeeLast', $term)
					->or_like('payeeFirst', $term)
					->get('other_payee');
		return $payees->result();
	}

	public function add_payee($data){

		$data = array(
	        'payeeLast' => $data['data']['lname'],
	        'payeeFirst' => $data['data']['fname'],
	        'payeeMiddle' => $data['data']['mname'],
	        'payeeExt' => $data['data']['ext'],
	        'payeeAddress' => $data['data']['address']
		);

		$this->db->insert('other_payee', $data);
		return $this->db->affected_rows();
	}

	public function update_payee($data){
		$data = (object)$data['data'];

		$this->db->set('payeeLast', $data->lname);
		$this->db->set('payeeFirst', $data->fname);
		$this->db->set('payeeMiddle', $data->mname);
		$this->db->set('payeeExt', $data->ext);
		$this->db->set('payeeAddress', $data->address);
		$this->db->where('otherPayeeId', $data->id);
		$this->db->update('other_payee');
		return true;
	}	

	public function delete_payee($id){
		$this->db->where('otherPayeeId', $id);
		$this->db->delete('other_payee');
		return true;
	}

	public function other_particulars($key = ""){
		$fee = $this->db
				->select('particulars.particularId, particulars.particularName,particulars.amt1,particulars.amt2,particulars.feeType,sem.sem,sy.sy, particulars.billType')
				->join('sy', 'particulars.syId = sy.syId')
				->join('sem', 'particulars.semId = sem.semId')
				->where('particulars.billType !=', 'regular')
				->like('particulars.particularName', $key)
				->limit(5)
				->get('particulars')
				->result();

		return $fee;
	}

	public function add_particular($data){
		$exists = $this->db
					->select('*, sy.sy, sem.sem')
					->join('sy', 'particulars.syId = sy.syId')
					->join('sem', 'particulars.semId = sem.semId')
					->where('billType', 'other')
					->where('sy.sy', $this->sy)
					->where('sem.sem', $this->sem)
					->where('particulars.particularName', $data->particular)
					->get('particulars')
					->result();
		if($exists){
			return 'error';
		}
		else{
			
			$fee_rows = [
				'particularName' => $data->particular,
				'amt2' => 250,
				'billType' => $data->particular_type,
				'syId' => $this->syId,
				'semId' => $this->semId
			];
			$this->db->insert('particulars', $fee_rows);
			return true;
		}
	}

	public function test($sem = '1st', $sy = '2018-2019', $type = 'regular', $ssi_id = '10992'){

		$fee = $this->db
				->select('particulars.particularName,particulars.amt1,particulars.amt2,particulars.feeType,sem.sem,sy.sy')
				->join('sy', 'particulars.syId = sy.syId')
				->join('sem', 'particulars.semId = sem.semId')
				->where('particulars.billType', 'other')
				->like('particulars.particularName', $key)
				->limit(5)
				->get('particulars')
				->result();

		echo "<pre>";
		print_r($fee);

	}


}