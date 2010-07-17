<?php
defined("C5_EXECUTE") or die(_("Access Denied."));
class EasyNewsController extends Controller
{
	const rssPagePath= 'tools/packages/easy_news/rss';
	const pkgHandle = 'easy_news';
	
    public function on_start()
    {
    }
	public static function getPackageUrl(){
		$pg = new Package();
		$pg = $pg->getByHandle(EasyNewsController::pkgHandle);
		$ci = Loader::helper('concrete/urls');
		$packageURL = $ci->getPackageURL($pg);
		return $packageURL;
	}
	public static function getRssPagePath(){
		return EasyNewsController::site('/'.EasyNewsController::rssPagePath);
	}
	public static function site($url){
		return BASE_URL.DIR_REL.'/index.php'.$url;
	}
}
