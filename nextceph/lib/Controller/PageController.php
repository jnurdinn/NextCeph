<?php
namespace OCA\NextCeph\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;

class PageController extends Controller {
	private $userId;

	public function __construct($AppName, IRequest $request, $UserId){
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index($template = 'dashboard') {
		return new TemplateResponse('nextceph', $template);  // templates/index.php
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function dashboard() {
		return new TemplateResponse('nextceph', 'dashboard');
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
}
