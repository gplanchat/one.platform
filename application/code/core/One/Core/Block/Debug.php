<?php

class One_Core_Block_Debug
    extends One_core_Block_Html
{
    public function getDatabaseProfilers()
    {
        $connectionList = $this->app()
            ->getSingleton('core/database.connection.pool')
            ->getConnectionList()
        ;

        $profilers = array();
        foreach ($connectionList as $connectionName => $connection) {
            $profiler = $connection->getProfiler();
            if ($profiler->getEnabled() !== true) {
                continue;
            }

            $profilers[$connectionName] = $connection->getProfiler();
        }

        return $profilers;
    }

    public function getTemplate()
    {
        if ($this->_template === null) {
            $this->_template = 'debug.phtml';
        }
        return $this->_template;
    }
}
