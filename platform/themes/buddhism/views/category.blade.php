@php Theme::set('section-name', $category->name) @endphp

@php
$arr = [];

$categoryPosts = get_featured_and_other_posts_by_category($category->id, 5 );

if(!empty($categoryPosts)){
foreach($categoryPosts as $cta){
array_push($arr, $cta);
}
};

$featuredNewspapers = [];
$firstNewspapers = [];
$secondNewspapers = [];
$lastNewspapers = [];

if(!empty($arr)){
foreach ($arr[0] as $art) {
array_push($featuredNewspapers, $art);
}
};

if (!empty($featuredNewspapers)) {
$firstNewspapers = $featuredNewspapers[0];
};

if (count($featuredNewspapers) > 1) {
$secondNewspapers = array_slice($featuredNewspapers, 1, 2);
};

if (count($featuredNewspapers) > 3) {
$lastNewspapers = array_slice($featuredNewspapers, 3, 4);
};

@endphp

@if(!empty($arr[0]))
<section class="section pt-50 section-newspapers">
    <div class="container ">
        <div class="section-newspapers-content-first">
            <div class="row section-content">
                <div class="col-12 col-lg-8 section-newspapers-first-left">
                    @if(!empty($firstNewspapers))
                    <div class="newspapers-item">
                        <div class="img-16-9 newspapers-image">
                            <img class="rounded-1" src="{{ RvMedia::getImageUrl($firstNewspapers->image, 'origin', false, RvMedia::getDefaultImage()) }}" alt="{{ $firstNewspapers->name }}" loading="lazy">
                            <a href="{{ $firstNewspapers->url }}" title="{{ $firstNewspapers->name }}" class="article__overlay"></a>
                        </div>
                        <a href="{{ $firstNewspapers->url }}" title="{{ $firstNewspapers->name }}" class="article__overlay">
                            <h2 class="h4 newspapers-title">{{$firstNewspapers->name}}</h2>
                        </a>
                        <h3 class="body-2__regular newspapers-description">{{$firstNewspapers->description}}</h3>
                    </div>
                    @endif
                </div>
                <div class="col-12 col-lg-4 section-newspapers-right">
                    @if(!empty($secondNewspapers))
                    @foreach($secondNewspapers as $secondNewspaper)
                    <div class="newspapers-item">
                        <div class="img-16-9 ">
                            <img class="rounded-1" src="{{ RvMedia::getImageUrl($secondNewspaper->image, 'origin', false, RvMedia::getDefaultImage()) }}" alt="{{ $secondNewspaper->name }}" loading="lazy">
                            <a href="{{ $secondNewspaper->url }}" title="{{ $secondNewspaper->name }}" class="article__overlay"></a>
                        </div>
                        <a href="{{ $secondNewspaper->url }}" title="{{ $secondNewspaper->name }}" class="article__overlay">
                            <h2 class="h5-1 newspapers-title">{{$secondNewspaper->name}}</h2>
                        </a>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
        @if(!empty($lastNewspapers))
        <div class="section-newspapers-content-second">
            <div class="row">
                @foreach($lastNewspapers as $secondNewspaper)
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="newspapers-item">
                        <div class="img-16-9 newspapers-image">
                            <img class="rounded-1" src="{{ RvMedia::getImageUrl($secondNewspaper->image, 'origin', false, RvMedia::getDefaultImage()) }}" alt="{{ $secondNewspaper->name }}" loading="lazy">
                            <a href="{{ $secondNewspaper->url }}" title="{{ $secondNewspaper->name }}" class="article__overlay"></a>
                        </div>
                        <a href="{{ $secondNewspaper->url }}" title="{{ $secondNewspaper->name }}" class="article__overlay">
                            <h2 class="h5-1 newspapers-title">{{$secondNewspaper->name}}</h2>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endif
@if(!empty($arr[1]))
<section class="section  pb-50 section-newspapers-second">
    <div class="container ">
        <div class="section-newspapers-second-title">
            <h1 class="h4 newspapers-title">Báo chí</h1>
        </div>
        <div class="row section-newspapers-second-content">
            <div class="col-12 col-lg-9">
                @if(!empty($arr[1]))
                @foreach($arr[1] as $item)
                <div class="item row">
                    <div class="col-4 item-content-left">
                        <div class="img-16-9 newspapers-image">
                            <img class="rounded-1" src="{{ RvMedia::getImageUrl($item->image, 'origin', false, RvMedia::getDefaultImage()) }}" alt="{{ $item->name }}" loading="lazy">
                            <a href="{{ $item->url }}" title="{{ $item->name }}" class="article__overlay"></a>
                        </div>
                    </div>
                    <div class="col-8 item-content-right">
                        <a href="{{ $item->url }}" title="{{ $item->name }}" class="article__overlay">
                            <h3 class="h5-1 item-content-right-title">{{$item->name}}</h3>
                        </a>
                        <h4 class="d-none d-md-block description-article-text item-content-right-description ">
                            {{$item->description}}
                        </h4>
                    </div>
                </div>
                @endforeach
                @endif
                <div class="page-pagination  d-flex justify-content-center">
                    {!! $arr[1]->withQueryString()->links() !!}
                </div>
            </div>
            <div class="col-12 col-lg-3 section-banner">
                {!! dynamic_sidebar('newspappers') !!}
            </div>
        </div>
    </div>
</section>
@endif

@if(empty($arr[1]) && empty($arr[0]))
<section class="section pb-50 pt-50 section-empty-data d-flex justify-content-center align-items-center">
    <div class="d-flex flex-column justify-content-center">
        <div class="img-13-10">
            <img src="/themes/buddhism/images/empty.png" alt="Empty" />
        </div>
        <h4 class="body-2__regular text-center pt-3">
            Hiện tại VĂN HÓA PHẬT GIÁO VIỆT NAM chưa có tin tức
        </h4>
    </div>
</section>
@endif
