<?php
$iduser = $_SESSION['iduser'];
$iduserdetail = data_user_detail_user($iduser,'GUID');
$a = mysql_query("select * from user where GUID='$iduser'");
$b = mysql_fetch_array($a);
$q = mysql_query("select * from user_detail where GUID='$iduserdetail'");
$d = mysql_fetch_array($q);
if(isset($_POST['namadepan'])){
	$iduser = $_SESSION['iduser'];
	$userr = $_SESSION['username'];
	$noid = mysql_real_escape_string($_POST['noid']);
	$nims = mysql_real_escape_string($_POST['nims']);
	$namadepan = mysql_real_escape_string($_POST['namadepan']);
	$namabelakang = mysql_real_escape_string($_POST['namabelakang']);
	$tempatlahir = mysql_real_escape_string($_POST['tempatlahir']);
	$tanggallahir = mysql_real_escape_string($_POST['tanggallahir']);
	$jk = mysql_real_escape_string($_POST['jk']);
	$alamat = mysql_real_escape_string($_POST['alamat']);
	$tel1 = mysql_real_escape_string($_POST['tel1']);
	$tel2 = mysql_real_escape_string($_POST['tel2']);
	$email = mysql_real_escape_string($_POST['email']);
	$hobi = mysql_real_escape_string($_POST['hobi']);

	if($_FILES['foto']['name']!=''){
		//--------------------------photo----------------------------------//
	    $tmp_name  = $_FILES['foto']['tmp_name']; //nama local temp file di server
	    $file_size = $_FILES['foto']['size']; //ukuran file (dalam bytes)
      $file_type = $_FILES['foto']['type']; //tipe filenya (langsung detect MIMEnya)
        $tipe = array("image/jpeg","image/png","image/gif");
        if(!in_array($file_type, $tipe)) eksyen('Improper File Type for Foto. Use JPEG/JPG/PNG/GIF only.','?p=profil_user');
	    $fp = fopen($tmp_name, 'r'); // open file (read-only, binary)
	    $photo = fread($fp, $file_size) or die("Tidak dapat membaca source file"); // read file
	    $photo = mysql_real_escape_string($photo) or die("Tidak dapat membaca source file"); // parse image ke string
	    fclose($fp); // tutup file
	    mysql_query("update user_detail set PHOTO='$photo', MIME_PHOTO='$file_type' where USER_ID='$iduser'");
	    //--------------------------photo----------------------------------//
	}

	if($_FILES['cv']['name']!=''){
	    //--------------------------cv----------------------------------//
	    $tmp_name  = $_FILES['cv']['tmp_name']; //nama local temp file di server
	    $file_size = $_FILES['cv']['size']; //ukuran file (dalam bytes)
      $file_type = $_FILES['cv']['type']; //tipe filenya (langsung detect MIMEnya)
        if($file_type!="application/pdf") eksyen('Improper File Type for CV. Use PDF only.','?p=profil_user');
	    $fp = fopen($tmp_name, 'r'); // open file (read-only, binary)
	    $cv = fread($fp, $file_size) or die("Tidak dapat membaca source file"); // read file
	    $cv = mysql_real_escape_string($cv) or die("Tidak dapat membaca source file"); // parse image ke string
	    fclose($fp); // tutup file
	    mysql_query("update user_detail set CV='$cv', MIME_CV='$file_type' where USER_ID='$iduser'");
	    //--------------------------cv----------------------------------//
	}

	// password
	if($_POST['pw']!=""){
		$pw = mysql_real_escape_string($_POST['pw']);
		mysql_query("update user set PASSWORD=md5('$pw') where GUID='$iduser'");
	}

	$q = mysql_query("update user_detail set FIRSTNAME='$namadepan', LASTNAME='$namabelakang', ID_CARD='$noid', NIM_NIS='$nims', EMAIL='$email', PLACE_OF_BIRTH='$tempatlahir', DATE_OF_BIRTH='$tanggallahir', GENDER='$jk', USER_ADDRESS='$alamat', HOBBY='$hobi', PHONE1='$tel1', PHONE2='$tel2', DTMUPD=now(), USRUPD='$userr' where USER_ID='$iduser'");
	if($q){
		eksyen('Registration saved!','?p=profil_user');
	}else{
		eksyen('Error!','?p=profil_user');
	}
}
?>
<h1>Edit Profile</h1>
<script type="text/javascript" src="js/isNumber.js"></script>
<form class="form-horizontal" action="" method="post" id="myform" enctype="multipart/form-data">
  <div class="form-group" style="background:#333">
    <label class="col-sm-2 control-label">Nomor Identitas</label>
    <div class="col-sm-4">
      <input type="text" name="noid" id="inputNoid" class="form-control" value="<?=$d['ID_CARD'];?>" required="required" maxlength="25">
    </div>
    <label class="col-sm-2 control-label">NIM/NIS</label>
    <div class="col-sm-4">
      <input type="text" name="nims" id="inputNims" class="form-control" value="<?=$d['NIM_NIS'];?>" onkeypress="return isNumber(event)" required="required" maxlength="15">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">Nama Depan</label>
    <div class="col-sm-4">
      <input type="text" name="namadepan" id="inputNamadepan" class="form-control" value="<?=$d['FIRSTNAME'];?>" required="required" maxlength="30">
    </div>
    <label class="col-sm-2 control-label">Nama Belakang</label>
    <div class="col-sm-4">
      <input type="text" name="namabelakang" id="inputNamabelakang" class="form-control" value="<?=$d['LASTNAME'];?>" required="required" maxlength="30">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">Tempat Lahir</label>
    <div class="col-sm-4">
      <input type="text" name="tempatlahir" id="inputTempatlahir" class="form-control" value="<?=$d['PLACE_OF_BIRTH'];?>" required="required" maxlength="50">
    </div>
    <label class="col-sm-2 control-label">Tanggal Lahir</label>
    <div class="col-sm-4">
      <input type="date" name="tanggallahir" id="tgl" class="form-control" value="<?=$d['DATE_OF_BIRTH'];?>" required="required" aria-describedby="helpBlock">
      <span id="helpBlock" class="help-block">Format: year-month-day</span>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">Jenis Kelamin</label>
    <div class="col-sm-10">
      <div class="radio">
        <label>
          <input type="radio" name="jk" id="inputJk" value="M"<?php if($d['GENDER']=='M'){ echo " checked";}?>>
          Laki-laki
        </label>
        &nbsp;&nbsp;
        <label>
          <input type="radio" name="jk" id="inputJk" value="F"<?php if($d['GENDER']=='F'){ echo " checked";}?>>
          Perempuan
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">Alamat</label>
    <div class="col-sm-10">
      <textarea class="form-control" name="alamat"><?=$d['USER_ADDRESS'];?></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">Telepon 1</label>
    <div class="col-sm-4">
      <input type="tel" name="tel1" id="inputTel1" class="form-control" value="<?=$d['PHONE1'];?>" required="required" onkeypress="return isNumber(event)" maxlength="15">
    </div>
    <label class="col-sm-2 control-label">Telepon 2</label>
    <div class="col-sm-4">
      <input type="tel" name="tel2" id="inputTel2" class="form-control" value="<?=$d['PHONE2'];?>" required="required" onkeypress="return isNumber(event)" maxlength="15">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">Email</label>
    <div class="col-sm-4">
      <input type="email" name="email" id="inputEmail" class="form-control" value="<?=$d['EMAIL'];?>" required="required" title="" maxlength="128">
    </div>
    <label class="col-sm-2 control-label">Hobi</label>
    <div class="col-sm-4">
      <input type="text" name="hobi" id="inputHobi" class="form-control" value="<?=$d['HOBBY'];?>" required="required" maxlength="160">
    </div>
  </div>
  <div class="form-group" style="background:#333">
    <label class="col-sm-2 control-label">User ID</label>
    <div class="col-sm-4">
      <input type="text" name="userid" id="inputUserid" class="form-control" value="<?=$b['USERNAME'];?>" readonly maxlength="30">
    </div>
    <label class="col-sm-2 control-label">Password</label>
    <div class="col-sm-4">
      <input type="password" name="pw" id="password" class="form-control">
    </div>
  </div>
  <div class="form-group" style="background:#333">
    <label class="col-sm-4 control-label">Upload Foto (JPEG/JPG/PNG)</label>
    <div class="col-sm-2">
      <input type="file" name="foto" >
    </div>
    <label class="col-sm-1 control-label">Upload CV (PDF)</label>
    <div class="col-sm-2">
      <input type="file" name="cv" >
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-12 text-center">
      Sebelum mensubmit, cek dan teliti data yang Anda isikan.<br>
      Semua data dan informasi Anda adalah benar dan dapat dipertanggungjawabkan.
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-12 text-center">
      <button type="submit" id="submit" class="btn btn-primary btn-lg">Submit</button>
      <button type="reset" class="btn btn-primary btn-lg">Reset</button>
    </div>
  </div>
</form>  