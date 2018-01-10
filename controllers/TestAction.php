<?php
class TestAction extends CAction
{
	public function run()
    {
    	require_once '../../pixelhumain/ph/vendor/autoload.php';
		$api = new MangoPay\MangoPayApi();

		// configuration
		$api->Config->ClientId = Yii::app()->params["mangoPay"]["ClientId"];
		$api->Config->ClientPassword = Yii::app()->params["mangoPay"]["ClientPassword"];
		$api->Config->TemporaryFolder = Yii::app()->params["mangoPay"]["TemporaryFolder"];

		use Jumbojett\OpenIDConnectClient;

		$oidc = new OpenIDConnectClient('https://id.provider.com',
		                                'ClientIDHere',
		                                'ClientSecretHere');
		$oidc->setCertPath('/path/to/my.cert');
		$oidc->authenticate();
		$name = $oidc->requestUserInfo('given_name');

		// call some API methods...
		echo "inside Mango ";
	}
