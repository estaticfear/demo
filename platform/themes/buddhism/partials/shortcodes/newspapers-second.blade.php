@php
@endphp
<section class="section  pb-50 section-newspapers-second">
    <div class="container ">
        <div class="section-newspapers-second-title">
            <h1 class="h4 newspapers-title">{{ $title }}</h1>
        </div>
        <div class="row">
            <div class="col-12 col-lg-9">
                <div class="item row">
                    <div class="col-4 item-content-left">
                        <div class="img-16-9 newspapers-image">
                            <img class="rounded-1" src="/themes/buddhism/images/news-papers.jpg" loading="lazy">
                        </div>
                    </div>
                    <div class="col-8 item-content-right">
                        <h3 class="h5-1 item-content-right-title">Bỉ trở thành nước EU thứ hai công nhận đạo Phật là tôn giáo chính thức</h3>
                        <h4 class="d-none d-md-block description-article-text item-content-right-description ">
                            Bỉ tiến tới công nhận đạo Phật là tôn giáo chính thức tại nước này sau khi chính quyền liên bang thông qua dự luật hôm 17.3, mở đường cho đoàn thể tăng lữ, Phật tử tiếp cận nguồn ngân sách liên bang, mở trường học…
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                {!! dynamic_sidebar('newspappers') !!}
            </div>
        </div>
    </div>
</section>