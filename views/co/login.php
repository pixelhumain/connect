<div class="pageContent">

	<div class="col-md-12 col-lg-12 col-sm-12 imageSection" 
		 style="margin-top: 30px; position:relative;">

		<div class="col-md-12 no-padding">
			
		<?php if(!isset(Yii::app()->session['userId'])) { ?>

			<div class="col-md-7 col-sm-7 text-center">
				<div id="homeImg">
					<img id="img-header" class="img-responsive" src="<?php echo Yii::app()->getModule( Yii::app()->params["module"]["parent"] )->assetsUrl; ?>/images/<?php echo Yii::app()->language ?>/1+1=3empty.jpg"/>
				</div>
			</div>

			<div class="col-md-4 col-sm-5 margin-top-25 padding-bottom-15 margin-right-50" 
				 style="border:1px solid #DDD; background-color: #F9F9F9; border-radius:4px;">
				<?php 	
					$this->renderPartial('register'); 
				  	$this->renderPartial('modalRegisterSuccess')
			  	?>
			</div>

		<?php } else { ?>

			<div class="col-md-12 text-center">
				<div id="homeImg">
					<img id="img-header" class="img-responsive" src="<?php echo Yii::app()->getModule( Yii::app()->params["module"]["parent"] )->assetsUrl; ?>/images/<?php echo Yii::app()->language ?>/1+1=3empty.jpg"/>
				</div>
			</div>

		<?php } ?>

		</div>
	</div>
</div>