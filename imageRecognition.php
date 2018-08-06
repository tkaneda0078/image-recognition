<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once 'HTTP/Request2.php';

try {
  $dotenv = new Dotenv\Dotenv(__DIR__);
  $dotenv->load();

  $PredictionKey = getenv('PredictionKey');
  $iterationId = getenv('iterationId');

  $headers = [
      'Prediction-Key' => $PredictionKey,
      'Content-Type'   => 'application/json'
  ];

  $url = "https://southcentralus.api.cognitive.microsoft.com/customvision/v2.0/Prediction/1277e459-4ccf-40ea-ac1c-49dc3b6a988d/url?iterationId={$iterationId}";

  $request = new HTTP_Request2();
  $request->setHeader($headers);
  $request->setUrl($url);
  $request->setMethod(HTTP_Request2::METHOD_POST);
  $request->setBody(json_encode([
      // sample
      'Url' => 'https://i.pinimg.com/736x/0c/44/bd/0c44bd96a66bfc2f1d7303f0611f7740.jpg'
  ]));

  $response = $request->send();
} catch (Exception $e) {
  echo $e->getMessage();
}

?>