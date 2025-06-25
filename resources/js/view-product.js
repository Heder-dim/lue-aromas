
document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                if (confirm('Deseja realmente excluir este produto?')) {
                    const id = this.dataset.id;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch(`/products/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Erro ao excluir o produto.');
                        }
                    });
                }
            });
        });
    });