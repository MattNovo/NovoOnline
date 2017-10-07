<?php
if ( ! function_exists( 'mZ_write_to_file' ) ) {
	/**
	 * Write message out to file
	 * @param  String/Array  $message		What we want to examine
	 * @param  String $file_path		A valid file path, default: file in WP_CONTENT_DIR
	 */

	function mZ_write_to_file($message, $file_path='')
	{
			$file_path = ( ($file_path == '') || !file_exists($file_path) ) ? WP_CONTENT_DIR . '/mbo_debug_log.txt' : $file_path;
			$header = date('l dS \o\f F Y h:i:s A', strtotime("now")) . " \nMessage:\t ";

			if (is_array($message)) {
					$header = "\nMessage is array.\n";
					$message = print_r($message, true);
			}
			$message .= "\n";
			file_put_contents(
					$file_path,
					$header . $message,
					FILE_APPEND | LOCK_EX
			);
	}
}

if ( ! function_exists( 'mz_pr' ) ) {
	/**
	 * Write message out to file
	 * @param  String/Array  $message		What we want to examine in browser
	 */
	function mz_pr($message) {
		echo "<pre>";
		print_r($message);
		echo "</pre>";
	}
}
?>