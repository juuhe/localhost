<?php

class a_captcha
{
	public function num_code($num = 4)
	{
		$possible = '23456789bcdfghjkmnpqrstvwxyz';
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
		$_SESSION['create_key'] = $code;
		$font_size = $height * 0.75;
		($image = @imagecreate($width, $height)) || exit('Cannot initialize new GD image stream');
		$background_color = imagecolorallocate($image, 255, 255, 255);
		$text_color = imagecolorallocate($image, 20, 40, 100);
		$noise_color = imagecolorallocate($image, 200, 120, 180);

		for ($i = 0; $i < (($width * $height) / 3); $i++) {
			imagefilledellipse($image, mt_rand(0, $width), mt_rand(0, $height), 1, 1, $noise_color);
		}

		$font = __DIR__ . '/monofont.ttf';
		($textbox = imagettfbbox($font_size, 0, $font, $code)) || exit('Error in imagettfbbox function');
		$x = (($width - $textbox[4]) / 2) - 3;
		$y = ($height - $textbox[5]) / 2;
		imagettftext($image, $font_size, 0, $x, $y, $text_color, $font, $code) || exit('Error in imagettftext function');
		header('Content-type: image/png');
		imagepng($image);
		imagedestroy($image);
	}
}


?>
