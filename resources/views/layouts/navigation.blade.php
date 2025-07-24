<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <img src="https://placehold.co/40x40/007bff/ffffff?text=Logo" alt="Logo Aplikasi" class="d-inline-block align-text-top">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" aria-current="page" href="{{ route('dashboard') }}">Dashboard</a>
                </li>

                {{-- Link untuk Admin --}}
                @can('manage-users')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Manajemen Pengguna</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.parameters.*') ? 'active' : '' }}" href="{{ route('admin.parameters.index') }}">Parameter Scoring</a>
                    </li>
                @endcan

                {{-- Link untuk Teller --}}
                @can('access-teller-features')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('teller.applications.create') ? 'active' : '' }}" href="{{ route('teller.applications.create') }}">Pengajuan Baru</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('teller.applications.index') ? 'active' : '' }}" href="{{ route('teller.applications.index') }}">Daftar Pengajuan</a>
                    </li>
                @endcan

                {{-- Link untuk Kabag --}}
                @can('access-kabag-features')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('kabag.applications.index') ? 'active' : '' }}" href="{{ route('kabag.applications.index') }}">Review Pengajuan</a>
                    </li>
                @endcan

                {{-- Link untuk Direksi --}}
                @can('access-direksi-features')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('direksi.applications.index') ? 'active' : '' }}" href="{{ route('direksi.applications.index') }}">Persetujuan Kredit</a>
                    </li>
                @endcan

                {{-- Link untuk semua role yang bisa melihat laporan (Kabag, Direksi, Admin) --}}
                @can('view-scoring-details')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}">Laporan</a>
                    </li>
                @endcan
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})
                    </a>

                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             this.closest('form').submit();">
                                Log Out
                            </a>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
