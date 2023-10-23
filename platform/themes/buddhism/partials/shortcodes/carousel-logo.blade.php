@php
    $gals = get_gallery_by_id($id);
    $images = [];
    foreach ($gals as $gal) {
        $obj = (object) $gal;
        array_push($images, $obj);
    }
@endphp
<style>
    .slick-slide {
        margin: 0 16px;
    }

    .slick-list {
        margin: 0 -16px;
    }
</style>
@if (!empty($images))
    <div class="carousel-logo container">
        <div class="py-3 my-5 py-lg-4 my-lg-4 w-100 d-inline-block">
            @if (!empty($title))
                <h2 class="carousel-logo__title h3 text-center mb-4 mb-lg-5">{{ $title }}</h2>
            @endif

            <div class="carousel-logo__content py-3">
                @foreach ($images as $image)
                    <div class="bg-white px-4 py-3 rounded-3">
                        <img src="{{ RvMedia::getImageUrl($image->img, 'origin') }}"
                            alt="{{ explode('||', $image->description)[0] }}" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
