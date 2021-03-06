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


<h1 style="">
	<img height=50 src="<?php echo Yii::app()->getModule( Yii::app()->params["module"]["parent"] )->getAssetsUrl()?>/images/CO.png">
	<img height=50 src="<?php echo $this->module->assetsUrl?>/images/logo.png">
	Single Sign on Module
</h1>

<div id="doc"></div>

<script type="text/javascript">

$(document).ready(function() { 
	getAjax('', baseUrl+'/'+moduleId+'/default/doc',
		function(data){ 
			descHtml = dataHelper.markdownToHtml(data); 
			$('#doc').html(descHtml);
		},"html");
});

</script>
