<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Exception;
class Personalid extends Model
{
    //
    protected $fillable = [
        'user_id',
        'image_path',
    ];




    public function delete()
    {
        try {
            $imagepath = $this->image_path;

            $deleted = false;


            Storage::disk('public')->delete($imagepath);

            if (!$deleted) {
                \Log::warning("Image file not found in any path for UserID: ");
            }


        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting image',
                'error' => $e->getMessage()
            ], 500);
        }


    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
