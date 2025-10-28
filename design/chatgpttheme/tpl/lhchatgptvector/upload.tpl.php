<?php if (isset($errors)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>


<?php if (isset($file_uploaded) && $file_uploaded == true) : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('file/new','File uploaded'); ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
<?php endif; ?>

<?php if ($file_uploaded == true) : ?>
    <script>
        setTimeout(function(){
            window.parent.location.reload();
        },1500);
    </script>
<?php endif;?>

<form action="" method="post" enctype="multipart/form-data" id="upload-form" class="lhc-module">

    <div class="form-group">
        <input type="file" name="files" />
    </div>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <input type="hidden" name="UploadFileAction" id="token" value="UploadFileAction" />

    <button type="button" class="btn btn-sm btn-secondary" name="UploadFileAction" id="upload-button" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('file/new','Upload');?>" onclick="this.disabled=true;this.innerHTML='<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('file/new','Uploading...');?>';this.form.submit();">
        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('file/new','Upload');?>
    </button>
</form>
