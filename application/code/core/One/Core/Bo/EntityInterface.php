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
 * Base model entity object management interface
 *
 * @uses        One_Core_ObjectInterface
 *
 * @since 0.1.0
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
 * @subpackage  One_Core
 */
interface One_Core_Bo_EntityInterface
{
    /**
     * Set the entity identifier
     *
     * @since 0.1.0
     *
     * @param string $identifier
     * @return One_Core_Model_EntityAbstract
     */
    public function setId($identifier);

    /**
     * Get the entity identifier
     *
     * @since 0.1.0
     *
     * @return string
     */
    public function getId();

    /**
     * Set the primary field name
     *
     * @since 0.1.0
     *
     * @param string $fieldName
     * @return One_Core_Model_EntityAbstract
     */
    public function setIdFieldName($fieldName);

    /**
     * Get the primary field name
     *
     * @since 0.1.0
     *
     * @return string
     */
    public function getIdFieldName();

    /**
     * Load an entity, based on its identifier
     *
     * @since 0.1.0
     *
     * @final
     * @param mixed $identifier
     * @return One_Core_Model_EntityAbstract
     */
    public function load($identifier, $field = NULL);

    /**
     * Save the model into its static representation
     *
     * @param mixed $identifier
     * @return One_Core_Model_EntityAbstract
     */
    public function save();

    /**
     * Delete the entity
     *
     * @since 0.1.0
     *
     * @param mixed $identifier
     * @return One_Core_Model_EntityAbstract
     */
    public function delete();

    /**
     * Get the loading status flag
     *
     * @since 0.1.0
     *
     * @return bool
     */
    public function isLoaded($flag = NULL);

    /**
     * Get the saving status flag
     *
     * @since 0.1.0
     *
     * @return bool
     */
    public function isSaved($flag = NULL);

    /**
     * Get the deletion status flag
     *
     * @since 0.1.0
     *
     * @return bool
     */
    public function isDeleted($flag = NULL);
}
