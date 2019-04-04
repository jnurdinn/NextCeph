<?php
namespace OCA\NextCeph\Controller;

use OCA\NextCeph\Service\AuthorService;
use OCA\NextCeph\Request\Curl;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http\RedirectResponse;
use OCP\AppFramework\Controller;

class PageController extends Controller {
	private $userId;
	protected $sysVal;

	public function __construct($AppName, IRequest $request, $UserId, AuthorService $sysVal){
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
		$this->sysVal = $sysVal;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	 public function returnJSON() {
		if ($this->userId == 'admin') {
			$key = 'passwd';
			$params = array($key => $this->sysVal->getAppValue($key));
		} else {
			$params = array($this->userId => 'youre not the admin');
		}
		return new JSONResponse($params);
   }

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'));
		return new TemplateResponse('nextceph', 'dashboard', $params);
	}

	/**
   * @NoAdminRequired
   * @NoCSRFRequired
   */
  public function osd() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'));
		return new TemplateResponse('nextceph', 'osd', $params);
  }

	/**
   * @NoAdminRequired
   * @NoCSRFRequired
   */
	public function mon() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'));
		return new TemplateResponse('nextceph', 'mon', $params);
  }

	/**
   * @NoAdminRequired
   * @NoCSRFRequired
   */
	public function pool() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'));
		return new TemplateResponse('nextceph', 'pool', $params);
  }

	/**
   * @NoAdminRequired
   * @NoCSRFRequired
   */
	public function host() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'));
		return new TemplateResponse('nextceph', 'host', $params);
  }

	/**
   * @NoAdminRequired
   * @NoCSRFRequired
   */
	public function config() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'));
		return new TemplateResponse('nextceph', 'config', $params);
  }

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function crush() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'));
		return new TemplateResponse('nextceph', 'crush', $params);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function perform() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'));
		return new TemplateResponse('nextceph', 'perform', $params);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function log() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'));
		return new TemplateResponse('nextceph', 'log', $params);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function apply() {
		if ($_POST["type"] == "applySetting"){
			$set = $this->sysVal->setAppValue('mgrip', $_POST["mgrip"]);
			$set = $this->sysVal->setAppValue('mgrport', $_POST["mgrport"]);
			$set = $this->sysVal->setAppValue('username', $_POST["username"]);
			$set = $this->sysVal->setAppValue('password', $_POST["password"]);
			return new RedirectResponse('/index.php/apps/nextceph/');
		} else if ($_POST["type"] == "genPool"){
			$url = 'https://'.$this->sysVal->getAppValue('mgrip').':'.$this->sysVal->getAppValue('mgrport').'/pool';
			$login = $this->sysVal->getAppValue('username');
			$pass = $this->sysVal->getAppValue('password');
			$jsondata = array('name'=>$_POST["poolName"], 'pg_num'=>$_POST["poolPG"]);
			$jsondata = json_encode($jsondata);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			$obj = json_decode($result);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/pool');
		} else if ($_POST["type"] == "delPool"){
			$url = 'https://'.$this->sysVal->getAppValue('mgrip').':'.$this->sysVal->getAppValue('mgrport').'/pool/'.$_POST["id"];
			$login = $this->sysVal->getAppValue('username');
			$pass = $this->sysVal->getAppValue('password');
			$jsondata = json_encode($jsondata);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			$result = curl_exec($ch);
			$obj = json_decode($result);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/pool');
		}
	}

	private function post($url,$login,$pass,$jsondata){
		$jsondata = json_encode($jsondata);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
		$result = curl_exec($ch);
		$obj = json_decode($result);
		curl_close($ch);
		return $obj;
	}
}
