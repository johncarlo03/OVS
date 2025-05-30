<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Management - CTU-Danao OVS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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
            <a href="/voter" class="active">Voters</a>
            <a href="/candidates">Candidates</a>
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
            <h1 class="text-2xl font-bold mb-4">Register Voter</h1>
            <div class="form-container">
                <form id="registerForm" method="POST" action="{{ route('voter.store') }}" enctype="multipart/form-data">
                    @csrf
                {{-- <form id="registerForm" method="POST" action="{{ route('voter.store') }}" enctype="multipart/form-data"> --}}
                    <div class="form-group">
                        <input type="text" name="first_name" class="form-input" placeholder="First Name" required>
                        <input type="text" name="middle_name" class="form-input" placeholder="Middle Name" required>
                        <input type="text" name="last_name" class="form-input" placeholder="Last Name" required>
                        <input type="number" name="student_id" class="form-input" placeholder="ID #" required>
                        <input type="number" name="rfid" class="form-input" placeholder="RFID" required>
                        <select class="select-input" name="department" id="departmentSelect" required>
                            <option value="" disabled selected>Department</option>
                            <option value="ceas">College of Education and Arts</option>
                            <option value="coe">College of Engineering</option>
                            <option value="cme">College of Management and Entrepreneurship</option>
                            <option value="cot">College of Technology</option>
                        </select>
                        <select class="select-input" name="session" required>
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

            <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Manage Voters</h1>
    <form method="GET" action="{{ url('/voter') }}" class="flex items-center gap-2">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, ID, or RFID"
        class="border border-gray-300 rounded-full px-4 py-2 w-72 focus:outline-none focus:ring-2 focus:ring-blue-500">
    <button type="submit"
        class="bg-blue-800 text-white px-4 py-2 rounded-full hover:bg-blue-900 transition">
        Search
    </button>
</form>

</div>

            <div class="overflow-y-auto max-h-[467px] border border-blue-900 rounded-lg">
                 <table class="min-w-full table-auto">
                    <thead class="bg-blue-900 text-white sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">RFID</th>
                            <th class="px-4 py-2 text-left">Department</th>
                            <th class="px-4 py-2 text-left">Session</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($voters as $voter)
                        <tr>
                            <td class="px-4 py-2">{{ $voter->first_name}} {{ substr($voter->middle_name, 0, 1) }}. {{ $voter->last_name}}</td>
                            <td class="px-4 py-2">{{ $voter->student_id}}</td>
                            <td class="px-4 py-2">{{ $voter->rfid}}</td>
                            <td class="px-4 py-2">
                                @switch($voter->department)
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
                            <td class="px-4 py-2">{{ ucfirst($voter->session)}}</td>
                            <td class="px-4 py-2">
                                @switch($voter->has_voted)
                                    @case('1')
                                        <span class="inline-block px-3 py-1 text-sm font-semibold text-green-700 bg-green-100 rounded-full">
                                            Voted
                                        </span>
                                        @break
                                    @case('0')
                                        <span class="inline-block px-3 py-1 text-sm font-semibold text-red-700 bg-red-100 rounded-full">
                                            Not Voted
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-block px-3 py-1 text-sm font-semibold text-gray-600 bg-gray-100 rounded-full">
                                            Unknown
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-4 py-2 text-center flex justify-center">
                                <button class="action-btn edit-btn"
                                data-id="{{ $voter->id }}"
                                data-first_name="{{ $voter->first_name }}"
                                data-middle_name="{{ $voter->middle_name }}"
                                data-last_name="{{ $voter->last_name }}"
                                data-student_id="{{ $voter->student_id }}"
                                data-rfid="{{ $voter->rfid }}"
                                data-department="{{ $voter->department }}"
                                data-session="{{ $voter->session }}">Edit</button>
                                <form action="{{ route('voter.destroy', $voter->id) }}" method="POST" class="delete-form">
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
    const form = document.getElementById('registerForm');
    const submitBtn = document.getElementById('formSubmitBtn');
    const cancelBtn = document.getElementById('cancelEditBtn');

    const originalAction = form.action;
    const originalMethod = form.method;

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            // Fill fields
            form.querySelector('[name="first_name"]').value = this.dataset.first_name;
            form.querySelector('[name="middle_name"]').value = this.dataset.middle_name;
            form.querySelector('[name="last_name"]').value = this.dataset.last_name;
            form.querySelector('[name="student_id"]').value = this.dataset.student_id;
            form.querySelector('[name="rfid"]').value = this.dataset.rfid;
            form.querySelector('[name="department"]').value = this.dataset.department;
            form.querySelector('[name="session"]').value = this.dataset.session;

            // Change form action/method
            form.action = `/voter/update/${this.dataset.id}`;
            form.method = 'POST';
            submitBtn.textContent = 'Update';
            cancelBtn.style.display = 'inline-block';

            // Add _method for PUT
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';
        });
    });

    // Handle Cancel
    cancelBtn.addEventListener('click', function () {
        // Clear form fields
        form.reset();

        // Reset form action/method
        form.action = originalAction;
        form.method = originalMethod;
        submitBtn.textContent = 'Register';
        cancelBtn.style.display = 'none';

        // Remove _method field if exists
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) {
            methodInput.remove();
        }
    });

    form.addEventListener('submit', function (e) {
        // Check if form is in update mode
        if (submitBtn.textContent === 'Update') {
            e.preventDefault(); // Prevent immediate submission

            Swal.fire({
                title: 'Confirm Update',
                text: 'Are you sure you want to update this candidate?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#1e40af',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Proceed with the update
                }
            });
        }
    });

    // Attach delete confirmation logic to all delete buttons
    document.querySelectorAll('.show-confirm').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('.delete-form');

            Swal.fire({
                title: 'Are you sure?',
                text: "This voter will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Display success alert if present in session
    @if(session('success'))
    Swal.fire({
        title: 'Success!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#1e40af'
    });
    @endif

    @if ($errors->any())
    @php
        $errorMsg = implode('\n', $errors->all());
    @endphp
    Swal.fire({
        title: 'Validation Error',
        text: `{!! $errorMsg !!}`,
        icon: 'error',
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'OK'
    });
@endif

// Search bar filtering
document.getElementById('voterSearch').addEventListener('keyup', function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});

</script>

</body>
</html>