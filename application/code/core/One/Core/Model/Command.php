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
 * Console command adapter
 *
 * @access      public
 * @author      gplanchat
 * @category    Exception
 * @package     One_Core
 * @subpackage  One_Core_Setup
 */
class One_Core_Model_Command
    implements One_Core_ObjectInterface
{
    protected $_executable = null;

    protected $_workingDirectory = null;

    protected $_extraParams = array();

    protected $_app = null;

    protected $_module = null;

    public function __construct($moduleName, Array $options = array(), One_Core_Model_Application $app = null)
    {
        $this->_app = $app;
        $this->_module = $moduleName;

        $this->_construct($options);
    }

    protected function _construct($options)
    {
        if (isset($options['command']) && $options['command'] !== null) {
            $this->_executable = escapeshellcmd($options['command']);
            unset($options['command']);
        }
        if (!isset($options['working-directory']) || $options['working-directory'] === null) {
            $this->_workingDirectory = realpath(getcwd());
        } else {
            $this->_workingDirectory = realpath($options['working-directory']);
            unset($options['working-directory']);
        }
    }

    public function app()
    {
        return $this->_app;
    }

    protected function _beforeExecCommand()
    {
    }

    protected function _afterExecCommand()
    {
    }

    protected function _execCommand($command)
    {
        $this->_beforeExecCommand();
        $descriptorSpec = array(
            0 => array('pipe', 'r'),
            1 => array('pipe', 'w'),
            2 => array('pipe', 'w')
            );
        $process = proc_open($command, $descriptorSpec, $pipes, $this->_workingDirectory, array_merge($_SERVER, $_ENV));
        if (is_resource($process)) {
            fclose($pipes[0]);

            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            $return = proc_close($process);
        } else {
            $this->app()->throwException('core/command-error', 'Could not open new process.');
        }

        $this->_afterExecCommand();

        if ($return !== 0) {
            $this->app()->throwException('core/command-error', $error);
        }

        return $output;
    }

    public function __call($method, $params)
    {
        $command = sprintf('%s %s', escapeshellcmd($this->_executable), escapeshellarg($method));

        foreach ($this->_extraParams as $param) {
            $command .= ' ' . escapeshellarg($param);
        }
        foreach ($params as $param) {
            $command .= ' ' . escapeshellarg($param);
        }

        return $this->_execCommand($command);
    }

    public function setWorkingDirectory($workingdirectory)
    {
        $this->_workingDirectory = $workingdirectory;
        return $this;
    }
}