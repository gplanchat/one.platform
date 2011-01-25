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
 * Patch utility class
 *
 * @uses        One_Core_Object
 *
 * @access      public
 * @author      gplanchat
 * @category    Setup
 * @package     One
 * @subpackage  One_Core
 */
class One_Core_Setup_Model_Patch
    extends One_Core_Object
{
    /**
     * TODO: PHPDoc
     */
    const NEWLINE_UNIX_REGEX = "#(\n)#";

    /**
     * TODO: PHPDoc
     */
    const NEWLINE_WINDOWS_REGEX = "#(\r\n)#";

    /**
     * TODO: PHPDoc
     */
    const NEWLINE_MAC_REGEX = "#(\r)#";

    /**
     * TODO: PHPDoc
     */
    const NEWLINE_COMPAT_REGEX = "#(\r\n|\r|\n)#";

    /**
     * TODO: PHPDoc
     */
    const NEWLINE_DEFAULT_REGEX = self::NEWLINE_COMPAT_REGEX;

    /**
     * TODO: PHPDoc
     */
    const NEWLINE_UNIX = "\n";

    /**
     * TODO: PHPDoc
     */
    const NEWLINE_WINDOWS = "\r\n";

    /**
     * TODO: PHPDoc
     */
    const NEWLINE_MAC = "\r";

    /**
     * TODO: PHPDoc
     */
    const NEWLINE_SYSTEM = PHP_EOL;

    /**
     * TODO: PHPDoc
     */
    const NEWLINE_DEFAULT = self::NEWLINE_UNIX;

    const LINE_ADD     = '+';
    const LINE_REMOVE  = '-';
    const LINE_SYSTEM  = "\\";
    const LINE_CONTEXT = ' ';

    const ERROR  = 3;
    const WARN   = 4;
    const NOTICE = 5;

    /**
     * TODO: PHPDoc
     */
    private $_sources = array();

    /**
     * TODO: PHPDoc
     */
    private $_destinations = array();

    /**
     * TODO: PHPDoc
     */
    private $_messages = array();

    /**
     * TODO: PHPDoc
     *
     * @var unknown_type
     */
    private $_patchBlockId = 0;

    /**
     * TODO: PHPDoc
     *
     * @var unknown_type
     */
    private $_patchFileId = 0;

    /**
     * TODO: PHPDoc
     *
     * @var unknown_type
     */
    private $_patchData = array();

    /**
     * TODO: PHPDoc
     *
     * @var unknown_type
     */
    private $_patchMeta = array();

    /**
     * TODO: PHPDoc
     */
    protected function _construct($options)
    {
        return $this;
    }

    /**
     * TODO: PHPDoc
     */
    public function getNewline()
    {
        if (!$this->hasData('newline')) {
            $this->setData('newline', self::NEWLINE_DEFAULT_REGEX);
        }
        return $this->getData('newline');
    }

    /**
     * TODO: PHPDoc
     */
    public function merge($patchFile, $path, $write = true, $newline = self::NEWLINE_DEFAULT, $reverse = false)
    {
        $this->_loadPatch($patchFile);

        foreach ($this->_patchMeta as $fileData) {
            $this->_loadSource($fileData['filename'], $path);
            $this->_destinations[$fileData['filename']] = array();

            $fileOutput = isset($this->_sources[$fileData['filename']]) ? $this->_sources[$fileData['filename']] : array();
//            $blockRejects = array();

            foreach ($fileData['blocks'] as $blockData) {
                $offset = $blockData['ranges'][0] - 1;
                $contextOffsets = 0;
                $lineOffsets = 0;

                foreach ($blockData['datas'] as $lineData) {
                    if (($lineData['type'] === self::LINE_ADD && !$reverse) || ($lineData['type'] === self::LINE_REMOVE && $reverse)) {
//                        if (isset($this->_sources[$fileData['filename']]) && $lineData['line'] == $this->_sources[$fileData['filename']][$offset]) {
//                            $this->_addWarning($fileData['filename'], $blockData['ranges'][2],
//                                'Block seems to exist already, does your patch have been already applied?');
//                            $blockRejects[$fileData['filename']][] = $blockData;
//                            break;
//                        }
                        array_splice($fileOutput, $blockData['ranges'][2] + $lineOffsets + $contextOffsets - 1, 0, array($lineData['line']));
                        $lineOffsets++;
                    } else if (($lineData['type'] === self::LINE_REMOVE && !$reverse) || ($lineData['type'] === self::LINE_ADD && $reverse)) {
//                        if (isset($this->_sources[$fileData['filename']]) && $lineData['line'] != $this->_sources[$fileData['filename']][$offset]) {
//                            $this->_addWarning($fileData['filename'], $blockData['ranges'][2],
//                                'Unmatched block, skipped.');
//                            $blockRejects[$fileData['filename']][] = $blockData;
//                            break;
//                        }
                        array_splice($fileOutput, $blockData['ranges'][2] + $lineOffsets + $contextOffsets - 1, 1);
                    } else if ($lineData['type'] === self::LINE_CONTEXT) {
//                        if (isset($this->_sources[$fileData['filename']]) && $lineData['line'] != $this->_sources[$fileData['filename']][$offset]) {
//                            $this->_addWarning($fileData['filename'], $blockData['ranges'][2],
//                                'Unmatched block, skipped.');
//                            $blockRejects[$fileData['filename']][] = $blockData;
//                            break;
//                        }
                        $contextOffsets++;
                    } else if ($lineData['type'] === self::LINE_SYSTEM) {
                        $lineOffsets++;
                    }
                    $offset++;
                }
            }

            file_put_contents($path . DS . $fileData['filename'], implode($newline, $fileOutput));
        }

        return $this;
    }

    /**
     * TODO: PHPDoc
     */
    protected function _readFile($filename, $create = false)
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            if ($create === false) {
                $this->_addNotice(null, null, 'Source file "%s" does not exist or is not readable.', $filename);
                return false;
            } else {
                return false;
            }
        }

        //return preg_split($this->getNewline(), file_get_contents($filename), -1, PREG_SPLIT_DELIM_CAPTURE);
        return preg_split($this->getNewline(), file_get_contents($filename));
    }

    /**
     * TODO: PHPDoc
     */
    protected function _writeFile($filename, $datas)
    {
        if (!is_writeable($filename) || !$fd = @fopen($filename, 'w')) {
            $this->_addNotice(null, null, 'Source file "%s" is not writeable.', $filename);
            return false;
        }

        foreach ($datas as $line) {
            fwrite($fd, $line);
        }
        fclose($fd);

        return $this;
    }

    /**
     * TODO: PHPDoc
     */
    protected function _loadSource($filename, $basePath = NULL)
    {
        if (isset($this->_sources[$filename])) {
            return $this->_sources[$filename];
        }

        return $this->_sources[$filename] = $this->_readFile(realpath($basePath) . DS . $filename);
    }

    /**
     * TODO: PHPDoc
     */
    protected function _writeSource($filename, $datas, $basePath = NULL)
    {
        $this->_writeFile(realpath($basePath) . DS . $filename, $datas);

        return $this;
    }

    /**
     *
     * @param $line
     */
    private function _extractFilename($line)
    {
        $length = strpos($line, "\t", 4);
        if ($length === false) {
            $length = strlen($line);
        }
        return substr($line, 4, $length - 4);
    }

    /**
     *
     * @param string $file
     * @param string $line
     * @param string $message
     * @param mixed $...
     *
     * @return One_Core_Model_Setup_Patch
     */
    protected function _addError($file, $line, $message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);
        array_shift($args);
        array_shift($args);

        return $this->_addMessage($file, $line, vsprintf($message, $args), self::ERROR);
    }

    /**
     *
     * @param string $file
     * @param string $line
     * @param string $message
     * @param mixed $...
     *
     * @return One_Core_Model_Setup_Patch
     */
    protected function _addWarning($file, $line, $message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);
        array_shift($args);
        array_shift($args);

        return $this->_addMessage($file, $line, vsprintf($message, $args), self::WARN);
    }

    /**
     *
     * @param string $file
     * @param string $line
     * @param string $message
     * @param mixed $...
     *
     * @return One_Core_Model_Setup_Patch
     */
    protected function _addNotice($file, $line, $message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);
        array_shift($args);
        array_shift($args);

        return $this->_addMessage($file, $line, vsprintf($message, $args), self::NOTICE);
    }

    /**
     * @see Zend_Log
     *
     * @param string $file
     * @param int $line
     * @param string $message
     * @param int $level
     *
     * @return One_Core_Model_Setup_Patch
     */
    private function _addMessage($file, $line, $message, $level)
    {
        if (!isset($this->_messages[$level])) {
            $this->_messages[$level] = array();
        }
        if (!is_null($file)) {
            $this->_messages[$level][] = sprintf('In file "%s", line %d: %s', $file, $line, $message);
        } else {
            $this->_messages[$level][] = $message;
        }

        return $this;
    }

    /**
     * TODO: PHPDoc
     *
     * @param $type
     */
    public function getMessages($type = NULL)
    {
        if (is_null($type)) {
            return $this->_messages;
        } else if (isset($this->_messages[$type])) {
            return $this->_messages[$type];
        }
        return NULL;
    }

    /**
     * TODO: PHPDoc
     */
    protected function _loadPatch($filename)
    {
        if (!empty($this->_patchData)) {
            return $this->_patchData;
        }

        if (!file_exists($filename) || !is_readable($filename)) {
            $this->_addNotice(null, null, 'Patch file "%s" does not exist or is not readable.', $filename);
            return false;
        }

        $patchBlocks = array();
        $patchData = $this->_readFile($filename);

        $patchBlock = NULL;
        $linesIterator = new ArrayIterator($patchData);
        $linesIterator->rewind();

        $this->_beginPatch();
        do {
            $offset = $linesIterator->key();
            if (substr($linesIterator->current(), 0, 4) === '--- ') {
                $this->_endBlock($patchData, $offset - 1);
                $this->_endFile($patchData, $offset - 1);

                $this->_beginFile($patchData, $offset);
                $linesIterator->next();
            } else if (preg_match('/@@ -(\\d+)(?:,(\\d+))?\\s+\\+(\\d+)(?:,(\\d+))?\\s+@@/A', $linesIterator->current(), $matches)) {
                $this->_endBlock($patchData, $offset - 1);

                $ranges = array(
                    (int) $matches[1],
                    (int) (isset($matches[2]) ? $matches[2] : 1),
                    (int) $matches[3],
                    (int) (isset($matches[4]) ? $matches[4] : 1),
                    );

                $this->_beginBlock($patchData, $offset, $ranges);
            } else {
                $this->_readLine($patchData, $offset);
            }

            $linesIterator->next();
        } while ($linesIterator->valid());
        $this->_endBlock($patchData, $offset - 1);
        $this->_endFile($patchData, $offset - 1);
        $this->_endPatch();

        return $this;
    }

    /**
     * TODO: PHPDoc
     */
    public function _beginPatch()
    {
        return $this;
    }

    /**
     * TODO: PHPDoc
     */
    public function _endPatch()
    {
        return $this;
    }

    /**
     * TODO: PHPDoc
     */
    public function _beginFile($patchData, $line)
    {
        $offset = strpos($patchData[$line], "\t", 4);
        if ($offset === false) {
            $offset = strlen($patchData[$line]);
        }

        $this->_patchMeta[$this->_patchFileId] = array(
            'filename' => substr($patchData[$line], 4, $offset - 4),
            'blocks' => array()
            );

        return $this;
    }

    /**
     * TODO: PHPDoc
     */
    public function _endFile($patchData, $line)
    {
        if (isset($this->_patchMeta[$this->_patchFileId])) {
            $this->_patchFileId++;
            $this->_patchBlockId = 0;
        }

        return $this;
    }

    /**
     * TODO: PHPDoc
     */
    public function _beginBlock($patchData, $line, $ranges)
    {
        $this->_patchMeta[$this->_patchFileId]['blocks'][$this->_patchBlockId] = array(
            'ranges' => $ranges,
            'datas'  => array()
            );

        return $this;
    }

    /**
     * TODO: PHPDoc
     */
    public function _endBlock($patchData, $line)
    {
        if (isset($this->_patchMeta[$this->_patchFileId]['blocks'][$this->_patchBlockId])) {
            $this->_patchBlockId++;
        }

        return $this;
    }

    /**
     * TODO: PHPDoc
     */
    public function _readLine($patchData, $line)
    {
        $lineType = substr($patchData[$line], 0, 1);
        $lineData = substr($patchData[$line], 1);
        $lineTypeList = array(
            self::LINE_ADD,
            self::LINE_CONTEXT,
            self::LINE_REMOVE,
            self::LINE_SYSTEM
            );

        if (!in_array($lineType, $lineTypeList)) {
            $this->_addNotice(NULL, $line, 'Line ignored');

            return $this;
        }

        $this->_patchMeta[$this->_patchFileId]['blocks'][$this->_patchBlockId]['datas'][] = array(
            'type' => $lineType,
            'line' => (string) $lineData
            );

        return $this;
    }
}
