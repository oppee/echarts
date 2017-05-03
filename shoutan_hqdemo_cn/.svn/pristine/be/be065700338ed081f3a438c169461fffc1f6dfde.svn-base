<?php
/**
 * 用于与第三方接口对接的类，遵循OAuth2.0标准，请先开启session
 * @author chenzhen
 * 构造函数参数为：
 * $app_id 用户的APP_ID，必传  
 * $app_secret 用户的APP_SECRET，必传
 * $callBackURL 用户设定的回调地址  ，必传
 * $urlArr=array() 用于OAuth2.0验证的地址,选填，不填则使用QQ的地址
 * 
 * 静态方法getUserInfo用于获取用户相关信息，以数组型式返回，参数为：
 * $url  接口url地址   
 * $quest 请求方式get/post
 * $param 调用接口所传入的参数
 * 
 */
class OpenAPI{
    private $app_id;//用户的APP_ID
    private $app_secret;//用户的APP_SECRET
    private $callBackURL;//用户设定的回调地址
    public $userId;//获取的用户的ID，用于识别用户在此网站的唯一身份认证
    public $access_token;//获取的用户access_token
    private $urlArr=array();//用于获取用户ID的三个接口地址,loginUrl是跳转到登录界面,tokenUrl是用于获取token_access的接口地址，userIdUrl是用于获取用户id的接口地址
    
    
    /**
     * 初始化部分参数
     * @param  $appId 用户的APP_ID
     * @param $appSecret 用户的APP_SECRET
     * @param $callBackURL 用户设定的回调地址
     * @param $urlArr OAuth2.0认证地址
     */
    public function __construct($appId,$appSecret,$callBackURL,$urlArr){
        $this->app_id=$appId;
        $this->app_secret=$appSecret;
        $this->callBackURL=$callBackURL;
        //若创建对象时，若给了$urlArr则使用该urlArr，没给则默认使用QQ登录
        if(empty($urlArr)){
            $this->urlArr=array(
                'loginUrl'=>array('url'=>'https://graph.qq.com/oauth2.0/authorize','data'=>'scope=get_user_info,add_idol'),//登录地址
                'tokenUrl'=>array('url'=>'https://graph.qq.com/oauth2.0/token','request'=>'get'),//获取access_token地址
                'userIdUrl'=>array('url'=>'https://graph.qq.com/oauth2.0/me','request'=>'get')//获取用户ID的地址
            );
        }else{
            $this->urlArr=$urlArr;
        }                
        //获取登录成功后的code值传入回调地址
        $this->getCode($callBackURL);
    }
    /**
     * 用于获取用户登录成功后的code值
     * @param $callback 回调地址
     */
    private function getCode($callback){              
        if($_GET['code']==null){//若用户没有登录过，则跳转到登录界面
            $data='';
            $_SESSION['state']=md5(uniqid(rand(), TRUE));//生成state值 用于下一步的判断
            //构建登录界面所需的参数
            if($this->urlArr['loginUrl']['data']){
                $data.=$this->urlArr['loginUrl']['data'].'&';
            }
            $data.='response_type=code&client_id='.$this->app_id."&state=".$_SESSION['state'].'&redirect_uri='.urlencode($callback);
            //header("location:".$this->urlArr['loginUrl']['url']."?".$data);
            redirect($this->urlArr['loginUrl']['url']."?".$data);
        }else{//若用户登录成功，则调用函数获取access_token
            //exit;
            if($_GET['state']==$_SESSION['state']){
                //unset($_SESSION['state']);
                $this->getAccessToken($_GET['code']);
            }else{
                exit("The state does not match. You may be a victim of CSRF.");
            }
        }
    }
    /**
     * 获取验证后的access_token值 
     * @param $code 获取的code 的值 
     */
    private function getAccessToken($code){
        $data=array(//构建获取access_token所需要的参数
                    "grant_type"=>"authorization_code",
                    "client_id"=>$this->app_id,
                    "client_secret"=>$this->app_secret,
                    "code"=>$code,
                    "redirect_uri"=>urlencode($this->callBackURL)
                );
        //调用https_request函数获取传过来的内容
        $response=$this->https_request($this->urlArr['tokenUrl']['url'],$this->urlArr['tokenUrl']['request'],$data);
        $tokenArray=array();
        if($this->checkAPI()){//对于腾讯返顺的结果需将结果处理下，以获取access信息
            $arr=explode("&",$response);            
            $tokenArray[substr($arr[0],0,12)]=substr($arr[0],13);//access_token的值
            $tokenArray[substr($arr[1],0,10)]=substr($arr[1],11);//expires_in的值
            $tokenArray[substr($arr[2],0,13)]=substr($arr[2],14);//refresh_token的值
        }else{
            $tokenArray=(Array)json_decode($response);
        }
        $this->access_token=$tokenArray['access_token'];
        //调用函数，获取用户的唯一ID
        $this->getUserId($tokenArray['access_token']);
    }    
    
    /**
     * 用于获取用户ID
     * @param $access_token获取到的access_token
     */
    private function getUserId($access_token){
        //构建获取openid所需要的url
        $data=array("access_token"=>$access_token);   
        $str=$this-> https_request($this->urlArr['userIdUrl']['url'],$this->urlArr['userIdUrl']['request'],$data);//调用curl函数，得到的结果是json格式，如:callback( {"client_id":"123456789","openid":"680726814F38234432332259FF9D62DC2"} );
        //如果获取成功，则提取json内容
        if($this->checkAPI()){//对于QQ则需进行处理 
            if (strpos($str, "callback") !== false){
                $lpos = strpos($str, "(");
                $rpos = strrpos($str, ")");
                $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
            }
        }       
        //将json格式的字符串转换为php对象
        $user = json_decode($str);     
        if (isset($user->error)){//如果转换失败，则显示失败的内容
            echo "<h3>error:</h3>" . $user->error;
            echo "<h3>msg  :</h3>" . $user->error_description;
            exit;
        }
        //将获取到的数据赋给uid
        if($user->uid){
            $this->userId=$user->uid;
        }elseif($user->openid){
            $this->userId=$user->openid;
        }
    }
    /**
     * 获取最终的信息
     * @param  $url 请求地址
     * @param  $request请求方式get/post
     * @param  $param请求参数
     * @return 以数组形式返回请求结果
     */
    public static function getInfo($url,$request,$param){
        //调用https_request函数
        $info=self::https_request($url,$request,$param);
        $result=(Array)json_decode($info);
        return $result;
    }
    
    /**
     * 检测当前所使用的是否是QQ的API
     * @return boolean
     */
    private function checkAPI(){
        if(preg_match("/qq/", $this->urlArr['loginUrl']['url']))
            return true;
        else
            return false;
    }
   
    /**
     * curl获取远程数据的函数
     * @param  $url 远程地址
     * @param  $request 请求方式get/post
     * @param  $data 传入的参数据，支持数组或url方式
     * @return 返回请求结果
     */
    private static function https_request($url,$request,$data){
        import("@.Util.Curl.libcurlemu");
        $ch = ecurl_init();
        $tmp='';
        if(is_array($data)){//传地来的若是数组，则将数组转成普通的地址参数
            //$combined = $baseURL."?";
            $valueArr = array();            
            foreach($data as $key => $val){
                $valueArr[] = "$key=$val";
            }            
            $keyStr = implode("&",$valueArr);
            $tmp=$keyStr;
        }else{
            $tmp=$data;
        }
        //exit;
        if($request=="get"){//处理get请求           
            ecurl_setopt($ch, CURLOPT_URL,$url."?".$tmp);
            ecurl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
            ecurl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//检 查证书
            ecurl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
         }else{//处理post请求    
            ecurl_setopt($ch, CURLOPT_URL,$url);
            ecurl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            ecurl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//检 查证书
            ecurl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
            ecurl_setopt($ch, CURLOPT_POST,1);
            ecurl_setopt($ch, CURLOPT_POSTFIELDS, $tmp);
         }
        $result = ecurl_exec($ch);
        return $result;   
    }
}

?>