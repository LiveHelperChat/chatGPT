var lhcChatGPT = {
    disabled : false,

    chatData : {

    },

    replyByChat : function (chat_id) {
        $.postJSON(WWW_DIR_JAVASCRIPT + 'chatgpt/getanswer/answer', {'chat_id' : chat_id, 'question' : $('#chatgpt_question_'+chat_id).val()}, function (data) {
            $('#chatgpt_response_' + chat_id).html(data.response);
        });
        return false;
    },

    addListener : function (evt) {
        var elm = evt.currentTarget;
        if (evt.which == 39 && elm !== null && elm.value == '' && typeof elm.chatterBot !== 'undefined') {
            elm.value = elm.chatterBot.a;
            var chat_id = elm.getAttribute('id').replace('CSChatMessage-','');
        }
    },

    syncadmin: function (params,chatMode) {

        if (lhcChatGPT.disabled == false && typeof params.chatgptbotids !== 'undefined') {

            $.getJSON(WWW_DIR_JAVASCRIPT + 'chatgpt/suggest/(id)/' + params.chatgptbotids.join("/")+'/(chat)/'+(chatMode === true ? 1 : 0), function (data) {

                $.each(data.sg, function (chat_id, item) {

                    if (!data.keep_previous) {
                        $('#suggest-container-' + chat_id).find('li.suggestion').remove();
                    }

                    var messageArea = $('#CSChatMessage-'+chat_id);

                    // Perhaps chat was closed while waiting for resposne
                    if (messageArea.length == 0) {
                        return;
                    }

                    var containerSuggest = $('#suggest-container-' + chat_id);

                    if ($('#suggest-container-' + chat_id).length == 0) {
                        $('#CSChatMessage-' + chat_id).parent().after('<div id="suggest-container-' + chat_id + '"><ul class="lhcchatbot-list list-inline generic-small-res-hide"></ul></div>');
                    }

                    containerSuggest = $('#suggest-container-' + chat_id).find('ul');

                    containerSuggest.find('button').removeClass('border-white');

                    $.each(item, function (i, itemSuggest) {
                        if ($('#' + chat_id + '-' + itemSuggest.aid).length == 0) {
                            messageArea.attr('placeholder',itemSuggest.a+' | →')[0].chatterBot = itemSuggest;
                            var li = jQuery('<li class="list-inline-item ps-1 pb-1 suggestion" ><button type="button" class="btn btn-sm text-secondary btn-light" onclick="lhcChatGPT.sendSuggest(' + chat_id + ',$(this))" title="Send a message" data-title="' + jQuery('<p/>').text(itemSuggest.a).html() + '" "><i class="material-icons me-0 fs11">edit</i></button> <button data-title="' + jQuery('<p/>').text(itemSuggest.a).html() + '" onclick="return lhcChatGPT.prefill(' + chat_id + ',$(this))" style="max-width: 300px" title="' + jQuery('<p/>').text(itemSuggest.a + " => " + itemSuggest.q).html() + '" type="button" class="btn btn-sm btn-light text-secondary border border-white text-truncate btn-send-success text-left">' + jQuery('<p/>').text(itemSuggest.a).html() + '</button> <button type="button" data-aid="' + itemSuggest.aid + '" class="btn btn-xs btn-warning" title="Report invalid suggestion" onclick="return lhcChatGPT.sendNegative(' + chat_id + ',$(this))"><i class="material-icons me-0 fs11">delete</i></button></li>').attr('title', jQuery('<p/>').text(itemSuggest.q).html());
                            var completer = $('#suggest-completer-'+chat_id);
                            if (completer.length > 0) {
                                completer.parent().after(li);
                            } else {
                                containerSuggest.prepend(li);
                            }
                        }
                    });

                    containerSuggest.find('li.suggestion:gt(3)').remove();

                    // Adjust scroll by the height of container
                    $('#messagesBlock-'+chat_id).prop('scrollTop',$('#messagesBlock-'+chat_id).prop('scrollTop')+$('#suggest-container-'+chat_id).height());

                });
            });
        }
    },

    sendNegative: function (chat_id, inst) {
        $.postJSON(WWW_DIR_JAVASCRIPT + 'chatgpt/suggestinvalid/' + chat_id, {
            'aid': inst.attr('data-aid')
        }, function (data) {
            inst.parent().remove();
        });
        return false;
    },

    prefill : function(chat_id,inst)
    {
        $("#CSChatMessage-" + chat_id).val(inst.attr('data-title'));
    },

    sendSuggest: function (chat_id, inst) {
        // Send message
        var textarea = $("#CSChatMessage-" + chat_id);
        textarea.val(inst.attr('data-title'));

        lhinst.addmsgadmin(chat_id);

        inst.attr('disabled','disabled').prepend('<span class="material-icons lhc-spin">autorenew</span>');
        setTimeout(function(){
            inst.removeAttr('disabled').parent().remove();
        },1000);
        textarea.focus();
    },

    selectedText: null
};

ee.addListener('eventSyncAdmin', function (params) {
    if (lhcChatGPT.disabled == false) {
        lhcChatGPT.syncadmin(params,false);
    }
});

ee.addListener('adminChatLoaded', function (chat_id) {
    var elm = document.getElementById('CSChatMessage-'+chat_id);
    if (elm !== null) {
        elm.addEventListener('keydown', lhcChatGPT.addListener);
    }
    lhcChatGPT.syncadmin({chatgptbotids:[chat_id]},true);
})

ee.addListener('removeSynchroChat', function(chat_id) {

    try {
        delete lhcChatGPT.chatData[chat_id];
    } catch (e){}

    // Remove event listener
    var elm = document.getElementById('CSChatMessage-'+chat_id);
    if (elm !== null) {
        elm.removeEventListener('keydown', lhcChatGPT.addListener);
    }
});

ee.addListener('eventLoadInitialData', function(initialData) {
    if (initialData.lhcchatgpt) {
        lhcChatGPT.disabled = !initialData.lhcchatgpt.enabled;
    }
});
