<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                                                                         *
 *  XPertMailer is a PHP Mail Class that can send and read messages in MIME format.        *
 *  This file is part of the XPertMailer package (http://xpertmailer.sourceforge.net/)     *
 *  Copyright (C) 2007 Tanase Laurentiu Iulian                                             *
 *                                                                                         *
 *  This library is free software; you can redistribute it and/or modify it under the      *
 *  terms of the GNU Lesser General Public License as published by the Free Software       *
 *  Foundation; either version 2.1 of the License, or (at your option) any later version.  *
 *                                                                                         *
 *  This library is distributed in the hope that it will be useful, but WITHOUT ANY        *
 *  WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A        *
 *  PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.        *
 *                                                                                         *
 *  You should have received a copy of the GNU Lesser General Public License along with    *
 *  this library; if not, write to the Free Software Foundation, Inc., 51 Franklin Street, *
 *  Fifth Floor, Boston, MA 02110-1301, USA                                                *
 *                                                                                         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class MAIL {

	public $From = NULL;
	public $To = array();
	public $Cc = array();
	public $Bcc = array();

	public $Subject = NULL;
	public $Text = NULL;
	public $Html = NULL;
	public $Header = array();
	public $Attach = array();

	public $Host = NULL;
	public $Port = NULL;
	public $User = NULL;
	public $Pass = NULL;
	public $Vssl = NULL;
	public $Tout = NULL;
	public $Auth = NULL;

	public $Name = NULL;
	public $Path = NULL;
	public $Priority = NULL;

	public $Context = NULL;

	public $SendMail = '/usr/sbin/sendmail';
	public $QMail = '/var/qmail/bin/sendmail';

	private $_conns = array();
	public $History = array();
	public $Result = NULL;

	public function __construct() {
		$this->_result(array(0 => 'initialize class'));
	}

	private function _result($data = array(), $ret = NULL) {
		$this->History[][strval(microtime(true))] = $data;
		$this->Result = $data;
		return $ret;
	}

	public function context($arr = NULL, $debug = NULL) {
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		if (!is_array($arr)) FUNC::trace($debug, 'invalid context type');
		else if (!is_resource($res = stream_context_create($arr))) FUNC::trace($debug, 'invalid context value');
		else {
			$this->Context = $res;
			return $this->_result(array(0 => 'set context connection'), true);
		}
	}

	public function name($host = NULL, $debug = NULL) {
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		if (!is_string($host)) FUNC::trace($debug, 'invalid hostname type');
		else {
			$host = strtolower(trim($host));
			if (!($host != '' && ($host == 'localhost' || FUNC::is_ipv4($host) || FUNC::is_hostname($host, true, $debug)))) FUNC::trace($debug, 'invalid hostname value');
			$this->Name = $host;
			return $this->_result(array(0 => 'set HELO/EHLO hostname'), true);
		}
	}

	public function path($addr = NULL, $debug = NULL) {
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		if (!is_string($addr)) FUNC::trace($debug, 'invalid address type in path $addr');
		else {
			if (!($addr != '' && FUNC::is_mail($addr))) FUNC::trace($debug, 'invalid address value');
			$this->Path = $addr;
			return $this->_result(array(0 => 'set Return-Path address'), true);
		}
	}

	public function priority($level = NULL, $debug = NULL) {
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		if ($level == NULL) {
			$this->Priority = NULL;
			return $this->_result(array(0 => 'unset priority'), true);
		} else if (is_int($level) || is_string($level)) {
			if (is_string($level)) $level = strtolower(trim(FUNC::str_clear($level)));
			if ($level == 1 || $level == 3 || $level == 5 || $level == 'high' || $level == 'normal' || $level == 'low') {
				$this->Priority = $level;
				return $this->_result(array(0 => 'set priority'), true);
			} else FUNC::trace($debug, 'invalid level value');
		} else FUNC::trace($debug, 'invalid level type');
	}

	public function from($addr = NULL, $name = NULL, $charset = NULL, $encoding = NULL, $debug = NULL) {
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		$err = array();
		if (!is_string($addr)) $err[] = 'invalid address type in from '.$addr;
		else if (!FUNC::is_mail($addr)) $err[] = 'invalid address value';
		if ($name != NULL) {
			if (!is_string($name)) $err[] = 'invalid name type';
			else {
				$name = trim(FUNC::str_clear($name));
				if ($name == '') $err[] = 'invalid name value';
			}
		}
		if ($charset != NULL) {
			if (!is_string($charset)) $err[] = 'invalid charset type';
			else if (!(strlen($charset) >= 2 && FUNC::is_alpha($charset, true, '-'))) $err[] = 'invalid charset value';
		}
		if ($encoding != NULL) {
			if (!is_string($encoding)) $err[] = 'invalid encoding type';
			else {
				$encoding = strtolower($encoding);
				if (!isset(MIME::$hencarr[$encoding])) $err[] = 'invalid encoding value';
			}
		}
		if (count($err) > 0) FUNC::trace($debug, implode(', ', $err));
		else {
			$this->From = array('address' => $addr, 'name' => $name, 'charset' => $charset, 'encoding' => $encoding);
			return $this->_result(array(0 => 'set From address'), true);
		}
	}

	public function addto($addr = NULL, $name = NULL, $charset = NULL, $encoding = NULL, $debug = NULL) {
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		$err = array();
		if (!is_string($addr)) $err[] = 'invalid address type in addto '.$addr;
		else if (!FUNC::is_mail($addr)) $err[] = 'invalid address value';
		if ($name != NULL) {
			if (!is_string($name)) $err[] = 'invalid name type';
			else {
				$name = trim(FUNC::str_clear($name));
				if ($name == '') $err[] = 'invalid name value';
			}
		}
		if ($charset != NULL) {
			if (!is_string($charset)) $err[] = 'invalid charset type';
			else if (!(strlen($charset) >= 2 && FUNC::is_alpha($charset, true, '-'))) $err[] = 'invalid charset value';
		}
		if ($encoding != NULL) {
			if (!is_string($encoding)) $err[] = 'invalid encoding type';
			else {
				$encoding = strtolower($encoding);
				if (!isset(MIME::$hencarr[$encoding])) $err[] = 'invalid encoding value';
			}
		}
		if (count($err) > 0) FUNC::trace($debug, implode(', ', $err));
		else {
			$find = false;
			if (count($this->To) > 0) {
				$ladr = strtolower($addr);
				foreach ($this->To as $to) {
					if ($ladr == strtolower($to['address'])) {
						FUNC::trace($debug, 'duplicate To address "'.$addr.'"', 1);
						$find = true;
					}
				}
			}
			if ($find) return false;
			else {
				$this->To[] = array('address' => $addr, 'name' => $name, 'charset' => $charset, 'encoding' => $encoding);
				return $this->_result(array(0 => 'add To address'), true);
			}
		}
	}

	public function delto($addr = NULL, $debug = NULL) {
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		if ($addr == NULL) {
			$this->To = array();
			return $this->_result(array(0 => 'delete all To addresses'), true);
		} else if (!(is_string($addr) && FUNC::is_mail($addr))) {
			FUNC::trace($debug, 'invalid address value');
		} else {
			$ret = false;
			$new = array();
			if (count($this->To) > 0) {
				$addr = strtolower($addr);
				foreach ($this->To as $to) {
					if ($addr == strtolower($to['address'])) $ret = true;
					else $new[] = $to;
				}
			}
			if ($ret) {
				$this->To = $new;
				return $this->_result(array(0 => 'delete To address'), true);
			} else return FUNC::trace($debug, 'To address "'.$addr.'" not found', 1);
		}
	}

	public function addcc($addr = NULL, $name = NULL, $charset = NULL, $encoding = NULL, $debug = NULL) {
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		$err = array();
		if (!is_string($addr)) $err[] = 'invalid address type addcc $addr';
		else if (!FUNC::is_mail($addr)) $err[] = 'invalid address value';
		if ($name != NULL) {
			if (!is_string($name)) $err[] = 'invalid name type';
			else {
				$name = trim(FUNC::str_clear($name));
				if ($name == '') $err[] = 'invalid name value';
			}
		}
		if ($charset != NULL) {
			if (!is_string($charset)) $err[] = 'invalid charset type';
			else if (!(strlen($charset) >= 2 && FUNC::is_alpha($charset, true, '-'))) $err[] = 'invalid charset value';
		}
		if ($encoding != NULL) {
			if (!is_string($encoding)) $err[] = 'invalid encoding type';
			else {
				$encoding = strtolower($encoding);
				if (!isset(MIME::$hencarr[$encoding])) $err[] = 'invalid encoding value';
			}
		}
		if (count($err) > 0) FUNC::trace($debug, implode(', ', $err));
		else {
			$find = false;
			if (count($this->Cc) > 0) {
				$ladr = strtolower($addr);
				foreach ($this->Cc as $cc) {
					if ($ladr == strtolower($cc['address'])) {
						FUNC::trace($debug, 'duplicate Cc address "'.$addr.'"', 1);
						$find = true;
					}
				}
			}
			if ($find) return false;
			else {
				$this->Cc[] = array('address' => $addr, 'name' => $name, 'charset' => $charset, 'encoding' => $encoding);
				return $this->_result(array(0 => 'add Cc address'), true);
			}
		}
	}

	public function delcc($addr = NULL, $debug = NULL) {
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		if ($addr == NULL) {
			$this->Cc = array();
			return $this->_result(array(0 => 'delete all Cc addresses'), true);
		} else if (!(is_string($addr) && FUNC::is_mail($addr))) {
			FUNC::trace($debug, 'invalid address value');
		} else {
			$ret = false;
			$new = array();
			if (count($this->Cc) > 0) {
				$addr = strtolower($addr);
				foreach ($this->Cc as $cc) {
					if ($addr == strtolower($cc['address'])) $ret = true;
					else $new[] = $cc;
				}
			}
			if ($ret) {
				$this->Cc = $new;
				return $this->_result(array(0 => 'delete Cc address'), true);
			} else return FUNC::trace($debug, 'Cc address "'.$addr.'" not found', 1);
		}
	}

	public function addbcc($addr = NULL, $debug = NULL) {
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		if (!is_string($addr)) FUNC::trace($debug, 'invalid address type in addbcc $addr');
		else if (!FUNC::is_mail($addr)) FUNC::trace($debug, 'invalid address value');
		$find = false;
		if (count($this->Bcc) > 0) {
			$ladr = strtolower($addr);
			foreach ($this->Bcc as $bcc) {
				if ($ladr == strtolower($bcc)) {
					FUNC::trace($debug, 'duplicate Bcc address "'.$addr.'"', 1);
					$find = true;
				}
			}
		}
		if ($find) return false;
		else {
			$this->Bcc[] = $addr;
			return $this->_result(array(0 => 'add Bcc address'), true);
		}
	}

	public function delbcc($addr = NULL, $debug = NULL) {
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		if ($addr == NULL) {
			$this->Bcc = array();
			return $this->_result(array(0 => 'delete all Bcc addresses'), true);
		} else if (!(is_string($addr) && FUNC::is_mail($addr))) {
			FUNC::trace($debug, 'invalid address value');
		} else {
			$ret = false;
			$new = array();
			if (count($this->Bcc) > 0) {
				$addr = strtolower($addr);
				foreach ($this->Bcc as $bcc) {
					if ($addr == strtolower($bcc)) $ret = true;
					else $new[] = $bcc;
				}
			}
			if ($ret) {
				$this->Bcc = $new;
				return $this->_result(array(0 => 'delete Bcc address'), true);
			} else return FUNC::trace($debug, 'Bcc address "'.$addr.'" not found', 1);
		}
	}

	public function addheader($name = NULL, $value = NULL, $charset = NULL, $encoding = NULL, $debug = NULL) {
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		$err = array();
		if (!is_string($name)) $err[] = 'invalid name type';
		else {
			$name = ucfirst(trim(FUNC::str_clear($name)));
			if (!(strlen($name) >= 2 && FUNC::is_alpha($name, true, '-'))) $err[] = 'invalid name value';
		}
		if (!is_string($value)) $err[] = 'invalid content type';
		else {
			$value = trim(FUNC::str_clear($value));
			if ($value == '') $err[] = 'invalid content value';
		}
		if ($charset != NULL) {
			if (!is_string($charset)) $err[] = 'invalid charset type';
			else if (!(strlen($charset) >= 2 && FUNC::is_alpha($charset, true, '-'))) $err[] = 'invalid charset value';
		}
		if ($encoding != NULL) {
			if (!is_string($encoding)) $err[] = 'invalid encoding type';
			else {
				$encoding = strtolower($encoding);
				if (!isset(MIME::$hencarr[$encoding])) $err[] = 'invalid encoding value';
			}
		}
		if (count($err) > 0) FUNC::trace($debug, implode(', ', $err));
		else {
			$ver = strtolower($name);
			$err = false;
			if ($ver == 'to') $err = 'can not set "To", for this, use function "AddTo()"';
			else if ($ver == 'cc') $err = 'can not set "Cc", for this, use function "AddCc()"';
			else if ($ver == 'bcc') $err = 'can not set "Bcc", for this, use function "AddBcc()"';
			else if ($ver == 'from') $err = 'can not set "From", for this, use function "From()"';
			else if ($ver == 'subject') $err = 'can not set "Subject", for this, use function "Subject()"';
			else if ($ver == 'x-priority') $err = 'can not set "X-Priority", for this, use function "Priority()"';
			else if ($ver == 'x-msmail-priority') $err = 'can not set "X-MSMail-Priority", for this, use function "Priority()"';
			else if ($ver == 'x-mimeole') $err = 'can not set "X-MimeOLE", for this, use function "Priority()"';
			else if ($ver == 'date') $err = 'can not set "Date", this value is automaticaly set';
			else if ($ver == 'content-type') $err = 'can not set "Content-Type", this value is automaticaly set';
			else if ($ver == 'content-transfer-encoding') $err = 'can not set "Content-Transfer-Encoding", this value is automaticaly set';
			else if ($ver == 'content-disposition') $err = 'can not set "Content-Disposition", this value is automaticaly set';
			else if ($ver == 'mime-version') $err = 'can not set "Mime-Version", this value is automaticaly set';
			else if ($ver == 'x-mailer') $err = 'can not set "X-Mailer", this value is automaticaly set';
			else if ($ver == 'message-id') $err = 'can not set "Message-ID", this value is automaticaly set';
			if ($err) FUNC::trace($debug, $err);
			else {
				$this->Header[] = array('name' => $name, 'value' => $value, 'charset' => $charset, 'encoding' => $encoding);
				return $this->_result(array(0 => 'add header'), true);
			}
		}
	}

	public function delheader($name = NULL, $debug = NULL) {
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		if ($name == NULL) {
			$this->Header = array();
			return $this->_result(array(0 => 'delete all headers'), true);
		} else if (!(is_string($name) && strlen($name) >= 2 && FUNC::is_alpha($name, true, '-'))) {
			FUNC::trace($debug, 'invalid name value');
		} else {
			$ret = false;
			$new = array();
			if (count($this->Header) > 0) {
				$name = strtolower($name);
				foreach ($this->Header as $header) {
					if ($name == strtolower($header['name'])) $ret = true;
					else $new[] = $header;
				}
			}
			if ($ret) {
				$this->Header = $new;
				return $this->_result(array(0 => 'delete header'), true);
			} else return FUNC::trace($debug, 'header not found', 1);
		}
	}

	public function subject($content = NULL, $charset = NULL, $encoding = NULL, $debug = NULL) {
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		$err = array();
		if (!is_string($content)) $err[] = 'invalid content type';
		else {
			$content = trim(FUNC::str_clear($content));
			if ($content == '') $err[] = 'invalid content value';
		}
		if ($charset != NULL) {
			if (!is_string($charset)) $err[] = 'invalid charset type';
			else if (!(strlen($charset) >= 2 && FUNC::is_alpha($charset, true, '-'))) $err[] = 'invalid charset value';
		}
		if ($encoding != NULL) {
			if (!is_string($encoding)) $err[] = 'invalid encoding type';
			else {
				$encoding = strtolower($encoding);
				if (!isset(MIME::$hencarr[$encoding])) $err[] = 'invalid encoding value';
			}
		}
		if (count($err) > 0) FUNC::trace($debug, implode(', ', $err));
		else {
			$this->Subject = array('content' => $content, 'charset' => $charset, 'encoding' => $encoding);
			return $this->_result(array(0 => 'set subject'), true);
		}
	}

	public function text($content = NULL, $charset = NULL, $encoding = NULL, $debug = NULL) {
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		$err = array();
		if (!(is_string($content) && $content != '')) $err[] = 'invalid content type';
		if ($charset != NULL) {
			if (!is_string($charset)) $err[] = 'invalid charset type';
			else if (!(strlen($charset) >= 2 && FUNC::is_alpha($charset, true, '-'))) $err[] = 'invalid charset value';
		}
		if ($encoding != NULL) {
			if (!is_string($encoding)) $err[] = 'invalid encoding type';
			else {
				$encoding = strtolower($encoding);
				if (!isset(MIME::$mencarr[$encoding])) $err[] = 'invalid encoding value';
			}
		}
		if (count($err) > 0) FUNC::trace($debug, implode(', ', $err));
		else {
			$this->Text = array('content' => $content, 'charset' => $charset, 'encoding' => $encoding);
			return $this->_result(array(0 => 'set text version'), true);
		}
	}

	public function html($content = NULL, $charset = NULL, $encoding = NULL, $debug = NULL) {
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		$err = array();
		if (!(is_string($content) && $content != '')) $err[] = 'invalid content type';
		if ($charset != NULL) {
			if (!is_string($charset)) $err[] = 'invalid charset type';
			else if (!(strlen($charset) >= 2 && FUNC::is_alpha($charset, true, '-'))) $err[] = 'invalid charset value';
		}
		if ($encoding != NULL) {
			if (!is_string($encoding)) $err[] = 'invalid encoding type';
			else {
				$encoding = strtolower($encoding);
				if (!isset(MIME::$mencarr[$encoding])) $err[] = 'invalid encoding value';
			}
		}
		if (count($err) > 0) FUNC::trace($debug, implode(', ', $err));
		else {
			$this->Html = array('content' => $content, 'charset' => $charset, 'encoding' => $encoding);
			return $this->_result(array(0 => 'set html version'), true);
		}
	}

	public function attach($content = NULL, $type = NULL, $name = NULL, $charset = NULL, $encoding = NULL, $disposition = NULL, $id = NULL, $debug = NULL) {
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		$err = array();
		if (!(is_string($content) && $content != '')) $err[] = 'invalid content type';
		if ($type != NULL) {
			if (!is_string($type)) $err[] = 'invalid type value';
			else {
				$type = trim(FUNC::str_clear($type));
				if (strlen($type) < 4) $err[] = 'invalid type value';
			}
		}
		if ($name != NULL) {
			if (!is_string($name)) $err[] = 'invalid name type';
			else {
				$name = trim(FUNC::str_clear($name));
				if ($name == '') $err[] = 'invalid name value';
			}
		}
		if ($charset != NULL) {
			if (!is_string($charset)) $err[] = 'invalid charset type';
			else if (!(strlen($charset) >= 2 && FUNC::is_alpha($charset, true, '-'))) $err[] = 'invalid charset value';
		}
		if ($encoding == NULL) $encoding = 'base64';
		else if (is_string($encoding)) {
			$encoding = strtolower($encoding);
			if (!isset(MIME::$mencarr[$encoding])) $err[] = 'invalid encoding value';
		} else $err[] = 'invalid encoding type';
		if ($disposition == NULL) $disposition = 'attachment';
		else if (is_string($disposition)) {
			$disposition = strtolower(FUNC::str_clear($disposition));
			if (!($disposition == 'inline' || $disposition == 'attachment')) $err[] = 'invalid disposition value';
		} else $err[] = 'invalid disposition type';
		if ($id != NULL) {
			if (!is_string($id)) $err[] = 'invalid id type';
			else {
				$id = FUNC::str_clear($id, array(' '));
				if ($id == '') $err[] = 'invalid id value';
			}
		}
		if (count($err) > 0) FUNC::trace($debug, implode(', ', $err));
		else {
			$this->Attach[] = array('content' => $content, 'type' => $type, 'name' => $name, 'charset' => $charset, 'encoding' => $encoding, 'disposition' => $disposition, 'id' => $id);
			return $this->_result(array(0 => 'add attachment'), true);
		}
	}

	public function delattach($name = NULL, $debug = NULL) {
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		if ($name == NULL) {
			$this->Attach = array();
			return $this->_result(array(0 => 'delete all attachments'), true);
		} else if (!(is_string($name) && strlen($name) > 1)) {
			FUNC::trace($debug, 'invalid name value');
		} else {
			$ret = false;
			$new = array();
			if (count($this->Attach) > 0) {
				$name = strtolower($name);
				foreach ($this->Attach as $att) {
					if ($name == strtolower($att['name'])) $ret = true;
					else $new[] = $att;
				}
			}
			if ($ret) {
				$this->Attach = $new;
				return $this->_result(array(0 => 'delete attachment'), true);
			} else return FUNC::trace($debug, 'attachment not found', 1);
		}
	}

	public function connect($host = NULL, $port = NULL, $user = NULL, $pass = NULL, $vssl = NULL, $tout = NULL, $name = NULL, $context = NULL, $auth = NULL, $debug = NULL) {
		global $_RESULT;
		$_RESULT = array();
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		if ($host == NULL) $host = $this->Host;
		if ($port == NULL) $port = $this->Port;
		if ($user == NULL) $user = $this->User;
		if ($pass == NULL) $pass = $this->Pass;
		if ($vssl == NULL) $vssl = $this->Vssl;
		if ($tout == NULL) $tout = $this->Tout;
		if ($name == NULL) $name = $this->Name;
		if ($context == NULL) $context = $this->Context;
		if ($auth == NULL) $auth = $this->Auth;
		if ($ret = SMTP::connect($host, $port, $user, $pass, $vssl, $tout, $name, $context, $auth, $debug)) $this->_conns[] = $ret;
		return $this->_result($_RESULT, $ret);
	}

	public function disconnect($resc = NULL, $debug = NULL) {
		global $_RESULT;
		$_RESULT = array();
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		if ($resc != NULL) {
			if (count($this->_conns) > 0) {
				$new = array();
				foreach ($this->_conns as $cres) {
					if ($cres != $resc) $new[] = $cres;
				}
				$this->_conns = $new;
			}
			$disc = SMTP::disconnect($resc, $debug);
			return $this->_result($_RESULT, $disc);
		} else {
			$rarr = array();
			$disc = true;
			if (count($this->_conns) > 0) {
				foreach ($this->_conns as $cres) {
					if (!SMTP::disconnect($cres, $debug)) $disc = false;
					$rarr[] = $_RESULT;
				}
			}
			return $this->_result($rarr, $disc);
		}
	}

	public function send($resc = NULL, $debug = NULL) {
		global $_RESULT;
		$_RESULT = $err = array();
		if (!FUNC::is_debug($debug)) $debug = debug_backtrace();
		if (is_resource($resc)) $delivery = 'relay';
		else {
			if ($resc == NULL) $resc = 'local';
			if (!is_string($resc)) $err[] = 'invalid connection type';
			else {
				$resc = strtolower(trim($resc));
				if ($resc == 'local' || $resc == 'client' || $resc == 'sendmail' || $resc == 'qmail') $delivery = $resc;
				else $err[] = 'invalid connection value';
			}
		}
		if (count($this->To) == 0) $err[] = 'to mail address is not set';
		if (!isset($this->Subject['content'])) $err[] = 'mail subject is not set';
		if (!(isset($this->Text['content']) || isset($this->Html['content']))) $err[] = 'mail message is not set';
		if (count($err) > 0) FUNC::trace($debug, implode(', ', $err));
		else {
			$header['local'] = $header['client'] = array();
			$body = '';
			$from = NULL;
			if (isset($this->From['address']) && is_string($this->From['address'])) {
				$from = $this->From['address'];
				$hv = 'From: ';
				if (isset($this->From['name']) && trim($this->From['name']) != '') {
					$hn = MIME::encode_header($this->From['name'], 
						isset($this->From['charset']) ? $this->From['charset'] : null, 
						isset($this->From['encoding']) ? $this->From['encoding'] : null, 
						null, null, $debug);
					if ($hn == $this->From['name']) $hn = '"'.str_replace('"', '\\"', $this->From['name']).'"';
					$hv .= $hn.' <'.$this->From['address'].'>';
				} else $hv .= $this->From['address'];
				$header['local'][] = $hv;
				$header['client'][] = $hv;
			}
			$addrs = $arr = array();
			foreach ($this->To as $to) {
				if (isset($to['address']) && FUNC::is_mail($to['address'], false, $debug)) {
					$addrs[] = $to['address'];
					if (isset($to['name']) && trim($to['name']) != '') {
						$hn = MIME::encode_header($to['name'], 
							isset($to['charset']) ? $to['charset'] : null, 
							isset($to['encoding']) ? $to['encoding'] : null, 
							null, null, $debug);
						if ($hn == $to['name']) $hn = '"'.str_replace('"', '\\"', $to['name']).'"';
						$arr[] = $hn.' <'.$to['address'].'>';
					} else $arr[] = $to['address'];
				}
			}
			if (count($arr) > 0) {
				$to = implode(', ', $arr);
				$header['client'][] = 'To: '.implode(', '.MIME::LE."\t", $arr);
			} else FUNC::trace($debug, 'to mail address is not set');
			if (count($this->Cc) > 0) {
				$arr = array();
				foreach ($this->Cc as $cc) {
					if (isset($cc['address']) && FUNC::is_mail($cc['address'], false, $debug)) {
						$addrs[] = $cc['address'];
						if (isset($cc['name']) && trim($cc['name']) != '') {
							$hn = MIME::encode_header($cc['name'], 
								isset($cc['charset']) ? $cc['charset'] : null, 
								isset($cc['encoding']) ? $cc['encoding'] : null, 
								null, null, $debug);
							if ($hn == $cc['name']) $hn = '"'.str_replace('"', '\\"', $cc['name']).'"';
							$arr[] = $hn.' <'.$cc['address'].'>';
						} else $arr[] = $cc['address'];
					}
				}
				if (count($arr) > 0) {
					$header['local'][] = 'Cc: '.implode(', ', $arr);
					$header['client'][] = 'Cc: '.implode(', '.MIME::LE."\t", $arr);
				}
			}
			$hbcc = '';
			if (count($this->Bcc) > 0) {
				$arr = array();
				foreach ($this->Bcc as $bcc) {
					if (FUNC::is_mail($bcc, false, $debug)) {
						$arr[] = $bcc;
						$addrs[] = $bcc;
					}
				}
				if (count($arr) > 0) {
					$header['local'][] = 'Bcc: '.implode(', ', $arr);
					$hbcc = MIME::LE.'Bcc: '.implode(', ', $arr);
				}
			}
			$hn = MIME::encode_header($this->Subject['content'], 
				isset($this->Subject['charset']) ? $this->Subject['charset'] : null, 
				isset($this->Subject['encoding']) ? $this->Subject['encoding'] : null, 
				null, null, $debug);
			$subject = $hn;
			$header['client'][] = 'Subject: '.$hn;
			if (is_int($this->Priority) || is_string($this->Priority)) {
				$arr = false;
				if ($this->Priority == 1 || $this->Priority == 'high') $arr = array(1, 'high');
				else if ($this->Priority == 3 || $this->Priority == 'normal') $arr = array(3, 'normal');
				else if ($this->Priority == 5 || $this->Priority == 'low') $arr = array(5, 'low');
				if ($arr) {
					$header['local'][] = 'X-Priority: '.$arr[0];
					$header['local'][] = 'X-MSMail-Priority: '.$arr[1];
					$header['local'][] = 'X-MimeOLE: Produced By XPertMailer v.4 MIME Class'; // << required by SpamAssassin in conjunction with "X-MSMail-Priority"
					$header['client'][] = 'X-Priority: '.$arr[0];
					$header['client'][] = 'X-MSMail-Priority: '.$arr[1];
					$header['client'][] = 'X-MimeOLE: Produced By XPertMailer v.4 MIME Class';
				}
			}
			$header['client'][] = 'Message-ID: <'.MIME::unique().'@xpertmailer.com>';
			if (count($this->Header) > 0) {
				foreach ($this->Header as $harr) {
					if (isset($harr['name'], $harr['value']) && strlen($harr['name']) >= 2 && FUNC::is_alpha($harr['name'], true, '-')) {
						$hn = MIME::encode_header($harr['value'], 
							isset($harr['charset']) ? $harr['charset'] : null, 
							isset($harr['encoding']) ? $harr['encoding'] : null, 
							null, null, $debug);
						$header['local'][] = ucfirst($harr['name']).': '.$hn;
						$header['client'][] = ucfirst($harr['name']).': '.$hn;
					}
				}
			}
			$text = $html = $att = NULL;
			if (isset($this->Text['content'])) {
				$text = MIME::message($this->Text['content'], 'text/plain', null, 
					isset($this->Text['charset']) ? $this->Text['charset'] : null, 
					isset($this->Text['encoding']) ? $this->Text['encoding'] : null, 
					null, null, null, null, $debug);
			}
			if (isset($this->Html['content'])) {
				$html = MIME::message($this->Html['content'], 'text/html', null, 
					isset($this->Html['charset']) ? $this->Html['charset'] : null, 
					isset($this->Html['encoding']) ? $this->Html['encoding'] : null, 
					null, null, null, null, $debug);
			}
			if (count($this->Attach) > 0) {
				$att = array();
				foreach ($this->Attach as $attach) {
					if (isset($attach['content'])) {
						$att[] = MIME::message($attach['content'], 
							isset($attach['type']) ? $attach['type'] : null, 
							isset($attach['name']) ? $attach['name'] : null, 
							isset($attach['charset']) ? $attach['charset'] : null, 
							isset($attach['encoding']) ? $attach['encoding'] : null, 
							isset($attach['disposition']) ? $attach['disposition'] : null, 
							isset($attach['id']) ? $attach['id'] : null, 
							null, null, $debug);
					}
				}
				if (count($att) == 0) $att = NULL;
			}
			$arr = MIME::compose($text, $html, $att);
			if ($delivery == 'relay') {
				$res = SMTP::send($resc, $addrs, implode(MIME::LE, $header['client']).MIME::LE.$arr['header'].MIME::LE.MIME::LE.$arr['content'], (($this->Path != NULL) ? $this->Path : $from), $debug);
				return $this->_result($_RESULT, $res);
			} else if ($delivery == 'local') {
				$rpath = (!FUNC::is_win() && $this->Path != NULL) ? '-f '.$this->Path : null;
				$spath = ($this->Path != NULL) ? @ini_set('sendmail_from', $this->Path) : false;
				if (!FUNC::is_win()) $arr['content'] = str_replace("\r\n", "\n", $arr['content']);
				$res = mail($to, $subject, $arr['content'], implode(MIME::LE, $header['local']).MIME::LE.$arr['header'], $rpath);
				if ($spath) @ini_restore('sendmail_from');
				return $this->_result(array(0 => 'send mail local'), $res);
			} else if ($delivery == 'client') {
				$group = array();
				foreach ($addrs as $addr) {
					$exp = explode('@', $addr);
					$group[strtolower($exp[1])][] = $addr;
				}
				$ret = true;
				$reg = (count($group) == 1);
				foreach ($group as $domain => $arrs) {
					$con = SMTP::mxconnect($domain, $this->Port, $this->Tout, $this->Name, $this->Context, $debug);
					if ($reg) $this->_result(array($domain => $_RESULT));
					if ($con) {
						if (!SMTP::send($con, $arrs, implode(MIME::LE, $header['client']).MIME::LE.$arr['header'].MIME::LE.MIME::LE.$arr['content'], (($this->Path != NULL) ? $this->Path : $from), $debug)) $ret = false;
						if ($reg) $this->_result(array($domain => $_RESULT));
						SMTP::disconnect($con, $debug);
					} else $ret = false;
				}
				if (!$reg) $this->_result(array(0 => 'send mail client'));
				return $ret;
			} else if ($delivery == 'sendmail' || $delivery == 'qmail') {
				$ret = false;
				$comm = (($delivery == 'sendmail') ? $this->SendMail : $this->QMail).' -oi'.(($this->Path != NULL) ? ' -f '.$this->Path : '').' -t';
				if ($con = popen($comm, 'w')) {
					if (fputs($con, implode(MIME::LE, $header['client']).$hbcc.MIME::LE.$arr['header'].MIME::LE.MIME::LE.$arr['content'])) {
						$res = pclose($con) >> 8 & 0xFF;
						if ($res == 0) {
							$ret = true;
							$this->_result(array(0 => 'send mail using "'.ucfirst($delivery).'" program'));
						} else $this->_result(array(0 => $res));
					} else $this->_result(array(0 => 'can not write'));
				} else $this->_result(array(0 => 'can not write line command'));
				return $ret;
			}
		}
	}

}

?>