<h1 class="attr-header"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Reported invalid suggestions');?></h1>

<?php include(erLhcoreClassDesign::designtpl('lhcchatbot/parts/filter_invalid.tpl.php')); ?>

<?php if ($pages->items_total > 0) { ?>

    <form class="lhc-module" action="<?php echo $input->form_action,$inputAppend?>" method="post" ng-non-bindable>

        <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

        <table cellpadding="0" cellspacing="0" class="table" width="100%">
            <thead>
            <tr>
                <th width="1%">ID</th>
                <th width="1%">Chat</th>
                <th width="1%">Reporter</th>
                <th width="50%"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Payload');?></th>
                <th width="50%"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Suggestion');?></th>
                <th width="1%"></th>
            </tr>
            </thead>
            <?php foreach ($items as $item) : ?>
                <tr>
                    <td class="fs12"><?php echo $item->id?></td>
                    <td nowrap="" class="fs12">
                        <?php if ($item->chat_id > 0) : ?>
                            <a href="#" title="Preview chat - <?php echo $item->chat_id?>" onclick="lhc.previewChat(<?php echo $item->chat_id?>)"><i class="material-icons">chat</i></a> <?php echo $item->chat_id?>
                        <?php endif; ?>
                    </td>
                    <td class="fs12">
                        [<?php echo $item->user_id?>] <?php echo erLhcoreClassModelUser::fetch($item->user_id)?><br>
                        <?php echo date('Y-m-d H:i:s',$item->ctime)?>
                    </td>
                    <td>
                        <textarea class="fs12 form-control" rows="10"><?php echo htmlspecialchars($item->question)?></textarea>

                    </td>
                    <td>
                        <textarea class="fs12 form-control" rows="10"><?php echo htmlspecialchars($item->answer)?></textarea>
                    </td>
                    <td nowrap>
                        <div class="btn-group" role="group" aria-label="..." style="width:60px;">
                            <a class="btn btn-danger btn-xs csfr-required" onclick="return confirm('<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('kernel/messages','Are you sure?');?>')" title="Report will be deleted" href="<?php echo erLhcoreClassDesign::baseurl('chatgpt/delete')?>/<?php echo $item->id?>" ><i class="material-icons me-0">&#xE872;</i></a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php include(erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php')); ?>

        <?php if (isset($pages)) : ?>
            <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
        <?php endif;?>

    </form>

<?php } else { ?>
    <br/>
    <p><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/activechats','Empty...');?></p>
<?php } ?>