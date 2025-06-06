<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Chấm Công') }}</title>

    <!-- Pikaday CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.8.1/css/pikaday.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Pikaday JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.8.1/pikaday.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    {{-- js model--}}
    <script src="{{ asset('js/model-employee.js') }}"></script>
    <script src="{{ asset('js/model-shift.js') }}"></script>
    <script src="{{ asset('js/model-overtime.js') }}"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Success popup -->
    <x-success-model/>
</head>
<body class="font-sans antialiased bg-gray-100">

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    @auth
        @if(auth()->user()->role === 'manager')
            @include('layouts.manager-navigation')
        @else
            @include('layouts.employee-navigation')
        @endif
    @endauth

    <!-- Bao gồm Modal -->
    <x-user-model/>

    <!-- Page Heading -->
    @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>
</div>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            responsive: true,
            language: {
                "sEmptyTable": "Không có dữ liệu",
                "sInfo": "Hiển thị _START_ đến _END_ trong tổng số _TOTAL_ bản ghi",
                "sInfoEmpty": "Hiển thị 0 đến 0 trong tổng số 0 bản ghi",
                "sInfoFiltered": "(được lọc từ _MAX_ bản ghi)",
                "sLengthMenu": "Hiển thị _MENU_ bản ghi",
                "sLoadingRecords": "Đang tải...",
                "sProcessing": "Đang xử lý...",
                "sSearch": "Tìm kiếm:",
                "sZeroRecords": "Không tìm thấy dữ liệu phù hợp",
                "oPaginate": {
                    "sFirst": "Đầu tiên",
                    "sPrevious": "Trước",
                    "sNext": "Tiếp theo",
                    "sLast": "Cuối cùng"
                },
                "oAria": {
                    "sSortAscending": ": Sắp xếp theo thứ tự tăng dần",
                    "sSortDescending": ": Sắp xếp theo thứ tự giảm dần"
                }
            }
        });
    });
</script>

</body>
</html>

<style>
    /* Tạo phong cách bảng đẹp hơn */
    table.dataTable {
        background-color: #ffffff;
        border-radius: 1.25rem;  /* Góc bo tròn mềm mại */
        box-shadow: 0px 6px 25px rgba(0, 0, 0, 0.1); /* Bóng mờ nhẹ nhưng sang trọng */
        width: 100%;
        overflow: hidden;  /* Tránh bảng bị tràn ra ngoài */
        border: none;
    }

    /* Kiểu chữ và padding cho các ô bảng */
    table.dataTable th,
    table.dataTable td {
        padding: 18px 24px; /* Padding rộng hơn giúp bảng thoáng hơn */
        font-size: 14px;
        color: #444;        /* Màu chữ nhẹ nhàng */
        text-align: left;   /* Căn trái cho các cột */
        font-family: 'Helvetica Neue', sans-serif; /* Font chữ hiện đại */
        font-weight: 400;   /* Độ đậm của chữ hợp lý */
        transition: background-color 0.3s ease; /* Mượt mà khi hover */
    }

    /* Tiêu đề cột */
    table.dataTable th {
        background-color: #007BFF;  /* Màu nền nhẹ nhàng và hiện đại */
        color: #fff;                /* Chữ màu trắng */
        font-weight: 600;           /* Tiêu đề đậm hơn một chút */
        font-size: 16px;            /* Kích thước chữ lớn hơn */
        text-transform: uppercase;  /* Chữ in hoa */
        letter-spacing: 1px;        /* Khoảng cách giữa các chữ */
    }

    /* Hiệu ứng cho các hàng khi hover */
    table.dataTable tbody tr:hover {
        background-color: #e0f7fa;   /* Màu nền nhẹ nhàng khi hover */
        cursor: pointer;             /* Hiển thị con trỏ tay */
        transition: background-color 0.3s ease; /* Hiệu ứng mượt mà */
    }

    /* Hiệu ứng cho nút phân trang */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        background-color: #007BFF;
        color: white;
        border-radius: 0.5rem;
        padding: 10px 20px;
        margin: 0 6px;
        font-size: 14px;
        text-align: center;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    /* Hiệu ứng hover cho nút phân trang */
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #0056b3;
        transform: scale(1.1); /* Hiệu ứng phóng to khi hover */
    }

    /* Nút phân trang khi được chọn */
    .dataTables_wrapper .dataTables_paginate .paginate_button.selected {
        background-color: #004085;
        border: 2px solid #002752;  /* Viền màu đậm khi được chọn */
    }

    /* Cải thiện thanh tìm kiếm */
    .dataTables_filter input {
        padding: 12px 16px;
        border-radius: 0.5rem;
        border: 1px solid #ddd;
        margin-left: 12px;
        width: 250px;
        font-size: 14px;
        transition: border-color 0.3s ease;
        outline: none; /* Loại bỏ viền focus mặc định */
    }

    /* Hiệu ứng khi thanh tìm kiếm có focus */
    .dataTables_filter input:focus {
        border-color: #007BFF;
    }

    /* Cải thiện thanh phân trang */
    .dataTables_info {
        font-size: 14px;
        color: #555;
    }

    /* Cải thiện kiểu hiển thị thông tin tổng cộng */
    .dataTables_length select {
        padding: 12px 16px;
        border-radius: 0.5rem;
        border: 1px solid #ddd;
        font-size: 14px;
        margin-right: 12px;
        transition: border-color 0.3s ease;
    }

    /* Hiệu ứng cho ô chọn số lượng bản ghi khi focus */
    .dataTables_length select:focus {
        border-color: #007BFF;
        outline: none;
    }

    /* Tối ưu bảng trên các thiết bị di động */
    @media (max-width: 768px) {
        table.dataTable {
            width: 100%; /* Làm bảng chiếm toàn bộ chiều rộng */
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 8px 14px;
        }

        .dataTables_filter input {
            width: 200px;  /* Giảm chiều rộng thanh tìm kiếm trên di động */
        }
    }
</style>
