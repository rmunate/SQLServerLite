<?php

namespace Rmunate\SqlServerLite\Traits;

use Rmunate\SqlServerLite\Singleton\SQLServerSingleton;

trait Transactions
{
    /**
     * set propierty beginTransaction and can do directly
     * @return bool
     */
    public static function beginTransaction()
    {
        SQLServerSingleton::beginTransaction();
    }

    /**
     * set propierty commit and can do directly
     * @return bool
     */
    public static function commit()
    {
        SQLServerSingleton::commit();
    }

    /**
     * set propierty rollback and can do directly
     * @return bool
     */
    public static function rollback()
    {
        SQLServerSingleton::rollback();
    }
}
