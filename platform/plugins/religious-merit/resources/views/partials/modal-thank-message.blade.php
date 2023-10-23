<div class="modal fade {{ $class }}" tabindex="-1" aria-labelledby="thank-message-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        <div class="modal-body p-3 p-lg-5 d-flex flex-column justify-content-center align-items-center">
            {!! do_shortcode('[static-block alias="thank-message"][/static-block]') !!}
        </div>
      </div>
    </div>
</div>