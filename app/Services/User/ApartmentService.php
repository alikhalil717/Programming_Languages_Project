<?php
namespace App\Services\User;
use App\Http\Requests\ApartmentRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\IndexApartmentRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Apartment;
use App\Models\User;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Rental;
use App\Models\ApartmentImage;
use Illuminate\Support\Facades\File;
class ApartmentService
{


    public function filterApartments(IndexApartmentRequest $request)
    {
        $request->validated();
        $apartment = Apartment::with('owner', 'images')
            ->where('status', 'approved')
            ->filter([
                'title' => request()->input('title'),
                'city' => $request->input('city'),
                'address' => $request->input('address'),
                'state' => $request->input('state'),
                'area' => $request->input('area'),
                'min_price' => $request->input('min_price'),
                'max_price' => $request->input('max_price'),
                'number_of_bedrooms' => $request->input('number_of_bedrooms'),
                'number_of_bathrooms' => $request->input('number_of_bathrooms'),
            ])->get();
        return [
            'success' => true,
            'message' => 'Apartments retrieved successfully',
            'apartments' => $apartment
        ];


    }
    public function getApartmentDetails($id): array
    {
        $apartment = Apartment::with('owner', 'images')->find($id);
        if (!$apartment || $apartment->status !== 'approved') {
            return [
                'success' => false,
                'message' => 'Apartment not found or not approved'
            ];
        }
        return [
            'success' => true,
            'message' => 'Apartment details retrieved successfully',
            'apartment' => $apartment
        ];
    }


    public function createApartment(ApartmentRequest $request): array
    {
        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not authenticated'
            ];
        }
        $validatedData = $request->validated();
        try {


            $apartment = Apartment::create([
                'owner_id' => $user->id,
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'address' => $validatedData['address'],
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],
                'area' => $validatedData['area'],
                'price_per_night' => $validatedData['price_per_night'],
                'number_of_bedrooms' => $validatedData['number_of_bedrooms'],
                'number_of_bathrooms' => $validatedData['number_of_bathrooms'],
            ]);

            foreach ($request->file('images', []) as $image) {
                $path = $image->store('apartment_images', 'public');
                $apartment->images()->create([
                    'apartment_id' => $apartment->id,
                    'image_path' => $path
                ]);
            }

            return [
                'success' => true,
                'message' => 'Apartment created successfully',
                'apartment' => $apartment
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to create apartment: ' . $e->getMessage()
            ];
        }
    }
    public function updateApartment(Request $request, $id): array
    {
        $apartment = Apartment::find($id);
        if (!$apartment) {
            return [
                'success' => false,
                'message' => 'Apartment not found'
            ];
        }
        if ($request->user()->id !== $apartment->owner_id) {
            return [
                'success' => false,
                'message' => 'Unauthorized action'
            ];
        }

        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'address' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:100',
            'state' => 'sometimes|required|string|max:100',
            'price_per_night' => 'sometimes|required|numeric|min:0',
            'number_of_bedrooms' => 'sometimes|required|integer|min:0',
            'number_of_bathrooms' => 'sometimes|required|integer|min:0',
        ]);

        try {
            $apartment->update($validatedData);

            return [
                'success' => true,
                'message' => 'Apartment updated successfully',
                'apartment' => $apartment
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to update apartment: ' . $e->getMessage()
            ];
        }
    }
    public function deleteApartment(Request $request, $id): array
    {


        $apartment = Apartment::with('images')->find($id);
        if (!$apartment) {
            return [
                'success' => false,
                'message' => 'Apartment not found'
            ];
        }
        $user = $request->user();
        if (!$user || $user->id !== $apartment->owner_id) {
            return [
                'success' => false,
                'message' => 'User not authenticated'
            ];
        }


        if (!$apartment) {
            return [
                'success' => false,
                'message' => 'Apartment not found'
            ];
        }

        $rentals = Rental::where('apartment_id', $apartment->id)->where(function ($query) {
            $query->where('status', 'pending')
                ->orWhere('status', 'confirmed')
                ->orWhere('status', 'ongoing');
        })->get();

        if ($rentals->count() > 0) {
            return [
                'success' => false,
                'message' => 'Apartment has ongoing or confirmed or pending rentals'
            ];
        }
        try {
            foreach ($apartment->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
            $apartment->delete();

            return [
                'success' => true,
                'message' => 'Apartment deleted successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to delete apartment: ' . $e->getMessage()
            ];
        }
    }
    public function ownerapartments(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not authenticated'
            ];
        }
        $apartments = Apartment::with('rentals', 'images')->where('owner_id', $user->id)->get();
        return [
            'success' => true,
            'message' => 'Apartments retrieved successfully',
            'apartments' => $apartments

        ];

    }
}