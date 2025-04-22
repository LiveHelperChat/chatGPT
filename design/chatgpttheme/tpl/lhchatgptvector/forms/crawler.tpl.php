<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'Name'); ?>*</label>
    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($item->name); ?>" />
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'Base URL'); ?>*</label>
            <input type="text" class="form-control" name="base_url" placeholder="https://example.com" value="<?php echo htmlspecialchars($item->base_url); ?>" />
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'Start URL'); ?></label>
            <input type="text" class="form-control" name="start_url" placeholder="https://example.com/faq" value="<?php echo htmlspecialchars($item->start_url); ?>" />
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'Maximum number of pages to index. Leave 0 for unlimited.'); ?></label>
            <input type="number" min="0" class="form-control" name="max_pages" placeholder="0" value="<?php echo htmlspecialchars($item->max_pages); ?>" />
        </div>
    </div>
</div>

<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'Crawl only those URL. One URL per line.'); ?></label>
    <textarea class="form-control" rows="5" name="url" placeholder="https://example.com"><?php echo htmlspecialchars($item->url); ?></textarea>
    <p class="text-muted fs12 mb0 pb0">We will ignore Base URL and Start URL and only those URLs will be indexed</p>
</div>

<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'Crawl frequency (hours)'); ?>*</label>
    <input type="number" min="1" class="form-control" name="crawl_frequency" value="<?php echo htmlspecialchars($item->crawl_frequency ? $item->crawl_frequency : 24); ?>" />
    <span class="text-muted fs12"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'How often the crawler should check for updates (in hours)'); ?></span>
</div>