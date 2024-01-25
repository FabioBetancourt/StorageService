<?php

namespace App\Http\Controllers;

use App\Models\ExternalPurchase;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IngredientController extends Controller
{
    protected Model $model;

    public function __construct(Ingredient $model)
    {
        $this->model = $model;
    }

    public function store(Request $request)
    {
        $request = $request->all();

        $ingredient = $this->model->create($request);

        return response()->json([
            'data' => $ingredient,
            'message' => 'Ingredient created successfully'
        ]);
    }

    public function index()
    {
        $ingredients = $this->model->all();

        return response()->json([
            'data' => $ingredients
        ]);
    }

    public function show(Ingredient $ingredient)
    {
        $ingredient = Ingredient::find($ingredient);

        if (!$ingredient) {
            return response()->json([
                'message' => 'Ingredient not found'
            ], 404);
        }
        return response()->json([
            'data' => $ingredient
        ]);
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();

        return response()->json([
            'message' => 'Ingredient deleted successfully'
        ]);
    }
    public function updateQuantity(Request $request)
    {
        // Intentar encontrar el ingrediente por ID o por nombre
        $ingredient = null;
        if ($request->has('ingredient_id')) {
            $ingredient = Ingredient::find($request->ingredient_id);
        } elseif ($request->has('name')) {
            $ingredient = Ingredient::where('name', $request->name)->first();
        }

        if (!$ingredient) {
            return response()->json(['message' => 'Ingredient not found'], 404);
        }

        // Intenta cumplir con la solicitud de cantidad
        while ($ingredient->quantity < $request->count) {
            // Intenta obtener más ingredientes de la API externa
            $success = $this->attemptExternalPurchase($ingredient, $request->count);

            if (!$success) {
                sleep(5); // Esperar 5 segundos antes de reintentar
            }
        }
        // Restar la cantidad solicitada y guardar
        $ingredient->quantity -= $request->count;
        $ingredient->save();

        return response()->json([
            'message' => 'Ingredient quantity updated successfully',
            'ingredient' => $ingredient
        ]);
    }

    /**
     * Intenta comprar ingredientes de la API externa y devuelve true si tiene éxito
     */
    private function attemptExternalPurchase($ingredient, $requiredQuantity)
    {
        $response = Http::get('https://recruitment.alegra.com/api/farmers-market/buy', [
            'ingredient' => $ingredient->name
        ]);

        $quantityBought = $response->json()['quantitySold'] ?? 0;

        if ($quantityBought > 0) {
            $ingredient->quantity += $quantityBought;
            $ingredient->save();

            // Crear un registro de la compra
            ExternalPurchase::create([
                'ingredient_id' => $ingredient->id,
                'quantity' => $quantityBought
            ]);
        }

        return $ingredient->quantity >= $requiredQuantity;
    }
}
