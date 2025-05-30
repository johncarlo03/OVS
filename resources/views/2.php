<script type="text/javascript">
    var gk_isXlsx = false;
    var gk_xlsxFileLookup = {};
    var gk_fileData = {};
    function filledCell(cell) {
      return cell !== '' && cell != null;
    }
    function loadFileData(filename) {
    if (gk_isXlsx && gk_xlsxFileLookup[filename]) {
        try {
            var workbook = XLSX.read(gk_fileData[filename], { type: 'base64' });
            var firstSheetName = workbook.SheetNames[0];
            var worksheet = workbook.Sheets[firstSheetName];

            // Convert sheet to JSON to filter blank rows
            var jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1, blankrows: false, defval: '' });
            // Filter out blank rows (rows where all cells are empty, null, or undefined)
            var filteredData = jsonData.filter(row => row.some(filledCell));

            // Heuristic to find the header row by ignoring rows with fewer filled cells than the next row
            var headerRowIndex = filteredData.findIndex((row, index) =>
              row.filter(filledCell).length >= filteredData[index + 1]?.filter(filledCell).length
            );
            // Fallback
            if (headerRowIndex === -1 || headerRowIndex > 25) {
              headerRowIndex = 0;
            }

            // Convert filtered JSON back to CSV
            var csv = XLSX.utils.aoa_to_sheet(filteredData.slice(headerRowIndex)); // Create a new sheet from filtered array of arrays
            csv = XLSX.utils.sheet_to_csv(csv, { header: 1 });
            return csv;
        } catch (e) {
            console.error(e);
            return "";
        }
    }
    return gk_fileData[filename] || "";
    }
    </script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">
    <style>
        .custom-border {
            border: 2px solid #1e40af;
            border-radius: 10px;
        }
        .candidate-button {
            background-color: #f3f4f6;
            border-radius: 20px;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .candidate-button.selected {
            background-color: #3b82f6;
            color: white;
        }
        .candidate-button:hover {
            background-color: #e5e7eb;
        }
        .logout-button, .submit-button {
            background-color: #1e40af;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .logout-button:hover, .submit-button:hover {
            background-color: #1e3a8a;
        }
        .centered-title {
            text-align: center;
        }
    </style>

</head>
<body class="bg-white py-5 !bg-white">

    <div class="container">
        @auth
            <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-1/4 bg-white p-4 custom-border m-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center mb-4">
                    <img src="{{ URL('images/profile.png') }}" alt="Profile" class="rounded-full w-10 h-10" style="margin-right:10px">
                    <div>
                        <p class="font-semibold">PISAO, KENDRA MAXENE L.</p>
                        <p class="text-sm text-gray-600">Bachelor of Science in Information Technology</p>
                    </div>
                </div>
                <h2 class="text-lg font-bold mb-2">» Candidate Positions</h2>
            </div>
            <form action="/logout" method="POST">
            @csrf
                <button class="w-full logout-button">Logout</button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-4 flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-center mb-4">
                <img src="https://via.placeholder.com/100" alt="Logo" class="mr-2">
                <h1 class="text-2xl font-bold text-blue-900">CTU-DANAO ONLINE VOTING SYSTEM</h1>
            </div>

            <!-- Voting Sections -->
            <div class="bg-white custom-border p-4 mb-4">
                <h2 class="text-xl font-bold text-blue-900 mb-2 centered-title">President</h2>
                <div class="flex gap-4 justify-center">
                    <div class="candidate-button" onclick="toggleSelection(this, 'President', 'Carpenter, Sabrina')">
                        <img src="{{ URL('images/profile.png') }}" alt="Profile" class="rounded-full w-8 h-8">
                        <span>Carpenter, Sabrina</span>
                    </div>
                    <div class="candidate-button" onclick="toggleSelection(this, 'President', 'Rodrigo, Olivia')">
                        <img src="{{ URL('images/profile.png') }}" alt="Profile" class="rounded-full w-8 h-8">
                        <span>Rodrigo, Olivia</span>
                    </div>
                </div>
            </div>

            <div class="bg-white custom-border p-4 mb-4">
                <h2 class="text-xl font-bold text-blue-900 mb-2 centered-title">Vice President</h2>
                <div class="flex gap-4 justify-center">
                    <div class="candidate-button" onclick="toggleSelection(this, 'Vice President', 'Beyonce')">
                        <img src="{{ URL('images/profile.png') }}" alt="Profile" class="rounded-full w-8 h-8">
                        <span>Beyonce</span>
                    </div>
                    <div class="candidate-button" onclick="toggleSelection(this, 'Vice President', 'Lady Gaga')">
                        <img src="{{ URL('images/profile.png') }}" alt="Profile" class="rounded-full w-8 h-8">
                        <span>Lady Gaga</span>
                    </div>
                </div>
            </div>

            <div class="bg-white custom-border p-4 flex-1">
                <h2 class="text-xl font-bold text-blue-900 mb-2 centered-title">Officers</h2>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Secretary</h3>
                        <div class="candidate-button" onclick="toggleSelection(this, 'Secretary', '-')">
                            <img src="{{ URL('images/profile.png') }}" alt="Profile" class="rounded-full w-8 h-8">
                            <span>-</span>
                        </div>
                        <div class="candidate-button mt-2" onclick="toggleSelection(this, 'Secretary', '-')">
                            <img src="{{ URL('images/profile.png') }}" alt="Profile" class="rounded-full w-8 h-8">
                            <span>-</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Auditor</h3>
                        <div class="candidate-button" onclick="toggleSelection(this, 'Auditor', '-')">
                            <img src="{{ URL('images/profile.png') }}" alt="Profile" class="rounded-full w-8 h-8">
                            <span>-</span>
                        </div>
                        <div class="candidate-button mt-2" onclick="toggleSelection(this, 'Auditor', '-')">
                            <img src="{{ URL('images/profile.png') }}" alt="Profile" class="rounded-full w-8 h-8">
                            <span>-</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Representatives</h3>
                        <div class="candidate-button" onclick="toggleSelection(this, 'Representatives', '-')">
                            <img src="{{ URL('images/profile.png') }}" alt="Profile" class="rounded-full w-8 h-8">
                            <span>-</span>
                        </div>
                        <div class="candidate-button mt-2" onclick="toggleSelection(this, 'Representatives', '-')">
                            <img src="{{ URL('images/profile.png') }}" alt="Profile" class="rounded-full w-8 h-8">
                            <span>-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Vote Button -->
            <div class="flex justify-end mt-4">
                <button class="submit-button">Submit Vote</button>
            </div>
        </div>
    </div>

        @else 
        <div class="container">
            <form action="/login" method="POST">
                @csrf
            <div class="login-box">
              <img src="{{ URL('images/logo.png') }}" alt="Logo" class="logo" />
              <h2>CTU-DANAO ONLINE VOTING SYSTEM</h2>
        
              <div class="mode-toggle" id="modeToggle">
                <button class="toggle-btn active" data-mode="manual">MANUAL</button>
                <button class="toggle-btn" data-mode="rfid">RFID</button>
              </div>
        
              <div class="input-group">
                <input name="student_id" type="text" placeholder="School ID" id="schoolId" required/>
                <span class="icon">👤</span>
              </div>

              <button class="login-btn" type="submit">LOGIN</button>
        
              <a href="#" class="forgot-password">Forgot password?</a>
            </div>
        </form>
          </div>
        @endauth
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let selectedCandidates = {};

        function toggleSelection(element, position, candidate) {
            const buttons = document.querySelectorAll(`.candidate-button[onclick*="${position}"]`);
            buttons.forEach(btn => btn.classList.remove('selected'));

            if (selectedCandidates[position] === candidate) {
                delete selectedCandidates[position];
                element.classList.remove('selected');
            } else {
                selectedCandidates[position] = candidate;
                element.classList.add('selected');
            }
        }
    </script>
</body>
</html>
