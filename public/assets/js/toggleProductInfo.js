function toggleProductInfo() {
    // const productsInfo = document.querySelector('#products-info');
    const productsInfoTab = document.querySelector('#product-info-tabs');
    const productsVariants = document.querySelector('#products-info-variants');
    const productsDivision = document.querySelector('#products-info-division');

    // Очищает активные ссылки
    const clearNavLink = () => {
        const navLinkInfo = document.querySelectorAll('.nav-link--info');

        navLinkInfo.forEach((element) => {
            element.classList.remove('active');
        });
    }

    clearNavLink();

    // Скрывает блок информации
    const hideProductBlock = (className) => {
        if (className === 'product-info-tab--variants') {
            productsVariants.style.display = 'block';
            productsDivision.style.display = 'none';
        } else if (className === 'product-info-tab--division') {
            productsDivision.style.display = 'block';
            productsVariants.style.display = 'none';
        }
    }

    hideProductBlock('product-info-tab--variants');

    // Устанавливает выбранной по умолчанию первый таб
    const setActiveNavLink = () => {
        const navLinkInfo = document.querySelectorAll('.nav-link--info');
        navLinkInfo[0].classList.add('active');
    }

    setActiveNavLink();

    // Устанавливает слушатель на таб
    productsInfoTab.addEventListener('click', (evt) => {
        evt.preventDefault();
        clearNavLink();

        const target = evt.target;
        const navItem = target.closest('.product-info-tab');
        const navItemId = navItem.getAttribute('id');
        target.classList.add('active');

        hideProductBlock(navItemId);
    });
}

toggleProductInfo();

