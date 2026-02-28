<?php

namespace App\Http\Controllers;

use App\Services\DocumentService;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    protected $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * Hiển thị trang tài liệu
     */
    public function index(Request $request)
    {
        try {
            // Lấy dữ liệu từ Service
            $document_reseach = Document::all();
            $documents = $this->documentService->getAllDocuments();
            $documents8 = $this->documentService->getLatestDocuments(
                config('app_constants.default_documents_limit')
            );

            // Xử lý AJAX requests
            if ($request->ajax()) {
                return $this->handleAjaxRequest($request);
            }

            return view('tai-lieu.tai-lieu', compact('documents', 'document_reseach', 'documents8'));
        } catch (\Exception $e) {
            Log::error('Error in DocumentController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi tải dữ liệu.');
        }
    }

    /**
     * Xử lý các AJAX requests
     */
    protected function handleAjaxRequest(Request $request)
    {
        // Tìm kiếm theo query
        if ($request->has('query')) {
            $query = $request->input('query');
            $searchResults = $this->documentService->searchDocuments($query);

            if ($searchResults->isEmpty()) {
                return '<p>Không có kết quả tìm kiếm</p>';
            }

            $view = view('partials.recent-search-list-2', ['document_reseach' => $searchResults])->render();
            return $view;
        }

        // Lọc theo khoảng thời gian
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            if (empty($startDate) || empty($endDate)) {
                return response()->json(['error' => 'Ngày bắt đầu hoặc ngày kết thúc không được để trống.']);
            }

            try {
                $documents = $this->documentService->getDocumentsByDateRange($startDate, $endDate);
                $view = view('partials.list-table', ['documents' => $documents])->render();
                return response()->json(['view' => $view]);
            } catch (\Exception $e) {
                Log::error('Error filtering documents by date', [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'error' => $e->getMessage()
                ]);

                return response()->json([
                    'error' => 'Có lỗi xảy ra trong việc xử lý ngày tháng. Vui lòng thử lại.'
                ]);
            }
        }

        return response()->json(['error' => 'Vui lòng cung cấp cả ngày bắt đầu và ngày kết thúc.']);
    }
}