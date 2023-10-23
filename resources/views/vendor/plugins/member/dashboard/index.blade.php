@extends('plugins/member::layouts.skeleton')
@section('content')
    @php
        $perPage = 10;
        $status = $_GET['status'] ?? 'success';
        $amount = $_GET['amount'] ?? '';
        $order = $amount ? ['amount' => $amount, 'id' => 'DESC'] : ['id' => 'DESC'];
        $keyword = $_GET['keyword'] ?? '';
        $merits = get_my_merits($perPage, $keyword, $order, ['status' => $status]);
        $currentPage = $merits->currentPage();
        $meritsReport = get_my_merits_report();
        $totalMeritsProject = $meritsReport['total_project'];
        if ($totalMeritsProject == 0) {
            $totalMeritsAmount = '0.00';
        } else {
            $totalMeritsAmount = $meritsReport['total_amount'];
        }
    @endphp
    <div class="personal--info">
        <div class="personal--info__banner position-relative">
            <div class="container">
                <div class="row">
                    <div class="personal--info--general position-absolute d-flex gap-4">
                        <div class="avt">
                            <img src="{{ $user->avatar_url }}" alt="Avatar" class="br-100">
                        </div>
                        <h1 class="personal--info--general__name h3">
                            @php
                                $generalName = $user->email;
                                if ($user->last_name && $user->first_name) {
                                    $generalName = $user->first_name . ' ' . $user->last_name;
                                } elseif ($user->last_name) {
                                    $generalName = $user->last_name;
                                } elseif ($user->first_name) {
                                    $generalName = $user->first_name;
                                }
                                echo $generalName;
                            @endphp
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row personal--info__merit-project">
                <h3 class="personal-title col-12 col-lg-5 h3 mb-3">
                    Các dự án bạn đã Công Đức
                </h3>
                <div class="total-project col-12 col-lg-3 d-flex gap-3 d-lg-block">
                    <p class="total-project__title body-1__regular">Số dự án đã Công Đức</p>
                    <p class="total-project__value h3">{{ $totalMeritsProject }}</p>
                </div>
                <div class="total-amount col-12 col-lg-4 d-flex gap-3 d-lg-block">
                    <p class="total-amount__title body-1__regular">Tổng số tiền đã Công Đức</p>
                    <p class="total-amount__value h3">{{ currency_format($totalMeritsAmount) }}</p>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="mt-4 personal--merit__tabs">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item mr-2" role="presentation">
                        <form action="" method="GET">
                            <input type="text" hidden name="status" value="success">
                            <button class="nav-link px-4 py-3 @php if($status === 'success') { echo 'active'; } @endphp"
                                id="success-tab" data-bs-toggle="tab" data-bs-target="#success" type="submit"
                                role="tab" aria-controls="success" aria-selected="true">Đã hoàn thành</button>
                        </form>
                    </li>
                    <li class="nav-item mx-2" role="presentation">
                        <form action="" method="GET">
                            <input type="text" hidden name="status" value="in-progress">
                            <button class="nav-link px-4 py-3 @php if($status === 'in-progress') { echo 'active'; } @endphp"
                                id="unsuccess-tab" data-bs-toggle="tab" data-bs-target="#unsuccess" type="submit"
                                role="tab" aria-controls="unsuccess" aria-selected="false">Chưa hoàn thành</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        @if ($totalMeritsProject === 0)
            <div class="container page-section">
                <div class="personal--merit__nodata">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="body-2__regular personal--merit__nodata__message">Hiện tại bạn chưa Công Đức cho dự
                                án nào. Hãy khám phá các dự án của chúng tôi và tìm một dự án phù hợp để thực hiện điều đó
                                nhé.</div>
                            <a href="/hoat-dong/dang-trien-khai"><button
                                    class="btn btn-primary body-2__medium personal--merit__nodata__button">Khám Phá Dự
                                    Án</button></a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="personal--merit__detail page-section">
                <div class="container">
                    <form action="" method="GET">
                        <input type="text" hidden name="status" value="{{ $status }}">
                        <div class="row">
                            <div class="col-6 col-lg-3 d-flex">
                                <div class="input-group border rounded">
                                    <span class="input-group-text bg-white border-0">
                                        <img src="/vendor/core/plugins/religious-merit/images/search.png" alt="search">
                                    </span>
                                    <input type="text" name="keyword" value="{{ $keyword }}"
                                        class="form-control input-search bg-white border-0" placeholder="Tìm dự án"
                                        aria-label="Tìm dự án">
                                </div>
                                <button type="submit" hidden></button>
                            </div>
                            <div class="col-6 col-lg-3 d-flex">
                                <select class="form-select select-search" name="amount" onchange="this.form.submit()">
                                    <option value>Lọc theo</option>
                                    <option value="desc" @php if($amount === 'desc') { echo 'selected'; } @endphp>Giá trị
                                        Tiền giảm dần</option>
                                    <option value="asc" @php if($amount === 'asc') { echo 'selected'; } @endphp>Giá trị
                                        Tiền tăng dần</option>
                                </select>
                            </div>
                        </div>
                    </form>
                    @include('plugins/member::modals.table-personal-merit')
                </div>
            </div>
        @endif
    @endsection
