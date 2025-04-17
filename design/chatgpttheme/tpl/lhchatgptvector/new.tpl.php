<h5><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptvector/new', 'Create new vector storage'); ?></h5>

<?php if (isset($errors)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<form method="post" action="<?php echo erLhcoreClassDesign::baseurl('chatgptvector/new')?>">

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>
    
    <div class="form-group mb-3">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptvector/new', 'Name'); ?>*</label>
        <input type="text" class="form-control form-control-sm" name="name" value="<?php echo htmlspecialchars($item->name);?>" required />
    </div>

    <div class="btn-group">
        <input type="submit" class="btn btn-sm btn-primary" name="Create" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptvector/new', 'Create'); ?>" />
        <a class="btn btn-sm btn-secondary" href="<?php echo erLhcoreClassDesign::baseurl('lhchatgptvector/list')?>">
            <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptvector/new', 'Cancel'); ?>
        </a>
    </div>

</form>