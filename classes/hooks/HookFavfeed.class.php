<?php
/*
 *
 * Project Name : Favourites feed
 * Copyright (C) 2011 Alexei Lukin. All rights reserved.
 * License: Free. GPL 2.0
 *
 */


/**
 * Регистрация хуков
 *
 */
class PluginFavfeed_HookFavfeed extends Hook {

	public function RegisterHook() {
		$this->AddHook('init_action', 'InitAction', __CLASS__);
		$this->AddHook('template_menu_profile', 'MenuSettingsTpl', __CLASS__);
	}

	public function InitAction() {
		if (Router::GetAction()=='ajax' and Router::GetActionEvent()=='favfeed') {
			Router::Action('favfeed','ajax');
		}
	}

	public function MenuSettingsTpl() {
		if ($this->User_IsAuthorization()) {
			$oUserCurrent=$this->User_GetUserCurrent();
			$aCount=$this->PluginFavfeed_ModuleFavfeed_GetAliveFavoritesCount($oUserCurrent->getId());
			$this->Viewer_Assign('iFavFeedTotal',$aCount['total']);
			$this->Viewer_Assign('iFavFeedCount',$aCount['count']);
			return $this->Viewer_Fetch(Plugin::GetTemplatePath('favfeed').'menu.profile.item.tpl');
		}

	}
}
?>