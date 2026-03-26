# AstonCV

A CV database website for programmers built for the DG1IAD Internet Applications and Databases module at Aston University.

## Features

- Browse and view programmer CVs
- Search CVs by name or programming language
- User registration and login system
- Registered users can view their CV details
- Password change functionality

## Tech Stack

- **Backend:** PHP with PDO
- **Database:** MySQL
- **Frontend:** HTML, CSS
- **Hosting:** Vercel (vercel-php runtime), Railway (MySQL)

## Setup

1. Import `setup.sql` into your MySQL database
2. Update credentials in `api/config/database.php`
3. Deploy to Vercel or run locally with a PHP server

## Project Structure

```
astoncv/
├── api/
│   ├── config/database.php
│   ├── includes/
│   │   ├── functions.php
│   │   ├── header.php
│   │   └── footer.php
│   ├── index.php
│   ├── view.php
│   ├── search.php
│   ├── register.php
│   ├── login.php
│   ├── dashboard.php
│   └── logout.php
├── css/style.css
├── setup.sql
└── vercel.json
```

## Security

- Password hashing with bcrypt
- Prepared statements for all database queries
- XSS prevention with htmlspecialchars
- CSRF token validation on all forms
- Session security with httpOnly cookies

## Author

Qayyum Bokhari — 240339423
