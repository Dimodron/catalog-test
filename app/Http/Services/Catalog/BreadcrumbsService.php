<?php

namespace App\Http\Services\Catalog;

use App\Models\Group;
use Illuminate\Support\Collection;

class BreadcrumbsService
{
    /** @return Collection<int, Group> ordered from root to leaf */
    public function forGroupId(int $groupId): Collection
    {
        $groupsById = Group::query()->get()->keyBy('id');

        $crumbs = [];
        $current = $groupsById[$groupId] ?? null;

        while ($current) {
            $crumbs[] = $current;
            $parentId = (int)$current->id_parent;
            $current = $parentId > 0 ? ($groupsById[$parentId] ?? null) : null;
        }

        return collect(array_reverse($crumbs));
    }
}
