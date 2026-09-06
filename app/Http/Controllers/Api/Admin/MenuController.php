<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct(private ActivityLogService $logger) {}

    public function index(): JsonResponse
    {
        return response()->json(
            Menu::with('children')->whereNull('parent_id')->orderBy('order')->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'url' => ['required', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:menus,id'],
            'order' => ['integer'],
            'is_active' => ['boolean'],
        ]);

        $menu = Menu::create($data);
        $this->logger->log($request->user()->id, 'created', 'menus', $menu->id);

        return response()->json(['message' => 'Menu created.', 'menu' => $menu], 201);
    }

    public function update(Request $request, Menu $menu): JsonResponse
    {
        $data = $request->validate([
            'label' => ['sometimes', 'string', 'max:100'],
            'url' => ['sometimes', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:menus,id'],
            'order' => ['integer'],
            'is_active' => ['boolean'],
        ]);

        $menu->update($data);
        $this->logger->log($request->user()->id, 'updated', 'menus', $menu->id);

        return response()->json(['message' => 'Menu updated.', 'menu' => $menu]);
    }

    public function destroy(Request $request, Menu $menu): JsonResponse
    {
        $this->logger->log($request->user()->id, 'deleted', 'menus', $menu->id);
        $menu->delete();

        return response()->json(['message' => 'Menu deleted.']);
    }
}
