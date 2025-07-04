import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/add-product.js', 'resources/js/view-product.js', 'resources/js/edit-products.js', 'resources/js/products-filters.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
