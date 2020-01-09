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
    if (($handle = fopen($request->file('csv'), "r")) !== FALSE) {
      while(($data = fgetcsv($handle, 0, ";")) !== FALSE) {
        if($counter == 0) {
          $arr = array('csvName' => $_FILES['csv']['name'], 'categories' => $data, 'products' => []);
          $counter++;
        } else {
          array_push($arr['products'], $data);
        }
      }
      fclose($handle);
    }

    return json_encode($arr);
  }



  public function exportCsv(Request $request, $delimiter = ';') {
    $csvCategories = json_decode($_POST['csvCategories']);
    $csvContent = json_decode($_POST['csvContent']);
    $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
        ,   'Content-type'        => 'text/csv'
        ,   'Content-Disposition' => 'attachment'
        ,   'Expires'             => '0'
        ,   'Pragma'              => 'public'
    ];

    $callback = function() use ($csvCategories, $csvContent, $delimiter) {
      $file = fopen('php://output', 'w');
      fputcsv($file, $csvCategories, $delimiter);
      foreach ($csvContent as $row) {
        fputcsv($file, $row, $delimiter);
      }
      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }
}
