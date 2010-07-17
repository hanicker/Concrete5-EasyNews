<?php 

defined('C5_EXECUTE') or die(_("Access Denied."));
?>
	
	<?php  if (($this->controller->getTask() == 'update' || $this->controller->getTask() == 'edit' || $this->controller->getTask() == 'add' || $this->controller->getTask() == 'delete')) { ?>
	
	<?php  
	$df = Loader::helper('form/date_time');
	$miscFields=array();
	if (is_object($news)) { 
		$newsTitle = $news->getCollectionName();
		$newsDescription = $news->getCollectionDescription();
		$newsDate = $news->getCollectionDatePublic();
		$cParentID = $news->getCollectionParentID();
		$ctID = $news->getCollectionTypeID();
		$newsBody = '';
		$eb = $news->getBlocks('Main');
		if (is_object($eb[0])) {
			$newsBody = $eb[0]->getInstance()->getContent();
		}
		if ($this->controller->getTask() != 'delete'){
			$task = 'update';
			$buttonText = t('Update Entry');
			$title = t('Update');
		}else{
			$task = 'remove';
			$buttonText = t('Delete Entry');
			$title = t('Delete');
			$miscFields=array('disabled'=>'true');
		}
	} else {
		$task = 'add';
		$title = t('Add');
		$buttonText = t('Add News Entry');
	}
	
	?>
	
	<div style="width: 760px">
	
	<h1><span><?php echo t('News')?></span></h1>
	<div class="ccm-dashboard-inner">
	
	<h2><span><?php echo $title?> News Entry</span></h2>
	
	<form method="post" action="<?php echo $this->action($task)?>" id="news-form">
	<?php  if ($this->controller->getTask() != 'add') { ?>
		<?php echo $form->hidden('newsID', $news->getCollectionID())?>
	<?php  } ?>
	
	<strong><?php echo $form->label('newsTitle', t('Title'))?></strong>
	<div><?php echo $form->text('newsTitle', $newsTitle, array_merge(array('style' => 'width: 730px'),$miscFields))?></div>
	<br/>
	<strong><?php echo $form->label('newsDescription', t('Summary'))?></strong>
	<div><?php echo $form->textarea('newsDescription', $newsDescription, array_merge(array('style' => 'width: 730px; height: 100px'),$miscFields))?></div>
	<br/>			
	<?php if ($task!='remove'){ ?>
	<strong><?php echo $form->label('cParentID', t('Section'))?></strong>
	<?php  if (count($sections) == 0) { ?>
		<div><?php echo t('No sections defined. Please create a page with the attribute "simplenews_entry" set to true.')?></div>
	<?php  } else { ?>
		<div><?php echo $form->select('cParentID', $sections, $cParentID)?></div>
	<?php  } ?>
<br/>
	
	<strong><?php echo $form->label('ctID', t('Page Type'))?></strong>
	<div><?php echo $form->select('ctID', $pageTypes, $ctID)?></div>
	<br/>
	<strong><?php echo $form->label('newsDate', t('Date/Time'))?></strong>
	<div><?php echo $df->datetime('newsDate', $newsDate)?></div>
	<br/>
	<strong><?php echo t('Full text')?></strong>
	<?php  Loader::element('editor_init'); ?>
	<?php  Loader::element('editor_config'); ?>
	<?php  Loader::element('editor_controls', array('mode'=>'full')); ?>
	<?php echo $form->textarea('newsBody', $newsBody, array('style' => 'width: 100%; height: 150px', 'class' => 'ccm-advanced-editor'))?>
	
	<br/>
	<?php } ?>
	<?php 
	$ih = Loader::helper('concrete/interface');
	print $ih->button(t('Cancel'), $this->url('/dashboard/easy_news/'), 'left');
	print $ih->submit($buttonText, 'news-form');
	?>
	<div class="ccm-spacer">&nbsp;</div>
	
	</form>
	
	</div>
	</div>
	
	<?php  } else { ?>
	
		<h1><span><?php echo t('News')?></span></h1>
		<div class="ccm-dashboard-inner">
		<h2><?php echo t('Add News')?></h2>
		<a href="<?php echo $this->action('add')?>"><?php echo t('Click here to add a new News Entry &gt;')?></a>
		<Br/><br/>
		
		<h2><?php echo t('View/Search News')?></h2>
	
		<form method="get" action="<?php echo $this->action('view')?>">
		<?php 
		$sections[0] = '** All';
		asort($sections);
		?>
		
		<strong><?php echo $form->label('cParentID', t('Section'))?></strong>
		<div><?php echo $form->select('cParentID', $sections, $cParentID)?>
		<?php echo $form->submit('submit', 'Search')?>
		</div>
		</form>
		<br/>
		<?php 
		$nh = Loader::helper('navigation');
		if ($newsList->getTotal() > 0) { 
			$newsList->displaySummary();
			?>
			
		<table border="0" class="ccm-results-list" cellspacing="0" cellpadding="0">
			<tr>
				<th class="<?php echo $newsList->getSearchResultsClass('cvName')?>"><a href="<?php echo $newsList->getSortByURL('cvName', 'asc')?>"><?php echo t('Name')?></a></th>
				<th class="<?php echo $newsList->getSearchResultsClass('cDateAdded')?>"><a href="<?php echo $newsList->getSortByURL('cDateAdded', 'asc')?>"><?php echo t('Date Added')?></a></th>
				<th class="<?php echo $newsList->getSearchResultsClass('cvDatePublic')?>"><a href="<?php echo $newsList->getSortByURL('cvDatePublic', 'asc')?>"><?php echo t('Public Date')?></a></th>
				<th><?php echo t('Page Owner')?></th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
			</tr>
			<?php 
			foreach($newsResults as $cobj) { ?>
			<tr>
				<td><a href="<?php echo $nh->getLinkToCollection($cobj)?>"><?php echo $cobj->getCollectionName()?></a></td>
				<td><?php echo $cobj->getCollectionDateAdded()?></td>
				<td><?php echo $cobj->getCollectionDatePublic()?></td>
				<td>
					<?php  
					$user = UserInfo::getByID($cobj->getCollectionUserID());
					print $user->getUserName();
					?>
				</td>
				<td><A href="<?php echo $this->url('/dashboard/easy_news', 'edit', $cobj->getCollectionID())?>"><?php echo t('Edit')?></a></td>
				<td><A href="<?php echo $this->url('/dashboard/easy_news', 'delete', $cobj->getCollectionID())?>"><?php echo t('Delete')?></a></td>
			</tr>
			<?php  } ?>
			
			</table>
			<br/>
			<?php 
			$newsList->displayPaging();
		} else {
			print t('No News entries found.');
		}
		?>
		</div>
		
	<?php  }?>
