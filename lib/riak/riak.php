<?php
use Tango\Core\Config;
use Tango\Core\TangoException;
use Tango\Core\Log;

// http://docs.basho.com/riak/latest/dev/references/http/#Bucket-Operations

class Riak {

	protected $_sName;
	static protected $_lInstance = [];

	protected $_iCURLError = 0;
	protected $_iJSONError = 0;

	protected $_aConfig = [];

	public static function getInstance($sName = 'default') {

		if (!$oClient =& self::$_lInstance[$sName]) {

			$lConfig = Config::get('riak');

			$lServer =& $lConfig['server'][$sName];
			if (!is_array($lServer)) {
				throw new TangoException('unknown server "'.$sName.'"');
			}

			$oClient = new self($lServer, $sName);
		}
		return $oClient;
	}

	public function __construct($lServer, $sName) {

		$this->_sName = $sName;

		$this->_aConfig = [
			'server' => $lServer,
		];
	}

	public function _http($sMethod = 'POST', $sURI, $aArg = [], $sData = NULL) {

		list($sHost, $iPort) = current($this->_aConfig['server']);

		$sURL = 'http://'.$sHost.':'.$iPort;

		$this->_iJSONError = 0;
		$this->_iCURLError = 0;

		$hCURL = curl_init();
		$aOpt = [
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_BINARYTRANSFER => TRUE,
			CURLOPT_ENCODING => 'gzip',
			CURLOPT_VERBOSE => TRUE,
			CURLOPT_URL => $sURL.$sURI,
			CURLOPT_ENCODING => 'gzip,deflate',
		];

		switch ((string)$sMethod) {

			case 'PUT':
				$aOpt[CURLOPT_CUSTOMREQUEST] = 'PUT';
			case 'POST':
				$aOpt[CURLOPT_POST] = TRUE;

				if (is_array($sData)) {
					$sData = json_encode($sData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
					$aOpt[CURLOPT_HTTPHEADER] = [
						'Content-type: application/json',
					];
				} else {
					$aOpt[CURLOPT_HTTPHEADER] = [
						'Content-type: text/plain',
					];
				}
				$aOpt[CURLOPT_POSTFIELDS] = is_null($sData) ? http_build_query($aArg, '', '&') : $sData;
				break;

			case 'GET':
				if ($aArg) {
					$aOpt[CURLOPT_URL] .= '?'.http_build_query($aArg, '', '&');
				}
				break;

			case 'DELETE':
				$aOpt[CURLOPT_CUSTOMREQUEST] = 'DELETE';
				break;

			default:
				throw new TangoException('unknown method "'.$sName.'"');
				break;
		}
		curl_setopt_array($hCURL, $aOpt);

		Log::debug('riak', sprintf('%7s %s', $sMethod, $aOpt[CURLOPT_URL]));

		$sReturn = curl_exec($hCURL);

		$aInfo = curl_getinfo($hCURL) + [
			'content_type' => '',
		];

		$this->_iCURLError = curl_errno($hCURL);

		curl_close($hCURL);

		if ($this->_iCURLError) {
			return FALSE;
		}

		Log::debug('riak', sprintf('%7s %s', ' ', $aInfo['content_type']));
		Log::debug('riak', '');

		switch ((string)$aInfo['content_type']) {

		 	case 'application/json':
				$aReturn = json_decode($sReturn, TRUE);
				if ($iJSONError = json_last_error()) {
					$this->_iJSONError = $iJSONError;
					return FALSE;
				}
				return $aReturn;
				break;

			default:
				return $sReturn;
				break;
		}
	}

	// http://docs.basho.com/riak/latest/dev/references/http/list-buckets/
	public function listBuckets() {
		$aReturn = $this->_http('GET', '/buckets', ['buckets' => 'true']);
		if (!is_array($aReturn) || empty($aReturn['buckets'])) {
			return [];
		}
		return $aReturn['buckets'];
	}

	// http://docs.basho.com/riak/latest/dev/references/http/list-keys/
	public function listKeys($sBucket) {
		$aReturn = $this->_http('GET', '/buckets/'.$sBucket.'/keys', ['keys' => 'true']);
		if (!is_array($aReturn) || empty($aReturn['keys'])) {
			return [];
		}
		return $aReturn['keys'];
	}

	// http://docs.basho.com/riak/latest/dev/references/http/get-bucket-props/
	public function getBucketProps($sBucket) {
		$aReturn = $this->_http('GET', '/buckets/'.$sBucket.'/props');
		if (!is_array($aReturn) || empty($aReturn['props'])) {
			return [];
		}
		return $aReturn['props'];
	}

	// http://docs.basho.com/riak/latest/dev/references/http/fetch-object/
	public function fetchObj($sBucket, $sKey) {
		$aReturn = $this->_http('GET', '/buckets/'.$sBucket.'/keys/'.$sKey);
		return $aReturn;
		if (!is_array($aReturn) || empty($aReturn['props'])) {
			return [];
		}
		return $aReturn['props'];
	}

	// http://docs.basho.com/riak/latest/dev/references/http/store-object/
	public function storeObj($sBucket, $sData, $sKey = NULL) {
		if ($sKey) {
			$aReturn = $this->_http('PUT', '/buckets/'.$sBucket.'/keys/'.$sKey, [], $sData);
		} else {
			$aReturn = $this->_http('POST', '/buckets/'.$sBucket.'/keys', [], $sData);
		}
		return $aReturn;
		if (!is_array($aReturn) || empty($aReturn['props'])) {
			return [];
		}
		return $aReturn['props'];
	}

	// http://docs.basho.com/riak/latest/dev/references/http/delete-object/
	public function deleteObj($sBucket, $sKey) {
		$aReturn = $this->_http('DELETE', '/buckets/'.$sBucket.'/keys/'.$sKey);
		return $aReturn;
	}

	// http://docs.basho.com/riak/latest/dev/references/http/ping/
	public function ping() {
		$sReturn = $this->_http('GET', '/ping');
		return $sReturn === 'OK';
	}

	// http://docs.basho.com/riak/latest/dev/references/http/status/
	public function status() {
		$aReturn = $this->_http('GET', '/stats');
		return $aReturn;
	}
}
