<?php
/**
 *
 * @author Greg
 *
 */

/**
 * @since 0.1.4
 */
class One_Core_Block_Html_Notifications
    extends One_Core_Block_Html
{
    /**
     * TODO: PHPDoc
     *
     * @since 0.1.4
     *
     * @var One_Core_Model_SessionAbstract
     */
    protected $_session = null;

    public function _construct($options)
    {
        $sessionType = 'core/session';
        if (isset($options['session-type']) && !empty($options['session-type'])) {
            $sessionType = $options['session-type'];
            unset($options['session-type']);
        }
        parent::_construct($options);

        $this->_session = $this->app()
            ->getSingleton($sessionType);
    }

    public function getAllErrors()
    {
        return $this->_session->getAllErrors();
    }

    public function getAllWarnings()
    {
        return $this->_session->getAllWarnings();
    }

    public function getAllNotices()
    {
        return $this->_session->getAllNotices();
    }

    public function getAllInfos()
    {
        return $this->_session->getAllInfos();
    }
}