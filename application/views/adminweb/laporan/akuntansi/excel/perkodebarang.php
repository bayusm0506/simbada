<style type="text/css">
.title {
	font-weight: bold;
	font-size: 24px;
}
</style>
<table width="1024">
<tr>
<td></td>
<td height="40" align="center" class="title">REKAPITULASI BARANG MILIK DAERAH MENURUT KODE BARANG</td>
</tr>
<tr>
  <td></td>
  <td>
  <p align="center"><strong><?php echo $periode; ?></strong></p>
  <table border="0" cellspacing="0" cellpadding="0" width="498">
    <tr>
      <td width="174" valign="top"><p><strong>PROVINSI</strong></p></td>
      <td width="324" valign="top"><p><strong>: SUMATERA UTARA</strong></p></td>
      </tr>
    <tr>
      <td width="174" valign="top"><p><strong>KABUPATEN/KOTA</strong></p></td>
      <td width="324" valign="top"><p><strong>: MEDAN</strong></p></td>
      </tr>
    <tr>
      <td width="174" valign="top"><p><strong>BIDANG</strong></p></td>
      <td width="324" valign="top"><p><strong>: <?php echo $nama_upb->Nm_bidang ?></strong></p></td>
      </tr>
    <tr>
      <td width="174" valign="top"><p><strong>UNIT ORGANISASI</strong></p></td>
      <td width="324" valign="top"><p><strong>: <?php echo $nama_upb->Nm_unit ?></strong></p></td>
      </tr>
    <tr>
      <td width="174" valign="top"><p><strong>SUB UNIT ORGANISASI</strong></p></td>
      <td width="324" valign="top"><p><strong>: <?php echo $nama_upb->Nm_sub_unit ?></strong></p></td>
      </tr>
    <tr>
      <td width="174" valign="top"><p><strong>UPB</strong></p></td>
      <td width="324" valign="top"><p><strong>: <?php echo $nama_upb->Nm_UPB; ?></strong></p></td>
      </tr>
    <tr>
      <td width="174" valign="bottom"><p>NO. KODE LOKASI</p></td>
      <td width="324" valign="bottom"><p>: 12.02.14.05.01.01.01</p></td>
      </tr>
  </table>
  </td>
</tr>
<tr>
<td></td>
<td>
  <table width="1024" border="1" cellpadding="8" style="border-collapse:collapse;">      
      <tr height="40" style="background-color:#9AE456;">
        <td width="20" style="text-align: center; font-weight: bold;">No</td>
        <td colspan="5" style="text-align: center; font-weight: bold;">Kode Barang</td>
        <td width="249" style="text-align: center; font-weight: bold;"><strong>Nama / Jenis Barang</strong></td>
        <td width="163" style="text-align: center; font-weight: bold;">Jumlah Barang</td>
        <td width="233" style="text-align: center; font-weight: bold;">Jumlah Nilai</td>
        <td width="150" style="text-align: center; font-weight: bold;">Keterangan</td>
      </tr>
      
      <tr>
        <td align="center" valign="top" bgcolor="#FDF8F4" style="text-align: center; font-weight: bold;">1</td>
        <td colspan="5" valign="top" bgcolor="#FDF8F4" style="text-align: center; font-weight: bold;">2</td>
        <td valign="top" bgcolor="#FDF8F4" style="text-align: center"><strong>3</strong></td>
        <td valign="top" bgcolor="#FDF8F4" style="text-align: center"><strong>4</strong></td>
        <td align='right' valign="top" bgcolor="#FDF8F4" style="text-align: center"><strong>5</strong></td>
        <td valign="top" bgcolor="#FDF8F4" style="text-align: center"><strong>6</strong></td>
      </tr>
	<?php
		$no=1;
		$jumlah=0;
		$total =0;
		foreach($data_view->result() as $dp)
		{
	?>
      <tr>
        <td valign="top" align="center"><?php echo $no; ?></td>
        <td width="17" valign="top"><?php echo $dp->Kd_Aset1; ?></td>
        <td width="17" valign="top"><?php echo $dp->Kd_Aset2; ?></td>
        <td width="17" valign="top"><?php echo $dp->Kd_Aset3; ?></td>
        <td width="17" valign="top"><?php echo $dp->Kd_Aset4; ?></td>
        <td width="17" valign="top"><?php echo $dp->Kd_Aset5; ?></td>
        <td valign="top"><?php echo $dp->Nm_Aset5; ?></td>
        <td valign="top"  align='right'><?php echo $dp->Jumlah; ?></td>
        <td align='right' valign="top"><?php echo rp($dp->Harga); ?></td>
        <td valign="top">-</td>
      </tr>
	 <?php
	 		$no++;
			$jumlah = $jumlah + $dp->Jumlah;
			$total 	= $total + $dp->Harga;
	 	}
	 ?>
	 <tr>
        <td valign="top" align="center">&nbsp;</td>
        <td colspan="5" valign="top">&nbsp;</td>
        <td valign="top">&nbsp;</td>
        <td valign="top"  align='right'><?php echo $jumlah; ?></td>
        <td valign="top"  align='right'><?php echo rp($total); ?></td>
        <td valign="top">&nbsp;</td>
      </tr>
  </table>
  <table width="100%" border="0">
    <tr>
      <td width="24%">&nbsp;</td>
      <td width="49%">&nbsp;</td>
      <td width="27%">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="ttd">Mengetahui</td>
      <td>&nbsp;</td>
      <td class="ttd"><?php echo $tanggal; ?></td>
    </tr>
    <tr>
      <td class="ttd">JABATAN PIMPINAN</td>
      <td>&nbsp;</td>
      <td class="ttd">PENGURUS BARANG</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="ttd">Nama Pimpinan</td>
      <td>&nbsp;</td>
      <td class="ttd">nama pengurus</td>
    </tr>
    <tr>
      <td class="ttd">NIP :</td>
      <td>&nbsp;</td>
      <td class="ttd">NIP :</td>
    </tr>
  </table>
  <p>&nbsp;</p></td>
</tr>
</table>
