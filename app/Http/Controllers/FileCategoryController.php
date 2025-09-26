<?php

namespace App\Http\Controllers;

use App\Models\FileCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FileCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = FileCategory::withCount('files');
        
        // Se não for admin, filtrar apenas categorias do usuário
        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }
        
        $categories = $query->orderBy('name')->paginate(20);
        
        return view('file-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('file-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:file_categories,name',
                'description' => 'nullable|string|max:500',
                'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/'
            ]);

            // Auto-atribuir user_id
            $validated['user_id'] = Auth::id();

            $category = FileCategory::create($validated);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoria criada com sucesso!',
                    'category' => $category
                ]);
            }

            return redirect()->route('file-categories.index')
                ->with('success', 'Categoria criada com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FileCategory $fileCategory)
    {
        // Verificar se o usuário pode editar esta categoria
        if (!Auth::user()->isAdmin() && $fileCategory->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para editar esta categoria.');
        }
        
        return view('file-categories.edit', compact('fileCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FileCategory $fileCategory)
    {
        // Verificar se o usuário pode atualizar esta categoria
        if (!Auth::user()->isAdmin() && $fileCategory->user_id !== Auth::id()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem permissão para editar esta categoria.'
                ], 403);
            }
            abort(403, 'Você não tem permissão para editar esta categoria.');
        }
        
        try {
            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('file_categories', 'name')->ignore($fileCategory->id)
                ],
                'description' => 'nullable|string|max:500',
                'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/'
            ]);

            $fileCategory->update($validated);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoria atualizada com sucesso!',
                    'category' => $fileCategory
                ]);
            }

            return redirect()->route('file-categories.index')
                ->with('success', 'Categoria atualizada com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, FileCategory $fileCategory)
    {
        // Verificar se o usuário pode excluir esta categoria
        if (!Auth::user()->isAdmin() && $fileCategory->user_id !== Auth::id()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem permissão para excluir esta categoria.'
                ], 403);
            }
            abort(403, 'Você não tem permissão para excluir esta categoria.');
        }
        
        // Check if category has files
        if ($fileCategory->files()->count() > 0) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não é possível excluir uma categoria que possui arquivos.'
                ], 422);
            }
            
            return redirect()->route('file-categories.index')
                ->with('error', 'Não é possível excluir uma categoria que possui arquivos.');
        }

        $fileCategory->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Categoria excluída com sucesso!'
            ]);
        }

        return redirect()->route('file-categories.index')
            ->with('success', 'Categoria excluída com sucesso!');
    }

    /**
     * Get categories for API/AJAX calls
     */
    public function api()
    {
        $query = FileCategory::select('id', 'name', 'color');
        
        // Se não for admin, filtrar apenas categorias do usuário
        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }
        
        $categories = $query->orderBy('name')->get();

        return response()->json($categories);
    }
}
