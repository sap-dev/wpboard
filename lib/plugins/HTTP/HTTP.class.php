<?php

	/**
		@author		< marco.a >

		< HTTP Class >
	**/

	final class HTTP implements HTTPInterface {

		/*
			@name	mimeTypes
		*/
		private static $mimeTypes = NULL;

		/*
			@name	init
			initializes
		*/
		public static function init() {
			if (self::$mimeTypes != NULL) return false;

			self::$mimeTypes = array(
				// basic
				'txt'	=> 'text/plain',
				'htm'	=> 'text/html',
				'html'	=> 'text/html',
				'php'	=> 'text/html',
				'css'	=> 'text/css',
				'js'	=> 'application/javascript',
				'json'	=> 'application/json',
				'xml'	=> 'application/xml',
				'swf'	=> 'application/x-shockwave-flash',
				'flv'	=> 'video/x-flv',

				// images
				'png'	=> 'image/png',
				'jpe'	=> 'image/jpeg',
				'jpeg'	=> 'image/jpeg',
				'jpg'	=> 'image/jpeg',
				'gif'	=> 'image/gif',
				'bmp'	=> 'image/bmp',
				'ico'	=> 'image/vnd.microsoft.icon',
				'tiff'	=> 'image/tiff',
				'tif'	=> 'image/tiff',
				'svg'	=> 'image/svg+xml',
				'svgz'	=> 'image/svg+xml',

				// archives
				'zip'	=> 'application/zip',
				'rar'	=> 'application/x-rar-compressed',
				'exe'	=> 'application/x-msdownload',
				'msi'	=> 'application/x-msdownload',
				'cab'	=> 'application/vnd.ms-cab-compressed',

				// video & audio
				'mov'	=> 'video/quicktime',
				'qt'	=> 'video/quicktime',
				'mp3'	=> 'audio/mpeg',

				// open office
				'odt'	=> 'application/vnd.oasis.opendocument.text',
				'ods'	=> 'application/vnd.oasis.opendocument.spreadsheet',

				// ms office
				'doc'	=> 'application/msword',
				'rtf'	=> 'application/rtf',
				'xls'	=> 'application/vnd.ms-excel',
				'ppt'	=> 'application/vnd.ms-powerpoint',
				'pptx'	=> 'application/vnd.ms-powerpoint',

				// adobe
				'pdf'	=> 'application/pdf',
				'psd'	=> 'image/vnd.adobe.photoshop',
				'ai'	=> 'application/postscript',
				'eps'	=> 'application/postscript',
				'ps'	=> 'application/postscript'
			);

			return true;
		}

		/*
			@name	isHex
			checks for hexadecimal
		*/
		public static function isHex($str) {
			$hex = strtolower(trim(ltrim($str, '0')));

			if (empty($hex)) $hex = 0x00;

			$dec = hexdec($hex);

			return ($hex == dechex($dec));
		}

		/*
			@name	decodeChunks
			decodes chunked response
		*/
		public static function decodeChunks($response) {
			$position = 0;
			$length = strlen($response);

			$result = '';

			while (($position < $length) && $chunkLengthHex = substr($response, $position, ($newLine = strpos($response, sprintf('%c', self::LF), $position + 1)) - $position)) {

				if (!self::isHex($chunkLengthHex)) {
					return false;
				}

				$position = $newLine + 1;
				$chunkLength = hexdec(rtrim($chunkLengthHex, sprintf('%c%c', self::CR, self::LF)));
				$result .= substr($response, $position, $chunkLength);
				$position = strpos($response, sprintf('%c', self::LF), $position + $chunkLength) + 1;

			}

			return $result;
		}

		/*
			@name	getMimeType
			gets mime type for a file by its extension
		*/
		public static function getMimeType($path) {
			$ext = explode('.', $path);
			$ext = end($ext);
			$ext = strtolower($ext);

			return (isset(self::$mimeTypes[$ext]) ? self::$mimeTypes[$ext] : 'application/octet-stream');
		}

	}

?>