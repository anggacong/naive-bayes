<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Analisis Sentimen Koperasi Merah Putih - Naive Bayes">
    <title>@yield('title', 'Dashboard') | SentiKMP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

{{-- ─── SIDEBAR BACKDROP (MOBILE) ─── --}}
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

{{-- ─── SIDEBAR ─── --}}
<aside class="sidebar" id="sidebar">
    {{-- Logo / Header --}}
    <div class="sidebar-header">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 via-purple-500 to-cyan-500 flex items-center justify-center text-white font-bold text-base shadow-md shadow-indigo-500/20 shrink-0">
                S
            </div>
            <div>
                <div class="sidebar-logo font-extrabold text-base leading-tight tracking-wide">SentiKMP</div>
                <div class="text-[10px] text-slate-500 font-medium leading-none mt-0.5">Naive Bayes · Unigram</div>
            </div>
        </div>

        {{-- Mobile Close Button --}}
        <button type="button" onclick="toggleSidebar()" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-[#252538] transition-colors" title="Tutup Sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        {{-- Dashboard --}}
        <div class="nav-section-title">Utama</div>
        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        {{-- Data Asli --}}
        <div class="nav-section-title mt-2">Data Asli</div>
        <a href="{{ route('data-x.index') }}"
           class="nav-item {{ request()->routeIs('data-x.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
            Data X (Twitter)
        </a>
        <a href="{{ route('data-thread.index') }}"
           class="nav-item {{ request()->routeIs('data-thread.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
            </svg>
            Data Thread
        </a>

        {{-- Preprocessing --}}
        <div class="nav-section-title mt-2">Preprocessing</div>
        <a href="{{ route('preprocessing.x') }}"
           class="nav-item {{ request()->routeIs('preprocessing.x*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
            </svg>
            Preprocessing Data X
        </a>
        <a href="{{ route('preprocessing.thread') }}"
           class="nav-item {{ request()->routeIs('preprocessing.thread*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
            </svg>
            Preprocessing Data Thread
        </a>

        {{-- =====================================================
             Unigram (TF-IDF) — DISEMBUNYIKAN SEMENTARA
             Aktifkan kembali jika dibutuhkan untuk analisis TF-IDF
             =====================================================
        <div class="nav-section-title mt-2">Unigram (TF-IDF)</div>
        <a href="{{ route('unigram.x') }}"
           class="nav-item {{ request()->routeIs('unigram.x*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
            </svg>
            Unigram Data X
        </a>
        <a href="{{ route('unigram.thread') }}"
           class="nav-item {{ request()->routeIs('unigram.thread*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
            </svg>
            Unigram Data Thread
        </a>
        --}}

        {{-- Unigram (Frekuensi) --}}
        <div class="nav-section-title mt-2">Unigram</div>
        <a href="{{ route('unigram-frekuensi.x') }}"
           class="nav-item {{ request()->routeIs('unigram-frekuensi.x*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
            </svg>
            Unigram Data X
        </a>
        <a href="{{ route('unigram-frekuensi.thread') }}"
           class="nav-item {{ request()->routeIs('unigram-frekuensi.thread*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
            </svg>
            Unigram Data Thread
        </a>

        {{-- Naive Bayes --}}
        <div class="nav-section-title mt-2">Naive Bayes</div>
        <a href="{{ route('naive-bayes.training') }}"
           class="nav-item {{ request()->routeIs('naive-bayes.training*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
            </svg>
            Pelatihan Model
        </a>
        <a href="{{ route('naive-bayes.pengujian') }}"
           class="nav-item {{ request()->routeIs('naive-bayes.pengujian*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Pengujian
        </a>
        <a href="{{ route('naive-bayes.evaluasi') }}"
           class="nav-item {{ request()->routeIs('naive-bayes.evaluasi*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Evaluasi
        </a>

        {{-- Manajemen Kamus --}}
        <div class="nav-section-title mt-2">Manajemen Kamus</div>
        <a href="{{ route('kamus-normalisasi.index') }}"
           class="nav-item {{ request()->routeIs('kamus-normalisasi.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
            Kamus Normalisasi
        </a>
        <a href="{{ route('stopwords.index') }}"
           class="nav-item {{ request()->routeIs('stopwords.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
            Stopwords
        </a>
    </nav>

    {{-- Footer sidebar --}}
    <div class="sidebar-footer">
        <div class="text-[10px] text-slate-500 text-center leading-relaxed">
            Koperasi Merah Putih<br>Analisis Sentimen &copy; 2026
        </div>
    </div>
</aside>

{{-- ─── MAIN CONTENT ─── --}}
<div class="main-content">

    {{-- Top Bar --}}
    <header class="topbar">
        <div class="flex items-center gap-3.5">
            {{-- Toggle Button --}}
            <button type="button" onclick="toggleSidebar()" id="sidebarToggleBtn"
                    class="p-2 rounded-xl bg-[#1e1e2e] border border-[#3f3f5a] text-slate-300 hover:text-white hover:border-indigo-500 hover:bg-[#252538] transition-all cursor-pointer shadow-sm active:scale-95"
                    title="Buka / Tutup Sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div>
                <nav class="breadcrumb text-xs">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-slate-400 hover:text-indigo-400 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Home
                    </a>
                    @hasSection('breadcrumb')
                        <span class="text-slate-600 mx-1">/</span>
                        @yield('breadcrumb')
                    @endif
                </nav>
                <h1 class="text-base font-bold text-white tracking-tight leading-tight mt-0.5">@yield('page-title', 'Dashboard')</h1>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <div class="text-[11px] text-slate-400">Koperasi Merah Putih</div>
                <div class="text-xs font-semibold text-indigo-400">Analisis Sentimen</div>
            </div>
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-indigo-500/20 border border-indigo-400/30">
                A
            </div>
        </div>
    </header>

    {{-- Page Content --}}
    <main class="p-7">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success fade-in-up" id="flashAlert">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error') || $errors->any())
            <div class="alert alert-error fade-in-up">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') ?? $errors->first() }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

{{-- ─── MODAL DETAIL ─── --}}
<div class="modal-overlay" id="detailModal" onclick="closeModal(event)">
    <div class="modal-box" id="modalBox">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-white" id="modalTitle">Detail</h3>
            <button onclick="closeModalDirect()" class="btn btn-outline btn-icon text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div id="modalContent" class="text-sm text-slate-300 leading-relaxed whitespace-pre-wrap"></div>
    </div>
</div>

<script>
    // Toggle Sidebar Function
    function toggleSidebar() {
        if (window.innerWidth <= 1024) {
            document.body.classList.toggle('sidebar-open-mobile');
        } else {
            const isClosed = document.body.classList.toggle('sidebar-closed');
            try {
                localStorage.setItem('sidebar-closed', isClosed ? 'true' : 'false');
            } catch (e) {}
        }
    }

    // Restore sidebar state from localStorage
    (function() {
        try {
            if (window.innerWidth > 1024 && localStorage.getItem('sidebar-closed') === 'true') {
                document.body.classList.add('sidebar-closed');
            }
        } catch (e) {}
    })();

    // Auto-hide flash alert
    setTimeout(() => {
        const el = document.getElementById('flashAlert');
        if (el) { el.style.opacity = '0'; el.style.transform = 'translateY(-8px)'; el.style.transition = '0.4s'; setTimeout(() => el.remove(), 400); }
    }, 4000);

    // Modal
    function openModal(title, content) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalContent').textContent = content;
        document.getElementById('detailModal').classList.add('open');
    }
    function closeModal(e) {
        if (e.target.id === 'detailModal') closeModalDirect();
    }
    function closeModalDirect() {
        document.getElementById('detailModal').classList.remove('open');
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModalDirect(); });
</script>

@stack('scripts')
</body>
</html>
