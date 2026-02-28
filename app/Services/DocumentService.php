<?php

namespace App\Services;

use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DocumentService
{
    /**
     * Lấy tất cả documents sắp xếp theo mới nhất
     */
    public function getAllDocuments()
    {
        return Document::orderBy('created_at', 'desc')->get();
    }

    /**
     * Lấy N documents mới nhất
     */
    public function getLatestDocuments($limit = 8)
    {
        return Document::orderBy('created_at', 'desc')->take($limit)->get();
    }

    /**
     * Tìm kiếm documents theo tên
     */
    public function searchDocuments($query)
    {
        if (empty($query)) {
            return collect();
        }

        return Document::where('document_name', 'LIKE', "%{$query}%")->get();
    }

    /**
     * Lấy documents theo khoảng thời gian
     */
    public function getDocumentsByDateRange($startDate, $endDate)
    {
        try {
            $startDate = Carbon::createFromFormat('Y-m-d', $startDate, 'UTC')->format('Y-m-d');
            $endDate = Carbon::createFromFormat('Y-m-d', $endDate, 'UTC')->format('Y-m-d');

            return Document::whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error filtering documents by date range', [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}

