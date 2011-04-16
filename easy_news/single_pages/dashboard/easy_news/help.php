<?php
defined("C5_EXECUTE") or die(_("Access Denied."));
?>
<h1><span><?php echo t('Easy News Help'); ?></span></h1>
<div class="ccm-dashboard-inner">
<?php echo t('If you would like to submit bug reports, feature requests or know the project status please contact me at <b>nick [_at_] concrete5 [_dot_] it</b> or see official wiki page <a href="http://www.concrete5.it/docuwiki/doku.php?id=packages:easy_news">here</a>'); ?>.
<br/><br/>
<?php echo t('If you have found this package useful, please consider making a donation.'); ?>
<br/><br/>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="business" value="nicola.87@gmail.com">
<input type="hidden" name="lc" value="IT">
<input type="hidden" name="item_name" value="Easy news">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="currency_code" value="EUR">
<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
<input type="image" src="https://www.paypal.com/it_IT/IT/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - Il sistema di pagamento online più facile e sicuro!">
<img alt="" border="0" src="https://www.paypal.com/it_IT/i/scr/pixel.gif" width="1" height="1">
</form>



</div>
