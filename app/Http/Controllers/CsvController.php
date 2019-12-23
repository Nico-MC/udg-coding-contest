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
    $counter = 0;

    if (($csv = fopen($request->file('csv'), "r+")) !== FALSE) {
      while(($row = fgetcsv($csv, 0, ";")) !== FALSE) {
        if($counter == 0) {
          $obj = array('categories' => $row, 'products' => []);
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
