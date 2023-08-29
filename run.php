<?php

include 'curl.php';


echo "Your referral code : ";
$reffcode = trim(fgets(STDIN));




function register ($reffcode) {
	$page = curl('https://gleancoin.com/referral/'.$reffcode);
	$cookies = getcookies($page);

	$create_email = curl('https://internxt.com/api/temp-mail/create-email', null, null, false);

	$email = fetch_value($create_email, '["','"]');



	$headers = array(
		"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/116.0",
		"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8",
		"Accept-Language: en-US,en;q=0.5",
		"Accept-Encoding: gzip, deflate, br",
		"Content-Type: application/x-www-form-urlencoded",
		"Origin: https://gleancoin.com",
		"Alt-Used: gleancoin.com",
		"Connection: keep-alive",
		"Referer: https://gleancoin.com/registration",
		"Cookie: PHPSESSID=".$cookies['PHPSESSID']."; _ga_VN4TGXFCKZ=GS1.1.1693309301.1.1.1693312729.0.0.0; _ga=GA1.1.1703107560.1693309301; lhc_per=vid^|49d5b0b7ab0fc27c1fc4",
		"Upgrade-Insecure-Requests: 1",
		"Sec-Fetch-Dest: document",
		"Sec-Fetch-Mode: navigate",
		"Sec-Fetch-Site: same-origin",
		"Sec-Fetch-User: ?1",
		"TE: trailers"
	);



	$data = array(
		"registrationForm[email]" => $email,
		"registrationForm[password][first]" => "@Misaka123",
		"registrationForm[password][second]" => "@Misaka123"
	);

	echo "[*] Try to register\n";


	$register = curl('https://gleancoin.com/registration', http_build_query($data), $headers, false);

	if (stripos($register, 'Redirecting to /login')) {
		echo "[*] Get verification email\n";
		sleep(8);
		$get_email = curl('https://internxt.com/api/temp-mail/get-inbox?email='.$email, null, null, false);
		$id = fetch_value($get_email, '"id":',',"from"');


		$read = curl('https://internxt.com/api/temp-mail/email-data?email='.$email.'&item='.$id, null, null, false);
		$json = json_decode($read, true);
		$verif = fetch_value($json['body'], '<a href="','">Click Here To Verify Email</a></p>');

		if ($verif != "") {
			$verif_link = curl($verif);

			$final = fetch_value($verif_link, '<a href="','">Found</a>');
			echo "[*] ".$final."\n";

			$fix = curl($final);

			if (stripos($fix, 'Redirecting to /login')) {
				echo "[*] Success to verification account\n";

			} else {
				echo "[*] Failed to verification account\n";
			}

		}
	} else {
		echo "[*] Failed to register\n";
	}


} 



