<article class="post post--single detail-post">
    <header class="post__header">
        <h1 class="post__title h3">{!! BaseHelper::clean($post->name) !!}</h1>
        <div class="post__meta">
            <span class="post__created-at text-capitalize">{{ $post->created_at->locale('vi')->translatedFormat('l, d/m/Y h:i') }}</span>
        </div>
    </header>
    <div class="post__content">
        @if (defined('GALLERY_MODULE_SCREEN_NAME') && !empty($galleries = gallery_meta_data($post)))
        {!! render_object_gallery($galleries, ($post->first_category ? $post->first_category->name : __('Uncategorized'))) !!}
        @endif
        {!! BaseHelper::clean($post->content) !!}
        <div class="fb-like" data-href="{{ request()->url() }}" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
    </div>
    <div class="news_social ml-auto d-block">
        <h4 class="body-2__medium title-share">Chia sẻ bài viết</h4>
        {!! Theme::partial('button-share', ['post' => $post, 'isHaveLogo' => 'yes' ]) !!}
    </div>
    <br>
    {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, theme_option('facebook_comment_enabled_in_post', 'yes') == 'yes' ? Theme::partial('comments') : null) !!}
</article>