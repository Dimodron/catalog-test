<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Product;
use App\Http\Services\Catalog\BreadcrumbsService;
use App\Http\Services\Catalog\GroupTreeService;
use App\Http\Services\Catalog\ProductCatalogService;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function __construct(
        private readonly GroupTreeService $groupTree,
        private readonly ProductCatalogService $products,
        private readonly BreadcrumbsService $breadcrumbs,
    ) {}

    public function index(Request $request)
    {
        $rootGroups = $this->groupTree->getRootGroups();
        $counts = $this->groupTree->getRootProductCounts();

        $params = $request->only(['sort', 'dir', 'per_page']);
        $products = $this->products->getAllProducts($params);

        $sort = $request->query('sort', 'price');
        $dir  = $request->query('dir', 'asc');

        return view('catalog.index', compact('rootGroups', 'counts', 'products', 'sort', 'dir'));
    }

    public function group(Request $request, Group $group)
    {
        $subGroups = $this->groupTree->getChildren($group->id);

        $groupIds = $this->groupTree->getDescendantIds($group->id);
        $params = $request->only(['sort', 'dir', 'per_page']);
        $products = $this->products->getProductsByGroupIds($groupIds->all(), $params);

        $sort = $request->query('sort', 'price');
        $dir  = $request->query('dir', 'asc');

        return view('catalog.group', compact('group', 'subGroups', 'products', 'sort', 'dir'));
    }

    public function product(Product $product)
    {
        $price = $this->products->getProductMinPrice($product->id);
        $breadcrumbs = $this->breadcrumbs->forGroupId((int)$product->id_group);

        return view('catalog.product', compact('product', 'price', 'breadcrumbs'));
    }
}
