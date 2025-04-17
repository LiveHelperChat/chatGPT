<h5>Available Vector Storages</h5>

<?php if (!empty($items) && isset($items['data']) && !empty($items['data'])) : ?>
    <table class="table table-sm table-hover">
        <thead>
        <tr>
            <th>Name</th>
            <th>ID</th>
            <th>Created at</th>
            <th>Status</th>
            <th>Size</th>
            <th>Files</th>
            <th>Last active</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($items['data'] as $item) : ?>
            <tr>
                <td><a href="<?php echo erLhcoreClassDesign::baseurl('chatgptvector/view')?>/<?php echo htmlspecialchars($item['id']); ?>"><?php echo htmlspecialchars($item['name']); ?></a></td>
                <td><?php echo htmlspecialchars($item['id']); ?></td>
                <td><?php echo date('Y-m-d H:i', $item['created_at']); ?></td>
                <td><span class="badge bg-<?php echo $item['status'] == 'completed' ? 'success' : 'secondary'; ?>"><?php echo htmlspecialchars($item['status']); ?></span></td>
                <td><?php echo round($item['usage_bytes']/1024/1024, 2); ?> MB</td>
                <td>
                    <?php if (isset($item['file_counts'])) : ?>
                        <?php echo $item['file_counts']['completed']; ?>/<?php echo $item['file_counts']['total']; ?>
                        <?php if ($item['file_counts']['failed'] > 0) : ?>
                            <span class="text-danger">(<?php echo $item['file_counts']['failed']; ?> failed)</span>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
                <td><?php echo !empty($item['last_active_at']) ? date('Y-m-d H:i', $item['last_active_at']) : '-'; ?></td>
                <td>
                    <a class="btn btn-xs btn-danger csfr-required" onclick="return confirm('Are you sure?')" href="<?php echo erLhcoreClassDesign::baseurl('chatgptvector/delete')?>/<?php echo htmlspecialchars($item['id']); ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php')); ?>

<?php else : ?>
    <p class="text-muted">No vector storages found</p>
<?php endif; ?>

<a class="btn btn-sm btn-primary" href="<?php echo erLhcoreClassDesign::baseurl('chatgptvector/new')?>">Create new vector storage</a>

