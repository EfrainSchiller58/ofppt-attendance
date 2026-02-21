<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JustificationResource;
use App\Mail\JustificationReviewedMail;
use App\Mail\JustificationSubmittedMail;
use App\Models\Justification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class JustificationController extends Controller
{
    /** GET /api/justifications */
    public function index(Request $request)
    {
        $query = Justification::with(['absence.student.user', 'absence.group']);

        $user = $request->user();
        if ($user->role === 'student') {
            $query->whereHas('absence', fn ($q) =>
                $q->where('student_id', $user->student->id)
            );
        }

        return JustificationResource::collection(
            $query->orderByDesc('submitted_at')->get()
        );
    }

    /** POST /api/justifications (multipart/form-data) */
    public function store(Request $request)
    {
        $data = $request->validate([
            'absence_id' => 'required|exists:absences,id',
            'reason'     => 'required|string',
            'file'       => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $file = $request->file('file');
        $path = $file->store('justifications', 'public');

        $ext = strtolower($file->getClientOriginalExtension());
        $fileType = $ext === 'pdf' ? 'pdf' : 'image';

        $justification = Justification::create([
            'absence_id' => $data['absence_id'],
            'reason'     => $data['reason'],
            'file_name'  => $file->getClientOriginalName(),
            'file_path'  => $path,
            'file_type'  => $fileType,
            'file_size'  => $file->getSize(),
        ]);

        $justification->load(['absence.student.user', 'absence.group']);

        // Send email notification to admin
        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)
                    ->send(new JustificationSubmittedMail($justification));
            }
        } catch (\Throwable $e) {
            \Log::warning('Failed to send justification submitted email: ' . $e->getMessage());
        }

        return new JustificationResource($justification);
    }

    /** PATCH /api/justifications/{id}/approve */
    public function approve(Request $request, Justification $justification)
    {
        $justification->update([
            'status'      => 'approved',
            'reviewed_by' => $request->user()->id,
            'review_note' => $request->input('review_note'),
            'reviewed_at' => now(),
        ]);

        // Also update the absence status
        $justification->absence->update(['status' => 'justified']);

        // Send email to student
        try {
            Mail::to($justification->absence->student->user->email)
                ->send(new JustificationReviewedMail($justification, 'approved'));
        } catch (\Throwable $e) {
            \Log::warning('Failed to send justification email: ' . $e->getMessage());
        }

        $justification->load(['absence.student.user']);
        return new JustificationResource($justification);
    }

    /** PATCH /api/justifications/{id}/reject */
    public function reject(Request $request, Justification $justification)
    {
        $justification->update([
            'status'      => 'rejected',
            'reviewed_by' => $request->user()->id,
            'review_note' => $request->input('review_note'),
            'reviewed_at' => now(),
        ]);

        $justification->absence->update(['status' => 'unjustified']);

        // Send email to student
        try {
            Mail::to($justification->absence->student->user->email)
                ->send(new JustificationReviewedMail($justification, 'rejected'));
        } catch (\Throwable $e) {
            \Log::warning('Failed to send justification email: ' . $e->getMessage());
        }

        $justification->load(['absence.student.user']);
        return new JustificationResource($justification);
    }
}
