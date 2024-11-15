<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blockchain Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        .fade-out {
            animation: fadeOut 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        /* Tailwind overrides for DataTables */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            @apply px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-full;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            @apply bg-blue-500 text-white;
        }
        .dataTables_wrapper .dataTables_filter input {
            @apply border border-gray-300 p-2 rounded-full;
        }
        .dataTables_wrapper .dataTables_length select {
            @apply border border-gray-300 p-2 rounded-full;
        }
        .dataTables_wrapper .dataTables_info {
            @apply text-gray-600;
        }
        /* Custom pagination styles */
        .pagination {
            @apply flex items-center space-x-1;
        }
        .pagination .page-item {
            @apply rounded-full border border-gray-300 bg-white text-gray-700;
        }
        .pagination .page-item.active,
        .pagination .page-item:hover {
            @apply bg-blue-500 text-white;
        }
        .pagination .page-item .page-link {
            @apply px-4 py-2;
        }
        .pagination .page-link {
            @apply rounded-full border border-gray-300;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Blockchain Data</h1>

        <div class="bg-white shadow-lg rounded-lg overflow-x-auto">
            <table id="main-table" class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hedera File ID</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($paginatedRecords as $index => $record)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record['data']['batch_number'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 hover:underline">
                                <span class="tooltip" data-tooltip="Click to view lots">
                                    <a href="#" onclick="showPopup({{ json_encode($record['data']) }})">
                                        {{ $record['file_id'] }}
                                    </a>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="mt-6">
            <div class="pagination">
                {{ $paginatedRecords->links('pagination::tailwind') }}
            </div>
        </div>

        <!-- Popup Modal -->
        <div id="popup-modal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 fade-in">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-4xl relative">
                <h2 class="text-xl font-bold mb-4">Lot Details</h2>
                <div id="popup-content" class="overflow-x-auto"></div>
                <button onclick="closePopup()" class="absolute top-2 right-2 bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">Close</button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function() {
        // Initialize DataTable for the main table
        var table = $('#main-table').DataTable({
            paging: true,
            pageLength: 10,
            lengthMenu: [10, 15, 20, 25, 'All', [5, 10, 15, 20, 25, 50, 100]],
            searching: true,
            ordering: true,
            language: {
                paginate: {
                    previous: '<',
                    next: '>'
                },
                search: 'Search:',
                lengthMenu: 'Show _MENU_ entries',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoFiltered: '(filtered from _MAX_ total entries)'
            }
        });

        // Establish an SSE connection
        const eventSource = new EventSource('/stream-updates');

        // Listen for messages from the server
        eventSource.onmessage = function(event) {
            const newData = JSON.parse(event.data);

            // Clear the existing table data
            table.clear();

            // Append the new data to the table
            newData.forEach(function(record, index) {
                table.row.add([
                    index + 1,
                    record.data.batch_number,
                    `<a href="#" onclick="showPopup(${JSON.stringify(record.data)})" class="text-blue-600 hover:underline">${record.file_id}</a>`
                ]).draw(false); // 'false' keeps the pagination and page state
            });
        };

        eventSource.onerror = function(error) {
            console.error('EventSource failed:', error);
        };
    });

    function showPopup(data) {
        const popupModal = document.getElementById('popup-modal');
        const popupContent = document.getElementById('popup-content');

        // Clear existing content
        popupContent.innerHTML = '';

        // Create table for lots and collections
        let tableHTML = `
            <table id="popup-table" class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Collection Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
        `;
        
        let totalQuantity = 0;
        data.lots.forEach(lot => {
            lot.collections.forEach(collection => {
                tableHTML += `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${collection.collection_number}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${collection.collection_quantity}</td>
                    </tr>
                `;
                totalQuantity += parseFloat(collection.collection_quantity);
            });
        });

        tableHTML += `
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Quantity</td>
                        <td class="px-6 py-3 text-sm text-gray-500">${totalQuantity}</td>
                    </tr>
                </tfoot>
            </table>
        `;

        popupContent.innerHTML = tableHTML;

        // Initialize DataTable for the popup table
        $('#popup-table').DataTable({
            paging: true,
            pageLength: 10,
            lengthMenu: [10, 15, 20, 25, 'All', [5, 10, 15, 20, 25, 50, 100]],
            searching: true,
            ordering: true,
            language: {
                paginate: {
                    previous: '<',
                    next: '>'
                },
                search: 'Search:',
                lengthMenu: 'Show _MENU_ entries',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoFiltered: '(filtered from _MAX_ total entries)'
            }
        });

        popupModal.classList.remove('hidden');
    }

    function closePopup() {
        document.getElementById('popup-modal').classList.add('hidden');
    }
</script>

</body>
</html>
