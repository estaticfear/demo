<?php

namespace Cmat\Analytics\GA4;

use Cmat\Analytics\Abstracts\AnalyticsAbstract;
use Cmat\Analytics\GA4\Traits\CustomAcquisitionTrait;
use Cmat\Analytics\GA4\Traits\CustomDemographicsTrait;
use Cmat\Analytics\GA4\Traits\CustomEngagementTrait;
use Cmat\Analytics\GA4\Traits\CustomRetentionTrait;
use Cmat\Analytics\GA4\Traits\CustomTechTrait;
use Cmat\Analytics\GA4\Traits\DateRangeTrait;
use Cmat\Analytics\GA4\Traits\DimensionTrait;
use Cmat\Analytics\GA4\Traits\FilterByDimensionTrait;
use Cmat\Analytics\GA4\Traits\FilterByMetricTrait;
use Cmat\Analytics\GA4\Traits\MetricAggregationTrait;
use Cmat\Analytics\GA4\Traits\MetricTrait;
use Cmat\Analytics\GA4\Traits\OrderByDimensionTrait;
use Cmat\Analytics\GA4\Traits\OrderByMetricTrait;
use Cmat\Analytics\GA4\Traits\ResponseTrait;
use Cmat\Analytics\GA4\Traits\RowOperationTrait;
use Cmat\Analytics\Period;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Analytics extends AnalyticsAbstract
{
    use DateRangeTrait;
    use MetricTrait;
    use DimensionTrait;
    use OrderByMetricTrait;
    use OrderByDimensionTrait;
    use MetricAggregationTrait;
    use FilterByDimensionTrait;
    use FilterByMetricTrait;
    use RowOperationTrait;
    use CustomAcquisitionTrait;
    use CustomEngagementTrait;
    use CustomRetentionTrait;
    use CustomDemographicsTrait;
    use CustomTechTrait;
    use ResponseTrait;

    public array $orderBys = [];

    public function __construct(int $propertyId, string $credentials)
    {
        $this->propertyId = $propertyId;
        $this->credentials = $credentials;
    }

    public function getCredentials(): string
    {
        return $this->credentials;
    }

    public function getClient(): BetaAnalyticsDataClient
    {
        return new BetaAnalyticsDataClient([
            'credentials' => $this->getCredentials(),
        ]);
    }

    public function get(): AnalyticsResponse
    {
        $response = $this->getClient()->runReport([
            'property' => 'properties/' . $this->getPropertyId(),
            'dateRanges' => $this->dateRanges,
            'metrics' => $this->metrics,
            'dimensions' => $this->dimensions,
            'orderBys' => $this->orderBys,
            'metricAggregations' => $this->metricAggregations,
            'dimensionFilter' => $this->dimensionFilter,
            'metricFilter' => $this->metricFilter,
            'limit' => $this->limit,
            'offset' => $this->offset,
            'keepEmptyRows' => $this->keepEmptyRows,
        ]);

        return $this->formatResponse($response);
    }

    public function fetchMostVisitedPages(Period $period, int $maxResults = 20): Collection
    {
        return $this->dateRange($period)
            ->metrics('screenPageViews')
            ->dimensions('pageTitle', 'fullPageUrl')
            ->orderByMetricDesc('screenPageViews')
            ->limit($maxResults)
            ->get()
            ->table;
    }

    public function fetchTopReferrers(Period $period, int $maxResults = 20): Collection
    {
        return $this->dateRange($period)
            ->metrics('screenPageViews')
            ->dimensions('sessionSource')
            ->orderByMetricDesc('screenPageViews')
            ->limit($maxResults)
            ->get()
            ->table;
    }

    public function fetchTopBrowsers(Period $period, int $maxResults = 10): Collection
    {
        return $this->dateRange($period)
            ->metrics('sessions')
            ->dimensions('browser')
            ->orderByMetricDesc('sessions')
            ->get()
            ->table;
    }

    public function performQuery(Period $period, string $metrics, array $others = []): Collection
    {
        $metrics = str_replace('ga:', '', $metrics);
        $metrics = array_unique(explode(',', $metrics));
        $metrics = array_map(function ($item) {
            return trim($item);
        }, $metrics);

        $metrics = $this->validateMetrics($metrics);

        $query = $this
            ->dateRange($period)
            ->metrics($metrics);

        if ($dimensions = Arr::get($others, 'dimensions')) {
            $dimensions = str_replace('ga:', '', $dimensions);

            $query = $query->dimensions($dimensions);
        }

        return $query->get()->table;
    }
}
