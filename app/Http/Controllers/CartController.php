<?php

namespace App\Http\Controllers;

use App\Interfaces\CartServiceInterface;
use App\Models\Cart;
use App\Models\CartProject;
use App\Validation\CartProjectValidated;
use Illuminate\Http\Request;
use App\Http\Resources\CartProjectResource;


class CartController extends BaseApiController
{

    public function __construct(protected CartServiceInterface $cartService)
    {
    }

    public function projects(Request $request, $id)
    {
        $cart = $this->cartService->projects($request, $id);
        if($cart){
            return CartProjectResource::make($cart);
        }
        return $this->return_success(__('cart.cart is empty'));

    }

    public function add(CartProjectValidated $request)
    {
        $cart = $this->cartService->add($request);

        return $this->return_success(__('cart.added_successfully'), $cart);
    }

    public function remove(Request $request)
    {
        $userId = auth()->user()?->id;
        // Get that latest cart for this session
        $projectId = $request->input('project_id');
        if ($userId) {
            $cart = Cart::where('user_id', $userId)->latest()->first();
        } else {
            $cart = Cart::where('session_id', $request->input('id'))->latest()->first();
        }
        if ($cart) {
            $project = CartProject::where('cart_id', $cart->id)
                ->where('project_id', $projectId)
                ->first();
            $project?->delete();
        }

        return response()->json(['message'=>__('cart.removed_successfully')]);
    }
}
