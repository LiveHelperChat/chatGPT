<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','ChatGPT Test'); ?></h1>

<form action="" method="post" ng-non-bindable>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Enter your question');?></label>
        <textarea class="form-control form-control-sm" id="chatgpt-question"></textarea>
    </div>

    <div id="chatgpt-answer" class="py-1 text-muted"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Response will appear here...');?></div>

    <div class="btn-group" role="group" aria-label="Basic example">
        <button id="send-question" type="button" class="btn btn-sm btn-info"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Send'); ?></button>
    </div>

</form>

<script>
    (function () {
        $('#send-question').click(function(){
            let question = document.getElementById('chatgpt-question').value;

            if (!question) {
                alert('Please enter a question!');
                return;
            }
            
            $.postJSON(WWW_DIR_JAVASCRIPT + 'chatgpt/getanswer/answer', {'question': question}, function(data){
                $('#chatgpt-answer').html(data.response);
            }).fail(function(){
                alert('request failed');
            });
        });

    })();
</script>