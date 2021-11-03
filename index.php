<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>


  <link rel="stylesheet" type="text/css" href="style.css">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    
  </head>
  <body>
    <form action="" class="decor" method="POST">
      <div class="form-left-decoration"></div>
      <div class="form-right-decoration"></div>
      <div class="circle"></div>
      <div class="form-inner">
        <h1 style="text-align: center;">Kimlik Numarası Doğrulama</h1>
        <input type="text" placeholder="İsim" name="ad" required>
        <input type="text" placeholder="Soyisim" name="soyad" required>
        <input type="text" placeholder="Doğum Yılı" name="dogumyılı" pattern="\d{4}" title="Sadece Yıl Girilebilir ! (Örnek 1900)" required>
        <input type="text" placeholder="TC Kimlik Numarası" name="tcno" pattern="\d{11}" title="Kimlik Numarasını 11 Hane Olacak Şekilde Giriniz. (Örnek 11111111111)" required>
        <button type="submit" name="submit" href="/">Gönder</button>
        <button type="reset" name="reset" href="/">Temizle</button>
      </div>
    </form>  <br>



<?php


if (isset($_POST["submit"])) {



	function tc_dogrula($bilgiler){

		$gonder = '<?xml version="1.0" encoding="utf-8"?>
		<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
		<soap:Body>
		<TCKimlikNoDogrula xmlns="http://tckimlik.nvi.gov.tr/WS">
		<TCKimlikNo>'.$bilgiler["tcno"].'</TCKimlikNo>
		<Ad>'.$bilgiler["isim"].'</Ad>
		<Soyad>'.$bilgiler["soyisim"].'</Soyad>
		<DogumYili>'.$bilgiler["dogumyili"].'</DogumYili>
		</TCKimlikNoDogrula>
		</soap:Body>
		</soap:Envelope>';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,            "https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx" );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_POST,           true );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS,    $gonder);
		curl_setopt($ch, CURLOPT_HTTPHEADER,     array(
		'POST /Service/KPSPublic.asmx HTTP/1.1',
		'Host: tckimlik.nvi.gov.tr',
		'Content-Type: text/xml; charset=utf-8',
		'SOAPAction: "http://tckimlik.nvi.gov.tr/WS/TCKimlikNoDogrula"',
		'Content-Length: '.strlen($gonder)
		));
		$gelen = curl_exec($ch);
		curl_close($ch);

	    return strip_tags($gelen);
	}

$nm        = $_POST['ad'];
$sn        = $_POST['soyad'];
$bd        = $_POST['dogumyılı'];
$id        = $_POST['tcno'];

$bilgiler = array(
"isim"      => "$nm",
"soyisim"   => "$sn",
"dogumyili" => "$bd",
"tcno"      => "$id"
);

$sonuc = tc_dogrula($bilgiler);

$true = "Doğrulama başarılı!";
$false = "Doğrulama Başarısız!";

if($sonuc=="true"){
echo "<script type='text/javascript'>alert('$true');</script>";
}else{
echo "<script type='text/javascript'>alert('$false');</script>";
}
//header("Refresh: 0; url=index.php");
//die();
}
?>

</body>
</html>