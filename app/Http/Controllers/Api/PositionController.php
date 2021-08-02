<?php

namespace App\Http\Controllers\Api;

use App\Models\Position;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PositionResource;
use App\Http\Resources\PositionCollection;
use App\Http\Requests\PositionStoreRequest;
use App\Http\Requests\PositionUpdateRequest;

class PositionController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Position::class);

        $search = $request->get('search', '');

        $positions = Position::search($search)
            ->latest()
            ->paginate();

        return new PositionCollection($positions);
    }

    /**
     * @param \App\Http\Requests\PositionStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PositionStoreRequest $request)
    {
        $this->authorize('create', Position::class);

        $validated = $request->validated();

        $position = Position::create($validated);

        return new PositionResource($position);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Position $position
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Position $position)
    {
        $this->authorize('view', $position);

        return new PositionResource($position);
    }

    /**
     * @param \App\Http\Requests\PositionUpdateRequest $request
     * @param \App\Models\Position $position
     * @return \Illuminate\Http\Response
     */
    public function update(PositionUpdateRequest $request, Position $position)
    {
        $this->authorize('update', $position);

        $validated = $request->validated();

        $position->update($validated);

        return new PositionResource($position);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Position $position
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Position $position)
    {
        $this->authorize('delete', $position);

        $position->delete();

        return response()->noContent();
    }
}
