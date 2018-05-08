<?php
//Saml Connection for Github and any other saml idp
class OauthAction extends CAction
{


	//Github OAuth
	// const CLIENT_ID     = '5fd3686cf2b8d2b158e3';
	// const CLIENT_SECRET = '';
	// const REDIRECT_URI  = 'http://communecter.org/connect/co/oauth';
	// const AUTH_URI  = 'https://github.com/login/oauth/authorize';
	// const TOKEN_URI  = 'https://github.com/login/oauth/access_token';
	// const APIBASE_URI  = 'https://api.github.com/';

	//FirstLife
	// const CLIENT_ID     = 'FXSkkpux';
	// const CLIENT_SECRET = '';
	// const REDIRECT_URI  = 'http://communecter.org/connect/co/oauth';
	// const AUTH_URI  = 'https://secure.firstlife.org/oauth/login';
	// const TOKEN_URI  = 'https://secure.di.unito.it/token';
	// const APIBASE_URI  = 'https://api.github.com/';


	// Commuecter Rocket Chat Oauth
	// const CLIENT_ID = '4JZxkcThcKT5FHJnk';
	// const CLIENT_SECRET = '';
	// const REDIRECT_URI = 'http://communecter.org/connect/co/oauth';
	// const AUTH_URI = 'https://chat.communecter.org/oauth/authorize';
	// const TOKEN_URI = 'https://chat.communecter.org/oauth/token';
	// const APIBASE_URI  = 'https://chat.communecter.org/oauth/';

	//COSSO
	const CLIENT_ID = '4JZxkcThcKT5FHJnk';
	const CLIENT_SECRET = '';
	const REDIRECT_URI = 'http://communecter.org/connect/co/oauth';
	const AUTH_URI = 'https://sso.communecter.org/oauth/authorize';
	const TOKEN_URI = 'https://sso.communecter.org/oauth/token';
	const APIBASE_URI  = 'https://sso.communecter.org/oauth/';

	//SSO lescommuns.org
	// const CLIENT_ID = 'communecter.org';
	// const CLIENT_SECRET = '';
	// const REDIRECT_URI = 'http://communecter.org/connect/co/oauth';
	// const AUTH_URI = 'https://login.lescommuns.org:8443/auth/realms/master/protocol/openid-connect/auth';
	// const TOKEN_URI = 'https://login.lescommuns.org:8443/auth/realms/master/protocol/openid-connect/token';
	// const APIBASE_URI  = 'https://login.lescommuns.org:8443/auth';

	public function run()
    {

    	$_SESSION['Oauthype'] = get("type"); 

		$authorizeURL = self::AUTH_URI;
		$tokenURL = self::TOKEN_URI;
		$apiURLBase = self::APIBASE_URI;

		// Start the login process by sending the user to Github's authorization page
		if(get('action') == 'login') {
		  // Generate a random hash and store in the session for security
		  $_SESSION['state'] = hash('sha256', microtime(TRUE).rand().$_SERVER['REMOTE_ADDR']);
		  unset($_SESSION['access_token']);

		  $params = array(
		    'client_id' => self::CLIENT_ID,
		    'redirect_uri' => self::REDIRECT_URI,
		    'scope' => 'user',
		    'state' => $_SESSION['state']
		  );

		  // Redirect the user to Github's authorization page
		  header('Location: ' . $authorizeURL . '?' . http_build_query($params));
		  die();
		}

		// When Github redirects the user back here, there will be a "code" and "state" parameter in the query string
		if(get('code')) {
		  // Verify the state matches our stored state
		  if(!get('state') || $_SESSION['state'] != get('state')) {
		    header('Location: ' . $_SERVER['PHP_SELF']);
		    die();
		  }

		  // Exchange the auth code for a token
		  $token = apiRequest($tokenURL, array(
		    'client_id' 	=> self::CLIENT_ID,
		    'client_secret' => self::CLIENT_SECRET,
		    'grant_type' 	=> 'authorization_code',
		    'redirect_uri' 	=> 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'],
		    'state' 		=> $_SESSION['state'],
		    'code' 			=> get('code')
		  ));

		  $_SESSION['access_token'] = $token->access_token;

		  header('Location: ' . $_SERVER['PHP_SELF']);
		}
		echo 'access_token : '.session('access_token');
		if(session('access_token')) {

		echo '<h3>Logged In SSO ';


		if(self::APIBASE_URI == "https://api.github.com/"){
			$user = apiRequest($apiURLBase . 'user');
			echo 'GITHUB</h3> <img src="'.$user->avatar_url.'"/>';

		}
		else if(self::APIBASE_URI == "https://chat.communecter.org/oauth/"){
			echo "chat.communecter </h3>";
			$user = apiRequest($apiURLBase . 'UserInfo');
		}
		else if(self::APIBASE_URI == "https://sso.communecter.org/oauth/"){
			echo "sso.communecter </h3>";
			$user = apiRequest($apiURLBase . "userinfo");
			$userMap = array("email"     => @$user->email,
							 "name"      => @$user->name,
							 "username"  => @$user->username,
							 "image"	 => @$user->picture);
		}
		else {
			echo self::APIBASE_URI." </h3>";
			$user = apiRequest($apiURLBase . 'UserInfo');
		}


		if( @$user )  
			print_r($user);

			//check if user exists in CO 
			echo "<br/>check exists in CO";
			$coUser = PHDB::findOne( Person::COLLECTION, array("email"=>$userMap["email"]) );
			if( !$coUser && $userMap ){
				//Create User process
				echo "<br/>doesn't exists in CO, create";
				$coUser = Person::createPersonFromImportData($userMap);
				$res = Person::insert($coUser);
				echo "<br/>inserted in CO";
				$coUser = $res["person"];
			} else 
				echo "<br/>user exists in CO";
			//Login Process
			CO2Stat::incNbLoad("co2-login");
			echo "<br/>log through CO";
			Person::saveUserSessionData($coUser);
			echo "<br/>log in ".Yii::app()->session['userId'];

		} else {
		  echo '<h3>Not logged in</h3>';
		  echo '<p><a href="?action=login">Log In</a></p>';
		}
	}
}



function apiRequest($url, $post=FALSE, $headers=array()) {
	echo "<br/>apiRequest : ".$url."<br/>";
  $ch = curl_init($url);
  curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER ['HTTP_USER_AGENT']); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

  if($post)
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

  $headers[] = 'Accept: application/json';

  if(session('access_token'))
    $headers[] = 'Authorization: Bearer ' . session('access_token');
echo "<br/>";
	print_r($ch);
echo "<br/>";
	print_r($headers);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $response = curl_exec($ch);
echo "<br/><br/>";
//print_r($response);
	// foreach (json_decode($response,true) as $key => $value) {
	// 	echo "<br/><b>".$key."</b><br/> ";
	// 	print_r($value);
	// }
  return json_decode($response);
}

function get($key, $default=NULL) {
  return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}

function session($key, $default=NULL) {
  return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}