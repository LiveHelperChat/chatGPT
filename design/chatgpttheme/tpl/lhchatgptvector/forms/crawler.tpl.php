<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'Name'); ?>*</label>
    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($item->name); ?>" />
</div>

<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'URL'); ?>*</label>
    <textarea class="form-control" rows="5" name="url" placeholder="https://example.com"><?php echo htmlspecialchars($item->url); ?></textarea>
</div>

<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'Crawl frequency (hours)'); ?>*</label>
    <input type="number" min="1" class="form-control" name="crawl_frequency" value="<?php echo htmlspecialchars($item->crawl_frequency ? $item->crawl_frequency : 24); ?>" />
    <span class="text-muted fs12"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'How often the crawler should check for updates (in hours)'); ?></span>
</div>