<?php

namespace Rmunate\SqlServerLite\Traits;

use Rmunate\SqlServerLite\Singleton\SQLServerSingleton;

trait Transactions
{
    /**
     * Set property beginTransaction and can be executed directly.
     *
     * @return void
     */
    public static function beginTransaction()
    {
        SQLServerSingleton::beginTransaction();
    }

    /**
     * Set property commit and can be executed directly.
     *
     * @return void
     */
    public static function commit()
    {
        SQLServerSingleton::commit();
    }

    /**
     * Set property rollback and can be executed directly.
     *
     * @return void
     */
    public static function rollback()
    {
        SQLServerSingleton::rollback();
    }
}
