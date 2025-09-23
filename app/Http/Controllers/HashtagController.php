<?php

namespace App\Http\Controllers;

use App\Models\Hashtag;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HashtagController extends Controller
{
    /**
     * Buscar hashtags para autocomplete
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $hashtags = Hashtag::byName($query)
                          ->byUsage('desc')
                          ->limit(10)
                          ->get(['id', 'name', 'usage_count']);

        return response()->json($hashtags->map(function ($hashtag) {
            return [
                'id' => $hashtag->id,
                'name' => $hashtag->name,
                'label' => $hashtag->name . ' (' . $hashtag->usage_count . ' usos)',
                'value' => $hashtag->name
            ];
        }));
    }

    /**
     * Listar hashtags mais usadas
     */
    public function popular(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 20);
        
        $hashtags = Hashtag::byUsage('desc')
                          ->where('usage_count', '>', 0)
                          ->limit($limit)
                          ->get(['id', 'name', 'usage_count']);

        return response()->json($hashtags);
    }

    /**
     * Estatísticas de hashtags
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total' => Hashtag::count(),
            'used' => Hashtag::where('usage_count', '>', 0)->count(),
            'unused' => Hashtag::where('usage_count', 0)->count(),
            'most_used' => Hashtag::byUsage('desc')->first(['name', 'usage_count'])
        ];

        return response()->json($stats);
    }
}