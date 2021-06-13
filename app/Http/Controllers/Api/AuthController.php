<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Type;
use Psy\Util\Str;
use function GuzzleHttp\Psr7\str;
use function Sodium\add;

class AuthController extends Controller
{


    public function register(RegisterRequest $request){
        $user = \App\Models\User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'balance' => $request->get('balance'),
        ]);
        return $user;
    }


    public function login(LoginRequest $request) {
        if (!auth()->attempt($request->all())) {
            return response(['error_messages' => 'Incorrect credentials, try again']);
        }
        $user = auth()->user();

        $token = $user->createToken('Api Token')->accessToken;
        return response(['user' => auth()->user(),'token' => $token]);
    }


    public function categories() {
        $categorys = \App\Models\Category::all();
        foreach ($categorys as $category) {
            $list1 = [];
            foreach ($category->products as $product) {
                array_push($list1, $product->name);
            }

            $ans = [$category->name => $list1];
            echo json_encode($ans);
        }
    }





    public function purchase($id,$number) {
        $user = auth()->user();
        $product = Product::findorfail($id);
        if ($product->stock >= $number) {
            if ($user->balance >= $number*$product->price){

                $order = Order::create([
                    'order_unique_code' => 3,
                    'paid_amount' => $number*$product->price,
                    'user_id' => $user->id
                ]);

                $order->products()->sync($product->id);

                $product->stock = $product->stock-$number;
                $product->save();

                $user->balance = $user->balance - $product->price * $number;
                $user->save();

                return 'successful';


            }

            else
            {
                return 'balansze sakmarisi tanxa araris';
            }

        }

        else
        {
            return 'produqti araris sakmarisi raodenobis';
        }
    }


    public function orders() {
        $unorders = new Order();
        $orders = $unorders->userid(auth()->user()->id);
        foreach ($orders as $order) {
            echo  $order;
        }
    }


}
