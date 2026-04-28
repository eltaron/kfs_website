<?php

require 'Initiation.php';
$initiator = new Initiation();
$params = array(
    "sender_id" => $_POST['sender_id'],
    "sender_name" => $_POST['sender_name'],
    "efinance_password" => $_POST['efinance_password'],
    "service_code" => $_POST['service_code'],
    "account_code" => $_POST['account_code'],
    "account_amount" => $_POST['account_amount'],
    "payment_gateway_url" => $_POST['payment_gateway_url'],
    "confirmation_url" => $_POST['confirmation_url'],
    "confirmation_redirect_url" => $_POST['confirmation_redirect_url'],
    "server_ip" => "::1",
    "certificate_path" => $_SERVER['DOCUMENT_ROOT'] . "/Payment/certificates/InternetPaymentCrt.cer",
    "client_order_id" => '123' //$order_info['order_id']
);
// print '<pre>';
// print_r($params);
// exit;
/**
 * In case of Channel
 */
/*
        $data['paymentType'] = "Channel";
        $data['paymentMechanism'] = "NotSet";
        $data['paymentChannel']['mobileNo'] = $this->config->get('payment_epay_channels_channel_mobile');
        $data['paymentChannel']['email'] = $this->config->get('payment_epay_channels_channel_email');
        $mechanism = array(
            "type" => $data['paymentType'],
            "mechanismType" => $data['paymentMechanism'],
            "channelMobileNo" => $data['paymentChannel']['mobileNo'],
            "channelEmail" => $data['paymentChannel']['email'],
        );
 */

//In case of Card
$data['paymentType'] = "Card";
$data['paymentMechanism'] = "NotSet";
$mechanism = array(
    "type" => $data['paymentType'],
    "mechanismType" => $data['paymentMechanism'],
    "channel" => "",
);

$url = $initiator->initiatePaymentRequest($params, $mechanism);
// print 'test <pre>';
// print_r($url);
?>

<form method="post" id="initiationForm"
    action="<?php echo $_POST['payment_gateway_url']; ?>">
    <input type="hidden" name="SenderID" value="<?= $url['SenderID'] ?>">
    <input type="hidden" name="RandomSecret" value="<?= $url['RandomSecret'] ?>">
    <input type="hidden" name="RequestObject" value="<?= $url['RequestObject'] ?>">
    <input type="hidden" name="HasedRequestObject" value="<?= $url['HasedRequestObject'] ?>">
    <input type="submit" id="sendButton" value="send">
</form>
<script type="text/javascript">
    document.getElementById("sendButton").style.display = "none";
    document.getElementById("initiationForm").submit();
</script>