<a href="https://www.facebook.com/sharer/sharer.php?u={{$post->url}}" target="_blank" class="section-button-share">
    <button class="btn mb-0 <?php if (empty($isHaveLogo)) :
                                echo 'btn-share-no-logo';
                            else :
                                echo 'btn-share';
                            endif; ?>">
        <div class="d-flex align-items-center gap-2">
            @if(!empty($isHaveLogo))
            <div class="icon-facebook">
                <div class="img-1-1">
                    <img src="/themes/buddhism/images/fb.jpg" alt="Facebook" class="icon-button" />
                </div>
            </div>
            @endif
            <span class="body-2__medium">Chia sẻ</span>
        </div>
    </button>
</a>