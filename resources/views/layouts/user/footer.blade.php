<footer class="site-footer">
  <div class="footer-inner">
    <div class="footer-grid">
      <div class="footer-brand">
        <img src="{{ asset('logo-tensitrack.png') }}" alt="TensiTrack logo" />
        <div>
          <div class="footer-title">TensiTrack</div>
          <p class="footer-desc">TensiTrack adalah platform sistem pakar berbasis web yang menggunakan metode forward chaining untuk skrining risiko hipertensi yang cepat dan akurat</p>
        </div>
      </div>

      <div>
        <div class="footer-title">Tautan</div>
        <ul class="footer-list">
          <li><a href="{{ url('/') }}">Beranda</a></li>
          <li><a href="{{ url('/fitur') }}">Fitur</a></li>
          <li><a href="{{ url('/alur-kerja') }}">Alur Kerja</a></li>
          <li><a href="{{ url('/tentang') }}">Tentang</a></li>
          <li><a href="{{ url('/artikel') }}">Artikel</a></li>
          <li><a href="{{ url('/faq') }}">FAQ</a></li>
        </ul>
      </div>

      <div>
        <div class="footer-title">Layanan</div>
        <ul class="footer-list">
          <li><a href="{{ url('/bmi') }}">Kalkulator BMI</a></li>
          <li><a href="{{ url('/skrining-hipertensi') }}">Skrining risiko hipertensi</a></li>
        </ul>
      </div>

      <div>
        <div class="footer-title">Kontak Kami</div>
        <div class="contact-item">
          <div class="icon"><i class="fa-regular fa-envelope"></i></div>
          <div class="text"><a href="mailto:tensitrack.id@gmail.com" style="color:inherit; text-decoration:none">tensitrack.id@gmail.com</a></div>
        </div>
        <div class="contact-item">
          <div class="icon"><i class="fa-solid fa-phone"></i></div>
          <div class="text">+62 852 7333 7881</div>
        </div>
        <div class="contact-item">
          <div class="icon"><i class="fa-solid fa-location-dot"></i></div>
          <div class="text">Jl. Terusan Ryacudu, Way Huwi, Kec. Jati Agung, Kabupaten Lampung Selatan, Lampung 35365</div>
        </div>
      </div>
    </div>

    <div class="footer-bottom">&copy; {{ date('Y') }} TensiTrack &#8226; All rights reserved</div>
  </div>
</footer>
