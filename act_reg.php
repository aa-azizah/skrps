<?php session_start(); include("db_connection.php"); 
if(!isset($_POST['noid'])){
	eksyen('','index.php');
}else{
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
	$userid = mysql_real_escape_string($_POST['userid']);
	$pw1 = mysql_real_escape_string($_POST['pw1']);
	$pw2 = mysql_real_escape_string($_POST['pw2']);
		if($pw1 != $pw2){
			eksyen('Password does not match','index.php');
		}
	$jenjang = mysql_real_escape_string($_POST['jenjang']);
	$instansi = mysql_real_escape_string($_POST['instansi']);
	$jurusan = mysql_real_escape_string($_POST['jurusan']);
	$minat = mysql_real_escape_string($_POST['minat']);
	$kemampuan = mysql_real_escape_string($_POST['kemampuan']);	

	//--------------------------photo----------------------------------//
    $tmp_name  = $_FILES['foto']['tmp_name']; //nama local temp file di server
    $file_size = $_FILES['foto']['size']; //ukuran file (dalam bytes)
    $fp = fopen($tmp_name, 'r'); // open file (read-only, binary)
    $file_type = $_FILES['foto']['type']; //tipe filenya (langsung detect MIMEnya)
    $photo = fread($fp, $file_size) or die("Tidak dapat membaca source file"); // read file
    $photo = mysql_real_escape_string($photo) or die("Tidak dapat membaca source file"); // parse image ke string
    fclose($fp); // tutup file
    //--------------------------photo----------------------------------//

    //--------------------------cv----------------------------------//
    $tmp_name  = $_FILES['cv']['tmp_name']; //nama local temp file di server
    $file_size = $_FILES['cv']['size']; //ukuran file (dalam bytes)
    $fp = fopen($tmp_name, 'r'); // open file (read-only, binary)
    $file_type = $_FILES['cv']['type']; //tipe filenya (langsung detect MIMEnya)
    $cv = fread($fp, $file_size) or die("Tidak dapat membaca source file"); // read file
    $cv = mysql_real_escape_string($cv) or die("Tidak dapat membaca source file"); // parse image ke string
    fclose($fp); // tutup file
    //--------------------------cv----------------------------------//

    // insert into user //
    mysql_query("insert into user(GUID,USERNAME,PASSWORD,DTMCRT) values(uuid(),'$userid',md5('$pw2'),now())");
    $qu = mysql_query("select GUID from user where USERNAME='$userid'");
    $du = mysql_fetch_array($qu);
    $iduser = $du['GUID'];
    $_SESSION['reg_id'] = $iduser;
    $_SESSION['reg_email'] = $email;

    // insert into user_detail //
    mysql_query("insert into user_detail(GUID,USER_ID,FIRSTNAME,LASTNAME,ID_CARD,NIM_NIS,EMAIL,PLACE_OF_BIRTH,DATE_OF_BIRTH,GENDER,USER_ADDRESS,HOBBY,PHONE1,PHONE2,CONCERN,ABOUT_ME,CV,PHOTO,DTMCRT,USRCRT) values(uuid(),'$iduser','$namadepan','$namabelakang','$noid','$nims','$email','$tempatlahir','$tanggallahir','$jk','$alamat','$hobi','$tel1','$tel2','$minat','$kemampuan','$cv','$photo',now(),'$userid')");
    $qu = mysql_query("select GUID from user_detail where USER_ID='$iduser'");
    $du = mysql_fetch_array($qu);
    $iduserdetail = $du['GUID'];

    // user level
    $query_group_guid = mysql_query("SELECT GUID FROM ms_group WHERE GROUP_NAME = 'USER'");
    $row = mysql_fetch_array($query_group_guid);
    $msgroup_guid=$row['GUID'];

    // insert into member_of_group
    mysql_query("insert into member_of_group(GUID,MS_GROUP_ID,USER_DETAIL_ID,DTMCRT,USRCRT) values(uuid(),'$msgroup_guid','$iduserdetail',now(),'$userid')");

    // instansi
    $qi = mysql_query("select GUID,INSTITUTE_NAME from institute where INSTITUTE_NAME='$instansi'");
    $ci = mysql_num_rows($qi);
    if($ci==1){
        $di = mysql_fetch_array($qi);
        $idins = $di['GUID'];
    }else{
        mysql_query("insert into institute(GUID,INSTITUTE_NAME,INSTITUTE_TYPE,DTMCRT,USRCRT) values(uuid(),'$institute','A',now(),'$userid')");
        $di = mysql_fetch_array($qi);
        $idins = $di['GUID'];
    }

    // jurusan
    $qi = mysql_query("select GUID,MAJOR_NAME from major where MAJOR_NAME='$jurusan'");
    $ci = mysql_num_rows($qi);
    if($ci==1){
        $di = mysql_fetch_array($qi);
        $idjur = $di['GUID'];
    }else{
        mysql_query("insert into major(GUID,MAJOR_NAME,DTMCRT,USRCRT) values(uuid(),'$jurusan',now(),'$userid')");
        $di = mysql_fetch_array($qi);
        $idjur = $di['GUID'];
    }

    // insert into user_education
    mysql_query("insert into user_education(GUID,USER_DETAIL_ID,EDUCATION_LEVEL_ID,INSTITUTE_ID,MAJOR_ID,DTMCRT,USRCRT) values(uuid(),'$iduserdetail','$jenjang','$idins','$idjur',now(),'$userid')");

    // OK
    eksyen('Registered!','index.php');
}
?>