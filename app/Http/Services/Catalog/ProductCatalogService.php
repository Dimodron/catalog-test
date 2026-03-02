<?php

namespace App\Http\Services\Catalog;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductCatalogService
{
    /**
     * @param array{sort?:string,dir?:string,per_page?:int} $params
     */
    public function getAllProducts(array $params): LengthAwarePaginator
    {
        [$sort, $dir, $perPage] = $this->normalizeParams($params);

        $q = $this->baseProductsQuery();

        $this->applySorting($q, $sort, $dir);

        return $q->paginate($perPage)->withQueryString();
    }

    /**
     * @param array{sort?:string,dir?:string,per_page?:int} $params
     */
    public function getProductsByGroupIds(array $groupIds, array $params): LengthAwarePaginator
    {
        [$sort, $dir, $perPage] = $this->normalizeParams($params);

        $q = $this->baseProductsQuery()
            ->whereIn('products.id_group', $groupIds);

        $this->applySorting($q, $sort, $dir);

        return $q->paginate($perPage)->withQueryString();
    }

    public function getProductMinPrice(int $productId): float
    {
        return (float) DB::table('prices')
            ->where('id_product', $productId)
            ->min('price');
    }

    private function baseProductsQuery()
    {
        return Product::query()
            ->select([
                'products.id',
                'products.id_group',
                'products.name',
                DB::raw('MIN(prices.price) as price'),
            ])
            ->leftJoin('prices', 'prices.id_product', '=', 'products.id')
            ->groupBy('products.id', 'products.id_group', 'products.name');
    }

    private function applySorting($query, string $sort, string $dir): void
    {
        if ($sort === 'name') {
            $query->orderBy('products.name', $dir);
        } else {
            $query->orderBy(DB::raw('price'), $dir);
        }
    }

    /**
     * @param array{sort?:string,dir?:string,per_page?:int} $params
     * @return array{0:string,1:string,2:int}
     */
    private function normalizeParams(array $params): array
    {
        $sort = $params['sort'] ?? 'price';
        $dir  = $params['dir'] ?? 'asc';
        $perPage = (int)($params['per_page'] ?? 6);

        $allowedSort = ['price', 'name'];
        $allowedDir  = ['asc', 'desc'];
        $allowedPer  = [6, 12, 18];

        if (!in_array($sort, $allowedSort, true)) $sort = 'price';
        if (!in_array($dir, $allowedDir, true))   $dir = 'asc';
        if (!in_array($perPage, $allowedPer, true)) $perPage = 6;

        return [$sort, $dir, $perPage];
    }
}
