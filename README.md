# 🎉 Event Management System

A web-based Event Management System built using Flask, MongoDB, HTML, CSS, and Python that allows users to register, log in, and explore event-related features through a dynamic and interactive interface.

This project demonstrates full-stack development skills including authentication, database integration, and responsive UI design.

## 🚀 Features

- 🔐 User Authentication (Signup/Login/Logout)

- 👤 Session-based access control

- 📅 Event browsing interface

- 🗂️ Database-driven event records using MongoDB

- 🎨 Responsive UI using HTML & CSS

- 📂 Organized templates and static assets

- 🧭 Dynamic page navigation with Flask routing

## 🛠️ Tech Stack

**Frontend:**

- HTML5

- CSS3

**Backend:**

- Python

- Flask

**Database:**

- MongoDB (PyMongo)

**Other Tools:**

- Jinja2 Templates

- Session management

## 📁 Project Structure
| File/Folder                     | Description                        |
| ------------------------------- | ---------------------------------- |
| app.py                          | Main Flask application             |
| templates/                      | Contains all HTML pages            |
| static/                         | CSS, images, and video files       |
| Database_update/                | Database management scripts        |
| populate_unique_destinations.py | Script to insert data into MongoDB |

## ⚙️ Installation & Setup
1️⃣ Clone the Repository
git clone https://github.com/your-username/event-management-system.git
cd event-management-system

2️⃣ Install Dependencies
pip install flask pymongo

3️⃣ Start MongoDB

Make sure MongoDB is running locally:

mongodb://localhost:27017/

4️⃣ Run the Application
python app.py

5️⃣ Open in Browser
http://127.0.0.1:5000/

## 🔐 Authentication Flow

- Users can create an account via signup

- Login credentials are validated using MongoDB

- Sessions are maintained using Flask session handling

- Protected routes require login access

## 📊 Database Collections

- users1 – Stores user profile data

- auth_users – Stores login credentials

- destinations – Stores event/location data

## 🎯 Learning Outcomes

This project helped in understanding:

- Full-stack web development

- Flask routing and templating

- MongoDB integration

- Session-based authentication

- MVC project structure

- Dynamic content rendering

## 🔮 Future Enhancements

- Admin dashboard for event creation

- Event booking & ticketing system

- Email notifications

- Payment gateway integration

- Search & filter functionality

- User profile management
