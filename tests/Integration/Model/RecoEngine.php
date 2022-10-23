<?php

namespace GraphAware\Reco4PHP\Tests\Integration\Model;

use GraphAware\Reco4PHP\Engine\BaseRecommendationEngine;

class RecoEngine extends BaseRecommendationEngine
{
    public function name(): string
    {
        return 'find_friends';
    }

    public function discoveryEngines(): array
    {
        return [
            new FriendsEngine(),
        ];
    }

    public function blacklistBuilders(): array
    {
        return [
            new SimpleBlacklist(),
        ];
    }
}
