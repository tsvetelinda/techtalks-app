# TechTalks App

This project was developed as part of the Scripting Languages for the Internet (PHP) course at New Bulgarian University, which focuses on the study of PHP. 

The aim of the project is to provide a platform where users can engage in discussions about various technical topics, exchange ideas, and share insights. The backend is built with PHP, utilizing MySQL and Apache servers, while the frontend is implemented using HTML and CSS.
<hr>

The forum offers two types of access: for **guests** and for **registered users**.

**Guests** have access to the homepage, from which they can be redirected to the registration or login pages. They can view existing threads and filter them by category or by keywords in the title. Guests are not able to add new posts or threads – they can only read.

**Registered users** have a personalized homepage that represents their profile. This profile contains personal data, such as email, username, and registration date, as well as a list of the threads, started by the user. 

**Registered users** can create new threads and add posts to existing ones. Each thread has a title, description, and category (with predefined options: General, Web Development, Mobile Development, Cloud Computing, DevOps, Machine Learning). The creator of a thread has the right to edit or delete it, as well as remove posts under it if deemed necessary. Each registered user can edit or delete their posts and update their profile information, including email, username, and password.
<hr>

* The **back-end** of the project is developed using PHP, with XAMPP used to run MySQL and Apache servers.
* **bcrypt** is used for password hashing before storing them in the database, ensuring user data protection through a secure industry standard for password hashing.
* The **front-end** is implemented with HTML and CSS.
<hr>

**Project Folder Structure:**
* **assets/** – contains images and CSS files used in the project.
* **includes/** – contains reusable components like header, footer, as well as functions for processing login, logout, registration, etc., and database configuration (db.php).
* **model/** – contains the business logic of the application.
* **public/** – contains publicly accessible files, such as the different pages.
