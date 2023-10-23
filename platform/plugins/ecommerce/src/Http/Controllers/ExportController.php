<?php

namespace Cmat\Ecommerce\Http\Controllers;

use Assets;
use BaseHelper;
use Cmat\Base\Http\Controllers\BaseController;
use Cmat\Ecommerce\Exports\CsvProductExport;
use Cmat\Ecommerce\Repositories\Interfaces\ProductInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductVariationInterface;
use Maatwebsite\Excel\Excel;

class ExportController extends BaseController
{
    public function __construct(
        protected ProductInterface $productRepository,
        protected ProductVariationInterface $productVariationRepository
    ) {
    }

    public function products()
    {
        page_title()->setTitle(trans('plugins/ecommerce::export.products.name'));

        Assets::addScriptsDirectly(['vendor/core/plugins/ecommerce/js/export.js']);

        $totalProduct = $this->productRepository->count(['is_variation' => 0]);
        $totalVariation = $this->productVariationRepository
            ->getModel()
            ->whereHas('product')
            ->whereHas('configurableProduct', function ($query) {
                $query->where('is_variation', 0);
            })
            ->count();

        return view('plugins/ecommerce::export.products', compact('totalProduct', 'totalVariation'));
    }

    public function exportProducts()
    {
        BaseHelper::maximumExecutionTimeAndMemoryLimit();

        return (new CsvProductExport())->download('export_products.csv', Excel::CSV, ['Content-Type' => 'text/csv']);
    }
}
