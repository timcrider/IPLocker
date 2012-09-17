<?php
/**
* Register common system level events to the 
*
*/

/**
* Register Event: Logging
* @todo Setup logging levels in the configuration and test them here
*/
\IPLocker\Event::register(
	'log',
	function ($data) {
		if (is_array($data)) {
			$level = (!empty($data['Level'])) ? $data['Level'] : 'INFO';
			$message = (!empty($data['Message'])) ? $data['Message'] : 'No Message';
		} else {
			$level = 'INFO';
			$message = (string)$data;
		}

		$line = sprintf("%s [%s]: {$message}\n", date('Ymd:His'), $level, $message);
		@file_put_contents(BASEDIR.'logs/app.log', $line, FILE_APPEND);
	}
);

\IPLocker\Event::register(
	'log.error',
	function ($data) {
		\IPLocker\Event::trigger('log', array('Level' => 'ERROR', 'Message' => $data));
	}
);

\IPLocker\Event::register(
	'log.debug',
	function ($data) {
		\IPLocker\Event::trigger('log', array('Level' => 'DEBUG', 'Message' => $data));
	}
);

/**
* Register Event: 
*/
