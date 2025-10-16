<?php
require_once '../db/conn.php';
if ($_SESSION['peran'] == '0') { ?>

<!-- Admin -->
    <li class="nav-item">
        <a href="../dashboard_admin" class="nav-link 
    <?php
    if ($halaman == 'dashboard_admin') {
        echo 'active';
    }
    ?>">

            <i class=" nav-icon fas fa-home"></i>
            <p>
                Beranda
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="../data_supplier_admin" class="nav-link 
    <?php
    if ($halaman == 'data_supplier_admin') {
        echo 'active';
    }
    ?>">

            <i class=" nav-icon fas fa-dolly"></i>
            <p>
                Supplier
            </p>
        </a>
    </li>

    <?php
    $query_count = mysqli_query($conn, "SELECT COUNT(*) AS jumlah FROM tb_pengajuan_barang WHERE status_pengajuan='Menunggu'");
    $data_count = mysqli_fetch_assoc($query_count);
    ?>
    <?php
    if ($data_count['jumlah'] > 0) { ?>
        <li class="nav-item">
            <a href="../pengajuan_barang_admin" class="nav-link 
    <?php if ($halaman == 'pengajuan_barang_admin')
                echo 'active'; ?>">
                <i class="nav-icon fas fa-paper-plane"></i>
                <p>
                    Pengajuan Barang
                    <span class="badge badge-primary">
                        <?= $data_count['jumlah']; ?>
                    </span>
                </p>
            </a>
        </li> <?php
    } else { ?>
        <li class="nav-item">
            <a href="../pengajuan_barang_admin" class="nav-link 
    <?php if ($halaman == 'pengajuan_barang_admin')
                echo 'active'; ?>">
                <i class="nav-icon fas fa-paper-plane"></i>
                <p>
                    Pengajuan Barang
                </p>
            </a>
        </li> <?php
    }
    ?>

    <li class="nav-item">
        <a href="../data_retur_admin" class="nav-link 
    <?php
    if ($halaman == 'data_retur_admin') {
        echo 'active';
    }
    ?>">

            <i class=" nav-icon fas fa-undo"></i>
            <p>
                Retur Barang
            </p>
        </a>
    </li>

    <li class="nav-item has-treeview 
    <?php if ($halaman == 'stok_masuk_admin' || $halaman == 'stok_keluar_admin')
        echo 'menu-open'; ?>">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-boxes"></i>
            <p>
                Stok
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="../stok_masuk_admin" class="nav-link 
                <?php if ($halaman == 'stok_masuk_admin')
                    echo 'active'; ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Stok Masuk</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../stok_keluar_admin" class="nav-link 
                <?php if ($halaman == 'stok_keluar_admin')
                    echo 'active'; ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Stok Keluar</p>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item has-treeview 
    <?php if ($halaman == 'laporan_rusak_admin' || $halaman == 'laporan_penjualan_admin')
        echo 'menu-open'; ?>">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-book"></i>
            <p>
                Laporan
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="../laporan_rusak_admin" class="nav-link 
                <?php if ($halaman == 'laporan_rusak_admin')
                    echo 'active'; ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Barang Rusak / Kadaluarsa</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../laporan_penjualan_admin" class="nav-link 
                <?php if ($halaman == 'laporan_penjualan_admin')
                    echo 'active'; ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Penjualan</p>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item">
        <a href="../bagi_hasil_admin" class="nav-link 
    <?php
    if ($halaman == 'bagi_hasil_admin') {
        echo 'active';
    }
    ?>">

            <i class=" nav-icon fas fa-handshake"></i>
            <p>
                Bagi Hasil
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="../pembayaran_supplier_admin" class="nav-link 
    <?php
    if ($halaman == 'pembayaran_supplier_admin') {
        echo 'active';
    }
    ?>">

            <i class=" nav-icon fas fa-money-bill"></i>
            <p>
                Pembayaran Supplier
            </p>
        </a>
    </li>
    <?php
} else { ?>

<!-- Supplier -->
    <li class="nav-item">
        <a href="../dashboard_supplier" class="nav-link 
    <?php
    if ($halaman == 'dashboard_supplier') {
        echo 'active';
    }
    ?>">

            <i class=" nav-icon fas fa-home"></i>
            <p>
                Beranda
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="../data_barang_supplier" class="nav-link 
    <?php
    if ($halaman == 'data_barang_supplier') {
        echo 'active';
    }
    ?>">

            <i class=" nav-icon fas fa-box"></i>
            <p>
                Barang Saya
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="../data_barang_supplier/pengajuan_barang_supplier.php" class="nav-link 
    <?php
    if ($halaman == 'pengajuan_barang_supplier') {
        echo 'active';
    }
    ?>">

            <i class=" nav-icon fas fa-paper-plane"></i>
            <p>
                Pengajuan Barang
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="../data_retur_supplier" class="nav-link 
    <?php
    if ($halaman == 'data_retur_supplier') {
        echo 'active';
    }
    ?>">

            <i class=" nav-icon fas fa-undo"></i>
            <p>
                Retur Barang
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="../laporan_penjualan_supplier" class="nav-link 
    <?php
    if ($halaman == 'laporan_penjualan_supplier') {
        echo 'active';
    }
    ?>">

            <i class=" nav-icon fas fa-book"></i>
            <p>
                Laporan Penjualan
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="../laporan_retur_supplier" class="nav-link 
    <?php
    if ($halaman == 'laporan_retur_supplier') {
        echo 'active';
    }
    ?>">

            <i class=" nav-icon fas fa-book"></i>
            <p>
                Laporan Retur
            </p>
        </a>
    </li>


    <?php
}
?>