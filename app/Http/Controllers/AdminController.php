<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Agency;
use App\Models\Spot;
use App\Models\SpotImage;
use App\Models\Blog;
use App\Models\Notification;

class AdminController extends Controller
{
    public function addAgency(Request $request)
    {
        $request->validate([
            'agency_name'      => 'required|string|max:255|unique:agencies,agency_name',
            'description'      => 'nullable|string',
            'contact_number'   => 'required|string|max:15',
            'location_address' => 'nullable|string|max:255',
        ]);

        [$agency, $user] = DB::transaction(function () use ($request) {
            $agencyNameSlug = strtolower(preg_replace('/\s+/', '', $request->agency_name));
            $generatedEmail = $agencyNameSlug . '.surigao@gmail.com';

            $user = User::create([
                'email'    => $generatedEmail,
                'password' => Hash::make($agencyNameSlug),
                'role'     => 'agency',
                'status'   => 1,
            ]);

            $agency = Agency::create([
                'user_id'          => $user->id,
                'agency_name'      => $request->agency_name,
                'description'      => $request->description,
                'contact_number'   => $request->contact_number,
                'location_address' => $request->location_address,
                'image_path'       => null,
            ]);

            return [$agency, $user];
        });

        return response()->json([
            'message' => 'Agency and user created successfully',
            'agency'  => $agency,
            'user'    => $user,
        ], 201);
    }

    public function addSpot(Request $request)
    {
        try {
            $request->validate([
                'spot_name'   => 'required|string|max:255',
                'description' => 'nullable|string',
                'location'    => 'nullable|string|max:255',
                'category'    => 'nullable|string|max:255',
                'images.*'    => 'image|mimes:jpeg,png,jpg,gif|max:40000'
            ]);
            \Log::info('Validation passed.');

            // ✅ Create the spot
            $spot = Spot::create([
                'spot_name'   => $request->spot_name,
                'description' => $request->description,
                'location'    => $request->location,
                'category'    => $request->category,
                'status'      => 'active'
            ]);
            \Log::info('Spot created successfully:', $spot->toArray());

            // ✅ Save images if available
            if ($request->hasFile('images')) {
                \Log::info('Images found:', ['count' => count($request->file('images'))]);

                foreach ($request->file('images') as $image) {
                    $path = $image->store('spots', 'public');
                    \Log::info('Image stored:', ['path' => $path]);

                    SpotImage::create([
                        'spot_id'    => $spot->id,
                        'spot_image' => $path
                    ]);
                }
            } else {
                \Log::info('No images uploaded.');
            }

            $users = User::whereIn('role', ['user', 'agency'])->get();

            foreach ($users as $user) {
                Notification::create([
                    'user_ID'     => $user->id,
                    'sender_id'   => auth()->id() ?? null,
                    'receiver_id' => $user->id,
                    'message'     => "A new spot named '{$spot->spot_name}' has been added!",
                    'status'      => 'unread',
                    'type'        => 'spotCreated',
                ]);
            }

            \Log::info('Notifications sent to all non-admin users.');

            return response()->json(['message' => 'Spot created successfully!'], 201);

        } catch (\Exception $e) {
            \Log::error('Error adding spot: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Something went wrong. Check logs for details.'], 500);
        }
    }
    public function updateSpot(Request $request, $id)
    {
        try {
            $request->validate([
                'spot_name'   => 'required|string|max:255',
                'description' => 'nullable|string',
                'location'    => 'nullable|string|max:255',
                'category'    => 'nullable|string|max:255',
                'images.*'    => 'image|mimes:jpeg,png,jpg,gif|max:40000'
            ]);

            $spot = Spot::findOrFail($id);

            $spot->update([
                'spot_name'   => $request->spot_name,
                'description' => $request->description,
                'location'    => $request->location,
                'category'    => $request->category,
            ]);

            // Remove selected images
            if ($request->has('removed_images')) {
                foreach ($request->removed_images as $imgId) {
                    $img = SpotImage::find($imgId);
                    if ($img) {
                        if (file_exists(storage_path('app/public/' . $img->spot_image))) {
                            unlink(storage_path('app/public/' . $img->spot_image));
                        }
                        $img->delete();
                    }
                }
            }

            // Save new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('spots', 'public');

                    SpotImage::create([
                        'spot_id'    => $spot->id,
                        'spot_image' => $path
                    ]);
                }
            }

            return response()->json(['message' => 'Spot updated successfully!']);

        } catch (\Exception $e) {
            \Log::error('Error updating spot: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Something went wrong. Check logs for details.'], 500);
        }
    }



    public function updateUserStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:users,id',
            'status' => 'required|integer|in:1,2,3',
        ]);

        $user = User::findOrFail($request->id);

        $user->status = $request->status;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully.',
            'user' => $user
        ]);
    }

    public function addBlog(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
        ]);

        $blog = Blog::create([
            'title' => $request->title,
            'url' => $request->url,
            'status' => 'active',
        ]);

        return response()->json([
            'message' => 'Blog post added successfully!',
            'data' => $blog,
        ]);
    }

    public function deleteSpot($id)
    {
        $spot = Spot::find($id);
        if (!$spot) {
            return response()->json(['message' => 'Spot not found'], 404);
        }

        // Delete related images
        foreach ($spot->images as $img) {
            if (file_exists(storage_path('app/public/' . $img->spot_image))) {
                unlink(storage_path('app/public/' . $img->spot_image));
            }
            $img->delete();
        }

        // Delete ratings
        $spot->ratings()->delete();

        // Delete reviews
        $spot->reviews()->delete();

        // Delete saved entries
        $spot->isSavedByUser()->delete();

        // Finally, delete the spot itself
        $spot->delete();

        return response()->json(['message' => 'Spot and all related data deleted successfully']);
    }
    public function deleteBlog($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $blog->delete();

        return response()->json([
            'message' => 'Blog deleted successfully'
        ]);
    }
    public function editBlog(Request $request, $id)
    {
        // Validate only the fields that are present
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'url'   => 'sometimes|required|string|max:1000',
        ]);

        // Find blog
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        // Update only existing fields
        if ($request->has('title')) {
            $blog->title = $request->title;
        }

        if ($request->has('url')) {
            $blog->url = $request->url;
        }

        $blog->save();

        return response()->json([
            'message' => 'Blog updated successfully!',
            'blog' => $blog
        ], 200);
    }





}
