<?php


namespace KosmosKosmos\Rating\Controllers;

use Illuminate\Http\Request;

class RateableController {

    function setRating(Request $request) {
        $model = $request->resource::find($request->resource_id);
        return response()->json(['success' => $model->updateRating($request->rating, $request->category)]);
    }

}
