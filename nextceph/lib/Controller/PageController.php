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
	 * @NoCSRFRequired
	 */
	public function index() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'),$this->sysVal->getAppValue('nrip'),$this->sysVal->getAppValue('nrport'));
		return new TemplateResponse('nextceph', 'dashboard', $params);
	}
	/**
   * @NoCSRFRequired
   */
  public function osd() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'));
		return new TemplateResponse('nextceph', 'osd', $params);
  }
	/**
   * @NoCSRFRequired
   */
	public function mon() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'));
		return new TemplateResponse('nextceph', 'mon', $params);
  }
	/**
   * @NoCSRFRequired
   */
	public function pool() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'),$this->sysVal->getAppValue('nrip'),$this->sysVal->getAppValue('nrport'));
		return new TemplateResponse('nextceph', 'pool', $params);
  }
	/**
   * @NoCSRFRequired
   */
	public function host() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'),$this->sysVal->getAppValue('nrip'),$this->sysVal->getAppValue('nrport'));
		return new TemplateResponse('nextceph', 'host', $params);
  }
	/**
   * @NoCSRFRequired
   */
	public function config() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'));
		return new TemplateResponse('nextceph', 'config', $params);
  }
	/**
	 * @NoCSRFRequired
	 */
	public function crush() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'),$this->sysVal->getAppValue('nrip'),$this->sysVal->getAppValue('nrport'));
		return new TemplateResponse('nextceph', 'crush', $params);
	}
	/**
	 * @NoCSRFRequired
	 */
	public function perform() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'),$this->sysVal->getAppValue('nrip'),$this->sysVal->getAppValue('nrport'));
		return new TemplateResponse('nextceph', 'perform', $params);
	}
	/**
	 * @NoCSRFRequired
	 */
	public function log() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'));
		return new TemplateResponse('nextceph', 'log', $params);
	}
	/**
	 * @NoCSRFRequired
	 */
	public function cephfs() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'));
		return new TemplateResponse('nextceph', 'cephfs', $params);
	}
	/**
	 * @NoCSRFRequired
	 */
	public function rbd() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'),$this->sysVal->getAppValue('nrip'),$this->sysVal->getAppValue('nrport'));
		return new TemplateResponse('nextceph', 'rbd', $params);
	}
	/**
	 * @NoCSRFRequired
	 */
	public function rgw() {
		$params = array($this->sysVal->getAppValue('mgrip'),$this->sysVal->getAppValue('mgrport'), $this->sysVal->getAppValue('username'), $this->sysVal->getAppValue('password'),$this->sysVal->getAppValue('nrip'),$this->sysVal->getAppValue('nrport'));
		return new TemplateResponse('nextceph', 'radosgw', $params);
	}
	/**
	 * @NoCSRFRequired
	 */
	public function apply() {
		if ($_POST["type"] == "applySetting"){
			$set = $this->sysVal->setAppValue('mgrip', $_POST["mgrip"]);
			$set = $this->sysVal->setAppValue('mgrport', $_POST["mgrport"]);
			$set = $this->sysVal->setAppValue('username', $_POST["username"]);
			$set = $this->sysVal->setAppValue('password', $_POST["password"]);
			$set = $this->sysVal->setAppValue('nrip', $_POST["nrip"]);
			$set = $this->sysVal->setAppValue('nrport', $_POST["nrport"]);
			return new RedirectResponse('/index.php/apps/nextceph/');
		} else if ($_POST["type"] == "genPool"){
			$jsondata = array('prefix'=>'osd pool create','pool'=>$_POST["name"],'pg_num'=>(int)$_POST["pg_num"],'size'=>(int)$_POST["size"], 'pool_type'=>$_POST["pg_type"], 'rule'=>$_POST["rule"]);
			$url = 'https://'.$this->sysVal->getAppValue('mgrip').':'.$this->sysVal->getAppValue('mgrport').'/request?wait=1';
			$login = $this->sysVal->getAppValue('username');
			$pass = $this->sysVal->getAppValue('password');
			if ($_POST["pg_type"] == 'erasure'){
				$jsondata = array('prefix'=>'osd pool create','pool'=>$_POST["name"],'pg_num'=>(int)$_POST["pg_num"],'size'=>(int)$_POST["size"], 'pool_type'=>$_POST["pg_type"], 'rule'=>$_POST["rule"], 'erasure_code_profile'=>$_POST["erasure_code_profile"]);
			} else if ($_POST["pg_type"] == 'replicated') {
				$jsondata = array('prefix'=>'osd pool create','pool'=>$_POST["name"],'pg_num'=>(int)$_POST["pg_num"],'size'=>(int)$_POST["size"], 'pool_type'=>$_POST["pg_type"], 'rule'=>$_POST["rule"]);
			}
			$jsondata = json_encode($jsondata);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/pool');
		} else if ($_POST["type"] == "genCRUSH"){
			if ($_POST["typeCRUSH"] == 'simple'){
				$jsondata = array('prefix'=>'osd crush rule create-simple','name'=>$_POST["name"],'root'=>$_POST["root"], 'type'=>$_POST["crush_type"], 'mode'=>$_POST["mode"]);
			} else if ($_POST["typeCRUSH"] == 'replicated'){
				$jsondata = array('prefix'=>'osd crush rule create-replicated','name'=>$_POST["name"],'root'=>$_POST["root"], 'type'=>$_POST["crush_type"], 'class'=>$_POST["class"]);
			} else if ($_POST["typeCRUSH"] == 'erasure'){
				$jsondata = array('prefix'=>'osd crush rule create-erasure','name'=>$_POST["name"], 'profile'=>$_POST["profile"]);
			}
			$jsondata = json_encode($jsondata);
			$url = 'https://'.$this->sysVal->getAppValue('mgrip').':'.$this->sysVal->getAppValue('mgrport').'/request?wait=1';
			$login = $this->sysVal->getAppValue('username');
			$pass = $this->sysVal->getAppValue('password');
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/crush');
		} else if ($_POST["type"] == "genECP"){
			$json = array('prefix'=>'osd erasure-code-profile set','name'=>$_POST["name"],'profile'=>array("k=".$_POST["k"],"m=".$_POST["m"],"crush-failure-domain=".$_POST["crush-failure-domain"],"crush-device-class=".$_POST["crush-device-class"]));
			$jsondata = json_encode($json);
			$url = 'https://'.$this->sysVal->getAppValue('mgrip').':'.$this->sysVal->getAppValue('mgrport').'/request?wait=1';
			$login = $this->sysVal->getAppValue('username');
			$pass = $this->sysVal->getAppValue('password');
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/crush');
		} else if ($_POST["type"] == "genOSD"){
			$json = array('prefix'=>'osd new','uuid'=>$_POST["uuid"],'id'=>$_POST["id"]);
			$jsondata = json_encode($json);
			$url = 'https://'.$this->sysVal->getAppValue('mgrip').':'.$this->sysVal->getAppValue('mgrport').'/request?wait=1';
			$login = $this->sysVal->getAppValue('username');
			$pass = $this->sysVal->getAppValue('password');
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/osd');
		} else if ($_POST["type"] == "genRBDImage"){
			$url = 'http://'.$this->sysVal->getAppValue('nrip').':'.$this->sysVal->getAppValue('nrport').'/'.$_POST["poolname"];
			$json = array('name'=>$_POST["imagename"],'size'=>$_POST["imagesize"],'feature'=>$_POST["imagefeature"]);
			$jsondata = json_encode($json);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/rbd');
			//return new JSONResponse(Array($json,$url));
		} else if ($_POST["type"] == "delECP"){
			$json = array('prefix'=>'osd erasure-code-profile rm','name'=>$_POST["name"]);
			$jsondata = json_encode($json);
			$url = 'https://'.$this->sysVal->getAppValue('mgrip').':'.$this->sysVal->getAppValue('mgrport').'/request?wait=1';
			$login = $this->sysVal->getAppValue('username');
			$pass = $this->sysVal->getAppValue('password');
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/crush');
		} else if ($_POST["type"] == "delCRUSH"){
			$json = array('prefix'=>'osd crush rule rm','name'=>$_POST["name"]);
			$jsondata = json_encode($json);
			$url = 'https://'.$this->sysVal->getAppValue('mgrip').':'.$this->sysVal->getAppValue('mgrport').'/request?wait=1';
			$login = $this->sysVal->getAppValue('username');
			$pass = $this->sysVal->getAppValue('password');
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/crush');
		} else if ($_POST["type"] == "delPool"){
			$url = 'https://'.$this->sysVal->getAppValue('mgrip').':'.$this->sysVal->getAppValue('mgrport').'/pool/'.$_POST["id"];
			$login = $this->sysVal->getAppValue('username');
			$pass = $this->sysVal->getAppValue('password');
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			$result = curl_exec($ch);
			$obj = json_decode($result);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/pool');
		} else if ($_POST["type"] == "delOSD"){
			$json = array('prefix'=>'osd rm','ids'=>array($_POST["id"]));
			$jsondata = json_encode($json);
			$url = 'https://'.$this->sysVal->getAppValue('mgrip').':'.$this->sysVal->getAppValue('mgrport').'/request?wait=1';
			$login = $this->sysVal->getAppValue('username');
			$pass = $this->sysVal->getAppValue('password');
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/osd');
		} else if ($_POST["type"] == "editPool"){
			$url = 'https://'.$this->sysVal->getAppValue('mgrip').':'.$this->sysVal->getAppValue('mgrport').'/pool/'.$_POST["id"];
			$login = $this->sysVal->getAppValue('username');
			$pass = $this->sysVal->getAppValue('password');
			$jsondata = array('pg_num'=>(int)$_POST["pg_num"],'pgp_num'=>(int)$_POST["pgp_num"],'size'=>(int)$_POST["size"]);
			$jsondata = json_encode($jsondata);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			$obj = json_decode($result);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/pool');
		} else if ($_POST["type"] == "editOSD"){
			$url = 'https://'.$this->sysVal->getAppValue('mgrip').':'.$this->sysVal->getAppValue('mgrport').'/osd/'.$_POST["id"];
			$login = $this->sysVal->getAppValue('username');
			$pass = $this->sysVal->getAppValue('password');
			$jsondata = array('id'=>$_POST["id"],'up'=>$_POST["up"]);
			$jsondata = json_encode($jsondata);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			$obj = json_decode($result);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/osd');
		}  else if ($_POST["type"] == "addCephFS"){
			$jsondata = array('fsname'=>$_POST["fsname"],'metapool'=>$_POST["metapool"],'datapool'=>$_POST["datapool"]);
			$jsondata = json_encode($jsondata);
			$url = 'http://'.$this->sysVal->getAppValue('nrip').':'.$this->sysVal->getAppValue('nrport').'/cephfs';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/cephfs');
		}  else if ($_POST["type"] == "addDataPool"){
			$jsondata = array('fsname'=>$_POST["fsname"]);
			$jsondata = json_encode($jsondata);
			$url = 'http://'.$this->sysVal->getAppValue('nrip').':'.$this->sysVal->getAppValue('nrport').'/cephfs/'.$_POST["datapool"];
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/cephfs');
		}  else if ($_POST["type"] == "delCephFS"){
			$jsondata = array('fsname'=>$_POST["fsname"]);
			$jsondata = json_encode($jsondata);
			$url = 'http://'.$this->sysVal->getAppValue('nrip').':'.$this->sysVal->getAppValue('nrport').'/cephfs';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/cephfs');
		}  else if ($_POST["type"] == "delDataPool"){
			$jsondata = array('fsname'=>$_POST["fsname"]);
			$jsondata = json_encode($jsondata);
			$url = 'http://'.$this->sysVal->getAppValue('nrip').':'.$this->sysVal->getAppValue('nrport').'/cephfs/'.$_POST["datapool"];
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/cephfs');
		} else if ($_POST["type"] == "delRBDImage"){
			$jsondata = array('name'=>$_POST["imagename"]);
			$jsondata = json_encode($jsondata);
			$url = 'http://'.$this->sysVal->getAppValue('nrip').':'.$this->sysVal->getAppValue('nrport').'/rbd/'.$_POST["poolname"];
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/rbd');
		} else if ($_POST["type"] == "addRGWUser"){
			$jsondata = array('uid'=>$_POST["uid"],'displayname'=>$_POST["displayname"],'usercaps'=>$_POST["usercaps"],'maxbuckets'=>(int)$_POST["maxbuckets"],'issuspended'=>(int)$_POST["issuspended"]);
			$jsondata = json_encode($jsondata);
			$url = 'http://'.$this->sysVal->getAppValue('nrip').':'.$this->sysVal->getAppValue('nrport').'/rgw';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/rgw');
		} else if ($_POST["type"] == "addS3Bucket"){
			$jsondata = array('name'=>$_POST["name"],'acl'=>$_POST["acl"]);
			$jsondata = json_encode($jsondata);
			$url = 'http://'.$this->sysVal->getAppValue('nrip').':'.$this->sysVal->getAppValue('nrport').'/s3';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/rgw');
		} else if ($_POST["type"] == "editRGWUser"){
			$jsondata = array('uid'=>$_POST["uid"],'displayname'=>$_POST["displayname"],'usercaps'=>$_POST["usercaps"],'maxbuckets'=>(int)$_POST["maxbuckets"],'issuspended'=>(int)$_POST["issuspended"]);
			$jsondata = json_encode($jsondata);
			$url = 'http://'.$this->sysVal->getAppValue('nrip').':'.$this->sysVal->getAppValue('nrport').'/rgw';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/rgw');
		} else if ($_POST["type"] == "delRGWUser"){
			$jsondata = array('uid'=>$_POST["uid"]);
			$jsondata = json_encode($jsondata);
			$url = 'http://'.$this->sysVal->getAppValue('nrip').':'.$this->sysVal->getAppValue('nrport').'/rgw';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/rgw');
		} else if ($_POST["type"] == "delS3Bucket"){
			$jsondata = array('name'=>$_POST["name"]);
			$jsondata = json_encode($jsondata);
			$url = 'http://'.$this->sysVal->getAppValue('nrip').':'.$this->sysVal->getAppValue('nrport').'/s3';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
			$result = curl_exec($ch);
			curl_close($ch);
			return new RedirectResponse('/index.php/apps/nextceph/rgw');
		} else {
			return new JSONResponse(array('info'=>'Error POST Request'));
		}
	}
}
