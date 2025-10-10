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
            const request = this.createRequest(url, arivalId);
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
            case 'accept-with-changes':
                return `/arivals/${arivalId}/acceptedwithchanges`;
            default:
                console.error('Неизвестное действие:', action);
                return null;
        }
    }

    createRequest(url, arivalId) {
        // Получаем все радиокнопки с value="accept"
        const acceptedRadios = document.querySelectorAll('input[type="radio"][value="accept"]:checked');
        const acceptedArray = Array.from(acceptedRadios).map(radio => radio.getAttribute('data-productid'));
        
        console.log(acceptedArray);
        
        return new Request(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: arivalId,
                accepted: acceptedArray
            })
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