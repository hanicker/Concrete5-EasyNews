<?php
defined('C5_EXECUTE') or die(_("Access Denied.")); 

$pkgHandle = 'easy_news';

Loader::library('controller',$pkgHandle);

//Get info about category
$cat=(int)$_GET['c'];

Loader::model('page_list');

Loader::model("collection_types");
$ct = Collection::getByID($cat);
//$a=$ct->getAttribute('easynews_section');
//if(empty($a)||(!$a)){
//	die();
//}
$cvID = CollectionVersion::getNumericalVersionID($ct->getCollectionID(),'ACTIVE');
$vObj = CollectionVersion::get($ct, $cvID);
$newsList = new PageList();
$newsList->filterByParentID($cat);
$newsList->sortBy('cvDatePublic', 'desc');
$newsPage=$newsList->getPage();

//Start creation

Loader::library('3rdparty/feedcreator/include/feedcreator.class',$pkgHandle);

//define channel
$rss = new UniversalFeedCreator();
$rss->useCached();
$rss->title=SITE.' - '.$vObj->cvName;
$rss->description=$vObj->cvName.' updates';
$rss->link=SITE_URL;
$rss->syndicationURL=EasyNewsController::getRssPagePath().'?c='.$cat;


//channel items/entries
foreach ($newsPage as $page){
	$item = new FeedItem();
	$item->title = $page->getCollectionName();
	$item->link = BASE_URL.Loader::helper('navigation')->getLinkToCollection($page);
	$item->description = $page->getCollectionDescription;
	$item->source = BASE_URL;
		$user = UserInfo::getByID($page->getCollectionUserID());
	$item->author = $user->getUserName();
	$item->date=date('r',strtotime($page->getCollectionDatePublic()));
	$rss->addItem($item);
}


//Output
//Valid parameters are RSS0.91, RSS1.0, RSS2.0, PIE0.1 (deprecated),
// MBOX, OPML, ATOM, ATOM1.0, ATOM0.3, HTML, JS

$rss->outputFeed("ATOM1.0"); 