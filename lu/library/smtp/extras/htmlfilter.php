<?php

function tln_tagprint($tagname, $attary, $tagtype)
{
	$me = 'tln_tagprint';

	if ($tagtype == 2) {
		$fulltag = '</' . $tagname . '>';
	}
	else {
		$fulltag = '<' . $tagname;
		if (is_array($attary) && sizeof($attary)) {
			$atts = array();

			while (list($attname, $attvalue) = each($attary)) {
				array_push($atts, $attname . '=' . $attvalue);
			}

			$fulltag .= ' ' . join(' ', $atts);
		}

		if ($tagtype == 3) {
			$fulltag .= ' /';
		}

		$fulltag .= '>';
	}

	return $fulltag;
}

function tln_casenormalize(&$val)
{
	$val = strtolower($val);
}

function tln_skipspace($body, $offset)
{
	$me = 'tln_skipspace';
	preg_match('/^(\\s*)/s', substr($body, $offset), $matches);

	if (sizeof($matches[1])) {
		$count = strlen($matches[1]);
		$offset += $count;
	}

	return $offset;
}

function tln_findnxstr($body, $offset, $needle)
{
	$me = 'tln_findnxstr';
	$pos = strpos($body, $needle, $offset);

	if ($pos === false) {
		$pos = strlen($body);
	}

	return $pos;
}

function tln_findnxreg($body, $offset, $reg)
{
	$me = 'tln_findnxreg';
	$matches = array();
	$retarr = array();
	$preg_rule = '%^(.*?)(' . $reg . ')%s';
	preg_match($preg_rule, substr($body, $offset), $matches);

	if (!isset($matches[0])) {
		$retarr = false;
	}
	else {
		$retarr[0] = $offset + strlen($matches[1]);
		$retarr[1] = $matches[1];
		$retarr[2] = $matches[2];
	}

	return $retarr;
}

function tln_getnxtag($body, $offset)
{
	$me = 'tln_getnxtag';

	if (strlen($body) < $offset) {
		return false;
	}

	$lt = tln_findnxstr($body, $offset, '<');

	if ($lt == strlen($body)) {
		return false;
	}

	$pos = tln_skipspace($body, $lt + 1);

	if (strlen($body) <= $pos) {
		return array(false, false, false, $lt, strlen($body));
	}

	$tagtype = false;

	switch (substr($body, $pos, 1)) {
	case '/':
		$tagtype = 2;
		$pos++;
		break;

	case '!':
		if (substr($body, $pos + 1, 2) == '--') {
			$gt = strpos($body, '-->', $pos);

			if ($gt === false) {
				$gt = strlen($body);
			}
			else {
				$gt += 2;
			}

			return array(false, false, false, $lt, $gt);
		}
		else {
			$gt = tln_findnxstr($body, $pos, '>');
			return array(false, false, false, $lt, $gt);
		}

		break;

	default:
		$tagtype = 1;
		break;
	}

	$tag_start = $pos;
	$tagname = '';
	$regary = tln_findnxreg($body, $pos, '[^\\w\\-_]');

	if ($regary == false) {
		return array(false, false, false, $lt, strlen($body));
	}

	list($pos, $tagname, $match) = $regary;
	$tagname = strtolower($tagname);

	switch ($match) {
	case '/':
		if (substr($body, $pos, 2) == '/>') {
			$pos++;
			$tagtype = 3;
		}
		else {
			$gt = tln_findnxstr($body, $pos, '>');
			$retary = array(false, false, false, $lt, $gt);
			return $retary;
		}
	case '>':
		return array($tagname, false, $tagtype, $lt, $pos);
		break;

	default:
		if (preg_match('/\\s/', $match)) {
		}
		else {
			$gt = tln_findnxstr($body, $lt, '>');
			return array(false, false, false, $lt, $gt);
		}
	}

	$attname = '';
	$atttype = false;
	$attary = array();

	while ($pos <= strlen($body)) {
		$pos = tln_skipspace($body, $pos);

		if ($pos == strlen($body)) {
			return array(false, false, false, $lt, $pos);
		}

		$matches = array();
		preg_match('%^(\\s*)(>|/>)%s', substr($body, $pos), $matches);
		if (isset($matches[0]) && $matches[0]) {
			$pos += strlen($matches[1]);

			if ($matches[2] == '/>') {
				$tagtype = 3;
				$pos++;
			}

			return array($tagname, $attary, $tagtype, $lt, $pos);
		}

		$regary = tln_findnxreg($body, $pos, '[^\\w\\-_]');

		if ($regary == false) {
			return array(false, false, false, $lt, strlen($body));
		}

		list($pos, $attname, $match) = $regary;
		$attname = strtolower($attname);

		switch ($match) {
		case '/':
			if (substr($body, $pos, 2) == '/>') {
				$pos++;
				$tagtype = 3;
			}
			else {
				$gt = tln_findnxstr($body, $pos, '>');
				$retary = array(false, false, false, $lt, $gt);
				return $retary;
			}
		case '>':
			$attary[$attname] = '"yes"';
			return array($tagname, $attary, $tagtype, $lt, $pos);
			break;

		default:
			$pos = tln_skipspace($body, $pos);
			$char = substr($body, $pos, 1);

			if ($char == '=') {
				$pos++;
				$pos = tln_skipspace($body, $pos);
				$quot = substr($body, $pos, 1);

				if ($quot == '\'') {
					$regary = tln_findnxreg($body, $pos + 1, '\'');

					if ($regary == false) {
						return array(false, false, false, $lt, strlen($body));
					}

					list($pos, $attval, $match) = $regary;
					$pos++;
					$attary[$attname] = '\'' . $attval . '\'';
				}
				else if ($quot == '"') {
					$regary = tln_findnxreg($body, $pos + 1, '\\"');

					if ($regary == false) {
						return array(false, false, false, $lt, strlen($body));
					}

					list($pos, $attval, $match) = $regary;
					$pos++;
					$attary[$attname] = '"' . $attval . '"';
				}
				else {
					$regary = tln_findnxreg($body, $pos, '[\\s>]');

					if ($regary == false) {
						return array(false, false, false, $lt, strlen($body));
					}

					list($pos, $attval, $match) = $regary;
					$attval = preg_replace('/\\"/s', '&quot;', $attval);
					$attary[$attname] = '"' . $attval . '"';
				}
			}
			else if (preg_match('|[\\w/>]|', $char)) {
				$attary[$attname] = '"yes"';
			}
			else {
				$gt = tln_findnxstr($body, $pos, '>');
				return array(false, false, false, $lt, $gt);
			}
		}
	}

	return array(false, false, false, $lt, strlen($body));
}

function tln_deent(&$attvalue, $regex, $hex = false)
{
	$me = 'tln_deent';
	$ret_match = false;
	preg_match_all($regex, $attvalue, $matches);
	if (is_array($matches) && (0 < sizeof($matches[0]))) {
		$repl = array();

		for ($i = 0; $i < sizeof($matches[0]); $i++) {
			$numval = $matches[1][$i];

			if ($hex) {
				$numval = hexdec($numval);
			}

			$repl[$matches[0][$i]] = chr($numval);
		}

		$attvalue = strtr($attvalue, $repl);
		return true;
	}
	else {
		return false;
	}
}

function tln_defang(&$attvalue)
{
	$me = 'tln_defang';
	if ((strpos($attvalue, '&') === false) && (strpos($attvalue, '\\') === false)) {
		return NULL;
	}

	$m = false;

	do {
		$m = false;
		$m = $m || tln_deent($attvalue, '/\\&#0*(\\d+);*/s');
		$m = $m || tln_deent($attvalue, '/\\&#x0*((\\d|[a-f])+);*/si', true);
		$m = $m || tln_deent($attvalue, '/\\\\(\\d+)/s', true);
	} while ($m == true);

	$attvalue = stripslashes($attvalue);
}

function tln_unspace(&$attvalue)
{
	$me = 'tln_unspace';

	if (strcspn($attvalue, "\t\r\n\x00 ") != strlen($attvalue)) {
		$attvalue = str_replace(array('	', "\r", "\n", "\x00", ' '), array('', '', '', '', ''), $attvalue);
	}
}

function tln_fixatts($tagname, $attary, $rm_attnames, $bad_attvals, $add_attr_to_tag)
{
	$me = 'tln_fixatts';

	while (list($attname, $attvalue) = each($attary)) {
		foreach ($rm_attnames as $matchtag => $matchattrs) {
			if (preg_match($matchtag, $tagname)) {
				foreach ($matchattrs as $matchattr) {
					if (preg_match($matchattr, $attname)) {
						unset($attary[$attname]);
						continue;
					}
				}
			}
		}

		tln_defang($attvalue);
		tln_unspace($attvalue);

		foreach ($bad_attvals as $matchtag => $matchattrs) {
			if (preg_match($matchtag, $tagname)) {
				foreach ($matchattrs as $matchattr => $valary) {
					if (preg_match($matchattr, $attname)) {
						list($valmatch, $valrepl) = $valary;
						$newvalue = preg_replace($valmatch, $valrepl, $attvalue);

						if ($newvalue != $attvalue) {
							$attary[$attname] = $newvalue;
						}
					}
				}
			}
		}
	}

	foreach ($add_attr_to_tag as $matchtag => $addattary) {
		if (preg_match($matchtag, $tagname)) {
			$attary = array_merge($attary, $addattary);
		}
	}

	return $attary;
}

function tln_sanitize($body, $tag_list, $rm_tags_with_content, $self_closing_tags, $force_tag_closing, $rm_attnames, $bad_attvals, $add_attr_to_tag)
{
	$me = 'tln_sanitize';
	$rm_tags = array_shift($tag_list);
	@array_walk($tag_list, 'tln_casenormalize');
	@array_walk($rm_tags_with_content, 'tln_casenormalize');
	@array_walk($self_closing_tags, 'tln_casenormalize');
	$curpos = 0;
	$open_tags = array();
	$trusted = "<!-- begin tln_sanitized html -->\n";
	$skip_content = false;
	$body = preg_replace('/&(\\{.*?\\};)/si', '&amp;\\1', $body);

	while (($curtag = tln_getnxtag($body, $curpos)) != false) {
		list($tagname, $attary, $tagtype, $lt, $gt) = $curtag;
		$free_content = substr($body, $curpos, $lt - $curpos);

		if ($skip_content == false) {
			$trusted .= $free_content;
		}

		if ($tagname != false) {
			if ($tagtype == 2) {
				if ($skip_content == $tagname) {
					$tagname = false;
					$skip_content = false;
				}
				else if ($skip_content == false) {
					if (isset($open_tags[$tagname]) && (0 < $open_tags[$tagname])) {
						$open_tags[$tagname]--;
					}
					else {
						$tagname = false;
					}
				}
			}
			else if ($skip_content == false) {
				if (($tagtype == 1) && in_array($tagname, $self_closing_tags)) {
					$tagtype = 3;
				}

				if (($tagtype == 1) && in_array($tagname, $rm_tags_with_content)) {
					$skip_content = $tagname;
				}
				else {
					if ((($rm_tags == false) && in_array($tagname, $tag_list)) || (($rm_tags == true) && !in_array($tagname, $tag_list))) {
						$tagname = false;
					}
					else {
						if ($tagtype == 1) {
							if (isset($open_tags[$tagname])) {
								$open_tags[$tagname]++;
							}
							else {
								$open_tags[$tagname] = 1;
							}
						}

						if (is_array($attary) && (0 < sizeof($attary))) {
							$attary = tln_fixatts($tagname, $attary, $rm_attnames, $bad_attvals, $add_attr_to_tag);
						}
					}
				}
			}

			if (($tagname != false) && ($skip_content == false)) {
				$trusted .= tln_tagprint($tagname, $attary, $tagtype);
			}
		}

		$curpos = $gt + 1;
	}

	$trusted .= substr($body, $curpos, strlen($body) - $curpos);

	if ($force_tag_closing == true) {
		foreach ($open_tags as $tagname => $opentimes) {
			while (0 < $opentimes) {
				$trusted .= '</' . $tagname . '>';
				$opentimes--;
			}
		}

		$trusted .= "\n";
	}

	$trusted .= "<!-- end tln_sanitized html -->\n";
	return $trusted;
}

function HTMLFilter($body, $trans_image_path, $block_external_images = false)
{
	$tag_list = array(false, 'object', 'meta', 'html', 'head', 'base', 'link', 'frame', 'iframe', 'plaintext', 'marquee');
	$rm_tags_with_content = array('script', 'applet', 'embed', 'title', 'frameset', 'xmp', 'xml');
	$self_closing_tags = array('img', 'br', 'hr', 'input', 'outbind');
	$force_tag_closing = true;
	$rm_attnames = array(
		'/.*/' => array('/^on.*/i', '/^dynsrc/i', '/^data.*/i', '/^lowsrc.*/i')
		);
	$bad_attvals = array(
		'/.*/' => array(
			'/^src|background/i' => array(
				array('/^([\\\'"])\\s*\\S+script\\s*:.*([\\\'"])/si', '/^([\\\'"])\\s*mocha\\s*:*.*([\\\'"])/si', '/^([\\\'"])\\s*about\\s*:.*([\\\'"])/si'),
				array('\\1' . $trans_image_path . '\\2', '\\1' . $trans_image_path . '\\2', '\\1' . $trans_image_path . '\\2', '\\1' . $trans_image_path . '\\2')
				),
			'/^href|action/i'    => array(
				array('/^([\\\'"])\\s*\\S+script\\s*:.*([\\\'"])/si', '/^([\\\'"])\\s*mocha\\s*:*.*([\\\'"])/si', '/^([\\\'"])\\s*about\\s*:.*([\\\'"])/si'),
				array('\\1#\\1', '\\1#\\1', '\\1#\\1', '\\1#\\1')
				),
			'/^style/i'          => array(
				array('/expression/i', '/binding/i', '/behaviou*r/i', '/include-source/i', '/position\\s*:\\s*absolute/i', '/url\\s*\\(\\s*([\\\'"])\\s*\\S+script\\s*:.*([\\\'"])\\s*\\)/si', '/url\\s*\\(\\s*([\\\'"])\\s*mocha\\s*:.*([\\\'"])\\s*\\)/si', '/url\\s*\\(\\s*([\\\'"])\\s*about\\s*:.*([\\\'"])\\s*\\)/si', '/(.*)\\s*:\\s*url\\s*\\(\\s*([\\\'"]*)\\s*\\S+script\\s*:.*([\\\'"]*)\\s*\\)/si'),
				array('idiocy', 'idiocy', 'idiocy', 'idiocy', '', 'url(\\1#\\1)', 'url(\\1#\\1)', 'url(\\1#\\1)', 'url(\\1#\\1)', 'url(\\1#\\1)', '\\1:url(\\2#\\3)')
				)
			)
		);

	if ($block_external_images) {
		array_push($bad_attvals['/.*/']['/^src|background/i'][0], '/^([\'\\"])\\s*https*:.*([\'\\"])/si');
		array_push($bad_attvals['/.*/']['/^src|background/i'][1], '\\1' . $trans_image_path . '\\1');
		array_push($bad_attvals['/.*/']['/^style/i'][0], '/url\\(([\'\\"])\\s*https*:.*([\'\\"])\\)/si');
		array_push($bad_attvals['/.*/']['/^style/i'][1], 'url(\\1' . $trans_image_path . '\\1)');
	}

	$add_attr_to_tag = array(
		'/^a$/i' => array('target' => '"_blank"')
		);
	$trusted = tln_sanitize($body, $tag_list, $rm_tags_with_content, $self_closing_tags, $force_tag_closing, $rm_attnames, $bad_attvals, $add_attr_to_tag);
	return $trusted;
}


?>
