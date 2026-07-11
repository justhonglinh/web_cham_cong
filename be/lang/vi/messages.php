<?php

return [

    'auth' => [
        'invalid_credentials'  => 'Thông tin đăng nhập không chính xác.',
        'logout_success'       => 'Đăng xuất thành công.',
        'profile_updated'      => 'Cập nhật thông tin thành công.',
        'password_incorrect'   => 'Mật khẩu hiện tại không đúng.',
        'password_updated'     => 'Đổi mật khẩu thành công.',
        'reset_link_sent'      => 'Đã gửi liên kết đặt lại mật khẩu tới email của bạn.',
        'reset_link_failed'    => 'Không thể gửi liên kết đặt lại mật khẩu. Vui lòng thử lại sau.',
        'password_reset'       => 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập.',
        'invalid_reset_token'  => 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.',
    ],

    'user' => [
        'created' => 'Tạo tài khoản nhân viên thành công.',
        'updated' => 'Cập nhật tài khoản nhân viên thành công.',
        'deleted' => 'Xóa tài khoản nhân viên thành công.',
    ],

    'shift' => [
        'created'          => 'Tạo ca làm việc thành công.',
        'updated'          => 'Cập nhật ca làm việc thành công.',
        'deleted'          => 'Xóa ca làm việc thành công.',
        'delete_forbidden' => 'Không thể xóa ca làm đang được sử dụng cho các ngày hiện tại hoặc tương lai.',
    ],

    'attendance' => [
        'updated'          => 'Cập nhật chấm công thành công.',
        'checked'          => 'Chấm công thành công.',
        'already_done'     => 'Đã chấm công đầy đủ cho ngày hôm nay.',
        'location_required' => 'Vui lòng bật định vị (GPS) để chấm công.',
        'outside_radius'   => 'Bạn đang ở ngoài phạm vi cho phép chấm công.',
    ],

    'overtime' => [
        'shift_created'      => 'Tạo ca tăng ca thành công.',
        'shift_updated'      => 'Cập nhật ca tăng ca thành công.',
        'shift_deleted'      => 'Xóa ca tăng ca thành công.',
        'request_updated'    => 'Cập nhật yêu cầu tăng ca thành công.',
        'already_registered' => 'Bạn đã đăng ký ca này rồi.',
        'registered'         => 'Đăng ký tăng ca thành công.',
        'unregistered'       => 'Hủy đăng ký tăng ca thành công.',
    ],

    'leave' => [
        'status_updated' => 'Cập nhật trạng thái đơn nghỉ phép thành công.',
        'created'        => 'Gửi đơn nghỉ phép thành công.',
        'deleted'        => 'Hủy đơn nghỉ phép thành công.',
    ],

    'location' => [
        'created'        => 'Tạo địa điểm thành công.',
        'updated'        => 'Cập nhật địa điểm thành công.',
        'deleted'        => 'Xóa địa điểm thành công.',
        'status_updated' => 'Cập nhật trạng thái địa điểm thành công.',
    ],

];
