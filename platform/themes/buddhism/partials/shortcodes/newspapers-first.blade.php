@php
$news_newspapers = [];
$firstNewspapers = [];
$secondNewspapers = [];
$lastNewspapers = [];

foreach ($newspapers as $art) {
array_push($news_newspapers, $art);
};

if (!empty($news_newspapers)) {
$firstNewspapers = $news_newspapers[0];
};

if (count($news_newspapers) > 1) {
$secondNewspapers = array_slice($news_newspapers, 1, 2);
};

if (count($news_newspapers) > 3) {
$lastNewspapers = array_slice($news_newspapers, 3, 4);
};
@endphp
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