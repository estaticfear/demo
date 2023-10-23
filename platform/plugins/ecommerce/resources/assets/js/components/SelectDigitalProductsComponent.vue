<template>
    <div>
        <div class="">
            <h6 class="text-center">Công sức</h6>
            <div class="flexbox-content">
                <div class="wrapper-content">
                    <div class="pd-all-10-20 border-top-title-main">
                        <div class="clearfix">
                            <div
                                class="table-wrapper p-none mb20 ps-relative z-index-4"
                                :class="{ 'loading-skeleton': checking }"
                                v-if="child_products.length"
                            >
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>{{ __('Tên hiện vật') }}</th>
                                            <th>{{ __('Giá') }}</th>
                                            <th width="90">
                                                {{ __('Số lượng') }}
                                            </th>
                                            <th width="90">
                                                {{ __('SL đã đóng góp') }}
                                            </th>
                                            <th>{{ __('Không nhận 1 phần') }}</th>
                                            <th>{{ __('Tổng tiền') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(variant, vKey) in child_products"
                                            v-bind:key="variant.id + '-' + vKey"
                                        >
                                            <td>
                                                <div class="wrap-img vertical-align-m-i">
                                                    <img
                                                        class="thumb-image"
                                                        :src="variant.image_url"
                                                        :alt="variant.name"
                                                        width="50"
                                                    />
                                                </div>
                                            </td>
                                            <td>
                                                <a
                                                    class="hover-underline pre-line"
                                                    :href="variant.product_link"
                                                    target="_blank"
                                                    >{{ variant.name }}</a
                                                >
                                                <p class="type-subdued" v-if="variant.variation_attributes">
                                                    <span class="small">{{ variant.variation_attributes }}</span>
                                                </p>
                                                <ul
                                                    v-if="
                                                        variant.option_values &&
                                                        Object.keys(variant.option_values).length
                                                    "
                                                    class="small"
                                                >
                                                    <li>
                                                        <span>{{ __('order.price') }}:</span>
                                                        <span>{{ variant.original_price_label }}</span>
                                                    </li>
                                                    <li v-for="option in variant.option_values" v-bind:key="option.id">
                                                        <span>{{ option.title }}:</span>
                                                        <span v-for="value in option.values" v-bind:key="value.id">
                                                            {{ value.value }}
                                                            <strong>+{{ value.price_label }}</strong>
                                                        </span>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <span>{{ variant.price_label }}</span>
                                            </td>
                                            <td style="width: 120px">
                                                <input
                                                    class="form-control"
                                                    :value="variant.select_qty"
                                                    type="number"
                                                    min="1"
                                                    @input="handleChangeQuantity($event, variant, vKey)"
                                                />
                                            </td>
                                            <td style="width: 90px">
                                                {{ variant.total_merit_qty }}
                                            </td>
                                            <td style="width: 120px">
                                                <b-form-checkbox
                                                    class="form-control"
                                                    v-model="variant.is_not_allow_merit_a_part"
                                                    @input="handleChangeIsNotAllowMeritAPart($event, variant, vKey)"
                                                >
                                                </b-form-checkbox>
                                            </td>
                                            <td class="text-center">
                                                {{ variant.total_price_label }}
                                            </td>
                                            <td class="text-center">
                                                <a
                                                    v-if="!variant.total_merit_qty"
                                                    href="#"
                                                    @click="handleRemoveVariant($event, variant, vKey)"
                                                >
                                                    <svg class="svg-next-icon svg-next-icon-size-12">
                                                        <use
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                            xlink:href="#next-remove"
                                                        ></use>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="box-search-advance product">
                                <div>
                                    <input
                                        type="text"
                                        class="next-input textbox-advancesearch product"
                                        :placeholder="__('Chọn hiện vật')"
                                        @click="loadListProductsAndVariations()"
                                        @keyup="handleSearchProduct($event.target.value)"
                                    />
                                </div>
                                <div
                                    class="panel panel-default"
                                    :class="{
                                        active: list_products,
                                        hidden: hidden_product_search_panel,
                                    }"
                                >
                                    <div class="panel-body">
                                        <div class="box-search-advance-head" v-b-modal.add-product-item>
                                            <img
                                                width="30"
                                                src="/vendor/core/plugins/ecommerce/images/next-create-custom-line-item.svg"
                                                alt="icon"
                                            />
                                            <span class="ml10">{{ __('Chọn hiện vật') }}</span>
                                        </div>
                                        <div class="list-search-data">
                                            <div class="has-loading" v-show="loading">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </div>
                                            <ul class="clearfix" v-show="!loading">
                                                <li
                                                    v-for="product_item in list_products.data"
                                                    :class="{
                                                        'item-selectable': !product_item.variations.length,
                                                        'item-not-selectable': product_item.variations.length,
                                                    }"
                                                    v-bind:key="product_item.id"
                                                >
                                                    <div class="wrap-img inline_block vertical-align-t float-start">
                                                        <img
                                                            class="thumb-image"
                                                            :src="product_item.image_url"
                                                            :alt="product_item.name"
                                                        />
                                                    </div>
                                                    <div
                                                        class="inline_block ml10 mt10 ws-nm"
                                                        style="width: calc(100% - 50px)"
                                                    >
                                                        <ProductAction
                                                            :product="product_item"
                                                            @select-product="selectProductVariant"
                                                        ></ProductAction>
                                                    </div>
                                                    <div v-if="product_item.variations.length">
                                                        <ul>
                                                            <li
                                                                class="product-variant"
                                                                v-for="variation in product_item.variations"
                                                                v-bind:key="variation.id"
                                                            >
                                                                <ProductAction
                                                                    :product="variation"
                                                                    @select-product="selectProductVariant"
                                                                ></ProductAction>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li v-if="list_products.data && list_products.data.length === 0">
                                                    <span>{{ __('order.no_products_found') }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div
                                        class="panel-footer"
                                        v-if="
                                            (list_products.links && list_products.links.next) ||
                                            (list_products.links && list_products.links.prev)
                                        "
                                    >
                                        <div class="btn-group float-end">
                                            <button
                                                type="button"
                                                @click="
                                                    loadListProductsAndVariations(
                                                        list_products.links.prev
                                                            ? list_products.meta.current_page - 1
                                                            : list_products.meta.current_page,
                                                        true,
                                                    )
                                                "
                                                :class="{
                                                    'btn btn-secondary': list_products.meta.current_page !== 1,
                                                    'btn btn-secondary disable': list_products.meta.current_page === 1,
                                                }"
                                                :disabled="list_products.meta.current_page === 1"
                                            >
                                                <svg
                                                    role="img"
                                                    class="svg-next-icon svg-next-icon-size-16 svg-next-icon-rotate-180"
                                                >
                                                    <use
                                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                                        xlink:href="#next-chevron"
                                                    ></use>
                                                </svg>
                                            </button>
                                            <button
                                                type="button"
                                                @click="
                                                    loadListProductsAndVariations(
                                                        list_products.links.next
                                                            ? list_products.meta.current_page + 1
                                                            : list_products.meta.current_page,
                                                        true,
                                                    )
                                                "
                                                :class="{
                                                    'btn btn-secondary': list_products.links.next,
                                                    'btn btn-secondary disable': !list_products.links.next,
                                                }"
                                                :disabled="!list_products.links.next"
                                            >
                                                <svg role="img" class="svg-next-icon svg-next-icon-size-16">
                                                    <use
                                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                                        xlink:href="#next-chevron"
                                                    ></use>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-12 col-lg-6 text-end d-flex">
            <button type="button" class="btn btn-primary" v-b-modal.update-digital-products>
                {{ __('Cập nhật') }}
            </button>
        </div>
        <b-modal
            id="update-digital-products"
            :title="__('Xác nhận cập nhật')"
            :ok-title="__('Cập nhật')"
            :cancel-title="__('Đóng')"
            @ok="updateProjectProducts($event)"
        >
            <div class="note note-warning">{{ __('Bạn chắc chắn muốn cập nhật danh sách công sức chứ?') }}.</div>
        </b-modal>
    </div>
</template>

<script>
import ProductAction from './partials/ProductActionComponent.vue';

export default {
    props: {
        product_type: {
            type: String,
        },
        project_id: {
            type: String | null,
            default: () => null,
        },
        products: {
            type: Array,
            default: () => [],
        },
        product_ids: {
            type: Array,
            default: () => [],
        },
        sub_amount: {
            type: Number,
            default: () => 0,
        },
        sub_amount_label: {
            type: String,
            default: () => '',
        },
        total_amount: {
            type: Number,
            default: () => 0,
        },
        total_amount_label: {
            type: String,
            default: () => '',
        },
    },
    data: function () {
        return {
            list_products: {
                data: [],
            },
            deleted_product_ids: [],
            hidden_product_search_panel: true,
            loading: false,
            checking: false,
            child_products: this.products,
            child_product_ids: this.product_ids,
            child_sub_amount: this.sub_amount,
            child_sub_amount_label: this.sub_amount_label,
            child_total_amount: this.total_amount,
            child_total_amount_label: this.total_amount_label,
            productSearchRequest: null,
            timeoutProductRequest: null,
            checkDataOrderRequest: null,
        };
    },
    components: {
        ProductAction,
    },
    mounted: function () {
        let context = this;
        if (this.project_id) {
            axios
                .get(
                    route('religious-merit-project.get-project-products', {
                        project_id: this.project_id,
                        product_type: this.product_type,
                    }),
                )
                .then((res) => {
                    let data = res.data.data;
                    const products = [];
                    data.map((item) => {
                        products.push({
                            id: item.product_id,
                            merit_project_id: item.project_id,
                            select_qty: item.qty,
                            total_merit_qty: item.total_merit_qty,
                            is_not_allow_merit_a_part: !!item.is_not_allow_merit_a_part,
                        });
                    });
                    // console.log('products', products);
                    context.child_products = products;
                    this.checkDataBeforeCreateOrder();
                })
                .catch((res) => {
                    Cmat.handleError(res.response.data);
                });
        }
        $(document).on('click', 'body', (e) => {
            let container = $('.box-search-advance');

            if (!container.is(e.target) && container.has(e.target).length === 0) {
                context.hidden_product_search_panel = true;
            }
        });
    },
    methods: {
        updateProjectProducts: function ($event) {
            $event.preventDefault();
            $($event.target).find('.btn-primary').addClass('button-loading');
            let context = this;

            let formData = this.getOrderFormData();
            axios
                .post(route('religious-merit-project.update-project-products'), formData)
                .then((res) => {
                    let data = res.data.data;
                    if (res.data.error) {
                        Cmat.showError(res.data.message);
                    } else {
                        Cmat.showSuccess(res.data.message);
                        context.$root.$emit('bv::hide::modal', 'update-digital-products');
                        setTimeout(() => {
                            window.location.href = route('religious-merit-project.edit', context.project_id);
                        }, 1000);
                    }
                })
                .catch((res) => {
                    Cmat.handleError(res.response.data);
                })
                .then(() => {
                    $($event.target).find('.btn-primary').removeClass('button-loading');
                });
        },
        loadListProductsAndVariations: function (page = 1, force = false, show_panel = true) {
            let context = this;
            if (show_panel) {
                context.hidden_product_search_panel = false;
                $('.textbox-advancesearch.product')
                    .closest('.box-search-advance.product')
                    .find('.panel')
                    .addClass('active');
            } else {
                context.hidden_product_search_panel = true;
            }
            if (_.isEmpty(context.list_products.data) || force) {
                context.loading = true;
                if (context.productSearchRequest) {
                    context.productSearchRequest.abort();
                }
                context.productSearchRequest = new AbortController();
                axios
                    .get(
                        route('products.get-all-products-and-variations', {
                            keyword: context.product_keyword,
                            page: page,
                            product_ids: context.child_product_ids,
                            product_type: context.product_type,
                        }),
                        { signal: context.productSearchRequest.signal },
                    )
                    .then((res) => {
                        context.list_products = res.data.data;
                        context.loading = false;
                    })
                    .catch((error) => {
                        if (!axios.isCancel(error)) {
                            Cmat.handleError(error.response.data);
                            context.loading = false;
                        }
                    });
            }
        },
        handleSearchProduct: function (value) {
            if (value !== this.product_keyword) {
                let context = this;
                context.product_keyword = value;
                if (context.timeoutProductRequest) {
                    clearTimeout(context.timeoutProductRequest);
                }

                context.timeoutProductRequest = setTimeout(() => {
                    context.loadListProductsAndVariations(1, true);
                }, 1000);
            }
        },
        selectProductVariant: function (product) {
            let context = this;
            context.child_products.push({
                id: product.id,
                merit_project_id: context.project_id,
                quantity: 1,
                is_not_allow_merit_a_part: false,
            });
            context.checkDataBeforeCreateOrder();
            context.hidden_product_search_panel = true;
        },
        checkDataBeforeCreateOrder: function (data = {}, onSuccess = null, onError = null) {
            let context = this;
            let formData = { ...context.getOrderFormData(), ...data };
            context.checking = true;
            if (context.checkDataOrderRequest) {
                context.checkDataOrderRequest.abort();
            }

            context.checkDataOrderRequest = new AbortController();

            axios
                .post(route('orders.check-data-before-create-order'), formData, {
                    signal: context.checkDataOrderRequest.signal,
                })
                .then((res) => {
                    let data = res.data.data;

                    if (data.update_context_data) {
                        context.child_products = data.products;
                        // console.log('vvvv', context.child_products);
                        context.child_product_ids = _.map(data.products, 'id');

                        context.child_sub_amount = data.sub_amount;
                        context.child_sub_amount_label = data.sub_amount_label;

                        context.child_total_amount = data.total_amount;
                        context.child_total_amount_label = data.total_amount_label;
                    }

                    if (res.data.error) {
                        Cmat.showError(res.data.message);
                        if (onError) {
                            onError();
                        }
                    } else {
                        if (onSuccess) {
                            onSuccess();
                        }
                    }
                    context.checking = false;
                })
                .catch((error) => {
                    if (!axios.isCancel(error)) {
                        context.checking = false;
                        Cmat.handleError(error.response.data);
                    }
                });
        },
        getOrderFormData: function () {
            let products = [];
            _.each(this.child_products, function (item) {
                products.push({
                    id: item.id,
                    quantity: item.select_qty,
                    is_not_allow_merit_a_part: !!item.is_not_allow_merit_a_part,
                    options: item.options,
                    total_merit_qty: item.total_merit_qty || 0,
                });
            });

            return {
                deleted_product_ids: this.deleted_product_ids,
                project_id: this.project_id,
                product_type: this.product_type,
                products,
                sub_amount: this.child_sub_amount,
                amount: this.child_total_amount,
            };
        },
        handleRemoveVariant: function ($event, variant, vKey) {
            $event.preventDefault();
            this.child_product_ids = this.child_product_ids.filter((item, k) => k != vKey);
            this.child_products = this.child_products.filter((item, k) => k != vKey);
            this.deleted_product_ids.push(variant.id);
            this.checkDataBeforeCreateOrder();
        },
        handleChangeQuantity: function ($event, variant, vKey) {
            $event.preventDefault();
            let context = this;
            const value = parseInt($event.target.value);
            variant.select_qty = value;

            _.each(context.child_products, function (item, key) {
                if (vKey === key) {
                    context.child_products[key] = variant;
                }
            });

            if (context.timeoutChangeQuantity) {
                clearTimeout(context.timeoutChangeQuantity);
            }

            context.timeoutChangeQuantity = setTimeout(() => {
                context.checkDataBeforeCreateOrder();
            }, 1500);
        },
        handleChangeIsNotAllowMeritAPart: function (value, variant, vKey) {
            let context = this;
            variant.is_not_allow_merit_a_part = !!value;
            _.each(context.child_products, function (item, key) {
                if (vKey === key) {
                    context.child_products[key] = variant;
                }
            });
        },
    },
    watch: {},
};
</script>
