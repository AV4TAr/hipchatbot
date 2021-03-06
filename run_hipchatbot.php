<?php

require(dirname(__FILE__) . '/class.HipChatBot.php');

// give it 1) your api token and 2) the room_id to monitor
$hcb = new HipChatBot('6c6adf46e1e3cbe9931d1c5af74be7','464562');

/* 
register some simple bots:  
 	1) call bot name  
 	2) bot display name  
 	3) function receiving args and returning what to send back to hipchat room 


*/

// simple bot that can return weather and emoticon info..
//  example typing ":bot weather" will return weather info from yahoo api 
$hcb->register_name_bot('bot', 'Bot', function($args) {

	$weather_url = 'http://query.yahooapis.com/v1/public/yql?format=json&q=select%20*%20from%20weather.forecast%20where%20woeid=468052%20%20and%20u="c"';
	$emoticons = array('(hipchat)','(beer)','(coffee)','(poo)','(heart)',
					   '(itsatrap)','(lolwut)','(sadpanda)','(bumble)',
					   '(cornelius)','(thumbsup)','(thumbsdown)',
					   '(washington)','(lincoln)','(taft)','(jobs)',
					   '(fu)','(troll)','(megusta)','(okay)','(omg)',
					   '(foreveralone)','(sadtroll)','(yuno)','(cereal)',
					   '(awyeah)','(lol)','(facepalm)','(garret)',
					   '(chris)','(pete)','(huh)','(gtfo)');

	if ( isset($args[0]) && $args[0] == 'weather' ) {
		$response = json_decode(HipChatBot::_curl($weather_url),true);
		return $response['query']['results']['channel']['item']['description'];
	} elseif ( isset($args[0]) && $args[0] == 'emoticons' ) {
		$text = '';
		foreach ( array_chunk($emoticons,5) as $emoticon_chunk) {
			$text .= implode(' ',$emoticon_chunk);
			$text .= '<br />';
		}
		return $text;
	}
	return "invalid argument";
});

// very simple bot will return back the first argument or 'nothing given'
$hcb->register_name_bot('rms', 'RMS', function($args) {
	if ( isset($args[0]) && strlen($args[0]) ) {
		return 'you said "' . $args[0] . '"';
	}
	return 'nothing given';
});

// "see bot" looks for a certain string in a message and then does func if finds it...
$hcb->register_needle_bot('emacs', 'Jack', function($args) {
	return 'yes! emacs was mentioned';
});



// start the bot up...
$hcb->run();
