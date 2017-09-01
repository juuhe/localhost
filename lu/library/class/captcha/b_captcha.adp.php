<?php

class b_captcha
{
	public function num_code($num = 4)
	{
		$possible = 'QWERTYUIOPLKJHGFDSAZXCVBNM';
		$code = '';
		$i = 0;

		while ($i < $num) {
			$code .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
			$i++;
		}

		return $code;
	}

	public function create_image($num_code = '4', $width = '120', $height = '40')
	{
		$code = $this->num_code($num_code);
		$_SESSION['captcha_key'] = $code;
		$font_size = $height * 0.75;
		($image = @imagecreate($width, $height)) || exit('Cannot initialize new GD image stream');
		$background_color = ImageColorAllocate($image, 255, 255, 255);
		$text_color = imagecolorallocate($image, 20, 40, 100);
		$noise_color = ImageColorAllocate($image, mt_rand(0, 255), mt_rand(0, 250), mt_rand(0, 250));

		for ($i = 0; $i < (($width * $height) / 3); $i++) {
		}

		$font = __DIR__ . '/monofont.ttf';
		$_x = $width / $num_code;

		for ($i = 0; $i < $num_code; $i++) {
			$fc = imagecolorallocate($image, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
			imagettftext($image, $font_size, mt_rand(-30, 30), ($_x * $i) + mt_rand(1, 5), $height / 1.3999999999999999, $fc, $font, $code[$i]);
		}

		for ($i = 0; $i < 6; $i++) {
			$color = imagecolorallocate($image, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
			imageline($image, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $color);
		}

		for ($i = 0; $i < 50; $i++) {
			$color = imagecolorallocate($image, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
			imagestring($image, mt_rand(1, 5), mt_rand(0, $width), mt_rand(0, $height), $this->num_code(1), $color);
		}

		header('Content-type: image/png');
		imagepng($image);
		imagedestroy($image);
	}
}


?>
