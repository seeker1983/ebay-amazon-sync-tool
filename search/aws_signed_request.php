<?php
function aws_signed_request($region, $params, $public_key, $private_key)
{
    /*
    Copyright (c) 2009 Ulrich Mierendorff

    Permission is hereby granted, free of charge, to any person obtaining a
    copy of this software and associated documentation files (the "Software"),
    to deal in the Software without restriction, including without limitation
    the rights to use, copy, modify, merge, publish, distribute, sublicense,
    and/or sell copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
    THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
    DEALINGS IN THE SOFTWARE.
    */
    
    /*
    Parameters:
        $region - the Amazon(r) region (ca,com,co.uk,de,fr,jp)
        $params - an array of parameters, eg. array("Operation"=>"ItemLookup",
                        "ItemId"=>"B000X9FLKM", "ResponseGroup"=>"Small")
        $public_key - your "Access Key ID"
        $private_key - your "Secret Access Key"
    */

    // some paramters
    $method = "GET";
	if($region=='it')
    $host = "webservices.amazon.".$region;
	else
	$host = "ecs.amazonaws.".$region;
	
    $uri = "/onca/xml";
    
    // additional parameters
    $params["Service"] = "AWSECommerceService";
    $params["AWSAccessKeyId"] = $public_key;
    // GMT timestamp
    $params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
    // API version
    $params["Version"] = "2011-08-01";
	$params["AssociateTag"]="coole20-20";
    
    // sort the parameters
    ksort($params);
    
    // create the canonicalized query
    $canonicalized_query = array();
    foreach ($params as $param=>$value)
    {
        $param = str_replace("%7E", "~", rawurlencode($param));
        $value = str_replace("%7E", "~", rawurlencode($value));
        $canonicalized_query[] = $param."=".$value;
    }
    $canonicalized_query = implode("&", $canonicalized_query);
    
    // create the string to sign
    $string_to_sign = $method."\n".$host."\n".$uri."\n".$canonicalized_query;
    
    // calculate HMAC with SHA256 and base64-encoding
    $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $private_key, True));
    
    // encode the signature for the request
    $signature = str_replace("%7E", "~", rawurlencode($signature));
    
    $request = "http://".$host.$uri."?".$canonicalized_query."&Signature=".$signature;

	return rest_helper($request,null,"GET","xml");
}

function rest_helper($url, $params = null, $verb = 'GET', $format = 'json'){
	$cparams = array(
		'http' => array(
			'method' => $verb,
			'ignore_errors' => true
		)
	);
	if ($params !== null) {
		$params = http_build_query($params, '', '&');
		if ($verb == 'POST') {
			$cparams['http']['content'] = $params;
		}
		else {
			$url .= '?' . $params;
		}
	}
	
	$context = stream_context_create($cparams);
	$fp = fopen($url, 'rb', false, $context);
	if (!$fp) {
		$res = false;
	} 
	else {
		// If you're trying to troubleshoot problems, try uncommenting the
		// next two lines; it will show you the HTTP response headers across
		// all the redirects:
		// $meta = stream_get_meta_data($fp);
		// var_dump($meta['wrapper_data']);
		$res = stream_get_contents($fp);
	}

	if ($res === false) {
		throw new Exception("$verb $url failed: $php_errormsg");
	}

	switch ($format) {
		case 'json':
		$r = json_decode($res);
		if ($r === null) {
			throw new Exception("failed to decode $res as json");
		}
		return $r;

		case 'xml':
		try {
			$r = simplexml_load_string($res);
			if ($r === null) {
				throw new Exception("failed to decode $res as xml");
			}
			return $r;
		} catch (Exception $e) {
			return false;
		}
		
	}
	return $res;
}
?>
