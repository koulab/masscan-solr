<?php
if (PHP_SAPI !== 'cli') {
    echo 'This application only works in cli';
    exit;
}
set_time_limit(0);
ini_set('memory_limit','50M');

if(empty($argv[1]) || empty($argv[2])){
    echo 'InputJsonFile:';
    $argv[1] = trim(fgets(STDIN,4096));
    echo 'OutputJsonFile:';
    $argv[2] = trim(fgets(STDIN,4096));
}
if(empty($argv[1]) || empty($argv[2])){
    echo 'Please enter a valid filepath'.PHP_EOL;
    exit(-1);
}
if(!file_exists($argv[1])){
    var_dump($argv);
    echo 'Input file could not be found.';
    exit(-1);
}
if(file_exists($argv[2])){
    do{
        echo 'OuputJsonFile already exists, Would you like to create as new-file? yes or no:';
        $ans = trim(fgets(STDIN,4096));
        if($ans === 'yes'){
            unlink($argv[2]);
        }
    }while(!($ans !== 'yes' xor $ans !== 'no'));
}
if($ans === 'no'){ echo 'No changed.'.PHP_EOL; exit(-1); }
$r = fopen($argv[1],'r');
$w = fopen($argv[2],'a+');

fwrite($w,'[');
while ($line = fgets($r)) {
    $tmp = json_decode('['.rtrim(trim($line),',').']',false);
    if($tmp !== FALSE && $tmp !== NULL){
        $newJson = [
            'ip'=>@$tmp[0]->ip,
            'timestamp'=>$tmp[0]->timestamp,
            'port'=>@$tmp[0]->ports[0]->port,
            'proto'=>@$tmp[0]->ports[0]->proto,
            'status'=>@$tmp[0]->ports[0]->status,
            'reason'=>@$tmp[0]->ports[0]->reason,
            'ttl'=>@$tmp[0]->ports[0]->ttl,
            'service'=>@$tmp[0]->ports[0]->service->name,
            'banner'=>@$tmp[0]->ports[0]->service->banner,
            ];
        foreach($newJson as $k=>$v){
            if(!isset($v)){
                unset($newJson[$k]);
            }
        }

        fwrite($w,json_encode($newJson).','.PHP_EOL);
    }
}
fwrite($w,']');

fclose($r);
fclose($w);
