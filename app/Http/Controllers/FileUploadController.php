<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\AKBankRecosImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\AKBankReco;
use App\AKCityReco;
use App\AkCityBankRecoLog;
use App\Log;

class FileUploadController extends Controller
{
    use Log;

    public function index()
    {

        $data['title'] = "Files Upload";
        return view('fileupload.index', compact($data));
    }

    public function uploadExcel(Request $request)
    {
        // return 'bb';
        try {
            $fileName = $request->file('file')->getClientOriginalName() . "";
            $a = AKBankReco::where('filename', '=', $fileName);
            if ($a->count()) {
                $this->log('EBL File Upload', $fileName, "File Already Exist", new AKBankReco());
                return json_encode([0, $fileName, "File Already Exist"]);
            }

            $request->file->move(public_path('/files'), $fileName);
            // $path=
            // Request::has('')
            $data = Excel::toArray(new AKBankRecosImport, 'files/' . $fileName, 'public');
            $x = 0;
            foreach ($data[0] as $key => $row) {

                if ($x === 0) {
                    $x++;
                    continue;
                }

                $x++;

                $processing_date = ($row[3] - 25569) * 86400;
                $processing_date = gmdate("Y-m-d", $processing_date);
                $transaction_date = ($row[4] - 25569) * 86400;
                $transaction_date = gmdate("Y-m-d", $transaction_date);

                $a = new AKBankReco();

                $a->processing_date = $processing_date;
                $a->transaction_date = $transaction_date;
                $a->merchant_name = $row[2];
                $a->reference_number = $row[8];
                $a->discount_percent = (double)$row[10];
                $a->transaction_amount = (double)$row[13];
                $a->discount_amount = (double)$row[14];
                $a->marchent_settled_amount = (double)$row[15];
                $a->transaction_defination = $row[16];
                $a->filename = $fileName;
                $a->save();
            }
            unlink(public_path('files/' . $fileName));
            // return "bsb";
            $this->log('EBL File Upload', $fileName, "Uploaded", $a);
            return json_encode([$x, $fileName, "Uploaded"]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }//end of uploadExcel

    function validateDate($value)
    {
        if (!$value) {
            return false;
        }

        try {
            return date_format((date_create_from_format('d/m/Y', $value)), 'Y-m-d');
        } catch (\Exception $e) {
            return false;
        }
    }//end of validationDate

    function uploadWord(Request $request)
    {
        try {
            $fileName = $request->file('file')->getClientOriginalName();
            $checkFileAlreadyExixt = AKCityReco::where('filename', '=', $fileName);
            if ($checkFileAlreadyExixt->count()) {
                $this->log('City Bank File Upload', $fileName, "File Already Exist", new AKCityReco());
                return json_encode([0, $fileName, 'File Already Exist']);
            }
            $striped_content = '';
            $content = '';
            $x = 0;
            $request->file->move(public_path('/files'), $fileName);
            $zip = zip_open(public_path('/files/' . $fileName));

            if (!$zip || is_numeric($zip)) return json_encode([0, 'File Format Problem', 'File Format Problem']);;

            while ($zip_entry = zip_read($zip)) {

                if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

                if (zip_entry_name($zip_entry) != "word/document.xml") continue;

                $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

                zip_entry_close($zip_entry);
            }// end while

            zip_close($zip);
            $cnt = 0;
            $result = [];
            $result2 = [];
            $terminalName = "";
            $lastTerminalName = false;
            $numberOfTransaction = 0;
            $sumOfAmount = 0;
            $start = 0;
            $commission = 0;
            $payable = 0;
            $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
            $content = str_replace('</w:r></w:p>', "\r\n", $content);
            $content = str_replace('-', "", $content);
            $content = str_replace('=', "", $content);
            $striped_content = strip_tags($content);
            $lines = explode(chr(0x0D), $striped_content);
            foreach ($lines as $key => $value) {
                if (strpos($value, 'Terminal Name')) {
                    $terminalName = str_replace('Terminal Name:', "", $value);
                    $terminalName = str_replace("\n", "", $terminalName);
                    if ($start === 0) $lastTerminalName = $terminalName;
                    $start = 1;
                }
                $data = preg_split('/\s+/', $value);
                $title = "";
                if (isset($result[count($data)]))
                    $result[count($data)] += 1;
                else $result[count($data)] = 1;
                if (count($data) == 12 && $data[1] !== "Other") {
                    $data[0] = $terminalName;
                    $txn_type = 1;
                    if ($data[8] === 'Dr') $txn_type = -1;

                    if ($lastTerminalName !== $terminalName) {

                        // $result2[]=array($sumOfAmount,$commission,$payable,$x,$lastTerminalName,$fileName);
                        if ($x > 0) {
                            AkCityBankRecoLog::create([
                                'terminal_name' => $lastTerminalName,
                                'txn_amount' => $sumOfAmount,
                                'comission' => $commission,
                                'payable_amount' => $payable,
                                'count' => $x,
                                'fileName' => $fileName
                            ]);
                        }
                        $sumOfAmount = 0;
                        $lastTerminalName = $terminalName;
                        $x = 0;
                        $commission = $payable = 0;
                    }
                    if ($this->validateDate($data[3]) && $this->validateDate($data[4]) && $this->validateDate($data[5])) {

                        $settlement_date = $this->validateDate($data[3]);
                        $posting_date = $this->validateDate($data[4]);
                        $transaction_date = $this->validateDate($data[5]);


                        $x++;
                        $cnt++;
                        AKCityReco::create([
                            'terminal_name' => $data[0],
                            'terminal_id' => $data[1],
                            'card_no' => $data[2],
                            'settlement_date' => $settlement_date,
                            'posting_date' => $posting_date,
                            'transaction_date' => $transaction_date,
                            'appr_code' => $data[6],
                            'batch_no' => $data[7],
                            'txn_type' => $data[8],
                            'txn_amount' => $txn_type * (double)str_replace(',', "", $data[9]),
                            'commission' => $txn_type * (double)str_replace(',', "", $data[10]),
                            'payable_amount' => $txn_type * (double)str_replace(',', "", $data[11]),
                            'filename' => $fileName
                        ]);
                        $sumOfAmount += $txn_type * (double)str_replace(',', "", $data[9]);
                        $commission += $txn_type * (double)str_replace(',', "", $data[10]);
                        $payable += $txn_type * (double)str_replace(',', "", $data[11]);
                    }

                }
            }
            // $result2[]=array($sumOfAmount,$commission,$payable,$x,$lastTerminalName,$fileName);
            if ($x > 0) {
                AkCityBankRecoLog::create([
                    'terminal_name' => $lastTerminalName,
                    'txn_amount' => $sumOfAmount,
                    'comission' => $commission,
                    'payable_amount' => $payable,
                    'count' => $x,
                    'fileName' => $fileName
                ]);
            }
            unlink(public_path('files/' . $fileName));
            $this->log('City Bank File Upload', $fileName, "Uploaded", new AKCityReco());
            return json_encode([$cnt, $fileName, 'Uploaded']);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            unlink(public_path('files/' . $fileName));
            return json_encode([0, 'File Format  not Match', 'Not Uploaded']);
        }
    }//end of uploadWord
}