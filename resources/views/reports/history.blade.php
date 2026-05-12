<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Report - {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
            body {
                background-color: white !important;
                padding: 0;
            }
            .report-container {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: none !important;
            }
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="p-4">

    <div class="max-w-5xl mx-auto overflow-hidden bg-white border border-gray-100 shadow-2xl rounded-3xl report-container">
        <!-- Header -->
        <div class="bg-[#132C51] p-8 text-white flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">Task History Report</h1>
                <p class="mt-1 opacity-80">Generated for {{ $user->name }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm opacity-80">Date Generated:</p>
                <p class="font-semibold">{{ now()->format('d F Y, H:i') }}</p>
            </div>
        </div>

        <!-- Content -->
        <div class="p-8">
            <div class="flex items-end justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-[#132C51]">Completed Tasks</h2>
                    <p class="text-gray-500">Summary of all finished work</p>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 border border-green-200 rounded-full">
                        Total Completed: {{ $historyTasks->count() }}
                    </span>
                </div>
            </div>

            <div class="">
                <table class="w-full text-left border-collapse table-fixed">
                    <thead>
                        <tr class="border-b-2 border-gray-100">
                            <th class="py-4 px-2 font-bold text-[#132C51] w-12 text-center">No</th>
                            <th class="py-4 px-4 font-bold text-[#132C51] w-auto">Task Title</th>
                            <th class="py-4 px-4 font-bold text-[#132C51] w-24 text-center">Priority</th>
                            <th class="py-4 px-4 font-bold text-[#132C51] w-32 text-center">Due Date</th>
                            <th class="py-4 px-4 font-bold text-[#132C51] w-40 text-center">Completed At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historyTasks as $index => $task)
                            <tr class="transition-colors border-b border-gray-50 hover:bg-gray-50">
                                <td class="px-2 py-4 font-medium text-center text-gray-500 align-top">{{ $index + 1 }}</td>
                                <td class="px-4 py-4 align-top">
                                    <div class="font-semibold text-gray-800 break-words whitespace-normal">{{ $task->title }}</div>
                                    @if($task->description)
                                        <div class="mt-1 text-xs italic text-gray-500 break-words whitespace-normal">{{ \Illuminate\Support\Str::limit($task->description, 200) }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center align-top">
                                    @php
                                        $priorityColor = match($task->priority) {
                                            'Urgent' => 'bg-red-100 text-red-700 border-red-200',
                                            'High' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                            'Normal' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'Low' => 'bg-green-100 text-green-700 border-green-200',
                                            default => 'bg-gray-100 text-gray-700 border-gray-200',
                                        };
                                    @endphp
                                    <span class="{{ $priorityColor }} text-[10px] font-bold px-2 py-0.5 rounded border inline-block">
                                        {{ $task->priority }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-center text-gray-600 align-top">
                                    {{ $task->due_date->format('d M Y') }}
                                </td>
                                <td class="px-4 py-4 text-sm text-center text-gray-600 align-top">
                                    {{ $task->completed_at ? $task->completed_at->format('d M Y, H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 italic text-center text-gray-400">No history tasks found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between p-6 border-t border-gray-100 bg-gray-50 no-print">
            <button onclick="window.close()" class="font-medium text-gray-500 transition-colors hover:text-gray-700">
                &larr; Back
            </button>
            <button onclick="window.print()" class="bg-[#132C51] text-white px-6 py-2 rounded-xl font-bold hover:bg-[#1C427A] transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:translate-y-0">
                Print Report
            </button>
        </div>
    </div>

</body>
</html>
