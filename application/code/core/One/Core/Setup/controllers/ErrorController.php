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
 * Setup error controller
 *
 * @uses        One_Core_ControllerAbstract
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
 * @subpackage  One_Core
 */
class One_Core_Setup_ErrorController
    extends One_Core_ControllerAbstract
{
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');        $exception = $errors->exception;

        switch ($errors->type) {
        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
            $this->_forward('not-found');            break;
        default:
            $this->getResponse()->setRawHeader('HTTP/1.1 500 Internal Server Error');
            $logger = new Zend_Log(new Zend_Log_Writer_Stream(
                APPLICATION_PATH . DS . 'var' . DS . 'log' . DS . 'exception.log'
                ));
            $message = $exception->getMessage() . PHP_EOL . $exception->getTraceAsString();
            $logger->debug($message);

            $errors = $this->_getParam('error_handler');
            $this->getResponse()->setBody($errors->exception->getMessage());
            break;
        }
    }

    public function notFoundAction()
    {
        $errors = $this->_getParam('error_handler');
        $body =<<<HTML_EOF
<html>
<head>
  <title>One.Platform Setup :: Page not found</title>
</head>
<body>
  <h1>Not Found</h1>
  <div>
    <p>The page you requested could not be found.</p>
    <p>The system returned the following error: <q>{$errors->exception->getMessage()}</q></p>
  </div>
  <hr />
  <div>Powered by One.Platform</div>
</body>
</html>
HTML_EOF;
        $this->getResponse()->setBody($body);

        $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
    }
}