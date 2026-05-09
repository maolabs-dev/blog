<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>@yield('title', 'Maolabs Blog')</title>
    <meta name="description" content="@yield('meta_description', 'Blog pessoal sobre arquitetura de software, IA e minimalismo digital.')">

    <link rel="stylesheet" href="/css/app.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@400;700;800&display=swap" rel="stylesheet">

    <script>
        // Previne flash de tema claro
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body>
    {{-- Skip link para acessibilidade --}}
    <a href="#main-content" class="skip-link">Ir para o conteúdo</a>

    <header class="header" role="banner">
        <div class="container header-inner">
            <a href="/" class="brand" aria-label="blog.maolabs — Página inicial">
                <img src="/img/logo.png" alt="" class="logo">
                <span>blog<span class="dot">.maolabs</span></span>
            </a>

            <nav class="nav" aria-label="Navegação principal">
                <a href="/">Home</a>
                <a href="#">Sobre</a>
                <button id="theme-toggle" class="theme-toggle" aria-label="Alternar tema claro/escuro">
                    <svg class="sun" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path></svg>
                    <svg class="moon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>
            </nav>
        </div>
    </header>

    <main class="main" id="main-content" role="main">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer class="footer" role="contentinfo">
        &copy; {{ date('Y') }} — maolabs
    </footer>

    <script>
        const themeToggle = document.getElementById('theme-toggle');
        
        themeToggle.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    </script>
</body>
</html>
