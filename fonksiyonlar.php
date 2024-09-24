<?php
function UrunOzelligiSor($KategoriOzellikID, $UrunID){ // Sayfa Adı Öğrenme
  global $db;
  //Site Ayarlarını Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM urunozellik WHERE OzellikID = ? and UrunID = ?');
  $sorgu->execute([$KategoriOzellikID, $UrunID]);
  $UrunOzelligiSor = $sorgu->fetch(PDO:: FETCH_ASSOC);

  return $UrunOzelligiSor['OzellikDeger'];
}
function SliderFotograf($SliderID){ // Sayfa Adı Öğrenme
  global $db;
  //Site Ayarlarını Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM galeri WHERE SliderID = ? and FotoUrl IS NOT NULL ORDER BY id ASC LIMIT 1');
  $sorgu->execute([$SliderID]);
  $sliderfotograf = $sorgu->fetch(PDO:: FETCH_ASSOC);
  if (!$sliderfotograf['FotoUrl']) {
    $sliderfoto = "/assets/fotografyok.jpg";
  }else{
    $sliderfoto = $sliderfotograf['FotoUrl'];
  }
  return $sliderfoto;
}

function MusteriEkle($isim, $soyisim, $eposta, $telefon, $sifre, $tckimlikno){
    global $db;

  $sorgu = $db->prepare('INSERT INTO musteri SET
    MusteriIsim = ?,
    MusteriSoyisim = ?,
    MusteriEposta = ?,
    MusteriTelefon = ?,
    MusteriSifre = ?,
    MusteriTC = ?');
  $ekle = $sorgu->execute([
    $isim, $soyisim, $eposta, $telefon, $sifre, $tckimlikno]);
    $sonId = $db->lastInsertId();
    if ($ekle) {
      return array("success" => "true", "sonId" => $sonId);
    } else {
      $hata = $sorgu->errorInfo();
      return array("success" => "false", "error" => "Müşteri eklenirken bir hata oluştu $hata[2]");
    }
}


function EpostaKontrol($eposta){
  global $db;
  $epostasorgu = $db->prepare("SELECT * FROM musteri WHERE MusteriEposta = ?");
  $epostasorgu->execute(array($eposta)); // $eposta'yı bir dizi içinde göndermek gerekiyor
  $epostakontrol = $epostasorgu->fetch(PDO::FETCH_ASSOC);

  // Eğer $epostakontrol boş değilse (e-posta bulunduysa), MüşteriID döner, aksi takdirde false döner
  if ($epostakontrol) {
    return $epostakontrol['id'];
  } else {
    return "false";
  }
}

function SifreOlustur($length) {
    // İzin verilen karakterler
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    // Karakter dizisi uzunluğu
    $charLength = strlen($characters);
    // Rastgele şifre oluşturma
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, $charLength - 1)];
    }
    return $password;
}



function o($gelenyazi){
  global $db;
  $sorgu = $db->prepare('SELECT * FROM sitetanimlar WHERE TanimYeri = ?');
  $sorgu->execute([$gelenyazi]);
  $sitetanimlari = $sorgu->fetch(PDO:: FETCH_ASSOC);
  if ($_SESSION['Dil'] == "TR") {
    return $sitetanimlari['Deger_TR'];
  }elseif($_SESSION['Dil'] == "EN"){
    return $sitetanimlari['Deger_EN'];
  }elseif($_SESSION['Dil'] == "DE"){
    return $sitetanimlari['DE'];
  }else{
    return $sitetanimlari['Deger_TR'];
  }
}
// İstek gönderme fonksiyonunu tanımlayın
function sendRequest($url) {
    $response = file_get_contents($url);
    return $response;
}
function AracGroupIDVarmi($id){
  global $db;
  try {
       // Veritabanında belirtilen grup ID'sine sahip araçları sorgula
       $sorgu = $db->prepare('SELECT COUNT(*) FROM araclar WHERE group_id = ?');
       $sorgu->execute([$id]);
       $idsor = $sorgu->fetchColumn(); // Sorgunun sonucunu al

       // Veri varsa true, yoksa false döndür
       return ($idsor > 0);
   } catch (PDOException $e) {
       // Hata durumunda hata mesajını yazdır ve false döndür
       echo "Hata: " . $e->getMessage();
       return false;
   }
}
function LocationIDVarmi($id){
  global $db;
  try {
       // Veritabanında belirtilen grup ID'sine sahip araçları sorgula
       $sorgu = $db->prepare('SELECT COUNT(*) FROM location WHERE location_id = ?');
       $sorgu->execute([$id]);
       $idsor = $sorgu->fetchColumn(); // Sorgunun sonucunu al

       // Veri varsa true, yoksa false döndür
       return ($idsor > 0);
   } catch (PDOException $e) {
       // Hata durumunda hata mesajını yazdır ve false döndür
       echo "Hata: " . $e->getMessage();
       return false;
   }
}
function WorkdayIDVarmi($location_id, $workday_id){
  global $db;
  try {
       // Veritabanında belirtilen grup ID'sine sahip araçları sorgula
       $sorgu = $db->prepare('SELECT COUNT(*) FROM workday WHERE location_id = ? AND workday_id = ?');
       $sorgu->execute([$location_id,$workday_id]);
       $idsor = $sorgu->fetchColumn(); // Sorgunun sonucunu al

       // Veri varsa true, yoksa false döndür
       return ($idsor > 0);
   } catch (PDOException $e) {
       // Hata durumunda hata mesajını yazdır ve false döndür
       echo "Hata: " . $e->getMessage();
       return false;
   }
}

function VeriGetir($Sorgu, $ExecuteBilgisi, $FetchBilgisi){
  global $db;
  $sorgu = $db->prepare("SELECT * FROM $Sorgu");
  if ($ExecuteBilgisi !== null) {
    $sorgu->execute($ExecuteBilgisi);
  } else {
    $sorgu->execute();
  }
  $VeriSorgula = $sorgu->{$FetchBilgisi}(PDO::FETCH_ASSOC);
  return $VeriSorgula;
}

function filtrele($deger){
  $deger = htmlspecialchars($deger);
  $deger = strip_tags($deger);
	return $deger;
}
  //Panel Sayfalarını Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM panelsayfa WHERE SayfaKategori = 0 and MenudeGoster = 1 and SayfaIzin = 1');
  $sorgu->execute();
  $paneltopmenu = $sorgu->fetchAll(PDO:: FETCH_ASSOC);
  $sorgu = $db->prepare('SELECT * FROM panelsayfa WHERE SayfaKategori = 0 and MenudeGoster = 1 and SayfaIzin = 1');
  $sorgu->execute();
  $panelnavmenu = $sorgu->fetchAll(PDO:: FETCH_ASSOC);
  $sorgu = $db->prepare('SELECT * FROM panelsayfa WHERE SayfaKategori = 0 and SayfaIzin = 1');
  $sorgu->execute();
  $yetkisayfasi = $sorgu->fetchAll(PDO:: FETCH_ASSOC);
  ##Panel Sayfaları Çektirme Sonu.

  //Banka Bilgilerini Çektirdik Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM banka');
  $sorgu->execute();
  $banka = $sorgu->fetchAll(PDO:: FETCH_ASSOC);
  //Banka Bilgilerini Çektirdik Sonu.
  //Kargo Firmalarını Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM kargofirma');
  $sorgu->execute();
  $kargofirma = $sorgu->fetchAll(PDO:: FETCH_ASSOC);
  //Kargo Firmalarını Çektirdik Sonu.
  //İl İlçe Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM il_ilce');
  $sorgu->execute();
  $ililce = $sorgu->fetchAll(PDO:: FETCH_ASSOC);
  //İl İlçe Çektirdik Sonu.
  //İcerik Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM icerik GROUP BY IcerikID ORDER BY IcerikID ASC');
  $sorgu->execute();
  $icerik = $sorgu->fetchAll(PDO:: FETCH_ASSOC);
  ##İçerik Çektirme Sonu.
  //İcerik Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM icerik ORDER BY IcerikID DESC');
  $sorgu->execute();
   $icerikid = $sorgu->fetch(PDO:: FETCH_ASSOC);
  ##İçerikid Çektirme Sonu.
  //İcerik Türü Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM icerikturu WHERE IcerikIzin = 1 ORDER By id ASC');
  $sorgu->execute();
  $icerikturu = $sorgu->fetchAll(PDO:: FETCH_ASSOC);
  ##İçerik Türü Çektirme Sonu.

  //Galeri Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM galeri');
  $sorgu->execute();
  $galeri = $sorgu->fetchAll(PDO:: FETCH_ASSOC);
  ##Galeri Çektirme Sonu.

  //Ürünleri Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM urunler ORDER BY id DESC');
  $sorgu->execute();
  $urunler = $sorgu->fetchAll(PDO:: FETCH_ASSOC);
  ##Ürünleri Çektirme Sonu.

  //Rastgele Ürünleri Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM urunler ORDER BY RAND()');
  $sorgu->execute();
  $randurunler = $sorgu->fetchAll(PDO:: FETCH_ASSOC);
  ##Rastgele Ürünleri Çektirme Sonu.
  //Rastgele Ürünleri Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM urunler ORDER BY RAND() LIMIT 10');
  $sorgu->execute();
  $randurunlermax10 = $sorgu->fetchAll(PDO:: FETCH_ASSOC);
  ##Rastgele Ürünleri Çektirme Sonu.
  //Rastgele Ürünleri Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM urunler ORDER BY RAND() LIMIT 20');
  $sorgu->execute();
  $randurunlermax20 = $sorgu->fetchAll(PDO:: FETCH_ASSOC);
  ##Rastgele Ürünleri Çektirme Sonu.

  //Ürünlerin Kategorilerini Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM urunlerkategori');
  $sorgu->execute();
  $urunkategorileri = $sorgu->fetchAll(PDO:: FETCH_ASSOC);
  ##Ürünlerin Kategorilerini Çektik Bitti.

  //Bildirimleri Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM log WHERE okundu = 0 Order By id DESC');
  $sorgu->execute();
  $bildirim = $sorgu->fetchAll(PDO:: FETCH_ASSOC);
  $sorgu = $db->prepare('SELECT COUNT(*) FROM log WHERE okundu = 0 Order By id DESC');
  $sorgu->execute();
  $bildirimsay = $sorgu->fetchColumn();
  $sorgu = $db->prepare('SELECT * FROM log ORDER By id DESC LIMIT 50');
  $sorgu->execute();
  $bildirimhavuzu = $sorgu->fetchAll(PDO:: FETCH_ASSOC);
  ##Bildirimlerin Sonu.

  //Site Ayarlarını Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM siteayarlar WHERE id = ?');
  $sorgu->execute([1]);
  $siteayarlar = $sorgu->fetch(PDO:: FETCH_ASSOC);
  //Site İletişim Bilgilerini Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM iletisimbilgileri WHERE id = ?');
  $sorgu->execute([1]);
  $iletisimbilgileri = $sorgu->fetch(PDO:: FETCH_ASSOC);
  //Site Api Bilgilerini Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM apiayarlari WHERE id = ?');
  $sorgu->execute([1]);
  $apiayarlari = $sorgu->fetch(PDO:: FETCH_ASSOC);
?>
<?php //Fonksiyonlar
function UserInfo() {
    // Kullanıcı dil ayarlarını alır ve kısaltır
    $language_full = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    $language_short = substr($language_full, 0, 2); // İlk iki karakteri alır

    // Diğer bilgileri de alır
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $browser = $_SERVER['HTTP_USER_AGENT'];
    $request_time = date("Y-m-d H:i:s", $_SERVER['REQUEST_TIME']);
    $server_ip = $_SERVER['SERVER_ADDR'];
    $server_name = $_SERVER['SERVER_NAME'];
    $request_method = $_SERVER['REQUEST_METHOD'];

    // Bilgileri birleştirme
    $user_info = "IP Address: $ip_address<br>" .
                 "Browser: $browser<br>" .
                 "Language: $language_short<br>" . // Kısaltılmış dil kodunu kullanır
                 "Request Time: $request_time<br>" .
                 "Server IP: $server_ip<br>" .
                 "Server Name: $server_name<br>" .
                 "Request Method: $request_method";

    // Bilgileri döndürme
    return $user_info;
}
function isMobile() {
  return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
//Kullanıcı Şifrelerini Şifreleme
function sifrele($s) {
    $s = trim($s);
    $s=md5($s);
    $s=crc32($s);
    $s=sha1($s);
    $s=md5($s);
    $s=hash('sha256',$s);
    $s=md5($s);
    $s=hash('sha512',$s);
    $s=md5($s);
    $s=hash('sha256',$s);
    $s = mb_substr($s,10,32);
    $s=md5($s);
    $s=hash('sha512',$s);
    $s = mb_substr($s,0,32);
    return $s;
}


function SehirSor($Plaka){
  global $db;
  $sorgu = $db->prepare('SELECT * FROM il_ilce WHERE plaka = ? ORDER BY id ASC LIMIT 1');
  $sorgu->execute([$Plaka]);
  $SehirSor = $sorgu->fetch(PDO:: FETCH_ASSOC);

  return $SehirSor['il'];
}

function humantime($tarih){
  $fark = time()-$tarih;
  $saniye = $fark;
  $dakika = round($fark/60);
  $saat = round($fark/3600);
  $gun = round($fark/86400);
  $hafta = round($fark/604800);
  $ay = round($fark/2419200);
  $yil = round($fark/29030400);

 if ($saniye <= 59) {return $saniye." saniye önce";

 } elseif ($dakika <= 59){return $dakika." dakika önce";

 } elseif ($saat <= 23){return $saat." saat önce";

 } elseif ($gun <= 6){return $gun." gün önce";

 } elseif ($hafta <= 3){return $hafta." hafta önce";

 } elseif ($ay <= 11){return $ay." gün önce";

 } else {return $yıl." önce yazıldı";}

}
function token($a,$b){
	$token = $a.$b*2731;
	return $token;
}
function tokencoz($a, $b){
	//Gelen birinci değer yani a, tokeni gönderir.
	//Gelen ikinci değer yani b, tokenin başındaki harflerin sayısını gönderir.
	$tokencoz = substr($a, $b);
	$tokencoz = $tokencoz/2731;
	return $tokencoz;
	/* Örnek
		<?php echo "Düz ID :".$hizmetler['id']; ?><br>
		<?php echo $tokenli = token("T3KL1F", $hizmetler['id']); ?><br>
		<?php echo "Tokeni Çözülmüş ID :".tokencoz($tokenli,6); ?>
		*/
}
function sayfaAdi($sayfaAdi){ // Sayfa Adı Öğrenme
  global $db;
  //Site Ayarlarını Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM panelsayfa WHERE SayfaUrl = ?');
  $sorgu->execute([$sayfaAdi]);
  $sayfaadiogren = $sorgu->fetch(PDO:: FETCH_ASSOC);
  return $sayfaadiogren['SayfaAdi'];
}
function UrunKategori($KategoriID){ // Sayfa Adı Öğrenme
  global $db;
  //Site Ayarlarını Çektirdik.
  $sorgu = $db->prepare('SELECT * FROM urunlerkategori WHERE id = ?');
  $sorgu->execute([$KategoriID]);
  $urunkategori = $sorgu->fetch(PDO:: FETCH_ASSOC);
  return $urunkategori['KategoriAdi'];
}
// SEO LİNK
function seo($s) {
	$tr = array('ş','Ş','ı','I','İ','ğ','Ğ','ü','Ü','ö','Ö','Ç','ç','(',')','/',' ',',','?');
	$eng = array('s','s','i','i','i','g','g','u','u','o','o','c','c','','','-','-','','');
	$s = str_replace($tr,$eng,$s);
	$s = strtolower($s);
	$s = preg_replace('/&amp;amp;amp;amp;amp;amp;amp;amp;amp;.+?;/', '', $s);
	$s = preg_replace('/\s+/', '-', $s);
	$s = preg_replace('|-+|', '-', $s);
	$s = preg_replace('/#/', '', $s);
	$s = preg_replace('/!/', '', $s);
	$s = preg_replace('/\+/', '-', $s);
	$s = preg_replace('/’/', '', $s);
	// KARAKTER SİLME KODU
	$s = str_replace(',', '', $s);
	$s = str_replace('?', '', $s);
	$s = str_replace('&', '', $s);
	$s = str_replace('%', '', $s);
	$s = str_replace('½', '', $s);
	$s = str_replace('^', '', $s);
	$s = str_replace('£', '', $s);
	$s = str_replace('>', '', $s);
	$s = str_replace('æ', '', $s);
	$s = str_replace('ß', '', $s);
	$s = str_replace('~', '', $s);
	$s = str_replace('é', '', $s);
	$s = str_replace("'", '', $s);
	$s = str_replace('.', '', $s);
	$s = str_replace('|', '', $s);
	$s = str_replace('*', '', $s);
	// KARAKTER SİLME KODU
	$s = trim($s, '-');
	return $s;
}

include 'class.phpmailer.php';
include 'class.smtp.php';

function SiteEpostaGonder($GonderilenMail, $Baslik, $Mesaj){
  global $siteayarlar;
  global $SiteBaslik;
  global $SiteSlogan;
  global $SiteAdres;
  global $SiteSabitTel;
  global $SiteEposta;
  global $domainhttps;
  global $SiteLogo;
  global $EpostaSunucu;
  global $EpostaAdres;
  global $EpostaSifre;
  global $EpostaGonderenAdi;
//email gönderimi için gerekli olan dosyaları dahil ediyoruz.

$mail = new PHPMailer(); //ilgili PHPMailer class"ımızdan bir nesne türetiyoruz.
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->Host = $EpostaSunucu; //SMTP için kullanılacak sunucu adresi
$mail->Port = 587; //TLS protokolünün kullanacağı port numarası
$mail->SMTPSecure = "tls"; //kullanacağımız güvenlik protokolü SSL veya TLS olabilir.
$mail->Username = $EpostaAdres; //Email gönderecek adres
$mail->Password = $EpostaSifre; ////Email gönderecek adresin şifresi
$mail->SetFrom($EpostaGonderenAdi, "Bigsoft");
$mail->AddAddress($GonderilenMail); //Bu emaili gideceği e-posta adresi
$mail->CharSet = "UTF-8"; //Karakterlerin düzgün görünmesi için utf-8 ekliyoruz.
$mail->Subject = "$Baslik"; //emailimizin konusu
$mail->MsgHTML("<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
  <meta name='viewport' content='width=device-width, initial-scale=1' />
  <title>".$SiteBaslik."</title>

  <style type='text/css'>
    /* Take care of image borders and formatting, client hacks */
    img { max-width: 600px; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;}
    a img { border: none; }
    table { border-collapse: collapse !important;}
    #outlook a { padding:0; }
    .ReadMsgBody { width: 100%; }
    .ExternalClass { width: 100%; }
    .backgroundTable { margin: 0 auto; padding: 0; width: 100% !important; }
    table td { border-collapse: collapse; }
    .ExternalClass * { line-height: 115%; }
    .container-for-gmail-android { min-width: 600px; }


    /* General styling */
    * {
      font-family: Helvetica, Arial, sans-serif;
    }

    body {
      -webkit-font-smoothing: antialiased;
      -webkit-text-size-adjust: none;
      width: 100% !important;
      margin: 0 !important;
      height: 100%;
      color: #676767;
    }

    td {
      font-family: Helvetica, Arial, sans-serif;
      font-size: 14px;
      color: #777777;
      text-align: center;
      line-height: 21px;
    }

    a {
      color: #ff6f6f;
      font-weight: bold;
      text-decoration: none !important;
    }

    .pull-left {
      text-align: left;
    }

    .pull-right {
      text-align: right;
    }

    .header-lg,
    .header-md,
    .header-sm {
      font-size: 32px;
      font-weight: 700;
      line-height: normal;
      padding: 35px 0 0;
      color: #4d4d4d;
    }

    .header-md {
      font-size: 24px;
    }

    .header-sm {
      padding: 5px 0;
      font-size: 18px;
      line-height: 1.3;
    }

    .content-padding {
      padding: 20px 0 30px;
    }

    .mobile-header-padding-right {
      width: 290px;
      text-align: right;
      padding-left: 10px;
    }

    .mobile-header-padding-left {
      width: 290px;
      text-align: left;
      padding-left: 10px;
    }

    .free-text {
      width: 100% !important;
      padding: 10px 60px 0px;
    }

    .block-rounded {
      border-radius: 5px;
      border: 1px solid #e5e5e5;
      vertical-align: top;
    }

    .button {
      padding: 30px 0 0;
    }

    .info-block {
      padding: 0 20px;
      width: 260px;
    }

    .mini-block-container {
      padding: 30px 50px;
      width: 500px;
    }

    .mini-block {
      background-color: #ffffff;
      width: 498px;
      border: 1px solid #cccccc;
      border-radius: 5px;
      padding: 45px 75px;
    }

    .block-rounded {
      width: 260px;
    }

    .info-img {
      width: 258px;
      border-radius: 5px 5px 0 0;
    }

    .force-width-img {
      width: 480px;
      height: 1px !important;
    }

    .force-width-full {
      width: 600px;
      height: 1px !important;
    }

    .user-img img {
      width: 130px;
      border-radius: 5px;
      border: 1px solid #cccccc;
    }

    .user-img {
      text-align: center;
      border-radius: 100px;
      color: #ff6f6f;
      font-weight: 700;
    }

    .user-msg {
      padding-top: 10px;
      font-size: 14px;
      text-align: center;
      font-style: italic;
    }

    .mini-img {
      padding: 5px;
      width: 140px;
    }

    .mini-img img {
      border-radius: 5px;
      width: 140px;
    }

    .force-width-gmail {
      min-width:600px;
      height: 0px !important;
      line-height: 1px !important;
      font-size: 1px !important;
    }

    .mini-imgs {
      padding: 25px 0 30px;
    }
  </style>

  <style type='text/css' media='screen'>
    @import url(http://fonts.googleapis.com/css?family=Oxygen:400,700);
  </style>

  <style type='text/css' media='screen'>
    @media screen {
      /* Thanks Outlook 2013! */
      * {
        font-family: 'Oxygen', 'Helvetica Neue', 'Arial', 'sans-serif' !important;
      }
    }
  </style>

  <style type='text/css' media='only screen and (max-width: 480px)'>
    /* Mobile styles */
    @media only screen and (max-width: 480px) {

      table[class*='container-for-gmail-android'] {
        min-width: 290px !important;
        width: 100% !important;
      }

      table[class='w320'] {
        width: 320px !important;
      }

      img[class='force-width-gmail'] {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
      }

      td[class*='mobile-header-padding-left'] {
        width: 160px !important;
        padding-left: 0 !important;
      }

      td[class*='mobile-header-padding-right'] {
        width: 160px !important;
        padding-right: 0 !important;
      }

      td[class='mobile-block'] {
        display: block !important;
      }

      td[class='mini-img'],
      td[class='mini-img'] img{
        width: 150px !important;
      }

      td[class='header-lg'] {
        font-size: 24px !important;
        padding-bottom: 5px !important;
      }

      td[class='header-md'] {
        font-size: 18px !important;
        padding-bottom: 5px !important;
      }

      td[class='content-padding'] {
        padding: 5px 0 30px !important;
      }

      td[class='button'] {
        padding: 5px !important;
      }

      td[class*='free-text'] {
        padding: 10px 18px 30px !important;
      }

      img[class='force-width-img'],
      img[class='force-width-full'] {
        display: none !important;
      }

      td[class='info-block'] {
        display: block !important;
        width: 280px !important;
        padding-bottom: 40px !important;
      }

      td[class='info-img'],
      img[class='info-img'] {
        width: 278px !important;
      }

      td[class='mini-block-container'] {
        padding: 8px 20px !important;
        width: 280px !important;
      }

      td[class='mini-block'] {
        padding: 20px !important;
      }

      td[class='user-img'] {
        display: block !important;
        text-align: center !important;
        width: 100% !important;
        padding-bottom: 10px;
      }

      td[class='user-msg'] {
        display: block !important;
        padding-bottom: 20px;
      }
    }
  </style>
</head>

<body bgcolor='#f7f7f7'>
<table align='center' cellpadding='0' cellspacing='0' class='container-for-gmail-android' width='100%'>
  <tr>
    <td align='left' valign='top' width='100%' style='background:repeat-x url() #ffffff;'>
      <center>
      <img src='http://s3.amazonaws.com/swu-filepicker/SBb2fQPrQ5ezxmqUTgCr_transparent.png' class='force-width-gmail'>
        <table cellspacing='0' cellpadding='0' width='100%' bgcolor='#ffffff' background='http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg' style='background-color:transparent'>
          <tr>
            <td width='100%' height='80' valign='top' style='text-align: center; vertical-align:middle;'>
            <!--[if gte mso 9]>
            <v:rect xmlns:v='urn:schemas-microsoft-com:vml' fill='true' stroke='false' style='mso-width-percent:1000;height:80px; v-text-anchor:middle;'>
              <v:fill type='tile' src='http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg' color='#ffffff' />
              <v:textbox inset='0,0,0,0'>
            <![endif]-->
              <center>
                <table cellpadding='0' cellspacing='0' width='600' class='w320'>
                  <tr>
                    <td class='' style='align: center;'>
                      <a href=''><img width='182' height='33' src='".$domainhttps.$SiteLogo."'></a>
                    </td>
                  </tr>
                </table>
              </center>
              <!--[if gte mso 9]>
              </v:textbox>
            </v:rect>
            <![endif]-->
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align='center' valign='top' width='100%' style='background-color: #f7f7f7;' class='content-padding'>
      <center>
        <table cellspacing='0' cellpadding='0' width='600' class='w320'>
          <tr>
            <td class='header-lg'>".$Baslik."
            </td>
          </tr>
          <tr>
            <td class='mini-block-container'>
              <table cellspacing='0' cellpadding='0' width='100%'  style='border-collapse:separate !important;'>
                <tr>
                  <td class='mini-block'>
                    <table cellpadding='0' cellspacing='0' width='100%'>
                      <tr>
                        <td>
                          <table cellspacing='0' cellpadding='0' width='100%'>
                            <tr>
                              <td class='user-msg'>".$Mesaj."</td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align='center' valign='top' width='100%' style='background-color: #f7f7f7; height: 100px;'>
      <center>
        <table cellspacing='0' cellpadding='0' width='600' class='w320'>
          <tr>
            <td style='padding: 25px 0 25px'>
              <strong>".$SiteBaslik." | ".$SiteSlogan."</strong><br />
              ".$SiteAdres." <br />
              ".$SiteSabitTel." - ".$SiteEposta."<br />
              <br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>
</body>
</html>");
  //Artık emailimizi gönderiyoruz, yukarıdaki bilgilerde bir hata varsa bu satırda hata verecektir.
  if($mail->Send()) {
       //E-posta gönderildi
       return "OK";
  } else {
      // Bir hata oluştu, hata mesajı yazdırıyoruz
      return $mail->ErrorInfo;
  }
}





function YeniSifreGonder($GonderilenMail, $Sifre){
  global $siteayarlar;
  global $SiteBaslik;
  global $SiteSlogan;
  global $SiteAdres;
  global $SiteSabitTel;
  global $SiteEposta;
  global $domainhttps;
  global $SiteLogo;
  global $EpostaSunucu;
  global $EpostaAdres;
  global $EpostaSifre;
  global $EpostaGonderenAdi;
//email gönderimi için gerekli olan dosyaları dahil ediyoruz.

$mail = new PHPMailer(); //ilgili PHPMailer class"ımızdan bir nesne türetiyoruz.
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->Host = $EpostaSunucu; //SMTP için kullanılacak sunucu adresi
$mail->Port = 587; //TLS protokolünün kullanacağı port numarası
$mail->SMTPSecure = "tls"; //kullanacağımız güvenlik protokolü SSL veya TLS olabilir.
$mail->Username = $EpostaAdres; //Email gönderecek adres
$mail->Password = $EpostaSifre; ////Email gönderecek adresin şifresi
$mail->SetFrom($EpostaGonderenAdi, "Dask Sorgula");
$mail->AddAddress($GonderilenMail); //Bu emaili gideceği e-posta adresi
$mail->CharSet = "UTF-8"; //Karakterlerin düzgün görünmesi için utf-8 ekliyoruz.
$mail->Subject = "Giriş Bilgileri"; //emailimizin konusu
$mail->MsgHTML("<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
  <meta name='viewport' content='width=device-width, initial-scale=1' />
  <title>".$SiteBaslik."</title>

  <style type='text/css'>
    /* Take care of image borders and formatting, client hacks */
    img { max-width: 600px; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;}
    a img { border: none; }
    table { border-collapse: collapse !important;}
    #outlook a { padding:0; }
    .ReadMsgBody { width: 100%; }
    .ExternalClass { width: 100%; }
    .backgroundTable { margin: 0 auto; padding: 0; width: 100% !important; }
    table td { border-collapse: collapse; }
    .ExternalClass * { line-height: 115%; }
    .container-for-gmail-android { min-width: 600px; }


    /* General styling */
    * {
      font-family: Helvetica, Arial, sans-serif;
    }

    body {
      -webkit-font-smoothing: antialiased;
      -webkit-text-size-adjust: none;
      width: 100% !important;
      margin: 0 !important;
      height: 100%;
      color: #676767;
    }

    td {
      font-family: Helvetica, Arial, sans-serif;
      font-size: 14px;
      color: #777777;
      text-align: center;
      line-height: 21px;
    }

    a {
      color: #ff6f6f;
      font-weight: bold;
      text-decoration: none !important;
    }

    .pull-left {
      text-align: left;
    }

    .pull-right {
      text-align: right;
    }

    .header-lg,
    .header-md,
    .header-sm {
      font-size: 32px;
      font-weight: 700;
      line-height: normal;
      padding: 35px 0 0;
      color: #4d4d4d;
    }

    .header-md {
      font-size: 24px;
    }

    .header-sm {
      padding: 5px 0;
      font-size: 18px;
      line-height: 1.3;
    }

    .content-padding {
      padding: 20px 0 30px;
    }

    .mobile-header-padding-right {
      width: 290px;
      text-align: right;
      padding-left: 10px;
    }

    .mobile-header-padding-left {
      width: 290px;
      text-align: left;
      padding-left: 10px;
    }

    .free-text {
      width: 100% !important;
      padding: 10px 60px 0px;
    }

    .block-rounded {
      border-radius: 5px;
      border: 1px solid #e5e5e5;
      vertical-align: top;
    }

    .button {
      padding: 30px 0 0;
    }

    .info-block {
      padding: 0 20px;
      width: 260px;
    }

    .mini-block-container {
      padding: 30px 50px;
      width: 500px;
    }

    .mini-block {
      background-color: #ffffff;
      width: 498px;
      border: 1px solid #cccccc;
      border-radius: 5px;
      padding: 45px 75px;
    }

    .block-rounded {
      width: 260px;
    }

    .info-img {
      width: 258px;
      border-radius: 5px 5px 0 0;
    }

    .force-width-img {
      width: 480px;
      height: 1px !important;
    }

    .force-width-full {
      width: 600px;
      height: 1px !important;
    }

    .user-img img {
      width: 130px;
      border-radius: 5px;
      border: 1px solid #cccccc;
    }

    .user-img {
      text-align: center;
      border-radius: 100px;
      color: #ff6f6f;
      font-weight: 700;
    }

    .user-msg {
      padding-top: 10px;
      font-size: 14px;
      text-align: center;
      font-style: italic;
    }

    .mini-img {
      padding: 5px;
      width: 140px;
    }

    .mini-img img {
      border-radius: 5px;
      width: 140px;
    }

    .force-width-gmail {
      min-width:600px;
      height: 0px !important;
      line-height: 1px !important;
      font-size: 1px !important;
    }

    .mini-imgs {
      padding: 25px 0 30px;
    }
  </style>

  <style type='text/css' media='screen'>
    @import url(http://fonts.googleapis.com/css?family=Oxygen:400,700);
  </style>

  <style type='text/css' media='screen'>
    @media screen {
      /* Thanks Outlook 2013! */
      * {
        font-family: 'Oxygen', 'Helvetica Neue', 'Arial', 'sans-serif' !important;
      }
    }
  </style>

  <style type='text/css' media='only screen and (max-width: 480px)'>
    /* Mobile styles */
    @media only screen and (max-width: 480px) {

      table[class*='container-for-gmail-android'] {
        min-width: 290px !important;
        width: 100% !important;
      }

      table[class='w320'] {
        width: 320px !important;
      }

      img[class='force-width-gmail'] {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
      }

      td[class*='mobile-header-padding-left'] {
        width: 160px !important;
        padding-left: 0 !important;
      }

      td[class*='mobile-header-padding-right'] {
        width: 160px !important;
        padding-right: 0 !important;
      }

      td[class='mobile-block'] {
        display: block !important;
      }

      td[class='mini-img'],
      td[class='mini-img'] img{
        width: 150px !important;
      }

      td[class='header-lg'] {
        font-size: 24px !important;
        padding-bottom: 5px !important;
      }

      td[class='header-md'] {
        font-size: 18px !important;
        padding-bottom: 5px !important;
      }

      td[class='content-padding'] {
        padding: 5px 0 30px !important;
      }

      td[class='button'] {
        padding: 5px !important;
      }

      td[class*='free-text'] {
        padding: 10px 18px 30px !important;
      }

      img[class='force-width-img'],
      img[class='force-width-full'] {
        display: none !important;
      }

      td[class='info-block'] {
        display: block !important;
        width: 280px !important;
        padding-bottom: 40px !important;
      }

      td[class='info-img'],
      img[class='info-img'] {
        width: 278px !important;
      }

      td[class='mini-block-container'] {
        padding: 8px 20px !important;
        width: 280px !important;
      }

      td[class='mini-block'] {
        padding: 20px !important;
      }

      td[class='user-img'] {
        display: block !important;
        text-align: center !important;
        width: 100% !important;
        padding-bottom: 10px;
      }

      td[class='user-msg'] {
        display: block !important;
        padding-bottom: 20px;
      }
    }
  </style>
</head>

<body bgcolor='#f7f7f7'>
<table align='center' cellpadding='0' cellspacing='0' class='container-for-gmail-android' width='100%'>
  <tr>
    <td align='left' valign='top' width='100%' style='background:repeat-x url() #ffffff;'>
      <center>
      <img src='http://s3.amazonaws.com/swu-filepicker/SBb2fQPrQ5ezxmqUTgCr_transparent.png' class='force-width-gmail'>
        <table cellspacing='0' cellpadding='0' width='100%' bgcolor='#ffffff' background='http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg' style='background-color:transparent'>
          <tr>
            <td width='100%' height='80' valign='top' style='text-align: center; vertical-align:middle;'>
            <!--[if gte mso 9]>
            <v:rect xmlns:v='urn:schemas-microsoft-com:vml' fill='true' stroke='false' style='mso-width-percent:1000;height:80px; v-text-anchor:middle;'>
              <v:fill type='tile' src='http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg' color='#ffffff' />
              <v:textbox inset='0,0,0,0'>
            <![endif]-->
              <center>
                <table cellpadding='0' cellspacing='0' width='600' class='w320'>
                  <tr>
                    <td class='' style='align: center;'>
                      <a href=''><img width='182' height='33' src='".$domainhttps.$SiteLogo."'></a>
                    </td>
                  </tr>
                </table>
              </center>
              <!--[if gte mso 9]>
              </v:textbox>
            </v:rect>
            <![endif]-->
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align='center' valign='top' width='100%' style='background-color: #f7f7f7;' class='content-padding'>
      <center>
        <table cellspacing='0' cellpadding='0' width='600' class='w320'>
          <tr>
            <td class='header-lg'>Giriş Bİlgileriniz
            </td>
          </tr>
          <tr>
            <td class='mini-block-container'>
              <table cellspacing='0' cellpadding='0' width='100%'  style='border-collapse:separate !important;'>
                <tr>
                  <td class='mini-block'>
                    <table cellpadding='0' cellspacing='0' width='100%'>
                      <tr>
                        <td>
                          <table cellspacing='0' cellpadding='0' width='100%'>
                            <tr>
                              <td class='user-msg'>E-Posta Adresi: ".$GonderilenMail."</td><br>
                              <td class='user-msg'>Şifre: ". $Sifre."</td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align='center' valign='top' width='100%' style='background-color: #f7f7f7; height: 100px;'>
      <center>
        <table cellspacing='0' cellpadding='0' width='600' class='w320'>
          <tr>
            <td style='padding: 25px 0 25px'>
              <strong>".$SiteBaslik." | ".$SiteSlogan."</strong><br />
              ".$SiteAdres." <br />
              ".$SiteSabitTel." - ".$SiteEposta."<br />
              <br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>
</body>
</html>");
  //Artık emailimizi gönderiyoruz, yukarıdaki bilgilerde bir hata varsa bu satırda hata verecektir.
  if($mail->Send()) {
       //E-posta gönderildi
       return "OK";
  } else {
      // Bir hata oluştu, hata mesajı yazdırıyoruz
      return $mail->ErrorInfo;
  }
}
?>
