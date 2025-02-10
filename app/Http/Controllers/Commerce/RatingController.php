<?php

namespace App\Http\Controllers\Commerce;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\RatingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RatingController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate($this->validationRules());

        // Simpan ulasan
        $rating = Rating::create([
            'user_id' => auth()->id(),
            'product_id' => $productId,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        // Simpan gambar jika ada
        $this->storeImages($request->file('images'), $rating->id);

        return back()->with('success', 'Ulasan berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $rating = Rating::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        // Hapus gambar ulasan
        $this->deleteImages($rating->images);

        // Hapus ulasan
        $rating->delete();

        return back()->with('success', 'Ulasan berhasil dihapus!');
    }

    public function respond(Request $request, $id)
    {
        $rating = Rating::findOrFail($id);
        $request->validate(['response' => 'required|string|max:500']);

        $rating->update(['response' => $request->response]);

        return back()->with('success', 'Respon berhasil ditambahkan!');
    }

    protected function validationRules()
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    protected function storeImages($images, $ratingId)
    {
        if ($images) {
            foreach ($images as $image) {
                $imagePath = $image->store('ratings', 'public');
                RatingImage::create([
                    'rating_id' => $ratingId,
                    'image_path' => $imagePath,
                ]);
            }
        }
    }

    protected function deleteImages($images)
    {
        foreach ($images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
    }
}
