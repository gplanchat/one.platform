<?php

class One_Core_Setup_Block_Setup
    extends One_Core_Block_Html
{
    public function _construct($options)
    {
        parent::_construct($options);

        $script =<<<SCRIPT_EOF
$(document).ready(function(){
    $('.rdbms.test').click(function(e){
        e.preventDefault();

        var result = $.post('stage-two-rdbms-test-ajax', $(':input', $(this).parents('fieldset').first()).serializeArray(), function(response, status, request) {
            if (status !== 'success') {
                return;
            }
            if (response.status === true) {
                alert('Connection was successful, using server version "' + response.version + '".');
            } else {
                alert('Connection failed, the server returned the following error: "' + response.error + '".');
            }
            }, 'json');
        });
    });
SCRIPT_EOF;

        $this->headScript()
            ->appendScript(file_get_contents(ROOT_PATH . DS . 'public/js/jquery.js'))
            ->appendScript($script);
    }
}