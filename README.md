QueryShelf: Library Database Management System

This project is created in partial fulfillment of the requirements for the course IT 112: Web Systems and Technologies.

Project Overview:
QueryShelf is a modern, efficient library database management system developed for the Local Government of Daraga to simplify and improve daily library operations. The system provides complete CRUD (Create, Read, Update, Delete) functionality for managing books, borrowers, and library records with accuracy and ease.
Library User/Borrowers can conveniently browse library materials and check availability, while authorized personnel such as the IT Staff, Library Clerk, and Librarian are given secure, role-based access to the main system for managing transactions. QueryShelf features an automated circulation system that monitors borrowed books, tracks due dates, and manages overdue records, helping the library maintain organized and reliable services.

Installation Instructions:
To run this project locally, ensure you have XAMPP installed.

1. Prepare the Files: Clone or copy the project folder into your C:\xampp\htdocs\ directory.
2. Start Services: Open the XAMPP Control Panel and click Start for both Apache and MySQL.
3. Setup Database: * Open http://localhost/phpmyadmin.
    • Create a new database named queryshelf.
    • Click Import and select the .sql file provided in the project folder to populate your tables.
4. Database Connection: * Open db.php in your project folder.
    • Verify the $port matches your XAMPP MySQL port (usually 3306 or 3307).
    • Ensure $user and $pass match your database settings (default is root with no password).
5. Run the System: Open your browser and navigate to http://localhost/QueryShelf/index.php.

Authors
Arugar, Princess C.
Castillo, Reazelle Angela L.
Magdaong, April A.
Mancera, Jessa B.
Medallon, Zeendy Grace

Submitted to: Prof. Jevah Rea Montas | May 25, 2026