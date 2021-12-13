<?php

namespace BabyLog {
    require_once("baby_util.php");

    class BabyLog {    
        private static $logDir = "log";
        private static $logFileName = "trace.log";
        private static $logLimit = 8*1024*1024;
        private static $logNum = 8;

        public static function trace($message) {
          //  global $TRACE_LOG_FILE_NAME;
            $date = date("Y-m-d H:i:s");
            $dbg = debug_backtrace();
            $ip = "-";
            $sessionid = "----";
            $className = "----";
            $funcName = "----";

            if(!file_exists(BabyLog::$logDir)) {
                mkdir(BabyLog::$logDir);
            }
            

            // 接続元のIPアドレスを取得する.
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                $ip_array = explode(",", $ip);
                $ip = $ip_array[0];
            } else {
                // Shell起動の時、エラーが大量に出るので修正
                if( isset($_SERVER['REMOTE_ADDR']) ) {
                    $ip = $_SERVER['REMOTE_ADDR'];
                }
                else {
                    $ip = " --- ";
                }
            }

            // セッションIDを取得する.
            if (session_id() != "") {
                $sessionid = session_id();
            }
            
            if(count($dbg) > 1) {
                if(array_key_exists('class', $dbg[1])) {
                    $className = $dbg[1]['class'];
                }

                if(array_key_exists('function', $dbg[1])) {
                    $funcName = $dbg[1]['function'];
                }
            }
            else {
                $className = "----";
                $funcName = "----";
            }
            $var = print_r($message, true);
            
            $output = date('Y/m/d H:i:s')." ".$ip." ".$sessionid." "."class:".$className." func:".$funcName." line:".$dbg[0]['line']." msg:" . $var . "\n";

            $logFilePath = BabyLog::$logDir."/".BabyLog::$logFileName;

            file_put_contents($logFilePath, $output, FILE_APPEND);
           
            //ここでファイルが上限を超えたらラップするように変更したい。
            $size = filesize($logFilePath);
            if($size > BabyLog::$logLimit) {

                $logFileList = \BabyUtil\getfileLists($logFilePath."[0-9]*", "asc");
                print_r($logFileList);
                $c = count($logFileList);
                if($c > BabyLog::$logNum) {
                    $backupFileName = $logFilePath.(string)($c+1);
                    rename($logFilePath, $backupFileName);
                }
                else {
                    $oldFileName = $logFileList[0];
                    $backupFileName = $oldFileName;
                    unlink($backupFileName);
                    rename(BabyLog::$logFileName, $backupFileName);
                }
                
            }

        }
    }
}
