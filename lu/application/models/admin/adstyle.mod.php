<?php

class adstyle_mod extends app_models
{
	public $default_from = 'adstyle';

	public function get_list($adtplid = false)
	{
		if ($adtplid) {
			$this->where('tplid', $adtplid);
		}

		$this->order_by('styleid');
		$data = $this->get();
		return $data;
	}

	public function add_post()
	{
		$htmlcontrol = array('htmlcontrol_title' => post('htmlcontrol_title'), 'htmlcontrol_type' => post('htmlcontrol_type'), 'htmlcontrol_name' => post('htmlcontrol_name'), 'htmlcontrol_checked' => post('htmlcontrol_checked'), 'htmlcontrol_value' => post('htmlcontrol_value'));

		if (!$htmlcontrol['htmlcontrol_title'][0]) {
			$htmlcontrol = '';
		}
		else {
			$htmlcontrol = serialize($htmlcontrol);
		}

		$data = array('tplid' => implode(',', post('tplid')), 'stylename' => post('stylename'), 'adnum' => post('adnum'), 'viewjs' => post('viewjs', '', false), 'iframejs' => post('iframejs', '', false), 'description' => post('description'), 'htmlcontrol' => $htmlcontrol, 'specs' => post('specs') ? implode(',', post('specs')) : '', 'addtime' => DATETIMES);
		$this->set($data);
		$this->insert();
		return true;
	}

	public function update_post($id)
	{
		$where = array('styleid' => (int) $id);
		$htmlcontrol = array('htmlcontrol_title' => post('htmlcontrol_title'), 'htmlcontrol_type' => post('htmlcontrol_type'), 'htmlcontrol_name' => post('htmlcontrol_name'), 'htmlcontrol_checked' => post('htmlcontrol_checked'), 'htmlcontrol_value' => post('htmlcontrol_value'));

		if (!$htmlcontrol['htmlcontrol_title'][0]) {
			$htmlcontrol = '';
		}
		else {
			$htmlcontrol = serialize($htmlcontrol);
		}

		$data = array('tplid' => implode(',', post('tplid')), 'stylename' => post('stylename'), 'adnum' => post('adnum'), 'viewjs' => post('viewjs', '', false), 'iframejs' => post('iframejs', '', false), 'htmlcontrol' => $htmlcontrol, 'description' => post('description'), 'specs' => implode(',', post('specs')));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function get_one($id)
	{
		$where = array('styleid' => (int) $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_all()
	{
	}

	public function get_tplid_specs($id)
	{
		$this->where('tplid', $id);
		$data = $this->find_one();
		return $data;
	}

	public function get_tplid_specs_all($id)
	{
		$this->where('tplid', $id);
		$data = $this->get();
		return $data;
	}

	public function get_sytlename($id, $specs = false)
	{
		$this->select('group_concat(stylename) AS stylename ');

		if ($specs) {
			$this->where('FIND_IN_SET(\'' . $specs . '\',specs)>0');
		}

		$this->where('tplid', $id);
		$this->group_by('tplid');
		$data = $this->find_one();
		return $data;
	}

	public function get_sytleid($specs)
	{
		$this->select('styleid,stylename,tplid');
		$this->where('FIND_IN_SET(\'' . $specs . '\',specs)>0');
		$data = $this->get();
		return $data;
	}

	public function unlock($id)
	{
		$where = array('styleid' => (int) $id);
		$data = array('status' => 'y');
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function lock($id)
	{
		$where = array('styleid' => (int) $id);
		$data = array('status' => 'n');
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function del($id)
	{
		$where = array('styleid' => (int) $id);
		$this->where($where);
		$data = $this->delete();
	}
}

?>
