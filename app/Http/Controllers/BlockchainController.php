namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Pagination\LengthAwarePaginator;

class BlockchainController extends Controller
{
    public function index()
    {
        // Define the path to the blockdata.json file
        $path = storage_path('blockdata.json');

        // Check if the file exists and is not empty
        if (!File::exists($path) || File::size($path) === 0) {
            // Return an empty message if the file is missing or empty
            return view('blockchain.index')->with('message', 'The file is empty or does not exist.');
        }

        // Read the blockdata.json file and decode its content
        $data = json_decode(File::get($path), true);

        // If the file contains no usable data, return the empty message
        if (empty($data)) {
            return view('blockchain.index')->with('message', 'The file contains no valid data.');
        }

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

        // Check if there are no records after processing
        if (empty($records)) {
            return view('blockchain.index')->with('message', 'No records found in the file.');
        }

        // Paginate the records
        $page = request()->get('page', 1);
        $perPage = 10;
        $paginatedRecords = new LengthAwarePaginator(
            collect($records)->forPage($page, $perPage),
            count($records),
            $perPage,
            $page,
            ['path' => route('blockchain.index')]
        );

        // Return the view with paginated records
        return view('blockchain.index', compact('paginatedRecords'));
    }
}
