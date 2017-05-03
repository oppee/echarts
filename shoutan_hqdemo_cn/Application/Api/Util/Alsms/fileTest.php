<?php
    include "TopSdk.php";
    date_default_timezone_set('Asia/Shanghai'); 

    $c = new TopClient;
    $c->appkey = '23437046';
    $c->secretKey = 'f39a8b063d0d8da3f72366ab3b2adc88';
    $req = new AlibabaAliqinFcSmsNumSendRequest;
    $req->setExtend("123456");
    $req->setSmsType("normal");
    $req->setSmsFreeSignName("达曼科技");//签名
    $req->setSmsParam("{\"code\":\"1234\",\"customer\":\"madu\",\"product\":\"madu\"}");//参数
    $req->setRecNum("17091645504");//手机号 多个用，号隔开
    $req->setSmsTemplateCode("SMS_13250620");//模板ID
    $resp = $c->execute($req);

    echo "<pre>";
    print_r($resp);
    echo "</pre>";
?>