<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Advisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    // แสดงฟอร์มกรอกข้อมูลนักศึกษา
    public function create()
    {
        // ดึงข้อมูลที่ปรึกษาเพื่อแสดงในฟอร์ม
        $advisors = Advisor::all();

        // ดึงข้อมูลนักศึกษาจากการเข้าสู่ระบบ
        $student = Student::where('user_id', Auth::id())->first();

        return view('profile_report', compact('advisors', 'student'));
    }

    // บันทึกข้อมูลนักศึกษาใหม่ลงในฐานข้อมูล
    public function store(Request $request)
    {
        // ตรวจสอบความถูกต้องของข้อมูลที่กรอก
        $request->validate([
            'student_id' => 'required|string|max:10|unique:students,id',
            'first_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'school_location' => 'nullable|string|max:255',
            'advisor_id' => 'required|exists:advisors,id',
        ]);

        // บันทึกข้อมูลนักศึกษาใหม่ลงในฐานข้อมูล
        Student::create([
            'id' => $request->student_id,
            'user_id' => Auth::id(),
            'first_name' => $request->first_name,
            'phone' => $request->phone,
            'school_location' => $request->school_location,
            'advisor_id' => $request->advisor_id,
        ]);

        // รีไดเรกต์กลับไปที่หน้าประวัตินักศึกษา พร้อมข้อความสำเร็จ
        return redirect()->route('student-profile')->with('success', 'บันทึกข้อมูลนักศึกษาสำเร็จ');
    }

    // ฟังก์ชันอัปเดตข้อมูลนักศึกษา
    public function update(Request $request, $id)
    {
        // ค้นหานักศึกษาตาม ID
        $student = Student::findOrFail($id);

        // ตรวจสอบความถูกต้องของข้อมูลที่กรอก
        $request->validate([
            'student_id' => 'required|string|max:10|unique:students,id,' . $id,
            'first_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'school_location' => 'nullable|string|max:255',
            'advisor_id' => 'required|exists:advisors,id',
        ]);

        // อัปเดตข้อมูลนักศึกษาในฐานข้อมูล
        $student->update([
            'id' => $request->student_id,
            'first_name' => $request->first_name,
            'phone' => $request->phone,
            'school_location' => $request->school_location,
            'advisor_id' => $request->advisor_id,
        ]);

        // รีไดเรกต์กลับไปที่หน้าเดิมและแสดงข้อความสำเร็จ
        return redirect()->back()->with('success', 'ข้อมูลนักศึกษาถูกอัปเดตเรียบร้อยแล้ว');
    }

    public function showReport()
    {   
        // ดึงข้อมูลนักศึกษาทั้งหมดจากฐานข้อมูล
        $students = Student::with(['academicRecords.course'])->get(); // ดึงข้อมูลนักศึกษาและวิชา

        // ส่งข้อมูลไปยัง view 'table_report'
        return view('table_report', compact('students'));
    }
}
