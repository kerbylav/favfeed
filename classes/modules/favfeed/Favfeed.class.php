<?php
/*
 * 
 * Project Name : Favourites feed
 * Copyright (C) 2011 Alexei Lukin. All rights reserved.
 * License: Free. GPL 2.0
 *
 */


/**
 * Модуль
 *
 */
class PluginFavfeed_ModuleFavfeed extends Module {
	public function Init() {
	}

	public function GetAliveFavoritesCount($iUId)
	{
		$aResult=$this->Topic_GetTopicsFavouriteByUserId($iUId,1,65535);
		$aTemp=$aResult['collection'];
		$count=0;
		$total=0;
		foreach ($aTemp as $oTopic)
		{
			$nc=$oTopic->getCountCommentNew();
			if ($nc>0) 
			{
				$count++;
				$total+=$nc;
			}
		}
		
		return array('count'=>$count,'total'=>$total);
	}

	public function GetAliveFavorites($iUId,$iPage=1,$iPerPage=20)
	{
		/**
		 * Получаем список избранных топиков
		 */
		$count=0;
		$ip=0;
		$curPage=1;
		$pp=Config::Get('module.topic.per_page');
		$aTopics=array();
		$doBreak=false;
		do
		{
			$aResult=$this->Topic_GetTopicsFavouriteByUserId($iUId,++$ip,$pp);
			$aTemp=$aResult['collection'];
			if (count($aTemp)==0) break;
			foreach ($aTemp as $oTopic)
			{
				if ($oTopic->getCountCommentNew()>0)
				{
					$aTopics[$oTopic->getId()]=$oTopic;
				}
					
				if (count($aTopics)==$iPerPage)
				{
					if ($curPage==$iPage) break;
					$curPage++;
					$aTopics=array();
				}
			}
			if (count($aTopics)==$iPerPage)
			{
				if ($curPage==$iPage) break;
				$curPage++;
				$aTopics=array();
			}
		}
		while (!$doBreak);
		if ($curPage!=$iPage) $aTopics=array();

		return $aTopics;
	}

}
?>