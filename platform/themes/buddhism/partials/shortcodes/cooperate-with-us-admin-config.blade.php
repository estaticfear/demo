<div class="form-group">
    <label class="control-label">{{ __('Nội dung') }}</label>
    {!! Form::input('textarea', 'coop', Arr::get($attributes, 'coop'), ['class' => 'form-control']) !!}
</div>