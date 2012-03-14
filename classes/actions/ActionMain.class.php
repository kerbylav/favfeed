<?php
/*
 *
 * Project Name : Favourites feed
 * Copyright (C) 2011 Alexei Lukin. All rights reserved.
 * License: Free. GPL 2.0
 *
 */


class PluginFavfeed_ActionMain extends ActionPlugin {
	/**
	 * Текущий юзер
	 *
	 * @var ModuleUser_EntityUser
	 */
	protected $oUserCurrent=null;

	/**
	 * Инициализация
	 *
	 * @var bool
	 */
	protected $bIsAdmin=false;

	public $sMenuHeadItemSelect='favfeed';
	public $sMenuItemSelect='favfeed';
	public $sMenuSubItemSelect='view';

	/**
	 * Инициализация
	 *
	 * @return null
	 */
	public function Init() {
		/**
		 * Проверяем авторизован ли юзер
		 */
		if ($this->User_IsAuthorization()) {
			$this->oUserCurrent=$this->User_GetUserCurrent();
			$this->bIsAdmin=$this->oUserCurrent->isAdministrator();
		}

		$this->Viewer_AppendStyle(Plugin::GetTemplateWebPath('PluginFavfeed').'css/style.css');
		$this->SetDefaultEvent('view');
	}

	protected function RegisterEvent() {
		$this->AddEvent('view','EventView');
		$this->AddEventPreg('/^(page(\d+))?$/i','EventView');
		$this->AddEvent('ajax','EventAjax');
	}

	public function EventView() {
		if (!$this->User_IsAuthorization()) return Router::Action('error','404');
		/**
		 * Передан ли номер страницы
		 */
		$iPage=Router::GetActionEvent()=='view'? 1 : substr(Router::GetActionEvent(),4);
		/**
		 * Получаем список избранных топиков
		 */
		if ($aTopics=$this->PluginFavfeed_ModuleFavfeed_GetAliveFavorites($this->oUserCurrent->getId(),$iPage,Config::Get('block.stream.row')))
		{
			$aCount=$this->PluginFavfeed_ModuleFavfeed_GetAliveFavoritesCount($this->oUserCurrent->getId());
			$aPaging=$this->Viewer_MakePaging($aCount['count'],$iPage,Config::Get('block.stream.row'),4,Router::GetPath('favfeed'));
			/**
			 * Загружаем переменные в шаблон
			 */
			$this->Viewer_Assign('iFavFeedTotal',$aCount['total']);
			$this->Viewer_Assign('iFavFeedCount',$aCount['count']);
			$this->Viewer_Assign('aPaging',$aPaging);
			$this->Viewer_Assign('aTopics',$aTopics);
		}
		$this->SetTemplateAction('view');
	}

	public function EventAjax() {
		$this->Viewer_SetResponseAjax();
		if (!$this->User_IsAuthorization()) return;
		
		if ($oTopics=$this->PluginFavfeed_ModuleFavfeed_GetAliveFavorites($this->oUserCurrent->getId(),1,Config::Get('block.stream.row')))
		{
			$oViewer=$this->Viewer_GetLocalViewer();
			$oViewer->Assign('oTopics',$oTopics);
			$sTextResult=$oViewer->Fetch("block.stream_favfeed.tpl");
			$this->Viewer_AssignAjax('sText',$sTextResult);
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('block_stream_topics_no'),$this->Lang_Get('attention'));
		}
	}

	public function EventShutdown() {
		$this->Viewer_Assign('sMenuHeadItemSelect', $this->sMenuHeadItemSelect);
		$this->Viewer_Assign('sMenuItemSelect', $this->sMenuItemSelect);
		$this->Viewer_Assign('sMenuSubItemSelect', $this->sMenuSubItemSelect);
		$this->Viewer_Assign('bIsAdmin', $this->bIsAdmin);
		$this->Viewer_Assign('oUserCurrent', $this->oUserCurrent);
		$this->Viewer_Assign('oUserProfile', $this->oUserCurrent);
		$this->Viewer_Assign('sTemplatePath', Plugin::GetTemplatePath('PluginFavfeed'));
	}
}



?>