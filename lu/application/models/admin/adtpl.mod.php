<?php

class adtpl_mod extends app_models
{
	public $default_from = 'adtpl';

	public function get_list($id = false)
	{
		if ($id) {
			$this->where('adtype.id', (int) $id);
		}

		$this->from('adtype AS adtype');
		$this->from('adtpl AS adtpl');
		$this->where('adtype.id', ' adtpl.adtypeid', 'AND', false);
		$this->order_by('adtpl.tplid');
		$data = $this->get();
		return $data;
	}

	public function add_post()
	{
		$htmlcontrol = array('htmlcontrol_title' => post('htmlcontrol_title'), 'htmlcontrol_type' => post('htmlcontrol_type'), 'htmlcontrol_name' => post('htmlcontrol_name'), 'htmlcontrol_id' => post('htmlcontrol_id'), 'htmlcontrol_span' => post('htmlcontrol_span'));
		$data = array('adtypeid' => (int) post('adtypeid'), 'tplname' => post('tplname'), 'tpltype' => post('tpltype'), 'viewjs' => post('viewjs', '', false), 'iframejs' => post('iframejs', '', false), 'description' => post('description'), 'sort' => post('sort'), 'htmlcontrol' => serialize($htmlcontrol), 'customspecs' => post('customspecs'), 'customcolor' => post('customcolor'), 'addtime' => DATETIMES);
		$this->set($data);
		$this->insert();
		return true;
	}

	public function update_post($id)
	{
		$where = array('tplid' => (int) $id);
		$htmlcontrol = array('htmlcontrol_title' => post('htmlcontrol_title'), 'htmlcontrol_type' => post('htmlcontrol_type'), 'htmlcontrol_name' => post('htmlcontrol_name'), 'htmlcontrol_id' => post('htmlcontrol_id'), 'htmlcontrol_span' => post('htmlcontrol_span'));
		$data = array('adtypeid' => (int) post('adtypeid'), 'tplname' => post('tplname'), 'tpltype' => post('tpltype'), 'viewjs' => post('viewjs', '', false), 'iframejs' => post('iframejs', '', false), 'sort' => post('sort'), 'description' => post('description'), 'customspecs' => post('customspecs'), 'customcolor' => post('customcolor'), 'htmlcontrol' => serialize($htmlcontrol));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function get_one($id)
	{
		$where = array('tplid' => (int) $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_one_adtpl_adtype($id)
	{
		$this->from('adtype AS adtype');
		$this->from('adtpl AS adtpl');
		$where = array('adtpl.tplid' => (int) $id);
		$this->where($where);
		$this->where('adtype.id', ' adtpl.adtypeid', 'AND', false);
		$data = $this->find_one();
		return $data;
	}

	public function get_all()
	{
		$this->from('adtype AS adtype');
		$this->from('adtpl AS adtpl');
		$this->where('adtype.id', ' adtpl.adtypeid', 'AND', false);
		$this->order_by('adtpl.sort', 'ASC');
		$data = $this->get();
		return $data;
	}

	public function get_adtypeid_all($id)
	{
		$where = array('adtypeid' => (int) $id);
		$this->where('status', 'y');
		$this->where($where);
		$this->order_by('sort', 'ASC');
		$data = $this->get();
		return $data;
	}

	public function unlock($id)
	{
		$where = array('tplid' => (int) $id);
		$data = array('status' => 'y');
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function lock($id)
	{
		$where = array('tplid' => (int) $id);
		$data = array('status' => 'n');
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function del($id)
	{
		$where = array('tplid' => (int) $id);
		$this->where($where);
		$data = $this->delete();
	}

	public function get_name_to_title($name)
	{
	}
}

?>
