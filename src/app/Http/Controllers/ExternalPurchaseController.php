<?php

namespace App\Http\Controllers;

use App\Models\ExternalPurchase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ExternalPurchaseController extends Controller
{
    protected Model $model;

    public function __construct(ExternalPurchase $model)
    {
        $this->model = $model;
    }

    public function index()
    {

        $externalPurchases = $this->model->with('ingredient')->get();
        return response()->json([
            'data' => $externalPurchases
        ]);
    }
}
