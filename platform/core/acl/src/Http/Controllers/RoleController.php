<?php

namespace Cmat\ACL\Http\Controllers;

use Cmat\ACL\Events\RoleAssignmentEvent;
use Cmat\ACL\Events\RoleUpdateEvent;
use Cmat\ACL\Forms\RoleForm;
use Cmat\ACL\Http\Requests\AssignRoleRequest;
use Cmat\ACL\Http\Requests\RoleCreateRequest;
use Cmat\ACL\Repositories\Interfaces\RoleInterface;
use Cmat\ACL\Repositories\Interfaces\UserInterface;
use Cmat\ACL\Tables\RoleTable;
use Cmat\Base\Events\BeforeEditContentEvent;
use Cmat\Base\Forms\FormBuilder;
use Cmat\Base\Http\Controllers\BaseController;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Base\Supports\Helper;
use Illuminate\Http\Request;

class RoleController extends BaseController
{
    public function __construct(protected RoleInterface $roleRepository, protected UserInterface $userRepository)
    {
    }

    public function index(RoleTable $dataTable)
    {
        page_title()->setTitle(trans('core/acl::permissions.role_permission'));

        return $dataTable->renderTable();
    }

    public function destroy(int|string $id, BaseHttpResponse $response)
    {
        $role = $this->roleRepository->findOrFail($id);

        $role->delete();

        Helper::clearCache();

        return $response->setMessage(trans('core/acl::permissions.delete_success'));
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $role = $this->roleRepository->findOrFail($id);
            $role->delete();
        }

        Helper::clearCache();

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $role = $this->roleRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $role));

        page_title()->setTitle(trans('core/acl::permissions.details') . ' - ' . e($role->name));

        return $formBuilder->create(RoleForm::class, ['model' => $role])->renderForm();
    }

    public function update(int|string $id, RoleCreateRequest $request, BaseHttpResponse $response)
    {
        if ($request->input('is_default')) {
            $this->roleRepository->getModel()->where('id', '!=', $id)->update(['is_default' => 0]);
        }

        $role = $this->roleRepository->findOrFail($id);

        $role->name = $request->input('name');
        $role->permissions = $this->cleanPermission((array)$request->input('flags', []));
        $role->description = $request->input('description');
        $role->updated_by = $request->user()->getKey();
        $role->is_default = $request->input('is_default');
        $this->roleRepository->createOrUpdate($role);

        Helper::clearCache();

        event(new RoleUpdateEvent($role));

        return $response
            ->setPreviousUrl(route('roles.index'))
            ->setNextUrl(route('roles.edit', $id))
            ->setMessage(trans('core/acl::permissions.modified_success'));
    }

    protected function cleanPermission(array $permissions): array
    {
        if (! $permissions) {
            return [];
        }

        $cleanedPermissions = [];
        foreach ($permissions as $permissionName) {
            $cleanedPermissions[$permissionName] = true;
        }

        return $cleanedPermissions;
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('core/acl::permissions.create_role'));

        return $formBuilder->create(RoleForm::class)->renderForm();
    }

    public function store(RoleCreateRequest $request, BaseHttpResponse $response)
    {
        if ($request->input('is_default')) {
            $this->roleRepository->getModel()->where('id', '>', 0)->update(['is_default' => 0]);
        }

        $role = $this->roleRepository->createOrUpdate([
            'name' => $request->input('name'),
            'slug' => $this->roleRepository->createSlug($request->input('name'), 0),
            'permissions' => $this->cleanPermission((array)$request->input('flags', [])),
            'description' => $request->input('description'),
            'is_default' => $request->input('is_default'),
            'created_by' => $request->user()->getKey(),
            'updated_by' => $request->user()->getKey(),
        ]);

        return $response
            ->setPreviousUrl(route('roles.index'))
            ->setNextUrl(route('roles.edit', $role->id))
            ->setMessage(trans('core/acl::permissions.create_success'));
    }

    public function getDuplicate(int|string $id, BaseHttpResponse $response)
    {
        $baseRole = $this->roleRepository->findOrFail($id);

        $role = $this->roleRepository->createOrUpdate([
            'name' => $baseRole->name . ' (Duplicate)',
            'slug' => $this->roleRepository->createSlug($baseRole->slug, 0),
            'permissions' => $baseRole->permissions,
            'description' => $baseRole->description,
            'created_by' => $baseRole->created_by,
            'updated_by' => $baseRole->updated_by,
        ]);

        return $response
            ->setPreviousUrl(route('roles.edit', $baseRole->id))
            ->setNextUrl(route('roles.edit', $role->id))
            ->setMessage(trans('core/acl::permissions.duplicated_success'));
    }

    public function getJson(): array
    {
        $pl = [];
        foreach ($this->roleRepository->all() as $role) {
            $pl[] = [
                'value' => $role->id,
                'text' => $role->name,
            ];
        }

        return $pl;
    }

    public function postAssignMember(AssignRoleRequest $request, BaseHttpResponse $response): BaseHttpResponse
    {
        $user = $this->userRepository->findOrFail($request->input('pk'));
        $role = $this->roleRepository->findOrFail($request->input('value'));

        $user->roles()->sync([$role->id]);

        event(new RoleAssignmentEvent($role, $user));

        return $response;
    }
}
