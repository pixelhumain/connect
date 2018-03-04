<?php
class LoginAction extends CAction
{
	public function run()
    {
    	$this->getController()->render("login");
	}
}
