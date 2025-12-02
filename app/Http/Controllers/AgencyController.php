<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Reservation;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;


class AgencyController extends Controller
{
    public function addPackage(Request $request)
    {

        $validated = $request->validate([
            'package_name'   => 'required|string|max:255',
            'shortDesc'      => 'nullable|string|max:255',
            'price'          => 'required|numeric|min:0',
            'capacity'       => 'required|integer|min:1',
            'available_slot'   => 'nullable|integer|min:0',
            'startDate'      => 'nullable|date',
            'endDate'        => 'nullable|date|after_or_equal:startDate',
            'startTime'      => 'nullable|date_format:H:i',
            'endTime'        => 'nullable|date_format:H:i',
            'pickupPoint'    => 'nullable|string|max:255',
            'destinations'   => 'nullable|array',
            'inclusions'     => 'nullable|string',
            'exclusions'     => 'nullable|string',
        ]);

        $package = Package::create([
            'userID' => auth()->id(),
            'package_name'    => $validated['package_name'],
            'description'     => $validated['shortDesc'] ?? null,
            'price'           => $validated['price'],
            'capacity'        => $validated['capacity'],
            'available_slot'  => $validated['available_slot'] ?? null,
            'start_date'      => $validated['startDate'] ?? null,
            'end_date'        => $validated['endDate'] ?? null,
            'start_time'      => $validated['startTime'] ?? null,
            'end_time'        => $validated['endTime'] ?? null,
            'pickup_point'    => $validated['pickupPoint'] ?? null,
            'tour_destination'=> json_encode($validated['destinations'] ?? []),
            'inclusions'      => $validated['inclusions'] ?? null,
            'exclusions'      => $validated['exclusions'] ?? null,
            'status'          => '1',
        ]);

        return response()->json([
            'message' => 'Package added successfully!',
            'package' => $package,
        ], 201);
    }

    public function updatePackage(Request $request, $id)
    {
        // Validate inputs
        $validated = $request->validate([
            'package_name'   => 'required|string|max:255',
            'shortDesc'      => 'nullable|string|max:255',
            'price'          => 'required|numeric|min:0',
            'capacity'       => 'required|integer|min:1',
            'available_slot' => 'nullable|integer|min:0',
            'startDate'      => 'nullable|date',
            'endDate'        => 'nullable|date|after_or_equal:startDate',
            'startTime'      => 'nullable|date_format:H:i',
            'endTime'        => 'nullable|date_format:H:i',
            'pickupPoint'    => 'nullable|string|max:255',
            'destinations'   => 'nullable|array',
            'inclusions'     => 'nullable|string',
            'exclusions'     => 'nullable|string',
            'status'         => 'nullable|string|in:0,1,active,inactive',
        ]);

        // Find package
        $package = Package::findOrFail($id);

        // Update only fields provided
        $package->update(array_filter([
            'package_name'     => $validated['package_name'] ?? $package->package_name,
            'description'      => $validated['shortDesc'] ?? $package->description,
            'price'            => $validated['price'] ?? $package->price,
            'capacity'         => $validated['capacity'] ?? $package->capacity,
            'available_slot'   => $validated['available_slot'] ?? $package->available_slot,
            'start_date'       => $validated['startDate'] ?? $package->start_date,
            'end_date'         => $validated['endDate'] ?? $package->end_date,
            'start_time'       => $validated['startTime'] ?? $package->start_time,
            'end_time'         => $validated['endTime'] ?? $package->end_time,
            'pickup_point'     => $validated['pickupPoint'] ?? $package->pickup_point,
            'tour_destination' => isset($validated['destinations'])
                ? json_encode($validated['destinations'])
                : $package->tour_destination,
            'inclusions'       => $validated['inclusions'] ?? $package->inclusions,
            'exclusions'       => $validated['exclusions'] ?? $package->exclusions,
            'status'           => $validated['status'] ?? $package->status,
        ], fn($v) => !is_null($v)));

        return response()->json([
            'message' => 'Package updated successfully!',
            'package' => $package,
        ], 200);
    }

    public function updateReservation(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|string|in:pending,confirmed,rejected,completed,cancelled',
            ]);

            $reservation = Reservation::findOrFail($id);

            $reservation->update([
                'status' => $request->status,
            ]);

            $statusMessage = match ($request->status) {
                'pending'   => 'Your reservation is now pending.',
                'confirmed' => 'Your reservation has been confirmed!',
                'rejected'  => 'Your reservation has been rejected.',
                'completed' => 'Your reservation has been completed. Thank you!',
                'cancelled' => 'Your reservation has been cancelled.',
                default     => 'Your reservation status has been updated.',
            };

            $receiverId = $reservation->user_id; // the user who made the reservation
            $senderId = auth()->id(); // the admin or staff who updated it

            Notification::create([
                'user_ID'     => $receiverId,  // make sure the column name matches your DB
                'sender_id'   => $senderId,
                'receiver_id' => $receiverId,
                'message'     => $statusMessage,
                'type'        => 'reservation',
                'status'      => 'unread',
            ]);

            return response()->json([
                'message' => 'Reservation updated successfully',
                'data' => $reservation,
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to update reservation',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function deletePackage($id)
    {
        try {
            $package = Package::find($id);

            if (!$package) {
                return response()->json([
                    'success' => false,
                    'message' => 'Package not found.'
                ], 404);
            }

            $package->delete();

            return response()->json([
                'success' => true,
                'message' => 'Package deleted successfully.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the package.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function agencyTotalPackages()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $totalPackages = $user->packages()->count();

        return response()->json([
            'total_packages' => $totalPackages
        ]);
    }
    public function agencyTotalReservations()
    {
        $user = Auth::user();

        $totalReservations = Reservation::whereHas('package', function ($query) use ($user) {
            $query->where('userID', $user->id);
        })->count();

        return response()->json([
            'total_reservations' => $totalReservations
        ]);
    }

    public function agencyTotalRevenue()
    {
        $user = Auth::user();

        $packageIds = Package::where('userID', $user->id)->pluck('id');

        $totalRevenue = Reservation::whereIn('package_id', $packageIds)
            ->where('status', 'completed')
            ->sum('total_amount');

        $monthlyRevenue = Reservation::selectRaw('MONTH(created_at) as month, SUM(total_amount) as amount')
            ->whereIn('package_id', $packageIds)
            ->where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->groupByRaw('MONTH(created_at)')
            ->orderBy('month')
            ->get();

        $monthlyRevenue = collect(range(1, 12))->map(function($monthNum) use ($monthlyRevenue) {
            $item = $monthlyRevenue->firstWhere('month', $monthNum);
            return [
                'month' => date('M', mktime(0, 0, 0, $monthNum, 1)),
                'amount' => $item->amount ?? 0
            ];
        });

        return response()->json([
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue
        ]);
    }

    public function topSellingPackages()
    {
        $user = Auth::user();

        // Get top 5 packages of this user, ordered by number of reservations
        $topPackages = Package::where('userID', $user->id)
            ->withCount(['reservations' => function($query) {
                $query->where('status', 'completed'); // count only completed reservations
            }])
            ->orderByDesc('reservations_count')
            ->limit(5)
            ->get(['id', 'package_name']);

        // Format the result for frontend
        $topPackages = $topPackages->map(function($pkg) {
            return [
                'id' => $pkg->id,
                'name' => $pkg->package_name,
                'sales' => $pkg->reservations_count,
                'percentage' => 0
            ];
        });

        $maxSales = $topPackages->max('sales') ?: 1;
        $topPackages = $topPackages->map(function($pkg) use ($maxSales) {
            $pkg['percentage'] = round(($pkg['sales'] / $maxSales) * 100);
            return $pkg;
        });

        return response()->json($topPackages);
    }


    public function recentReservations()
    {
        $user = Auth::user();

        // Get package IDs that belong to this user
        $packageIds = Package::where('userID', $user->id)->pluck('id');

        // Get top 3 most recent reservations
        $recentReservations = Reservation::with('package')
            ->whereIn('package_id', $packageIds)
            ->orderByDesc('created_at')
            ->take(3)
            ->get()
            ->map(function ($res) {
                return [
                    'id' => $res->id,
                    'customer' => $res->full_name,
                    'package' => $res->package->package_name ?? 'Unknown',
                    'date' => $res->created_at->format('M d, Y'),
                    'amount' => $res->total_amount,
                    'status' => $res->status,
                ];
            });

        return response()->json($recentReservations);
    }






}
