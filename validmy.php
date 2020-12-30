<?php
 
$__uid = 'ab2d-e'.mt_rand(10, 99).'1-i'.mt_rand(10, 99).'k-a' . mt_rand(100, 999);
 
$__setting = [
    ['my', '-', 'Malaysia', 'https://hd.c.mi.com/my/eventapi/api/aptcha/index?type=netflix&uid='.$__uid],
];
 
function http_request($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
 
$__stopped=false;$ii=0;
echo 'Mi 10T x Netflix'. PHP_EOL;
echo 'Support: ONLY MY'. PHP_EOL;
echo 'Buka link dibawah, terus input captchanya yak!' . PHP_EOL;
foreach ($__setting as $sett) {
    echo '[' . $sett[2] . '] Challenge the captcha => ' . $sett[3] . PHP_EOL;
    echo 'Input captcha : ';
    $input = fopen("php://stdin","r");
    $answer = trim(fgets($input));
    $__setting[$ii][1] = $answer;
    $ii++;
}
echo '[Silahkan input IMEI sob...]' . PHP_EOL;
 
while(1) {
    $input_imei = fopen("php://stdin","r");
	$imei = trim(fgets($input_imei));
    if($__stopped) {
        break;
    }
    foreach ($__setting as $sett) {
        $data = http_request('https://hd.c.mi.com/'.$sett[0].'/eventapi/api/netflix/gettoken?uid='.$__uid.'&vcode='.$sett[1].'&imei='.$imei);
        $data = json_decode($data, true);
        if (isset($data['msg']) && $data['msg'] == 'Success') {
            $__if_valid = $data['data']['redirect_url']. PHP_EOL;
            echo 'VALID => ' . $__if_valid;
	echo '[Silahkan input IMEI lagi sob...]' . PHP_EOL;
        } else if(isset($data['code']) && $data['code'] == '800706') {
            echo 'Please challenge the captcha => ' . $sett[2] . PHP_EOL;
            $__stopped = true;
        } else {
            echo 'INVALID => ' . $imei . '|' . $sett[2] . PHP_EOL;
		$__stopped = true;

        }
    }
}