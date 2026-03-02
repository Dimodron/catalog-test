@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <div class="row g-4">
        <aside class="col-12 col-lg-3">
            <div class="list-group">
                <div class="list-group-item fw-semibold">Группы</div>
                @foreach($rootGroups as $g)
                    <a class="list-group-item list-group-item-action"
                       href="{{ route('catalog.group', $g) }}">
                        @php $cnt = (int)($counts[$g->id] ?? 0); @endphp
                        {{ $g->name }}
                        <span class="badge text-bg-secondary ms-2">{{ $cnt }}</span>
                    </a>
                @endforeach
            </div>
        </aside>

        <section class="col-12 col-lg-9">
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
                <h1 class="h4 mb-0">Все товары</h1>

                <form method="GET" class="d-flex gap-2">
                    <select name="sort" class="form-select">
                        <option value="price" @selected($sort==='price')>Цена</option>
                        <option value="name"  @selected($sort==='name')>Название</option>
                    </select>
                    <select name="dir" class="form-select">
                        <option value="asc"  @selected($dir==='asc')>↑</option>
                        <option value="desc" @selected($dir==='desc')>↓</option>
                    </select>
                    <select name="per_page" class="form-select">
                        @foreach([6,12,18] as $pp)
                            <option value="{{ $pp }}"
                                @selected(request('per_page', 6) == $pp)>
                                {{ $pp }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary">Применить</button>
                </form>
            </div>

            <div class="products-wrapper">
                <div class="row g-3">
                @foreach($products as $p)
                    <div class="col-12 col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="fw-semibold">
                                    <a href="{{ route('catalog.product', $p->id) }}" class="text-decoration-none">
                                        {{ $p->name }}
                                    </a>
                                </div>
                                <div class="text-muted mt-2">
                                    Цена: {{ number_format((float)$p->price, 2, '.', ' ') }} ₽
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>

            <div class="pagination-wrap mt-3">
                {{ $products->links() }}
            </div>
        </section>
    </div>
@endsection
