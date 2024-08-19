<?php

namespace App\Http\Controllers\Api\Promotion;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\PromotionService;
use App\Models\PromotionCondition;

class PromotionController extends Controller
{
    // Lấy danh sách tất cả các chương trình khuyến mãi
    public function index()
    {
        $promotions = Promotion::all();
        return response()->json($promotions);
    }

    // Lấy thông tin chi tiết của một chương trình khuyến mãi
    public function show($id)
    {
        $promotion = Promotion::findOrFail($id);
        return response()->json($promotion);
    }


    // Tạo mới một chương trình khuyến mãi
    public function store(Request $request)
    {
        $promotionData = $request->only([
            'name', 'description', 'discount_type', 'discount_value', 'start_date', 'end_date'
        ]);

        $promotion = Promotion::create($promotionData);

        // Lưu các dịch vụ áp dụng cho chương trình khuyến mãi
        if ($request->has('service_ids')) {
            foreach ($request->service_ids as $serviceId) {
                PromotionService::create([
                    'promotion_id' => $promotion->id,
                    'service_id' => $serviceId,
                ]);
            }
        }

        // Lưu các điều kiện áp dụng cho chương trình khuyến mãi
        if ($request->has('conditions')) {
            foreach ($request->conditions as $condition) {
                PromotionCondition::create([
                    'promotion_id' => $promotion->id,
                    'condition_type' => $condition['condition_type'],
                    'condition_value' =>json_encode($condition['condition_value']),
                ]);
            }
        }

        // Load lại promotion với conditions sau khi lưu thành công
        $promotion->load('conditions');

        return response()->json($promotion, 201);
    }


    // Cập nhật thông tin của một chương trình khuyến mãi
    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $promotionData = $request->only([
            'name', 'description', 'discount_type', 'discount_value', 'start_date', 'end_date'
        ]);

        $promotion->update($promotionData);

        // Cập nhật các dịch vụ áp dụng cho chương trình khuyến mãi
        PromotionService::where('promotion_id', $promotion->id)->delete(); // Xóa các dịch vụ cũ

        if ($request->has('service_ids')) {
            foreach ($request->service_ids as $serviceId) {
                PromotionService::create([
                    'promotion_id' => $promotion->id,
                    'service_id' => $serviceId,
                ]);
            }
        }

        // Cập nhật các điều kiện áp dụng cho chương trình khuyến mãi
        PromotionCondition::where('promotion_id', $promotion->id)->delete(); // Xóa các điều kiện cũ

        if ($request->has('conditions')) {
            foreach ($request->conditions as $condition) {
                PromotionCondition::create([
                    'promotion_id' => $promotion->id,
                    'condition_type' => $condition['condition_type'],
                    'condition_value' => $condition['condition_value'],
                ]);
            }
        }

        return response()->json($promotion, 200);
    }

    // Xóa một chương trình khuyến mãi
    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        // Xóa các dịch vụ và điều kiện áp dụng cho chương trình khuyến mãi
        PromotionService::where('promotion_id', $id)->delete();
        PromotionCondition::where('promotion_id', $id)->delete();

        return response()->json(null, 204);
    }
}
