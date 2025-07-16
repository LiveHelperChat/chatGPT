<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'Name'); ?>*</label>
    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($item->name); ?>" />
</div>

<div class="form-group">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link <?php echo (!isset($item->type) || $item->type == \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl::TYPE_CRAWL) ? 'active' : ''; ?>" href="#crawler-tab" data-bs-toggle="tab" role="tab">
                <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'Crawler'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo (isset($item->type) && $item->type == \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl::TYPE_CONTENT) ? 'active' : ''; ?>" href="#content-tab" data-bs-toggle="tab" role="tab">
                <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'Content'); ?>
            </a>
        </li>
    </ul>
</div>

<input type="hidden" name="type" id="type-input" value="<?php echo htmlspecialchars(isset($item->type) ? $item->type : \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl::TYPE_CRAWL); ?>" />

<div class="tab-content">
    <div class="tab-pane fade <?php echo (!isset($item->type) || $item->type == \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl::TYPE_CRAWL) ? 'show active' : ''; ?>" id="crawler-tab" role="tabpanel">

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

    </div>
    
    <div class="tab-pane fade <?php echo (isset($item->type) && $item->type == \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl::TYPE_CONTENT) ? 'show active' : ''; ?>" id="content-tab" role="tabpanel">
        <div class="form-group">
            <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'Content'); ?>*</label>
            <textarea class="form-control" rows="10" name="content" placeholder="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'Enter your content here...'); ?>"><?php echo htmlspecialchars($item->content ?? ''); ?></textarea>
            <p class="text-muted fs12 mb0 pb0"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'Enter the content that you want to make searchable'); ?></p>
        </div>
    </div>
</div>

<script>
const tabLinks = document.querySelectorAll('a[data-bs-toggle="tab"]');
const typeInput = document.getElementById('type-input');
tabLinks.forEach(link => {
    link.addEventListener('shown.bs.tab', function (e) {
        const tabId = e.target.getAttribute('href');
        console.log('Tab changed to:', tabId);
        if (tabId === '#crawler-tab') {
            typeInput.value = '<?php echo \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl::TYPE_CRAWL; ?>';
        } else if (tabId === '#content-tab') {
            typeInput.value = '<?php echo \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl::TYPE_CONTENT; ?>';
        }
    });
});
</script>