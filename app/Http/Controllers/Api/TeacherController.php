<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    /** GET /api/teachers */
    public function index()
    {
        if (request()->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $teachers = Teacher::with(['user', 'groups'])->get();
        return TeacherResource::collection($teachers);
    }

    /** POST /api/teachers */
    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'nullable|string|max:20',
            'subject'    => 'required|string|max:100',
        ]);

        return DB::transaction(function () use ($data) {
            $email = $this->generateSchoolEmail();
            $defaultPassword = 'password123';

            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $email,
                'phone'      => $data['phone'] ?? null,
                'password'   => Hash::make($defaultPassword),
                'role'       => 'teacher',
                'must_change_password' => true,
            ]);

            $teacher = Teacher::create([
                'user_id' => $user->id,
                'subject' => $data['subject'],
            ]);

            $teacher->load(['user', 'groups']);
            return response()->json([
                'data' => new TeacherResource($teacher),
                'credentials' => [
                    'email' => $email,
                    'password' => $defaultPassword,
                ],
            ]);
        });
    }

    /** PUT /api/teachers/{id} */
    public function update(Request $request, Teacher $teacher)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'first_name' => 'sometimes|string|max:100',
            'last_name'  => 'sometimes|string|max:100',
            'phone'      => 'nullable|string|max:20',
            'subject'    => 'sometimes|string|max:100',
        ]);

        DB::transaction(function () use ($teacher, $data) {
            $teacher->user->update(collect($data)->only(['first_name', 'last_name', 'phone'])->toArray());
            $teacher->update(collect($data)->only(['subject'])->toArray());
        });

        $teacher->load(['user', 'groups']);
        return new TeacherResource($teacher);
    }

    /** DELETE /api/teachers/{id} */
    public function destroy(Teacher $teacher)
    {
        if (request()->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $teacher->user->delete();
        return response()->noContent();
    }

    private function generateSchoolEmail(): string
    {
        do {
            $code = 'f' . random_int(10000, 99999);
            $email = $code . '@ofppt.com';
        } while (User::where('email', $email)->exists());

        return $email;
    }
}
