<?php 

defined('C5_EXECUTE') or die(_("Access Denied."));

class EasyNewsPackage extends Package {

	protected $pkgHandle = 'easy_news';
	protected $appVersionRequired = '5.4.0';
	protected $pkgVersion = '1.1';
	
	function __construct(){
		Loader::library('controller',$this->pkgHandle); //Used by controllers
		Loader::library('dashboardcontroller',$this->pkgHandle); //Used by controllers
	}
	
	public function getPackageDescription() {
		return t('Add multiple news area to your site.');
	}
	
	public function getPackageName() {
		return t('Easy News');
	}
	
	public function install() {
		$pkg = parent::install();
		Loader::model('single_page');
		Loader::model('attribute/categories/collection');
		
		// install attributes
		$cab1 = CollectionAttributeKey::add('BOOLEAN',array('akHandle' => 'easynews_section', 'akName' => t('NEWS Section'), 'akIsSearchable' => true), $pkg);

		//install pages
		$def = SinglePage::add('/dashboard/easy_news', $pkg);
		$def->update(array('cName'=>'Easy News', 'cDescription'=>t('Manage site news.')));
		$def = SinglePage::add('/dashboard/easy_news/help', $pkg);
		$def->update(array('cName'=>'Easy News Help', 'cDescription'=>t('Easy News help.')));
		
		//install block
		BlockType::installBlockTypeFromPackage('easynews_list', $pkg); 

	}
}