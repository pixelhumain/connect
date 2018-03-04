<?php
class BadgeAction extends CAction
{
	public function run($id,$type)
    {
    	echo $this->getController()->renderPartial( "badge", array("id"=>$id,"type"=>$type) );
	}
}
