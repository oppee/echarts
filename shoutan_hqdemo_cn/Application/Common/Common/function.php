<?php
function P($data) {
    dump($data,1,'<pre>',0);die;
}
function formatUrl($str)
{
    if ($str != "") {
        $Urls = explode("http://", $str);
        if (count($Urls) != 0) {
            if (count($Urls) == 1) {
                $url = "http://" . $str;
            } else {
                $url = $str;
            }
        } else {
            $url = "javascript:void(0)";
        }
    } else {
        $url = "javascript:void(0)";
    }

    return $url;
}

//格式化数字，位数不足补0
function format_num($str, $num, $prefix)
{
    return $prefix.sprintf("%0".($num-count($prefix))."d", $str);
}

//防脚本注入XSS
function RemoveXSS($val) {
   // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
   // this prevents some character re-spacing such as <java\0script>
   // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
   // straight replacements, the user should never need these since they're normal characters
   // this prevents like <IMG SRC=@avascript:alert('XSS')>
   $search = 'abcdefghijklmnopqrstuvwxyz';
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $search .= '1234567890!@#$%^&*()';
   $search .= '~`";:?+/={}[]-_|\'\\';
   for ($i = 0; $i < strlen($search); $i++) {
      // ;? matches the ;, which is optional
      // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
      // @ @ search for the hex values
      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
      // @ @ 0{0,7} matches '0' zero to seven times
      $val = preg_replace('/(?{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
   }
   // now the only remaining whitespace attacks are \t, \n, and \r
   $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
   $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
   $ra = array_merge($ra1, $ra2);
   $found = true; // keep replacing as long as the previous round replaced something
   while ($found == true) {
      $val_before = $val;
      for ($i = 0; $i < sizeof($ra); $i++) {
         $pattern = '/';
         for ($j = 0; $j < strlen($ra[$i]); $j++) {
            if ($j > 0) {
               $pattern .= '(';
               $pattern .= '(&#[xX]0{0,8}([9ab]);)';
               $pattern .= '|';
               $pattern .= '|(?{0,8}([9|10|13]);)';
               $pattern .= ')*';
            }
            $pattern .= $ra[$i][$j];
         }
         $pattern .= '/i';
         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
         if ($val_before == $val) {
            // no replacements were made, so exit the loop
            $found = false;
         }
      }
   }
   return $val;
}
//防sql注入
function abacaAddslashes($var) {
    if (! get_magic_quotes_gpc ()) {
        if (is_array ( $var )) {
            foreach ( $var as $key => $val ) {
                $var [$key] = abacaAddslashes ( $val );
            }
        } else {
            $var = addslashes ( $var );
        }
    }
    return $var;
}

//二级数组添加元素
function addkey(&$val ,$key, $param){ 
	$val[$param['key']] = $param['val'];
}

//数字转化为大写一，二，三...
function numCapital($number){
    $number=substr($number,0,2);
    $arr=array("零","一","二","三","四","五","六","七","八","九");
    if(strlen($number)==1){
        $result=$arr[$number];
    }else{
        if($number==10){
            $result="十";
        }else{
            if($number<20){
                $result="十";
            }else{
                $result=$arr[substr($number,0,1)]."十";
            }
            if(substr($number,1,1)!="0"){
                $result.=$arr[substr($number,1,1)];
            }
        }
    }
    return $result;
}

//当前月第一天及最后一天
function monthFirstLast(){
    $firstdate=date('Y-m-01 00:00:00');
    $firsttime=strtotime($firstdate);
    $lastdate = date('Y-m-d 23:59:59', strtotime("$firstdate +1 month -1 day"));
    $lasttime=strtotime($lastdate);
    $other=array(
        0=>$firsttime,
        1=>$lasttime
    );
    return $other;
}
    /**
     * 根据所要生成的文件夹，依次生成根目录文件夹
     * @access public
     * @param string $path 目标文件夹路径
     * @return boolean
     */
    function makeDir($path){
        $dir = explode('/',$path);
        $root = $dir[0] = $dir[0].'/';
        for($i=0;$i<count($dir);$i++){
            if($i!=0){
                $tmpdir = $dir[$i-1];
                $tmpdir .= $dir[$i].'/';
                $dir[$i] = $tmpdir;
            }
        }
        if(!is_dir($root)){
            return false;
        }else{
            for($i=1;$i<count($dir);$i++){
                if(!is_dir($dir[$i])){
                    mkdir($dir[$i],0777);
                }
            }
        }
    }

/**
 * 邮件发送
 * @param type $subject 邮件标题
 * @param type $message 邮件内容
 * @param type $recipients 接收人 单个直接邮箱地址，多个可以使用数组
 * @param type $bcc 抄送
 * @param type $replyTo
 * @param type $html 是否为html邮件
 * @param type $attachment 用逗号分隔
 * @param type $mail_from 发件人
 * @param type $mail_fromname 发件人姓名
 */
function sendMail($subject, $message, $recipients, $cc = '', $bcc = '', $replyTo = '', $mail_from = '', $mail_fromname = '', $html = false, $attachment = '') {
    import("@.Util.Mail.PHPMailerAutoload");
    try {
        $mail = new PHPMailer();

        //check smtp
        $Config = C('Config');
        if ($Config['mail_mode'] == 1) {
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = $Config['smtp_host']; // Specify main and backup server
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = $Config['smtp_username']; // SMTP username
            $mail->Password = $Config['smtp_password']; // SMTP password
            $mail->SMTPSecure = $Config['smtp_secure'] ? $Config['smtp_secure'] : ''; // Enable encryption, 'tls' 'ssl' also accepted
        }

        $mail->CharSet = 'utf-8';
        $mail->From = $Config['smtp_username']; //默认为系统发件人邮箱
        $mail->FromName = $mail_fromname ? $mail_fromname : $Config['mail_fromname']; //默认为系统发件人姓名

        if (is_array($recipients)) {
            foreach ($recipients as $email => $name) {
                $mail->addAddress($email, $name); // Add a recipient
            }
        } else {
            $mail->addAddress($recipients);
        }
        if ($replyTo) {
            $mail->addReplyTo($replyTo);
        }
        if ($cc) {
            if (is_array($cc)) {
                foreach ($cc as $email => $name) {
                    $mail->addCC($email, $name); // Add CC
                }
            } else {
                $mail->addCC($cc);
            }
        }
        if ($bcc) {
            if (is_array($bcc)) {
                foreach ($bcc as $email => $name) {
                    $mail->addBCC($email, $name); // Add BCC
                }
            } else {
                $mail->addBCC($bcc);
            }
        }

        //$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
        //添加附件
        if ($attachment) {
            $attachmentArray = explode(",", $attachment);
            foreach ($attachmentArray as $file) {
                if (is_file($file)) {
                    $mail->addAttachment($file); // Add attachments
                }
            }
        }

        // Set email format to HTML
        if ($html) {
            $mail->isHTML(true);
        }

        $mail->Subject = $subject;
        $mail->Body = $message;

        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        return $mail->send();
    } catch (phpmailerException $e) {
        return $e->errorMessage();
    }
}

/**
 * 取邮件模板，from email表
 */
function getEmailConfig($key) {
    return M('Email')->where(array('key' => $key))->find();
}


/**
 * 根据上传文件命名规则取得保存文件名(符合tp规则)
 * @access private
 * @param string $name 数据
 * @return string
 */
function uniqidName($name)
{
	$extension = array_pop(explode('.', $name));
	return uniqid() . "." . $extension;
}


//判断是否是苹果系统
function is_ios()
{
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$is_pc = (strpos($agent, 'windows nt')) ? true : false;
	$is_mac = (strpos($agent, 'mac os')) ? true : false;
	$is_iphone = (strpos($agent, 'iphone')) ? true : false;
	$is_ipad = (strpos($agent, 'ipad')) ? true : false;

	if($is_pc){
		  return  false;
	}
	
	if($is_mac){
		  return  true;
	}
	
	if($is_iphone){
		  return  true;
	}
	if($is_ipad){
		  return  true;
	}
}

//判断是否是手机
function is_mobile()
{
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	
	$is_iphone = (strpos($agent, 'iphone')) ? true : false;
	$is_android = (strpos($agent, 'android')) ? true : false;
		
	if($is_iphone){
		  return  true;
	}
	
	if($is_android){
		  return  true;
	}

}


	/*
	 * 删除文件
	 * @param $path  
	 * @return $info
	 */
	function delFile($path) {
		$flag="";
		if(!is_dir($path)){
			$pathInfo = pathinfo($path);
			$flag = unlink($pathInfo['dirname'].'/'.$pathInfo['basename']);
		}else {//否则就是目录，调用方法进行删除该目录下的所有文件
			$flag = $this->deldir($path);
		}
		return $flag;
	}

	function deldir($dir) {
		//先删除目录下的文件：
		$dh = opendir($dir);
		while ($file = readdir($dh)) {
			if ($file != "." && $file != "..") {
				$fullpath = $dir . "/" . $file;
				if (!is_dir($fullpath)) {
					unlink($fullpath);
				} else {
					deldir($fullpath);
				}
			}
		}

		closedir($dh);
		//删除当前文件夹：
		if (rmdir($dir)) {
			return true;
		} else {
			return false;
		}
	}

    //测试DEBUG
    function debug($str, $Path) {
        $fp = fopen($Path, "a+");
        if (is_array($str)) {
            foreach ($str as $key => $val) {
                $tempstr = $key . "=>" . $val . "\n";
                fwrite($fp, $tempstr);
            }
        } else {
            fwrite($fp, $str . "\n");
        }
        fclose($fp);
    }

    //数组转xml
    function arrToxml($arr)
    {
        if(!is_array($arr) 
            || count($arr) <= 0)
        {
            echo "数组数据异常！";die;
        }
        
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            // if (is_numeric($val)){
            //     $xml.="<".$key.">".$val."</".$key.">";
            // }else{
            //     $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            // }
            $xml.="<".$key.">".$val."</".$key.">";
        }
        $xml.="</xml>";
        return $xml; 
    }

    /**
     * xml转array
     */
    function xmlToarr($xml)
    {   
        if(!$xml){
            echo "xml数据异常！";
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $order_return = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
        return $order_return;
    }

    /**
     * 以post方式提交xml到对应的接口url
     * 
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param int $second   url执行超时时间，默认30s
     */
    function postXmlCurl($xml, $url, $second = 30)
    {       
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        
        //如果有配置代理这里就设置代理
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            echo "curl出错，错误码:$error";
        }
    }
?>
