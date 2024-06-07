<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Models\Setting;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $permissions = $this->getUserPermissions($request);

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'profile' => $request->user() ? $request->user()->profile : null,
                'role' => $request->user() ? $request->user()->roles()->first() : null,
                'permissions' => $request->user() ? $permissions : [],
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'urls' => [
                'storeUrl' => config('app.url') . '/storage/',
            ],
            'settings' => Setting::select('name', 'value')->where('tab_name', 'general')->get(),
        ];
    }
    private function getUserPermissions(Request $request)
    {
        
        if (!$request->user()) {
            return [];
        }
        
        $user = $request->user();

        

        if ($user->roles() == null) {
            return [];
        }

        $roles = $user->roles()->with('permissions')->get();

        

        $permissions = $roles->flatMap(function ($role) {
            return $role->permissions;
        });

        $uniquePermissions = $permissions->unique('id');



        return $uniquePermissions;
    }
}
