<?php

use Cmat\ReligiousMerit\Models\ReligiousMeritProject;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritInterface;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectCategoryInterface;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

function generateUUID($length) {
    $random = '';
    for ($i = 0; $i < $length; $i++) {
      $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
    }
    return $random;
  }

if (!function_exists('get_project_transaction_message')) {
    function get_project_transaction_message(string|null $prefix) {
        return strtoupper($prefix ? $prefix . '-' . generateUUID(6) : generateUUID(6));
    }
}

if (!function_exists('get_projects_prefix')) {
    function get_projects_prefix() {
        return SlugHelper::getPrefix(ReligiousMeritProject::class);
    }
}

if (!function_exists('get_projects_category_prefix')) {
    function get_projects_category_prefix() {
        return SlugHelper::getPrefix(ReligiousMeritProject::class) . '/c';
    }
}


/**
 *
 * @access    public
 * @param    string
 * @return    string
 */
if (!function_exists('currency_format')) {
    function currency_format($number, $suffix = 'đ') {
        return number_format($number, 0, '.', ',') . "{$suffix}";
    }
}

if (! function_exists('get_religious_merit_project_categories')) {
    function get_religious_merit_project_categories(): Collection
    {
        $categories = app(ReligiousMeritProjectCategoryInterface::class)
            ->getAllProjectCategories([], [
                'created_at' => 'DESC',
                'order' => 'ASC',
            ], ['id', 'name']);

        return $categories;
    }
}

if (! function_exists('get_vietqr_banks')) {
    function get_vietqr_banks()
    {
        try {
            $client = new GuzzleHttp\Client();
            $res = $client->get('https://api.vietqr.io/v2/banks', []);
            if ($res->getStatusCode() == 200) {
                $response = json_decode($res->getBody());
                $data = $response->data;
                return $data;
            }
        } catch (\Throwable $th) {
            return [];
        }
    }
}

if (! function_exists('get_vietqr_code')) {
    function get_vietqr_code(): string
    {
        $bankId = setting('bank_transfer_bank_name');
        $accountNumber = setting('bank_transfer_bank_account_number');
        $accountName = setting('bank_transfer_bank_account_name');
        $vietqrTemplate = 'VheXowT';
        if ($bankId && $accountNumber && $accountName) {
            $link = 'https://img.vietqr.io/image/' . $bankId . '-' . $accountNumber . '-' . $vietqrTemplate . '.jpg?accountName=' . $accountName;
            return $link;
        }
        return '';
    }
}

if (! function_exists('get_available_projects')) {
    function get_available_projects($keyword = ''): Collection
    {
        return app(ReligiousMeritProjectInterface::class)
            ->getAvailableProjects($keyword);
    }
}

if (! function_exists('get_featured_projects')) {
    function get_featured_projects($keyword = ''): Collection
    {
        return app(ReligiousMeritProjectInterface::class)
            ->getFeaturedProjects($keyword);
    }
}

if (! function_exists('get_finished_projects')) {
    function get_finished_projects($keyword = ''): Collection|LengthAwarePaginator|null
    {
        return app(ReligiousMeritProjectInterface::class)
            ->getFinishedProjects($keyword);
    }
}

if (! function_exists('get_my_merits')) {
    function get_my_merits($limit, $keyword = '', $orderBy = [], $conditions = []): Collection|LengthAwarePaginator|null
    {
        return app(ReligiousMeritInterface::class)
            ->getMyMerits($limit, $keyword, $orderBy, $conditions);
    }
}

if (! function_exists('get_my_merits_report')) {
    function get_my_merits_report(): Array|null
    {
        return app(ReligiousMeritInterface::class)
            ->getMyMeritsReport();
    }
}

if (! function_exists('get_project_merits')) {
    function get_project_merits($project_id, $query = '', $paginate = 10, $limit = 10): Collection|LengthAwarePaginator|null
    {
        return app(ReligiousMeritProjectInterface::class)
            ->getProjectMerits($project_id, $query, $paginate, $limit);
    }
}
