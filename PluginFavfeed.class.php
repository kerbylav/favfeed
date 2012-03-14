<?php
/*
 *
 * Project Name : Favourites feed
 * Copyright (C) 2011 Alexei Lukin. All rights reserved.
 * License: Free. GPL 2.0
 *
 */


if (!class_exists('Plugin')) {
	die('Hacking attemp!');
}

define('Favfeed_VERSION', '1.0.2');

class PluginFavfeed extends Plugin
{
	protected $sTemplatesUrl = "";

	protected $aInherits=array(
		'module'  =>array('ModuleTopic'=>'_ModuleTopic')    
	);
	
	public function Activate()
	{
		return true;
	}


	public function Deactivate()
	{
		return true;
	}

	public function Init()
	{
		$sTemplatesUrl = Plugin::GetTemplatePath('PluginFavfeed');
	}

}

?>
