<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Resources\LinkShowResource;
use App\Models\Link;
use Illuminate\Http\Request;

class LinkController
{
    public function show(Link $link){
        return new LinkShowResource($link);
    }
}
