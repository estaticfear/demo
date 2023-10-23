<?php

namespace Cmat\Ecommerce\Providers;

use ApiHelper;
use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Cmat\Ecommerce\Commands\SendAbandonedCartsEmailCommand;
use Cmat\Ecommerce\Facades\CartFacade;
use Cmat\Ecommerce\Facades\CurrencyFacade;
use Cmat\Ecommerce\Facades\EcommerceHelperFacade;
use Cmat\Ecommerce\Facades\InvoiceHelperFacade;
use Cmat\Ecommerce\Facades\OrderHelperFacade;
use Cmat\Ecommerce\Facades\OrderReturnHelperFacade;
use Cmat\Ecommerce\Facades\ProductCategoryHelperFacade;
use Cmat\Ecommerce\Http\Middleware\CaptureFootprintsMiddleware;
use Cmat\Ecommerce\Http\Middleware\RedirectIfCustomer;
use Cmat\Ecommerce\Http\Middleware\RedirectIfNotCustomer;
use Cmat\Ecommerce\Models\Address;
use Cmat\Ecommerce\Models\Brand;
use Cmat\Ecommerce\Models\Currency;
use Cmat\Ecommerce\Models\Customer;
use Cmat\Ecommerce\Models\Discount;
use Cmat\Ecommerce\Models\FlashSale;
use Cmat\Ecommerce\Models\GlobalOption;
use Cmat\Ecommerce\Models\GlobalOptionValue;
use Cmat\Ecommerce\Models\GroupedProduct;
use Cmat\Ecommerce\Models\Invoice;
use Cmat\Ecommerce\Models\Option;
use Cmat\Ecommerce\Models\OptionValue;
use Cmat\Ecommerce\Models\Order;
use Cmat\Ecommerce\Models\OrderAddress;
use Cmat\Ecommerce\Models\OrderHistory;
use Cmat\Ecommerce\Models\OrderProduct;
use Cmat\Ecommerce\Models\OrderReturn;
use Cmat\Ecommerce\Models\OrderReturnItem;
use Cmat\Ecommerce\Models\Product;
use Cmat\Ecommerce\Models\ProductAttribute;
use Cmat\Ecommerce\Models\ProductAttributeSet;
use Cmat\Ecommerce\Models\ProductCategory;
use Cmat\Ecommerce\Models\ProductCollection;
use Cmat\Ecommerce\Models\ProductLabel;
use Cmat\Ecommerce\Models\ProductTag;
use Cmat\Ecommerce\Models\ProductVariation;
use Cmat\Ecommerce\Models\ProductVariationItem;
use Cmat\Ecommerce\Models\Review;
use Cmat\Ecommerce\Models\Shipment;
use Cmat\Ecommerce\Models\ShipmentHistory;
use Cmat\Ecommerce\Models\Shipping;
use Cmat\Ecommerce\Models\ShippingRule;
use Cmat\Ecommerce\Models\ShippingRuleItem;
use Cmat\Ecommerce\Models\StoreLocator;
use Cmat\Ecommerce\Models\Tax;
use Cmat\Ecommerce\Models\Wishlist;
use Cmat\Ecommerce\Repositories\Caches\AddressCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\BrandCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\CurrencyCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\CustomerCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\DiscountCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\FlashSaleCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\GlobalOptionCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\GroupedProductCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\InvoiceCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\OrderAddressCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\OrderCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\OrderHistoryCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\OrderProductCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\OrderReturnCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\OrderReturnItemCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\ProductAttributeCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\ProductAttributeSetCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\ProductCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\ProductCategoryCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\ProductCollectionCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\ProductLabelCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\ProductTagCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\ProductVariationCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\ProductVariationItemCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\ReviewCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\ShipmentCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\ShipmentHistoryCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\ShippingCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\ShippingRuleCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\ShippingRuleItemCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\StoreLocatorCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\TaxCacheDecorator;
use Cmat\Ecommerce\Repositories\Caches\WishlistCacheDecorator;
use Cmat\Ecommerce\Repositories\Eloquent\AddressRepository;
use Cmat\Ecommerce\Repositories\Eloquent\BrandRepository;
use Cmat\Ecommerce\Repositories\Eloquent\CurrencyRepository;
use Cmat\Ecommerce\Repositories\Eloquent\CustomerRepository;
use Cmat\Ecommerce\Repositories\Eloquent\DiscountRepository;
use Cmat\Ecommerce\Repositories\Eloquent\FlashSaleRepository;
use Cmat\Ecommerce\Repositories\Eloquent\GlobalOptionRepository;
use Cmat\Ecommerce\Repositories\Eloquent\GroupedProductRepository;
use Cmat\Ecommerce\Repositories\Eloquent\InvoiceRepository;
use Cmat\Ecommerce\Repositories\Eloquent\OrderAddressRepository;
use Cmat\Ecommerce\Repositories\Eloquent\OrderHistoryRepository;
use Cmat\Ecommerce\Repositories\Eloquent\OrderProductRepository;
use Cmat\Ecommerce\Repositories\Eloquent\OrderRepository;
use Cmat\Ecommerce\Repositories\Eloquent\OrderReturnItemRepository;
use Cmat\Ecommerce\Repositories\Eloquent\OrderReturnRepository;
use Cmat\Ecommerce\Repositories\Eloquent\ProductAttributeRepository;
use Cmat\Ecommerce\Repositories\Eloquent\ProductAttributeSetRepository;
use Cmat\Ecommerce\Repositories\Eloquent\ProductCategoryRepository;
use Cmat\Ecommerce\Repositories\Eloquent\ProductCollectionRepository;
use Cmat\Ecommerce\Repositories\Eloquent\ProductLabelRepository;
use Cmat\Ecommerce\Repositories\Eloquent\ProductRepository;
use Cmat\Ecommerce\Repositories\Eloquent\ProductTagRepository;
use Cmat\Ecommerce\Repositories\Eloquent\ProductVariationItemRepository;
use Cmat\Ecommerce\Repositories\Eloquent\ProductVariationRepository;
use Cmat\Ecommerce\Repositories\Eloquent\ReviewRepository;
use Cmat\Ecommerce\Repositories\Eloquent\ShipmentHistoryRepository;
use Cmat\Ecommerce\Repositories\Eloquent\ShipmentRepository;
use Cmat\Ecommerce\Repositories\Eloquent\ShippingRepository;
use Cmat\Ecommerce\Repositories\Eloquent\ShippingRuleItemRepository;
use Cmat\Ecommerce\Repositories\Eloquent\ShippingRuleRepository;
use Cmat\Ecommerce\Repositories\Eloquent\StoreLocatorRepository;
use Cmat\Ecommerce\Repositories\Eloquent\TaxRepository;
use Cmat\Ecommerce\Repositories\Eloquent\WishlistRepository;
use Cmat\Ecommerce\Repositories\Interfaces\AddressInterface;
use Cmat\Ecommerce\Repositories\Interfaces\BrandInterface;
use Cmat\Ecommerce\Repositories\Interfaces\CurrencyInterface;
use Cmat\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Cmat\Ecommerce\Repositories\Interfaces\DiscountInterface;
use Cmat\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Cmat\Ecommerce\Repositories\Interfaces\GlobalOptionInterface;
use Cmat\Ecommerce\Repositories\Interfaces\GroupedProductInterface;
use Cmat\Ecommerce\Repositories\Interfaces\InvoiceInterface;
use Cmat\Ecommerce\Repositories\Interfaces\OrderAddressInterface;
use Cmat\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Cmat\Ecommerce\Repositories\Interfaces\OrderInterface;
use Cmat\Ecommerce\Repositories\Interfaces\OrderProductInterface;
use Cmat\Ecommerce\Repositories\Interfaces\OrderReturnInterface;
use Cmat\Ecommerce\Repositories\Interfaces\OrderReturnItemInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductAttributeInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductAttributeSetInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductCollectionInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductLabelInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductTagInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductVariationInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductVariationItemInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ShipmentHistoryInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ShipmentInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ShippingInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ShippingRuleInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ShippingRuleItemInterface;
use Cmat\Ecommerce\Repositories\Interfaces\StoreLocatorInterface;
use Cmat\Ecommerce\Repositories\Interfaces\TaxInterface;
use Cmat\Ecommerce\Repositories\Interfaces\WishlistInterface;
use Cmat\Ecommerce\Services\Footprints\Footprinter;
use Cmat\Ecommerce\Services\Footprints\FootprinterInterface;
use Cmat\Ecommerce\Services\Footprints\TrackingFilter;
use Cmat\Ecommerce\Services\Footprints\TrackingFilterInterface;
use Cmat\Ecommerce\Services\Footprints\TrackingLogger;
use Cmat\Ecommerce\Services\Footprints\TrackingLoggerInterface;
use Cmat\Ecommerce\Services\HandleApplyCouponService;
use Cmat\Ecommerce\Services\HandleRemoveCouponService;
use Cmat\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Cmat\Payment\Models\Payment;
use Cart;
use EcommerceHelper;
use EmailHandler;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Http\Request;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
// use SeoHelper;
// use SiteMapManager;
// use SlugHelper;
use SocialService;

class EcommerceServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        config([
            'auth.guards.customer' => [
                'driver' => 'session',
                'provider' => 'customers',
            ],
            'auth.providers.customers' => [
                'driver' => 'eloquent',
                'model' => Customer::class,
            ],
            'auth.passwords.customers' => [
                'provider' => 'customers',
                'table' => 'ec_customer_password_resets',
                'expire' => 60,
            ],
        ]);

        /**
         * @var Router $router
         */
        $router = $this->app['router'];

        $router->aliasMiddleware('customer', RedirectIfNotCustomer::class);
        $router->aliasMiddleware('customer.guest', RedirectIfCustomer::class);
        $router->pushMiddlewareToGroup('web', CaptureFootprintsMiddleware::class);

        $this->app->bind(ProductInterface::class, function () {
            return new ProductCacheDecorator(
                new ProductRepository(new Product())
            );
        });

        $this->app->bind(ProductCategoryInterface::class, function () {
            return new ProductCategoryCacheDecorator(
                new ProductCategoryRepository(new ProductCategory())
            );
        });

        $this->app->bind(ProductTagInterface::class, function () {
            return new ProductTagCacheDecorator(
                new ProductTagRepository(new ProductTag())
            );
        });

        $this->app->bind(GlobalOptionInterface::class, function () {
            return new GlobalOptionCacheDecorator(
                new GlobalOptionRepository(new GlobalOption())
            );
        });

        $this->app->bind(BrandInterface::class, function () {
            return new BrandCacheDecorator(
                new BrandRepository(new Brand())
            );
        });

        $this->app->bind(ProductCollectionInterface::class, function () {
            return new ProductCollectionCacheDecorator(
                new ProductCollectionRepository(new ProductCollection())
            );
        });

        $this->app->bind(CurrencyInterface::class, function () {
            return new CurrencyCacheDecorator(
                new CurrencyRepository(new Currency())
            );
        });

        $this->app->bind(ProductAttributeSetInterface::class, function () {
            return new ProductAttributeSetCacheDecorator(
                new ProductAttributeSetRepository(new ProductAttributeSet()),
                ECOMMERCE_GROUP_CACHE_KEY
            );
        });

        $this->app->bind(ProductAttributeInterface::class, function () {
            return new ProductAttributeCacheDecorator(
                new ProductAttributeRepository(new ProductAttribute()),
                ECOMMERCE_GROUP_CACHE_KEY
            );
        });

        $this->app->bind(ProductVariationInterface::class, function () {
            return new ProductVariationCacheDecorator(
                new ProductVariationRepository(new ProductVariation()),
                ECOMMERCE_GROUP_CACHE_KEY
            );
        });

        $this->app->bind(ProductVariationItemInterface::class, function () {
            return new ProductVariationItemCacheDecorator(
                new ProductVariationItemRepository(new ProductVariationItem()),
                ECOMMERCE_GROUP_CACHE_KEY
            );
        });

        $this->app->bind(TaxInterface::class, function () {
            return new TaxCacheDecorator(
                new TaxRepository(new Tax())
            );
        });

        $this->app->bind(ReviewInterface::class, function () {
            return new ReviewCacheDecorator(
                new ReviewRepository(new Review())
            );
        });

        $this->app->bind(ShippingInterface::class, function () {
            return new ShippingCacheDecorator(
                new ShippingRepository(new Shipping())
            );
        });

        $this->app->bind(ShippingRuleInterface::class, function () {
            return new ShippingRuleCacheDecorator(
                new ShippingRuleRepository(new ShippingRule())
            );
        });

        $this->app->bind(ShippingRuleItemInterface::class, function () {
            return new ShippingRuleItemCacheDecorator(
                new ShippingRuleItemRepository(new ShippingRuleItem())
            );
        });

        $this->app->bind(ShipmentInterface::class, function () {
            return new ShipmentCacheDecorator(
                new ShipmentRepository(new Shipment())
            );
        });

        $this->app->bind(ShipmentHistoryInterface::class, function () {
            return new ShipmentHistoryCacheDecorator(
                new ShipmentHistoryRepository(new ShipmentHistory())
            );
        });

        $this->app->bind(OrderInterface::class, function () {
            return new OrderCacheDecorator(
                new OrderRepository(new Order())
            );
        });

        $this->app->bind(OrderHistoryInterface::class, function () {
            return new OrderHistoryCacheDecorator(
                new OrderHistoryRepository(new OrderHistory())
            );
        });

        $this->app->bind(OrderProductInterface::class, function () {
            return new OrderProductCacheDecorator(
                new OrderProductRepository(new OrderProduct())
            );
        });

        $this->app->bind(OrderAddressInterface::class, function () {
            return new OrderAddressCacheDecorator(
                new OrderAddressRepository(new OrderAddress())
            );
        });

        $this->app->bind(OrderReturnInterface::class, function () {
            return new OrderReturnCacheDecorator(
                new OrderReturnRepository(new OrderReturn())
            );
        });

        $this->app->bind(OrderReturnItemInterface::class, function () {
            return new OrderReturnItemCacheDecorator(
                new OrderReturnItemRepository(new OrderReturnItem())
            );
        });

        $this->app->bind(DiscountInterface::class, function () {
            return new DiscountCacheDecorator(
                new DiscountRepository(new Discount())
            );
        });

        $this->app->bind(WishlistInterface::class, function () {
            return new WishlistCacheDecorator(
                new WishlistRepository(new Wishlist())
            );
        });

        $this->app->bind(AddressInterface::class, function () {
            return new AddressCacheDecorator(
                new AddressRepository(new Address())
            );
        });
        $this->app->bind(CustomerInterface::class, function () {
            return new CustomerCacheDecorator(
                new CustomerRepository(new Customer())
            );
        });

        $this->app->bind(GroupedProductInterface::class, function () {
            return new GroupedProductCacheDecorator(
                new GroupedProductRepository(new GroupedProduct())
            );
        });

        $this->app->bind(StoreLocatorInterface::class, function () {
            return new StoreLocatorCacheDecorator(
                new StoreLocatorRepository(new StoreLocator())
            );
        });

        $this->app->bind(FlashSaleInterface::class, function () {
            return new FlashSaleCacheDecorator(
                new FlashSaleRepository(new FlashSale())
            );
        });

        $this->app->bind(ProductLabelInterface::class, function () {
            return new ProductLabelCacheDecorator(
                new ProductLabelRepository(new ProductLabel())
            );
        });

        $this->app->bind(InvoiceInterface::class, function () {
            return new InvoiceCacheDecorator(new InvoiceRepository(new Invoice()));
        });

        $this->app->bind(TrackingFilterInterface::class, function ($app) {
            return $app->make(TrackingFilter::class);
        });

        $this->app->bind(TrackingLoggerInterface::class, function ($app) {
            return $app->make(TrackingLogger::class);
        });

        $this->app->singleton(FootprinterInterface::class, function ($app) {
            return $app->make(Footprinter::class);
        });

        Request::macro('footprint', function () {
            return app(FootprinterInterface::class)->footprint($this);
        });

        $this->setNamespace('plugins/ecommerce')->loadHelpers();

        $loader = AliasLoader::getInstance();
        $loader->alias('Cart', CartFacade::class);
        $loader->alias('OrderHelper', OrderHelperFacade::class);
        $loader->alias('OrderReturnHelper', OrderReturnHelperFacade::class);
        $loader->alias('EcommerceHelper', EcommerceHelperFacade::class);
        $loader->alias('ProductCategoryHelper', ProductCategoryHelperFacade::class);
        $loader->alias('CurrencyHelper', CurrencyFacade::class);
        $loader->alias('InvoiceHelper', InvoiceHelperFacade::class);
    }

    public function boot(): void
    {
        // SlugHelper::registerModule(Product::class, 'Products');
        // SlugHelper::registerModule(Brand::class, 'Brands');
        // SlugHelper::registerModule(ProductCategory::class, 'Product Categories');
        // SlugHelper::registerModule(ProductTag::class, 'Product Tags');
        // SlugHelper::setPrefix(Product::class, 'products');
        // SlugHelper::setPrefix(Brand::class, 'brands');
        // SlugHelper::setPrefix(ProductTag::class, 'product-tags');
        // SlugHelper::setPrefix(ProductCategory::class, 'product-categories');

        // SiteMapManager::registerKey(['product-categories', 'product-tags', 'product-brands', 'products-((?:19|20|21|22)\d{2})-(0?[1-9]|1[012])']);

        $this
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishTranslations()
            ->loadRoutes([
                'base',
                'product',
                'tax',
                'review',
                'shipping',
                'order',
                'discount',
                'customer',
                'cart',
                'shipment',
                'wishlist',
                'compare',
                'invoice',
                'invoice-template',
            ])
            ->loadAndPublishConfigurations([
                'general',
                'shipping',
                'order',
                'cart',
                'email',
            ])
            ->loadAndPublishViews()
            ->loadMigrations()
            ->publishAssets();

        if (class_exists('ApiHelper') && ApiHelper::enabled()) {
            ApiHelper::setConfig([
                'model' => Customer::class,
                'guard' => 'customer',
                'password_broker' => 'customers',
                'verify_email' => true,
            ]);
        }

        if (File::exists(storage_path('app/invoices/template.blade.php'))) {
            $this->loadViewsFrom(storage_path('app/invoices'), 'plugins/ecommerce/invoice');
        }

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            LanguageAdvancedManager::registerModule(Product::class, [
                'name',
                'description',
                'content',
            ]);

            if (config('plugins.ecommerce.general.enable_faq_in_product_details', false)) {
                LanguageAdvancedManager::addTranslatableMetaBox('faq_schema_config_wrapper');

                LanguageAdvancedManager::registerModule(Product::class, array_merge(
                    LanguageAdvancedManager::getTranslatableColumns(Product::class),
                    ['faq_schema_config']
                ));
            }

            LanguageAdvancedManager::registerModule(ProductCategory::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(ProductAttribute::class, [
                'title',
            ]);

            LanguageAdvancedManager::addTranslatableMetaBox('attributes_list');

            LanguageAdvancedManager::registerModule(ProductAttribute::class, array_merge(
                LanguageAdvancedManager::getTranslatableColumns(ProductAttribute::class),
                ['attributes']
            ));

            LanguageAdvancedManager::registerModule(ProductAttributeSet::class, [
                'title',
            ]);

            LanguageAdvancedManager::registerModule(Brand::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(ProductCollection::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(ProductLabel::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(FlashSale::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(ProductTag::class, [
                'name',
            ]);

            LanguageAdvancedManager::registerModule(GlobalOption::class, [
                'name',
            ]);

            LanguageAdvancedManager::registerModule(Option::class, [
                'name',
            ]);

            LanguageAdvancedManager::registerModule(GlobalOptionValue::class, [
                'option_value',
            ]);

            LanguageAdvancedManager::registerModule(OptionValue::class, [
                'option_value',
            ]);

            LanguageAdvancedManager::addTranslatableMetaBox('product_options_box');

            add_action(LANGUAGE_ADVANCED_ACTION_SAVED, function ($data, $request) {
                switch (get_class($data)) {
                    case Product::class:
                        $variations = $data->variations()->get();

                        foreach ($variations as $variation) {
                            if (! $variation->product->id) {
                                continue;
                            }

                            LanguageAdvancedManager::save($variation->product, $request);
                        }

                        $options = $request->input('options', []) ?: [];

                        if (! $options) {
                            return;
                        }

                        $newRequest = new Request();

                        $newRequest->replace([
                            'language' => $request->input('language'),
                            'ref_lang' => $request->input('ref_lang'),
                        ]);

                        foreach ($options as $item) {
                            $option = Option::find($item['id']);

                            $newRequest->merge(['name' => $item['name']]);

                            if ($option) {
                                LanguageAdvancedManager::save($option, $newRequest);
                            }

                            $newRequest = new Request();

                            $newRequest->replace([
                                'language' => $request->input('language'),
                                'ref_lang' => $request->input('ref_lang'),
                            ]);

                            foreach ($item['values'] as $value) {
                                if (! $value['id']) {
                                    continue;
                                }

                                $optionValue = OptionValue::find($value['id']);

                                $newRequest->merge([
                                    'option_value' => $value['option_value'],
                                ]);

                                if ($optionValue) {
                                    LanguageAdvancedManager::save($optionValue, $newRequest);
                                }
                            }
                        }

                        break;
                    case ProductAttributeSet::class:

                        $attributes = json_decode($request->input('attributes', '[]'), true) ?: [];

                        if (! $attributes) {
                            break;
                        }

                        $request = new Request();
                        $request->replace([
                            'language' => request()->input('language'),
                            'ref_lang' => request()->input('ref_lang'),
                        ]);

                        foreach ($attributes as $item) {
                            $request->merge([
                                'title' => $item['title'],
                            ]);

                            $attribute = $this->app->make(ProductAttributeInterface::class)->findById($item['id']);

                            if ($attribute) {
                                LanguageAdvancedManager::save($attribute, $request);
                            }
                        }

                        break;
                    case GlobalOption::class:

                        $option = GlobalOption::find($request->input('id'));

                        if ($option) {
                            LanguageAdvancedManager::save($option, $request);
                        }

                        $options = $request->input('options', []) ?: [];

                        if (! $options) {
                            return;
                        }

                        $newRequest = new Request();

                        $newRequest->replace([
                            'language' => $request->input('language'),
                            'ref_lang' => $request->input('ref_lang'),
                        ]);

                        foreach ($options as $value) {
                            if (! $value['id']) {
                                continue;
                            }

                            $optionValue = GlobalOptionValue::find($value['id']);

                            $newRequest->merge([
                                'option_value' => $value['option_value'],
                            ]);

                            if ($optionValue) {
                                LanguageAdvancedManager::save($optionValue, $newRequest);
                            }
                        }

                        break;
                }
            }, 1234, 2);
        }

        $this->app->register(HookServiceProvider::class);

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                // ->registerItem([
                //     'id' => 'cms-plugins-ecommerce',
                //     'priority' => 8,
                //     'parent_id' => null,
                //     'name' => 'plugins/ecommerce::ecommerce.name',
                //     'icon' => 'fa fa-shopping-cart',
                //     'url' => route('products.index'),
                //     'permissions' => ['plugins.ecommerce'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-plugins-ecommerce-report',
                //     'priority' => 0,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::reports.name',
                //     'icon' => 'far fa-chart-bar',
                //     'url' => route('ecommerce.report.index'),
                //     'permissions' => ['ecommerce.report.index'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-plugins-flash-sale',
                //     'priority' => 0,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::flash-sale.name',
                //     'icon' => 'fa fa-bolt',
                //     'url' => route('flash-sale.index'),
                //     'permissions' => ['flash-sale.index'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-plugins-ecommerce-order',
                //     'priority' => 1,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::order.menu',
                //     'icon' => 'fa fa-shopping-bag',
                //     'url' => route('orders.index'),
                //     'permissions' => ['orders.index'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-plugins-ecommerce-invoice',
                //     'priority' => 2,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::invoice.name',
                //     'icon' => 'fas fa-book',
                //     'url' => route('ecommerce.invoice.index'),
                //     'permissions' => ['ecommerce.invoice.index'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-plugins-invoice-template',
                //     'priority' => 2,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::invoice-template.name',
                //     'icon' => 'fas fa-book',
                //     'url' => route('invoice-template.index'),
                //     'permissions' => ['ecommerce.invoice-template.index'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-plugins-ecommerce-incomplete-order',
                //     'priority' => 2,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::order.incomplete_order',
                //     'icon' => 'fas fa-shopping-basket',
                //     'url' => route('orders.incomplete-list'),
                //     'permissions' => ['orders.index'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-plugins-ecommerce-order-return',
                //     'priority' => 3,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::order.order_return',
                //     'icon' => 'fa fa-cart-arrow-down',
                //     'url' => route('order_returns.index'),
                //     'permissions' => ['orders.edit'],
                // ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce.product',
                    'priority' => 3,
                    'parent_id' => 'cms-plugins-religious-merit',
                    'name' => 'plugins/ecommerce::products.name',
                    'icon' => 'fa fa-camera',
                    'url' => route('products.index'),
                    'permissions' => ['religious-merit.index'],
                ])
                // ->registerItem([
                //     'id' => 'cms-plugins-product-categories',
                //     'priority' => 4,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::product-categories.name',
                //     'icon' => 'fa fa-archive',
                //     'url' => route('product-categories.index'),
                //     'permissions' => ['product-categories.index'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-plugins-product-tag',
                //     'priority' => 4,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::product-tag.name',
                //     'icon' => 'fa fa-tag',
                //     'url' => route('product-tag.index'),
                //     'permissions' => ['product-tag.index'],
                // ])
                ->registerItem([
                    'id' => 'cms-plugins-product-attribute',
                    'priority' => 3,
                    'parent_id' => 'cms-plugins-religious-merit',
                    'name' => 'plugins/ecommerce::product-attributes.name',
                    'icon' => 'fas fa-glass-martini',
                    'url' => route('product-attribute-sets.index'),
                    'permissions' => ['religious-merit.index'],
                ])
                // ->registerItem([
                //     'id' => 'cms-plugins-ecommerce-global-options',
                //     'priority' => 3,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::product-option.name',
                //     'icon' => 'fa fa-database',
                //     'url' => route('global-option.index'),
                //     'permissions' => ['global-option.index'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-plugins-brands',
                //     'priority' => 6,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::brands.name',
                //     'icon' => 'fa fa-registered',
                //     'url' => route('brands.index'),
                //     'permissions' => ['brands.index'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-plugins-product-collections',
                //     'priority' => 7,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::product-collections.name',
                //     'icon' => 'fa fa-file-excel',
                //     'url' => route('product-collections.index'),
                //     'permissions' => ['product-collections.index'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-plugins-product-label',
                //     'priority' => 8,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::product-label.name',
                //     'icon' => 'fas fa-tags',
                //     'url' => route('product-label.index'),
                //     'permissions' => ['product-label.index'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-ecommerce-review',
                //     'priority' => 9,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::review.name',
                //     'icon' => 'fa fa-comments',
                //     'url' => route('reviews.index'),
                //     'permissions' => ['reviews.index'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-plugins-ecommerce-shipping-provider',
                //     'priority' => 10,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::shipping.shipping',
                //     'icon' => 'fas fa-shipping-fast',
                //     'url' => route('shipping_methods.index'),
                //     'permissions' => ['shipping_methods.index'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-plugins-ecommerce-shipping-shipments',
                //     'priority' => 11,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::shipping.shipments',
                //     'icon' => 'fas fa-people-carry',
                //     'url' => route('ecommerce.shipments.index'),
                //     'permissions' => ['orders.edit'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-plugins-ecommerce-discount',
                //     'priority' => 12,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::discount.name',
                //     'icon' => 'fa fa-gift',
                //     'url' => route('discounts.index'),
                //     'permissions' => ['discounts.index'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-plugins-ecommerce-customer',
                //     'priority' => 13,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::customer.name',
                //     'icon' => 'fa fa-users',
                //     'url' => route('customers.index'),
                //     'permissions' => ['customers.index'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-plugins-ecommerce.basic-settings',
                //     'priority' => 998,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::ecommerce.basic_settings',
                //     'icon' => 'fas fa-cogs',
                //     'url' => route('ecommerce.settings'),
                //     'permissions' => ['ecommerce.settings'],
                // ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce.advanced-settings',
                    'priority' => 3,
                    'parent_id' => 'cms-plugins-religious-merit',
                    'name' => 'plugins/ecommerce::ecommerce.advanced_settings',
                    'icon' => 'fas fa-plus',
                    'url' => route('ecommerce.advanced-settings'),
                    'permissions' => ['religious-merit.index'],
                ])
                // ->registerItem([
                //     'id' => 'cms-plugins-ecommerce.tracking-settings',
                //     'priority' => 999,
                //     'parent_id' => 'cms-plugins-religious-merit',
                //     'name' => 'plugins/ecommerce::ecommerce.setting.tracking_settings',
                //     'icon' => 'fa-solid fa-chart-pie',
                //     'url' => route('ecommerce.tracking-settings'),
                //     'permissions' => ['ecommerce.settings'],
                // ])
                ;

            // if (EcommerceHelper::isTaxEnabled()) {
            //     dashboard_menu()->registerItem([
            //         'id' => 'cms-plugins-ecommerce-tax',
            //         'priority' => 14,
            //         'parent_id' => 'cms-plugins-religious-merit',
            //         'name' => 'plugins/ecommerce::tax.name',
            //         'icon' => 'fas fa-money-check-alt',
            //         'url' => route('tax.index'),
            //         'permissions' => ['tax.index'],
            //     ]);
            // }

            // if (! dashboard_menu()->hasItem('cms-core-tools')) {
            //     dashboard_menu()->registerItem([
            //         'id' => 'cms-core-tools',
            //         'priority' => 96,
            //         'parent_id' => null,
            //         'name' => 'core/base::base.tools',
            //         'icon' => 'fas fa-tools',
            //         'url' => '',
            //         'permissions' => [],
            //     ]);
            // }

            // dashboard_menu()
            //     ->registerItem([
            //         'id' => 'cms-core-tools-ecommerce-bulk-import',
            //         'priority' => 1,
            //         'parent_id' => 'cms-core-tools',
            //         'name' => 'plugins/ecommerce::bulk-import.menu',
            //         'icon' => 'fas fa-file-import',
            //         'url' => route('ecommerce.bulk-import.index'),
            //         'permissions' => ['ecommerce.bulk-import.index'],
            //     ])
            //     ->registerItem([
            //         'id' => 'cms-core-tools-ecommerce-export-products',
            //         'priority' => 2,
            //         'parent_id' => 'cms-core-tools',
            //         'name' => 'plugins/ecommerce::export.products.name',
            //         'icon' => 'fas fa-file-export',
            //         'url' => route('ecommerce.export.products.index'),
            //         'permissions' => ['ecommerce.export.products.index'],
            //     ]);

            $emailConfig = config('plugins.ecommerce.email', []);

            if (! EcommerceHelper::isEnabledSupportDigitalProducts()) {
                Arr::forget($emailConfig, 'templates.download_digital_products');
            }

            if (! EcommerceHelper::isReviewEnabled()) {
                Arr::forget($emailConfig, 'templates.review_products');
            }

            EmailHandler::addTemplateSettings(ECOMMERCE_MODULE_SCREEN_NAME, $emailConfig);
        });

        $this->app->booted(function () {
            // SeoHelper::registerModule([
            //     Product::class,
            //     Brand::class,
            //     ProductCategory::class,
            //     ProductTag::class,
            // ]);

            $this->app->make(Schedule::class)->command(SendAbandonedCartsEmailCommand::class)->weekly('23:30');

            if (is_plugin_active('payment')) {
                Payment::resolveRelationUsing('order', function ($model) {
                    return $model->belongsTo(Order::class, 'order_id')->withDefault();
                });
            }

            if (defined('SOCIAL_LOGIN_MODULE_SCREEN_NAME') && Route::has('customer.login') && Route::has('public.index')) {
                SocialService::registerModule([
                    'guard' => 'customer',
                    'model' => Customer::class,
                    'login_url' => route('customer.login'),
                    'redirect_url' => route('public.index'),
                ]);
            }
        });

        $this->app->register(EventServiceProvider::class);
        $this->app->register(CommandServiceProvider::class);

        Event::listen(['cart.removed', 'cart.stored', 'cart.restored', 'cart.updated'], function ($cart) {
            $coupon = session('applied_coupon_code');
            if ($coupon) {
                $this->app->make(HandleRemoveCouponService::class)->execute();
                if (Cart::count() || ($cart instanceof \Cmat\Ecommerce\Cart\Cart && $cart->count())) {
                    $this->app->make(HandleApplyCouponService::class)->execute($coupon);
                }
            }
        });
    }
}
