<?php


/**
 * Facebook Public Status Feed class
 * Essentially a wrapper to the Facebook Platform
 * to read public streams using Offline Access extended permissions
 */
class PublicFacebookFeed {
	public $client;
	public $userID;
	public $db;

	/**
	 * 5 is 5 items, i.e. an album with 10 pictures counts as 10 items. Possibly comments too. Basically, turn this off.
	 */
	public $limit = null;

	public function __construct($appapikey, $appsecret, $adoConnectString) {
		$this->client = new Facebook($appapikey, $appsecret);

		$this->db = NewADOConnection($adoConnectString);
		ADOdb_Active_Record :: SetDatabaseAdapter($this->db);
	}

	/**
	 * Get user's public items
	 */
	function getItems() {

		/*
		 * As of 2010:
		 * 
		 * Check user has offline access & stream read permissions
		 * 
		 * Retrieve session key (used to be called 'infinite session key').
		 * This is stored locally to avoid using the Facebook UI on requests in RSS scripts, etc.
		 * 
		 * Read content from Streams API. The (deprecated?) Status API doesn't contain the privacy information
		 * 
		 * Related info/URLs:
		 * http://wiki.developers.facebook.com/index.php/Extended_permissions
		 * http://wiki.developers.facebook.com/index.php/Status.get
		 * 
		 * 
		 */

		$perms = 'offline_access, read_stream';
		if (!$this->checkPermissions($perms)) {
			$this->halt("\$userID $this->userID doesn't have '$perms' permissions");
		}

		$this->getLocalSessionKey();
		if (!$this->client->api_client->session_key) {
			$this->halt("\$userID $this->userID doesn't have a session key");
		}

		//the actual info get is straightforward
		//working through the documentation/API mudball took over 10 hours...

		$query = "SELECT post_id, created_time, source_id,  message, attachment, target_id, filter_key, privacy, permalink FROM stream" .
		" WHERE source_id = $this->userID and privacy.value = 'EVERYONE'";
		//notice we could also get friend's activities
		//When you add a friend, they authorize you to do whatever you like with their activity
		if ($this->limit) {
			$query .= " limit $this->limit";
		}
		//$query .= " AND created_time < <oldest_time>";
		$statuses = $this->client->api_client->fql_query($query);
		return $statuses;
	}

	/**
	* get UserID, from GET 
	*/
	function getUserID() {

		//$_GET['uid']='802410391'; //http://www.facebook.com/john.field
		if (isset ($_GET['uid'])) {
			$user_id = $_GET['uid'];
		} else {
			//$this->halt("User ID not specified (e.g. ?uid=802410391)");
		}
		$this->userID = $user_id;
	}

	/**
	 * get locally stored session key
	 */
	function getLocalSessionKey() {
		$this->client->api_client->session_key = $this->db->getOne("select session_key from offline_access where uid=?", $this->userID);
		//$this->dump($this->client->api_client->session_key, "\$sessionKey for \$userID $userID");
		return $this->client->api_client->session_key;
	}

	/**
	 * Store session key locally. As this requires user to login, it renders the Facebook UI, so is unsuitable for external use e.g. creating RSS feeds
	 */
	function setLocalSessionKey() {
		$this->userID = $this->client->require_login($required_permissions = 'offline_access,read_stream');
		if ($this->userID) {
			$this->dump("\$userID of $this->userID found");
		} else {
			$this->halt("no \$userID found - permission not given?");
		}

		//now, get the session key, store,  & re-use it!!!

		/*
		CREATE TABLE IF NOT EXISTS `offline_access` (
		  `uid` varchar(250) NOT NULL default '',
		  `session_key` varchar(250) NOT NULL default '',
		  `create_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
		  PRIMARY KEY  (`uid`)
		);
		*/
		$this->db->execute("replace into offline_access set uid=?, session_key=?", array (
			$this->userID,
			$this->client->api_client->session_key
		));

	}

	/**
	 * Check user has set of permissions
	 * @param $str e.g. "offline_access, read_stream"
	 * 
	 */
	public function checkPermissions($str) {
		//has user given us extended permissions?
		$query = "SELECT uid, $str FROM permissions " .
		" WHERE uid = $this->userID";

		$foundPerms = $this->client->api_client->fql_query($query);
		//$this->dump($foundPerms, $query);

		foreach (explode(',', $str) as $perm) {
			$perm = trim($perm);
			if (!isset ($foundPerms[0][$perm])) {
				return false;
			}
			if ($foundPerms[0][$perm] != '1') {
				return false;
			}
		}
		return true;
	}

	/**
	 * Helpful dump
	 */
	function dump($obj, $msg = null, $echo = true) {
		$return = null;
		if (!$msg) {
			//autogenerate a heading
			$type = ucfirst(gettype($obj));
			if (is_object($obj)) {
				$type = get_class($obj) . ' Object';
			}
			$trace = debug_backtrace();
			$msg = $type . " (called {$trace[0]['file']}, line {$trace[0]['line']})";
		}
		$id = rand(0, 10000);

		$return .= "<hr/>$msg";
		$return .= '<pre>';
		if (is_string($obj)) {
			$return .= $obj;
		} else {
			$return .= var_export($obj, true);

		}
		$return .= '</pre>';
		$return .= '<br/>';
		if ($echo) {
			echo $return;
		} else {
			return $return;
		}
	}

	/**
	 * Helpful die
	*/
	function halt($msg) {
		die($msg);
	}

}