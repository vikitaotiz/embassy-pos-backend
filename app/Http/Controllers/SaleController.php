<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Http\Resources\Sales\SaleResource;
use App\Http\Requests\Sales\CreateSale;
use App\Http\Requests\Sales\UpdateSale;
use Carbon\Carbon;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::all();

        return SaleResource::collection($sales);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSale $request)
    {
        $sale = Sale::create([
            'content' => $request->content,
            'payment_mode' => $request->payment_mode,
            'amount' => $request->amount,
            'sold' => $request->sold,
            'user_id' => auth()->user()->id
        ]);

        return new SaleResource($sale);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sale = Sale::findOrFail($id);

        return $sale;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSale $request, $id)
    {
        $sale = Sale::whereId($id)->update([
            'content' => $request->content,
            'payment_mode' => $request->payment_mode,
            'amount' => $request->amount,
            'sold' => $request->sold,
            'user_id' => auth()->user()->id
        ]);

        return $sale;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);

        $sale->delete();

		return response()->json(['message' => 'Sale deleted successfully!']);
    }

    public function cashSalesIn24hrs(){
        $sales = Sale::where('payment_mode', '=', 'cash')
                     ->where('created_at', '>=', Carbon::now()->subDay())->get();
        return SaleResource::collection($sales);
    }

    public function mpesaSalesIn24hrs(){
        $sales = Sale::where('payment_mode', '=', 'mpesa')
                     ->where('created_at', '>=', Carbon::now()->subDay())->get();
        return SaleResource::collection($sales);
    }

    public function cardSalesIn24hrs(){
        $sales = Sale::where('payment_mode', '=', 'card')
                     ->where('created_at', '>=', Carbon::now()->subDay())->get();
        return SaleResource::collection($sales);
    }
}
