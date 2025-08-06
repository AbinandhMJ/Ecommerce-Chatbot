# 🛒 AGRINA Ecommerce Chatbot

An interactive PHP-based chatbot built for a **farm ecommerce website**. It helps users with product search, order tracking, FAQs, cancellations, and feedback — all in one smart chat interface.

---

## ✨ Features

- 🤖 Smart chatbot with natural language understanding  
- 📦 Track orders and cancel with ease  
- 🛍️ View and buy products directly from chat  
- ❓ Instant answers to FAQs  
- 📝 Collects customer feedback with ratings  
- 🔄 Session-based multi-turn conversations  

---

## 🧰 Tech Stack

- **Backend**: [![PHP](https://img.shields.io/badge/Built%20With-PHP-blue)](https://www.php.net/) 
- **Frontend**: [![HTML](https://img.shields.io/badge/Frontend-HTML5-e34f26)](https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/HTML5), [![CSS](https://img.shields.io/badge/Styles-CSS3-264de4)](https://developer.mozilla.org/en-US/docs/Web/CSS),  [![Bootstrap](https://img.shields.io/badge/UI-Bootstrap-563d7c)](https://getbootstrap.com/), [![JavaScript](https://img.shields.io/badge/Script-JavaScript-f7df1e)](https://developer.mozilla.org/en-US/docs/Web/JavaScript) 
- **Database**: [![Database](https://img.shields.io/badge/Database-MySQL-orange)](https://www.mysql.com/) 
- **Session Management**: Native PHP sessions  

---

## 🚀 Getting Started

1. **Clone this repo**  
   ```bash
   git clone https://github.com/AbinandhMJ/Ecommerce-Chatbot.git
   cd Ecommerce-Chatbot

## ⚙️ Setup Instructions

### Set up the database

- Update `includes/db_config.php` with your database credentials  
- Create the following tables in your database:  
  - `products`  
  - `orders`  
  - `faq`  
  - `feedback`

### Run the server

- Start a PHP server locally  
- Open your browser and go to: `http://localhost:8000`

---

## 💬 How It Works

The chatbot handles:

- Greetings  
- FAQs (from the database)  
- Order tracking  
- Order cancellations  
- Product listing  
- Product purchasing

---

## 📝 Feedback System

- Users submit feedback with name, email, rating, and message  
- The data is sent to the server and stored in the `feedback` table  
- Helps in collecting valuable insights from users

---

## 📌 Example Chat Flow (Backend)

- Connects to the database  
- Starts a user session  
- Captures user message  
- Processes the message and sends back a chatbot response

---

## 🔧 To Do

- 🤖 Add AI/NLP search capabilities  
- 🌍 Add multilingual support  
- 🎙️ Integrate voice chat functionality  
- 📊 Build an admin dashboard for viewing feedback
