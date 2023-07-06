<?php

namespace Rmunate\SqlServerLite\Traits;

use PDO;

trait Transaction
{
    /**
     * Do not automatically save changes.
     *
     * @return $this
     */
    public function beginTransaction()
    {
        $this->connectionPDO();
        $this->PDO->beginTransaction();
        return $this;
    }

    /**
     * Save changes.
     *
     * @return $this
     */
    public function commit()
    {
        $this->PDO->commit();
        return $this;
    }

    /**
     * Not Save changes.
     *
     * @return $this
     */
    public function rollback()
    {
        $this->PDO->rollback();
        return $this;
    }


}
