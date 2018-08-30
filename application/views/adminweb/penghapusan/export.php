<?php
error_reporting(0);
$nama_file = date('Y-m-d')."_KIB_A_".str_replace(' ', '_', $nama_upb->Nm_UPB).".xls";    
header("Pragma: public");   
header("Expires: 0");   
header("Cache-Control: must-revalidate, post-check=0,pre-check=0");     
header("Content-Type: application/force-download");     
header("Content-Type: application/octet-stream");   
header("Content-Type: application/download");   
header("Content-Disposition: attachment;filename=".$nama_file."");  
header("Content-Transfer-Encoding: binary ");
?>
<style type="text/css">
.title {
	font-size: 24px;
	font-weight: bold;
}
</style>

<table width="1110">
<tr>
<td></td>
<td height="40" align="center" class="title">KIB A. TANAH</td>
</tr>
<tr>
  <td></td>
  <td>
  <p align="center"><strong>KARTU INVENTARIS BARANG (KIB) A. TANAH</strong></p>
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
  <table width="1104" border="1" cellpadding="8" style="border-collapse:collapse;">      
      <tr height="40" style="background-color:#9AE456;">
        <td width="10" style="text-align: center; font-weight: bold;">No</td>
        <td width="68" style="text-align: center; font-weight: bold;"><strong>Jenis Barang/ Nama Barang</strong></td>
        <td width="62" style="text-align: center; font-weight: bold;"><strong>Kode Barang</strong></td>
        <td width="66" style="text-align: center; font-weight: bold;"><strong>Register</strong></td>
        <td width="54" style="text-align: center; font-weight: bold;"><p align="center"><strong>Luas</strong></p>
          <strong>(M2)</strong></td>
        <td width="110" style="text-align: center; font-weight: bold;"><p><strong>Tahun</strong></p>
          <strong>Pengadaan</strong></td>
        <td width="106" style="text-align: center; font-weight: bold;"><strong>Letak/ Alamat</strong></td>
        <td width="241" style="text-align: center; font-weight: bold;">Harga (Rp)</td>
        <td width="193" style="text-align: center; font-weight: bold;">Keterangan</td>
      </tr>
	<?php
		$no=1;
		foreach($data_view->result_array() as $dp)
		{
	?>
      <tr>
        <td valign="top" align="center"><?php echo $no; ?></td>
        <td valign="top"><?php echo $dp['Nm_Aset5'].'<font color="white">_</font>'; ?></td>
        <td valign="top"><?php echo $dp['Kd_Aset1'].".".$dp['Kd_Aset2'].".".$dp['Kd_Aset3'].".".$dp['Kd_Aset4'].".".$dp['Kd_Aset5']  ?></td>
        <td valign="top" align="center"><?php echo $dp['No_Register']; ?></td>
        <td valign="top"><?php echo $dp['Luas_M2']; ?></td>
        <td valign="top" align="center"><?php echo tahun($dp['Tgl_Perolehan']); ?></td>
        <td valign="top"><?php echo $dp['Alamat']; ?></td>
        <td align='right' valign="top"><?php echo rp($dp['Harga']); ?></td>
        <td valign="top"><?php echo $dp['Keterangan']; ?></td>
      </tr>
	 <?php
	 		$no++;
			$total 	= $total + $dp['Harga'];
	 	}
	 ?>
	 <tr style="background-color:#dbf2e4;">
        <td>&nbsp;</td>
        <td colspan="6"><b></b>Total</td>
        <td align='right'><b><?php echo rp($total); ?></b></td>
        <td>&nbsp;</td>
      </tr>
  </table>
</td>
</tr>
</table>
