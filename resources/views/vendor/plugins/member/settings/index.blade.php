@extends('plugins/member::layouts.skeleton')
{!! Theme::partial('banner-top') !!}
@section('content')
  <div class="settings crop-avatar">
    <div class="container">
      <div class="p-5">
        <h3 class="h3 text-primary mb-4">Chỉnh sửa thông tin</h3>
        <form action="{{ route('public.member.post.settings') }}" id="setting-form" method="POST">
        @csrf
          <div class="row">
            <div class="col-12 col-sm-6 form-group mb-4">
              <label for="first_name">Tên</label>
              <input type="text" class="form-control bg-white border-0 py-2" name="first_name" id="first_name" required value="{{ old('first_name') ?? $user->first_name }}">
            </div>
            <div class="col-12 col-sm-6 form-group mb-4">
              <label for="last_name">Họ</label>
              <input type="text" class="form-control bg-white border-0 py-2" name="last_name" id="last_name" required value="{{ old('last_name') ?? $user->last_name }}">
            </div>
            <div class="col-12 col-sm-6 form-group mb-4">
              <label for="phone">Số điện thoại</label>
              <input type="text" class="form-control bg-white border-0 py-2" name="phone" id="phone" value="{{ old('phone') ?? $user->phone }}">
            </div>
            <div class="col-12 col-sm-6 form-group mb-4">
              <label for="email">{{ trans('plugins/member::dashboard.email') }}</label>
              <input type="email" class="form-control bg-white border-0 py-2" name="email" id="email" readonly placeholder="{{ trans('plugins/member::dashboard.email_placeholder') }}" required value="{{ old('email') ?? $user->email }}">
            </div>
            <div class="col-12 col-sm-6 form-group mb-4">
              <label for="gender">Giới tính</label>
              <select class="form-control bg-white border-0 py-2" name="gender" id="gender">
                <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>{{ trans('plugins/member::dashboard.gender_male') }}</option>
                <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>{{ trans('plugins/member::dashboard.gender_female') }}</option>
                <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>{{ trans('plugins/member::dashboard.gender_other') }}</option>
              </select>
            </div>
            <div class="col-12 col-sm-6 form-group">
              <label for="dob">Ngày sinh</label>
              <div class="birthday-box">
                <select id="year" name="year" class="form-control bg-white border-0 py-2{{ $errors->has('year') ? ' is-invalid' : '' }}" style="width: 74px!important; display: inline-block!important;" onchange="changeYear(this)"></select>
                <select id="month" name="month" class="form-control bg-white border-0 py-2{{ $errors->has('month') ? ' is-invalid' : '' }}" style="width: 90px!important; display: inline-block!important;" onchange="changeMonth(this)"></select>
                <select id="day" name="day" class="form-control bg-white border-0 py-2{{ $errors->has('day') ? ' is-invalid' : '' }}" style="width: 74px!important; display: inline-block!important;"></select>
                <span class="invalid-feedback">
                  <strong>{{ $errors->has('dob') ? $errors->first('dob') : '' }}</strong>
                </span>
              </div>
            </div>
          </div>
          <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary w-25">Lưu lại thông tin</button>
          </div>
        </form>
      </div>
    </div>
    @include('plugins/member::modals.avatar')
  </div>
@endsection
@push('scripts')
  <script type="text/javascript" src="{{ asset('vendor/core/core/js-validation/js/js-validation.js')}}"></script>
  {!! JsValidator::formRequest(\Cmat\Member\Http\Requests\SettingRequest::class); !!}
  <script type="text/javascript">
      "use strict";

      let numberDaysInMonth = [31,28,31,30,31,30,31,31,30,31,30,31];

    $(document).ready(function() {
      initSelectBox();
    });

    function initSelectBox() {
      let oldBirthday = '{{ $user->dob }}';
      let selectedDay = '';
      let selectedMonth = '';
      let selectedYear = '';

      if (oldBirthday !== '') {
        selectedDay = parseInt(oldBirthday.substr(8, 2));
        selectedMonth = parseInt(oldBirthday.substr(5, 2));
        selectedYear = parseInt(oldBirthday.substr(0, 4));
      }

      let dayOption = `<option value="">{{ trans('plugins/member::dashboard.day_lc') }}</option>`;
      for (let i = 1; i <= numberDaysInMonth[0]; i++) {
        if (i === selectedDay) {
          dayOption += `<option value="${i}" selected>${i}</option>`;
        } else {
          dayOption += `<option value="${i}">${i}</option>`;
        }
      }
      $('#day').append(dayOption);

      let monthOption = `<option value="">{{ trans('plugins/member::dashboard.month_lc') }}</option>`;
      for (let j = 1; j <= 12; j++) {
        if (j === selectedMonth) {
          monthOption += `<option value="${j}" selected>${j}</option>`;
        } else {
          monthOption += `<option value="${j}">${j}</option>`;
        }
      }
      $('#month').append(monthOption);

      let d = new Date();
      let yearOption = `<option value="">{{ trans('plugins/member::dashboard.year_lc') }}</option>`;
      for (let k = d.getFullYear(); k >= 1918; k--) {
        if (k === selectedYear) {
          yearOption += `<option value="${k}" selected>${k}</option>`;
        } else {
          yearOption += `<option value="${k}">${k}</option>`;
        }
      }
      $('#year').append(yearOption);
    }

    function isLeapYear(year) {
      year = parseInt(year);
      if (year % 4 !== 0) {
        return false;
      }
      if (year % 400 === 0) {
        return true;
      }
      if (year % 100 === 0) {
        return false;
      }
      return true;
    }

    function changeYear(select) {
      if (isLeapYear($(select).val())) {
        numberDaysInMonth[1] = 29;
      } else {
        numberDaysInMonth[1] = 28;
      }

      let monthSelectedValue = parseInt($("#month").val());
      if (monthSelectedValue === 2) {
        let day = $('#day');
        let daySelectedValue = parseInt($(day).val());
        if (daySelectedValue > numberDaysInMonth[1]) {
          daySelectedValue = null;
        }

        $(day).empty();

        let option = `<option value="">{{ trans('plugins/member::dashboard.day_lc') }}</option>`;
        for (let i = 1; i <= numberDaysInMonth[1]; i++) {
          if (i === daySelectedValue) {
            option += `<option value="${i}" selected>${i}</option>`;
          } else {
            option += `<option value="${i}">${i}</option>`;
          }
        }

        $(day).append(option);
      }
    }

    function changeMonth(select) {
      let day = $('#day');
      let daySelectedValue = parseInt($(day).val());
      let month = 0;

      if ($(select).val() !== '') {
        month = parseInt($(select).val()) - 1;
      }

      if (daySelectedValue > numberDaysInMonth[month]) {
        daySelectedValue = null;
      }

      $(day).empty();

      let option = `<option value="">{{ trans('plugins/member::dashboard.day_lc') }}</option>`;

      for (let i = 1; i <= numberDaysInMonth[month]; i++) {
        if (i === daySelectedValue) {
          option += `<option value="${i}" selected>${i}</option>`;
        } else {
          option += `<option value="${i}">${i}</option>`;
        }
      }

      $(day).append(option);
    }
  </script>
@endpush
