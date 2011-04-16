<?php
defined("C5_EXECUTE") or die(_("Access Denied."));

class DashboardEasyNewsHelpController extends EasyNewsDashboardController
{
    public function on_start() {
		$subnav = array(
			array(View::url('/dashboard/easy_news'),t('Easy News Manage')), 
			array(View::url('/dashboard/easy_news/help'),'Help', true)
		);
		$this->set('subnav', $subnav);
	}
}
