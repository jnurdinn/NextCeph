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
  	return new TemplateResponse('nextceph', 'osd');
  }

	/**
   * @NoAdminRequired
   * @NoCSRFRequired
   */
	public function mon() {
  	return new TemplateResponse('nextceph', 'mon');
  }

	/**
   * @NoAdminRequired
   * @NoCSRFRequired
   */
	public function pool() {
  	return new TemplateResponse('nextceph', 'pool');
  }

	/**
   * @NoAdminRequired
   * @NoCSRFRequired
   */
	public function host() {
  	return new TemplateResponse('nextceph', 'host');
  }

	/**
   * @NoAdminRequired
   * @NoCSRFRequired
   */
	public function config() {
  	return new TemplateResponse('nextceph', 'config');
  }

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function crush() {
		return new TemplateResponse('nextceph', 'crush');
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function perform() {
		return new TemplateResponse('nextceph', 'perform');
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function log() {
		return new TemplateResponse('nextceph', 'log');
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function apply() {
		$set = $this->sysVal->setAppValue('mgrip', $_POST["mgrip"]);
		$set = $this->sysVal->setAppValue('mgrport', $_POST["mgrport"]);
		$set = $this->sysVal->setAppValue('username', $_POST["username"]);
		$set = $this->sysVal->setAppValue('password', $_POST["password"]);
		return new RedirectResponse('/index.php/apps/nextceph/');
	}
}
