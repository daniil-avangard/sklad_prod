class ArivalActions {
    constructor() {
        this.actionButtons = document.querySelectorAll('button[data-action]');
        this.bindEventListeners();
    }

    bindEventListeners() {
        this.actionButtons.forEach(button => {
            button.onclick = (event) => this.handleActionClick(event);
        });
    }

    async handleActionClick(event) {
        const button = event.target;
        const action = button.getAttribute('data-action');
        const arivalId = button.getAttribute('data-arival-id');
        
        const url = this.getActionUrl(action, arivalId);
        if (!url) return;
        
        try {
            this.setButtonLoading(button, true);
            const request = this.createRequest(url);
            const response = await fetch(request);
            
            if (response.ok) {
                window.location.href = window.location.href;
            } else {
                const errorData = await response.json().catch(() => ({}));
                this.showError(errorData.message || 'Не удалось выполнить действие');
                this.setButtonLoading(button, false);
            }
        } catch (error) {
            console.error('Ошибка при выполнении запроса:', error);
            this.showError('Произошла ошибка при выполнении запроса');
            this.setButtonLoading(button, false);
        }
    }

    getActionUrl(action, arivalId) {
        switch (action) {
            case 'accept':
                return `/arivals/${arivalId}/accepted`;
            case 'reject':
                return `/arivals/${arivalId}/rejected`;
            default:
                console.error('Неизвестное действие:', action);
                return null;
        }
    }

    createRequest(url) {
        return new Request(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    }

    setButtonLoading(button, isLoading) {
        if (isLoading) {
            button.dataset.originalText = button.textContent;
            button.textContent = 'Обработка...';
            button.disabled = true;
        } else {
            button.textContent = button.dataset.originalText || button.textContent;
            button.disabled = false;
        }
    }

    showError(message) {
        alert('Ошибка: ' + message);
    }
}

// Инициализация класса после загрузки DOM
document.addEventListener('DOMContentLoaded', () => {
    new ArivalActions();
});