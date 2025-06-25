 document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('edit-product-form');
    const messageDiv = document.getElementById('edit-message');
    const productId = form.dataset.productId;

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const formData = new FormData(form);

      fetch(`/products/update/${productId}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        messageDiv.classList.remove('hidden');
        if (data.success || data.message?.includes("sucesso")) {
            messageDiv.textContent = 'Produto atualizado com sucesso!';
            messageDiv.classList.add('text-green-600');
            messageDiv.classList.remove('text-red-600');

            const viewProductsUrl = document.querySelector('meta[name="route-view-products"]').getAttribute('content');
            window.location.href = viewProductsUrl;

          
        } else {
            messageDiv.textContent = 'Erro ao atualizar o produto.';
            messageDiv.classList.add('text-red-600');
            messageDiv.classList.remove('text-green-600');
        }
      })
      .catch(error => {
        console.error(error);
        messageDiv.classList.remove('hidden');
        messageDiv.textContent = 'Erro ao enviar os dados.';
        messageDiv.classList.add('text-red-600');
      });
    });
  });