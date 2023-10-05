<?php


namespace Modules\Hotels\Http\Controllers\DashboardController;

use App\Http\Controllers\Controller;
use Modules\Hotels\Http\Requests\HotelRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Hotels\RepositoryInterface\HotelRepositoryInterface;
use Modules\Hotels\Http\Resources\HotelResource;
use Modules\Hotels\Http\Resources\AttributeTermsResource;
use Modules\Hotels\Http\Resources\RoomResource;
use Modules\Hotels\Http\Services\HotelService;

class HotelsController extends Controller
{
    protected $hotelRepository;

    public function __construct(HotelRepositoryInterface $hotelRepository, private HotelService $hotelService)
    {
        if(Auth::guard('user')->user()) {
            $this->middleware('permission:hotels_get',['only' => ['index']]);
            $this->middleware('permission:rooms_get', ['only' => ['getRooms']]);
        }

        $this->middleware('permission:hotels_create',['only' => ['store']]);
        $this->middleware('permission:hotels_update',['only' => ['update', 'updateTerms']]);
        $this->middleware('permission:hotels_is_featured',['only' => ['updateFeatured']]);
        $this->middleware('permission:hotels_delete',['only' => ['destroy']]);

        // $this->middleware('permission:hotels_manager|update_rooms_manager',['only' => ['updateStatus','addMedia','deleteMedia']]);

        // $this->middleware('permission:rooms_manager', ['only' => ['getRooms']]);
        $this->hotelRepository = $hotelRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $hotels = $this->hotelService->getHotels($request);
        return $this->successResponse(
            $this->resource($hotels, HotelResource::class),
            'dataFetchedSuccessfully'
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(HotelRequest $request)
    {
        $validatedData = $request->validated();
        $hotel = $this->hotelService->createHotel($validatedData);
        if ($request->file('image') && $request->file('image')->isValid()) {
            $hotel->addMedia($request->file('image'))->toMediaCollection('thumbnail');
        }
        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($hotelId)
    {
        //check policies for show hotel by user

        $hotel = $this->hotelService->showHotelDetails($hotelId);
        return $this->successResponse(
            $this->resource($hotel, HotelResource::class),
            'dataFetchedSuccessfully'
        );
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(HotelRequest $request, $hotelId)
    {
        $validatedData = $request->validated();
        $hotel = $this->hotelService->updateHotelDetails($hotelId, $validatedData);
        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($hotelId)
    {
        $this->hotelService->deleteHotel($hotelId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
    public function getNearestHotel(HotelRequest $request)
    {
        $hotel = $this->hotelService->getNearestHotel($request);
        return $this->successResponse(
            $this->resource($hotel, HotelResource::class),
            'dataUpdatedSuccessfully'
        );
    }

    public function updateStatus($id)
    {
        DB::beginTransaction();
        $hotel = $this->hotelRepository->find($id);
        $hotel->updateStatus();
        DB::commit();
        return $this->successResponse(
            $this->resource($hotel, HotelResource::class),
            'dataUpdatedSuccessfully'
        );
    }


    public function updateFeatured($id)
    {
        DB::beginTransaction();
        $hotel = $this->hotelRepository->find($id);
        $hotel->updateFeatured();
        DB::commit();
        return $this->successResponse(
            $this->resource($hotel, HotelResource::class),
            'dataUpdatedSuccessfully'
        );
    }


    public function getMedia($id)
    {
        $media = $this->hotelService->getAllMedia($id);
        return $this->successResponse(
            $media,
            'mediaFetchedSuccessfully'
        );
    }


    public function addMedia(Request $request, $id)
    {
        DB::beginTransaction();

        if ($request->file('media') && $request->file('media')->isValid()) {
            $this->hotelRepository->createMedia($id, $request->file('media'), $request->type);
        }
        DB::commit();
        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }

    public function deleteMedia($hotelId, $mediaId)
    {
        $this->hotelRepository->deleteMediaForId($hotelId, $mediaId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }


    public function getRooms($hotelId)
    {

        $rooms = $this->hotelService->getRooms($hotelId);
        return $this->successResponse(
            $this->resource($rooms, RoomResource::class),
            'dataFetchedSuccessfully'
        );
    }


    public function getAttributeTerms($hotelId)
    {
        $attributeterms = $this->hotelService->getAttributeTermsByHotel($hotelId);
        return $this->successResponse(
            $this->resource($attributeterms, AttributeTermsResource::class),
            'dataFetchedSuccessfully'
        );
    }


    public function updateTerms(Request $request, $hotelId)
    {
        $this->hotelService->updateTermsByhotel($request->termsIds, $hotelId);
        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }


    public function search(Request $request)
    {
        //$this->hotelService->search($request->search_type);
        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function getHotelsAndRooms(Request $request)
    {
        $hotels = $this->hotelService->getHotelsAndRooms($request);
        return $this->successResponse(
            $this->resource($hotels, HotelResource::class),
            'dataFetchedSuccessfully'
        );
    }
}
