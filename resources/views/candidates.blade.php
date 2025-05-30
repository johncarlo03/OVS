<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Management - CTU-Danao OVS</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="{{ asset('js/candidate.js') }}"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }
        .form-container, .table-container {
            border: 2px solid #1e40af;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            width: 100%;
            box-sizing: border-box;
        }
        .form-group {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }
        .form-input {
            flex: 1;
            padding: 8px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            background-color: #f3f4f6;
        }
        .select-input {
            padding: 8px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            background-color: #f3f4f6;
            flex: 1;
        }
        .register-btn {
            background-color: #1e40af;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .register-btn:hover {
            background-color: #1e3a8a;
        }
        .logout-btn {
            background-color: #1e40af;
            color: white;
            padding: 10px 20px;
            text-align: left;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        .logout-btn:hover {
            background-color: #3b82f6;
        }
        .action-btn {
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 5px;
        }
        .edit-btn {
            background-color: #f59e0b;
            color: white;
        }
        .delete-btn {
            background-color: #ef4444;
            color: white;
        }
        .action-btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="flex justify-center items-center p-4">
                <img src="{{ URL('images/logo.png') }}" alt="OVS Logo" class="mr-2 w-20 h-20 ">
            </div>
            <div class="flex items-center mb-4 p-2">
                <img src="{{ asset('uploads/admin/' . Auth::guard('admin')->user()->photo) }}" alt="Profile" class="rounded-full mr-2 w-20 h-20">
                <span style="font-size:20px; font-weight:bold;">ADMIN</span>
            </div>
            <span class="indicator">Reports</span>
            <a href="/admin">Dashboard</a>
            <span class="indicator">Manage</span>
            <a href="/accounts">Admins</a>
            <a href="/voter">Voters</a>
            <a href="/candidates" class="active">Candidates</a>            
                <form action="/logout" method="POST" class="flex flex-col justify-end mt-auto">
                @csrf
                <div class="p-4">
<button class="w-full text-white px-6 py-2 rounded-full transition bg-[#3f5391] hover:bg-[#2C3D74]">
    Logout
</button>
                </div></form>
        </div>

        <!-- Main Content -->
        <div class="content">
            <h1 class="text-2xl font-bold mb-4">Register Candidate</h1>
            <div class="form-container">
                <form id="registerForm" method="POST" action="{{ route('candidates.store' ) }}"  enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="first_name" class="form-input" placeholder="First Name" required>
                        <input type="text" name="middle_name" class="form-input" placeholder="Middle Name" required>
                        <input type="text" name="last_name" class="form-input" placeholder="Last Name" required>
                    </div>
                    <div class="form-group">
                        <select class="select-input" id="positionSelect" name="position" required>
                            <option value="" disabled selected>Position</option>
                            <option value="P">President</option>
                            <option value="VP">Vice President</option>
                            <option value="GSEC">General Secretary</option>
                            <option value="FSEC">Finance Secretary</option>
                            <option value="AUD">Auditor</option>
                            <option value="REP">Representative</option>
                        </select>
                        <select class="select-input" name="department">
                            <option value="" disabled selected>Department</option>
                            <option value="ceas">College of Education, Arts, and Sciences</option>
                            <option value="coe">College of Engineering</option>
                            <option value="cme">College of Management and Entrepreneurship</option>
                            <option value="cot">College of Technology</option>
                        </select>
                        <select class="select-input" name="session">
                            <option value="" disabled selected>Session</option>
                            <option value="day">Day</option>
                            <option value="night">Night</option>
                        </select>
                        <input type="file" name="photo" class="form-input" placeholder="Profile Picture">
                        </div>
                    <div class="form-group">
                        <div style="flex-grow: 1;"></div>
                        <button type="submit" class="register-btn" id="formSubmitBtn">Register</button>
                        <button type="button" class="register-btn bg-gray-500 hover:bg-gray-600" id="cancelEditBtn" style="display: none;">Cancel</button>
                    </div>
                </form>
            </div>

            <h1 class="text-2xl font-bold mb-4">Manage Candidate</h1>
            <div class="overflow-y-auto max-h-[470px] border border-blue-900 rounded-lg">
                 <table class="min-w-full table-auto">
                    <thead class="bg-blue-900 text-white sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Position</th>
                            <th class="px-4 py-2 text-left">Department</th>
                            <th class="px-4 py-2 text-left">Session</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($candidates as $candidate)
                        <tr>
                            <td class="px-4 py-2">{{ $candidate->first_name}} {{ substr($candidate->middle_name, 0, 1) }}. {{ $candidate->last_name}}</td>
                            <td class="px-4 py-2">{{ $candidate->position_full }}</td>
                            <td class="px-4 py-2">
                                @switch($candidate->department)
                                    @case('cot')
                                        College of Technology
                                        @break
                                    @case('cme')
                                        College of Management and Entrepreneurship
                                        @break
                                    @case('ceas')
                                        College of Education, Arts, and Sciences
                                        @break
                                    @case('coe')
                                        College of Engineering
                                        @break
                                    @default
                                    Unknown Department
                                @endswitch
                            </td>
                            <td class="px-4 py-2">{{ ucfirst($candidate->session) }}</td>
                            <td class="px-4 py-2 text-center flex justify-center">
                                <button class="action-btn edit-btn"
                                data-id="{{ $candidate->id }}"
                                data-first_name="{{ $candidate->first_name }}"
                                data-middle_name="{{ $candidate->middle_name }}"
                                data-last_name="{{ $candidate->last_name }}"
                                data-position="{{ $candidate->position }}"
                                data-department="{{ $candidate->department }}"
                                data-session="{{ $candidate->session }}">Edit</button>
                                <form action="{{ route('candidates.destroy', $candidate->id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="action-btn delete-btn show-confirm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        
        @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#1e40af'
        });
    @endif
</script>

</body>
</html>