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
	static $myself;
	
	/**
	* Instead of using "global $user;" in the function, we use dependencies again.
	*/
	public function __construct($config)
	{
		//echo 'constructing listener, fancydice<br />';
		$this->config = $config;
		//$this->prep_bbcode();
		//echo "setting self for singlet<br />";
		self::singlet($this);
	}
	
	static public function getSubscribedEvents()
	{
		//echo 'getting subscribed events, fancydice<br />';
		return array(
//			'core.viewtopic_modify_post_row'	=> 'dice_process',
//			'core.modify_submit_post_data'		=> 'dice_process_post',
//			'core.modify_text_for_storage_before'	=> 'dice_process_post',

			'core.modify_text_for_display_before'	=> 'dice_process_text',

//			'core.posting_modify_message_text'		=> 'dice_process_posting',

//			'core.bbcode_cache_init_end'		=> 'bbcode_add_wrap',
			'core.modify_bbcode_init'			=> 'bbcode_add_wrap',
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
		$macros = false;
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
					$macros[$key] = $value;
					//$macros[$macro[0]] = array('INDEX'=>$i, 'NAME'=>$macro[0], 'DEF'=>$macro[1]);
				}
				$i++;
			}
		}
		return $macros;
	}

	// not doing anything here, but requires the core to initialize object	
	public function bbcode_add_wrap($event)
	{

	}
	
	// scan message for [dice] bbcode tag, and replace with processed dice
/*	public function dice_process($event)
	{
		$post_row = $event['post_row'];
		$post_id = $post_row['POST_ID'];
		$message = $post_row['MESSAGE'];
		global $config;
		
		// load configured macros
		$macros = $this->get_macros();
		
		$this->dice = new \hanelyp\fancydice\fancydice($macros, $post_id);

		//$matches = array();
		preg_replace_callback('/(\[dice\])(.*)([[\\dice\]/)', $this->replace_dice, $message);
	}
// */
	// scan message for [dice] bbcode tag, and replace with processed dice
	// run before message is recorded, preserves results for quotes
/*	public function dice_process_post($event)
	{
		//echo "dice processing<br />";
		$post = $event['data'];
		//$post_id = $post['POST_ID'];
		$message = $post['message'];
		global $config;
		//echo $message;
		
		// load configured macros
		$macros = $this->get_macros();
		
		$this->dice = new \hanelyp\fancydice\fancydice($macros);

		//$matches = array();
		//preg_replace_callback('/(\[dice\])(.*)([[\\dice\]/)', $this->replace_dice, $message);
		preg_replace_callback('/(\[dice\])(.*)(\[\\dice\])/',
			function($matches) use ($that)
			{
				return $that->replace_dice($matches);
			}
			, $message);
		//echo $message;
		$event['message'] = $message;
	}
// */
	public function dice_process_text($event)
	{
//		echo "dice processing text<br />";
		//print_r($event); die();
		//$post = $event['data'];
		//$post_id = $post['POST_ID'];
		//print_r($post);		
		$message = $event['text'];	//$post['text'];
		//global $config;
		//echo $message,'<br />';
		
		// load configured macros
		$this->macros = $this->get_macros();
		
		//$this->dice = new \hanelyp\fancydice\fancydice($macros);

		//$matches = array();
		$that = $this;
		//preg_replace_callback('/(\[dice\])(.*)([[\\dice\]/)', $this->replace_dice, $message);
		//preg_replace_callback('/(\[dice\])(.*)(\[\\dice\])/',
		$message = preg_replace_callback('#\[dice\s+seed=(\d+)\s+secure=(\w+):?\w*\](.+)\[/dice\]#i',
										//'/\[dice\s*seed=(\d+)\s*secure=(\w+)\](.*)\[\\dice\]/i',
			function($matches) use ($that)
			{
				//return $that->replace_dice($matches);
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

	// obsolete early processing.  replaced by bb_prep_dice invoked through bbcode first pass.
/*	public function dice_process_posting($event)
	{
		//return;
		echo "dice posting<br />";
		$message = $event['message_parser']->message;
		// need to replace post_id here.  Will be covered by bbcode integration being considered
		//echo $event['post_id'],'<br />',$message, '<br />';
//		global $config;
		
		// load configured macros
		$macros = $this->get_macros();
		
		$this->dice = new \hanelyp\fancydice\fancydice($macros, $event['post_id']);
		//$this->dice->debug = true;
		//$this->dice->debug = true;
		$that = $this;
		//preg_replace_callback('/(\[dice\])(.*)([[\\dice\]/)', $this->replace_dice, $message);
		//$message = preg_replace_callback('/\\[dice\\]([^\/]+)(\/dice\\])/',
		$message = preg_replace_callback('/\\[dice\\](.+)(\\[\/dice\\])/',
			function($matches) use ($that)
			{
				return $that->replace_dice($matches);
			}
			, $message);
		//$event['preview'] = $message;
		//echo $message, '<br />';
		$event['message_parser']->message = $message;
		//return ($event);
	}
	
	public function replace_dice($matches)
	{
		$spec = $matches[1];
		// trim trailing [
		//$spec = substr($matches[1], 0, strlen($spec)-1);
		//echo 'replacing '.$spec.'<br />';
		$roll = $this->dice->roll($spec);
		$total = $this->dice->sum($roll);

		return '[quote]'.$spec.' => '.join(', ', $roll).' => '.$total.'[/quote]';
	}
// */
	// start direct bbcode integration
	static function singlet($me = false)
	{
		if ($me)
		{
			self::$myself = $me;
		}
		if (!self::$myself)
		{
			self::$myself = new main_listener(false);
		} // */
		return self::$myself;
	}

	// invoked by bbcode first pass
	public function bb_prep_dice($spec, $uid)
	{
		//echo "$spec<br />";
		$seed = rand();
		$secure = $this->validate($seed);
		return '[dice seed='.$seed.' secure='.$secure.':'.$uid.']'.$spec.'[/dice]';
	}

	// not the most secure, but enough to discourage fiddling with the seed
	function validate($seed)
	{
		//echo $this->config['fancyDiceSecure'].' : '.$seed.'<br />';
		return substr(sha1($this->config['fancyDiceSecure'].$seed),0, 8);
	}

	// second pass won't call this correctly for post display, but does for post preview		
	public function bb_replace_dice($spec, $seed, $secure)
	{
		//return
		//debug_print_backtrace(0,2);
		//echo "$spec $seed $secure ".$this->validate($seed)."<br />";
		// validate seed against secure
		$valid = $this->validate($seed)==$secure?'':' invalid';

		$dice = new \hanelyp\fancydice\fancydice($this->macros, $seed);
		$roll = $dice->roll($spec);
		$total = $dice->sum($roll);

		return '<div class="dicebox">'.$spec.' => '.join(', ', $roll).' => '.$total.$valid.'</div>';
	}
}

