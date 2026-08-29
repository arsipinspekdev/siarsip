<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Users associated with this role.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Permissions granted to this role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role')->withTimestamps();
    }

    /**
     * Check if role has a specific permission by module and action.
     */
    public function hasPermission(string $module, string $action): bool
    {
        // Map common aliases
        $actionMap = [
            'print' => 'export',
            'cetak' => 'export',
            'edit'  => 'update',
            'ubah'  => 'update',
        ];
        $action = $actionMap[$action] ?? $action;

        return $this->permissions()
            ->where('module', $module)
            ->where('action', $action)
            ->exists();
    }
}
