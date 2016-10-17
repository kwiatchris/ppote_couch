<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
//require '..DB/db.php';
require_once "couch.php";
require_once "couchClient.php";
require_once "couchDocument.php";

$app = new \Slim\App;
$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});
//*****************************usando curl para un elemento con ID***********
$app->get ('/query/concurl/{one}', function (Request $request,Response $response){
	 //$one = $request->getAttribute('one');
	 $one = $request->getAttribute('one');
	// $couch_dsn = "http://localhost:5984/";
	//$couch_db = "pintxopote";
	//$client = new couchClient($couch_dsn,$couch_db);
	$url="http://localhost:5984/pintxopote/".(string)$one;

	
		$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser
curl_exec($ch);
//$info = curl_getinfo($ch);
//print_r($info);
// close cURL resource, and free up system resources
curl_close($ch);
	});
//*******************************retrive element with ID sin curl*****************
$app->get ('/query/sincurl/{two}', function (Request $request,Response $response){

 $two = $request->getAttribute('two');
$couch_dsn = "http://localhost:5984/";
$couch_db = "pintxopote";

 //$doc = new couchDocument($client);
$client = new couchClient($couch_dsn, $couch_db);

try {
	$doc = $client->getDoc($two);
} catch (Exception $e) {
	if ( $e->code() == 404 ) {
		echo "Document not found\n";
	} else {
		echo "Error: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
	}
	exit(1);
}
print_r($doc);

  // $view_fn="function(doc) { if (doc.firstname && doc.lastname) { emit([doc.firstname,doc.lastname], doc); } }";
  // $design_doc = new stdClass();
  // $design_doc->_id = "_design/app1";
  // $design_doc->language = "javascript";
  // $design_doc->views = ["by_name" => ["map" => $view_fn]];
  // $client->storeDoc($design_doc);

// $client = new couchClient($couch_dsn,$couch_db);
// try {
// 	$info = $client->getDatabaseInfos();
// } catch (Exception $e) {
// 	echo "Error:".$e->getMessage()." (errcode=".$e->getCode().")\n";
// 	exit(1);
// }
// //print_r($info);{db}/{docid}
// try {
// 	$doc = $client->getDoc('23798a45a52785b234bb4341ee00027a');
// 	//$doc = $client->findDocuments('bar');
// 	//$doc=$client->getAllDocs();
// 	//$doc=$client->compactAllViews();
// } catch (Exception $e) {
// 	if ( $e->code() == 404 ) {
// 		echo "Document not found\n";
// 	} else {
// 		echo "Error: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
// 	}
// 	//exit(0);
// }
//print_r($doc);


 //*************************creando el view**********************
// $doc->_id = "_design/app1";

// //preparing the views object
// $views = new stdClass();
// $views->{"by-type"} = array (
// 	"map" => "function (doc) {
// 		if ( doc.type ) {
// 			emit (doc.type, null);
// 		}
// 	}"
// );
// $doc->views = $views;
// print_r($doc);
//***********************************************************************

//  $doc = couchDocument::getInstance($client,"_design/app1");
// $views = new stdClass();
// $views->{"by-type"} = array (
// 	"map" => "function (doc) {
// 		if ( doc.type ) {
// 			emit (doc.type, null);
// 		}
// 	}"
// );
// $doc->views = $views;
// print_r($doc);
//print_r($doc);
});
$app->get ('/query/giveall', function (Request $request,Response $response){

$couch_dsn = "http://localhost:5984/";
$couch_db = "pintxopote";
$client = new couchClient($couch_dsn,$couch_db);

//*****************setting a view with tag ="bar"
// $doc = couchDocument::getInstance($client,"_design/app1");
// $views = $doc->views;

// $jsfunc = "function (doc,meta) {
// 	if (  doc.type == \"bar\") {
		
// 			emit('name',doc.name, null);
// 			emit('calle',doc.calle, null);
// 			emit('img',doc.img, null);
// 	}
// }";

// $views->{"by-tag"} = array("map"=>$jsfunc);
// $doc->views = $views;
//*******************************************************
 $doc = couchDocument::getInstance($client,"_design/app1");
 $views = $doc->views;

 $jsfunc = "function(doc, meta)
{
  if (doc.name)
  {
     emit(doc.name.toLowerCase(),null);
  }
}";
$views->{"by-json"} = array("map"=>$jsfunc);
$doc->views = $views;
//**************retriving a view from app1, with name by-tag************
$view = $client->getView("app1","by-json");
print_r($view);


//print_r($doc);
// $doc = new couchDocument($client);
// $doc->_id = "_design/app1";

// //preparing the views object
// $views = new stdClass();
// $views->{"by-type"} = array (
// 	"map" => "function (doc) {
// 		if ( doc.type ) {
// 			emit (doc.type, null);
// 		}
// 	}"
// );

// $doc->views = $views;
// print_r($doc);


});
$app->run();


















///Note you’ll get an error message about “Page Not Found” at this URL - but it’s an error message from Slim, so this is expected. Try http://localhost:8080/hello/joebloggs instead :)
///== \"bar\"
//cd Workspace/PintxoPoteApp/App/src/public/

//php -S localhost:8080
