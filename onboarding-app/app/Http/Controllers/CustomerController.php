<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $customer = Customer::paginate(10);
        $customer = Customer::all();
        // return response()-> json(['data'=>$customer]);
        return CustomerResource::collection($customer);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        $customer = Customer::create([
            'name' => $request->name,
            'order' => $request->order,
            'email' => $request->email
        ]);
        if(!$customer){
            throw new CustomException('failed to create customer', 400);
        }
        return response()->json([
            'data' => $customer
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $request): Response
    {
        return response(Customer::findOrFail($request->id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $customer->name = $request->name;
        $customer->order = $request->order;
        $customer->email = $request->email;
        $customer->save();
        return response()->json([
            'data' => $customer
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json([
            'message' => 'customer deleted'
        ], 204);
    }
}
