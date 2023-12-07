<?php

namespace Rmunate\SqlServerLite\Support;

trait Deprecated
{
    private $noCount; 

    /**
     * Enable NOCOUNT mode for the PDO connection.
     *
     * Note: This method is deprecated and will be removed in a future major version.
     * Please use the setDirectQuery() method instead.
     *
     * @return $this Returns the current instance of the object.
     */
    public function noCount($status = "ON")
    {
        $this->noCount = ($status === "ON" ||  $status === true) ? "SET NOCOUNT ON;" : "SET NOCOUNT OFF;";

        return $this;
    }

}
