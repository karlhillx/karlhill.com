# Karl Hill's Portfolio Website

A modern, responsive portfolio website built with Laravel, Tailwind CSS, and modern web technologies.

## Features

- **Dark/Light mode support**
- **Responsive design** using Tailwind CSS v4
- **Portfolio showcasing professional projects** including:
    - ESCCOR (NASA Earth Science Data Management)
    - Taylor MDA (Eclipse-based UML Modeling Tool)
- **Modern tech stack integration**
- **Interactive UI components**

## Tech Stack

- **Laravel**
- **Tailwind CSS v4**
- **Livewire/Inertia capabilities**
- **Vite** for asset bundling
- **PHP 8.3+**

## Local Development

1. **Clone the repository:**

    ```sh
    git clone https://github.com/karlhillx/karlhill.com.git
    ```

2. **Navigate to the project directory:**

    ```sh
    cd karlhill.com
    ```

3. **Install dependencies:**

    ```sh
    composer install
    npm install
    ```

4. **Copy the `.env.example` file to `.env` and configure your environment variables:**

    ```sh
    cp .env.example .env
    ```

5. **Generate an application key:**

    ```sh
    php artisan key:generate
    ```

6. **Run the development server:**

    ```sh
    npm run dev
    php artisan serve
    ```

## Building for Production

To build the project for production, run:

```sh
npm run build
```


## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.
```
