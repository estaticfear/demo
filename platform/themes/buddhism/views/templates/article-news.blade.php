<article class="article-item">
    <div class="img-16-9 article-image">
        <img class="rounded-1" src="{{ RvMedia::getImageUrl($article->image, 'origin', false, RvMedia::getDefaultImage()) }}" alt="{{ $article->name }}" loading="lazy">
        <a href="{{ $article->url }}" title="{{ $article->name }}" class="article__overlay"></a>
    </div>
    <div class="content-article bg-white">
        <a href="{{ $article->url }}">
            <h3 class="title-article h5-1">{{ $article->name }}</h3>
        </a>
        <p class="description-article description-article-text">{{$article->description}}</p>
        <a href="{{ $article->url }}" class="link-detail d-flex align-items-center gap-2">Xem chi tiết <i class="icon--arrow-right"></i></a>
    </div>
</article>
