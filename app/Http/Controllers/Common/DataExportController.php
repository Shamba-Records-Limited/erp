<?php

namespace App\Http\Controllers\common;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\JsonExcelDataExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\User;
use Spatie\Permission\Models\Role;
use App\MillerWarehouse;
use App\MillerWarehouseAdmin;
use Illuminate\Support\Facades\Auth;


class DataExportController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function exportJsonExcelData(Request $request)
    {
               // Validate that the request contains 'data' and 'headers' as required arrays
               $request->validate([
                'data' => 'required|array',
                'headers' => 'required|array',
            ]);
            // Retrieve the JSON data and headers from the request
            $jsonData = $request->input('data');
            $headers = $request->input('headers');
    
            // Filter the JSON data so that it only includes the key-value pairs corresponding to the headers
            $filteredData = array_map(function ($item) use ($headers) {
                $filteredItem = [];
                foreach ($headers as $header) {
                    // Ensure that both the header and key are treated as strings
                    $header = (string) $header;
    
                    foreach ($item as $key => $value) {
                        // Ensure both the key and header are strings before calling stripos
                        if (stripos((string) $key, $header) !== false) { // case-insensitive partial match
                            // Keep both the key and value if they match
                            $filteredItem[$key] = $value;
                        }
                    }
                }
                return $filteredItem;
            }, $jsonData);
            // Add the headers as the first row of the data
           // array_unshift($filteredData, $headers);  // This will add headers as the first row of the Excel file
            // Define the file name
            $fileName = 'mydata_' . now()->format('Y-m-d') . '.xlsx';
            // Export the filtered data to Excel
            return Excel::download(new JsonExcelDataExport($filteredData), $fileName);
  }

  public function exportPdfData(Request $request)
  {  
    $user = Auth::user();
    $miller_id = null;
    $logo_path='';
    //1. Miller
    if ($user->miller_admin) {
        $miller_id = $user->miller_admin->miller_id;
        $miller = collect(Miller::where("id", $miller_id)->get());
        $logo = $miller->first()->logo ?? null;
    }
    //2.cooperative

    // Validate that the request contains 'data' and 'headers' as required arrays
    $request->validate([
        'data' => 'required|array',
        'headers' => 'required|array',
    ]);
    // Retrieve the JSON data and headers from the request
    $jsonData = $request->input('data');
    $headers = $request->input('headers');
    $title=$request->input('title');
    // Filter the JSON data so that it only includes the key-value pairs corresponding to the headers
        $filteredData = array_map(function ($item) use ($headers) {
        $filteredItem = [];
        $headers = array_map('strval', $headers); // Ensure all headers are treated as strings

        foreach ($item as $key => $value) {
            // Check if the key (string) partially matches any of the headers (case-insensitive)
            foreach ($headers as $header) {
                if (stripos((string) $key, $header) !== false) {
                    // Add the key-value pair to filtered item
                    $filteredItem[$key] = $value;
                    break; // Stop after the first match
                }
            }
        }
        return $filteredItem;
    }, $jsonData);

    $log_path="";

    $columns = $headers;
    $data = [
        'title' => $title,
        'pdf_view' => 'warehouses',
        'records' => $filteredData,
        'filename' => strtolower(preg_replace('/[^A-Za-z0-9_-]/', '', $title) . '' . date('d_m_Y')),
        'orientation' => 'letter',
        'logo' => $log_path,
    ];
    //dd($data);
    return download_pdf2($columns, $data);
}

}