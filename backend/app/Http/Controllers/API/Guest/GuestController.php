<?php

namespace App\Http\Controllers\API\Guest;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\News;
use App\Models\Restaurant;
use App\Models\RestaurantMenuItem;
use App\Models\Room;
use App\Models\Service;
use App\Models\SpaProcedure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function hotels(Request $request): JsonResponse
    {
        $hotels = Hotel::query()
            ->active()
            ->with(['settings', 'roomCategories', 'services' => function ($query) {
                $query->active();
            }])
            ->when($request->filled('city'), fn ($query) => $query->where('city', $request->string('city')))
            ->get();

        return response()->json($hotels);
    }

    public function rooms(Request $request, Hotel $hotel): JsonResponse
    {
        $rooms = Room::query()
            ->where('hotel_id', $hotel->id)
            ->with(['category'])
            ->when($request->filled('available'), fn ($query) => $query->where('is_available', $request->boolean('available')))
            ->get();

        return response()->json([
            'hotel' => $hotel,
            'rooms' => $rooms,
        ]);
    }

    public function services(Hotel $hotel): JsonResponse
    {
        $services = Service::query()
            ->where('hotel_id', $hotel->id)
            ->active()
            ->with('category')
            ->get();

        return response()->json($services);
    }

    public function spa(Hotel $hotel): JsonResponse
    {
        $procedures = SpaProcedure::query()
            ->where('hotel_id', $hotel->id)
            ->with('schedule')
            ->get();

        return response()->json($procedures);
    }

    public function restaurants(Hotel $hotel): JsonResponse
    {
        $restaurants = Restaurant::query()
            ->where('hotel_id', $hotel->id)
            ->with(['menuItems' => function ($query) {
                $query->where('is_available', true);
            }])
            ->get();

        return response()->json($restaurants);
    }

    public function menu(Restaurant $restaurant): JsonResponse
    {
        $items = RestaurantMenuItem::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('is_available', true)
            ->orderBy('category')
            ->get();

        return response()->json([
            'restaurant' => $restaurant,
            'items' => $items,
        ]);
    }

    public function map(Request $request): JsonResponse
    {
        $hotels = Hotel::query()
            ->active()
            ->get(['id', 'name', 'city', 'country', 'address']);

        return response()->json([
            'points' => $hotels,
            'filters' => $request->only(['city', 'country']),
        ]);
    }

    public function guide(Hotel $hotel): JsonResponse
    {
        $rooms = $hotel->rooms()->with('category')->limit(10)->get();
        $services = $hotel->services()->active()->limit(10)->get();
        $restaurants = $hotel->restaurants()->limit(5)->get();

        return response()->json([
            'hotel' => $hotel,
            'rooms' => $rooms,
            'services' => $services,
            'restaurants' => $restaurants,
        ]);
    }

    public function news(Request $request, Hotel $hotel): JsonResponse
    {
        $news = News::query()
            ->where('hotel_id', $hotel->id)
            ->published()
            ->orderByDesc('published_at')
            ->paginate($request->integer('per_page', 10));

        return response()->json($news);
    }
}
