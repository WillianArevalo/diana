import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/colaborator.js",
                "resources/js/facilitator.js",
                "resources/js/rrhh.js",
            ],
            refresh: true,
        }),
    ],
});
