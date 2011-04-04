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
 * CMS Gaget display block
 *
 * @access      public
 * @author      gplanchat
 * @category    Cms
 * @package     One_Cms
 * @subpackage  One_Cms
 */
class One_Cms_Block_Gadget
    extends One_Core_BlockAbstract
{
    protected $_model = null;

    protected function _construct($options)
    {
        if (isset($options['identifier'])) {
            $this->setGagetId($options['identifier']);
            unset($options['identifier']);
        }
        parent::_construct($options);

        $this->_model = $this->app()
            ->getSingleton('cms/gadget')
        ;

        return $options;
    }

    protected function _render()
    {
        $gadgetId = $this->getGadgetId();
        try {
            if (is_int($gagetId)) {
                $this->_model
                    ->load($gadgetId);
            } else {
                $this->_model
                    ->load(array(
                        'identifier' => $gadgetId,
                        'website_id' => $this->app()->getWebsiteId()
                    ));
            }
        } catch (One_Core_Exception $e) {
            return 'CMS rendering error.';
        }

        return $this->app()
            ->getSingleton('cms/template')
            ->render($this->getLayout(), $this->_model->getContent(),
                array_merge($this->getData(), $this->_model->getData()))
        ;
    }
}