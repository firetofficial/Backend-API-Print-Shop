# Backend-API-Print-Shop 📄🖨️  

## Backend API for Online Printing Website  

Đây là hệ thống API được xây dựng bằng PHP để quản lý website in ấn trực tuyến. Hệ thống cung cấp đầy đủ các API để quản lý tài khoản nhân viên, khách hàng, đơn hàng, nhà in, danh mục sản phẩm, và nhiều chức năng khác.  

## ✨ Tính năng chính  
- 🔑 **Xác thực & Phân quyền**: Đăng nhập, tạo tài khoản, cập nhật quyền, kiểm tra vai trò người dùng.  
- 👨‍💼 **Quản lý nhân viên**: Lấy danh sách, thêm, sửa, xóa tài khoản nhân viên.  
- 👥 **Quản lý khách hàng**: Tạo, cập nhật, xóa khách hàng, truy vấn thông tin khách hàng.  
- 🖨️ **Quản lý nhà in**: Thêm, cập nhật, xóa, lấy danh sách nhà in.  
- 📦 **Quản lý đơn hàng**: Tạo, cập nhật trạng thái, xóa đơn hàng, truy vấn danh sách đơn hàng.  
- 🔖 **Quản lý danh mục & sản phẩm**: Thêm, cập nhật, xóa danh mục sản phẩm, quản lý sản phẩm với nhiều mức giá.  


## 🚀 Kết hợp với Frontend  
API này được sử dụng cùng với repo **Frontend-Print-Shop** tại đây: [Frontend-Print-Shop](https://github.com/firetofficial/Frontend-Print-Shop).  

**Frontend-Print-Shop** được xây dựng bằng **React + Vite**, cung cấp giao diện người dùng hiện đại, tối ưu hiệu suất. API này hoạt động như backend cho frontend, xử lý logic và dữ liệu của hệ thống in ấn trực tuyến.

## 📌 Hướng dẫn sử dụng  
API được tổ chức theo RESTful, sử dụng token xác thực để bảo vệ dữ liệu. Bạn có thể tham khảo chi tiết từng endpoint trong tài liệu API.  

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
## 5. **Get All Employee Accounts**
### Endpoint: `GET /tamphuc/api/get_all_accounts.php`
- **Request Headers:**
    - `Authorization: <session_token>`
    - `Content-Type: application/json`
- **Response:**
    ```json
    {
        "success": true,
        "accounts": [
            {
                "id": 1,
                "username": "admin",
                "permissions": {
                    "all": true,
                    "read": true,
                    "write": true,
                    "delete": true
                },
                "created_at": "2024-01-01 10:00:00"
            },
            {
                "id": 2,
                "username": "user1",
                "permissions": {
                    "read": true,
                    "write": false,
                    "delete": false
                },
                "created_at": "2024-01-02 11:00:00"
            }
        ]
    }
    ```

---

## 6 **Update Employee Account**
### Endpoint: `PUT /tamphuc/api/update_account.php`
- **Request Headers:**
    - `Authorization: <session_token>`
    - `Content-Type: application/json`
- **Request Body:**
    ```json
    {
        "user_id": 2,
        "username": "new_username",
        "password": "new_password",
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
        "message": "User information updated successfully"
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
    "session_token": "146b70c9495d5a17ccf03c72016e6521e2a065f4cac766894495f88c8080315d",
    "customer_id": 1,
    "recipient_name": "John Doe",
    "recipient_phone": "1234567890",
    "delivery_address": "123 Main St, Hanoi",
    "order_status": 1,
    "notes": "Urgent order",
    "product_details": [
        {
            "product_code": "SP001",
            "quantity": 10,
            "price": 11110
        },
        {
            "product_code": "SP002",
            "quantity": 5,
            "price": 15000
        }
    ],
    "processing_employee_id": 2,
    "design_confirm_employee_id": 3,
    "estimated_delivery_date": "2024-12-15"
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
    "order_status": 2,
    "printing_company_id": 3,
    "estimated_delivery_date": "2024-12-15"
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
## 5. **Get Orders**
### Endpoint: `POST /tamphuc/api/order/get_orders.php`

- **Request Body (raw JSON):**
    ```json
    {
        "session_token": "your_session_token_here",
        "page": 1,
        "limit": 10
    }
    ```

# Order Status Management API

## 1. **Add Order Status**
### Endpoint: `POST /tamphuc/api/order_status.php`
- **Request Headers:**
    - `Content-Type: application/json`
- **Request Body:**
    ```json
    {
        "session_token": "your-session-token",
        "status_name": "Đang báo giá",
        "description": "Khách hàng đang được báo giá sản phẩm"
    }
    ```
- **Response:**
    ```json
    {
        "success": true,
        "message": "Order status added successfully"
    }
    ```

## 2. **Update Order Status**
### Endpoint: `PUT /tamphuc/api/order_status.php`
- **Request Headers:**
    - `Content-Type: application/json`
- **Request Body:**
    ```json
    {
        "session_token": "your-session-token",
        "id": 1,
        "status_name": "Đang xử lý",
        "description": "Đơn hàng đang được xử lý"
    }
    ```
- **Response:**
    ```json
    {
        "success": true,
        "message": "Order status updated successfully"
    }
    ```

## 3. **Delete Order Status**
### Endpoint: `DELETE /tamphuc/api/order_status.php`
- **Request Headers:**
    - `Content-Type: application/json`
- **Request Body:**
    ```json
    {
        "session_token": "your-session-token",
        "id": 1
    }
    ```
- **Response:**
    ```json
    {
        "success": true,
        "message": "Order status deleted successfully"
    }
    ```

---

# Category Management API

## 1. **Add Category**
### Endpoint: `POST /tamphuc/api/categories.php`
- **Request Headers:**
    - `Content-Type: application/json`
- **Request Body:**
    ```json
    {
        "session_token": "your-session-token",
        "category_name": "Điện tử",
        "description": "Các sản phẩm thuộc nhóm điện tử"
    }
    ```
- **Response:**
    ```json
    {
        "success": true,
        "message": "Category added successfully"
    }
    ```

## 2. **Update Category**
### Endpoint: `PUT /tamphuc/api/categories.php`
- **Request Headers:**
    - `Content-Type: application/json`
- **Request Body:**
    ```json
    {
        "session_token": "your-session-token",
        "id": 1,
        "category_name": "Đồ gia dụng",
        "description": "Các sản phẩm thuộc nhóm đồ gia dụng"
    }
    ```
- **Response:**
    ```json
    {
        "success": true,
        "message": "Category updated successfully"
    }
    ```

## 3. **Delete Category**
### Endpoint: `DELETE /tamphuc/api/categories.php`
- **Request Headers:**
    - `Content-Type: application/json`
- **Request Body:**
    ```json
    {
        "session_token": "your-session-token",
        "id": 1
    }
    ```
- **Response:**
    ```json
    {
        "success": true,
        "message": "Category deleted successfully"
    }
    ```
## 4. **Get All Categories**
### Endpoint: `GET /tamphuc/api/categories.php`
- **Request Headers:**
    - `Authorization: <session_token>`
    - `Content-Type: application/json`
- **Response:**
    ```json
    {
        "success": true,
        "categories": [
            {
                "id": 1,
                "category_name": "Giấy",
                "description": "ChUyêN fr0ntEnD IT =))))))"
            },
            {
                "id": 2,
                "category_name": "giấy",
                "description": "Các sản phẩm thuộc nhóm thời trang"
            }
        ]
    }
    ```



# Product Management API

## 1. **Add Product (One Price)**
### Endpoint: `POST /tamphuc/product/qlsp.php`
- **Request Headers:**
    - `Content-Type: application/json`
- **Request Body:**
    ```json
    {
        "session_token": "your-session-token",
        "category_id": 1,
        "product_name": "Couche 150gsm",
        "rules": "Quy cách chuẩn in giấy",
        "notes": "Sản phẩm thông dụng",
        "nhieuquycach": false,
        "pricing": [
            {
                "quantity": 10,
                "price": 16000
            }
        ]
    }
    ```
- **Response:**
    ```json
    {
        "success": true,
        "message": "Product added successfully"
    }
    ```

## 2. **Add Product (Multiple Prices)**
### Endpoint: `POST /tamphuc/product/qlsp.php`
- **Request Headers:**
    - `Content-Type: application/json`
- **Request Body:**
    ```json
    {
        "session_token": "your-session-token",
        "category_id": 1,
        "product_name": "Couche 200gsm",
        "rules": "Quy cách chuẩn in giấy",
        "notes": "Sản phẩm cao cấp",
        "nhieuquycach": true,
        "pricing": [
            {
                "quantity": 10,
                "price": 17000,
                "note": "Giá lẻ"
            },
            {
                "quantity": 50,
                "price": 7500,
                "note": "Giá sỉ"
            }
        ]
    }
    ```
- **Response:**
    ```json
    {
        "success": true,
        "message": "Product added successfully"
    }
    ```

## 3. **Edit Product**
### Endpoint: `PUT /tamphuc/product/qlsp.php`
- **Request Headers:**
    - `Content-Type: application/json`
- **Request Body:**
    ```json
    {
        "session_token": "your-session-token",
        "id": 1,
        "category_id": 1,
        "product_name": "Couche 150gsm Updated",
        "rules": "Quy cách chuẩn in giấy",
        "notes": "Sản phẩm thông dụng đã cập nhật",
        "nhieuquycach": false,
        "pricing": [
            {
                "quantity": 10,
                "price": 16000
            }
        ]
    }
    ```
- **Response:**
    ```json
    {
        "success": true,
        "message": "Product updated successfully"
    }
    ```

## 4. **Delete Product**
### Endpoint: `DELETE /tamphuc/product/qlsp.php`
- **Request Headers:**
    - `Content-Type: application/json`
- **Request Body:**
    ```json
    {
        "session_token": "your-session-token",
        "id": 1
    }
    ```
- **Response:**
    ```json
    {
        "success": true,
        "message": "Product deleted successfully"
    }
    ```

## 5. **Get All Products**
### Endpoint: `GET /tamphuc/product/show_products.php`
- **Request Headers:**
    - `Content-Type: application/json`
- **Request Body:**
    - `session_token`: (User session token to verify the request)
- **Response:**
    ```json
    {
        "success": true,
        "products": [
            {
                "id": 1,
                "product_name": "Couche 150gsm",
                "rules": "Quy cách chuẩn in giấy",
                "notes": "Sản phẩm thông dụng",
                "multiple_pricing": false,
                "category_name": "Giấy Couche",
                "pricing": [
                    {
                        "quantity": 10,
                        "price": 16000,
                        "note": "Giá lẻ"
                    }
                ]
            },
            {
                "id": 2,
                "product_name": "Couche 200gsm",
                "rules": "Quy cách chuẩn in giấy",
                "notes": "Sản phẩm cao cấp",
                "multiple_pricing": true,
                "category_name": "Giấy Couche",
                "pricing": [
                    {
                        "quantity": 10,
                        "price": 17000,
                        "note": "Giá lẻ"
                    },
                    {
                        "quantity": 50,
                        "price": 7500,
                        "note": "Giá sỉ"
                    }
                ]
            }
        ]
    }
    ```

## 6. **Get Product Details By ID**
### Endpoint: `GET /tamphuc/product/show_product_by_id.php`
- **Request Headers:**
    - `Content-Type: application/json`
- **Query Parameters:**
    - `session_token`: (User session token)
    - `product_id`: (Product ID to view details)
- **Response:**
    ```json
    {
        "success": true,
        "product": {
            "id": 1,
            "product_name": "Couche 150gsm",
            "rules": "Quy cách chuẩn in giấy",
            "notes": "Sản phẩm thông dụng",
            "multiple_pricing": false,
            "category_name": "Giấy Couche",
            "pricing": [
                {
                    "quantity": 10,
                    "price": 16000,
                    "note": "Giá lẻ"
                }
            ]
        }
    }
    ```
## 7. **Check Product Orders**
### Endpoint: `POST /tamphuc/product/check_product_orders.php`
- **Request Headers:**
    - `Content-Type: application/json`
- **Request Body:**
    - `session_token`: (User session token to verify the request)
    - `product_name`: (The name of the product to check for in orders, case-insensitive)

- **Response:**
    - **Success**:
    ```json
    {
        "success": true,
        "message": "Orders found for the product",
        "orders": [
            {
                "order_id": 1,
                "customer_id": 2,
                "recipient_name": "John Doe",
                "recipient_phone": "1234567890",
                "delivery_address": "123 Main St, Hanoi",
                "order_date": "2024-11-24 10:00:00",
                "order_status": 1,
                "notes": "Urgent order",
                "product_details": "[{\"product_code\": \"SP001\", \"quantity\": 10}]",
                "printing_company_id": null
            },
            {
                "order_id": 2,
                "customer_id": 3,
                "recipient_name": "Jane Doe",
                "recipient_phone": "0987654321",
                "delivery_address": "456 Other St, Hanoi",
                "order_date": "2024-11-25 14:00:00",
                "order_status": 1,
                "notes": "Standard delivery",
                "product_details": "[{\"product_code\": \"SP001\", \"quantity\": 20}]",
                "printing_company_id": 1
            }
        ]
    }
    ```
