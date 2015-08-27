<?php
namespace hanelyp\fancydice\acp;

class defs
{
	var $BB_sql_ary = array(
	//	'bbcode_id'					=> $this->bbcodecount(),
		'bbcode_tag'				=> 'dice',
		'bbcode_match'				=> '#[dice\s+seed={number}\s+secure={simpletext}]{text}[/dice]#ie',
		'bbcode_tpl'				=> '<blockquote>hanelyp\fancydice\event\main_listener::singlet()->replace_dice("{text}",{number},"{simpletext}")</blockquote>',
		'display_on_posting'		=> 1,
		'bbcode_helpline'			=> '[dice]3d6+1[/dice]',
		'first_pass_match'			=> '#\[dice\](.+)\[/dice\]#ie',
		'first_pass_replace'		=> 'hanelyp\fancydice\event\main_listener::singlet()->bb_prep_dice(\'$1\',\'$uid\')',
		'second_pass_match'			=> '#\[dice\s+seed=(\d+)\s+secure=([\w/+]+):?\w*\](.+)\[/dice\]#ie',
		'second_pass_replace'		=> 'hanelyp\fancydice\event\main_listener::singlet()->bb_replace_dice(\'$3\',$1,\'$2\')',
	);
	
}
?>
