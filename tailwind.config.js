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
                original: {
                    teal: {
                        100: "#92c4ba",
                        150: "#78baab",
                        200: "#6db1a3",
                        300: "#499d8c",
                        400: "#3a7e70",
                        450: "#316b5f",
                        500: "#2c5e54",
                    },
                    lightteal: "#e9fff4",
                    white: {
                        0: "#ffffff",
                        100: "#f8f8fB",
                    },
                },
                neutral: {
                    black: {
                        100: "#8c8c8c",
                        200: "#656565",
                        300: "#3f3f3f",
                        400: "#323232",
                        500: "#262626",
                    },
                    grey: {
                        0: "#b5b3bc",
                        100: "#8f8f8f",
                        200: "#4e4d4d"
                    },
                },
                secondhand: {
                    indigo: {
                        100: "#a997f4",
                        200: "#8c75f1",
                        300: "#6f52ed",
                        400: "#5942be",
                        500: "#43318e",
                    },
                    orange: {
                        100: "#ffe7d9",
                        200: "#f6896d",
                        300: "#f46c49",
                        400: "#c3563a",
                        500: "#92412c",
                    },
                    white: "#f8f5fd",
                },
                thirdinner: {
                    lightred: {
                        100: "#fdf4f4",
                        200: "#fcf0f0",
                        300: "#fbecec",
                        400: "#c9bdbd",
                        500: "#978e8e",
                    },
                    lightyellow: {
                        100: "#fdffe9",
                        200: "#fdffe2",
                        300: "#fcffdb",
                        400: "#caccaf",
                        500: "#979983",
                    },
                    lightblue: {
                        100: "#e0f9fe",
                        200: "#d6f7fe",
                        300: "#ccf5fe",
                        400: "#a3c4cb",
                        500: "#7a9398",
                    },
                    lightteal: {
                        100: "#f4fdf8",
                        200: "#f0fcf6",
                        300: "#ecfbf4",
                        400: "#bdc9c3",
                        500: "#8e9792",
                    },
                },
                thirdouter: {
                    red: {
                        100: "#f3a4a4",
                        200: "#ef8585",
                        300: "#eb6767",
                        400: "#bc5252",
                        500: "#8d3e3e",
                    },
                    yellow: {
                        100: "#f7ee9d",
                        200: "#f5e97c",
                        300: "#f2e35b",
                        400: "#c2b649",
                        500: "#918837",
                    },
                    blue: {
                        100: "#82b6d4",
                        200: "#589ec5",
                        300: "#2e86b7",
                        400: "#256b92",
                        500: "#1c506e",
                    },
                    teal: {
                        100: "#82d4bb",
                        200: "#58c5a5",
                        300: "#2eb78e",
                        400: "#259272",
                        500: "#1c6e55",
                    },
                },
            },
        },
    },

    plugins: [forms],
};
