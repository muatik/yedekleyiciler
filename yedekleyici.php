<?php

$dbSuffix='';
$dbs=array('projedizini1','projedizini2');
$backupPath='/root/yedekleyiciler/vt/';
$log='';
$dbFail=array();
$dbUser='';
$dbPsw='';

$duration=1;
if(isset($_SERVER['argv'][1])){
	$duration=$_SERVER['argv'][1];
	$backupPath.=$_SERVER['argv'][1].'/';
	switch($_SERVER['argv'][1]){
		case 1:$log.="GÜNLÜK YEDEKLEYİCİ\N\N";
			break;
		case 3:$log.="3 GÜNDE BİR YEDEKLEYİCİ\N\N";
                       	break;
		case 10:$log.="10 GÜNDE BİR YEDEKLEYİCİ\N\N";
                       	break;
		case 30:$log.="AYLIK  YEDEKLEYİCİ\N\N";
                       	break;
	}
}

function cmm($cmm,$loged=true){
	global $log;
	
	exec($cmm);
	
	if($loged)
		$log.=$cmm."\n";
}

$log.="\n\n### YEDEKLENİYOR ###";
$dsig=date('dmYHi');
foreach($dbs as $db){
	
	$log.="\n=== ".$db." ===\n";
	
	$fname=$db.$dsig;
	$filePath=$backupPath.$fname.'.sql';
	
	cmm('mysqldump -u'.$dbUser.' -p'.$dbPsw.' '.$dbSuffix.$db.'>'.$filePath);
	cmm('tar -cjf '.$backupPath.$fname.'.tar.bz2 '.$filePath);
	cmm('rm -Rf '.$filePath);
	
	if(!file_exists($backupPath.$fname.'.tar.bz2')){
		$dbFail[]=$db;
	}
}

$log.="\n\n### ESKİ DOSYALAR SİLİNİYOR ###";
if($bdir=opendir($backupPath)){
	while(($f=readdir($bdir))!==false){
		if(is_dir($f)) continue;
		
		$mtime=filemtime($backupPath.$f);
		
		if(time()-(3600*24*$duration)>$mtime){
			cmm('rm -Rf '.$backupPath.$f);
		}
		
	}
}


$log=
"Toplam ".(count($dbs)-count($dbFail))." adet veritabanı yedeklendi. \n"
.count($dbFail)." adet veritabanı yedeklenemedi: ".implode(', ',$dbFail)." \n\n"
.$log;

echo $log;


mail('mustafa.atik@botego.com','Botego.org Yedekleyic Zabıtı',$log,
	"content-type:text/plain;charset=utf-8;")

?>
