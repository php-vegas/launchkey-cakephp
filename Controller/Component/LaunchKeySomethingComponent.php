<?php
//App::uses('BaseAuthenticate', 'Controller/Component/Auth');


class LaunchKeySomethingComponent extends Component {


	/**
	 * [$settings description]
	 * @var array
	 */
	public $settings = array(
		'appId' => NULL,
		'secretKey' => NULL,
		'privateKey' => NULL,
		'polling' => FALSE,
		'domain' => NULL,
	);

	/**
	 * [$_LaunchKey description]
	 * @var [type]
	 */
	private $_LaunchKey;

	/**
	 * [$_privateKeyData description]
	 * @var [type]
	 */
	private $_privateKeyData;

	/**
	 * [__construct description]
	 * @param ComponentCollection $collection [description]
	 * @param array               $settings   [description]
	 */
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->settings = array_merge($this->settings, $settings);

		App::uses('File', 'Utility');
		$privateKeyFileInstance = new File($this->settings['privateKey']);
		$this->_privateKeyData = $privateKeyFileInstance->read();

		App::import('Vendor', 'LaunchKey.autoload.php', array('file' => 'autoload.php'));
		App::import('Vendor', 'LaunchKey.LaunchKey', array('file' => 'launchkey/launchkey/lib/LaunchKey/LaunchKey.php'));

		$this->_LaunchKey = new LaunchKey($this->settings['appId'], $this->settings['secretKey'], $this->_privateKeyData, $this->settings['domain']);
	}

	public function ping() {
		return $this->_LaunchKey->ping();
	}

	public function auth($userName) {
		return $this->_LaunchKey->authorize($userName);
	}

	public function poll_request($authRequestId) {
		return $this->_LaunchKey->poll_request($authRequestId);
	}

	public function is_authorized($package) {
		return $this->_LaunchKey->is_authorized($package);
	}

  public function authenticate(CakeRequest $request, CakeResponse $response) {
		// Do things for LaunchKey here.
		//
		// Return an array of user if they could authenticate the user,
		// return false if not
  }

  public function logout($user) {
  }
}
?>