<?php


namespace Modules\Hotels\Http\Controllers\DashboardController;

use Modules\Hotels\Http\Resources\AttributeTermsResource;
use App\Http\Controllers\Controller;
use Modules\Hotels\Http\Requests\RoomRequest;
use Illuminate\Contracts\Support\Renderable;
use Modules\Hotels\Http\Resources\RoomResource;
use Modules\Hotels\Http\Services\RoomService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class RoomsController extends Controller
{
    protected $roomRepository;

    public function __construct(private RoomService $roomService)
    {
        // $this->middleware('permission:rooms_manager',['only' => ['store','update','destroy','updateTerms']]);
        // $this->middleware('permission:rooms_manager||update_rooms_manager',['only' => ['updatePrice','updateStatus','addMedia','deleteMedia']]);

        $this->middleware('permission:rooms_create',['only' => ['store']]);
        $this->middleware('permission:rooms_update',['only' => ['update', 'updatePrice','updateTerms']]);
        $this->middleware('permission:rooms_delete',['only' => ['destroy']]);
        $this->middleware('permission:update_rooms_price',['only' => ['updatePrice']]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(RoomRequest $request)
    {
        $validatedData = $request->validated();
        DB::beginTransaction();
        $room = $this->roomService->create($validatedData);
        if ($request->file('image') && $request->file('image')->isValid()) {
            $room->addMedia($request->file('image'))->toMediaCollection('thumbnail');
        }
        DB::commit();
        return $this->successResponse(
            $this->resource($room, RoomResource::class),
            'dataAddedSuccessfully'
        );
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($roomId)
    {
        $room = $this->roomService->show($roomId);
        return $this->successResponse(
            $this->resource($room, RoomResource::class),
            'dataFetchedSuccessfully'
        );
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(RoomRequest $request, $roomId)
    {
        $validatedData = $request->validated();
        $room = $this->roomService->update($roomId, $validatedData);
        return $this->successResponse(
            $this->resource($room, RoomResource::class),
            'dataUpdatedSuccessfully'
        );
    }

    public function updatePrice(RoomRequest $request, $roomId)
    {
        $validatedData = $request->validated();
        $room = $this->roomService->updatePrice($roomId, $validatedData);
        return $this->successResponse(
            $this->resource($room, RoomResource::class),
            'dataUpdatedSuccessfully'
        );
    }



    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($roomId)
    {
        $this->roomService->delete($roomId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }

    public function updateStatus($roomId)
    {
        DB::beginTransaction();
        $room = $this->roomService->updateActiveStatus($roomId);
        DB::commit();
        return $this->successResponse(
            $this->resource($room, RoomResource::class),
            'dataUpdatedSuccessfully'
        );
    }

    public function getMedia($roomId)
    {
        $media = $this->roomService->getAllMedia($roomId);
        return $this->successResponse(
            $media,
            'mediaFetchedSuccessfully'
        );
    }


    public function addMedia(Request $request, $roomId)
    {
        DB::beginTransaction();
        $this->roomService->addMedia($roomId, $request);
        DB::commit();
        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }

    public function deleteMedia($roomId, $mediaId)
    {
        $this->roomService->deleteMediaForId($roomId, $mediaId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }

    public function getAttributeTerms($roomId)
    {
        $attributeterms = $this->roomService->getAttributeTermsByRoom($roomId);
        return $this->successResponse(
            $this->resource($attributeterms, AttributeTermsResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function updateTerms(Request $request, $roomId)
    {
        $this->roomService->updateTermsByRoom($request->termsIds, $roomId);
        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }
}
