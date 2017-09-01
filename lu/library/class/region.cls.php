<?php

class region
{
	public $fd = false;
	public $inum;
	public $off1 = 0;
	public $off2 = 0;
	public $flag;
	public $chrono;

	public function open()
	{
		$fpath = dirname(__FILE__) . '/data.dat';

		if (!($fd = fopen($fpath, 'rb'))) {
			return false;
		}

		fseek($fd, 0, SEEK_SET);
		$tmp = unpack('a4flag/Vinum', fread($fd, 8));
		fseek($fd, ($tmp['inum'] * 12) + 8, SEEK_SET);
		$buf = fread($fd, 12);
		if (($tmp['flag'] != 'ZYAD') || (strlen($buf) != 12)) {
			fclose($fd);
			return false;
		}

		$this->close();
		$this->fd = $fd;
		$this->flag = $tmp['flag'];
		$this->inum = $tmp['inum'];
		$this->off1 = ($tmp['inum'] * 12) + 20;
		$tmp = unpack('V3', $buf);
		$this->off2 = $this->off1 + $tmp[2];
		$this->chrono = $tmp[3];
		return true;
	}

	public function close()
	{
		if ($this->fd) {
			fclose($this->fd);
		}

		$this->fd = false;
	}

	public function query($ip)
	{
		$this->open();
		$ip0 = $this->zip2long($ip);
		if (($ip0 === false) || ($ip0 === -1)) {
			$ip0 = gethostbyname($ip0);
			if (!$ip0 || !($ip0 = $this->zip2long($ip0) || ($ip0 == -1))) {
				return false;
			}
		}

		if (!$this->fd && !$this->open()) {
			return false;
		}

		$ip0 = (double) sprintf('%u', $ip0 & 4294967295);
		$low = 0;
		$high = $this->inum - 1;
		$ret = false;

		while ($low <= $high) {
			$mid = ($low + $high) >> 1;
			$off = ($mid * 12) + 8;
			fseek($this->fd, $off, SEEK_SET);
			$buf = fread($this->fd, 16);

			if (strlen($buf) != 16) {
				break;
			}

			$tmp = unpack('V4', $buf);

			if ($tmp[1] < 0) {
				$tmp[1] = (double) sprintf('%u', $tmp[1] & 4294967295);
			}

			if ($tmp[4] < 0) {
				$tmp[4] = (double) sprintf('%u', $tmp[4] & 4294967295);
			}

			if ($ip0 < $tmp[1]) {
				$high = $mid - 1;
			}
			else if ($tmp[4] <= $ip0) {
				$low = $mid + 1;
			}
			else {
				$ret = array(NULL, NULL);

				if (0 <= $tmp[2]) {
					fseek($this->fd, $this->off1 + $tmp[2], SEEK_SET);
					$vlen = ord(fread($this->fd, 1));

					if (0 < $vlen) {
						$ret[0] = fread($this->fd, $vlen);
					}
				}

				if (0 <= $tmp[3]) {
					fseek($this->fd, $this->off2 + $tmp[3], SEEK_SET);
					$vlen = ord(fread($this->fd, 1));

					if (0 < $vlen) {
						$ret[1] = fread($this->fd, $vlen);
					}
				}

				break;
			}
		}

		return $ret;
	}

	public function zip2long($ip)
	{
		return bindec(decbin(ip2long($ip)));
	}
}


?>
