<?php

namespace Cmat\CustomField\Providers;

use Assets;
use Cmat\ACL\Repositories\Interfaces\RoleInterface;
use Cmat\Blog\Models\Post;
use Cmat\Blog\Repositories\Interfaces\PostInterface;
use Cmat\CustomField\Facades\CustomFieldSupportFacade;
use Cmat\Page\Models\Page;
use CustomField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        add_action(BASE_ACTION_META_BOXES, [$this, 'handle'], 125, 2);
    }

    public function handle(string $priority, ?Model $object = null): void
    {
        $reference = get_class($object);
        if (CustomField::isSupportedModule($reference) && $priority == 'advanced') {
            add_custom_fields_rules_to_check([
                $reference => isset($object->id) ? $object->id : null,
                'model_name' => $reference,
            ]);

            /**
             * Every model will have these rules by default
             */
            if (Auth::check()) {
                add_custom_fields_rules_to_check([
                    'logged_in_user' => Auth::id(),
                    'logged_in_user_has_role' => $this->app->make(RoleInterface::class)->pluck('id'),
                ]);
            }

            if (defined('PAGE_MODULE_SCREEN_NAME') && $reference == Page::class) {
                add_custom_fields_rules_to_check([
                    'page_template' => isset($object->template) ? $object->template : '',
                ]);
            }

            if (defined('POST_MODULE_SCREEN_NAME')) {
                if ($reference == Post::class && $object) {
                    $relatedCategoryIds = $this->app->make(PostInterface::class)->getRelatedCategoryIds($object);
                    add_custom_fields_rules_to_check([
                        $reference . '_post_with_related_category' => $relatedCategoryIds,
                        $reference . '_post_format' => $object->format_type,
                    ]);
                }
            }

            echo $this->render($reference, isset($object->id) ? $object->id : null);
        }
    }

    protected function render(string $reference, int|string|null $id): ?string
    {
        $customFieldBoxes = get_custom_field_boxes($reference, $id);

        if (! $customFieldBoxes) {
            return null;
        }

        Assets::addStylesDirectly([
            'vendor/core/plugins/custom-field/css/custom-field.css',
        ])
            ->addScriptsDirectly([
                'vendor/core/plugins/custom-field/js/use-custom-fields.js',
            ])
            ->addScripts(['jquery-ui']);

        CustomFieldSupportFacade::renderAssets();

        return CustomFieldSupportFacade::renderCustomFieldBoxes($customFieldBoxes);
    }
}
