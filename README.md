# TIJARA-Online-Shop-Symfony

TIJARA is an e-commerce web application built with Symfony. It features a client side for browsing and purchasing products and an admin side for managing the product inventory and client carts. The application implements role-based access control and session testing.

## Features

### Client Side
- User authentication with session and role management.
- Home page showcasing featured products and latest additions.
- Product search and filtering functionality.
- Detailed product pages with recommendations.
- Shopping cart management for adding, viewing, and removing products.

### Admin Side
- Full CRUD operations for managing products.
- Overview of client carts for order tracking.

## Technologies Used
- **Framework:** Symfony  
- **Database:** MySQL  
- **Frontend:** Twig templates, Bootstrap  
- **Testing:** Symfony testing tools for session and role management  
- **Version Control:** Git  

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Symfony CLI
- MySQL
- Node.js and npm (for frontend assets)

### Steps
1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/TIJARA-Online-Shop-Symfony.git
   cd TIJARA-Online-Shop-Symfony
2. Install dependencies:
   composer install
   npm install

3. Configure the environment:

- Copy .env to .env.local and set up your database credentials:

  DATABASE_URL="mysql://username:password@127.0.0.1:3306/tijara_db"

4. Set up the database:

  php bin/console doctrine:database:create
  php bin/console doctrine:migrations:migrate
  php bin/console doctrine:fixtures:load

5. Start the development server:

  symfony server:start
  
6. Open the application in your browser:

  http://localhost:8000

### Usage
- Client Features
- Log in or create a new account.
- Browse products by categories or search with filters.
- Add products to the cart, view the cart, and proceed to checkout.

### Admin Features
- Log in with admin credentials.
- Add, edit, delete products in the inventory.
- View client carts for monitoring purchases.

### Screenshots

![image](https://github.com/user-attachments/assets/3a4819ef-3517-4cb6-b449-317a68dbd8ed)
![image](https://github.com/user-attachments/assets/68a4e89d-d6f0-443d-85a0-1b50c34ade6e)

- Client Pages:
![image](https://github.com/user-attachments/assets/3c31abfb-e3cb-4e72-be2f-950ca94abdae)
![image](https://github.com/user-attachments/assets/c3631fd6-e239-4419-9d23-39bf22aea5ae)
![image](https://github.com/user-attachments/assets/06bfb7a4-6e55-4f25-8edf-0bbc3bad1826)
![image](https://github.com/user-attachments/assets/e7e8573f-18c2-44ef-bf08-c9f601faaafd)


- Admin Panels:
![image](https://github.com/user-attachments/assets/e9bd52dd-81d7-4790-aa5b-eb0bfb12aeff)
![image](https://github.com/user-attachments/assets/47b757f4-5410-4565-a92c-19d7c3877c40)
![image](https://github.com/user-attachments/assets/25333e53-9468-4bae-b7d2-931cc8faf5c4)
![image](https://github.com/user-attachments/assets/490982c7-cd01-4f86-9c03-9758aeb980d7)
![image](https://github.com/user-attachments/assets/cc748d1d-4d57-47e6-927a-33c4b0383568)

### Testing
- The application includes basic tests for:
- Role-based access control.
- Session management.
- CRUD operations.

### Run the tests using:

  php bin/phpunit
  
### Contributing
Contributions are welcome! Please follow these steps:

1. Fork the repository.
2. Create a feature branch:
  git checkout -b feature-name
3. Commit your changes and push the branch.
4. Open a pull request.
