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
                    <img class="brand-mark" src="{{ asset('images/logo-yayasan.png') }}" alt="">
                    <span>Yayasan Raudhah Syarifah</span>
                </a>
                <a class="login-link" href="{{ route('login') }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                    Login
                </a>
            </nav>

            <section class="hero" id="tentang">
                <div class="hero-copy">
                    <h1>Sistem Informasi Inventaris<br>Sarana dan Prasarana<br><span>Yayasan Raudhah Syarifah</span></h1>
                    <p class="hero-description">Memudahkan pengelolaan data inventaris sarana dan prasarana secara cepat, akurat, dan terintegrasi.</p>
                </div>
                <div class="hero-image" role="img" aria-label="Gedung sekolah Yayasan Raudhah Syarifah"></div>
            </section>

            <section class="feature-section" id="fitur" aria-labelledby="feature-title">
                <div class="feature-grid">
                    <a class="feature-card" href="#data-inventaris">
                        <span class="feature-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 7h16v13H4zM4 7l2-4h12l2 4M9 11h6M8 3v4M16 3v4"/></svg></span>
                        <h2 id="feature-title">Data Inventaris</h2>
                        <p>Lihat dan kelola seluruh data inventaris sarana dan prasarana secara lengkap.</p>
                    </a>
                    <a class="feature-card" href="#kondisi-sarana">
                        <span class="feature-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 20h18M5 20V9l7-6 7 6v11M9 20v-6h6v6M8 10h.01M12 10h.01M16 10h.01"/></svg></span>
                        <h2>Kondisi Sarana</h2>
                        <p>Pantau kondisi sarana yang ada di lingkungan yayasan secara berkala.</p>
                    </a>
                    <a class="feature-card" href="#pengelolaan">
                        <span class="feature-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z"/><path d="M19.4 15a2 2 0 0 0 1.4-3 2 2 0 0 0-1.4-3M4.6 9a2 2 0 0 0-1.4 3 2 2 0 0 0 1.4 3M9 4.6a2 2 0 0 0 3-1.4 2 2 0 0 0 3 1.4M9 19.4a2 2 0 0 0 3 1.4 2 2 0 0 0 3-1.4"/></svg></span>
                        <h2>Pengelolaan Prasarana</h2>
                        <p>Kelola data prasarana untuk mendukung kegiatan pendidikan.</p>
                    </a>
                </div>
            </section>

            <section class="foundation-contact" id="kontak" aria-label="Informasi yayasan">
                <div class="foundation-contact-inner">
                    <div class="foundation-contact-layout" style="display: flex; flex-wrap: wrap; gap: 18px;">
                        <div class="foundation-contact-block" style="flex: 1 1 260px;">
                            <p class="foundation-contact-label">Alamat Utama</p>
                            <h3 style="font-size: 18px;">Kantor Yayasan</h3>
                            <p style="font-size: 12px;">Kp, Jl. Sindangreret, Dangdeur, Kec. Bungursari, Kabupaten Purwakarta, Jawa Barat 41181</p>
                        </div>
                        <div class="foundation-contact-block" style="flex: 1 1 260px;">
                            <h3 style="font-size: 18px;">Kontak</h3>
                            <p style="font-size: 12px;"><strong>Telepon / WhatsApp</strong><br>085714673916 (A. Dede Ali)<br>081958159264 (Wiwi Suherti)</p>
                            <p style="font-size: 12px;"><strong>Email</strong><br>nuurudz@gmail.com</p>
                        </div>
                        <div class="foundation-contact-block" style="flex: 1 1 260px;">
                            <h3 style="font-size: 18px;">Media Sosial</h3>
                            <p style="font-size: 12px;"><strong>Instagram</strong><br>ponpes_nuurudzholaam</p>
                            <p style="font-size: 12px;"><strong>Facebook</strong><br>Ponpes Nuurudzholaam</p>
                            <p style="font-size: 12px;"><strong>YouTube</strong><br>Nuzo Tv</p>
                            <p style="font-size: 12px;"><strong>TikTok</strong><br>Media Nuzo</p>
                        </div>
                    </div>
                </div>
                <footer class="site-footer">Yayasan Raudhah Syarifah <span>•</span> Sistem Informasi Sarana dan Prasarana</footer>
            </section>
        </main>
    </body>
</html>