<?php

namespace App\Http\Controllers\Api\Statistic;

use App\Http\Controllers\Controller;
use App\Models\Base;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\StoreInformation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatisticsController extends Controller
{
    // Tổng số booking
    public function getTotalBookings()
    {
        $stores = StoreInformation::all();
        $results = [];

        foreach ($stores as $store) {
            $totalBookings = Booking::whereHas('bases', function ($query) use ($store) {
                $query->where('store_name', $store->name);
            })->count();

            $results[] = [
                'store_name' => $store->name,
                'total_bookings' => $totalBookings,
            ];
        }

        return response()->json(['total_bookings_by_store' => $results]);
    }
    // Số lần đặt chỗ của các user

    public function getUserBookings()
    {
        $stores = StoreInformation::all();
        $results = [];
        foreach ($stores as $store) {
            $userBookings = Booking::whereHas('bases', function ($query) use ($store) {
                $query->where('store_name', $store->name);
            })->select('user_id', DB::raw('COUNT(*) as booking_count'))
                ->groupBy('user_id')
                ->get();
            $results[] = [
                'store_name' => $store->name,
                'user_bookings' => $userBookings,
            ];
        }
        return response()->json(['user_bookings_by_store' => $results]);
    }

    // Tỷ lệ từ bỏ đặt chỗ
    public function getAbandonmentRate()
    {
        $stores = StoreInformation::all();
        $results = [];
        foreach ($stores as $store) {
            $totalBookings = Booking::whereHas('bases', function ($query) use ($store) {
                $query->where('store_name', $store->name);
            })->count();
            $completedBookings = Booking::whereHas('bases', function ($query) use ($store) {
                $query->where('store_name', $store->name)
                    ->where('status', 'canceled');
            })->count();
            // Log::info("Store: {$store->name} - Total Bookings: {$totalBookings} - Completed Bookings: {$completedBookings}");
            $abandonmentRate = $totalBookings > 0 ? ($completedBookings) / $totalBookings * 100 : 0;
            $results[] = [
                'store_name' => $store->name,
                'abandonment_rate' => $abandonmentRate,
            ];
        }

        return response()->json(['abandonment_rates_by_store' => $results]);
    }


    // Giá trị trung bình đơn hàng
    public function getAverageBookingValue()
{
    $stores = StoreInformation::all();
    $results = [];
    foreach ($stores as $store) {
        $totalRevenue = Base::whereHas('booking', function ($query) use ($store) {
            $query->whereHas('bases', function ($query) use ($store) {
                $query->where('store_name', $store->name);
            });
        })->where('status', 'done')->sum('total_price');
        $totalBookings = Booking::whereHas('bases', function ($query) use ($store) {
            $query->where('store_name', $store->name);
        })->whereHas('bases', function ($query) {
            $query->where('status', 'done');
        })->count();
        $averageBookingValue = $totalBookings ? ($totalRevenue / $totalBookings) : 0;
        $results[] = [
            'store_name' => $store->name,
            'average_booking_value' => $averageBookingValue,
        ];
    }
    return response()->json(['average_booking_values' => $results]);
}

    // tỷ lệ lập đầy thời gian
    public function getOccupancyRate()
    {
        $stores = StoreInformation::all();
        $results = [];

        foreach ($stores as $store) {
            $totalWorkingHours = Schedule::whereHas('user', function ($query) use ($store) {
                $query->where('store_id', $store->id)->where('role', 1);
            })->where('is_valid', 1)
                ->sum(DB::raw('TIMESTAMPDIFF(HOUR, start_time, end_time)'));

            $totalBookedHours = Booking::whereHas('bases', function ($query) use ($store) {
                $query->where('store_name', $store->name);
            })->sum('total_time') / 60;

            $occupancyRate = $totalWorkingHours ? ($totalBookedHours / $totalWorkingHours) * 100 : 0;
            $results[] = [
                'store_name' => $store->name,
                'occupancy_rate' => $occupancyRate // tổng giờ đã đăt : tổng số giờ làm viêcj * 100
            ];
        }

        return response()->json(['occupancy_rates' => $results]);
    }
    // tổng doanh thu từ bookig
    public function getTotalRevenue()
    {
        $stores = StoreInformation::all();
        $results = [];

        foreach ($stores as $store) {
            // Tính tổng doanh thu của các đơn hàng của cửa hàng hiện tại
            $totalRevenue = Base::whereHas('booking', function ($query) use ($store) {
                $query->whereHas('bases', function ($query) use ($store) {
                    $query->where('store_name', $store->name);
                });
            })->where('status', 'done')->sum('total_price');

            // Thêm kết quả vào mảng kết quả
            $results[] = [
                'store_name' => $store->name,
                'total_revenue' => $totalRevenue,
            ];
        }

        return response()->json(['total_revenues' => $results]);
    }

    // doanh thu theo từng dịch vụ theo từng cửa hàng
   















}
