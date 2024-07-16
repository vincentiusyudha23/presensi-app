<li class="nav-item {{ request()->is('*akun-petugas*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/akun-petugas') }}">
        <i style="font-size: 1.5rem" class="fas fa-fw fa-users-cog"></i>
        <span>Akun Petugas</span></a>
</li>
<li class="nav-item {{ request()->is('*jadwal*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/jadwal') }}">
        <i style="font-size: 1.5rem" class="fas fa-fw fa-clock"></i>
        <span>Jadwal Kerja</span></a>
</li>
<li class="nav-item {{ request()->is('*izin*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/izin') }}">
        <i style="font-size: 1.5rem" class="fas fa-fw fa-folder"></i>
        <span>Izin/Cuti</span></a>
</li>
<li class="nav-item {{ request()->is('*presensi*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/presensi') }}">
        <i style="font-size: 1.5rem" class="fas fa-fw fa-clipboard"></i>
        <span>Presensi</span></a>
</li>
<li class="nav-item {{ request()->is('*gaji*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/gaji') }}">
        <i style="font-size: 1.5rem" class="fas fa-fw fa-file-invoice-dollar"></i>
        <span>Penggajian</span></a>
</li>
<li class="nav-item {{ request()->is('*pengaduan*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/pengaduan') }}">
        <i style="font-size: 1.5rem" class="fas fa-fw fa-exclamation-triangle"></i>
        <span>Pengaduan</span></a>
</li>
