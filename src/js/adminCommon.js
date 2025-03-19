// adminCommon.js

// Fonction pour afficher une notification
function showNotification(message, bgColor = 'bg-green-500') {
    const notification = document.getElementById('notification');
    if (!notification) return;
    notification.textContent = message;
    notification.classList.remove('hidden', 'bg-red-500', 'bg-green-500');
    notification.classList.add(bgColor);
    setTimeout(() => notification.classList.add('hidden'), 3000);
}

// Délai pour masquer les messages de succès
function hideSuccessMessage() {
    const successDiv = document.getElementById('successMessage');
    if (successDiv) {
        setTimeout(() => {
            successDiv.style.display = 'none';
        }, 3000);
    }
}

// Recherche dynamique dans un tableau
function setupSearch(tableId, searchInputId) {
    const searchInput = document.getElementById(searchInputId);
    if (!searchInput) return;
    searchInput.addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll(`#${tableId} tr`);
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
}

// Tri d'un tableau
function setupSort(tableId, sortSelectId, columnMap) {
    const sortSelect = document.getElementById(sortSelectId);
    if (!sortSelect) return;
    sortSelect.addEventListener('change', function() {
        let rows = Array.from(document.querySelectorAll(`#${tableId} tr`));
        let sortType = this.value;
        let columnIndex = columnMap[sortType] || 0;
        rows.sort((a, b) => {
            let valA = a.cells[columnIndex].textContent.toLowerCase();
            let valB = b.cells[columnIndex].textContent.toLowerCase();
            return valA.localeCompare(valB);
        });
        document.getElementById(tableId).append(...rows);
    });
}

// Pagination d'un tableau
function paginateTable(tableId, rowsPerPage = 10) {
    let rows = document.querySelectorAll(`#${tableId} tr`);
    let totalPages = Math.ceil(rows.length / rowsPerPage);
    let pagination = document.getElementById('pagination');
    if (!pagination) return;
    pagination.innerHTML = '';

    function showPage(page) {
        rows.forEach((row, index) => {
            row.style.display = index >= (page - 1) * rowsPerPage && index < page * rowsPerPage ? '' : 'none';
        });
        document.querySelectorAll("#pagination button").forEach(btn => btn.classList.remove("bg-gray-500", "text-white"));
        let activeBtn = document.querySelector(`#pagination button:nth-child(${page})`);
        if (activeBtn) activeBtn.classList.add("bg-gray-500", "text-white");
    }

    for (let i = 1; i <= totalPages; i++) {
        let btn = document.createElement('button');
        btn.textContent = i;
        btn.className = "px-3 py-2 rounded-md bg-gray-300 hover:bg-gray-400";
        btn.addEventListener('click', () => showPage(i));
        pagination.appendChild(btn);
    }
    showPage(1);
}

// Gestion de la suppression avec confirmation
function setupDeleteWithConfirmation(config) {
    let deleteId = null;
    const {
        deleteBtnSelector,
        deleteModalId,
        deleteUrlPrefix,
        successMessage,
        errorMessage,
        tableRowSelector
    } = config;

    document.querySelectorAll(deleteBtnSelector).forEach(button => {
        button.addEventListener('click', function() {
            deleteId = this.dataset.id;
            document.getElementById(deleteModalId).classList.remove('hidden');
        });
    });

    document.getElementById('cancelDelete').addEventListener('click', function() {
        document.getElementById(deleteModalId).classList.add('hidden');
        deleteId = null;
    });

    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (!deleteId) return;
        fetch(`${deleteUrlPrefix}/${deleteId}`, { method: 'DELETE' })
            .then(response => {
                if (response.ok) {
                    const row = document.querySelector(tableRowSelector.replace('${deleteId}', deleteId));
                    if (row) row.remove();
                    showNotification(successMessage);
                } else {
                    showNotification(errorMessage, 'bg-red-500');
                }
                document.getElementById(deleteModalId).classList.add('hidden');
            })
            .catch(error => {
                console.error('Erreur:', error);
                showNotification('Une erreur est survenue.', 'bg-red-500');
                document.getElementById(deleteModalId).classList.add('hidden');
            });
    });
}

// Gestion de la modale pour ajout et modification
function setupModal(modalConfig) {
    const {
        modalId,
        formId,
        openModalBtnId,
        closeModalBtnId,
        editBtnSelector,
        addAction,
        updateAction,
        titleElementId,
        submitButtonId,
        fields,
        iconElementId
    } = modalConfig;

    const modal = document.getElementById(modalId);
    const form = document.getElementById(formId);
    const openModalButton = document.getElementById(openModalBtnId);
    const closeModal = document.getElementById(closeModalBtnId);
    const modalTitle = document.getElementById(titleElementId);
    const submitButton = document.getElementById(submitButtonId);
    const modalIcon = document.getElementById(iconElementId);

    openModalButton.addEventListener("click", () => {
        form.reset();
        form.action = addAction;
        modalTitle.textContent = modalConfig.addTitle;
        modalIcon.textContent = "add_circle";
        submitButton.textContent = "✅ Ajouter";
        modal.classList.remove("hidden");
    });

    document.querySelectorAll(editBtnSelector).forEach(button => {
        button.addEventListener("click", function() {
            const id = this.dataset.id;
            fields.forEach(field => {
                const element = document.getElementById(field.id);
                if (element) element.value = this.dataset[field.dataKey] || '';
            });
            form.action = `${updateAction}/${id}`;
            modalTitle.textContent = modalConfig.editTitle;
            modalIcon.textContent = "edit";
            submitButton.textContent = "✅ Mettre à jour";
            modal.classList.remove("hidden");
        });
    });

    closeModal.addEventListener("click", () => {
        modal.classList.add("hidden");
    });
}

// Export des fonctions pour utilisation dans les pages
export {
    showNotification,
    hideSuccessMessage,
    setupSearch,
    setupSort,
    paginateTable,
    setupDeleteWithConfirmation,
    setupModal
};