@php

$oursMembers = $members->items();

@endphp
<style>
    .slick-slide {
        margin: 0 16px;
    }

    .slick-list {
        margin: 0 -16px;
    }
</style>
@if (!empty($oursMembers))
<div class="carousel-logo container">
    <div class="py-3 my-5 py-lg-4 my-lg-4 w-100 d-inline-block">
        @if (!empty($title))
        <h2 class="carousel-logo__title h3 text-center mb-4 mb-lg-5">{{ $title }}</h2>
        @endif
        <div class="carousel-logo__content py-3 ours-member-items" id="carousel-ours-member__content">
            @foreach ($oursMembers as $oursMember)
            <div class=" ours-member-item">
                <div class="item">
                    <div class="img-1-1">
                        <img src="{{ RvMedia::getImageUrl($oursMember->avatar, 'origin') }}" alt="{{  $oursMember->avatar }}" class="member-image" />
                    </div>
                    <h3 class="member-name h5-1 text-center">{{$oursMember->name}}</h3>
                    <h4 class="member-job-title h5-1 text-center">{{$oursMember->jobtitle}}</h4>
                    <h5 class="member-introduct sub-title-header-text text-center">{{$oursMember->introduct}}</h5>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif