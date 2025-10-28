<?php
$modalHeaderClass = 'pt-1 pb-1 ps-2 pe-2';
$modalHeaderTitle = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatpreview','New Crawler/Content');
$modalSize = 'xl';
$modalBodyClass = 'p-1'
?>

<?php include(erLhcoreClassDesign::designtpl('lhkernel/modal_header.tpl.php'));?>

<div class="modal-body lhc-module">

<?php if (isset($errors)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php')); ?>
<?php endif; ?>

<?php if (isset($saved) && $saved == 'true') : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/sendnotice','Crawl configuration was saved!'); ?>
    <script>
        setTimeout(function() {
            document.location.reload();
        },2000);
    </script>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
<?php endif; ?>

<form action="<?php echo erLhcoreClassDesign::baseurl('chatgptvector/newcrawl')?>/<?php echo htmlspecialchars($storage_id)?>" method="post" onsubmit="return lhinst.submitModalForm($(this))">

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php')); ?>

    <?php include(erLhcoreClassDesign::designtpl('lhchatgptvector/forms/crawler.tpl.php')); ?>

    <div class="btn-group" role="group" aria-label="...">
        <input type="submit" class="btn btn-primary" name="Create" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons', 'Save'); ?>" />
    </div>

</form>

</div>
<?php include(erLhcoreClassDesign::designtpl('lhkernel/modal_footer.tpl.php'));?>