<div class="form-group">
    <label class="control-label">{{ __('Title') }}</label>
    {!! Form::input('text', 'title', Arr::get($attributes, 'title'), ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    <label class="control-label">{{ __('Sub title') }}</label>
    {!! Form::input('text', 'sub', Arr::get($attributes, 'sub'), ['class' => 'form-control']) !!}
</div>