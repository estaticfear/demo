<div class="form-group">
    <label class="control-label">{{ __('Title') }}</label>
    {!! Form::input('text', 'title', Arr::get($attributes, 'title'), ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    <label class="control-label">{{ __('Sub title') }}</label>
    {!! Form::input('text', 'sub_title', Arr::get($attributes, 'sub_title'), ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    <label class="control-label">{{ __('Content') }}</label>
    {!! Form::textarea('text_content', Arr::get($attributes, 'text_content'), ['class' => 'form-control']) !!}
</div>
