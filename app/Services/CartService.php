<?php

namespace App\Services;

use App\Interfaces\CartServiceInterface;
use App\Models\Cart;
use App\Models\CartProject;
use App\Models\Link;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartService implements CartServiceInterface
{
    public function __construct(protected Cart $cart)
    {
    }


    public function projects($request, $id)
    {
        return Cart::where('user_id', $id)->orWhere('session_id', $id)->with('cartProjects')
            ->first();

    }

    public function add($request)
    {
        $userId = auth()->user()?->id;
        $project = Project::findOrFail($request->project_id);
        $cart = $this->findOrCreateCart($userId, $request->user_session ?? $request->session_id);
        $data = $request->only([
            'gifted_to_email',
            'gifted_to_phone',
            'gifted_to_name',
            'code',
            'recurring',
            'donor_comment'
        ]);
        return $this->createOrUpdateCartProject($cart, $project, $request->amount, $data);
    }

    private function findOrCreateCart($userId, $sessionId)
    {
        if ($userId) {
            return Cart::where('user_id', $userId)->firstOrCreate(['user_id' => $userId]);
        } elseif ($sessionId) {
            return Cart::where('session_id', $sessionId)->firstOrCreate(['session_id' => $sessionId]);
        } else {
            throw ValidationException::withMessages(['session_id' => ['session_id is required if user is not loged in']]);
        }
    }

    public function findProjectInCart($cart, $projectId)
    {
        return CartProject::where(['project_id' => $projectId, 'cart_id' => $cart->id])->first();
    }

    public function createOrUpdateCartProject($cart, $project, $amount, $data = [])
    {
        $existingCartProject = $this->findProjectInCart($cart, $project->id);

        if ($existingCartProject) {
            $existingCartProject->update(['amount' => $amount]);
                $amounts = CartProject::where('cart_id',$cart->id)->sum('amount');
                Cart::find($cart->id)->update(['total_amount' => $amounts]);
            return $existingCartProject;
            
        } else {
            $data2 = [
                'cart_id' => $cart->id,
                'project_id' => $project->id,
                'amount' => $amount,
                'recurring' => $data['recurring'] ?? null,
                'gifted_to_email' => $data['gifted_to_email'] ?? null,
                'gifted_to_phone' => $data['gifted_to_phone'] ?? null,
                'gifted_to_name' => $data['gifted_to_name'] ?? null,
                'donor_comment' => $data['donor_comment'] ?? null,
            ];
//            if($project->is_gifted) {
//                $data2['gifted_to_email'] = $data['gifted_to_email'];
//                $data2['gifted_to_phone'] = $data['gifted_to_phone'];
//                $data2['gifted_to_name'] = $data['gifted_to_name'];
//            }
            if (isset($data['code'])) {
                Link::where('code', $data['code'])->update(
                    [
                        'amount' => DB::raw('amount + ' . $amount),
                    ]
                );
            }

            $cartProjects =  CartProject::create($data2);
            $amounts = CartProject::where('cart_id',$cart->id)->sum('amount');
            Cart::find($cart->id)->update(['total_amount' => $amounts]);
            return $cartProjects;
        }
    }
}
