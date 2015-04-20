<?php
/**
*
* @package hanelyp\fancydice
* @copyright (c) 2015 Peter Hanely
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace hanelyp\fancydice\event;

/**
* Event listener
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class main_listener implements EventSubscriberInterface
{
	private $dice;
	private $macros = false;
//	private $bbcode;
	static $myself;
	public $user;
	private $user_lastpost_time = false;
	private $rollcount = 0;
		
	/**
	* Instead of using "global $user;" in the function, we use dependencies again.
	*/
	public function __construct($config, $user)
	{
		//echo 'constructing listener, fancydice<br />';
	/*	if (!isset($config['fancyDiceMacro_1']))
		{
			debug_print_backtrace(0,3);
		} // */
		$this->config = $config;
		$user->add_lang_ext('hanelyp/fancydice', 'common');
		$this->user = $user;
//		$this->bbcode = $bbcode;
		//$this->prep_bbcode();
		//echo "setting self for singlet<br />";
		self::singlet($this);
		//global $last_post_time;
		//echo 'post time '.$last_post_time.'<br />';
		//echo $user->data['user_lastpost_time'].'<br />';
	}
	
	static public function getSubscribedEvents()
	{
		//echo 'getting subscribed events, fancydice<br />';
		return array(
//			'core.viewtopic_modify_post_row'	=> 'dice_process',
//			'core.modify_submit_post_data'		=> 'dice_process_post',
//			'core.modify_text_for_storage_before'	=> 'dice_process_post',

// must call this before normal bbcode runs or bbcode messes up with multiple rolls on a post
	// called for post display
			'core.modify_text_for_display_before'	=> 'dice_process_text',
//			'core.modify_text_for_display_after'	=> 'dice_process_text',
// called for post preview, doesn't hit for normal bbcode trigger?
			'core.modify_format_display_text_after'	=> 'dice_process_text',

			'core.posting_modify_message_text'		=> 'dice_process_posting',

//			'core.bbcode_cache_init_end'			=> 'bbcode_add_wrap',
// make sure this is constructed before bbcode is run
			'core.modify_bbcode_init'				=> 'bbcode_add_wrap',
//			'core.modify_text_for_display_before'	=> 'bbcode_add_wrap',
//			'core.user_setup'						=> 'user_data',
		);
	}
/*
	public function prep_bbcode()
	{
		$bbcode = hanelyp\fancydice\bbcode::singlet();
		$bbcode->setup($this->config);
	}
*/	
	function get_macros()
	{
		global $config;
		//$macros = false;
		$macros = $this->macros;
		if (!$macros)
		{
			if (isset($config['fancyDiceMacro_1']))
			{
				$macros = array();
				$i = 1;
				while (isset($config['fancyDiceMacro_'.$i]))
				{
					$macro = json_decode($config['fancyDiceMacro_'.$i]);
					//$macro = explode(':', $config['fancyDiceMacro_'.$i]);
					foreach ($macro as $key=>$value)
					{
					//	echo "$i $key => $value<br />";
						$macros[$key] = $value;
						//$macros[$macro[0]] = array('INDEX'=>$i, 'NAME'=>$macro[0], 'DEF'=>$macro[1]);
					}
					$i++;
				}
			}
			$this->macros = $macros;
		}
	/*	else
		{
			debug_print_backtrace(0,3);
		} // */
		return $macros;
	}

	// not doing anything here, but requires the core to initialize object	
	public function bbcode_add_wrap($event)
	{

	}
	
	public function user_data($event)
	{
		print_r($event['user_data']);
	}

	public function dice_process_posting($event)
	{
		//echo 'dice_process_posting<br />';
		/*
		cancel, forum_id, load, message_parser, mode, post_data, post_id, preview, refresh, save, submit, topic_id 
		*/
		//print_r ($event['post_data']);
		//echo '<br />';
		$message = $event['message_parser']->message.'<br />';
		// count already prepped dice in post
		$this->rollcount = preg_match_all('#\[dice\sseed=(\d+)\ssecure=(\w+):?\w*\](.+?)\[/dice\]#i',
					$message);
		//echo 'prior count '.$this->rollcount.'<br />';
	}

	public function dice_process_text($event)
	{
		$message = $event['text'];
		$that = $this;
		$message = preg_replace_callback('#\[dice\sseed=(\d+)\ssecure=(\w+):?\w*\](.+?)\[/dice\]#i',
			function($matches) use ($that)
			{
				return $that->text_replace_dice($matches);
			}
			, $message);
		//echo $message, '<br />';
		$event['text'] = $message;
	}

	function text_replace_dice($matches)
	{
		//print_r ($matches);	echo '<br />';
		//return json_encode($matches);		
		return $this->bb_replace_dice($matches[3], $matches[1], $matches[2]);
	}

	// start direct bbcode integration
	static function singlet($me = false)
	{
		if ($me)
		{
			self::$myself = $me;
		}
		if (!self::$myself)
		{
			// dubious
			self::$myself = new main_listener(false, false);
		} // */
		return self::$myself;
	}

	function get_seed()
	{
		//return rand();
		// random enough value that should never change over the course of making a post,
		//	but always between posts
		return $this->user->data['user_lastpost_time'] ^
				1*$this->user->data['user_email_hash'] ^
				($this->config['fancyDiceSecure']*$this->rollcount++);
	}
	
	// invoked by bbcode first pass
	public function bb_prep_dice($spec, $uid)
	{
		//echo "$spec<br />";
		//global $last_post_time;
		//echo 'last post '.$last_post_time.'<br />';
		$seed = $this->get_seed(); //rand();
		$secure = $this->validate($seed, $spec);
		return '[dice seed='.$seed.' secure='.$secure.':'.$uid.']'.$spec.'[/dice]';
	}

	// not the most secure, but enough to discourage fiddling with the seed
	function validate($seed, $spec)
	{
		//echo $this->config['fancyDiceSecure'].' : '.$seed.'<br />';
		return substr(sha1($this->config['fancyDiceSecure'].$seed.$spec),0, 8);
	}

	// second pass won't call this correctly for post display, but does for post preview		
	public function bb_replace_dice($spec, $seed, $secure)
	{
		//return
		//debug_print_backtrace(0,2);
		//echo "$spec - $seed - $secure - ".$this->validate($seed, $spec)."<br />";
		// validate seed against secure
		$validate = $this->validate($seed, $spec);
		$valid = $validate==$secure?'':$this->user->lang('FANCYDICE_FIDDLED'); //' invalid';
/*		if (strlen($valid) > 0)
		{
			echo "$spec - $seed - $secure - $validate<br />";
			//debug_print_backtrace(0,4);
			//echo '<br />';
		} // */

		$dice = new \hanelyp\fancydice\fancydice($this->get_macros(), $seed, $this->user);
		//$dice->debug = true;
		$roll = $dice->roll($spec);
		$total = $dice->sum($roll);

		$roll1 = join(', ', $roll);
		$roll1 = preg_replace (array('#&gt;#', '#&quot;#'),
							array('>', '"'),
							$roll1);
		$total = preg_replace (array('#&gt;#', '#&quot;#'),
							array('>', '"'),
							$total);
							
		//return '<div class="dicebox">'.$spec.' => '.join(', ', $roll).' => '.$total.$valid.'</div>';
		$pattern = $this->config['fancyDicePresent'];
		return preg_replace (array('#{spec}#i', '#{dice}#i', '#{total}#i', '#{valid}#iu'),
					array($spec, $roll1, $total, $valid),
					$pattern); // */
		//return $pattern;
	}
}

