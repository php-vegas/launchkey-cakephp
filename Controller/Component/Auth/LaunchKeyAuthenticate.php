<?php
App::uses('FormAuthenticate', 'Controller/Component/Auth');

/**
 *
 */
class LaunchKeyAuthenticate extends FormAuthenticate {

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
		'fields' => array(
			'username' => 'username',
			'password' => 'password'
		),
		'userModel' => 'User',
		'scope' => array(),
		'recursive' => 0,
		'contain' => null,
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

		parent::__construct($collection, $settings);
	}

/**
 * Checks the fields to ensure they are supplied.
 *
 * @param CakeRequest $request The request that contains login information.
 * @param string $model The model used for login verification.
 * @param array $fields The fields to be checked.
 * @return boolean False if the fields have not been supplied. True if they exist.
 */
	protected function _checkFields(CakeRequest $request, $model, $fields) {
		if (empty($request->data[$model])) {
			return false;
		}
		foreach (array($fields['username']) as $field) {
			$value = $request->data($model . '.' . $field);
			if (empty($value) || !is_string($value)) {
				return false;
			}
		}
		return true;
	}

	/**
	 * authenticate
	 *
	 * @param  CakeRequest  $request  [description]
	 * @param  CakeResponse $response [description]
	 * @return [type]                 [description]
	 */
	public function authenticate(CakeRequest $request, CakeResponse $response) {
		$userModel = $this->settings['userModel'];
		list(, $model) = pluginSplit($userModel);

		$fields = $this->settings['fields'];
		if (!$this->_checkFields($request, $model, $fields)) {
			return false;
		}
		$user = $this->_findUser(
			array(
				$model . '.' . $fields['username'] => $request->data[$model][$fields['username']],
			)
		);
		if (!$user) {
			return false;
		}

		//Generate Auth Request with LuanchKey
		$authRequestId = $this->_LaunchKey->authorize($user[$fields['username']]);

		$stateOfResponse = FALSE;
		while (!$stateOfResponse) {
			$response = $this->_LaunchKey->poll_request($authRequestId);
			if(array_key_exists('auth', $response)) {
				$stateOfResponse = TRUE;
				if($this->_LaunchKey->is_authorized($response['auth'], $authRequestId)){
					return $user;
				} else {
					return FALSE;
				}
			}
		}

		return false;
	}

	public function logout($user) {

	}
}