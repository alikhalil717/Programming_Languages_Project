<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\FavoriteService;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    private FavoriteService $favoriteService;
    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }
    public function index(Request $request)
    {
        $result = $this->favoriteService->index($request);
        return response()->json($result, $result['success'] ? 200 : 422);
    }
    public function add(Request $request ,$id)
    {
        $result = $this->favoriteService->add($request ,$id) ;
        return response()->json($result, $result['success'] ? 200 : 422);
    }
    public function remove(Request $request, $id)
    {
        $result = $this->favoriteService->remove($request, $id);
        return response()->json($result, $result['success'] ? 200 : 422);
    }

}
