@extends('layouts.app')

@section('title', $product->name)

@section('content')

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('catalog.index') }}">Главная</a>
            </li>

            @foreach($breadcrumbs as $g)
                <li class="breadcrumb-item">
                    <a href="{{ route('catalog.group', $g) }}">{{ $g->name }}</a>
                </li>
            @endforeach

            <li class="breadcrumb-item active" aria-current="page">
                {{ $product->name }}
            </li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-body">
            <h1 class="h4">{{ $product->name }}</h1>
            <div class="text-muted mt-2">
                Цена: {{ number_format($price, 2, '.', ' ') }} ₽
            </div>
        </div>
    </div>

@endsection
