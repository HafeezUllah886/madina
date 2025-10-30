<?php

namespace App\Http\Controllers;

use App\Models\SelfTarget;
use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\categories;
use App\Models\products;
use App\Models\SelfTagetDetails;
use App\Models\SelfTargetDetails;
use App\Models\units;
use App\Models\purchase_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SelfTargetController extends Controller
{
    public function index()
    {
        $targets = SelfTarget::orderBy("endDate", 'desc')->get();
       
        return view('self_target.index', compact('targets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
        $vendors = accounts::vendor()->get();
        $categories = categories::all();
        
        return view('self_target.create', compact('categories', 'vendors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $target = SelfTarget::create(
                [
                    'categoryID'    => $request->categoryID,
                    'targetQty'     => $request->targetQty,
                    'startDate'     => $request->startDate,
                    'endDate'       => $request->endDate,
                    'notes'         => $request->notes,
                ]
            );
            DB::commit();
            return back()->with("success", "Target Saved");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return back()->with("error", $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $target = SelfTarget::find($id);
            
            $products = products::where('catID', $target->categoryID)->get();
            foreach($products as $product)
            {
              $purchase = purchase_details::where('productID', $product->id)->whereBetween('date', [$target->startDate, $target->endDate])->sum('qty');
             
              // Guard against null/zero unit values
              $unitValue = optional($product->unit)->value ?? null;
             
              $product->qty = $purchase;
              $product->packSize = $unitValue;
            }
       
        return view('self_target.view', compact('target', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(targets $targets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, targets $targets)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $target = SelfTarget::find($id);
        $target->details()->delete();
        $target->delete();
        session()->forget('confirmed_password');
        return to_route('self_targets.index')->with("success", "Target Deletes");
    }

    public function getcat($id)
    {
        $category = categories::find($id);
        
        return $category;
    }

}
