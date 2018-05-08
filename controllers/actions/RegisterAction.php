<?php
class RegisterAction extends CAction
{
	public function run()
    {
    	$this->getController()->layout = "//layouts/empty";
    	$this->getController()->render("login");
	}
}
