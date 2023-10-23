import SelectPhysicalProducts from './components/SelectPhysicalProductsComponent.vue';
import SelectDigitalProducts from './components/SelectDigitalProductsComponent.vue';

vueApp.booting((vue) => {
    vue.filter('formatPrice', (value) => {
        return parseFloat(value).toFixed(2);
    });

    vue.component('select-physical-products', SelectPhysicalProducts);
    vue.component('select-digital-products', SelectDigitalProducts);
});
