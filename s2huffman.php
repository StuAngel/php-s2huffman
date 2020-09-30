class s2huffman
{
	function clean(&$o, &$t, $ext = '')
		{ if(is_array($o)){ if(count($o)==1)return $this->clean($o[0], $t, $ext); 	$o[0] = $this->clean($o[0], $t, $ext . '0'); $o[1] = $this->clean($o[1], $t, $ext . '1'); } else $t[$o] = $ext; return $o; }
	function compress($s)
	{			
		$t = []; $o = [];
		$s = array_map(function($s){ return str_pad(dechex(ord($s)), 2, '0', STR_PAD_LEFT);  }, str_split($s));
		foreach($s as $h)$t[$h] = (empty($t[$h])?1:$t[$h]+1);
		foreach($t as $v=>$i){ $o[] = [$i, $v]; $t[$v] = ''; };
		while($o&&usort($o, function($a, $b){ return ($a[0]==$b[0]?$a[1]<$b[1]:$b[0]>$a[0]);  }))
			if(($result = array_pop($o))&&($_r = array_pop($o)))$o[] = [$result[0]+$_r[0], array_slice($result, 1), array_slice($_r, 1)];
		$result = array_slice($result, 1); $this->clean($result, $t); 
		for($i = count($s); $i--;)$s[$i] = $t[$s[$i]]; $s = implode($s);
		$len = str_pad(dechex(strlen($s)), 4, '0', STR_PAD_LEFT);
		$h = ''; while($node = substr($s, -8))($h=str_pad(dechex(bindec($node)), 2, '0', STR_PAD_LEFT).$h)&&($s = substr($s, 0, -8));			
		$r = json_encode($result); foreach(['"'=>'', ','=>'M', '[['=>'L', ']]'=>'K', '(('=>'J', '))'=>'I', '(['=>'H', ')]'=>'G'] as $k=>$v)$r = str_replace($k, $v, $r);	
		return 's2' . $r . '.' . $len . strtoupper($h);
	}
	function decompress($s)
	{
		if('s2'!=substr($s, 0, 2))return null; $result = '';
		if(count($e = explode('.', substr($s, 2)))==2)
		{
			foreach(['G'=>')]', 'H'=>'([', 'I'=>'))', 'J'=>'((', 'K'=>']]', 'L'=>'[[', 'M'=>'","', ']'=>'"]', ']"'=>']', '['=>'["', '"['=>'['] as $k=>$v)$e[0] = str_replace($k, $v, $e[0]);
			if($e[0] = json_decode($e[0]))
			{
				$lu = substr(($lu = implode(array_map(function($s){ return str_pad(decbin(hexdec($s)), 8, '0', STR_PAD_LEFT); }, str_split(substr($e[1], 4), 2)))), strlen($lu)-hexdec(substr($e[1], 0, 4)));
				$ptr = &$e[0];foreach(str_split($lu) as $o)if(is_string($ptr[$o])){ $result.=chr(hexdec($ptr[$o])); $ptr = &$e[0]; } else $ptr = &$ptr[$o];
			};							
		};
		return $result;
	}
};
