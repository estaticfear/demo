<article class="article-item">
    <div class="row">
        <div class="col-12 col-md-6 article-image-wrap">
            <div class="article-image" style="background-image: url('{{ RvMedia::getImageUrl($article->image, 'origin', false, RvMedia::getDefaultImage()) }}')">
                <a href="{{ $article->url }}" title="{{ $article->name }}" class="article__overlay"></a>
            </div>
        </div>
        <div class="content-article col-12 col-md-6 bg-white article-content-wrap ps-md-4">
            <div class="content-article-first">
                <a href="{{ $article->url }}">
                    <h3 class="title-article h5">{{$article->name}}</h3>
                </a>
                <p class="description-article description-article-text">{{$article->description}}</p>
                <a href="{{ $article->url }}" class="link-detail d-flex align-items-center gap-2">Xem chi tiết <i class="icon--arrow-right"></i></a>
            </div>
        </div>
    </div>
</article>
