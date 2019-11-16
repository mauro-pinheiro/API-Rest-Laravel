<?php

namespace App\Http\Controllers\Api;

use App\API\ApiError;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    public function index(){
        return response()->json($this->product->paginate(10));
    }

    public function show($id){
        $product = $this->product->find($id);

        if(!$product) return response()->json(['data' => ['msg' => 'Produto nao encontrado!']], 404);

        $data = ['data' => $product];
        return response()->json($data);
    }

    public function store(Request $request){
        try {
            $productData = $request->all();
            $this->product->create($productData);
            return response()->jason(['msg'=>'Produto criado com sucesso!',201]);
        } catch (\Exception $e) {
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(),1010));
            }
            return response()->json(ApiError::errorMessage('Houve ao realizar a operação de salvar',1010));
        }
    }

    public function update(Request $request, $id){
        try {
            $productData    = $request->all();
            $product        = $this->product->find($id);
            $this->product->update($productData);
            return response()->jason(['msg'=>'Produto atualizado com sucesso!',201]);
        } catch (\Exception $e) {
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(),1010));
            }
            return response()->json(ApiError::errorMessage('Houve ao realizar a operação de atualizar',1011));
        }
    }

    public function delete(Product $id){
        try {
            $id->delete();
            return response()->json(['data' => ['msg' => 'Produto ' . $id->name . ' removido com sucesso!']],201);
        } catch (\Exception $e) {
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(),1010));
            }
            return response()->json(ApiError::errorMessage('Houve ao realizar a operação de deletar',1012));
        }
    }
}
