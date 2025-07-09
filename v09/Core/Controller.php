<?php

namespace Core;


use \Core\GlobalsVariable;



class Controller extends DefinAll
{
    public static function Encrypt($encrypt, $key, $type = null)
    {
        $ENCRYPTION_KEY = $key;
        $ENCRYPTION_ALGORITHM = 'AES-256-CBC';
        $EncryptionKey = base64_decode($ENCRYPTION_KEY);
        // 16바이트의 IV 생성
        $InitializationVector = openssl_random_pseudo_bytes(16);
        $EncryptedText = openssl_encrypt($encrypt, $ENCRYPTION_ALGORITHM, $EncryptionKey, OPENSSL_RAW_DATA, $InitializationVector);
        // IV 기록
        $IVForDecryption = base64_encode($InitializationVector);
        $EncryptVal = base64_encode($EncryptedText . '|@ebuy@|' . $IVForDecryption);
        $EncryptVal = str_replace('+', '||plus||', $EncryptVal);
        $EncryptVal = str_replace('/', '||slush||', $EncryptVal);
        return $EncryptVal;
    }

    public static function Decrypt($decrypt, $key, $type = null)
    {
        $ENCRYPTION_KEY = $key;
        $ENCRYPTION_ALGORITHM = 'AES-256-CBC';
        $EncryptionKey = base64_decode($ENCRYPTION_KEY);
        $decrypt = str_replace('||plus||', '+', $decrypt);
        $decrypt = str_replace('||slush||', '/', $decrypt);
        list($Encrypted_Data, $InitializationVector) = array_pad(explode('|@ebuy@|', base64_decode($decrypt), 2), 2, null);
        // IV를 사용하여 복호화
        $DecryptedText = openssl_decrypt($Encrypted_Data, $ENCRYPTION_ALGORITHM, $EncryptionKey, OPENSSL_RAW_DATA, base64_decode($InitializationVector));
        return $DecryptedText;
    }

    // 접속 IP 주소
    public function GetIPaddress()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }

    // err return
    protected static function ErrExport($data = null)
    {
        echo json_encode(['result' => 'f','msg' => $data],JSON_UNESCAPED_UNICODE);
        exit();
    }

    // 랜덤스트링 조합
    public static function MakeRandomString($LENGTH = 32, $TYPE = NULL)
    {
        switch ($TYPE) {
            case 'SignUpHistoryRandoms': // 로그인시 랜덤값음 히스토리에 넣기위해
            $characters  = '123456789';
            $string_generated = '';
            $nmr_loops = $LENGTH;
            while ($nmr_loops--)
            {
                $string_generated .= $characters[mt_rand(0, strlen($characters) - 1)];
            }
            break;
            default:
                $characters  = '0123456789';
                $characters .= 'abcdefghijklmnopqrstuvwxyz';
                $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $characters .= '_';
                $string_generated = '';
                $nmr_loops = $LENGTH;
                while ($nmr_loops--)  {
                    $string_generated .= $characters[mt_rand(0, strlen($characters) - 1)];
                }
            break;
        }
        return $string_generated; // 랜덤 종합
    }

    // curl쏘기
    public static function SendCurl($dataPack=null,$url=null)
    {
        if(isset($dataPack)&&!empty($dataPack)&&
            isset($url)&&!empty($url)
        ){

            $result = json_encode($dataPack,JSON_UNESCAPED_UNICODE);
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$result,
                CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        }
    }


}
