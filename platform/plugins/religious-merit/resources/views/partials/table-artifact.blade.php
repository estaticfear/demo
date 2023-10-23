<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th class="text-center">STT</th>
                <th>Ảnh hiện vật</th>
                <th>Tên hiện vật</th>
                <th class="text-end">Số lượng yêu cầu</th>
                <th class="text-end">Đã đóng góp</th>
                <th class="text-end">Giá trị</th>
            </tr>
        </thead>
        <tbody class="table-light">
            @if (!empty($artifacts))
                @foreach ($artifacts as $i => $artifact)
                    <tr class="align-middle">
                        <td class="text-center">{{ $i + 1 + ($offset / $limit) * $limit }}</td>
                        <td><img src="{{ RvMedia::getImageUrl($artifact['product']['image'], 'medium', false, RvMedia::getDefaultImage()) }}" alt="{{ $artifact['product_name'] }}" loading="lazy" class="project-artifact-image"></td>
                        <td>{{ $artifact['product']['name'] }}</td>
                        <td class="text-end">{{ $artifact['qty'] }}</td>
                        <td class="text-end">{{ $artifact['total_merit_qty'] }}</td>
                        <td class="text-end">{{ currency_format($artifact['price']) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="6">Không có dữ liệu</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<div class="page-pagination mt-5 d-flex justify-content-center artifacts-ajax">
    <nav>
        <ul class="pagination">
            @if ($totalItems > $limit)
                @if ($page === 1)
                    <li class="page-item disabled"><span class="page-link">‹</span></li>
                @else
                    <li class="page-item"><a href="?page={{ $page - 1 }}" class="page-link">‹</a></li>
                @endif
                @for ($i = 1; $i <= $totalPages; $i++)
                    <li class="page-item @if ($page === $i) active @endif"><a href="?page={{ $i }}" class="page-link">{{ $i }}</a></li>
                @endfor
                @if ($page === $totalPages)
                    <li class="page-item disabled"><span class="page-link">›</span></li>
                @else
                    <li class="page-item"><a href="?page={{ $page + 1 }}" class="page-link">›</a></li>
                @endif
            @endif
        </ul>
    </nav>
</div>
