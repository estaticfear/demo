@if (!empty($page))
    <section class="section-about-us w-full py-5 my-4 bg-primary ">
        <div class="container py-4">
            <div class="row">
                <div class="col-12 col-lg-6 content-left mb-3 mb-lg-0">
                    <div class="image"
                        style="background-image: url('{{ RvMedia::getImageUrl($page->image, 'origin', false, RvMedia::getDefaultImage()) }}')">
                    </div>
                </div>
                <div class="col-12  col-lg-6 content-right">
                    <h2 class="section-about-title text-start h3">{{ $title }}</h2>
                    <div class="description-content fs-16 text-white">
                        {!! do_shortcode('[static-block alias="about-us"][/static-block]') !!}
                    </div>
                    <a href="/gioi-thieu" class="link-detail">Xem thêm <i class="ion-arrow-right-c"></i></a>
                </div>
            </div>
        </div>
    </section>
@endif
