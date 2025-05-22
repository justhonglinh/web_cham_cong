$(document).ready(function() {
    $('#myTable').DataTable({
        language: {
            processing:   "Đang xử lý...",
            search:       "Tìm kiếm:",
            lengthMenu:   "Hiển thị _MENU_ bản ghi",
            info:         "Hiển thị _START_ đến _END_ của _TOTAL_ bản ghi",
            infoEmpty:    "Hiển thị 0 đến 0 của 0 bản ghi",
            infoFiltered: "(lọc từ _MAX_ bản ghi)",
            loadingRecords: "Đang tải...",
            zeroRecords:  "Không tìm thấy bản ghi phù hợp",
            emptyTable:   "Không có dữ liệu trong bảng",
            paginate: {
                first:    "Đầu",
                previous: "Trước",
                next:     "Tiếp",
                last:     "Cuối"
            },
            aria: {
                sortAscending:  ": sắp xếp tăng dần",
                sortDescending: ": sắp xếp giảm dần"
            }
        }
    });
});
