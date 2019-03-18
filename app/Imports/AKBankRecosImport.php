<?php

namespace App\Imports;

use App\AKBankReco;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
class AKBankRecosImport implements WithMappedCells,ToModel
{
    private $fileName="";
    // public function __construct($fileName){
    //     $this->fileName=$fileName;
    //     // dd($fileName);

    // }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function mapping(): array
    {
        return [
         '1' => 'D1',     
         '2' => 'E1',     
         '3' => 'C1',
         '4' => 'I1',                
         '5' => 'K1',                 
         '6' => 'N1',                         
         '7' => 'O1',                         
         '8' => 'P1',                             
         '9' => 'Q1'                
        ];
    }
    public function model(array $row)
    {
          $processing_date = ($row['1'] - 25569) * 86400;
          $processing_date= gmdate("Y-m-d", $processing_date);
          $transaction_date = ($row['2'] - 25569) * 86400;
          $transaction_date= gmdate("Y-m-d", $transaction_date);
        return new AKBankReco([
         'processing_date'         => $processing_date,     
         'transaction_date'        => $transaction_date,     
         'merchant_name'           => $row['3'],
         'reference_number'        => $row['4'],                
         'discount_percent'        => (double)$row['5'],                 
         'transaction_amount'      => (double)$row['6'],                         
         'discount_amount'         => (double)$row['7'],                         
         'marchent_settled_amount' => (double)$row['8'],                             
         'transaction_defination'  => $row['9'],                     
         'filename'                => $this->fileName
     ]);
    }
}
