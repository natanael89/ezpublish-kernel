<?php

/**
 * File containing the ModifiedBetween criterion visitor class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace eZ\Publish\Core\Search\Elasticsearch\Content\CriterionVisitor\DateMetadata;

use eZ\Publish\API\Repository\Values\Content\Query\CriterionInterface;
use eZ\Publish\Core\Search\Elasticsearch\Content\CriterionVisitorDispatcher as Dispatcher;
use eZ\Publish\Core\Search\Elasticsearch\Content\CriterionVisitor\DateMetadata;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Operator;

/**
 * Visits the PublishedIn DateMetadata criterion.
 */
class PublishedIn extends DateMetadata
{
    /**
     * Check if visitor is applicable to current criterion.
     *
     * @param CriterionInterface $criterion
     *
     * @return bool
     */
    public function canVisit(CriterionInterface $criterion)
    {
        return
            $criterion instanceof Criterion\Matcher\DateMetadata &&
            $criterion->target === 'created' &&
            (
                ($criterion->operator ?: Operator::IN) === Operator::IN ||
                $criterion->operator === Operator::EQ
            );
    }

    /**
     * Map field value to a proper Elasticsearch filter representation.
     *
     * @param CriterionInterface $criterion
     * @param \eZ\Publish\Core\Search\Elasticsearch\Content\CriterionVisitorDispatcher $dispatcher
     * @param array $languageFilter
     *
     * @return mixed
     */
    public function visitFilter(CriterionInterface $criterion, Dispatcher $dispatcher, array $languageFilter)
    {
        /** @var Criterion\Matcher\DateMetadata $criterion */
        if (count($criterion->value) > 1) {
            $that = $this;
            $filter = array(
                'terms' => array(
                    'published_dt' => array_map(
                        function ($timestamp) use ($that) {
                            return $that->getNativeTime($timestamp);
                        },
                        $criterion->value
                    ),
                ),
            );
        } else {
            $filter = array(
                'term' => array(
                    'published_dt' => $this->getNativeTime($criterion->value),
                ),
            );
        }

        return $filter;
    }
}
