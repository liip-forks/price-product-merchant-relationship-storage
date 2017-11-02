<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor;

use ArrayObject;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Generated\Shared\Transfer\FacetSearchResultTransfer;
use Generated\Shared\Transfer\FacetSearchResultValueTransfer;

class CategoryExtractor implements AggregationExtractorInterface
{
    const DOC_COUNT = 'doc_count';
    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected $facetConfigTransfer;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer)
    {
        $this->facetConfigTransfer = $facetConfigTransfer;
    }

    /**
     * @param array $aggregations
     * @param array $requestParameters
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function extractDataFromAggregations(array $aggregations, array $requestParameters)
    {
        $parameterName = $this->facetConfigTransfer->getParameterName();

        $facetResultValueTransfers = $this->extractFacetData($aggregations);
        $totalDocCount = $facetResultValueTransfers[static::DOC_COUNT];
        unset($facetResultValueTransfers[static::DOC_COUNT]);

        $facetResultTransfer = new FacetSearchResultTransfer();
        $facetResultTransfer
            ->setName($parameterName)
            ->setValues($facetResultValueTransfers)
            ->setConfig(clone $this->facetConfigTransfer)
            ->setDocCount($totalDocCount);

        if (isset($requestParameters[$parameterName])) {
            $facetResultTransfer->setActiveValue($requestParameters[$parameterName]);
        }

        return $facetResultTransfer;
    }

    /**
     * @param array $aggregation
     *
     * @return \ArrayObject
     */
    protected function extractFacetData(array $aggregation)
    {
        $facetValues = new ArrayObject();
        $totalDocCount = 0;
        foreach ($aggregation['buckets'] as $bucket) {
            $facetResultValueTransfer = new FacetSearchResultValueTransfer();
            $facetResultValueTransfer
                ->setValue($bucket['key'])
                ->setDocCount($bucket[static::DOC_COUNT]);

            $facetValues->append($facetResultValueTransfer);
            $totalDocCount += $bucket[static::DOC_COUNT];
        }

        $facetValues[static::DOC_COUNT] = $totalDocCount;

        return $facetValues;
    }
}
