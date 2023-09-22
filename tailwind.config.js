import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Poppins", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                vegan: {
                    50: "#E9FFF4",
                    100: "#92c4ba",
                    200: "#6db1a3",
                    300: "#499d8c",
                    400: "#3a7e70",
                    500: "#2c5e54",
                },
            },
        },
    },

    plugins: [forms],
};
