<?php 

defined('C5_EXECUTE') or die(_("Access Denied."));

class EasyNewsPackage extends Package {

	protected $pkgHandle = 'easy_news';
	protected $appVersionRequired = '5.4.0';
	protected $pkgVersion = '1.0';
	
	public function getPackageDescription() {
		return t('Simple News Addon.');
	}
	
	public function getPackageName() {
		return t('Easy News');
	}
	
	public function install() {
		$pkg = parent::install();
		Loader::model('single_page');
		Loader::model('attribute/categories/collection');
		
		// install attributes
		$cab1 = CollectionAttributeKey::add('BOOLEAN',array('akHandle' => 'faq_section', 'akName' => t('FAQ Section'), 'akIsSearchable' => true), $pkg);
		$cab2 = CollectionAttributeKey::add('SELECT',array('akHandle' => 'faq_tags', 'akName' => t('FAQ Tags'), 'akSelectAllowMultipleValues' => true, 'akSelectAllowOtherValues' => true, 'akIsSearchable' => true), $pkg);

		$def = SinglePage::add('/dashboard/example_faq', $pkg);
		$def->update(array('cName'=>'FAQ Entries', 'cDescription'=>'Frequently asked questions.'));
	}
}