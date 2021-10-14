<?php
class ControllerExtensionFeedSolviCsvGenerator extends Controller {
  private $feed_filepath = '';
  private $host = 'www.frpneu.cz';
  private $protocol = 'https';
  private $save_folder = 'feed';
  private $save_filename = 'example';
  private $save_link = '';
  private $fields = 'link,brand,gtin';
  private $fields_arr = [];
  private $headers_arr = [];

  public function index() {

    if (empty($this->request->get['feed'])) {
      $this->error('Please, send me feed filepath in "feed" get-param.');
    }

    $this->feed_filepath = $this->request->get['feed'];

    if (!empty($this->request->get['fields'])) {
      $this->fields = $this->request->get['fields'];
    }

    $fields = explode(',', $this->fields);

    foreach ($fields as $field) {
      if (strpos($field, ':')) {
        $names = explode(':', $field);
        $name = isset($names[1]) ? $names[1] : $field;
        $field = $names[0];
        $this->headers_arr[] = $name;
      } else {
        $this->headers_arr[] = $field;
      }

      $this->fields_arr[] = trim('google_' . $field);
    }

    if (!empty($this->request->get['host'])) {
      $this->host = $this->request->get['host'];
    } else {
      $this->host = $_SERVER['HTTP_HOST'];
    }

    if (!empty($this->request->get['protocol'])) {
      $this->protocol = $this->request->get['protocol'];
    }

    if (!empty($this->request->get['save_folder'])) {
      $this->save_folder = $this->request->get['save_folder'];
    }

    if (!empty($this->request->get['save_filename'])) {
      $this->save_filename = $this->request->get['save_filename'];
    }

    $full_xml_path = $this->protocol . '://' . $this->host . '/' . $this->feed_filepath;

    $xml = @file_get_contents($full_xml_path);

    if (!$xml) {
      $this->error('XML file not found in "' . $full_xml_path . '"!');
    }

    $save_folder = DIR_APPLICATION . '../csv/' . $this->save_folder;
    $save_filepath = $save_folder . '/' . $this->save_filename . '.csv';

    $this->save_link = $_SERVER['HTTP_HOST'] . '/csv/' . $this->save_folder . '/' . $this->save_filename . '.csv';

    if (!is_dir($save_folder)) {
      mkdir($save_folder);
    }

    $this->convertXMLtoCSV($xml, $save_filepath);

  }

  private function convertXMLtoCSV($xml, $save_filepath) {

    if (!function_exists('simplexml_load_string')) {
      $this->error('Function "simplexml_load_string" does not exist! Check your PHP settings.');
    }

    if (file_exists($save_filepath)) {
      unlink($save_filepath);
    }

    $xml = str_replace('g:', 'google_', $xml);
    $simplexml = simplexml_load_string($xml);

    $file = fopen($save_filepath, 'w');

    if ( fputcsv($file, $this->headers_arr, ';', '"') === FALSE ) {
      fclose($file);
      $this->error('File "' . $csv_filepath . '" cant be written.');
    }

    $this->putXMLtoCSVrow($simplexml, $file);
    fclose($file);

    echo '<a href="' . $this->save_link . '">' . $this->save_link . '</a>';

  }

  private function putXMLtoCSVrow($simplexml, $file) {
    foreach ( $simplexml->children() as $item )
    {
      if ($item->getName() == 'entry')
      {
        $put_arr = $this->getEntryXMLtoArray($item);
        fputcsv($file, $put_arr, ';', '"');
      }
    }
  }

  private function getEntryXMLtoArray($simplexml)
  {
    $put_arr = [];

    foreach ( $this->fields_arr as $field ) {
      $res = '';

      foreach ( $simplexml->children() as $item )
      {
        if( trim($item->getName()) == $field )
        {
          $res = $item;
          break;
        }
      }

      $put_arr[] = $res;
    }

    return $put_arr;
  }

  private function putEntryXMLtoCSVrow($simplexml, $file) {
    $put_arr = [];

    foreach ( $simplexml->children() as $item )
    {
      $hasChild = count($item->children()) > 0;

      if( !$hasChild && in_array( trim($item->getName()), $this->fields_arr ) )
      {
        $put_arr[] = $item;
        fputcsv($file, $put_arr, ',', '"');
      }
    }
  }

  private function error($text)
  {
    die( 'Error: ' . $text );
  }

}