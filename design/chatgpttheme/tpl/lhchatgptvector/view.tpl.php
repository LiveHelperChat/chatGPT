<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Vector Storage: <?php echo htmlspecialchars($storage['name']); ?></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th>ID</th>
                                <td><?php echo htmlspecialchars($storage['id']); ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><span class="badge bg-success"><?php echo htmlspecialchars($storage['status']); ?></span></td>
                            </tr>
                            <tr>
                                <th>Created</th>
                                <td><?php echo date('Y-m-d H:i:s', $storage['created_at']); ?></td>
                            </tr>
                            <tr>
                                <th>Last Active</th>
                                <td><?php echo date('Y-m-d H:i:s', $storage['last_active_at']); ?></td>
                            </tr>
                            <tr>
                                <th>Storage Usage</th>
                                <td><?php echo number_format($storage['usage_bytes'] / 1024 / 1024, 2); ?> MB</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">File Counts</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-sm-6">
                                        <div class="info-box bg-info rounded text-white ps-1">
                                            <div class="info-box-content">
                                                <span class="info-box-text text-white pe-1">Total</span>
                                                <span class="info-box-number text-white"><?php echo $storage['file_counts']['total']; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="info-box bg-success text-white ps-1 rounded">
                                            <div class="info-box-content">
                                                <span class="info-box-text pe-1">Completed</span>
                                                <span class="info-box-number"><?php echo $storage['file_counts']['completed']; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="info-box bg-warning text-white ps-1 rounded">
                                            <div class="info-box-content">
                                                <span class="info-box-text pe-1">In Progress</span>
                                                <span class="info-box-number"><?php echo $storage['file_counts']['in_progress']; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="info-box bg-danger text-white ps-1 rounded">
                                            <div class="info-box-content">
                                                <span class="info-box-text pe-1">Failed</span>
                                                <span class="info-box-number"><?php echo $storage['file_counts']['failed']; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Files</h3>
            </div>
            <div class="card-body">
                <?php if (isset($files['data']) && !empty($files['data'])): ?>
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Size</th>
                            <th>Chunk Size</th>
                            <th>Overlap</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($files['data'] as $file) : $fileInformation = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::getFile($file['id']); ?>
                        <tr>
                            <td title="<?php echo htmlspecialchars($file['id']); ?>"><?php echo htmlspecialchars($fileInformation['filename'] ?? 'File was deleted!'); ?></td>
                            <td>
                                <?php if ($file['status'] == 'completed'): ?>
                                <span class="badge bg-success">Completed</span>
                                <?php elseif ($file['status'] == 'in_progress'): ?>
                                <span class="badge bg-warning">In Progress</span>
                                <?php else: ?>
                                <span class="badge bg-danger"><?php echo htmlspecialchars($file['status']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('Y-m-d H:i:s', $file['created_at']); ?></td>
                            <td><?php echo number_format($file['usage_bytes'] / 1024, 2); ?> KB</td>
                            <td><?php echo isset($file['chunking_strategy']['static']['max_chunk_size_tokens']) ? $file['chunking_strategy']['static']['max_chunk_size_tokens'] : 'N/A'; ?></td>
                            <td><?php echo isset($file['chunking_strategy']['static']['chunk_overlap_tokens']) ? $file['chunking_strategy']['static']['chunk_overlap_tokens'] : 'N/A'; ?></td>
                            <td>
                                <a class="btn btn-xs btn-danger csfr-required" onclick="return confirm('Are you sure?')" href="<?php echo erLhcoreClassDesign::baseurl('chatgptvector/deletefile')?>/<?php echo htmlspecialchars($storage_id)?>/<?php echo htmlspecialchars($file['id'])?>"><i class="material-icons me-0">&#xE872;</i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p>No files found in this vector storage.</p>
                <?php endif; ?>

                <button onclick="lhc.revealModal({'title' : 'Upload file to vector storage','iframe':true,'height':500,'url':WWW_DIR_JAVASCRIPT +'chatgptvector/upload/<?php echo htmlspecialchars($storage_id)?>'})" type="button" class="btn btn-sm btn-primary">Upload</button>

            </div>
        </div>
    </div>
</div>


<div class="row mt-2">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Crawls</h3>
            </div>
            <div class="card-body">

                <?php if ($crawls = LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl::getList(['filter' => ['vector_storage_id' => $storage_id]])) : ?>
                    <table class="table table-hover table-sm">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>URL</th>
                            <th>Number of Pages</th>
                            <th>Crawl Frequency</th>
                            <th>Last Crawled</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($crawls as $crawl) : ?>
                            <tr>
                                <td>
                                    <?php if ($crawl->file_id != '') : ?>
                                            <span class="material-icons" title="File ID: <?php echo htmlspecialchars($crawl->file_id); ?>">description</span>
                                    <?php endif; ?>
                                    <?php if ($crawl->lhc_file_id > 0) : ?>
                                        <?php  $fileData = erLhcoreClassModelChatFile::fetch($crawl->lhc_file_id); if ($fileData instanceof erLhcoreClassModelChatFile) :  ?>
                                            <a href="<?php echo erLhcoreClassDesign::baseurl('file/downloadfile')?>/<?php echo $fileData->id?>/<?php echo $fileData->security_hash?>" target="_blank">
                                                <span class="material-icons" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Download local copy')?>">download</span>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <a href="#" title="<?php echo htmlspecialchars($crawl->file_id); ?>" onclick="lhc.revealModal({'url':WWW_DIR_JAVASCRIPT +'chatgptvector/editcrawl/<?php echo htmlspecialchars($storage_id)?>/<?php echo $crawl->id?>'})" > <span class="material-icons" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Edit')?>">edit</span><?php echo htmlspecialchars($crawl->name); ?></a>
                                </td>
                                <td>

                                    <span class="material-icons" title="Base URL">link</span><?php echo htmlspecialchars($crawl->base_url); ?>

                                    <?php if ($crawl->start_url != '') : ?>
                                        <br /><span class="material-icons" title="Start URL">start</span><?php echo htmlspecialchars($crawl->start_url); ?>
                                    <?php endif; ?>

                                </td>
                                <td><?php echo htmlspecialchars($crawl->number_of_pages); ?></td>
                                <td><?php echo htmlspecialchars($crawl->crawl_frequency); ?></td>
                                <td><?php echo $crawl->last_crawled_at > 0 ? date('Y-m-d H:i:s', $crawl->last_crawled_at) : 'Never'; ?></td>
                                <td>
                                    <?php if ($crawl->status == \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl::STATUS_IDLE): ?>
                                    <span class="badge bg-secondary">Idle</span>
                                    <?php elseif ($crawl->status == \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl::STATUS_CRAWLING): ?>
                                    <span class="badge bg-warning">Crawling</span>
                                    <?php elseif ($crawl->status == \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl::STATUS_ERROR): ?>
                                    <span class="badge bg-danger">Error</span>
                                    <?php elseif ($crawl->status == \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl::STATUS_COMPLETED): ?>
                                    <span class="badge bg-success">Completed</span>
                                    <?php else: ?>
                                    <span class="badge bg-info">Unknown</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a class="btn btn-xs btn-warning csfr-required me-1" onclick="return confirm('Reset crawl status?')" href="<?php echo erLhcoreClassDesign::baseurl('chatgptvector/resetcrawl')?>/<?php echo htmlspecialchars($storage_id)?>/<?php echo $crawl->id?>"><i class="material-icons me-0">refresh</i></a>
                                    <a class="btn btn-xs btn-danger csfr-required" onclick="return confirm('Are you sure?')" href="<?php echo erLhcoreClassDesign::baseurl('chatgptvector/deletecrawl')?>/<?php echo htmlspecialchars($storage_id)?>/<?php echo $crawl->id?>"><i class="material-icons me-0">&#xE872;</i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No crawls found for this vector storage.</p>
                <?php endif; ?>

                <button onclick="lhc.revealModal({'url':WWW_DIR_JAVASCRIPT +'chatgptvector/newcrawl/<?php echo htmlspecialchars($storage_id)?>'})" type="button" class="btn btn-sm btn-primary">New</button>

            </div>
        </div>
    </div>
</div>

<?php include(erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php')); ?>
