<?php
namespace Miglio\Utils;

class Mgutils{
    const WRITE = 'w';
    const READ  = 'r';
    const WINDOWS = 'b';
    private static $MODE = array(0=>'r',1=>'r+',2=>'w',3=>'w+',4=>'a',5=>'a+',6=>'x',7=>'x+',8=>'c',9=>'c+' );
    //
    public static function getIp(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    //json encode for cross domain
    public static function sendCallback($array,$callName = 'jsonp_callback',$prety = false){
        $json = $callName.'(';
        $json .= json_encode($array);
        $json .= ');';
        return $json;
    }
    public static function directoryCreate($path){
        //$path = "uploads/product";
        if(!is_dir($path)){
          mkdir($path,0755,TRUE);
        } 
    }
    public static function fileDelete($file){
        if (file_exists($value)) {
            unlink($file);
        }
    }
    public static function fileExist($file){
        if (file_exists($file)){
            return true;
        }
        return false;
    }
    public static function fileCreate($path,$filename,$data,$mode=false){
        $mode = (empty($mode))?self::$MODE[5] : self::$MODE[$mode];
        $file = fopen($path.$filename, $mode);
        if(!fwrite($file, $data)){
            return false;
        }
        fclose($file);
        return true;
    }
    public static function fileRead($path,$filename){
        $fp = fopen($path.$filename, self::$MODE[0]);
        $content = fread($fp, filesize($path.$filename));
        if(!$content){
            return false;
        }
        fclose($fp);
        return $content;
    }
    public static function isJson($data){
        $array = json_decode($data);
        return (json_last_error() == 0)? $array : false ;
    }
    public static function setCoockie($name,$value,$hour,$minutes=60){
        setcookie($name, $value, time() +  $hour*$minutes * 60, "/");
        // 86400 = 1 day
    }
    public static function getCoockie($name){
        if(isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        }
        return false;
    }
    public static function deleteCoockie($name){
        unset($_COOKIE[$cookie_name]);
        setcookie($cookie_name, '', time() - 3600);
    }
    public static function coockiesEnabled(){
        setcookie("test_cookie", "miglio test coockie", time() + 3600, '/');
        if(count($_COOKIE) > 0) {
            //echo "Cookies are enabled";
            return true;
        } else {
            //echo "Cookies are disabled";
            return false;
        }
    }
    //HEADERS
    public static function getHeaders(){
        $headers = apache_request_headers();
        return !empty($headers)?$headers:array();
    }
    public static function searchHeader($header){
        $headers = apache_request_headers();
        return isset($headers[$header])?true:false;
    }
    //like :  domain/file/  ,  file.zip
    public function downloadFile($path,$filename){
        // http://perishablepress.com/press/2010/11/17/http-headers-file-downloads/
        // set example variables
        $filepath = $path;
        // http headers for zip downloads
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"".$filename."\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($filepath.$filename));
        ob_end_flush();
        @readfile($filepath.$filename);
    }
    //SORTING MULTIDIMENSIONAL
    public function multiArrayOrder(&$data,$key,$order=''){
        foreach ($data as $k => $row) {
            $element[$k]  = $row[$key];
        }
        if($order=='desc'){
            array_multisort($element, SORT_DESC, $data);
        }else{
            array_multisort($element, SORT_ASC, $data);
        }
    }
    public function multiple_strstr($array_search,$string){
        foreach($array_search as $value){
            if(strstr($string,$value)){
                return true;
            }
        }
        return false;
    }
    public static function verySympleMail($data){
        //'FROM:directbathrooms'
        ////mail($to,$subject,$message,$headers);
        $headers = 'FROM:'.$data['From']."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        return (mail($data['AddAddress'],$data['Subject'],$data['Body'],$headers))?true:false;
    }
    /**
     * @string $result : data response
     * @array  $dropdown : multidimensional array
     * @string $space : space
     * @boolean $bool : booelan variable
     * */
    public function buildtree(&$result,$dropdown,$space=' ',$bool=true){
        if(!is_array($dropdown) || count($dropdown) < 1 ){ return false; }
        $i = 0;
        $space .= $bool ? $space : '';
        while($i < count($dropdown)){
            $key = key($dropdown);
            $result .= $space;
            $result .= ( is_numeric($key) ? $dropdown[$key] : $key );
            $result .= '<br />';
            if(is_array($dropdown[$key])){
                buildtree($result,$dropdown[$key],$space);
            }
            next($dropdown);
            $i++;
        }
    }
}