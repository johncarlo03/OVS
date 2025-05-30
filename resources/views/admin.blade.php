<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard - CTU-Danao OVS</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="flex justify-center items-center p-4">
                <img src="{{ URL('images/logo.png') }}" alt="OVS Logo" class="mr-2 w-20 h-20 " />
            </div>
            <div class="flex items-center mb-4 p-2">
                <img
                    src="{{ asset('uploads/admin/' . Auth::guard('admin')->user()->photo) }}"
                    alt="Profile"
                    class="rounded-full mr-2 w-20 h-20"
                />
                <span style="font-size:20px; font-weight:bold;">ADMIN</span>
            </div>
            <span class="indicator">Reports</span>
            <a href="/admin" class="active">Dashboard</a>
            <span class="indicator">Manage</span>
            <a href="/accounts">Admins</a>
            <a href="/voter">Voters</a>
            <a href="/candidates">Candidates</a>
            <form action="/logout" method="POST" class="flex flex-col justify-end mt-auto">
                @csrf
                <div class="p-4">
                    <button
                        class="w-full text-white px-6 py-2 rounded-full transition bg-[#3f5391] hover:bg-[#2C3D74]"
                    >
                        Logout
                    </button>
                </div>
            </form>
        </div>

        <!-- Main Content -->
        <div class="ml-64 p-6 flex-1 fade-in">
            <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

            <!-- Statistics Cards -->
            <div class="flex flex-wrap justify-center gap-6 mb-6">
                <!-- No. of Candidates -->
                <div
                    class="bg-white text-[#2C3D74] shadow-md rounded-2xl p-6 w-64 hover:shadow-xl transition transform hover:scale-[1.03] duration-300"
                >
                    <div class="flex items-center justify-center mb-4">
                        <!-- 👤 User Icon -->
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-10 h-10 text-blue-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5.121 17.804A10.97 10.97 0 0112 15c2.489 0 4.778.896 6.879 2.383M15 10a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>
                    </div>
                    <p class="text-4xl font-extrabold mb-2 text-center">{{ $candidatesNo }}</p>
                    <p class="text-lg font-medium text-center text-blue-700">No. of Candidates</p>
                </div>

                <!-- Students Voted -->
                <div
                    class="bg-white text-[#2C3D74] shadow-md rounded-2xl p-6 w-64 hover:shadow-xl transition transform hover:scale-[1.03] duration-300"
                >
                    <div class="flex items-center justify-center mb-4">
                        <!-- ✅ Check Icon -->
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-10 h-10 text-blue-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="text-4xl font-extrabold mb-2 text-center">{{ $studentsVoted }}</p>
                    <p class="text-lg font-medium text-center text-blue-700">Students Voted</p>
                </div>

                <!-- Total Voters -->
                <div
                    class="bg-white text-[#2C3D74] shadow-md rounded-2xl p-6 w-64 hover:shadow-xl transition transform hover:scale-[1.03] duration-300"
                >
                    <div class="flex items-center justify-center mb-4">
                        <!-- 🧑‍🤝‍🧑 Users Group Icon -->
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-10 h-10 text-blue-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87
                                  M16 7a4 4 0 11-8 0 4 4 0 018 0z"
                            />
                        </svg>
                    </div>
                    <p class="text-4xl font-extrabold mb-2 text-center">{{ $totalVoters }}</p>
                    <p class="text-lg font-medium text-center text-blue-700">Total Voters</p>
                </div>
            </div>

            <!-- Votes Tally -->
            <h2 class="text-xl font-bold mb-4">Votes Tally</h2>
            <div class="bar-chart relative">
                <div class="flex justify-end absolute top-3 right-5 p-2">
                    <select
                        name=""
                        id="positionFilter"
                        class="ml-auto p-0 bg-white border rounded transition hover:ring-2 hover:ring-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600"
                    >
                        <option value="all">All Positions</option>
                        <option value="P">President</option>
                        <option value="VP">Vice President</option>
                        <option value="GSEC">General Secretary</option>
                        <option value="FSEC">Finance  Secretary</option>
                        <option value="AUD">Auditor</option>
                        <option value="REP">Representative</option>
                    </select>
                </div>
                <canvas id="voteChart"></canvas>
            </div>

            <div class="flex justify-end mb-2">
                <button
                    id="printTableBtn"
                    class="px-4 py-2 mt-5 text-white rounded bg-[#3f5391] hover:bg-[#2C3D74] transition"
                >
                    Print Vote Table
                </button>
            </div>
            <h2 class="text-xl font-bold mb-1">Votes Table</h2>
            <div class="vote-table mt-3 overflow-y-auto max-h-60">
                <table class="w-full">
                    <thead>
                        <tr class="!font-bold">
                            <th>#</th>
                            <th>Positionss</th>
                            <th>Candidate</th>
                            <th>Total Vote</th>
                        </tr>
                    </thead>
                    @foreach($candidates as $index => $candidate)
                    <tr>
                        <td class="text-center"> {{ $index + 1 }}</td>
                        <td class="text-center">{{ $candidate->position_full }}</td>
                        <td class="text-center">
                            {{ $candidate->first_name }} {{ substr($candidate->middle_name, 0, 1) }}.
                            {{ $candidate->last_name }}
                        </td>
                        <td class="text-center">{{ $candidate->total_votes }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Data for the horizontal bar chart
        const voteData = {
            labels: {!! json_encode(
                $candidates->map(function ($c) {
                    $middleInitial = $c->middle_name ? substr($c->middle_name, 0, 1) . '.' : '';

                    return "{$c->first_name} {$middleInitial} {$c->last_name} ({$c->position_full})";
                })
            ) !!},
            datasets: [
                {
                    label: "Votes",
                    data: {!! json_encode($candidates->pluck("total_votes")) !!},
                    backgroundColor: "#1e40af",
                    borderColor: "#1e40af",
                    borderWidth: 1,
                },
            ],
        };

        // Chart configuration
        const config = {
            type: "bar",
            data: voteData,
            options: {
                indexAxis: "y", // This makes it a horizontal bar chart
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5,
                        },
                    },
                    y: {
                        beginAtZero: true,
                    },
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                },
            },
        };

        // Render the chart
        const voteChart = new Chart(document.getElementById("voteChart"), config);
        document
            .getElementById("positionFilter")
            .addEventListener("change", function (event) {
                const selectedPosition = event.target.value;

                // Filter candidates based on the selected position
                let filteredCandidates = {!! json_encode($candidates) !!};

                if (selectedPosition !== "all") {
                    filteredCandidates = filteredCandidates.filter(
                        (candidate) => candidate.position === selectedPosition
                    );
                }

                // Update chart data
                const updatedLabels = filteredCandidates.map(
                    (c) =>
                        `${c.first_name} ${c.middle_name.charAt(
                            0
                        )}. ${c.last_name} (${c.position_full})`
                );
                const updatedData = filteredCandidates.map((c) => c.total_votes);

                voteChart.data.labels = updatedLabels;
                voteChart.data.datasets[0].data = updatedData;

                // Update the chart
                voteChart.update();
            });

        document
            .getElementById("printTableBtn")
            .addEventListener("click", function () {
                const tableContent = document.querySelector(".vote-table").innerHTML;
                const originalContent = document.body.innerHTML;

                document.body.innerHTML = `
            <html>
            <head>
                <title>Print Vote Table</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #333; padding: 8px; text-align: center; }
                    th { background-color: #1e40af; color: white; }
                </style>
            </head>
            <body>
                <h2>Vote Results</h2>
                <table>${tableContent}</table>
            </body>
            </html>
        `;

                window.print();

                // Restore original content after printing
                document.body.innerHTML = originalContent;
                window.location.reload(); // Reload to reset any JS event listeners
            });
    </script>
</body>
</html>
