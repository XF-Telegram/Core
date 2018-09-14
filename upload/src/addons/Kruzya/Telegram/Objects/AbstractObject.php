<?php
namespace Kruzya\Telegram\Objects;

abstract class AbstractObject {
  private $_int_remappings = null;
  private $_int_classMaps  = null;

  public function __construct() {
    $this->_int_remappings = $this->getRemappings();
    $this->_int_classMaps = $this->getClassMaps();
  }

  /**
   * Magic PHP methods.
   */
  public function __get($key) {
    if ($this->IsValidRemapKey($key)) {
      $proxy_key = $this->_int_remappings[$key];
      return $this->{$proxy_key};
    }
  }

  public function __set($key, $value) {
    if ($this->IsValidRemapKey($key)) {
      $proxy_key = $this->_int_remappings[$key];
      $this->{$proxy_key} = $value;
    }
  }

  public function __isset($key) {
    return $this->IsValidRemapKey($key);
  }

  public function __unset($key) {
    if ($this->IsValidRemapKey($key)) {
      $proxy_key = $this->_int_remappings[$key];
      unset($this->{$proxy_key});
    }
  }

  public function bulkSet($data) {
    foreach ($data as $key => $value) {
      $this->__set($key, $value);
    }
  }

  private function IsValidRemapKey($key) {
    return isset($this->_int_remappings[$key]);
  }

  private function IsValidClassMap($key) {
    return isset($this->_int_classMaps[$key]);
  }

  /**
   * For importing.
   *
   * TODO: add caching for already
   *       created instances.
   * Maybe implement something like
   * "object storage"?
   */
  private function exportData(array $data) {
    foreach ($data as $key => $value) {
      $push_value = $value;
      if ($this->IsValidClassMap($key) && !is_null($value)) {
        $className = $this->_int_classMaps[$key];
        $push_value = $this->classExport($className, $value);
      }

      $this->{$key} = $push_value;
    }
  }

  private function isSeqArray(array $data) {
    if (array() === $data) return false;

    return (array_keys($data) !== range(0, count($data) - 1));
  }

  private function classImport($className, $value) {
    if ($this->isSeqArray($value)) {
      return $this->classSeqImport($className, $value);
    }

    return call_user_method_array([$className, 'export'], [$value]);
  }

  private function classSeqImport($className, $value) {
    if (!$this->isSeqArray($value)) {
      return call_user_method_array([$className, 'export'], [$value]);
    }

    $data = [];
    foreach ($value as $item) {
      $data[] = $this->classSeqImport($className, $item);
    }

    return $data;
  }

  public static function import(array $data) {
    $className = get_called_class();
    $object    = new $className();
    $object->importData($data);

    return $object;
  }

  /**
   * For exporting.
   */
  public function export() {
    $variables = array_keys(get_class_vars(get_called_class()));
    $result = [];

    foreach ($variables as $variable) {
      $item = $this->{$variable};

      if ($this->IsValidClassMap($variable)) {
        $result[] = $item->export();
      } else if (!is_null($item)) {
        $result[] = $item;
      }
    }

    return $result;
  }

  /**
   *   You should reoverride this methods.
   *
   *   getRemappings() returns an array with
   * reoverriding default key names.
   *
   *   getClassMaps() returns an array with
   * class object names for fields.
   */
  protected function getRemappings();
  protected function getClassMaps();
}