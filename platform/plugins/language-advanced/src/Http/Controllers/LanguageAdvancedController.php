<?php

namespace Cmat\LanguageAdvanced\Http\Controllers;

use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Http\Controllers\BaseController;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\LanguageAdvanced\Http\Requests\LanguageAdvancedRequest;
use Cmat\LanguageAdvanced\Supports\LanguageAdvancedManager;

class LanguageAdvancedController extends BaseController
{
    public function save(int|string $id, LanguageAdvancedRequest $request, BaseHttpResponse $response)
    {
        $model = $request->input('model');

        if (! class_exists($model)) {
            abort(404);
        }

        $data = (new $model())->findOrFail($id);

        LanguageAdvancedManager::save($data, $request);

        do_action(LANGUAGE_ADVANCED_ACTION_SAVED, $data, $request);

        event(new UpdatedContentEvent('', $request, $data));

        return $response
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
}
