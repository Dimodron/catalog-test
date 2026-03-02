<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Каталог')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-light border-bottom">
    <div class="container">
        <a class="navbar-brand" href="{{ route('catalog.index') }}">Каталог</a>
    </div>
</nav>

<main class="container py-4">
    @yield('content')
</main>
<script>
    function ajaxNavigate(url) {
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
            .then(r => r.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newProducts = doc.querySelector('.products-wrapper');
                const currentProducts = document.querySelector('.products-wrapper');

                const newPaginationWrap = doc.querySelector('.pagination-wrap');
                const currentPaginationWrap = document.querySelector('.pagination-wrap');

                if (newProducts && currentProducts) {
                    currentProducts.innerHTML = newProducts.innerHTML;
                }

                if (newPaginationWrap && currentPaginationWrap) {
                    currentPaginationWrap.innerHTML = newPaginationWrap.innerHTML;
                }

                history.pushState({}, '', url);
            });
    }

    document.addEventListener('click', function (e) {
        const link = e.target.closest('.pagination a');
        if (!link) return;

        e.preventDefault();
        ajaxNavigate(link.href);
    });

    window.addEventListener('popstate', function () {
        ajaxNavigate(location.href);
    });
</script>
</body>
</html>
