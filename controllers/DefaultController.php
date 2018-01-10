<?php
/**
 * DefaultController.php
 *
 * @author: Tibor Katelbach <tibor@pixelhumain.com>
 * Date: 14/03/2014
 */
class DefaultController extends CommunecterController {

  protected function beforeAction($action)
	{
    //parent::initPage();
	  return parent::beforeAction($action);
	}

	public function actionIndex() 
	{
    	if(Yii::app()->request->isAjaxRequest)
        echo $controller->renderPartial("index");
      else
      {
        $this->layout = "//layouts/empty";
        $this->render("index");
      }
  }

  public function actionDoc() 
  {
      echo file_get_contents('../../modules/'.$this->module->id.'/README.md');
  }

}
