<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th class="text-center">STT</th>
                <th>Tên hạng mục</th>
                <th class="text-end">Số lượng</th>
                <th class="text-end">Đơn vị tính</th>
                <th class="text-end">Thành tiền</th>
                <th class="text-end">Trạng thái</th>
            </tr>
        </thead>
        <tbody class="table-light">
            @if (!empty($budgets))
                @foreach ($budgets as $i => $budget)
                    <tr>
                        <td class="text-center">{{ $i + 1 + ($offset / $limit) * $limit }}</td>
                        @php $qty = 0; $totalSpent = 0 @endphp
                        @foreach ($budget as $item)
                            @php if ($item['key'] === 'total_spent') $totalSpent = $item['value'] @endphp
                            @if ($item['key'] === 'name')
                                <td>
                                    {{ $item['value'] }}
                                </td>
                            @elseif ($item['key'] === 'qty')
                                <td class="text-end">
                                    @php $qty = $item['value'] @endphp
                                    {{ currency_format($item['value'], '') }}
                                </td>
                            @elseif ($item['key'] === 'unit')
                                <td class="text-end">
                                    {{ $item['value'] }}
                                </td>
                            @elseif ($item['key'] === 'cost_per_unit')
                                <td class="text-end">
                                    {{ currency_format($item['value'] * $qty) }}
                                </td>
                            @elseif ($item['key'] === 'status')
                                <td class="text-end">
                                    @if ($item['value'] == 1)
                                        Đã hoàn thành
                                    @elseif ($totalSpent > 0)
                                        Đã chi: {{ currency_format($totalSpent) }}
                                    @else
                                        Chờ triển khai
                                    @endif
                                </td>
                            @endif
                        @endforeach
                        @php $qty = 0; $totalSpent = 0 @endphp
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
<div class="page-pagination mt-5 d-flex justify-content-center budgets-ajax">
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
