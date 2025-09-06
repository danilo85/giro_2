<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestUploadController
{
    public function upload(Request $request)
    {
        try {
            // Test with minimal validation
            $request->validate([
                'transaction_id' => 'required'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Test upload working with validation',
                'data' => [
                    'method' => $request->method(),
                    'has_file' => $request->hasFile('file'),
                    'transaction_id' => $request->input('transaction_id')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . $e->getMessage(),
                'error' => $e->getTraceAsString()
            ], 422);
        }
    }
}