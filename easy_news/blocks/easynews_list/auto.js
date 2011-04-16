var newsList ={
	servicesDir: $("input[name=newsListToolsDir]").val(),
	init:function(){
		this.blockForm=document.forms['ccm-block-form'];
		this.cParentIDRadios=this.blockForm.cParentID;
		for(var i=0;i<this.cParentIDRadios.length;i++){
			this.cParentIDRadios[i].onclick  = function(){ newsList.locationOtherShown(); }
			this.cParentIDRadios[i].onchange = function(){ newsList.locationOtherShown(); }			
		}
		
		this.rss=document.forms['ccm-block-form'].rss;
		for(var i=0;i<this.rss.length;i++){
			this.rss[i].onclick  = function(){ newsList.rssInfoShown(); }
			this.rss[i].onchange = function(){ newsList.rssInfoShown(); }			
		}
		
		this.truncateSwitch=$('#ccm-newslist-truncateSummariesOn');
		this.truncateSwitch.click(function(){ newsList.truncationShown(this); });
		this.truncateSwitch.change(function(){ newsList.truncationShown(this); });
		
		this.tabSetup();
	},	
	tabSetup: function(){
		$('ul#ccm-newslist-tabs li a').each( function(num,el){ 
			el.onclick=function(){
				var pane=this.id.replace('ccm-newslist-tab-','');
				newsList.showPane(pane);
			}
		});		
	},
	truncationShown:function(cb){ 
		var truncateTxt=$('#ccm-newslist-truncateTxt');
		var f=$('#ccm-newslist-truncateChars');
		if(cb.checked){
			truncateTxt.removeClass('faintText');
			f.attr('disabled',false);
		}else{
			truncateTxt.addClass('faintText');
			f.attr('disabled',true);
		}
	},
	showPane:function(pane){
		$('ul#ccm-newslist-tabs li').each(function(num,el){ $(el).removeClass('ccm-nav-active') });
		$(document.getElementById('ccm-newslist-tab-'+pane).parentNode).addClass('ccm-nav-active');
		$('div.ccm-newslistPane').each(function(num,el){ el.style.display='none'; });
		$('#ccm-newslistPane-'+pane).css('display','block');
		if(pane=='preview') this.loadPreview();
	},
	locationOtherShown:function(){
		for(var i=0;i<this.cParentIDRadios.length;i++){
			if( this.cParentIDRadios[i].checked && this.cParentIDRadios[i].value=='OTHER' ){
				$('div.ccm-page-list-page-other').css('display','block');
				return; 
			}				
		}
		$('div.ccm-page-list-page-other').css('display','none');
	},
	loadPreview:function(){
		var loaderHTML = '<div style="padding: 20px; text-align: center"><img src="' + CCM_IMAGE_PATH + '/throbber_white_32.gif"></div>';
		$('#ccm-newslistPane-preview').html(loaderHTML);
		var qStr=$(this.blockForm).formSerialize();
		$.ajax({ 
			url: this.servicesDir+'preview_pane.php?'+qStr,
			success: function(msg){ $('#ccm-newslistPane-preview').html(msg); }
		});
	},
	validate:function(){
			var failed=0;
			
			var rssOn=$('#ccm-newslist-rssSelectorOn');
			
			if(failed){
				ccm_isBlockError=1;
				return false;
			}
			return true;
	}	
}
$(function(){ newsList.init(); });

ccmValidateBlockForm = function() { return newsList.validate(); }