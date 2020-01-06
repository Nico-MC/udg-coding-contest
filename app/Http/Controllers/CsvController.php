<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CsvController extends Controller
{
  /**
   * Handle and return uploaded csv file
   *
   * @return \Illuminate\Http\Response
   */
  public function handleCsv(Request $request) {
    // ----- just for testing -----
    // $file = $_FILES['csv']['tmp_name'];
    // $content = file_get_contents($file);
    // print_r($content);
    // ----- just for testing -----

    $counter = 0;
    if (($csv = fopen($request->file('csv'), "r")) !== FALSE) {
      while(($row = fgetcsv($csv, 0, ";")) !== FALSE) {
        if($counter == 0) {
          $obj = array('csvName' => $_FILES['csv']['name'], 'categories' => $row, 'products' => []);
          $counter++;
        } else {
          array_push($obj['products'], $row);
        }
      }
      fclose($csv);
    }

    return json_encode($obj);
  }
}
