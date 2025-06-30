# hospital-management
Admin Dashboard:
![image](https://github.com/user-attachments/assets/8914b60a-d23f-4b77-9b48-06d0a3d17665)
Doctor Dashboard:
![image](https://github.com/user-attachments/assets/1304c73c-f96d-4c29-a167-e97f654babd6)
Add Patient:
![image](https://github.com/user-attachments/assets/b591a08e-411e-41c6-976f-ee816df04e86)
![image](https://github.com/user-attachments/assets/371b33ce-0a44-4261-b39a-6df83fdcd355)
Schedule Appointment:
![image](https://github.com/user-attachments/assets/221f044b-9a7a-426f-ab61-139f393bc3bf)
![image](https://github.com/user-attachments/assets/5fccb141-0f56-4807-b390-1a62844c4d37)
Patient Deleted:
![image](https://github.com/user-attachments/assets/81378c00-0b9b-423f-92e2-f0249e0d1f15)

1. Clone the Repository

git clone https://github.com/Janarththan1/Lara-hospital-management.git
cd hospital-management-app

2. Install PHP Dependencies

composer install

3. Install Node Dependencies

npm install
npm run dev

4. Set Up Environment Variables

cp .env.example .env

5. Update the following variables in .env:

DB_DATABASE=hospital_db
DB_USERNAME=root
DB_PASSWORD=your_db_password

6. Generate App Key

php artisan key:generate

7. Run Migrations

php artisan migrate

8. Run the App

php artisan serve


Tech Stack

Laravel

Laravel Breeze (Authentication)

Tailwind CSS (UI)

MySQL or PostgreSQL
