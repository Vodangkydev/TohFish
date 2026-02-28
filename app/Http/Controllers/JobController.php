<?php

namespace App\Http\Controllers;

use App\Services\JobService;
use App\Http\Requests\UploadCvRequest;
use App\Models\JobModel;
use App\Models\JobDetailsModel;
use App\Models\Cv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    protected $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    /**
     * Hiển thị chi tiết công việc
     */
    public function show($job_id)
    {
        try {
            $jobs = $this->jobService->getJobById($job_id);
            $jobDetail = JobDetailsModel::where('job_id', $job_id)->first();

            if (!$jobs) {
                return redirect()->route('tuyen-dung')
                    ->with('error', 'Không tìm thấy thông tin tuyển dụng.');
            }

            return view('tuyen-dung.chi-tiet-tuyen-dung', compact('jobs', 'jobDetail'));
        } catch (\Exception $e) {
            Log::error('Error in JobController@show', [
                'job_id' => $job_id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('tuyen-dung')
                ->with('error', 'Đã xảy ra lỗi khi tải thông tin tuyển dụng.');
        }
    }

    /**
     * Upload CV
     */
    public function upload(UploadCvRequest $request)
    {
        try {
            $filePath = null;

            if ($request->hasFile('file_path')) {
                $file = $request->file('file_path');
                $fileName = time() . '_' . $file->getClientOriginalName();
                
                // Đảm bảo thư mục cv_files tồn tại
                $uploadPath = public_path('cv_files');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Lưu file vào thư mục public/cv_files
                $file->move($uploadPath, $fileName);
                $filePath = $fileName;
            }

            $jobId = $request->route('id') ?? $request->input('job_id');

            // Xử lý willing_to_travel và willing_to_work_overtime (chuyển từ checkbox sang integer)
            $willingToTravel = $request->has('willing_to_travel') && $request->input('willing_to_travel') ? 1 : 0;
            $willingToWorkOvertime = $request->has('willing_to_work_overtime') && $request->input('willing_to_work_overtime') ? 1 : 0;

            // Xử lý sex - nếu database là boolean thì chuyển đổi, nếu là string thì giữ nguyên
            $sex = $request->input('sex');
            
            Cv::create([
                'job_id' => $jobId,
                'ho_ten' => $request->input('ho_ten'),
                'age' => $request->input('age'),
                'current_residence' => $request->input('current_residence'),
                'email' => $request->input('email'),
                'level' => $request->input('level'),
                'applied_position' => $request->input('applied_position'),
                'willing_to_travel' => $willingToTravel,
                'sex' => $sex,
                'place_of_birth' => $request->input('place_of_birth'),
                'phone' => $request->input('phone'),
                'url_facebook' => $request->input('url_facebook') ?? '',
                'file_path' => $filePath,
                'willing_to_work_overtime' => $willingToWorkOvertime,
                'previous_experiences' => $request->input('previous_experiences') ?? '',
                'personal_experience' => $request->input('personal_experience') ?? '',
            ]);

            if ($jobId) {
                return redirect()->route('chi-tiet-tuyen-dung', ['id' => $jobId])
                    ->with('success', 'CV đã được gửi thành công!');
            }
            
            // Redirect về trang recruitment (tuyen-dung-toh) thay vì tuyen-dung
            return redirect()->route('recruitment')
                ->with('success', 'CV đã được gửi thành công!');
        } catch (\Exception $e) {
            Log::error('Error in JobController@upload', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã xảy ra lỗi khi gửi CV. Vui lòng thử lại. Lỗi: ' . $e->getMessage());
        }
    }
}