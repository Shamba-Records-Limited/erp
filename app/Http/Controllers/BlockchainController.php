<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Pagination\LengthAwarePaginator;

class BlockchainController extends Controller
{
    public function index()
    {
        // Read the blockdata.json file from storage
        $path = storage_path('blockdata.json');
        $data = json_decode(File::get($path), true);

        // Normalize data into a consistent structure for easier processing
        $records = [];

        // Handle the root-level data
        if (isset($data['data'], $data['file_id'])) {
            $records[] = [
                'data' => $data['data'],
                'file_id' => $data['file_id']
            ];
        }

        // Handle the indexed entries (e.g., 0, 1, 2, ...)
        foreach ($data as $key => $value) {
            if (is_numeric($key) && isset($value['data'], $value['file_id'])) {
                $records[] = [
                    'data' => $value['data'],
                    'file_id' => $value['file_id']
                ];
            }
        }

        // Paginate records
        $page = request()->get('page', 1);
        $perPage = 10;
        $paginatedRecords = new LengthAwarePaginator(
            collect($records)->forPage($page, $perPage),
            count($records),
            $perPage,
            $page,
            ['path' => route('blockchain.index')]
        );

        return view('blockchain.index', compact('paginatedRecords'));
    }
}
