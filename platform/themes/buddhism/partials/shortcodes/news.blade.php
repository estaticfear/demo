@php
$news_articles = [];
$firstArticles = [];
$secondArticles = [];

foreach ($articles as $art) {
array_push($news_articles, $art);
};

if (!empty($news_articles)) {
$firstArticles = $news_articles[0];
};

if (count($news_articles) > 1) {
$secondArticles = array_slice($news_articles, 1, 4);
};

@endphp
<section class="section pt-50 pb-50 container section-articles">
    <h2 class="section-main-title text-center h3">{{$title}}</h2>
    <span class="section-sub-title text-center sub-title-header-text">{{$sub}}</span>
    <div class="section-articles-content ">
        <div class="col-12 first-articles">
            @include(Theme::getThemeNamespace() . '::views.templates.first-article-news', ['article'=>$firstArticles])
        </div>
        <div class="row">
            @foreach($secondArticles as $article)
            <div class="col-12 col-md-6 col-xl-3 second-articles g-4">
                @include(Theme::getThemeNamespace() . '::views.templates.article-news', compact(['article']))
            </div>
            @endforeach
        </div>
    </div>
    <div class="section-btn-show-all">
        <a href="#">
            <button class="btn-show-all">
                <span>
                    Xem tất cả ({{$articles->count()}})
                </span>
            </button>
        </a>
    </div>

</section>