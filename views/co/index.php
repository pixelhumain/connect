<?php 
$cssJS = array(
	'/plugins/jquery-validation/dist/jquery.validate.min.js',  
	'/plugins/ladda-bootstrap/dist/spin.min.js' , 
    '/plugins/ladda-bootstrap/dist/ladda.min.js' , 
    '/plugins/ladda-bootstrap/dist/ladda.min.css',
    '/plugins/ladda-bootstrap/dist/ladda-themeless.min.css',
);

HtmlHelper::registerCssAndScriptsFiles($cssJS, Yii::app()->request->baseUrl);

$cssJS = array(
'/js/dataHelpers.js',
);
HtmlHelper::registerCssAndScriptsFiles($cssJS, Yii::app()->getModule( Yii::app()->params["module"]["parent"] )->getAssetsUrl() );
 ?>

<div class='container '>

	<div class="row text-center">
		<h1 class="col-sm-12">
			<img height=50 src="<?php echo Yii::app()->getModule( Yii::app()->params["module"]["parent"] )->getAssetsUrl()?>/images/CO.png">
			<img height=50 src="<?php echo $this->module->assetsUrl?>/images/logo.png">
			Single Sign on Module
		</h1>
	</div>

	<br/>

<?php 
function base64url_decode($base64url) {
    return base64_decode(b64url2b64($base64url));
}

function b64url2b64($base64url) {
    // "Shouldn't" be necessary, but why not
    $padding = strlen($base64url) % 4;
    if ($padding > 0) {
	$base64url .= str_repeat("=", 4 - $padding);
    }
    return strtr($base64url, '-_', '+/');
}

if(@$_SESSION['access_token']){


	echo "Logged In with token : ".$_SESSION['access_token'];
	$parts = explode(".", $_SESSION['access_token']);
    echo "<br/>".json_decode(base64url_decode($parts[0]));
    // echo "<br/>".json_decode(base64url_decode($parts[1]));
    // echo "<br/>".json_decode(base64url_decode($parts[2]));
} 
else if(!Yii::app()->session["userId"]){ ?>



	<div class="row">
		<div class="col-sm-offset-2 col-sm-2">
		<a href="/connect/co/oauth?action=login&type=co" class="btn btn-default">CO</a>
		</div>
		<div class="col-sm-2">
		<a href="/connect/co/oauth?action=login" class="btn btn-default">Github</a>
		</div>
		<div class="col-sm-2">
		<a href="/connect/co/fairkom" class="btn btn-default">FairCoop</a>
		</div>
		<div class="col-sm-2">
		<a href="/connect/co/oauth?action=login&type=firstlife" class="btn btn-default">FirstLife</a>
		</div>
	</div>



<?php } else {?>



	Logged in
	<br/> 
	<?php 
	echo Yii::app()->session["userId"]."<br/>";
	echo Yii::app()->session["user"]["name"]."<br/>";
	echo Yii::app()->session["userEmail"]."<br/>";
	 ?>
	<a href="/co2/person/logout">Logout</a>



<?php } ?>

</div>