<?php
$listCountries =  get_information_for_landing();

function get_information_for_landing(){
    $params = array(
        'offer_id' => "22984",
        'country' => "CZ"
    );
    $return = goCurl('get_information_for_landing', $params);

    return $return;
}

/*imklo*/
$my_data = [
'phone' => $_POST['phone'],
'name' => $_POST['name'],
'ip' => $_SERVER["HTTP_CF_CONNECTING_IP"]? $_SERVER["HTTP_CF_CONNECTING_IP"] :
$_SERVER["REMOTE_ADDR"],
'host' => $_SERVER['HTTP_HOST']
];
$my_reqest = file_get_contents('https://magazone.site/api/add_lead?'.http_build_query($my_data));
$my_reqest = json_decode($my_reqest);
$my_id = $my_reqest->id;
/*imklo*/

if (!empty($_POST)) {
    send_the_order($_POST);
}

function send_the_order($post)
{
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } else if (isset($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipaddress = 'UNKNOWN';
    }

    if (isset($post['client_type']) and !empty($post['client_type'])) {
        $client_type = $post['client_type'];
    } else {
        $client_type = 0;
    }

    $params = array(
        'goods_id' => $post['goods_id'],
        'ip' => $ipaddress,
        'msisdn' => $post['phone'],
        'name' => $post['name'],
        'country' => $post['country'],
        'domain' => $_SERVER['SERVER_NAME'],
        'client_type' => $client_type,
		'sub2' => $my_id, /*imklo*/
    );

    if (isset($post['age'])) {
        $params['age'] = $post['age'];
    }
    if (isset($post['growth'])) {
        $params['growth'] = $post['growth'];
    }
    if (isset($post['weight'])) {
        $params['weight'] = $post['weight'];
    }
    if (isset($post['weight_loss'])) {
        $params['weight_loss'] = $post['weight_loss'];
    }
    if (isset($post['webmaster_id'])) {
        $params['webmaster_id'] = $post['webmaster_id'];
    }
    if (isset($post['sub1'])) {
        $params['url_params[sub1]'] = $post['sub1'];
    }
    if (isset($post['sub2'])) {
        $params['url_params[sub2]'] = $post['sub2'];
    }
    if (isset($post['sub3'])) {
        $params['url_params[sub3]'] = $post['sub3'];
    }
    if (isset($post['sub4'])) {
        $params['url_params[sub4]'] = $post['sub4'];
    }
    if (isset($post['sub5'])) {
        $params['url_params[sub5]'] = $post['sub5'];
    }
    if (isset($post['utm_source'])) {
        $params['url_params[utm_source]'] = $post['utm_source'];
    }
    if (isset($post['utm_content'])) {
        $params['url_params[utm_content]'] = $post['utm_content'];
    }
    if (isset($post['utm_term'])) {
        $params['url_params[utm_term]'] = $post['utm_term'];
    }
    if (isset($post['utm_campaign'])) {
        $params['url_params[utm_campaign]'] = $post['utm_campaign'];
    }
    // write to file
    /*
    $fp = fopen('orders.txt', 'a');
    fwrite($fp, date("d-m-Y H:i:s"));
    fwrite($fp, ";");
    fwrite($fp, $params['name']);
    fwrite($fp, ";");
    fwrite($fp, $params['msisdn']);
    fwrite($fp, "\n");
    fclose($fp);
    */

    $return = goCurl('order/create.php', $params);
    $array = json_decode($return, true);
//    echo '<pre>';
//    print_r($array);
//    echo '</pre>';
//    die();


/*imklo*/	
$upd = ['id' => $my_id, 'response_api' => $api_reqest];
file_get_contents('https://magazone.site/api/update_lead?'.http_build_query($upd));
/*imklo*/

    header('Location:' . 'thankyou-cz.php');

    // Show the error while testing
    /*
    if (isset($array['response'])) $array = $array['response'];
    if ($array['msg'] == "error") {
    header('Location:'.'error.php?msg='.$array['msg'].'&error='.$array['error']);
    } else {
    header('Location:'.'thanks.php?request_id='.$array['order_id']);
    }
    */
}

function goCurl($url, $data){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://leadreaktor.com/api/$url?api_key=DTn1ro75vsQbOWRstQpaZeGh0Y3GVjak");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $return = curl_exec($ch);
    curl_close($ch);

    return $return;
}