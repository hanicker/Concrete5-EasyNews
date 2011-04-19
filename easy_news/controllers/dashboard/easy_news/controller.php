<?php 
defined('C5_EXECUTE') or die(_("Access Denied.")); 
class DashboardEasyNewsController extends EasyNewsDashboardController {
	
	public $helpers = array('html','form');
	
	public function on_start() {
		$subnav = array(
			array(View::url('/dashboard/easy_news'),t('Easy News Manage'), true), 
			array(View::url('/dashboard/easy_news/help'),'Help')
		);
		$this->set('subnav', $subnav);
		Loader::model('page_list');
		$this->error = Loader::helper('validation/error');
	}
	
	public function view() {
		$this->loadNewsSections();
		$newsList = new PageList();
		$newsList->sortBy('cDateAdded', 'desc');
		if (isset($_GET['cParentID']) && $_GET['cParentID'] > 0) {
			$newsList->filterByParentID($_GET['cParentID']);
		} else {
			$sections = $this->get('sections');
			$keys = array_keys($sections);
			$keys[] = -1;
			$newsList->filterByParentID($keys);
		}
		$this->set('newsList', $newsList);
		$this->set('newsResults', $newsList->getPage());
	}

	protected function loadNewsSections() {
		$newsSectionList = new PageList();
		$newsSectionList->filterByEasynewsSection(1);
		$newsSectionList->sortBy('cvName', 'asc');
		$tmpSections = $newsSectionList->get();
		$sections = array();
		foreach($tmpSections as $_c) {
			$sections[$_c->getCollectionID()] = $_c->getCollectionName();
		}
		$this->set('sections', $sections);
	}


	public function edit($cID) {
		$this->setupForm();
		$news = Page::getByID($cID);
		$sections = $this->get('sections');
		if (in_array($news->getCollectionParentID(), array_keys($sections))) {
			$this->set('news', $news);	
		} else {
			$this->redirect('/dashboard/easy_news/');
		}
	}
	
	public function delete($cID) {
		$this->setupForm();
		$news = Page::getByID($cID);
		$sections = $this->get('sections');
		if (in_array($news->getCollectionParentID(), array_keys($sections))) {
			$this->set('news', $news);	
		} else {
			$this->redirect('/dashboard/easy_news/');
		}
	}	

	protected function setupForm() {
		$this->loadNewsSections();
		Loader::model("collection_types");
		$ctArray = CollectionType::getList('');
		$pageTypes = array();
		foreach($ctArray as $ct) {
			$pageTypes[$ct->getCollectionTypeID()] = $ct->getCollectionTypeName();		
		}
		$this->set('pageTypes', $pageTypes);
		$this->addHeaderItem(Loader::helper('html')->javascript('tiny_mce/tiny_mce.js'));
	}

	public function add() {
		$this->setupForm();
		if ($this->isPost()) {
			$this->validate();
			if (!$this->error->has()) {
				$parent = Page::getByID($this->post('cParentID'));
				$ct = CollectionType::getByID($this->post('ctID'));				
				$data = array('cName' => $this->post('newsTitle'), 'cDescription' => $this->post('newsDescription'), 'cDatePublic' => Loader::helper('form/date_time')->translate('newsDate'));
				$p = $parent->add($ct, $data);	
				//$rss='<link rel="alternate" type="application/rss+xml" title="'.Loader::helper('text')->entities($p->getCollectionName()).'" href="/'.$this->site($this->rssPageName).'" />';
				//$p->setAttribute('header_extra_content',$rss);
				$this->saveData($p);
				$this->redirect('/dashboard/easy_news/', 'news_added');
			}
		}
	}

	public function update() {
		$this->edit($this->post('newsID'));
		
		if ($this->isPost()) {
			$this->validate();
			if (!$this->error->has()) {
				$p = Page::getByID($this->post('newsID'));
				$parent = Page::getByID($this->post('cParentID'));
				$ct = CollectionType::getByID($this->post('ctID'));				
				$data = array('ctID' =>$ct->getCollectionTypeID(), 'cDescription' => $this->post('newsDescription'), 'cName' => $this->post('newsTitle'), 'cDatePublic' => Loader::helper('form/date_time')->translate('newsDate'));
				$p->update($data);
				if ($p->getCollectionParentID() != $parent->getCollectionID()) {
					$p->move($parent);
				}
				$this->saveData($p);
				$this->redirect('/dashboard/easy_news/', 'news_updated');
			}
		}
	}
	
	public function remove() {
		$this->delete($this->post('newsID'));
		
		if ($this->isPost()) {
			$p = Page::getByID($this->post('newsID'));
			$p->delete();
			$this->redirect('/dashboard/easy_news/', 'news_deleted');
		}
	}	

	protected function validate() {
		$vt = Loader::helper('validation/strings');
		$vn = Loader::Helper('validation/numbers');
		$dt = Loader::helper("form/date_time");
		if (!$vn->integer($this->post('cParentID'))) {
			$this->error->add(t('You must choose a parent page for this News entry.'));
		}			

		if (!$vn->integer($this->post('ctID'))) {
			$this->error->add(t('You must choose a page type for this News entry.'));
		}			
		
		if (!$vt->notempty($this->post('newsTitle'))) {
			$this->error->add(t('Title is required'));
		}

		if (!$this->error->has()) {
			Loader::model('collection_types');
			$ct = CollectionType::getByID($this->post('ctID'));				
			$parent = Page::getByID($this->post('cParentID'));				
			$parentPermissions = new Permissions($parent);
			if (!$parentPermissions->canAddSubCollection($ct)) {
				$this->error->add(t('You do not have permission to add a page of that type to that area of the site.'));
			}
		}
	}
	
	private function saveData($p) {
		$blocks = $p->getBlocks('Main');
		foreach($blocks as $b) {
			if($b->getBlockTypeHandle()=='content')
				$b->deleteBlock();
		}

		$bt = BlockType::getByHandle('content');
		$data = array('content' => $this->post('newsBody'));
		$p->addBlock($bt, 'Main', $data);
		
		Loader::model("attribute/categories/collection");
	}

	public function news_added() {
		$this->set('message', t('News added.'));
		$this->view();
	}
	
	public function news_updated() {
		$this->set('message', t('News updated.'));
		$this->view();
	}
	
	public function news_deleted() {
		$this->set('message', t('News deleted.'));
		$this->view();
	}	
	
	public function on_before_render() {
		$this->set('error', $this->error);
	}
	
}