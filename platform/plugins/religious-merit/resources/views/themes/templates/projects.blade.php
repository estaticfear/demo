@if ($projects->count() > 0)
    <div class="row g-4 project">
        @foreach ($projects as $project)
            @include('plugins/religious-merit::themes.templates.project', compact(['project']))
        @endforeach
    </div>
    <div class="page-pagination mt-5 d-flex justify-content-center">
        {!! $projects->withQueryString()->links() !!}
    </div>
@endif

@include('plugins/religious-merit::partials.modal-merit', [
    'type' => '',
    'name' => 'modal-merit',
    'title' => trans('plugins/religious-merit::religious-merit.select_payment_gate'),
    'submit_text' => trans('plugins/religious-merit::religious-merit.merit'),
    'action_button_attributes' => [
        'class' => 'delete-crud-entry',
    ],
])

@include('plugins/religious-merit::partials.modal-merit-report', [
    'type' => '',
    'name' => 'modal-merit-report',
    'title' => trans('plugins/religious-merit::religious-merit.report'),
    'download_text' => trans('plugins/religious-merit::religious-merit.download-report'),
    'action_button_attributes' => [
        'class' => 'delete-crud-entry',
    ],
])


