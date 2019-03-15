<?php
    require_once('models/CurlModel.php');
    require_once('models/HttpResponseModel.php'); 

    class APIHelper {    

        public static function Get($url,$headers) {

            $curl = new CurlModel();
            $httpResponse = new HttpResponseModel();
            curl_setopt($curl->ch, CURLOPT_URL, $url);
            if($headers)
                curl_setopt($curl->ch, CURLOPT_HTTPHEADER, $headers);
            // CURL EXECUTE:
            $httpResponse->response = curl_exec($curl->ch);
            $header_size = curl_getinfo($curl->ch, CURLINFO_HEADER_SIZE);
            $httpResponse->header_code = curl_getinfo($curl->ch, CURLINFO_HTTP_CODE);
            $httpResponse->header = substr($httpResponse->response, 0, $header_size);
            $httpResponse->body = substr($httpResponse->response, $header_size);
            $err = curl_error($curl->ch);
            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                return $httpResponse;
            }
        }

        public static function Post($url,$data,$headers) {

            $curl = new CurlModel();
            $httpResponse = new HttpResponseModel();
            curl_setopt($curl->ch, CURLOPT_POST, 1);
            if ($data)
                curl_setopt($curl->ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl->ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl->ch, CURLOPT_URL, $url);            

            // CURL EXECUTE:
            $httpResponse->response = curl_exec($curl->ch);
            $header_size = curl_getinfo($curl->ch, CURLINFO_HEADER_SIZE);
            $httpResponse->header_code = curl_getinfo($curl->ch, CURLINFO_HTTP_CODE);
            $httpResponse->header = substr($httpResponse->response, 0, $header_size);
            $httpResponse->body = substr($httpResponse->response, $header_size);

            $err = curl_error($curl->ch);
            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                return $httpResponse;
            }
        }

        public static function Put($url,$data,$headers) {

            $curl = new CurlModel();
            $httpResponse = new HttpResponseModel();
            curl_setopt($curl->ch, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data)
                curl_setopt($curl->ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl->ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl->ch, CURLOPT_URL, $url);            

            // CURL EXECUTE:
            $httpResponse->response = curl_exec($curl->ch);
            $header_size = curl_getinfo($curl->ch, CURLINFO_HEADER_SIZE);
            $httpResponse->header_code = curl_getinfo($curl->ch, CURLINFO_HTTP_CODE);
            $httpResponse->header = substr($httpResponse->response, 0, $header_size);
            $httpResponse->body = substr($httpResponse->response, $header_size);

            $err = curl_error($curl->ch);
            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                return $httpResponse;
            }
        }

        public static function Delete($url,$headers) {

            $curl = new CurlModel();
            $httpResponse = new HttpResponseModel();
            curl_setopt($curl->ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($curl->ch, CURLOPT_URL, $url);
            if($headers)
                curl_setopt($curl->ch, CURLOPT_HTTPHEADER, $headers);
            // CURL EXECUTE:
            $httpResponse->response = curl_exec($curl->ch);
            $header_size = curl_getinfo($curl->ch, CURLINFO_HEADER_SIZE);
            $httpResponse->header_code = curl_getinfo($curl->ch, CURLINFO_HTTP_CODE);
            $httpResponse->header = substr($httpResponse->response, 0, $header_size);
            $httpResponse->body = substr($httpResponse->response, $header_size);
            $err = curl_error($curl->ch);
            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                return $httpResponse;
            }
        }
    }
?>