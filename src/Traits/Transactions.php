<?php

namespace Rmunate\SqlServerLite\Traits;

use Rmunate\SqlServerLite\Singleton\SQLServerSingleton;

trait Transactions
{
    public static function beginTransaction()
    {
        SQLServerSingleton::beginTransaction();
    }

    public static function commit()
    {
        SQLServerSingleton::commit();
    }

    public static function rollback()
    {
        SQLServerSingleton::rollback();
    }
}
