<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once 'HTTP/Request2.php';

/**
 * 画像認識
 * Class imageRecognition
 */
class imageRecognition
{
  /**
   * @var \Dotenv\Dotenv
   */
  private $dotenv;

  /**
   * @var HTTP_Request2
   */
  private $request;

  /**
   * prediction key
   */
  private $predictionKey;

  /**
   * iteration id
   */
  private $iterationId;


  public function __construct()
  {
    $this->request = new HTTP_Request2();
    $this->dotenv = new Dotenv\Dotenv(__DIR__);
    $this->dotenv->load();
    $this->predictionKey = getenv('PredictionKey');
    $this->iterationId = getenv('iterationId');
  }

  /**
   * 認識判定
   */
  public function determineRecognition()
  {
    try {
      $headers = [
          'Prediction-Key' => $this->predictionKey,
          'Content-Type'   => 'multipart/form-data'
      ];

      $url = "https://southcentralus.api.cognitive.microsoft.com/customvision/v2.0/Prediction/1277e459-4ccf-40ea-ac1c-49dc3b6a988d/image?iterationId={$this->iterationId}";

      $this->request->setHeader($headers);
      $this->request->setUrl($url);
      $this->request->setMethod(HTTP_Request2::METHOD_POST);
      $this->request->addUpload('files', $uploadFile, $fileName, $fileType);

      $response = $this->request->send();
      $data = json_decode($response->getBody(), true);

      return $this->formatData($data);

    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }

  /**
   * データを整形する
   */
  private function formatData($data)
  {
    // tag毎のデータを格納
    $tagsData = array_column($data['predictions'], 'probability', 'tagName');

    foreach ($tagsData as $key => $value) {
      // 確率
      $probability = floor($value * 10000) / 100;
      $tagsData[$key] = $probability . '%';
    }

    return $tagsData;
  }

}

?>
