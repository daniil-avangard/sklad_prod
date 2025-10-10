class ArivalActions {
    constructor() {
        this.actionButtons = document.querySelectorAll('button[data-action]');
        this.radioInputs = document.querySelectorAll('input[type="radio"]');
        this.acceptButton = document.querySelector('button[data-action="accept"]');
        this.rejectButton = document.querySelector('button[data-action="reject"]');
        this.acceptWithChangesButton = document.querySelector('button[data-action="accept-with-changes"]');
        this.clearButton = document.querySelector('button[data-action="clear"]');
        this.init();
        this.bindEventListeners();
    }

    init() {
        // Блокируем кнопку "Принять с изменениями" при инициализации
        if (this.acceptWithChangesButton) {
            this.acceptWithChangesButton.disabled = true;
        }
    }

    bindEventListeners() {
        this.actionButtons.forEach(button => {
            button.onclick = (event) => this.handleActionClick(event);
        });

        // Обработчик кликов по радиокнопкам
        this.radioInputs.forEach(radio => {
            radio.addEventListener('click', () => this.handleRadioClick());
        });

        // Обработчик клика по кнопке "Очистить"
        if (this.clearButton) {
            this.clearButton.onclick = () => this.handleClearClick();
        }
    }

    handleRadioClick() {
        // Блокируем кнопки "Принять" и "Отклонить"
        if (this.acceptButton) {
            this.acceptButton.disabled = true;
        }
        if (this.rejectButton) {
            this.rejectButton.disabled = true;
        }
        
        // Разблокируем кнопку "Принять с изменениями"
        if (this.acceptWithChangesButton) {
            this.acceptWithChangesButton.disabled = false;
        }
    }

    handleClearClick() {
        // Очищаем все радиокнопки
        this.radioInputs.forEach(radio => {
            radio.checked = false;
        });

        // Разблокируем кнопки "Принять" и "Отклонить"
        if (this.acceptButton) {
            this.acceptButton.disabled = false;
        }
        if (this.rejectButton) {
            this.rejectButton.disabled = false;
        }
        
        // Блокируем кнопку "Принять с изменениями"
        if (this.acceptWithChangesButton) {
            this.acceptWithChangesButton.disabled = true;
        }
    }

    async handleActionClick(event) {
        const button = event.target;
        const action = button.getAttribute('data-action');
        const arivalId = button.getAttribute('data-arival-id');
        
        // Проверка для кнопки "Принять с изменениями"
        if (action === 'accept-with-changes') {
            if (!this.areAllRadiosSelected()) {
                Toast.fire({
                    icon: 'error',
                    title: 'Вам нужно выбрать все инпуты'
                });
                return;
            }
        }
        
        const url = this.getActionUrl(action, arivalId);
        if (!url) return;
        
        try {
            this.setButtonLoading(button, true);
            const request = this.createRequest(url, arivalId);
            const response = await fetch(request);
            
            if (response.ok) {
                window.location.href = window.location.origin + '/arivals';
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

    areAllRadiosSelected() {
        // Получаем все уникальные имена групп радиокнопок
        const radioGroups = new Set();
        this.radioInputs.forEach(radio => {
            if (radio.name) {
                radioGroups.add(radio.name);
            }
        });
        
        // Проверяем, выбрана ли хотя бы одна радиокнопка в каждой группе
        for (const groupName of radioGroups) {
            const groupRadios = document.querySelectorAll(`input[name="${groupName}"]`);
            const isAnyChecked = Array.from(groupRadios).some(radio => radio.checked);
            if (!isAnyChecked) {
                return false;
            }
        }
        
        return true;
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
        
        // Получаем все радиокнопки с value="pending"
        const pendingRadios = document.querySelectorAll('input[type="radio"][value="pending"]:checked');
        const pendingArray = Array.from(pendingRadios).map(radio => radio.getAttribute('data-productid'));
        
        // Получаем все радиокнопки с value="reject" (для свойства rejected)
        const rejectedRadios = document.querySelectorAll('input[type="radio"][value="reject"]:checked');
        const rejectedArray = Array.from(rejectedRadios).map(radio => radio.getAttribute('data-productid'));
        
        console.log(acceptedArray);
        console.log(pendingArray);
        console.log(rejectedArray);
        
        return new Request(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: arivalId,
                accepted: acceptedArray,
                pending: pendingArray,
                rejected: rejectedArray
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