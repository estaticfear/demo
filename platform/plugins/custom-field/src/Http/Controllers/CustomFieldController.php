<?php

namespace Cmat\CustomField\Http\Controllers;

use Assets;
use Cmat\Base\Events\BeforeEditContentEvent;
use Cmat\Base\Forms\FormBuilder;
use Cmat\Base\Http\Controllers\BaseController;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\CustomField\Actions\CreateCustomFieldAction;
use Cmat\CustomField\Actions\DeleteCustomFieldAction;
use Cmat\CustomField\Actions\ExportCustomFieldsAction;
use Cmat\CustomField\Actions\ImportCustomFieldsAction;
use Cmat\CustomField\Actions\UpdateCustomFieldAction;
use Cmat\CustomField\Forms\CustomFieldForm;
use Cmat\CustomField\Http\Requests\CreateFieldGroupRequest;
use Cmat\CustomField\Http\Requests\UpdateFieldGroupRequest;
use Cmat\CustomField\Repositories\Interfaces\FieldGroupInterface;
use Cmat\CustomField\Repositories\Interfaces\FieldItemInterface;
use Cmat\CustomField\Tables\CustomFieldTable;
use CustomField;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CustomFieldController extends BaseController
{
    public function __construct(protected FieldGroupInterface $fieldGroupRepository, protected FieldItemInterface $fieldItemRepository)
    {
    }

    public function index(CustomFieldTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/custom-field::base.page_title'));

        Assets::addScriptsDirectly('vendor/core/plugins/custom-field/js/import-field-group.js')
            ->addScripts(['blockui']);

        return $dataTable->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/custom-field::base.form.create_field_group'));

        Assets::addStylesDirectly([
            'vendor/core/plugins/custom-field/css/custom-field.css',
            'vendor/core/plugins/custom-field/css/edit-field-group.css',
        ])
            ->addScriptsDirectly('vendor/core/plugins/custom-field/js/edit-field-group.js')
            ->addScripts(['jquery-ui']);

        return $formBuilder->create(CustomFieldForm::class)->renderForm();
    }

    public function store(CreateFieldGroupRequest $request, CreateCustomFieldAction $action, BaseHttpResponse $response)
    {
        $result = $action->run($request->input());

        $hasError = false;
        $message = trans('core/base::notices.create_success_message');
        if ($result['error']) {
            $hasError = true;
            $message = Arr::first($result['messages']);
        }

        return $response
            ->setError($hasError)
            ->setPreviousUrl(route('custom-fields.index'))
            ->setNextUrl(route('custom-fields.edit', $result['data']['id']))
            ->setMessage($message);
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        Assets::addStylesDirectly([
            'vendor/core/plugins/custom-field/css/custom-field.css',
            'vendor/core/plugins/custom-field/css/edit-field-group.css',
        ])
            ->addScriptsDirectly('vendor/core/plugins/custom-field/js/edit-field-group.js')
            ->addScripts(['jquery-ui']);

        $fieldGroup = $this->fieldGroupRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $fieldGroup));

        page_title()->setTitle(trans('plugins/custom-field::base.form.edit_field_group') . ' "' . $fieldGroup->title . '"');

        $fieldGroup->rules_template = CustomField::renderRules();

        return $formBuilder->create(CustomFieldForm::class, ['model' => $fieldGroup])->renderForm();
    }

    public function update(
        int|string $id,
        UpdateFieldGroupRequest $request,
        UpdateCustomFieldAction $action,
        BaseHttpResponse $response
    ) {
        $result = $action->run($id, $request->input());

        $message = trans('core/base::notices.update_success_message');
        if ($result['error']) {
            $response->setError();
            $message = Arr::first($result['messages']);
        }

        return $response
            ->setPreviousUrl(route('custom-fields.index'))
            ->setMessage($message);
    }

    public function destroy(int|string $id, BaseHttpResponse $response, DeleteCustomFieldAction $action)
    {
        try {
            $action->run($id);

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response, DeleteCustomFieldAction $action)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $action->run($id);
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getExport(ExportCustomFieldsAction $action, $id = null)
    {
        $ids = [];

        if (! $id) {
            foreach ($this->fieldGroupRepository->all() as $item) {
                $ids[] = $item->id;
            }
        } else {
            $ids[] = $id;
        }

        $json = $action->run($ids)['data'];

        return response()->json($json, 200, [
            'Content-type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="export-field-group.json"',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function postImport(ImportCustomFieldsAction $action, Request $request)
    {
        $json = (array)$request->input('json_data', []);

        return $action->run($json);
    }
}
