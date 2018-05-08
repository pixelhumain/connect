<?php
//Open Id connection with FairKom
class FairkomAction extends CAction
{
	public function run()
    {
    	//Yii::import('connect.vendor.PHP-OAuth2.src.OAuth2.Client.php');
    	Yii::import('vendor.oidc-client-php.*',true);
		//for the well known 
		// https://id.fairkom.net/auth/realms/fairlogin/.well-known/openid-configuration
		$oidc = new OpenIDConnectClient('https://id.fairkom.net/auth/realms/fairlogin',
		                                'communecter',
		                                '');

		$oidc->providerConfigParam(array(
			"issuer" => "https://id.fairkom.net/auth/realms/fairlogin",
			"authorization_endpoint" => "https://id.fairkom.net/auth/realms/fairlogin/protocol/openid-connect/auth",
			"token_endpoint" => "https://id.fairkom.net/auth/realms/fairlogin/protocol/openid-connect/token",
			"token_introspection_endpoint" => "https://id.fairkom.net/auth/realms/fairlogin/protocol/openid-connect/token/introspect",
			"userinfo_endpoint" => "https://id.fairkom.net/auth/realms/fairlogin/protocol/openid-connect/userinfo",
			"end_session_endpoint" => "https://id.fairkom.net/auth/realms/fairlogin/protocol/openid-connect/logout",
			"jwks_uri" => "https://id.fairkom.net/auth/realms/fairlogin/protocol/openid-connect/certs",
			"check_session_iframe" => "https://id.fairkom.net/auth/realms/fairlogin/protocol/openid-connect/login-status-iframe.html",
			"grant_types_supported" => [
				"authorization_code",
				"implicit",
				"refresh_token",
				"password",
				"client_credentials"
			],
			"response_types_supported" => [
				"code",
				"none",
				"id_token",
				"token",
				"id_token token",
				"code id_token",
				"code token",
				"code id_token token"
			],
			"subject_types_supported" => [
				"public",
				"pairwise"
			],
			"id_token_signing_alg_values_supported" => ["RS256"],
			"userinfo_signing_alg_values_supported" => ["RS256"],
			"request_object_signing_alg_values_supported" => ["none","RS256"],
			"response_modes_supported" => [
				"query",
				"fragment",
				"form_post"
			],
			"registration_endpoint" => "https://id.fairkom.net/auth/realms/fairlogin/clients-registrations/openid-connect",
			"token_endpoint_auth_methods_supported" => [
				"private_key_jwt",
				"client_secret_basic",
				"client_secret_post"
			],
			"token_endpoint_auth_signing_alg_values_supported" => ["RS256"],
			"claims_supported" => [
				"sub",
				"iss",
				"auth_time",
				"name",
				"given_name",
				"family_name",
				"preferred_username",
				"email"
			],
			"claim_types_supported" => [ "normal"],
			"claims_parameter_supported" => false,
			"scopes_supported" => [
			"openid",
			"offline_access"
			],
			"request_parameter_supported" => true,
			"request_uri_parameter_supported" => true
			));
		echo @$_SESSION['openid_connect_state']."<br/>";
		$oidc->addScope("openid email profile address");
		$oidc->authenticate();

		$email = $oidc->requestUserInfo('email');
		//$given_name = $oidc->requestUserInfo('given_name');*/

		echo "<a href='https://id.fairkom.net/auth/realms/fairlogin/protocol/openid-connect/logout'>Logout</a>";

	}

// Identity Path: /realms/fairlogin/protocol/openid-connect/userinfo
      
/*      
const CLIENT_ID     = 'communecter';
	const CLIENT_SECRET = '9eb4f0c5-82f6-4225-8042-8ca6d355a35b';

	const REDIRECT_URI           = 'http://communecter.org/connect/co/fairkom';

	public function run()
    {
    	$authorizeURL 	= 'https://id.fairkom.net/auth/realms/fairlogin/protocol/openid-connect/auth';
		$tokenURL 		= 'https://id.fairkom.net/auth//realms/fairlogin/protocol/openid-connect/token';
		$apiURLBase 	= 'https://id.fairkom.net/auth';

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
		    'client_id' => self::CLIENT_ID,
		    'client_secret' => self::CLIENT_SECRET,
		    'redirect_uri' => 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'],
		    'state' => $_SESSION['state'],
		    'code' => get('code')
		  ));
		  $_SESSION['access_token'] = $token->access_token;

		  header('Location: ' . $_SERVER['PHP_SELF']);
		}
		echo session('access_token');
		if(session('access_token')) {
		  $user = apiRequest($apiURLBase . 'user');

		  echo '<h3>Logged In</h3>';
		  echo '<h4>' . $user->name . '</h4>';
		  echo '<pre>';
		  print_r ($user) ;
		  echo '</pre>';

		} else {
		  echo '<h3>Not logged in</h3>';
		  echo '<p><a href="?action=login">Log In</a></p>';
		}
	}
}



function apiRequest($url, $post=FALSE, $headers=array()) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

  if($post)
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

  $headers[] = 'Accept: application/json';

  if(session('access_token'))
    $headers[] = 'Authorization: Bearer ' . session('access_token');

  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $response = curl_exec($ch);
  return json_decode($response);
}

function get($key, $default=NULL) {
  return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}

function session($key, $default=NULL) {
  return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;*/
}
