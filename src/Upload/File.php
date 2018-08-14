<?php

namespace Upload;

/**
 * Class File
 */
class File
{
  /**
   * File name
   */
  private $name;

  /**
   * Temporary name
   */
  private $tmpName;

  /**
   * MIME type
   */
  private $mimeType;

  /**
   * 	full name of local file
   */
  private $localName;

  /**
   * Constructor
   * 
   * @param  string $fieldName $_FILES[] key
   * @throws \Exception
   */
  public function __construct($fieldName, $storage)
  {
    try {
      $this->tmpName = $_FILES[$fieldName]['tmp_name'];
      if (!is_uploaded_file($this->tmpName)) {
        // todo msg
        throw new \Exception('');
      }
      
      if (!file_exists($storage)) {
        if (!mkdir($storage)) {
          // todo msg
          throw new \Exception('');
        }
        chmod($storage, 0777);
      }

      $this->name = uniqid() . basename($_FILES[$fieldName]['name']);
      $this->mimeType = $_FILES[$fieldName]['type'];

      $this->localName = $storage . $this->name;
      if (!move_uploaded_file($this->tmpName, $this->localName)) {
        // todo msg
        throw new \Exception('');
      }
    } catch (\Exception $e) {
      // todo log
      echo $e->getMessage();
    }
  }

  /**
   * Get file name
   * 
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Get full name of local file
   * 
   * @return string
   */
  public function getLocalName()
  {
    return $this->localName;
  }

  /**
   * Get mime type
   * 
   * @return string
   */
  public function getMimeType()
  {
    return $this->mimeType;
  }

}