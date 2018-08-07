<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once 'HTTP/Request2.php';

try {
  $uploadDir = __DIR__ . '/uploadFile/';
  $tmpFile = $_FILES['files']['tmp_name'];
  if (is_uploaded_file($tmpFile)) {
    $fileName = basename($_FILES['files']['name']);
    $fileType = $_FILES['files']['type'];
    $uploadFile = $uploadDir . $fileName;
    if (!move_uploaded_file($tmpFile, $uploadFile)) {
      throw new Exception();
    }
  }

  $dotenv = new Dotenv\Dotenv(__DIR__);
  $dotenv->load();

  $predictionKey = getenv('PredictionKey');
  $iterationId = getenv('iterationId');

  $headers = [
      'Prediction-Key' => $predictionKey,
      'Content-Type'   => 'multipart/form-data'
  ];

  $url = "https://southcentralus.api.cognitive.microsoft.com/customvision/v2.0/Prediction/1277e459-4ccf-40ea-ac1c-49dc3b6a988d/image?iterationId={$iterationId}";

  $request = new HTTP_Request2();
  $request->setHeader($headers);
  $request->setUrl($url);
  $request->setMethod(HTTP_Request2::METHOD_POST);
  $request->addUpload('files', $uploadFile, $fileName, $fileType);

  $response = $request->send();

  $data = json_decode($response->getBody(), true);
  // tag毎のデータを格納
  $data = array_column($data['predictions'], 'probability', 'tagName');

  foreach ($data as $key => $value) {
    // 確率
    $probability = floor($value * 10000) / 100;
    $data[$key] = $probability . '%';
  }
} catch (Exception $e) {
  echo $e->getMessage();
}

?>
