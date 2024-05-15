<?php

namespace App\Http\Controllers;

use App\Models\ServicePrice;
use Illuminate\Http\Request;

class ServicePriceController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->query('name');

        $items = ServicePrice::when($name, function ($query) use ($name) {
            return $query->where('name', 'like', '%' . addcslashes($name, '%_') . '%');
        })
            ->paginate(15)
            ->withQueryString();

        return response()->json(['items' => $items], 200);
    }

    public function store()
    {
    }

    public function show()
    {
    }

    public function update()
    {
    }

    public function destroy()
    {
    }
}
