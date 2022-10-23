<?php

namespace GraphAware\Reco4PHP\Demo\Github;

use GraphAware\Reco4PHP\Engine\BaseRecommendationEngine;

class RecommendationEngine extends BaseRecommendationEngine
{
    public function discoveryEngines(): array
    {
        return [
            new FollowedByFollowers(),
            new SameContribution(),
        ];
    }

    public function postProcessors(): array
    {
        return [
            new PenalizeTooMuchFollowers(),
        ];
    }

    public function name(): string
    {
        return 'github_who_to_follow';
    }
}
