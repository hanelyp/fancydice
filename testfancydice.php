<?php
	require ("fancydice.php");
	// macro 'd' to implement common dice notation
	$macros = array('d' => '@[1_>]',
			'p' => '@["*", "**", "***", "****", "*****","******"]',
			//'eA' => '@[".","*","*","⁑","ᙉ","ᙉ","*ᙉ","ᙉᙉ"]',
			//'eP' => '@[".","*","*","⁑","⁑","*ᙉ","*ᙉ","*ᙉ","ᙉ","ᙉᙉ","ᙉᙉ","(*)"]',
			//'eB' => '@[".",".","*","ᙉ","ᙉᙉ","*ᙉ"]',
			'ef' => '@["⚫","⚫","⚫","⚫","⚫","⚫","⚫⚫","O","O","O","OO","OO"]',
			//'eD' => '@[".","❂","❂","❂","❂❂","❂ᐃ","ᐃ","ᐃᐃ"]',
			//'eC' => '@[".","❂","❂","❂❂","❂❂","❂ᐃ","❂ᐃ","ᐃ","ᐃ","ᐃᐃ","ᐃᐃ","ᐃ⃝"]',
			//'eS' => '@[".",".","❂","❂","ᐃ","ᐃ"]'	// 
			'ea' => '@[".","ⵜ","ⵜ","ⵜⵜ","⚚","⚚","ⵜ⚚","⚚⚚"]',
			'ep' => '@[".","ⵜ","ⵜ","ⵜⵜ","ⵜⵜ","ⵜ⚚","ⵜ⚚","ⵜ⚚","⚚","⚚⚚","⚚⚚","ⴲ"]', // ⊕ ⨁ ⵜ 
			'eb' => '@[".",".","ⵜ","⚚","⚚⚚","ⵜ⚚"]', // ⚚ ☥
			
			'ed' => '@[".","☠","☠","☠","☠☠","☠／","／","⁄⁄"]',
			'ec' => '@[".","／","／","//","//","☠／","☠／","☠","☠","☠☠","☠☠","ⵁ"]', // ⃠  ⊖ ⵁ ∅ ⴱ /
			'es' => '@[".",".","☠","☠","／","／"]',
			//'eS' => '@[.,.,☠,☠,／,／]',
			'nbb'  => '[0,1]?(1,nb)',
			'nb' => '[0,(1,nbb)]',
			'coin' => '[0,1]',
	);
/*	$macros = array(
		'sm' => 'smilie [smtag]',
		'next' => '>',
		'smilie' => '"<img src=&quot;./images/smilies/icon_>.gif&quot;&gt;"',
		'smtag' =>	'["e_biggrin","e_sad","evil","lol","cool","e_smile","e_wink","mad","e_geek","mrgreen"]',
	); // */
	$fancydice = new hanelyp\fancydice\fancydice($macros, rand());
	//$fancydice = new fancydice();
	//$fancydice->debug = true;
	$tests = array("3D6",
			"13/3", "10*3-12", '5p', '[1_5]', '["a","b","c"]', 'broken',
			"1eA 1eP 1eD 1eC 1eB 1eS 4eF",
			//'6eS',
			//'(1,2,3)',
			'6@nb', '6>@1',
	);
/*	$tests = array(
		'sm'
	); // */
	foreach ($tests as $dice)
	{
		echo "test $dice\n";
		$roll = $fancydice->roll($dice);
		echo (join("\t", $roll)."\n");
		// $roll contains 3 random #s in 1..6
		$total = $fancydice->sum($roll);
		$total = preg_replace (array('#&gt;#', '#&quot;#'),
							array('>', '"'),
							$total);
		echo ("total => $total\n");
	}
	//echo '"ⵁ ⴲ ⵜ ／⁄⁄ ☠ ⚚"';
?>
