@php
$faqItems = $faqs->items();
@endphp
<section class="section section-faqs">
    <div class="container">
        <div class="first-and-second-content row gx-5  pb-4 ">
            <div class="col-12 col-lg-5 image-content ">
                <div class="img-7-5">
                    <img src="/themes/buddhism/images/faqs-first.jpg" alt="faqs first" />
                </div>
            </div>
            <div class="col-12 col-lg-7 text-content">
                <div class="title-content-section">
                    <h2 class="h3 title-content text-uppercase">Hướng dẫn đóng góp</h2>
                </div>
                <p class="description-content body-1__regular">Bạn có thể đóng góp các dự án qua 05 bước đơn giản bằng bất cứ tài khoản thanh toán điện tử nào mà mình có tại Văn hóa Phật giáo Việt Nam, như: Thẻ nội địa (Internet Banking); Thẻ quốc tế; Ví điện tử Viettel Money hoặc VNpay; Quét mã QR;...</p>
                <a href="/huong-dan-cong-duc" class="btn btn-primary text-white">Xem chi tiết</a>
            </div>
        </div>
        <div class="first-and-second-content row gx-5 flex-row-reverse pb-4">
            <div class="col-12 col-lg-5 image-content ">
                <div class="img-7-5">
                    <img src="/themes/buddhism/images/faqs-second.jpg" alt="faqs first" />
                </div>
            </div>
            <div class="col-12 col-lg-7 text-content">
                <div class="title-content-section">
                    <h2 class="h3 title-content">HỢP TÁC VỚI VĂN HÓA PHẬT GIÁO VIỆT NAM?</h2>
                </div>
                <p class="description-content body-1__regular">Văn hóa Phật giáo Việt Nam cho phép các tổ chức có chức năng vận động và tiếp nhận tài trợ thực hiện việc khởi tạo các dự án gây quỹ trên nền tảng Văn hóa Phật giáo Việt Nam. Nếu bạn là một tổ chức được cấp phép hoạt động, như: Quỹ từ thiện; Quỹ xã hội; Doanh nghiệp xã hội;…vui lòng liên hệ với Văn hóa Phật giáo Việt Nam để được hỗ trợ.</p>
                <a href="/lien-he" class="btn btn-primary text-white">Liên hệ với chúng tôi</a>
            </div>
        </div>
        @if(!empty($faqItems))
        <div class="last-content">
            <div class="section-last-content-title pb-4 d-flex justify-content-center">
                <div class="section-content-title">
                    <h1 class="h3 text-center last-content-title pb-4">FAQs</h1>
                </div>
            </div>
            <div class="section-faqs-items">
                @foreach($faqItems as $faq)
                <div class="faqs-item">
                    <div data-bs-toggle="collapse" href="#faq{{$faq->id}}" aria-expanded="false" aria-controls="faq{{$faq->id}}" class="d-flex align-items-center">
                        <i class="ion-chevron-down"></i>
                        <h3 class="title-faqs-item-content h5-1">{{ $faq->question }}</h3>
                    </div>
                    <div class="collapse" id="faq{{$faq->id}}">
                        <div class="card card-body content-faqs-item-content description-article-text ">
                            {!! $faq->answer !!}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
