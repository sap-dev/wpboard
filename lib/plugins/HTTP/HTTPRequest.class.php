<?php

	/**
		@author		< marco.a >

		< HTTPRequest Class >
	**/

	final class HTTPRequest implements HTTPRequestInterface {

		/*
			@name	inited
		*/
		public $inited = false;

		/*
			@name	options
		*/
		private $options = NULL;

		/*
			@name	method
		*/
		private $method = NULL;

		/*
			@name	useMultipart
		*/
		private $useMultipart = NULL;

		/*
			@name	useUTF
		*/
		private $useUTF = NULL;

		/*
			@name	headers
		*/
		private $headers = NULL;

		/*
			@name	response
		*/
		private $response = NULL;

		/*
			@name	alloc
			allocates HTTPRequest instance
		*/
		public static function alloc($options = 0x00) {
			$obj = new self();

			$obj->init();

			if ($options > 0x00) {
				$obj->setOpts($options);
			}

			return $obj;
		}

		/*
			@name	init
			inits request
		*/
		public function init() {
			if ($this->inited) return false;

			$this->inited = true;
			$this->options = array();
			$this->headers = array();

			$this->addHeader('Connection', 'close');

			return true;
		}

		/*
			@name	setOpt
			sets option
		*/
		public function setOpt($key, $value) {
			if (isset($this->options[$key])) return false;

			$this->options[$key] = $value;

			return true;
		}

		/*
			@name	getOpt
			gets option
		*/
		public function getOpt($name) {
			if (!isset($this->options[$name])) return NULL;

			return $this->options[$name];
		}

		/*
			@name	setOpts
			sets options
		*/
		public function setOpts($options) {
			if ($this->inited == false) return false;

			/*
				METHOD_GET              0000 0001 -> 0x 0 1
				METHOD_POST             0000 0011 -> 0x 0 3

				METHOD_GET | MULTIPART  0001 0001 -> 0x 1 1
				METHOD_GET | USE_UTF    0011 0001 -> 0x 3 1

				METHOD_POST | MULTIPART 0001 0011 -> 0x 1 3
				METHOD_POST | USE_UTF   0011 0011 -> 0x 3 3
			*/

			// get lower tetrad
			$lowTetrad = $options & 0x0F;

			// get higher tetrad
			$highTetrad = $options & 0xF0;

			if ($lowTetrad != 0x01 && $lowTetrad != 0x03) {
				return false;
			}

			if ($highTetrad != 0x10 && $highTetrad != 0x30) {
				return false;
			}

			$this->method = $lowTetrad;
			$this->useMultipart = ($highTetrad == 0x10);
			$this->useUTF = ($highTetrad == 0x30);

			return true;
		}

		/*
			@name	addHeader
			adds a header
		*/
		public function addHeader($name, $value) {
			if (isset($this->headers[$name])) return false;

			$this->headers[$name] = $value;

			return true;
		}

		/*
			@name	getHeaders
			gets headers
		*/
		public function getHeaders($parsed) {
			if ($parsed == false) return $this->headers;

			$payload = '';

			foreach ($this->headers as $headerName => $headerValue) {
				$payload .= sprintf('%s: %s%c%c', $headerName, $headerValue, HTTP::CR, HTTP::LF);
			}

			return $payload;
		}

		/*
			@name	send
			sends request
		*/
		public function send($callback) {
			if (!is_callable($callback)) return false;

			$host = $port = $reqFile = $timeout = $data = NULL;

			if (($host = $this->getOpt(HTTP::OPT_HOST)) == NULL) return false;

			if (($port = $this->getOpt(HTTP::OPT_PORT)) == NULL) {
				$port = 80;
			}

			if (($reqFile = $this->getOpt(HTTP::OPT_REQ_FILE)) == NULL) {
				$reqFile = '';
			}

			if (($timeout = $this->getOpt(HTTP::OPT_TIMEOUT)) == NULL) {
				$timeout = 10;
			}

			$this->addHeader('Host', $host);

			$reqFile = sprintf('/%s', $reqFile);

			$CRLF = sprintf('%c%c', HTTP::CR, HTTP::LF);

			if ($this->useMultipart) {
				$boundary = strtoupper(substr(sha1(uniqid('', true)), 0, 12));
			}

			if (($data = $this->getOpt(HTTP::OPT_DATA)) != NULL) {
				if (!($data instanceof HTTPRequestDataInterface)) return false;

				$fields = $data->getFields();

				$fieldsPayload = ($this->method == HTTP::OPT_METHOD_POST ? sprintf('%s', $CRLF) : '');

				foreach ($fields as $fieldName => $fieldValue) {
					if ($this->useMultipart && $this->method == HTTP::OPT_METHOD_POST) {
						if (is_array($fieldValue)) {
							$fieldsPayload .= sprintf('--%s%sContent-Disposition: form-data; name="%s"; filename="%s"%sContent-Type: %s%sContent-Transfer-Encoding: binary%s%s%s%s',
														$boundary,
														$CRLF,
														$fieldName,
														$fieldValue['path'],
														$CRLF,
														HTTP::getMimeType($fieldValue['path']),
														$CRLF,
														$CRLF,
														$CRLF,
														file_get_contents($fieldValue['path']),
														$CRLF
														);
						} else {
							$fieldsPayload .= sprintf('--%s%sContent-Disposition: form-data; name="%s"%s%s%s%s', $boundary, $CRLF, $fieldName, $CRLF, $CRLF, $fieldValue, $CRLF);
						}
					} else {
						$fieldsPayload .= sprintf('%s=%s&', $fieldName, $fieldValue);
					}
				}

				if ($this->useMultipart) {
					$fieldsPayload .= sprintf('--%s--', $boundary);
				} else {
					$fieldsPayload = mb_substr($fieldsPayload, 0, mb_strlen($fieldsPayload, 'UTF-8') - 1, 'UTF-8');
				}

				if ($this->method == HTTP::OPT_METHOD_POST) {
					$this->addHeader('Content-Length', strlen($fieldsPayload)); // utf8 is evil

					if ($this->useMultipart) {
						$this->addHeader('Content-Type', 'multipart/form-data; boundary='.$boundary);
					} else {
						$this->addHeader('Content-Type', 'application/x-www-form-urlencoded');
					}

					$fieldsPayload .= sprintf('%s', $CRLF);
				}

			}

			$plainHeaders = $this->getHeaders(true);
			if (isset($fieldsPayload)) {
				if ($this->method == HTTP::OPT_METHOD_POST) {
					$plainHeaders .= $fieldsPayload;
				} else {
					$getParams = explode('?', $reqFile);

					if (sizeof($getParams) == 1) {
						$reqFile .= '?'.$fieldsPayload;
					} else {
						$reqFile .= '&'.$fieldsPayload;
					}
				}
			}

			$plainHeaders = sprintf('%s %s HTTP/1.1%s%s', ($this->method == HTTP::OPT_METHOD_GET ? 'GET' : ($this->method == HTTP::OPT_METHOD_POST ? 'POST' : 'GET')), $reqFile, $CRLF, $plainHeaders);

			$this->response = new HTTPResponse();

			$_errno = 0;
			$_errstr = '';

			$handle = @fsockopen($host, $port, $_errno, $_errstr, $timeout);

			$this->response->setItem('errorCode', ($handle == false && $_errno == 0) ? -1 : $_errno);
			$this->response->setItem('errorString', $_errstr);

			if ($handle == false) {
				$this->response->lock();

				return $callback($this->response);
			}

			if ($this->method == HTTP::OPT_METHOD_GET) {
				$plainHeaders .= sprintf('%s%s', $CRLF, $CRLF);
			}

			fwrite($handle, $plainHeaders);

			$response = '';

			while (feof($handle) == false) {
				$response .= fgets($handle, 8);
			}

			fclose($handle);

			$responseSplitted = explode(sprintf('%s%s', $CRLF, $CRLF), $response);

			/*
				get data from headers
			*/
			$headers = $responseSplitted[0];
			$responseSplitted[0] = NULL;
			unset($responseSplitted[0]);

			$response = implode($responseSplitted, sprintf('%s%s', $CRLF, $CRLF));

			$tmpHeader = strtolower($headers);

			if (preg_match('~transfer\-encoding\:(\s|)chunked~Ui', $tmpHeader) === 1) {
				// response is chunked
				$response = HTTP::decodeChunks($response);
			}

			$GLOBALS['responseCode'] = 0;

			preg_replace_callback('~HTTP\/1\.1 ([0-5]{3})~Ui', function($match) {
				$GLOBALS['responseCode'] = $match[1];
			}, $headers);

			$GLOBALS['mimeType'] = '';

			preg_replace_callback(sprintf('~Content\-Type\: (.*)%s~Ui', $CRLF), function($match) {
				$GLOBALS['mimeType'] = str_replace($CRLF, '', $match[1]);
			}, $headers);

			/*
				build response
			*/
			$this->response->setItem('responseCode', (int)$GLOBALS['responseCode']);
			$this->response->setItem('response', $response);
			$this->response->setItem('mimeType', $GLOBALS['mimeType']);
			$this->response->lock();

			/*
				TO BE CONTINUED
			*/

			return $callback($this->response);
		}

	}

?>