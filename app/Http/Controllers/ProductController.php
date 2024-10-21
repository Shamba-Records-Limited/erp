<?php

namespace App\Http\Controllers;

use App\Category;
use App\Events\AuditTrailEvent;
use App\Exports\FarmerProductSupplyExport;
use App\Exports\ProductExport;
use App\Exports\ProductSuppliersExport;
use App\Farmer;
use App\Product;
use App\Route;
use App\Unit;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

use function PHPSTORM_META\type;

class ProductController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $coop = Auth::user()->cooperative_id;
        $products = Product::get_products($coop, $request, 100);
        $categories = Category::where('cooperative_id', $coop)->latest()->get();
        $units = Unit::where('cooperative_id', $coop)->latest()->get();
        return view('pages.cooperative.product.index', compact('products', 'units', 'categories'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "name" => "required|string",
            "mode" => "sometimes|string",
            "sale_price" => "required|regex:/^\d+(\.\d{1,2})?$/",
            "buying_price" => "required|regex:/^\d+(\.\d{1,2})?$/",
            "vat" => "required|regex:/^\d+(\.\d{1,2})?$/",
            "category" => "required|string",
            "unit" => "required|string",
            "serial_number" => "required|string|unique:products",
            "image" => "sometimes|file|max:3072",
            "threshold" => 'required|regex:/^\d+(\.\d{1,2})?$/|min:1'
        ]);
        $user = Auth::user();
        $product = new Product();
        $this->persist($product, $request, $user);
        toastr()->success('Product created successfully');
        $data = ['user_id' => $user->id, 'activity' => 'created  ' . $request->name . ' Product', 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($data));
        return redirect()->route('cooperative.products.show');
    }

    private function persist(Product $product, $request, User $user, $isEdit = false)
    {
        $product->name = $request->name;
        $product->cooperative_id = $user->cooperative->id;
        $product->sale_price = $request->sale_price;
        $product->buying_price = $request->buying_price;
        $product->category_id = $request->category;
        $product->unit_id = $request->unit;
        $product->mode = $request->mode;
        $product->vat = $request->vat;
        $product->threshold = $request->threshold;
        if ($isEdit) {
            $product->image = $request->has('image') ?
                store_image($request, "image", $request->image, "images/products", 200, 200) :
                $product->image;
        } else {
            $product->serial_number = $request->serial_number;
            $product->image = store_image($request, "image", $request->image, "images/products", 200, 200);
        }

        $product->save();
    }

    public function download_products($type, Request $request)
    {
        if ($request->request_data == '[]') {
            $request = null;
        } else {
            $request = json_decode($request->request_data);
        }
        $cooperative = Auth::user()->cooperative->id;
        $products = Product::get_products($cooperative, $request, null);
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('products_' . date('d') . '_' . date('m') . '_' . date('Y')) . '.' . $type;
            return Excel::download(new ProductExport($products), $file_name);
        } else {
            $data = [
                'title' => 'RegisteredProducts',
                'pdf_view' => 'registered_products',
                'records' => $products,
                'filename' => strtolower('products_' . date('d') . '_' . date('m') . '_' . date('Y')),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function get_suppliers(Request $request)
    {
        $coop = Auth::user()->cooperative_id;
        $farmers = Farmer::get_farmers($coop,$request, 100);
        $routes = Route::select(['id', 'name'])->where('cooperative_id', $coop)->orderBy('name')->get();
        return view('pages.cooperative.product.suppliers', compact('farmers', 'routes'));
    }

    public function download_products_suppliers($type, Request $request)
    {
        if ($request->request_data == '[]') {
            $request = null;
        } else {
            $request = json_decode($request->request_data);
        }

        $cooperative = Auth::user()->cooperative->id;
        $farmers = Farmer::get_farmers($cooperative,$request, null);
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('products_suppliers' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new ProductSuppliersExport($farmers), $file_name);
        } else {
            $data = [
                'title' => 'Products Suppliers',
                'pdf_view' => 'products_suppliers',
                'records' => $farmers,
                'filename' => strtolower('products_suppliers' . date('d_m_Y')),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }


    public function get_farmer_products($userId)
    {
        $user = User::findOrFail($userId);
        $farmerId = $user->farmer->id;
        $products = Product::farmer_products($userId, $farmerId);
        $product_ids = $user->products->pluck('id');
        $names = ucwords(strtolower($user->first_name . ' ' . $user->other_names));
        $new_products = Product::select(['name', 'id'])
            ->where('cooperative_id', $user->cooperative_id)
            ->whereNotIn('id', $product_ids)
            ->latest()->limit(100)->get();
        return view('pages.cooperative.product.supplier-products',
            compact('products', 'userId', 'names', 'new_products'));
    }

    public function download_farmer_products($userId, $type)
    {
        $user = User::findOrFail($userId);
        $farmerId = $user->farmer->id;
        $products = Product::farmer_products($userId, $farmerId);
        $file_name_prefix = strtolower(
                str_replace(' ', '_', $user->first_name . '_' . $user->other_names)) . '_products_supply';

        if ($type != env('PDF_FORMAT')) {
            $file_name = $file_name_prefix . '.' . $type;
            return Excel::download(new FarmerProductSupplyExport($products), $file_name);
        } else {
            $data = [
                'title' => ucwords(strtolower($user->first_name . ' ' . $user->other_names)) . ' Products',
                'pdf_view' => 'farmer_product',
                'records' => $products,
                'filename' => $file_name_prefix,
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function add_products_to_farmer(Request $request, $farmerId)
    {
        $this->validate($request, [
            'products' => 'required'
        ]);
        $user = Auth::user();

        $farmer = User::findOrFail($farmerId);
        $farmer->products()->attach($request->products);
        $farmer->save();

        $data = [
            'user_id' => $user->id,
            'activity' => 'Added products to :  ' . $farmer->first_name . ' ' . $farmer->other_names,
            'cooperative_id' => $user->cooperative->id
        ];
        event(new AuditTrailEvent($data));
        toastr()->success('Products added');
        return redirect()->back();
    }

    public function edit(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $product = Product::findOrFail($id);
        $this->validate($request, [
            "name" => "required|string",
            "mode" => "sometimes|string",
            "sale_price" => "required|regex:/^\d+(\.\d{1,2})?$/",
            "buying_price" => "required|regex:/^\d+(\.\d{1,2})?$/",
            "vat" => "required|regex:/^\d+(\.\d{1,2})?$/",
            "category" => "required|string",
            "unit" => "required|string",
            "image" => "sometimes|file|max:3072",
            "threshold" => 'required|regex:/^\d+(\.\d{1,2})?$/|min:1'
        ]);

        $user = Auth::user();
        $this->persist($product, $request, $user, true);
        toastr()->success('Cooperative Created Successfully');
        $data = ['user_id' => $user->id, 'activity' => 'Updated  product:  ' . $product->id, 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($data));
        return redirect()->route('cooperative.products.show');
    }

}
