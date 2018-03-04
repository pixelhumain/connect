<?php
class TAction extends CAction
{
	public function run()
    {
    	$salt = uniqid(mt_rand(), true);
    	echo "<h1>Module Connect Action : ".get_class($this)."</h1>".$salt;
	}
}
