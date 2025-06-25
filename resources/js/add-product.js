// arquivo: public/js/products.js
document.getElementById("form-product").addEventListener("submit", async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    // Se for múltiplas imagens:
    const imageInput = form.querySelector('input[type="file"]');
    if (imageInput && imageInput.files.length > 0) {
        for (const file of imageInput.files) {
            formData.append("images[]", file);
        }
    }

    try {
        // Usando as meta tags
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const storeUrl = document.querySelector('meta[name="products-store-url"]').getAttribute('content');
        
        // Ou usando as variáveis globais
        // const csrfToken = window.Laravel.csrfToken;
        // const storeUrl = window.Laravel.routes.productsStore;

        const response = await fetch(storeUrl, {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok) {
            alert(data.message);
            const viewProductsUrl = document.querySelector('meta[name="route-view-products"]').getAttribute('content');
            window.location.href = viewProductsUrl;
            form.reset();
        } else {
            console.error(data);
            alert("Erro ao cadastrar. Verifique os dados.");
        }
    } catch (err) {
        console.error(err);
        alert("Erro na requisição.");
    }
});