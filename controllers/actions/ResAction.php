<?php
class ResAction extends CAction
{
	public function run($state=null)
    {
    	echo "<h1>Connect Res ".@$state."</h1>";
	}
}