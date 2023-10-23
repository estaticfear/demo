<?php //dd($merits)
?>
<div class="table-responsive">
    <div class="upload-image-message"></div>
    <table class="my-3 table-personal-merit table table-bordered">
        <thead>
            <tr>
                <th scope="col" class="body-2__medium ">STT</th>
                <th scope="col" class="body-2__medium">Tên dự án</th>
                <th scope="col" class="body-2__medium">Ảnh giao dịch</th>
                <th scope="col" class="body-2__medium">Thời gian</th>
                <th scope="col" class="body-2__medium">Loại Công Đức</th>
                <th scope="col" class="body-2__medium">Giá trị</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($merits as $key => $merit)
                <tr>
                    <th scope="row" class="body-2__regular">
                        {{ $merit->id }}
                        {{ $key + 1 + $perPage * ($currentPage - 1) }}</th>
                    <td class="body-2__regular">{{ $merit->project->name }}</td>
                    <td class="body-2__regular">
                        @if ($merit->transaction_image_url)
                            <a href="<?= $merit->transaction_image_url ?>"
                                data-lightbox="<?= 'transaction-image-' . $merit->id ?>">
                                <img class="img-thumbnail" width="150" src="<?= $merit->transaction_image_url ?>"
                                    alt="transaction image" />
                            </a>
                        @endif
                        <div class="mt-2">
                            @if ($merit->status != 'success')
                            {!! Form::open(['route' => 'public.religious-merit.upload-transaction-image', 'class' => 'upload-transaction-form']) !!}
                                <input type="file" name="file" accept=".jpg, .png, .jpeg" class="mb-3 input-upload-image">
                                <input type="hidden" name="merit_id" value="<?= $merit->id ?>" />
                                <button type="button" class="btn btn-primary btn-upload-image">Tải ảnh lên</button>
                            {!! Form::close() !!}
                            @endif
                        </div>
                    </td>
                    <td class="body-2__regular">
                        {{ Carbon\Carbon::parse($merit->created_at)->format('h:i - d/m/Y') }}</td>
                    <td class="body-2__regular">
                        <?php echo $merit->type->toHtml(); ?>
                    </td>
                    <td class="body-2__regular">{{ currency_format($merit->amount, 'đ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="page-pagination mt-5 d-flex justify-content-center ajax">
    {!! $merits->withQueryString()->links() !!}
</div>

<script>
    $(function () {
        lightbox.option({
            'fadeDuration': 200,
            'resizeDuration': 300
        })
    })
</script>
