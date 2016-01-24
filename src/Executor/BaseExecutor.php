<?php

namespace GraphAware\Reco4PHP\Executor;

use GraphAware\Reco4PHP\Persistence\DatabaseService;

class BaseExecutor
{
    protected $databaseService;

    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }
}
