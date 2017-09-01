<?php

class file_upload
{
	/**
	 * 上传文件信息
	 */
	public $file = array();
	/**
	 * 上传文件对象的名字
	 */
	protected $allowed_types = 'jpg,jpeg,gif,png,swf';
	/**
	 * 上传大小限制
	 */
	protected $max_size = 1024;

	public function upload($field, $file_path, $file_name = false)
	{
		$dir = date('Y-m-d') . '/';
		$file_path = $file_path . $dir;

		if (!is_dir($file_path)) {
			if (!@mkdir($file_path)) {
				$this->file['error'] = 'upload_no_filepath';
				return false;
			}
		}
		else if (!is_writable($file_path)) {
			$this->file['error'] = 'upload_no_writable';
			return false;
		}

		if ($file_name === false) {
			$file_name = time() . mt_rand(1000, 9999);
		}

		$file = $_FILES[$field];
		$this->file['save_path'] = $file_path;
		$this->file['dir'] = $dir;
		$this->file['save_name'] = $file_name;
		$this->file['tmp_name'] = $file['tmp_name'];

		if ($file['error'] !== 0) {
			$this->_error($file['error']);
			return false;
		}

		if (!$this->_check_size($file['size'])) {
			$this->file['error'] = 'upload_no_size';
			return false;
		}

		if (!$this->_check_type($file['name'])) {
			$this->file['error'] = 'upload_no_ext';
			return false;
		}

		if (!$this->_check_upload($file['tmp_name'])) {
			$this->file['error'] = 'upload_no_uploaded';
			return false;
		}

		$this->_save();
	}

	public function _save()
	{
		$this->file['save_name'] = $this->file['save_name'] . '.' . $this->file['save_ext'];
		$new_file = $this->file['save_path'] . $this->file['save_name'];

		if (!move_uploaded_file($this->file['tmp_name'], $new_file)) {
			$this->file['error'] = 'upload_no_move';
			return false;
		}
	}

	public function _check_size($max_size)
	{
		return round($max_size / 1024, 2) < $this->max_size;
	}

	public function _check_type($filename)
	{
		$ext = $this->_get_ext($filename);

		if (in_array(strtolower($ext), explode(',', $this->allowed_types))) {
			if (!$this->_check_type_read1024(strtolower($ext))) {
				$this->file['error_ext'] = 'upload_no_read_ext';
				return false;
			}

			$this->file['save_ext'] = $ext;
			return true;
		}

		return false;
	}

	public function _check_type_read1024($extname)
	{
		$str = $new_ext = '';
		$file = fopen($this->file['tmp_name'], 'rb');

		if ($file) {
			$str = fread($file, 1024);
			fclose($file);
		}
		else {
			$this->file['error'] = 'upload_read_1024_error';
			return false;
		}

		if (($new_ext == '') && (2 <= strlen($str))) {
			if (substr($str, 0, 3) == "\xff\xd8\xff") {
				$new_ext = 'jpg';
			}
			else {
				if ((substr($str, 0, 4) == 'GIF8') && ($extname != 'txt')) {
					$new_ext = 'gif';
				}
				else if (substr($str, 0, 8) == "�PNG\r\n\x1a\n") {
					$new_ext = 'png';
				}
				else {
					if ((substr($str, 0, 2) == 'BM') && ($extname != 'txt')) {
						$new_ext = 'bmp';
					}
					else {
						if (((substr($str, 0, 3) == 'CWS') || (substr($str, 0, 3) == 'FWS')) && ($extname != 'txt')) {
							$new_ext = 'swf';
						}
					}
				}
			}
		}

		return $new_ext == $extname;
	}

	public function _get_ext($filename)
	{
		$pathinfo = pathinfo($filename);
		return $pathinfo['extension'];
	}

	public function _check_upload($filename)
	{
		return is_uploaded_file($filename);
	}

	public function _error($error)
	{
		switch ($error) {
		case 1:
			$this->file['error'] = 'File size exceeds the size of the server space.';
			break;

		case 2:
			$this->file['error'] = 'To upload the file size exceeds the limit browser.';
			break;

		case 3:
			$this->file['error'] = 'The file was only partially uploaded.';
			break;

		case 4:
			$this->file['error'] = 'No file was uploaded.';
			break;

		case 5:
			$this->file['error'] = 'The servers temporary folder is missing.';
			break;

		case 6:
			$this->file['error'] = 'Failed to write to the temporary folder.';
			break;

		case 7:
			$this->file['error'] = 'upload_unable_to_write_file';
			break;

		case 8:
			$this->file['error'] = 'upload_stopped_by_extension';
			break;

		default:
			$this->file['error'] = 'upload_no_file_selected';
			break;
		}
	}
}


?>
