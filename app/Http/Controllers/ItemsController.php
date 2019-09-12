<?php

namespace App\Http\Controllers;

use App\Item;
use App\Http\Requests\CreateItemRequest;
use App\Http\Requests\SearchItemRequest;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SearchItemRequest $request)
    {
        $items = $request->has('search') ? Item::searchByQuery(['match' => ['title' => $request->input('search')]]) : Item::all();

        return response()->json([
            'data' => $items
        ]);
    }

    /**
     * Store a newly created resource in storage
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateItemRequest $request)
    {
        $item = Item::create($request->all());
        $item->addToIndex();

        return response()->json([
            'data' => $item
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Item  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $response = $item->delete();
        $item->removeFromIndex();

        return response()->json(['success' => $response ? true : false]);
    }
}


