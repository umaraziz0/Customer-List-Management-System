<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\FollowUp;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Customer::all();
    }

    public function getCustomersByAgent()
    {
        return Customer::where("agent", auth()->user()->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "phone" => "required|string|unique:customers,phone",
            "email" => "required|email|unique:customers,email",
        ]);

        $customer = Customer::create($request->all());

        return response()->json([
            "success" => true,
            "message" => "Customer contact created.",
            "customer" => $customer
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
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
        $request->validate([
            "name" => "required|string",
            "phone" => "required|string|unique:customers,phone," . $customer->id,
            "email" => "required|email|unique:customers,email," . $customer->id,
        ]);

        $customer->update([
            "name" => $request->input("name"),
            "phone" => $request->input("phone"),
            "email" => $request->input("email"),
        ]);

        return response()->json([
            "success" => true,
            "message" => "Customer contact updated.",
            "customer" => $customer
        ], 200);
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
            "success" => true,
            "message" => "Customer contact deleted.",
            "customer" => $customer
        ], 200);
    }

    /**
     * Assign an agent to a customer
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function assignAgent(Request $request, Customer $customer)
    {
        // ! CHECK
        $request->validate([
            "agent_id" => "required|numeric|exists:users,id",
        ]);

        $customer->update([
            "agent" => $request->input("agent_id")
        ]);

        return response()->json([
            "success" => true,
            "message" => "Agent assigned.",
            "customer" => $customer
        ], 200);
    }

    /**
     * Send a follow up message to a customer
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function followUp(Request $request)
    {
        $request->validate([
            "title" => "required|string",
            "message" => "required|string",
            "customer_id" => "required|numeric|exists:customers,id",
        ]);

        $request->request->add(["agent_id" => auth()->user()->id]);

        $followUp = FollowUp::create($request->all());

        // Update customer status
        $customer = Customer::find($request->input("customer_id"));

        if ($customer->status == "uncontacted") {
            $customer->status = "pending";
            $customer->save();
        }

        return response()->json([
            "success" => true,
            "message" => "Customer contact created.",
            "follow_up" => $followUp,
            "status" => $customer->status
        ], 201);
    }

    /**
     * Manually updates customer status
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, Customer $customer)
    {
        $request->validate([
            "status" => "required|string|in:Uncontacted,Pending,Qualified,Lost",
        ]);

        $customer->update([
            "status" => Str::lower($request->input("status"))
        ]);

        return response()->json([
            "success" => true,
            "message" => "Customer contact updated.",
            "customer" => $customer
        ], 200);
    }
}
