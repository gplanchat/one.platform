<?php

class One_Core_Setup_Block_Git
    extends One_Core_Block_Html
{
    public function _construct($options)
    {
        parent::_construct($options);

        $script =<<<SCRIPT_EOF
$(document).ready(function(){
    $('.repository.url').change(function(e){
        e.preventDefault();

        var populateSelect = function(element, values) {
            for (var i in values) {
                if (typeof(values[i]) == 'string') {
                    var child = document.createElement('option');
                    child.setAttribute('value', values[i]);

                    var label = document.createTextNode(values[i]);
                    child.appendChild(label);
                    element.append($(child));
                } else {
                    var child = document.createElement('optgroup');
                    child.setAttribute('label', i);
                    child.setAttribute('value', 0);

                    element.append($(child));
                    populateSelect($(child), values[i]);
                }
            }
            };

        var result = $.post('stage-one-ajax', {repository: $(this).val()}, function(response, status, request) {
            if (status !== 'success') {
                return;
            }
            var branches = $('.repository.branch');
            branches.empty();
            populateSelect(branches, response);
            }, 'json');
        });
    });
SCRIPT_EOF;

        $this->headScript()
            ->appendScript(file_get_contents(ROOT_PATH . DS . 'public/js/jquery.js'))
            ->appendScript($script);
    }
}