<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


	function http_get( $url, $https = false, $cookie='' )
	{
		// 初始化一个cURL会话
		$curl = curl_init($url);
		
		if($https)
		{
			//验证证书
			//curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,true); ;
   			//curl_setopt($curl,CURLOPT_CAINFO,BASEPATH.'cacert.pem');
			
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);			
		}
		
		// 不显示header信息
		curl_setopt($curl, CURLOPT_HEADER, 0);
		// 将 curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 使用自动跳转
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		if(!empty($cookie)) {
			// 包含cookie数据的文件名，cookie文件的格式可以是Netscape格式，或者只是纯HTTP头部信息存入文件。
			curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie);
		}
		curl_setopt($curl, CURLOPT_TIMEOUT,120);  
		// 自动设置Referer
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
		// 执行一个curl会话
		$tmp = curl_exec($curl);
		// 关闭curl会话
		curl_close($curl);
		return $tmp;
	}

    function http_post( $url, $params, $https=false ,$multi = false)
    {
        $curl = curl_init($url);
        
        if($https)
        {
            //不验证证书
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        
        curl_setopt($curl, CURLOPT_HEADER, 0);

        //模拟用户使用的浏览器，在HTTP请求中包含一个”user-agent”头的字符串。
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        //发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
        curl_setopt($curl, CURLOPT_POST, 1);
        // 将 curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 使用自动跳转
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
        // 自动设置Referer
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        // Cookie地址
        //curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);
        // 全部数据使用HTTP协议中的"POST"操作来发送。要发送文件，
        // 在文件名前面加上@前缀并使用完整路径。这个参数可以通过urlencoded后的字符串
        // 类似'para1=val1¶2=val2&...'或使用一个以字段名为键值，字段数据为值的数组
        // 如果value是一个数组，Content-Type头将会被设置成multipart/form-data。
        if ($multi)
        {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        }else{
            curl_setopt($curl, CURLOPT_POSTFIELDS,$params);

        }
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

	
	function http_dowload($remote, $local, $cookie= '') {
		$cp = curl_init($remote);
		$fp = fopen($local,"w");
		curl_setopt($cp, CURLOPT_FILE, $fp);
		curl_setopt($cp, CURLOPT_HEADER, 0);
		if($cookie != '') {
			curl_setopt($cp, CURLOPT_COOKIEFILE, $cookie);
		}
		curl_exec($cp);
		curl_close($cp);
		fclose($fp);
	}
	
	function http_stream($host,$port,$uri,$content)
	{
		
        $fp = @fsockopen($host,$port,$errno,$errstr,30);
		if($fp)
		{
			stream_set_blocking($fp,0);  
	        fwrite($fp,"POST ".$uri." HTTP/1.1\r\n");
	        fwrite($fp,"Host:".$host."\r\n");
	        fwrite($fp,"Content-Type: multipart/form-data; \r\n");
	        fwrite($fp,"Content-length:".strlen($content)."\r\n\r\n");
	        fwrite($fp,$content);
			
			$ret ="";
			
	        while (!feof($fp)){
	            $ret .= fgets($fp, 1024);
	        }
	        fclose($fp);
			
	        $ret = trim(strstr($ret, "\r\n\r\n"));
			
			return $ret;
		}
		else
		{
			log_message('error',"http_stream 连接失败");
			return  "";
		}
	}
	
	function post_it($datastream, $url,$port) { 

		$url = preg_replace("@^http://@i", "", $url);
		$host = substr($url, 0, strpos($url, "/"));
		$uri = strstr($url, "/"); 
		
      	$reqbody = "";
	    foreach($datastream as $key=>$val) {
        	if (!empty($reqbody)) 
          		$reqbody.= "&";
	      	$reqbody.= $key."=".urlencode($val);
	    } 
		
		$contentlength = strlen($reqbody);
	    $reqheader =  "POST $uri HTTP/1.1\r\n".
	                   "Host: $host\n". "User-Agent: PostIt\r\n".
	     "Content-Type: application/x-www-form-urlencoded\r\n".
	     "Content-Length: $contentlength\r\n\r\n".
	     "$reqbody\r\n"; 
		
		$socket = fsockopen($host, $port, $errno, $errstr);
		
		if (!$socket) {
		   $result["errno"] = $errno;
		   $result["errstr"] = $errstr;
		   return $result;
		}
		
		fputs($socket, $reqheader);
		
		while (!feof($socket)) {
		   $result[] = fgets($socket, 4096);
		}
		
		fclose($socket);
		
		return $result;
	}

    function http_respon($url,$data,$type='GET') {
        $data_string = http_build_query($data);
        $url = $url.'/';
        if($type=='GET'){
            $data_string= preg_replace('[=|&]','/',$data_string);
            $url = $url.$data_string;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
            return json_decode($output = curl_exec($ch),TRUE) ; 
        }
        $ch = curl_init($url);
         $this_header = array(
            "content-type: application/x-www-form-urlencoded; 
            charset=UTF-8"
            );     
        curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        switch ($type) {
            case 'PUT':
           
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            break;
            case 'DELETE':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
        }
        
        $result = curl_exec($ch);
        
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contenttype = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        return json_decode($result,TRUE);
     
}
function http_respon1 ($url,$data,$type='GET') {
        $data_string = http_build_query($data);
        $url = $url.'?';
        if($type=='GET'){
            $url = $url.$data_string;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
            return json_decode($output = curl_exec($ch),TRUE) ; 
        }
        $ch = curl_init($url);
         $this_header = array(
            "content-type: application/x-www-form-urlencoded; 
            charset=UTF-8"
            );     
        curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        switch ($type) {
            case 'PUT':
           
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            break;
            case 'DELETE':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
        }
        
        $result = curl_exec($ch);
        
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contenttype = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        return json_decode($result,TRUE);
     
}
//post to josn
function http_respon_json ($url,$data) {
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );

        return json_decode(curl_exec($ch),true);
}

	
?>
