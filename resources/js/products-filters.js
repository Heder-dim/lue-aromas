// resources/js/products-filters.js

class ProductFilters {
    constructor() {
        this.filterForm = document.getElementById('filterForm');
        this.clearFiltersBtn = document.getElementById('clearFilters');
        this.productsContainer = document.getElementById('productsContainer');
        this.paginationContainer = document.getElementById('paginationContainer');
        this.loading = document.getElementById('loading');
        this.debounceTimer = null;
        this.activeFilters = new Set();
        
        this.init();
    }

    init() {
        this.attachEventListeners();
        this.createFilterTags();
        this.attachDeleteListeners();
        this.setupRealTimeFilters();
    }

    attachEventListeners() {
        // Form submit
        this.filterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.applyFilters();
        });

        // Clear filters
        this.clearFiltersBtn.addEventListener('click', () => {
            this.clearAllFilters();
        });

        // Handle pagination clicks
        document.addEventListener('click', (e) => {
            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                const url = e.target.closest('.pagination a').href;
                this.loadPage(url);
            }
        });
    }

    setupRealTimeFilters() {
        const filterInputs = this.filterForm.querySelectorAll('input, select');
        
        filterInputs.forEach(input => {
            input.addEventListener('input', () => {
                this.debounceFilter();
            });
            
            input.addEventListener('change', () => {
                this.debounceFilter();
            });
        });
    }

    debounceFilter() {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => {
            this.applyFilters();
        }, 800);
    }

    applyFilters() {
        const formData = new FormData(this.filterForm);
        const params = new URLSearchParams();

        // Build parameters
        for (let [key, value] of formData.entries()) {
            if (value.trim() !== '') {
                params.append(key, value);
                this.activeFilters.add(key);
            } else {
                this.activeFilters.delete(key);
            }
        }

        this.showLoading();
        this.updateFilterTags();

        // Make AJAX request
        fetch(`${window.location.pathname}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            this.updateProducts(data);
            this.updatePagination(data);
            this.updateUrl(params);
            this.hideLoading();
        })
        .catch(error => {
            console.error('Erro ao aplicar filtros:', error);
            this.hideLoading();
            this.showError('Erro ao aplicar filtros. Tente novamente.');
        });
    }

    loadPage(url) {
        this.showLoading();
        
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            this.updateProducts(data);
            this.updatePagination(data);
            this.hideLoading();
            
            // Update URL
            window.history.pushState(null, '', url);
        })
        .catch(error => {
            console.error('Erro ao carregar página:', error);
            this.hideLoading();
            this.showError('Erro ao carregar página. Tente novamente.');
        });
    }

    updateProducts(data) {
        this.productsContainer.style.opacity = '0';
        
        setTimeout(() => {
            this.productsContainer.innerHTML = data.products;
            this.productsContainer.style.opacity = '1';
            this.attachDeleteListeners();
            this.updateResultsCounter();
        }, 150);
    }

    updatePagination(data) {
        this.paginationContainer.innerHTML = data.pagination;
    }

    updateUrl(params) {
        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState(null, '', newUrl);
    }

    showLoading() {
        this.loading.classList.remove('hidden');
        this.productsContainer.style.opacity = '0.5';
    }

    hideLoading() {
        this.loading.classList.add('hidden');
        this.productsContainer.style.opacity = '1';
    }

    showError(message) {
        // Create or update error message
        let errorDiv = document.getElementById('errorMessage');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.id = 'errorMessage';
            errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
            this.productsContainer.parentNode.insertBefore(errorDiv, this.productsContainer);
        }
        
        errorDiv.textContent = message;
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (errorDiv) {
                errorDiv.remove();
            }
        }, 5000);
    }

    clearAllFilters() {
        this.filterForm.reset();
        this.activeFilters.clear();
        this.updateFilterTags();
        this.applyFilters();
    }

    createFilterTags() {
        const filterTagsContainer = document.createElement('div');
        filterTagsContainer.id = 'filterTags';
        filterTagsContainer.className = 'flex flex-wrap gap-2 mb-4';
        
        const filtersTitle = this.filterForm.parentNode.querySelector('h2');
        filtersTitle.parentNode.insertBefore(filterTagsContainer, filtersTitle.nextSibling);
    }

    updateFilterTags() {
        const filterTagsContainer = document.getElementById('filterTags');
        if (!filterTagsContainer) return;

        filterTagsContainer.innerHTML = '';

        const formData = new FormData(this.filterForm);
        const labels = {
            'name': 'Nome',
            'min_price': 'Preço mín.',
            'max_price': 'Preço máx.',
            'min_discount': 'Desconto mín.',
            'max_discount': 'Desconto máx.',
            'sort': 'Ordenar por',
            'order': 'Ordem'
        };

        for (let [key, value] of formData.entries()) {
            if (value.trim() !== '' && key !== 'sort' && key !== 'order') {
                const tag = this.createFilterTag(labels[key] || key, value, key);
                filterTagsContainer.appendChild(tag);
            }
        }

        // Add results counter
        this.updateResultsCounter();
    }

    createFilterTag(label, value, key) {
        const tag = document.createElement('span');
        tag.className = 'filter-tag';
        tag.innerHTML = `
            ${label}: ${value}
            <button type="button" onclick="productFilters.removeFilter('${key}')" title="Remover filtro">
                ×
            </button>
        `;
        return tag;
    }

    removeFilter(key) {
        const input = this.filterForm.querySelector(`[name="${key}"]`);
        if (input) {
            input.value = '';
            this.activeFilters.delete(key);
            this.updateFilterTags();
            this.applyFilters();
        }
    }

    updateResultsCounter() {
        const products = this.productsContainer.querySelectorAll('.product-card');
        let counterDiv = document.getElementById('resultsCounter');
        
        if (!counterDiv) {
            counterDiv = document.createElement('div');
            counterDiv.id = 'resultsCounter';
            counterDiv.className = 'results-counter';
            this.productsContainer.parentNode.insertBefore(counterDiv, this.productsContainer);
        }

        const count = products.length;
        const hasFilters = this.activeFilters.size > 0;
        
        if (hasFilters) {
            counterDiv.textContent = `${count} produto${count !== 1 ? 's' : ''} encontrado${count !== 1 ? 's' : ''}`;
            counterDiv.style.display = 'block';
        } else {
            counterDiv.style.display = 'none';
        }
    }

    attachDeleteListeners() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => this.handleDelete(e));
        });
    }

    handleDelete(e) {
        const productId = e.target.getAttribute('data-id');
        const productCard = e.target.closest('.product-card');
        
        if (confirm('Tem certeza que deseja excluir este produto?')) {
            // Show loading on button
            const originalText = e.target.textContent;
            e.target.textContent = 'Excluindo...';
            e.target.disabled = true;

            fetch(`/products/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Animate removal
                    productCard.style.transform = 'scale(0)';
                    productCard.style.opacity = '0';
                    
                    setTimeout(() => {
                        productCard.remove();
                        this.updateResultsCounter();
                    }, 300);
                    
                    this.showSuccessMessage('Produto excluído com sucesso!');
                } else {
                    throw new Error('Erro ao excluir produto');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                e.target.textContent = originalText;
                e.target.disabled = false;
                this.showError('Erro ao excluir produto. Tente novamente.');
            });
        }
    }

    showSuccessMessage(message) {
        const successDiv = document.createElement('div');
        successDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4';
        successDiv.textContent = message;
        
        this.productsContainer.parentNode.insertBefore(successDiv, this.productsContainer);
        
        setTimeout(() => {
            successDiv.remove();
        }, 3000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.productFilters = new ProductFilters();
});