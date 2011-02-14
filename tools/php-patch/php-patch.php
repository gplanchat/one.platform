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

define('VERSION', '1.1.0');

defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('PS') || define('PS', PATH_SEPARATOR);

/**
 * Patch management class
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
 * @subpackage  One_Core
 */
class Patcher
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
    const DEBUG  = 7;

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
     *
     * @var unknown_type
     */
    private $_newline = null;

    /**
     * TODO: PHPDoc
     *
     * @var unknown_type
     */
    private $_reportLevel = self::ERROR;

    /**
     * Constructor
     *
     * @param int $reportLevel
     */
    public function __construct($reportLevel = null)
    {
        if ($reportLevel !== null && is_int($reportLevel)) {
            $this->_reportLevel = $reportLevel;
        }
    }

    /**
     * TODO: PHPDoc
     */
    public function merge($patchFile, $path, $fuzz = 0, $write = true, $newline = self::NEWLINE_DEFAULT, $reverse = false)
    {
        $this->_loadPatch($patchFile, $fuzz);

        foreach ($this->_patchMeta as $fileData) {
            if ($fileData['filename'][0] === null) {
                $this->_addDebug(null, null, 'Creating file "%s".', $fileData['filename'][1]);
                $this->_sources[$fileData['filename'][0]] = array();
            } else if (!isset($this->_sources[$fileData['filename'][0]])) {
                $this->_sources[$fileData['filename'][0]] = $this->_readFile(realpath($path) . DS . $fileData['filename'][0]);
            }

            if ($fileData['filename'][1] === null) {
//                unlink(realpath($path) . DS . $fileData['filename'][0]);
                $this->_addDebug(null, null, 'File "%s" deleted.', $fileData['filename'][0]);
                continue;
            }
            $this->_destinations[$fileData['filename'][1]] = array();

            if (isset($this->_sources[$fileData['filename'][0]]) && is_array($this->_sources[$fileData['filename'][0]])) {
                $fileOutput = $this->_sources[$fileData['filename'][0]];
            } else {
                continue;
            }
//            $blockRejects = array();

            foreach ($fileData['blocks'] as $blockData) {
                $offset = $blockData['ranges'][0] - 1;
                $contextOffsets = 0;
                $lineOffsets = 0;

                foreach ($blockData['datas'] as $lineData) {
                    if (($lineData['type'] === self::LINE_ADD && !$reverse) || ($lineData['type'] === self::LINE_REMOVE && $reverse)) {
//                        if (isset($this->_sources[$fileData['filename'][0]]) && $lineData['line'] == $this->_sources[$fileData['filename'][0]][$offset]) {
//                            $this->_addWarning($fileData['filename'][0], $blockData['ranges'][2],
//                                'Block seems to exist already, does your patch have been already applied?');
//                            $blockRejects[$fileData['filename'][0]][] = $blockData;
//                            break;
//                        }
                        array_splice($fileOutput, $blockData['ranges'][2] + $lineOffsets + $contextOffsets - 1, 0, array($lineData['line']));
                        $lineOffsets++;
                    } else if (($lineData['type'] === self::LINE_REMOVE && !$reverse) || ($lineData['type'] === self::LINE_ADD && $reverse)) {
//                        if (isset($this->_sources[$fileData['filename'][0]]) && $lineData['line'] != $this->_sources[$fileData['filename'][0]][$offset]) {
//                            $this->_addWarning($fileData['filename'][0], $blockData['ranges'][2],
//                                'Unmatched block, skipped.');
//                            $blockRejects[$fileData['filename'][0]][] = $blockData;
//                            break;
//                        }
                        array_splice($fileOutput, $blockData['ranges'][2] + $lineOffsets + $contextOffsets - 1, 1);
                    } else if ($lineData['type'] === self::LINE_CONTEXT) {
//                        if (isset($this->_sources[$fileData['filename'][0]]) && $lineData['line'] != $this->_sources[$fileData['filename'][0]][$offset]) {
//                            $this->_addWarning($fileData['filename'][0], $blockData['ranges'][2],
//                                'Unmatched block, skipped.');
//                            $blockRejects[$fileData['filename'][0]][] = $blockData;
//                            break;
//                        }
                        $contextOffsets++;
                    } else if ($lineData['type'] === self::LINE_SYSTEM) {
                        $lineOffsets++;
                    }
                    $offset++;
                }
            }

            if (!is_dir(dirname($path . DS . $fileData['filename'][1]))) {
                $tmpPath = $path;
                $offsetStart = 0;
                $offsetEnd = 0;
                while (strlen($tmpPath) < 150) {
                    $offsetEnd = strpos($fileData['filename'][1], '/', $offsetStart + 1);
                    if ($offsetEnd == $offsetStart || $offsetEnd === false) {
                        break;
                    }
                    $tmpPath .= DS . substr($fileData['filename'][1], $offsetStart, $offsetEnd - $offsetStart);

                    if (!is_dir($tmpPath)) {
                        mkdir($tmpPath);
                    }
                    $offsetStart = $offsetEnd + 1;
                }
            }

            if (@file_put_contents($path . DS . $fileData['filename'][1], implode($newline, $fileOutput))) {
                $this->_addDebug(null, null, 'File "%s" updated.', $fileData['filename'][1]);
            } else {
                $this->_addError(null, null, 'File "%s" is not writable, skipping file.', $fileData['filename'][1]);
            }
        }

        return $this;
    }

    /**
     * TODO: PHPDoc
     */
    public function getNewline()
    {
        if ($this->_newline === null) {
            $this->_newline = self::NEWLINE_DEFAULT_REGEX;
        }
        return $this->_newline;
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
    protected function _writeSource($filename, $datas, $basePath = null)
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
     *
     * @param string $file
     * @param string $line
     * @param string $message
     * @param mixed $...
     *
     * @return One_Core_Model_Setup_Patch
     */
    protected function _addDebug($file, $line, $message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);
        array_shift($args);
        array_shift($args);

        return $this->_addMessage($file, $line, vsprintf($message, $args), self::DEBUG);
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
        if ($level > $this->_reportLevel) {
            return $this;
        }

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
    public function getMessages($type = null)
    {
        if (is_null($type)) {
            return $this->_messages;
        } else if (isset($this->_messages[$type])) {
            return $this->_messages[$type];
        }
        return null;
    }

    /**
     * TODO: PHPDoc
     */
    protected function _loadPatch($filename, $fuzz)
    {
        if (!empty($this->_patchData)) {
            return $this->_patchData;
        }

        if (!file_exists($filename) || !is_readable($filename)) {
            $this->_addError(null, null, 'Patch file "%s" does not exist or is not readable.', $filename);
            return false;
        }

        $patchBlocks = array();
        $patchData = $this->_readFile($filename);

        $patchBlock = null;
        $linesIterator = new ArrayIterator($patchData);
        $linesIterator->rewind();

        $this->_beginPatch();
        do {
            $offset = $linesIterator->key();
            if (substr($linesIterator->current(), 0, 4) === '--- ') {
                $this->_endBlock($patchData, $offset - 1);
                $this->_endFile($patchData, $offset - 1);

                $this->_beginFile($patchData, $offset, $fuzz);
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
    public function _beginFile($patchData, $line, $fuzz)
    {
        $offset = strpos($patchData[$line], "\t", 4);
        if ($offset === false) {
            $offset = strlen($patchData[$line]);
        }

        $filenameA = substr($patchData[$line], 4, $offset - 4);
        if ($filenameA === '/dev/null') {
            $filenameA = null;
        } else {
            for ($i = 0; $i < $fuzz; $i++) {
                $filenameA = substr($filenameA, strpos($filenameA, '/') + 1);
            }
        }

        $offset = strpos($patchData[$line + 1], "\t", 4);
        if ($offset === false) {
            $offset = strlen($patchData[$line + 1]);
        }

        $filenameB = substr($patchData[$line + 1], 4, $offset - 4);
        if ($filenameB === '/dev/null') {
            $filenameB = null;
        } else {
            for ($i = 0; $i < $fuzz; $i++) {
                $filenameB = substr($filenameB, strpos($filenameB, '/') + 1);
            }
        }

        $this->_patchMeta[$this->_patchFileId] = array(
            'filename' => array(
                $filenameA,
                $filenameB
                ),
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
            $this->_addNotice(null, $line, 'Line %d ignored: %s', $line, $patchData[$line]);

            return $this;
        }

        $this->_patchMeta[$this->_patchFileId]['blocks'][$this->_patchBlockId]['datas'][] = array(
            'type' => $lineType,
            'line' => (string) $lineData
            );

        return $this;
    }
}

// Begin frontend logic

try {
    if (!isset($_FILES) || empty($_FILES)) {
?>
<html>
  <head>
    <title>One.Platform PHPatch - version <?php echo htmlspecialchars(VERSION, ENT_QUOTES, 'UTF-8') ?></title>
    <style type="text/css">
/*<![CDATA[*/
h1,div.form {display:block;width:800px;margin:0 auto;text-align:center;}
/*]]>*/
    </style>
  </head>
  <body>
    <h1>One.Platform PHPatch</h1>
    <div class="form">
      <form action="#" method="post" enctype="multipart/form-data">
        <p>
          <label for="base-path">Enter here the base path where the patch will be applied (defaults to the current directory) : </label>
          <input type="text" id="base-path" name="path" size="50" value="<?php echo htmlspecialchars(dirname(__FILE__), ENT_QUOTES, 'UTF-8') ?>" />
        </p>
        <p>
          <label for="fuzz">Fuzz size</label>
          <input type="text" id="fuzz" name="fuzz" size="3" value="1" />
        </p>
        <p>
          <label for="patch">Enter here your Unix format patch to execute : </label>
          <input type="file" id="patch" name="patch" />
        </p>
        <p>
          <input type="submit" id="submit" />
        </p>
      </form>
    </div>
  </body>
</html>
<?php
    }

    if (isset($_FILES['patch'])) {
        if ($_FILES['patch']['error'] != 1) {
            $basePath = isset($_POST['path']) && !empty($_POST['path']) ? realpath($_POST['path']) : dirname(__FILE__);
            $basePath = $basePath !== false ? $basePath : dirname(__FILE__);

            $fuzz = isset($_POST['fuzz']) && !empty($_POST['fuzz']) ? intval($_POST['fuzz']) : 0;
            $patcher = new Patcher(Patcher::DEBUG);
            $patcher->merge($_FILES['patch']['tmp_name'], $basePath, $fuzz);

            $levels = array(
                0 => 'Emergency',
                1 => 'Alert',
                2 => 'Critical',
                3 => 'Errors',
                4 => 'Warnings',
                5 => 'Notices',
                6 => 'Info',
                7 => 'Debug'
                );
?>
<html>
  <head>
    <title>One.Platform PHPatch - version <?php echo htmlspecialchars(VERSION, ENT_QUOTES, 'UTF-8') ?></title>
    <style type="text/css">
/*<![CDATA[*/
h1,div.form {display:block;width:800px;margin:0 auto;text-align:center;}
/*]]>*/
    </style>
  </head>
  <body>
    <h1>One.Platform PHPatch</h1>
    <div class="error">
      <?php if (($leveledMessages = $patcher->getMessages())): ?>
      <h2>Patcher returned some messages</h2>
      <ul>
        <?php foreach ($leveledMessages as $level => $messageList): ?>
        <li>
          <p><?php echo htmlspecialchars($levels[(int) $level], ENT_QUOTES, 'UTF-8')?></p>
          <ul>
            <?php foreach ($messageList as $message): ?>
            <li><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8')?></li>
            <?php endforeach ?>
          </ul>
        </li>
        <?php endforeach ?>
      </ul>
      <?php endif ?>
    </div>
  </body>
</html>
<?php
        } else {
?>
<html>
  <head>
    <title>One.Platform PHPatch - version <?php echo htmlspecialchars(VERSION, ENT_QUOTES, 'UTF-8') ?></title>
    <style type="text/css">
/*<![CDATA[*/
h1,div.form {display:block;width:800px;margin:0 auto;text-align:center;}
/*]]>*/
    </style>
  </head>
  <body>
    <h1>One.Platform PHPatch</h1>
    <div class="error">
      <h2>An error occured during file transfer</h2>
      <p>
        An error occured during the file transfer, check the size of your file.
        PHP currently allows files smaller than <?php echo htmlspecialchars(ini_get('upload_max_filesize'), ENT_QUOTES, 'UTF-8') ?> bytes.
      </p>
    </div>
  </body>
</html>
<?php
            die('An error occured during the file transfer, check your file.');
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}