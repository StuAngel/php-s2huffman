class s2huffman
{
	function clean(&$o, &$t, $ext = '')
		{ if(is_array($o)){ if(count($o)==1)return $this->clean($o[0], $t, $ext); 	$o[0] = $this->clean($o[0], $t, $ext . '0'); $o[1] = $this->clean($o[1], $t, $ext . '1'); } else $t[$o] = $ext; return $o; }
	function compress($s)
	{			
		$ts = [];
		foreach(($s = array_map(function($chr){ return str_pad(dechex(ord($chr)), 2, '0', STR_PAD_LEFT); }, str_split($s))) as $chr)if(empty($ts[$chr]))$ts[$chr] = [1, $chr]; else $ts[$chr][0]+=1; 
		do { usort($ts, function($a, $b){ return $b[0]-$a[0]; }); $result = array_pop($ts); if($r1 = array_pop($ts))$ts[] = [$result[0]+$r1[0], array_slice($result, 1), array_slice($r1, 1)]; }while($r1);
		$result = array_slice($result, 1); $t = []; $this->clean($result, $t); $r = ''; while($s)$r.= $t[array_pop($s)]; if(!($rl = strlen($r)))return null;
		$o = []; while($en = substr($r, -8)){ $o[] = str_pad(dechex(bindec($en)), 2, '0', STR_PAD_LEFT); $r = substr($r, 0, -8); };
		$result = json_encode($result); foreach(['"'=>'', ','=>'M', '[['=>'L', ']]'=>'K', '(('=>'J', '))'=>'I', '(['=>'H', ')]'=>'G'] as $k=>$v)$result = str_replace($k, $v, $result);
		return 's2' . $result . '.' . str_pad(dechex($rl), 4, '0', STR_PAD_LEFT) . implode($o);
	}
	function decompress($s)
	{
		if('s2'!=substr($s, 0, 2))return null; $result = '';
		if(count($e = explode('.', $s))==2)
		{
			$e[0] = substr($e[0], 2); foreach(['G'=>')]', 'H'=>'([', 'I'=>'))', 'J'=>'((', 'K'=>']]', 'L'=>'[[', 'M'=>'","', ']'=>'"]', ']"'=>']', '['=>'["', '"['=>'['] as $k=>$v)$e[0] = str_replace($k, $v, $e[0]);
			if($e[0] = json_decode($e[0]))
			{
				$lu = substr(str_pad(implode(array_reverse(array_map(function($s){  return str_pad(decbin(hexdec($s)), 8, '0', STR_PAD_LEFT); }, str_split(substr($e[1], 4), 2)))), ($l = hexdec(substr($e[1], 0, 4))), '0', STR_PAD_LEFT), -$l);
				$ptr = &$e[0];foreach(str_split($lu) as $o)if(is_string($ptr[$o])){ $result = chr(hexdec($ptr[$o])) . $result; $ptr = &$e[0]; } else $ptr = &$ptr[$o];
			};
		};
		return $result;
	}
};
