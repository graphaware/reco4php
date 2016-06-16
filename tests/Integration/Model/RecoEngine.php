<?php

namespace GraphAware\Reco4PHP\Tests\Integration\Model;

use GraphAware\Reco4PHP\Engine\BaseRecommendationEngine;
use GraphAware\Reco4PHP\Filter\ExcludeSelf;

class RecoEngine extends BaseRecommendationEngine
{
    public function name() : string
    {
        return 'find_friends';
    }

    public function discoveryEngines() : array
    {
        return array(
            new FriendsEngine()
        );
    }

    public function blacklistBuilders() : array
    {
        return array(
            new SimpleBlacklist()
        );
    }


}