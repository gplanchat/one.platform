<?php
/**
 * This file is part of One.Platform
 *
 * @license Modified BSD
 * @see https://github.com/gplanchat/one.platform
 *
 * Copyright (c) 2009-2010, Grégory PLANCHAT <g.planchat at gmail.com>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *     - Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *
 *     - Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *
 *     - Neither the name of Grégory PLANCHAT nor the names of its
 *       contributors may be used to endorse or promote products derived from this
 *       software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing One.Platform.
 *
 */

/**
 * User login block, displays a login form
 *
 * @access      public
 * @author      gplanchat
 * @category    User
 * @package     One_User
 * @subpackage  One_User
 */
class Legacies_Chat_Block_Chat
    extends One_Core_Block_Html_Form
{
    protected $_submitLabel = 'Send';

    public function _construct($options)
    {
        $options = parent::_construct($options);

        $this->headScript()
            ->appendFile($this->getScriptUrl('jquery.js'))
            ->appendFile($this->getScriptUrl('core.js'))
        ;

        $script =<<<SCRIPT_EOF
(function($){
  var messages = [];
  var timer = 0;

  var updateMessageList = function(data){
    if (data.error !== undefined) {
      alert(data.message);
      return;
    }
    var item = null;
    var shoutbox = $('#shoutbox');
    for (var i in data) {
      if (messages.length >= 50) {
        item = messages.shift();
        item.element.remove();
      }
      item = data[i];
      item.element = $(
        '<p class="message">'
        + '<span class="timestamp">'
        +   item.timestamp
        + '</span> '
        + '<span class="author">'
        +   item.author
        + '</span> '
        + item.message +
        '</p>'
        );
      item.element.appendTo(shoutbox);

      messages.push(item);
    }
    };

  var addMessage = function(message) {
    timer = (new Date()).getTime();

    $.ajax({
      url: "{$this->getBaseUrl('chat/send-message-ajax')}",
      type: 'post',
      data: {message: message},
      dataType: 'json',
      success: updateMessageList
      });
    };

  var loadMessageList = function() {
    timer = (new Date()).getTime();

    $.ajax({
      url: "{$this->getBaseUrl('chat/update-message-list-ajax')}",
      success: updateMessageList,
      dataType: 'json'
      });
    };

  $(document).ready(function(){
    var context = $('#chat');
    var message = $('input.message', context);
    var submit = $('input.submit', context);
    message.keydown(function(e){
      if (e.which === 13) {
        addMessage($(this).val());
        $(this).val('');
      }
      });
    submit.click(function(e){
        addMessage($('input.message', context).val());
        $('input.message', context).val('');
        });
    });

  var updater = function() {
    var now = (new Date()).getTime();
    if ((now - timer) > 2500) {
      loadMessageList();
    }
  }

  loadMessageList();
  setInterval(updater, 250);
})(jQuery);
SCRIPT_EOF;

        $this->headScript()
            ->appendScript($script)
        ;

        return $options;
    }

    public function setTransferSalt($salt)
    {
        $this->_form
            ->getSubform('login')
            ->getElement('salt')
            ->setValue($salt)
        ;

        return $this;
    }

    public function getTransferSalt()
    {
        return $this->_form
            ->getSubform('login')
            ->getElement('salt')
            ->getValue()
        ;
    }
}