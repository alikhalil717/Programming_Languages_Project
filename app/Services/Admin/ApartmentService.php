<?php
namespace App\Services\Admin;
use App\Http\Requests\ApartmentRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Apartment;
use App\Models\User;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
class ApartmentService
{



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



}