<?

include_once('./lib/Zxing/QrReader.php');

$dir = scandir('qrcodes');

foreach($dir as $file) {
    if($file=='.'||$file=='..') continue;

    print $file;
    print ' --- ';
    $qrcode = new QrReader('qrcodes/'.$file);
    print $text = $qrcode->text();
    print "<br/>";
}