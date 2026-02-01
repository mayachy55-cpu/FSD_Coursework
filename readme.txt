                                           Product Inventory Project
Server link

https://student.heraldcollege.edu.np/~np03cs4a240274/product_inventory/public/

Github link 

https://github.com/mayachy55-cpu/FSD_Coursework


A. Login Credentials

Admin Login:
Username: Hem Dangaura
Password: 123456

User login:
Username: hey yem
Password: 123456



B. Setup Instructions

a. Local Setup (XAMPP)

1. Install XAMPP

2. Start Apache & MySQL

3. Copy project to: htdocs/product_inventory

4. Create database: product_inventory

5. Import the provided SQL file

6. Update config/db.php:

	$host = "localhost";
	$user = "root";
	$pass = "";
	$db   = "product_inventory";

7. Open browser:

	http://localhost/product_inventory/public/

b. Server Setup: 

1. Upload project:
	scp -r product_inventory np03cs4a240274@10.80.0.250:~/public_html/

2. Database config:

   Edit config/db.php:
	$host = "localhost";
	$user = "np03cs4a240274";
	$pass = "YOUR_SERVER_PASSWORD";
	$db   = "np03cs4a240274";

3. Access site:
	http://student.heraldcollege.edu.np/~np03cs4a240274/product_inventory/



C. Features Implemented:

  i. Authentication:
 - Login system

 - Logout system

 - Session-based authentication

 - Role-based access (Admin/User)

 ii. Product Management

 - Add new products

 - Update product details

 - Delete products

 - View product list

 - Low stock alert

iii. Supplier Management

 - Add suppliers

 - Edit suppliers

 - Delete suppliers

 - View suppliers list

iv. User Management (Admin Only)

 - Add users

 - Edit users

 - Delete users

 - Assign roles

v. Dashboard

 - Total products count

 - Total suppliers count

 - Total users count

 - Low stock alerts

 - Recently added products

vi. UI & UX

 - Responsive layout

 - Modern CSS design

 - Navbar with active state

 - Clean dashboard cards

 - Table styling



D. Known Issues

1. CSS not loading if baseUrl is wrong

    - Fix by setting:

    $baseUrl = "/~np03cs4a240274/product_inventory";


2. Server is case-sensitive

   - CSS is different form css

   - Assets is different form assets

3. Browser cache issue

 - Use Ctrl + F5 after upload


