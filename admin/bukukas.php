<?php include 'header.php'; ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            BUKU KAS UMUM
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <section class="col-lg-12">
                <div class="box box-info">
                    <div class="box-header">
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                data-target="#exampleModal">
                                <i class="fa fa-plus"></i> &nbsp Tambah Transaksi
                            </button>
                            <a href="bukukas_print.php" target="_blank" class="btn btn-sm btn-primary"><i
                                    class="fa fa-print"></i> &nbsp
                                PRINT</a>
                        </div>
                    </div>
                    <hr>
                    <?php 
                if(isset($_GET['alert'])){
                  if($_GET['alert']=='gagal'){
                    ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-warning"></i> Peringatan !</h4>
                        Ekstensi Tidak Diperbolehkan
                    </div>
                    <?php
                  }else if($_GET['alert']=="berhasil"){
                    ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> Success</h4>
                        Berhasil Disimpan
                    </div>
                    <?php
                  }else if($_GET['alert']=="berhasilupdate"){
                    ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> Success</h4>
                        Berhasil Update
                    </div>
                    <?php
                  }
                }
                ?>
                </div>
                <div class="box-body">

                    <!-- Modal -->
                    <form action="bukukas_act.php" method="post" enctype="multipart/form-data">
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="exampleModalLabel">Tambah Transaksi</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="form-group">
                                            <label>Tanggal</label>
                                            <input type="text" name="tanggal" required="required"
                                                class="form-control datepicker2">
                                        </div>

                                        <div class="form-group">
                                            <label>Jenis</label>
                                            <select name="jenis" class="form-control" required="required">
                                                <option value="">- Pilih -</option>
                                                <option value="Pemasukan">Pemasukan</option>
                                                <option value="Pengeluaran">Pengeluaran</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Kategori</label>
                                            <select name="kategori" class="form-control" required="required">
                                                <option value="">- Pilih -</option>
                                                <?php 
                          $kategori = mysqli_query($koneksi,"SELECT * FROM kategori ORDER BY kategori ASC");
                          while($k = mysqli_fetch_array($kategori)){
                            ?>
                                                <option value="<?php echo $k['kategori_id']; ?>">
                                                    <?php echo $k['kategori']; ?></option>
                                                <?php 
                          }
                          ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Nominal</label>
                                            <input type="number" name="nominal" required="required" class="form-control"
                                                placeholder="Masukkan Nominal ..">
                                        </div>

                                        <div class="form-group">
                                            <label>Keterangan</label>
                                            <textarea name="keterangan" class="form-control" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Rekening Bank</label>
                                            <select name="bank" class="form-control" required="required">
                                                <option value="">- Pilih -</option>
                                                <?php 
                          $bank = mysqli_query($koneksi,"SELECT * FROM bank");
                          while($b = mysqli_fetch_array($bank)){
                            ?>
                                                <option value="<?php echo $b['bank_id']; ?>">
                                                    <?php echo $b['bank_nama']; ?></option>
                                                <?php 
                          }
                          ?>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="table-datatable">
                            <thead>
                                <tr>
                                    <th rowspan="1" class="text-center">NO</th>
                                    <th width="10%" rowspan="2" class="text-center">TANGGAL</th>
                                    <th rowspan="2" class="text-center">PERIHAL</th>
                                    <th class="text-center">NO.KAS</th>
                                    <th class="text-center">MASUK</th>
                                    <th class="text-center">KELUAR</th>
                                    <th width="10%" rowspan="2">SALDO</th>
                                    <th rowspan="2" width="10%" class="text-center">OPSI</th>
                                </tr>

                            </thead>

                            <tbody>
                                <?php include '../koneksi.php';
$no = 1;

// Calculate the total income
$total_pemasukan = 0;
$data = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE transaksi_jenis='Pemasukan'");
while ($d = mysqli_fetch_array($data)) {
  $total_pemasukan += $d['transaksi_nominal'];
}

// Calculate the total expenditure
$total_pengeluaran = 0;
$data = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE transaksi_jenis='Pengeluaran'");
while ($d = mysqli_fetch_array($data)) {
  $total_pengeluaran += $d['transaksi_nominal'];
}

// Calculate the balance
$saldo = $total_pemasukan - $total_pengeluaran;

// Iterate over the transactions and update the balance
$data = mysqli_query($koneksi, "SELECT * FROM transaksi ORDER BY transaksi_id ASC");
while ($d = mysqli_fetch_array($data)) {
  if ($d['transaksi_jenis'] == 'Pengeluaran') {
    // Subtract the amount of the transaction from the balance
    $saldo -= $d['transaksi_nominal'];
  } else if ($d['transaksi_jenis'] == 'Pemasukan') {
    // Subtract the amount of the transaction from the balance
    $saldo += $d['transaksi_nominal'];
  }
  ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td class="text-center">
                                        <?php
  $tanggal = date('d-M-y', strtotime($d['transaksi_tanggal']));
  $tanggal = str_replace(
    ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
    $tanggal
  );
  echo $tanggal; // contoh hasil: "03-Jan-21"
?>
                                    </td>
                                    <td><?php echo $d['transaksi_keterangan']; ?></td>
                                    <td><?php echo $d['kode_rekening']; ?></td>
                                    <td class="text-center">
                                        <?php 
                        if($d['transaksi_jenis'] == "Pemasukan"){
                          echo "Rp. ".number_format($d['transaksi_nominal'])." ,-";
                        }else{
                          echo "-";
                        }
                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php 
                        if($d['transaksi_jenis'] == "Pengeluaran"){
                          echo "Rp. ".number_format($d['transaksi_nominal'])." ,-";
                        }else{
                          echo "-";
                        }
                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
     echo "Rp. ".number_format($saldo)." ,-";
   ?> </td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                            data-target="#edit_transaksi_<?php echo $d['transaksi_id'] ?>">
                                            <i class="fa fa-cog"></i>
                                        </button>

                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#hapus_transaksi_<?php echo $d['transaksi_id'] ?>">
                                            <i class="fa fa-trash"></i>
                                        </button>

                                    </td>

                                    <form action="bukukas_update.php" method="post" enctype="multipart/form-data">
                                        <div class="modal fade" id="edit_transaksi_<?php echo $d['transaksi_id'] ?>"
                                            tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="exampleModalLabel">Edit
                                                            transaksi</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="form-group" style="width:100%;margin-bottom:20px">
                                                            <label>Tanggal</label>
                                                            <input type="hidden" name="id"
                                                                value="<?php echo $d['transaksi_id'] ?>">
                                                            <input type="text" style="width:100%" name="tanggal"
                                                                required="required" class="form-control datepicker2"
                                                                value="<?php echo $d['transaksi_tanggal'] ?>">
                                                        </div>

                                                        <div class="form-group" style="width:100%;margin-bottom:20px">
                                                            <label>Jenis</label>
                                                            <select name="jenis" style="width:100%" class="form-control"
                                                                required="required">
                                                                <option value="">- Pilih -</option>
                                                                <option
                                                                    <?php if($d['transaksi_jenis'] == "Pemasukan"){echo "selected='selected'";} ?>
                                                                    value="Pemasukan">Pemasukan</option>
                                                                <option
                                                                    <?php if($d['transaksi_jenis'] == "Pengeluaran"){echo "selected='selected'";} ?>
                                                                    value="Pengeluaran">Pengeluaran</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group" style="width:100%;margin-bottom:20px">
                                                            <label>Kategori</label>
                                                            <select name="kategori" style="width:100%"
                                                                class="form-control" required="required">
                                                                <option value="">- Pilih -</option>
                                                                <?php 
                                      $kategori = mysqli_query($koneksi,"SELECT * FROM kategori ORDER BY kategori ASC");
                                      while($k = mysqli_fetch_array($kategori)){
                                        ?>
                                                                <option
                                                                    <?php if($d['transaksi_kategori'] == $k['kategori_id']){echo "selected='selected'";} ?>
                                                                    value="<?php echo $k['kategori_id']; ?>">
                                                                    <?php echo $k['kategori']; ?></option>
                                                                <?php 
                                      }
                                      ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group" style="width:100%;margin-bottom:20px">
                                                            <label>Nominal</label>
                                                            <input type="number" style="width:100%" name="nominal"
                                                                required="required" class="form-control"
                                                                placeholder="Masukkan Nominal .."
                                                                value="<?php echo $d['transaksi_nominal'] ?>">
                                                        </div>

                                                        <div class="form-group" style="width:100%;margin-bottom:20px">
                                                            <label>Keterangan</label>
                                                            <textarea name="keterangan" style="width:100%"
                                                                class="form-control"
                                                                rows="4"><?php echo $d['transaksi_keterangan'] ?></textarea>
                                                        </div>

                                                        <div class="form-group" style="width:100%;margin-bottom:20px">
                                                            <label>Upload File</label>
                                                            <input type="file" name="trnfoto" class="form-control"><br>
                                                            <!-- <small><?php echo $d['transaksi_foto'] ?></small> -->
                                                            <p class="help-block">Bila File
                                                                <?php echo "<a class='fancybox btn btn-xs btn-primary' target=_blank href='../gambar/bukti/$d[transaksi_foto]'>$d[transaksi_foto]</a>";?>
                                                                tidak dirubah kosongkan saja</p>
                                                        </div>

                                                        <div class="form-group" style="width:100%;margin-bottom:20px">
                                                            <label>Rekening Bank</label>
                                                            <select name="bank" class="form-control" required="required"
                                                                style="width:100%">
                                                                <option value="">- Pilih -</option>
                                                                <?php 
                                      $bank = mysqli_query($koneksi,"SELECT * FROM bank");
                                      while($b = mysqli_fetch_array($bank)){
                                        ?>
                                                                <option
                                                                    <?php if($d['transaksi_bank'] == $b['bank_id']){echo "selected='selected'";} ?>
                                                                    value="<?php echo $b['bank_id']; ?>">
                                                                    <?php echo $b['periode']; ?></option>
                                                                <?php 
                                      }
                                      ?>
                                                            </select>
                                                        </div>


                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="modal fade" id="lihat_transaksi_<?php echo $d['transaksi_id'] ?>"
                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="exampleModalLabel">Lihat Bukti
                                                        Upload</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <embed src="../gambar/bukti/<?php echo $d['transaksi_foto']; ?>"
                                                        type="application/pdf" width="100%" height="400px" />
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- modal hapus -->
                                    <div class="modal fade" id="hapus_transaksi_<?php echo $d['transaksi_id'] ?>"
                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="exampleModalLabel">Peringatan!
                                                    </h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <p>Yakin ingin menghapus data ini ?</p>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Tutup</button>
                                                    <a href="transaksi_hapus.php?id=<?php echo $d['transaksi_id'] ?>"
                                                        class="btn btn-primary">Hapus</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    </td>
                                </tr>
                                <?php 
                  }
                  ?>
                            </tbody>
                        </table>
                    </div>
                </div>

        </div>
    </section>
</div>
</section>

</div>


<?php include 'footer.php'; ?>