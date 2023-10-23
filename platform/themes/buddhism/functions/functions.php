<?php

use Cmat\Base\Forms\FormAbstract;
use Cmat\Blog\Models\Post;
use Cmat\Page\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request as IlluminateRequest;
use Kris\LaravelFormBuilder\FormHelper;
use Theme\Ripple\Fields\ThemeIconField;

register_page_template([
    'no-sidebar' => __('No sidebar'),
    'no-sidebar-no-breadcrumbs' => __('No sidebar no breadcrumbs'),
    'no-sidebar-no-breadcrumbs-have-banner' => __('No sidebar no breadcrumbs have banner top'),
    // 'text-page' => __('Text page'),
    'contact-page' => __('Contact page'),
]);

register_sidebar([
    'id' => 'top_sidebar',
    'name' => __('Top sidebar'),
    'description' => __('Area for widgets on the top sidebar'),
]);

register_sidebar([
    'id' => 'footer_sidebar',
    'name' => __('Footer sidebar'),
    'description' => __('Area for footer widgets'),
]);

register_sidebar([
    'id' => 'project_detail',
    'name' => __('Project Detail Banner'),
    'description' => __('Display banner'),
]);

register_sidebar([
    'id' => 'news_papers',
    'name' => __('NewsPapers Banner'),
    'description' => __('Display banner'),
]);

RvMedia::setUploadPathAndURLToPublic();
RvMedia::addSize('featured', 565, 375)->addSize('medium', 540, 360);

add_filter(BASE_FILTER_BEFORE_RENDER_FORM, function (FormAbstract $form, Model $data): FormAbstract {
    switch (get_class($data)) {
        case Post::class:
        case Page::class:
            $bannerImage = MetaBox::getMetaData($data, 'banner_image', true);

            $form
                ->addAfter('image', 'banner_image', is_in_admin(true) ? 'mediaImage' : 'customImage', [
                    'label' => __('Banner image (1920x170px)'),
                    'label_attr' => ['class' => 'control-label'],
                    'value' => $bannerImage,
                ]);

            break;
    }

    return $form;
}, 124, 3);

add_action([BASE_ACTION_AFTER_CREATE_CONTENT, BASE_ACTION_AFTER_UPDATE_CONTENT], function (string $type, IlluminateRequest $request, Model $object): void {
    switch (get_class($object)) {
        case Post::class:
        case Page::class:
            if ($request->has('banner_image')) {
                MetaBox::saveMetaBoxData($object, 'banner_image', $request->input('banner_image'));
            }

            break;
    }
}, 175, 3);

Form::component('themeIcon', Theme::getThemeNamespace() . '::partials.icons-field', [
    'name',
    'value' => null,
    'attributes' => [],
]);

add_filter('form_custom_fields', function (FormAbstract $form, FormHelper $formHelper): FormAbstract {
    if (!$formHelper->hasCustomField('themeIcon')) {
        $form->addCustomField('themeIcon', ThemeIconField::class);
    }

    return $form;
}, 29, 2);
