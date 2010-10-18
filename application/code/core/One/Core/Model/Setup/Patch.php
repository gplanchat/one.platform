<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-2010, Grégory PLANCHAT <g.planchat at gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing XNova.
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
class One_Core_Model_Setup_Patch
    extends One_Core_Object
{
    /**
     * TODO: PHPDoc
     */
    const NEWLINE_UNIX = "#(\n)#";

    /**
     * TODO: PHPDoc
     */
    const NEWLINE_WINDOWS = "#(\r\n)#";

    /**
     * TODO: PHPDoc
     */
    const NEWLINE_MAC = "#(\r)#";

    /**
     * TODO: PHPDoc
     */
    const NEWLINE_COMPAT = "#(\r\n|\r|\n)#";

    /**
     * TODO: PHPDoc
     */
    const NEWLINE_DEFAULT = self::NEWLINE_COMPAT;

    const LINE_ADD     = '+';
    const LINE_REMOVE  = '-';
    const LINE_SYSTEM  = "\\";
    const LINE_CONTEXT = ' ';

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
    protected function _construct()
    {
        return $this;
    }

    /**
     * TODO: PHPDoc
     */
    public function getNewline()
    {
        if (!$this->hasData('newline')) {
            $this->setData('newline', self::NEWLINE_DEFAULT);
        }
        return $this->getData('newline');
    }

    /**
     * TODO: PHPDoc
     */
    public function merge($patchFile, $path, $prefix = null, $reverse = false)
    {
        $this->_loadPatch($patchFile);

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

        return $this->_addMessage($file, $line, vsprintf($message, $args), Zend_Log::ERROR);
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

        return $this->_addMessage($file, $line, vsprintf($message, $args), Zend_Log::WARN);
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

        return $this->_addMessage($file, $line, vsprintf($message, $args), Zend_Log::NOTICE);
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
            var_dump($linesIterator->current());

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



    public function __test($patch)
    {
        $this->_loadPatch($patch);
        $root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . DS;

        foreach ($this->_patchMeta as $file) {
            $this->_loadSource($file['filename'], $root);
            $this->_destinations[$file['filename']] = array();

            $fileOutput = isset($this->_sources[$file['filename']]) ? $this->_sources[$file['filename']] : array();

            $blockOffsets = 0;
            $contextOffsets = 0;
            foreach ($file['blocks'] as $block) {
                $offset = $block['ranges'][0] - 1;

                foreach ($block['datas'] as $line) {
                    if ($line['type'] === self::LINE_ADD) {
                        if (isset($this->_sources[$file['filename']]) && $line['line'] == $this->_sources[$file['filename']][$offset]) {
                            $this->_addWarning($file['filename'], $block['ranges'][2],
                                'Block seems to exist already, does your patch have been already applied?');
                            break;
                        }
                        $fullOffset = $blockOffsets + $contextOffsets + ($block['ranges'][0] - 1);
                        array_splice($fileOutput, $fullOffset, 0, array((string) $line['line']));
                        $blockOffsets++;
                    } else if ($line['type'] === self::LINE_REMOVE) {
                        if (isset($this->_sources[$file['filename']]) && $line['line'] != $this->_sources[$file['filename']][$offset]) {
                            $this->_addWarning($file['filename'], $block['ranges'][2],
                                'Unmatched block, skipped.');
                            break;
                        }
                        array_splice($fileOutput, $blockOffsets + $block['ranges'][0], 1);
                        $blockOffsets--;
                        $offset++;
                    } else if ($line['type'] === self::LINE_CONTEXT) {
                        if (isset($this->_sources[$file['filename']]) && $line['line'] != $this->_sources[$file['filename']][$offset]) {
                            $this->_addWarning($file['filename'], $block['ranges'][2],
                                'Unmatched block, skipped.');
                            //break;
                        }
                        $contextOffsets++;
                        $offset++;
                    } else if ($line['type'] === self::LINE_SYSTEM) {
                        array_splice($fileOutput, $blockOffsets + $block['ranges'][0], 0, array(''));
                        $blockOffsets++;
                    }
                }
            }
            //$this->_writeSource($file['filename'], $root, $fileOutput);
            var_dump($fileOutput);
        }
        var_dump($this->getMessages());
    }














	function _apply_diff(&$lines, $src, $dst, $src_line, $src_size, $dst_line, $dst_size) {
		$src_line--;
		$dst_line--;
		$line = next($lines);
		if ($line === false) {
			$this->msg = 'Unexpected end of file';
			return false;
		}
		$source = array();		// source lines (old file)
		$destin = array();		// new lines (new file)
		$src_left = $src_size;
		$dst_left = $dst_size;
		do {
			if (!isset($line{0})) {
				$source[] = '';
				$destin[] = '';
				$src_left--;
				$dst_left--;
				continue;
			}
			if ($line{0}=='-') {
				if ($src_left==0) {
					$this->msg = sprintf('Unexpected remove line at line %d', key($lines));
					return false;
				}
				$source[] = substr($line, 1);
				$src_left--;
			} else if ($line{0}=='+') {
				if ($dst_left==0) {
					$this->msg = sprintf('Unexpected add line at line %d', key($lines));
					return false;
				}
				$destin[] = substr($line, 1);
				$dst_left--;
			} else {
				if (!isset($line{1}))
					$line = '';
				else if ($line{0}=='\\') {
					if ($line=='\\ No newline at end of file')
						continue;
				} else
					$line = substr($line, 1);
				$source[] = $line;
				$destin[] = $line;
				$src_left--;
				$dst_left--;
			}

			if (($src_left==0) && ($dst_left==0)) {
				// now apply the patch, finally!
				if ($src_size>0) {
					$src_lines =& $this->_get_source($src);
					if (!isset($src_lines))
						return false;
				}
				if ($dst_size>0) {
					if ($src_size>0) {
						$dst_lines =& $this->_get_destin($dst, $src);
						if (!isset($dst_lines))
							return false;
						$src_bottom=$src_line+count($source);
						$dst_bottom=$dst_line+count($destin);

						for ($l=$src_line;$l<$src_bottom;$l++) {
							if ($src_lines[$l]!=$source[$l-$src_line]) {
								$this->msg = sprintf('Failed source verification of file %s at line %d', $src, $l);
								return false;
							}
						}
						array_splice($dst_lines, $dst_line, count($source), $destin);
					} else
						$this->destinations[$dst] = $destin;
				} else
					$this->removals[] = $src;

				return true;
			}
		} while (FALSE !== ($line = next($lines)));

		$this->msg = 'Unexpected end of file';
		return false;
	}

	function _Diff(&$latest, &$udiff) {
		$this->msg = 'Not available';
		return false;
	}

}
