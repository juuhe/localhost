<?php

class api_ad
{
	static public function view($adsid, $a = false, $proportion = true)
	{
		if (!$a) {
			$a = dr('api/api_ad.get_ad_plan_one', (int) $adsid);
		}

		if ($a['plantype'] == 'cpm') {
			return false;
		}

		if ($a['imageurl']) {
			if ($proportion) {
				$height = abs($a['height'] - ($a['height'] * 0.69999999999999996));
				$width = abs($a['width'] - ($a['width'] * 0.69999999999999996));
			}
			else {
				$height = $a['height'];
				$width = $a['width'];
			}

			$imgext = substr($a['imageurl'], -3);

			if ($imgext) {
				$parse_url = parse_url($a['imageurl']);

				if (!$parse_url['scheme']) {
					$a['imageurl'] = $GLOBALS['C_ZYIIS']['img_url'] . $a['imageurl'];
				}
			}

			if ($a['imageurl'] && (substr($a['imageurl'], -3) == 'swf')) {
				$html .= '<embed src=' . $a['imageurl'] . ' quality=\'high\' pluginspage=\'http://www.macromedia.com/go/getflashplayer\' type=\'application/x-shockwave-flash\'   wmode=transparent></embed>';
			}
			else {
				if ($a['imageurl'] && (substr($a['imageurl'], -4) == 'html')) {
					$html .= '<iframe  width=' . $height . ' height=' . $height . ' frameborder=0 src="' . $a['imageurl'] . '" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true" scrolling="no"></iframe>';
				}
				else if ($width) {
					$html .= '<img src=' . $a['imageurl'] . '  border=\'0\'  width=' . $width . ' height=' . $height . '>';
				}
				else {
					$html .= '<img src=' . $a['imageurl'] . '  border=\'0\'><br>';
				}
			}
		}

		if ($a['height'] == 0) {
			if ($a['headline'] && !$a['description']) {
				$html .= '<font color="#4668ab" style="font-size: 14px">' . $a['headline'] . '</font>';
			}

			if ($a['headline'] && $a['description']) {
				$html .= '<font color="#4668ab" style="font-size: 14px">' . $a['headline'] . "</font>\r\n\t\t\t\t\t\t <br><font color=\"#008000\" style=\"font-size: 12px\">" . $a['description'] . "</font>\r\n\t\t\t\t\t\t ";
			}
		}

		return $html;
	}
}


?>
