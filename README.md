# API Documentation - Backend PHP for Online Printing Website
d
## 1. **Login**
### Endpoint: `POST /tamphuc/api/login.php`
- **Request Headers:**
    - `Content-Type: application/json`
- **Request Body:**
    ```json
    {
        "username": "admin",
        "password": "123456"
    }
    ```
- **Response:**
    ```json
    {
        "success": true,
        "session_token": "9f384172a5482a37c01f08e4c927d7153b6fd7dc7d294f7bfac4ea03c59dcffe",
        "permissions": { "all": true },
        "user_id": 1
    }
    ```

## 2. **Add New User**
### Endpoint: `POST /tamphuc/api/register.php`
- **Request Headers:**
    - `Authorization: 9f384172a5482a37c01f08e4c927d7153b6fd7dc7d294f7bfac4ea03c59dcffe`
    - `Content-Type: application/json`
- **Request Body:**
    ```json
    {
        "username": "user2",
        "password": "password123",
        "permissions": {
            "read": true,
            "write": false,
            "delete": false
        }
    }
    ```
- **Response:**
    ```json
    {
        "success": true,
        "user_id": "4",
        "message": "User registered successfully"
    }
    ```

## 3. **Update User Permissions**
### Endpoint: `PUT /tamphuc/api/update_permissions.php`
- **Request Headers:**
    - `Authorization: d128720a880f55b01b4f8a334f3a787a3bc70b6bc80a302ef220829174e62fc1`
    - `Content-Type: application/json`
- **Request Body:**
    ```json
    {
        "user_id": 2,
        "permissions": {
            "read": true,
            "write": true,
            "delete": false
        }
    }
    ```
- **Response:**
    ```json
    {
        "success": true,
        "message": "Permissions updated successfully"
    }
    ```

## 4. **Check User Role**
### Endpoint: `POST /tamphuc/api/check_role.php`
- **Request Headers:**
    - `Content-Type: application/json`
- **Request Body:**
    ```json
    {
        "session_token": "9f384172a5482a37c01f08e4c927d7153b6fd7dc7d294f7bfac4ea03c59dcffe",
        "feature": "write"
    }
    ```
- **Response:**
    ```json
    {
        "success": true,
        "access_granted": true
    }
    ```

---

# Customer Management API

## 1. **Create Customer**
### Endpoint: `POST /tamphuc/api/customer/create_customer.php`
- **Request Body (raw JSON):**
    ```json
    {
        "session_token": "9f384172a5482a37c01f08e4c927d7153b6fd7dc7d294f7bfac4ea03c59dcffe",
        "customer_name": "Nguyễn Văn A",
        "phone": "0901234567",
        "email": "nguyenvana@example.com",
        "birth_year": "1980",
        "gender": "Nam",
        "note": "Khách hàng VIP",
        "delivery_address": "Đường A, Thành phố B",
        "company_name": "Công ty ABC",
        "tax_code": "123456789",
        "company_email": "abc@company.com",
        "city": "Hà Nội",
        "district": "Quận Hoàn Kiếm",
        "ward": "Phường 1",
        "address": "Số 123, Đường ABC"
    }
    ```

## 2. **Update Customer**
### Endpoint: `POST /tamphuc/api/customer/update_customer.php`
- **Request Body (raw JSON):**
    ```json
    {
        "session_token": "9f384172a5482a37c01f08e4c927d7153b6fd7dc7d294f7bfac4ea03c59dcffe",
        "customer_id": 1,
        "customer_name": "Nguyễn Văn A Cập Nhật",
        "phone": "0901234567",
        "email": "nguyenvana_updated@example.com",
        "birth_year": "1980",
        "gender": "Nam",
        "note": "Khách hàng VIP đã cập nhật",
        "delivery_address": "Đường B, Thành phố C",
        "company_name": "Công ty XYZ",
        "tax_code": "987654321",
        "company_email": "xyz@company.com",
        "city": "Hà Nội",
        "district": "Quận Ba Đình",
        "ward": "Phường 3",
        "address": "Số 456, Đường XYZ"
    }
    ```

## 3. **Delete Customer**
### Endpoint: `POST /tamphuc/api/customer/delete_customer.php`
- **Request Body (raw JSON):**
    ```json
    {
        "session_token": "your_session_token_here",
        "customer_id": 1
    }
    ```

## 4. **Get Customers**
### Endpoint: `POST /tamphuc/api/customer/get_customers.php`
- **Request Body (raw JSON):**
    ```json
    {
        "session_token": "your_session_token_here"
    }
    ```

## 5. **Get Customer by ID**
### Endpoint: `POST /tamphuc/api/customer/get_customer_by_id.php`
- **Request Body (raw JSON):**
    ```json
    {
        "session_token": "your_session_token_here",
        "customer_id": 1
    }
    ```

---

# Printer Management API

## 1. **Add New Printer**
### Endpoint: `POST /tamphuc/api/nhain/add_printer.php`
- **Request Body (raw JSON):**
    ```json
    {
        "session_token": "your_session_token",
        "company_name": "Nhà in ABC",
        "tax_code": "123456789",
        "phone": "0987654321",
        "email": "abc@nhain.com",
        "city": "Hà Nội",
        "district": "Cầu Giấy",
        "ward": "Dịch Vọng Hậu",
        "address": "Số 1, Phố XYZ",
        "note": "Nhà in mới"
    }
    ```

## 2. **Update Printer**
### Endpoint: `POST /tamphuc/api/nhain/update_printer.php`
- **Request Body (raw JSON):**
    ```json
    {
        "session_token": "your_session_token",
        "printer_id": 1,
        "company_name": "Nhà in XYZ",
        "tax_code": "987654321",
        "phone": "0912345678",
        "email": "xyz@nhain.com",
        "city": "TP. Hồ Chí Minh",
        "district": "Quận 1",
        "ward": "Bến Nghé",
        "address": "Số 10, Đường ABC",
        "note": "Nhà in đã thay đổi thông tin"
    }
    ```

## 3. **Delete Printer**
### Endpoint: `POST /tamphuc/api/nhain/delete_printer.php`
- **Request Body (raw JSON):**
    ```json
    {
        "session_token": "your_session_token",
        "printer_id": 1
    }
    ```

## 4. **Get Printers**
### Endpoint: `POST /tamphuc/api/nhain/get_printers.php`
- **Request Body (raw JSON):**
    ```json
    {
        "session_token": "your_session_token",
        "soluong": 10,
        "trang": 1
    }
    ```

## 5. **Get Printer by ID**
### Endpoint: `POST /tamphuc/api/nhain/get_printer_by_id.php`
- **Request Body (raw JSON):**
    ```json
    {
        "session_token": "your_session_token",
        "printer_id": 1
    }
    ```
---

# Order Management API

## 1. **Create Order**
### Endpoint: `POST /tamphuc/api/order/create_order.php`
- **Request Body (raw JSON):**
    ```json
    {
        "session_token": "your_session_token_here",
        "customer_id": 1,
        "recipient_name": "John Doe",
        "recipient_phone": "1234567890",
        "delivery_address": "123 Main St, Hanoi",
        "order_status": 1,
        "notes": "Urgent order",
        "product_details": [
            {
                "product_code": "SP001",
                "quantity": 10
            },
            {
                "product_code": "SP002",
                "quantity": 5
            }
        ]
    }
    ```

## 2. **Get Order**
### Endpoint: `POST /tamphuc/api/order/get_order.php`
- **Request Body (raw JSON):**
    ```json
    {
        "session_token": "your_session_token_here",
        "order_id": 1
    }
    ```

## 3. **Update Order Status**
### Endpoint: `POST /tamphuc/api/order/update_order_status.php`
- **Request Body (raw JSON):**
    ```json
    {
        "session_token": "your_session_token_here",
        "order_id": 1,
        "order_status": 2
    }
    ```

## 4. **Delete Order**
### Endpoint: `POST /tamphuc/api/order/delete_order.php`
- **Request Body (raw JSON):**
    ```json
    {
        "session_token": "your_session_token_here",
        "order_id": 1
    }
    ```
