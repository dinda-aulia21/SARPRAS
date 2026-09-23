<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Sistem informasi inventaris sarana dan prasarana Yayasan Raudhah Syarifah.">
        <title>Sarpras | Yayasan Raudhah Syarifah</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <main class="page-shell">
            <nav class="site-nav" aria-label="Navigasi utama">
                <a class="brand" href="{{ url('/') }}" aria-label="Yayasan Raudhah Syarifah">
                    <span class="brand-mark" aria-hidden="true">R</span>
                    <span>Yayasan Raudhah Syarifah</span>
                </a>
                <div class="nav-links">
                    <a class="active" href="{{ url('/') }}">Beranda</a>
                    <a href="#tentang">Tentang</a>
                    <a href="#fitur">Fitur</a>
                    <a href="#kontak">Kontak</a>
                </div>
                <a class="login-link" href="#fitur">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                    Login
                </a>
            </nav>

            <section class="hero" id="tentang">
                <div class="hero-copy">
                    <h1>Sistem Informasi Inventaris<br>Sarana dan Prasarana<br><span>Yayasan Raudhah Syarifah</span></h1>
                    <p class="hero-description">Memudahkan pengelolaan data inventaris sarana dan prasarana secara cepat, akurat, dan terintegrasi.</p>
                    <a class="primary-button" href="#fitur">Masuk ke Sistem <span aria-hidden="true">→</span></a>
                </div>
                <div class="hero-image" role="img" aria-label="Gedung sekolah Yayasan Raudhah Syarifah"></div>
            </section>

            <section class="feature-section" id="fitur" aria-labelledby="feature-title">
                <div class="feature-grid">
                    <a class="feature-card" href="#data-inventaris">
                        <span class="feature-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 7h16v13H4zM4 7l2-4h12l2 4M9 11h6M8 3v4M16 3v4"/></svg></span>
                        <h2 id="feature-title">Data Inventaris</h2>
                        <p>Lihat dan kelola seluruh data inventaris sarana dan prasarana secara lengkap.</p>
                        <span class="card-arrow" aria-hidden="true">→</span>
                    </a>
                    <a class="feature-card" href="#kondisi-sarana">
                        <span class="feature-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 20h18M5 20V9l7-6 7 6v11M9 20v-6h6v6M8 10h.01M12 10h.01M16 10h.01"/></svg></span>
                        <h2>Kondisi Sarana</h2>
                        <p>Pantau kondisi sarana yang ada di lingkungan yayasan secara berkala.</p>
                        <span class="card-arrow" aria-hidden="true">→</span>
                    </a>
                    <a class="feature-card" href="#pengelolaan">
                        <span class="feature-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z"/><path d="M19.4 15a2 2 0 0 0 1.4-3 2 2 0 0 0-1.4-3M4.6 9a2 2 0 0 0-1.4 3 2 2 0 0 0 1.4 3M9 4.6a2 2 0 0 0 3-1.4 2 2 0 0 0 3 1.4M9 19.4a2 2 0 0 0 3 1.4 2 2 0 0 0 3-1.4"/></svg></span>
                        <h2>Pengelolaan Prasarana</h2>
                        <p>Kelola data prasarana untuk mendukung kegiatan pendidikan.</p>
                        <span class="card-arrow" aria-hidden="true">→</span>
                    </a>
                </div>
            </section>

            <footer id="kontak">Yayasan Raudhah Syarifah <span>•</span> Sistem Informasi Sarana dan Prasarana</footer>
        </main>
    </body>
</html>