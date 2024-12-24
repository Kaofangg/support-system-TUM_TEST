<!DOCTYPE html> 
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บันทึกข้อมูลนักศึกษา</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <div class="navbar d-flex justify-content-between align-items-center p-2 bg-light">
        <div class="navbar-logo">
            <img src="{{ asset('img/KKU_Logo.png') }}" alt="โลโก้ระบบ" style="height: 50px;">
        </div>

        <div class="dropdown">
            <a class="btn btn-light dropdown-toggle" href="#" role="button" id="userDropdown" 
               data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ asset('img/user-icon.png') }}" alt="User Icon" 
                     style="width: 30px; height: 30px; border-radius: 50%;">
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li>
                    <a class="dropdown-item" href="#">
                        {{ Auth::user()->username ?? 'ไม่พบข้อมูลผู้ใช้' }} 
                        ({{ Auth::user()->role ?? 'ไม่มีสิทธิ์' }})
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#">
                        รหัสนักศึกษา: {{ Auth::user()->student->id ?? 'ไม่พบรหัสนักศึกษา' }}
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}">Log Out</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar">
        <a href="{{ route('report') }}">รายงานผลการศึกษา</a>
        <a href="{{ route('upload.document') }}">อัปโหลดเอกสาร</a>
        <a href="{{ route('student-profile') }}">ประวัตินักศึกษา</a>
    </div>

    <div class="content">
        <div class="container mt-5">
            <h2 class="text-center">บันทึกข้อมูลนักศึกษา</h2>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('student.store') }}" method="POST" id="studentForm">
                @csrf

                <div class="mb-3">
                    <label for="student_id" class="form-label">รหัสนักศึกษา</label>
                    <input type="text" class="form-control" id="student_id" name="student_id" 
                           value="{{ old('student_id') }}" required>
                    @error('student_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="first_name" class="form-label">ชื่อจริง</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" 
                           value="{{ old('first_name') }}" required>
                    @error('first_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">เบอร์โทร</label>
                    <input type="text" class="form-control" id="phone" name="phone" 
                           value="{{ old('phone') }}">
                    @error('phone')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="school_location" class="form-label">จบการศึกษาจาก</label>
                    <input type="text" class="form-control" id="school_location" name="school_location" 
                           value="{{ old('school_location') }}">
                    @error('school_location')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="advisor_id" class="form-label">อาจารย์ที่ปรึกษา</label>
                    <select class="form-control" id="advisor_id" name="advisor_id" required>
                        <option value="">-- เลือกอาจารย์ที่ปรึกษา --</option>
                        @foreach ($advisors as $advisor)
                            <option value="{{ $advisor->id }}" 
                                {{ old('advisor_id') == $advisor->id ? 'selected' : '' }}>
                                {{ $advisor->first_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('advisor_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
