<?php


function  af_request($params, $region = 'it')
{
	$public_key = $GLOBALS['temperamente']['amazon']['api_key'];
	$private_key = $GLOBALS['temperamente']['amazon']['api_secret'];
	$associate_tag = $GLOBALS['temperamente']['amazon']['associate_tag'];
    $method = "GET";

	if($region =='com') {
		$host = "ecs.amazonaws.".$region;
	} else {
		$host ='webservices.amazon.'.$region; 
	}
    $uri = "/onca/xml";    
    
    $params["Service"]          = "AWSECommerceService";
    $params["AWSAccessKeyId"]   = $public_key;
    $params["AssociateTag"]     = $associate_tag;
    $params["Timestamp"]        = gmdate("Y-m-d\TH:i:s\Z");
    $params["Version"]          = "2011-08-01";

    /* The params need to be sorted by the key, as Amazon does this at
      their end and then generates the hash of the same. If the params
      are not in order then the generated hash will be different thus
      failing the authetication process.
    */
    ksort($params);
    
    $canonicalized_query = array();

    foreach ($params as $param=>$value)
    {
        $param = str_replace("%7E", "~", rawurlencode($param));
        $value = str_replace("%7E", "~", rawurlencode($value));
        $canonicalized_query[] = $param."=".$value;
    }
    
    $canonicalized_query = implode("&", $canonicalized_query);
    $string_to_sign = $method."\n".$host."\n".$uri."\n".$canonicalized_query;
    
    /* calculate the signature using HMAC with SHA256 and base64-encoding.
       The 'hash_hmac' function is only available from PHP 5 >= 5.1.2.
    */
    $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $private_key, True));
    
    /* encode the signature for the request */
    $signature = str_replace("%7E", "~", rawurlencode($signature));
    
    /* create request */
    $request = "http://".$host.$uri."?".$canonicalized_query."&Signature=".$signature;
//	echo '<a href="'.$request.'">xml</a><br>';	die;

	return DOMDocument::loadXML( file_get_contents($request) );
}

function af_get_book_details_from_api_item($item) {
	$book = array();
	
	$title = $item->getElementsByTagName('Title')->item(0);
	$author = $item->getElementsByTagName('Author')->item(0);
	$url = $item->getElementsByTagName('DetailPageURL')->item(0);
	$ean = $item->getElementsByTagName('EAN')->item(0);
	$asin = $item->getElementsByTagName('ASIN')->item(0);
	$isbn = $item->getElementsByTagName('ISBN')->item(0);
	$label = $item->getElementsByTagName('Label')->item(0);
	if($price = $item->getElementsByTagName('ListPrice')->item(0)) {
		$price = $price->getElementsByTagName('Amount')->item(0);
	}
	$pages = $item->getElementsByTagName('NumberOfPages')->item(0);
	$release_date = $item->getElementsByTagName('ReleaseDate')->item(0);
	if($lowest_price = $item->getElementsByTagName('OfferSummary')->item(0)) {
		if($lowest_price = $lowest_price->getElementsByTagName('LowestNewPrice')->item(0)) {
			$lowest_price = $lowest_price->getElementsByTagName('Amount')->item(0);
		}
	}
	if($medium_image =  $item->getElementsByTagName('MediumImage')->item(0)) {
		$medium_image =  $medium_image->getElementsByTagName('URL')->item(0);		
	}
			
	if($title) { $book['title'] = $title->nodeValue; }
	if($author) { $book['author'] = $author->nodeValue; }
	if($ean) { $book['ean'] = $ean->nodeValue; }
	if($url) { $book['url'] = urldecode($url->nodeValue); }
	if($isbn) { $book['isbn'] = $isbn->nodeValue; }
	if($asin) { $book['asin'] = $asin->nodeValue; }
	if($label) { $book['label'] = $label->nodeValue; }
	if($price) { $book['price'] = $price->nodeValue; }
	if($pages) { $book['pages'] = $pages->nodeValue; }
	if($release_date) { $book['release-date'] = $release_date->nodeValue; }
	if($lowest_price) { $book['lowest-price'] = $lowest_price->nodeValue; }
	if($medium_image) { $book['medium-image'] = $medium_image->nodeValue; }
	return $book;
}

function af_get_book_details_by_search($book_title, $multiple = false) {

	$res = af_request( array(
		'Keywords' => $book_title,
		'Operation' => 'ItemSearch',
		'ResponseGroup' => 'Medium',
		'SearchIndex' => 'Books',
		'Service' => 'AWSECommerceService',
		));
	$i = 0;
	
	$items = $res->getElementsByTagName('Item');
	$return = array();
	while(($multiple || $i==0) && $item = $items->item($i)) {
		
		$return[] = af_get_book_details_from_api_item($item);
		$i++;
	}
	if(count($return) <=0) {
		return false;
	} else {
		if($multiple) {
			return $return;
		} else {
			return $return[0];
		}
	}
}





function af_get_similarity_lookup_items($item_ids) {
	$res = af_request( array(
		'Keywords' => $book_title,
		'Operation' => 'SimilarityLookup',
		'ItemId' => implode(',',$item_ids),
		'SearchIndex' => 'Books',
		'SimilarityType' => 'Random',
		'ResponseGroup' => 'Medium',
		'Service' => 'AWSECommerceService',
		));
	$items = $res->getElementsByTagName('Item');
	$return = array();
	$i = 0;
	while($item = $items->item($i)) {
		$book = af_get_book_details_from_api_item($item);
		$return[] = $book;
		$i++;
	}
	return $return;
}

function af_get_book_by_post_id($post_id) {
	$sql = "SELECT * FROM wp_books WHERE post_id = '$post_id'; ";
	if($rows = $GLOBALS['wpdb']->get_results($sql)) {
		return $rows[0];
	}
	return false;
}

function af_save_book_details($book, $post_id=0) {
	$post_id = intval($post_id);
	foreach($book as $k => $v) {
		$book[$k] = mysql_real_escape_string($v);	
	}

	if(($book['isbn'] && $book['isbn'] != '') || $post_id>0) {
		if($book['isbn'] != '') {
			$sql = "SELECT id FROM wp_books WHERE isbn = '{$book['isbn']}'; ";
		} else {
			$sql = "SELECT id FROM wp_books WHERE post_id = '{$post_id}'; ";
		}
		$rows = $rows = $GLOBALS['wpdb']->get_results($sql);
		if(count($rows) == 0) {
			$sql = "INSERT INTO wp_books 
				(created, title, amazon_url, amazon_asin, amazon_medium_image_url, amazon_release_date, amazon_author, amazon_price, amazon_lowest_price, isbn, post_id) 
				VALUES (			
				'".date('Y-m-d H:i:s')."', '{$book['title']}', '{$book['url']}', '{$book['asin']}', '{$book['medium-image']}', '{$book['release-date']}', '{$book['author']}',
				'" . ($book['price']/100) . "', '" . ($book['lowest-price']/100) . "', '{$book['isbn']}', '$post_id'
				);
				";
			if($GLOBALS['wpdb']->query($sql)) {			
				return 'create';
			}
		} else {
			$sql = "UPDATE wp_books SET 
				updated = '".date('Y-m-d H:i:s')."',
				title = '{$book['title']}',
				amazon_url = '{$book['url']}',
				amazon_asin = '{$book['asin']}',
				amazon_medium_image_url = '{$book['medium-image']}',
				amazon_release_date = '{$book['release-date']}',
				amazon_author = '{$book['author']}',
				amazon_price = '" . ($book['price']/100) . "',
				amazon_lowest_price = '" . ($book['lowest-price']/100) . "' ";
			if($book['isbn'] != '') {
				$sql .= ", post_id = '$post_id' WHERE isbn = '{$book['isbn']}'; ";
			} else {
				$sql .= " WHERE post_id = '$post_id'; ";
			}
			
			if($GLOBALS['wpdb']->query($sql)) {			
				return 'update';
			}
		}
		return $sql;
		return false;		
	}	
}