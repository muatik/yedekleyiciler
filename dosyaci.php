<?php

$rpath='/home/www/';
$backPath='/root/yedekleyiciler/dosya/';
$log='';
$dirs=array(
	'projedizini1'=>'*',
	'projedizini2'=>'*'
);


if(!isset($_SERVER['argv'][1])) die("parametreler eksik\n");
$duration=$_SERVER['argv'][1];
$backPath.=$_SERVER['argv'][1].'/';

function cmm($c){
	global $log;
	$log.=$c."\n";
	exec($c);
}


switch($duration){
	case 1:
		$log.="GÜNLÜK YEDEKLEYİCİ\n\n";
		break;
	case 3:
		$log.="3 GÜNDE BİR YEDEKLEYİCİ\n\n";
		break;
	case 10:
		$log.="10 GÜNDE BİR YEDEKLEYİCİ\n\n";
		break;
	case 30:
		$log.="AYLIK  YEDEKLEYİCİ\n\n";
		break;
}


$mtime=date('Ymd');
$fails=array();
foreach($dirs as $d=>$sd){
// yedeklenecek dizin ve dosyalar listesi hazırlanıyor
        $dest='';
        if($sd=='*')
                $dest.=$rpath.$d;
        else
	foreach($sd as $i)
                $dest.=$rpath.$d.'/'.$i.' ';
	
	cmm('tar -cjf '.$backPath.$d.$mtime.'.tar.bz2 '.$dest);
	if(!file_exists($backPath.$d.$mtime.'.tar.bz2'))
		$fails[]=$d;

}


$log.="\n\n### ESKİ DOSYALAR SİLİNİYOR ###\n";
if($bdir=opendir($backPath)){
	while(($f=readdir($bdir))!==false){
		if(is_dir($f)) continue;

		$mtime=filemtime($backPath.$f);

		if(time()-(3600*24*$duration)>$mtime){
			cmm('rm -Rf '.$backPath.$f);
		}

	}
}


$log=
"Toplam ".(count($dirs)-count($fails))." parça yedeklendi\n"
.count($fails)." başarısız işlem oluştu: ".implode(', ',$fails)."\n\n"
.$log;

mail('mustafa.atik@botego.com',
	'Botego.org dosyaları yedeklendi',
	$log,
	'content-type:text/plain;charset:utf-8'
);
echo $log;

?>

