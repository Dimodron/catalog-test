<?php

namespace App\Http\Services\Catalog;

use App\Models\Group;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GroupTreeService
{
    /** @return Collection<int, int> list of group ids (self + descendants) */
    public function getDescendantIds(int $groupId): Collection
    {
        $rows = DB::select(
            "
            WITH RECURSIVE subgroups AS (
                SELECT id FROM `groups` WHERE id = ?
                UNION ALL
                SELECT g.id
                FROM `groups` g
                INNER JOIN subgroups sg ON g.id_parent = sg.id
            )
            SELECT id FROM subgroups
            ",
            [$groupId]
        );

        return collect($rows)->pluck('id')->map(fn ($v) => (int)$v);
    }

    /** @return Collection<int, Group> */
    public function getRootGroups(): Collection
    {
        return Group::query()
            ->where('id_parent', 0)
            ->orderBy('name')
            ->get();
    }

    /** @return Collection<int, Group> */
    public function getChildren(int $groupId): Collection
    {
        return Group::query()
            ->where('id_parent', $groupId)
            ->orderBy('name')
            ->get();
    }

    /**
     * Counts products for each root group including all descendants.
     * @return array<int, int> root_group_id => count
     */
    public function getRootProductCounts(): array
    {
        $rows = DB::select("
            WITH RECURSIVE tree AS (
                SELECT id, id_parent, id AS root_id
                FROM `groups`
                WHERE id_parent = 0

                UNION ALL

                SELECT g.id, g.id_parent, t.root_id
                FROM `groups` g
                INNER JOIN tree t ON g.id_parent = t.id
            )
            SELECT
                tree.root_id,
                COUNT(DISTINCT p.id) AS cnt
            FROM tree
            LEFT JOIN products p ON p.id_group = tree.id
            GROUP BY tree.root_id
        ");

        $out = [];
        foreach ($rows as $r) {
            $out[(int)$r->root_id] = (int)$r->cnt;
        }
        return $out;
    }
}
