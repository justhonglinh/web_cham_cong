# 🕐 Web Chấm Công

Hệ thống quản lý chấm công trực tuyến với nhận diện khuôn mặt, hỗ trợ phân quyền Manager/Employee, quản lý ca làm việc, tăng ca, nghỉ phép và tổng hợp công.

**Demo:** [web-cham-cong-teal.vercel.app](https://web-cham-cong-teal.vercel.app)

---

## 🛠 Tech Stack

| Thành phần | Công nghệ |
|---|---|
| Backend | Laravel 12, PHP 8.2, Laravel Sanctum |
| Frontend | Nuxt 4, Vue 3, TypeScript, Pinia |
| Database | PostgreSQL (production), MySQL (Docker local) |
| Styling | TailwindCSS |
| Face API | Face++ (FacePlusPlus) |
| Deploy BE | Render |
| Deploy FE | Vercel |

---

## 📁 Cấu trúc dự án

```
web_cham_cong/
├── be/          # Backend - Laravel 12
└── fe/          # Frontend - Nuxt 4
```

---

## ✨ Tính năng

### 👔 Manager (Quản lý)
- Dashboard thống kê tổng quan
- Quản lý nhân viên (thêm, sửa, xóa)
- Quản lý ca làm việc
- Quản lý ca tăng ca & duyệt yêu cầu tăng ca
- Xem & chỉnh sửa bảng chấm công
- Duyệt đơn nghỉ phép
- Tổng hợp công & xuất Excel
- Quản lý địa điểm chấm công

### 👷 Employee (Nhân viên)
- Dashboard cá nhân
- Chấm công bằng **nhận diện khuôn mặt** (Face++)
- Xem lịch sử chấm công
- Đăng ký / hủy tăng ca
- Nộp & quản lý đơn nghỉ phép

---

## 🚀 Cài đặt & Chạy local

### Yêu cầu
- PHP >= 8.2, Composer
- Node.js >= 18, npm
- PostgreSQL hoặc MySQL

---

### Backend (Laravel)

```bash
cd be
cp .env.example .env
# Cập nhật thông tin DB và FACEPLUSPLUS_API_KEY trong .env

composer install
php artisan key:generate
php artisan migrate
php artisan serve
```

Backend chạy tại: `http://localhost:8000`

#### Chạy bằng Docker

```bash
cd be
docker-compose up -d
```

---

### Frontend (Nuxt)

```bash
cd fe
npm install
npm run dev
```

Frontend chạy tại: `http://localhost:3000`

---

## ⚙️ Biến môi trường quan trọng

### Backend (`be/.env`)

| Biến | Mô tả |
|---|---|
| `DB_CONNECTION` | Loại database (`pgsql` hoặc `mysql`) |
| `DB_HOST` | Host database |
| `DB_DATABASE` | Tên database |
| `DB_USERNAME` / `DB_PASSWORD` | Thông tin đăng nhập DB |
| `FRONTEND_URL` | URL frontend (dùng cho CORS) |
| `SANCTUM_STATEFUL_DOMAINS` | Domain frontend cho Sanctum |
| `FACEPLUSPLUS_API_KEY` | API Key của Face++ |
| `FACEPLUSPLUS_API_SECRET` | API Secret của Face++ |

---

## 📡 API Endpoints chính

| Method | Endpoint | Mô tả | Auth |
|---|---|---|---|
| POST | `/api/login` | Đăng nhập | Public |
| POST | `/api/register` | Đăng ký | Public |
| POST | `/api/logout` | Đăng xuất | ✅ |
| GET | `/api/user` | Thông tin user hiện tại | ✅ |
| GET | `/api/dashboard` | Dashboard | ✅ |
| POST | `/api/face-compare` | So sánh khuôn mặt | ✅ |
| GET | `/api/users` | Danh sách nhân viên | Manager |
| GET | `/api/attendance/management` | Bảng chấm công | Manager |
| GET | `/api/work-summary/export` | Xuất Excel tổng hợp công | Manager |
| POST | `/api/employees/attendance` | Chấm công | Employee |
| POST | `/api/employees/leave` | Nộp đơn nghỉ phép | Employee |

---

## 🗄 Cơ sở dữ liệu

Các bảng chính:
- `users` — Tài khoản người dùng (role: `manager` / `employee`)
- `user_details` — Thông tin chi tiết nhân viên
- `shifts` — Ca làm việc
- `attendances` — Bản ghi chấm công
- `overtime_shifts` — Ca tăng ca
- `overtime_requests` — Yêu cầu tăng ca
- `leave_requests` — Đơn nghỉ phép
- `locations` — Địa điểm chấm công
- `work_summary` — Tổng hợp công

---

## 👥 Contributors

Xem danh sách contributors tại [GitHub Contributors](https://github.com/justhonglinh/web_cham_cong/graphs/contributors)
