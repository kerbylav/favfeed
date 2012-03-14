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
class PluginFavfeed_ModuleTopic extends PluginFavfeed_Inherit_ModuleTopic {
	public function AddFavouriteTopic(ModuleFavourite_EntityFavourite $oFavouriteTopic) {
		$res=parent::AddFavouriteTopic($oFavouriteTopic);
		if ($res)
		{
			$oTopicRead=$this->Topic_GetTopicRead($oFavouriteTopic->getTargetId(),$oFavouriteTopic->getUserId());
			if (!$oTopicRead)
			{
				$oTopicRead=Engine::GetEntity('Topic_TopicRead');
				$oTopicRead->setTopicId($oFavouriteTopic->getTargetId());
				$oTopicRead->setUserId($oFavouriteTopic->getUserId());
				$oTopicRead->setCommentCountLast(0);
				$oTopicRead->setCommentIdLast(0);
			}
			$oTopicRead->setDateRead(date("Y-m-d H:i:s"));
			$this->Topic_SetTopicRead($oTopicRead);
		}

		return $res;
	}
}
?>