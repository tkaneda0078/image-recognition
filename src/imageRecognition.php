<?php

require_once 'HTTP/Request2.php';


/**
 * Custom Vision Service 画像認証
 *
 * Class imageRecognition
 */
class imageRecognition
{
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

  /**
   * @var string
   */
  private $baseUrl = 'https://southcentralus.api.cognitive.microsoft.com/customvision/v2.0/Prediction/1277e459-4ccf-40ea-ac1c-49dc3b6a988d';


  /**
   * Constructor
   *
   */
  public function __construct()
  {
    $this->request = new HTTP_Request2();
  }

  /**
   * set Prediction key
   *
   * @param $predictionKey
   */
  public function setPredictionKey($predictionKey)
  {
    $this->predictionKey = $predictionKey;
  }

  /**
   * Prediction key
   *
   * @return string
   */
  public function getPredictionKey()
  {
    return $this->predictionKey;
  }

  /**
   * set Iteration id
   *
   * @param $iterationId
   */
  public function setIterationId($iterationId)
  {
    $this->iterationId = $iterationId;
  }

  /**
   * Iteration id
   *
   * @return string
   */
  public function getIterationId()
  {
    return $this->iterationId;
  }

  /**
   * 画像認識判定
   *
   * @param array $file  upload file
   * @return array $data
   */
  public function determineRecognition($file)
  {
    try {
      $headers = [
          'Prediction-Key' => $this->getPredictionKey(),
          'Content-Type'   => 'multipart/form-data'
      ];

      $url = $this->baseUrl . "/image?iterationId={$this->getIterationId()}";

      $this->request->setHeader($headers);
      $this->request->setUrl($url);
      $this->request->setMethod(HTTP_Request2::METHOD_POST);
      $this->request->addUpload($file['fieldName'], $file['localName'], $file['fileName'], $file['mimeType']);

      $response = $this->request->send();
      $data = json_decode($response->getBody(), true);

      return $this->formatData($data);

    } catch (Exception $e) {
      // todo log
      echo $e->getMessage();
    }
  }

  /**
   * データを整形する
   *
   * @param array $data
   * @return array $tagData
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
