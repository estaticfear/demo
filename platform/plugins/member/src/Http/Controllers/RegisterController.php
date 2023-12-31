<?php

namespace Cmat\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use Cmat\ACL\Traits\RegistersUsers;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Member\Models\Member;
use Cmat\Member\Repositories\Interfaces\MemberInterface;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SeoHelper;
use Theme;
use Illuminate\Support\Facades\URL;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected string $redirectTo = '/';

    public function __construct(protected MemberInterface $memberRepository)
    {
    }

    public function showRegistrationForm()
    {
        SeoHelper::setTitle(__('Register'));

        if (!session()->has('url.intended')) {
            session(['url.intended' => url()->previous()]);
        }

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Register'), route('public.member.register'));

        if (view()->exists(Theme::getThemeNamespace() . '::views.member.auth.register')) {
            return Theme::scope('member.auth.register')->render();
        }

        return view('plugins/member::auth.register');
    }

    public function confirm(int|string $id, Request $request, BaseHttpResponse $response, MemberInterface $memberRepository)
    {
        if (!URL::hasValidSignature($request)) {
            abort(404);
        }

        $member = $memberRepository->findOrFail($id);

        $member->confirmed_at = Carbon::now();
        $this->memberRepository->createOrUpdate($member);

        $this->guard()->login($member);

        return $response
            ->setNextUrl(route('public.member.dashboard'))
            ->setMessage(trans('plugins/member::member.confirmation_successful'));
    }

    protected function guard()
    {
        return auth('member');
    }

    public function resendConfirmation(Request $request, MemberInterface $memberRepository, BaseHttpResponse $response)
    {
        $member = $memberRepository->getFirstBy(['email' => $request->input('email')]);
        if (!$member) {
            return $response
                ->setError()
                ->setMessage(__('Cannot find this account!'));
        }

        $this->sendConfirmationToUser($member);

        return $response
            ->setMessage(trans('plugins/member::member.confirmation_resent'));
    }

    protected function sendConfirmationToUser(Member $member)
    {
        // Notify the user
        $notificationConfig = config('plugins.member.general.notification');
        if ($notificationConfig) {
            $notification = app($notificationConfig);
            $member->notify($notification);
        }
    }

    public function register(Request $request, BaseHttpResponse $response)
    {
        $this->validator($request->input())->validate();

        event(new Registered($member = $this->create($request->input())));

        if (setting('verify_account_email', config('plugins.member.general.verify_email'))) {
            $this->sendConfirmationToUser($member);

            return $this->registered($request, $member)
                ?: $response->setNextUrl($this->redirectPath())
                ->setMessage(trans('plugins/member::member.confirmation_info'));
        }

        $member->confirmed_at = Carbon::now();
        $member->first_name = '';
        $member->last_name = '';
        $this->memberRepository->createOrUpdate($member);
        $this->guard()->login($member);

        return $this->registered($request, $member)
            ?: $response->setNextUrl($this->redirectPath());
    }

    protected function validator(array $data)
    {
        $rules = [
            'email' => 'required|email|max:255|unique:members',
            'password' => 'required|min:6',
        ];

        if (is_plugin_active('captcha') && setting('enable_captcha') && setting(
            'member_enable_recaptcha_in_register_page',
            0
        )) {
            $rules += ['g-recaptcha-response' => 'required|captcha'];
        }

        return Validator::make($data, $rules, [
            'g-recaptcha-response.required' => __('Captcha Verification Failed!'),
            'g-recaptcha-response.captcha' => __('Captcha Verification Failed!'),
        ]);
    }

    protected function create(array $data)
    {
        return $this->memberRepository->create([
            'first_name' =>  '',
            'last_name' =>  '',
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function getVerify()
    {
        return view('plugins/member::auth.verify');
    }
}
