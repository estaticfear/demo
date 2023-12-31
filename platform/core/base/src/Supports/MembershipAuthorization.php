<?php

namespace Cmat\Base\Supports;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MembershipAuthorization
{
    protected Client $client;

    protected Request $request;

    protected string $url;

    public function __construct(Client $client, Request $request)
    {
        $this->client = $client;
        $this->request = $request;
        $this->url = rtrim(url('/'), '/');
    }

    public function authorize(): bool
    {
        try {
            if (! filter_var($this->url, FILTER_VALIDATE_URL)) {
                return false;
            }

            if ($this->isInvalidDomain()) {
                return false;
            }

            $authorizeDate = setting('membership_authorization_at');

            if (! $authorizeDate) {
                return $this->processAuthorize();
            }

            $authorizeDate = Carbon::createFromFormat('Y-m-d H:i:s', $authorizeDate);
            if (Carbon::now()->diffInDays($authorizeDate) > 7) {
                return $this->processAuthorize();
            }

            return true;
        } catch (Exception|GuzzleException) {
            return false;
        }
    }

    protected function isInvalidDomain(): bool
    {
        if (filter_var($this->url, FILTER_VALIDATE_IP)) {
            return true;
        }

        $blacklistDomains = [
            'localhost',
            '.local',
            '.test',
            '127.0.0.1',
            '192.',
            'mail.',
            '8000',
        ];

        foreach ($blacklistDomains as $blacklistDomain) {
            if (Str::contains($this->url, $blacklistDomain)) {
                return true;
            }
        }

        return false;
    }

    protected function processAuthorize(): bool
    {
        try {
            $this->client->post('https://cmat.com/membership/authorize', [
                'form_params' => [
                    'website' => $this->url,
                ],
            ]);
        } catch (GuzzleException) {
            return true;
        }

        setting()
            ->set('membership_authorization_at', Carbon::now()->toDateTimeString())
            ->save();

        return true;
    }
}
