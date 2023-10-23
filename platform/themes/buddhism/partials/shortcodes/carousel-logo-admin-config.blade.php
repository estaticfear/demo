<div class="form-group">
    <label class="control-label">{{ __('Title') }}</label>
    {!! Form::input('text', 'title', Arr::get($attributes, 'title'), ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    <label class="control-label">{{ __('Gallery ID') }}</label>
    {!! Form::input('text', 'id', Arr::get($attributes, 'id'), ['class' => 'form-control']) !!}
</div>