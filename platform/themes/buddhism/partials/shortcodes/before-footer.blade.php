<section class="before-footer text-center d-flex justify-content-center align-items-center flex-column">
    <div class="before-footer--container">
        <h2 class="before-footer--content__title h1">{{ $title }}</h2>
        <div class="before-footer--content__subtitle"><span class="body-1__semibold">{{ $sub_title }}</span></div>
        <div class="before-footer--content__description">
            <div class="body-1__regular">
                {!! $text_content !!}
            </div>
            </div>
        <div class="before-footer--action">
            <a href="/<?= get_projects_prefix() ?>/da-ket-thuc"><button class="before-footer--action__button-1 btn btn-secondary"><span class="body-2__medium">Báo cáo</span></button></a>
            <a href="/lien-he"><button class="before-footer--action__button-2 btn btn-secondary"><span class="body-2__medium">Liên hệ với chúng tôi</span></button></a>
        </div>
    </div>
</section>
