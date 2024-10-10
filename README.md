Member Administration Application

Overview
The Member Administration Application is a web-based system designed for managing families, their members, and contributions. Developed using PHP, MySQL, CSS, and HTML, the application provides a user-friendly interface to perform CRUD (Create, Read, Update, Delete) operations for families and members, as well as manage their contributions.

Features
User Authentication: Secure login and logout functionality.
Family Management: Create, edit, and list family records.
Member Management: Manage individual members within each family, including adding, editing, and listing members. 
Contribution Management: Record and track financial contributions from family members.
Responsive Design: Optimized for different devices using custom CSS styles.

Set up AMPPS: Ensure AMPPS is installed and configured with PHP and MySQL.

Database Setup:

Create a new database in MySQL.
Import the database file provided in the database/ folder using phpMyAdmin.
Configuration:

Update the database connection settings in config.php with your database credentials.
Run the Application:

Place the project folder in the AMPPS www directory.

Access the application in your browser at http://localhost/member-administration/public/index.php.

Usage

Login: Access the login page and enter valid credentials.

Manage Families: Navigate to the "Families" section to add, edit, or view family records.

Manage Members: Access the "Members" section to manage members associated with each family.

Track Contributions: Use the "Contributions" section to record and monitor financial contributions.

Technical Choices

The application is developed in English to ensure international reach, making it accessible to a global audience, including non-Dutch speakers. The choice of PHP and MySQL was driven by their robustness for building dynamic web applications, while the MVC structure ensures a clear separation of logic, data, and presentation.

Reflection
During development, various challenges like database connection issues, input validation, and CSS styling were addressed and resolved. These improvements are documented across different version updates in the codebase.

Contributing
Contributions are welcome! Please follow these steps:

Fork the repository.
Create a new branch (feature/your-feature).
Commit your changes.
Push to the branch.
Open a pull request.
License
This project is licensed under the MIT License. See the LICENSE file for more details.
