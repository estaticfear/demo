<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th class="text-center">STT</th>
                <th>Loại công sức</th>
                <th class="text-end">Số ngày công</th>
                <th class="text-end">Đã đóng góp</th>
                <th class="text-end">Giá trị</th>
            </tr>
        </thead>
        <tbody class="table-light">
            @if (!empty($efforts))
                @foreach ($efforts as $i => $effort)
                    <tr>
                        <td class="text-center">{{ $i + 1 + ($offset / $limit) * $limit }}</td>
                        <td>{{ $effort['product']['name'] }}</td>
                        <td class="text-end">{{ $effort['qty'] }}</td>
                        <td class="text-end">{{ $effort['total_merit_qty'] }}</td>
                        <td class="text-end">{{ currency_format($effort['price']) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="5">Không có dữ liệu</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<div class="page-pagination mt-5 d-flex justify-content-center efforts-ajax">
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
