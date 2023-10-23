@if($config['img'])
<div class="widget-banner">
    @if($config['ads'])
    <a href="{{ $config['ads'] }}"><img src="{{ $config['img'] }}" class="widget-banner__image"></a>
    @else
    <img src="{{ $config['img'] }}" class="widget-banner__image">
    @endif
</div>
@endif