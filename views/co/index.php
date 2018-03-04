<?php

//assets from ph base repo
$cssAnsScriptFilesTheme = array(
	// SHOWDOWN
	'/plugins/showdown/showdown.min.js',
	//MARKDOWN
	'/plugins/to-markdown/to-markdown.js'
);
HtmlHelper::registerCssAndScriptsFiles($cssAnsScriptFilesTheme, Yii::app()->request->baseUrl);

//gettting asstes from parent module repo
$cssAnsScriptFilesModule = array(
	'/js/dataHelpers.js',
	'/css/md.css',
);
HtmlHelper::registerCssAndScriptsFiles($cssAnsScriptFilesModule, Yii::app()->getModule( Yii::app()->params["module"]["parent"] )->getAssetsUrl() );
?>

<div class='container center'>

<h1 style="">
	<img height=50 src="<?php echo Yii::app()->getModule( Yii::app()->params["module"]["parent"] )->getAssetsUrl()?>/images/CO.png">
	<img height=50 src="<?php echo $this->module->assetsUrl?>/images/logo.png">
	Single Sign on Module
</h1>

<br/>

<?php if(!Yii::app()->session["userId"]){ ?>

<a href="/connect/co/login" class="btn btn-default">CO</a>
<a href="/connect/co/oauth?action=login" class="btn btn-default">Github</a>
<a href="/connect/co/fairkom" class="btn btn-default">FairCoop</a>
<a href="/connect/co/firstlife" class="btn btn-default">FirstLife</a>

<?php } else {?>

Logged in
<br/> 
<?php 
echo Yii::app()->session["userId"]."<br/>";
echo Yii::app()->session["user"]["name"]."<br/>";
echo Yii::app()->session["user"]["email"]."<br/>";
 ?>

<?php } ?>

</div>