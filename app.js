let listProductHTML = document.querySelector('.listProduct');
let listCartHTML = document.querySelector('.listCart');
let iconCart = document.querySelector('.icon-cart');
let iconCartSpan = document.querySelector('.icon-cart span');
let body = document.querySelector('body');
let closeCart = document.querySelector('.close');
let categoryFilter = document.querySelector('#categoryFilter');
let productCountLabel = document.querySelector('#productCount');
let emptyMessage = document.querySelector('.decom-empty');
let categoryTiles = document.querySelectorAll('.decom-category');

let products = [];
let cart = [];
let activeCategory = 'all';

const toggleCart = () => {
    if (!body) return;
    body.classList.toggle('showCart');
};

if (iconCart) {
    iconCart.addEventListener('click', toggleCart);
}
if (closeCart) {
    closeCart.addEventListener('click', toggleCart);
}

const getFilteredProducts = () => {
    return activeCategory === 'all'
        ? products
        : products.filter(product => product.category === activeCategory);
};

const updateCountLabel = (count) => {
    if (productCountLabel) {
        const suffix = count === 1 ? 'item' : 'items';
        productCountLabel.innerText = `Showing ${count} ${suffix}`;
    }
};

const addDataToHTML = () => {
    if (!listProductHTML) return;

    listProductHTML.innerHTML = '';
    const renderList = getFilteredProducts();
    updateCountLabel(renderList.length);

    if (emptyMessage) {
        emptyMessage.style.display = renderList.length ? 'none' : 'block';
    }

    if (!renderList.length) {
        return;
    }

    renderList.forEach(product => {
        const desc = product.description || 'Ready for your next build.';
        const newProduct = document.createElement('div');
        newProduct.dataset.id = product.id;
        newProduct.classList.add('item', 'product-card');
        newProduct.innerHTML =
            `<div class="product-image-frame">
                <img src="${product.image}" alt="${product.name}">
            </div>
            <div class="product-info">
                <p class="product-category">${product.category || ''}</p>
                <h2>${product.name}</h2>
                <p class="product-description">${desc}</p>
            </div>
            <div class="product-footer">
                <div class="price">$${product.price}</div>
                <button class="addCart">Add To Cart</button>
            </div>`;
        listProductHTML.appendChild(newProduct);
    });
};

if (listProductHTML) {
    listProductHTML.addEventListener('click', (event) => {
        const target = event.target;
        if (target.classList.contains('addCart')) {
            const parentItem = target.closest('.item');
            const id_product = parentItem ? parentItem.dataset.id : null;
            if (id_product) {
                addToCart(id_product);
            }
        }
    });
}

const addToCart = (product_id) => {
    const positionThisProductInCart = cart.findIndex((value) => value.product_id == product_id);
    if (cart.length <= 0) {
        cart = [{
            product_id: product_id,
            quantity: 1
        }];
    } else if (positionThisProductInCart < 0) {
        cart.push({
            product_id: product_id,
            quantity: 1
        });
    } else {
        cart[positionThisProductInCart].quantity = cart[positionThisProductInCart].quantity + 1;
    }
    addCartToHTML();
    addCartToMemory();
};

const addCartToMemory = () => {
    localStorage.setItem('cart', JSON.stringify(cart));
};

const addCartToHTML = () => {
    if (!listCartHTML) return;
    listCartHTML.innerHTML = '';
    let totalQuantity = 0;
    if (cart.length > 0) {
        cart.forEach(item => {
            totalQuantity = totalQuantity + item.quantity;
            const positionProduct = products.findIndex((value) => value.id == item.product_id);
            if (positionProduct < 0) {
                return;
            }
            const info = products[positionProduct];
            const newItem = document.createElement('div');
            newItem.classList.add('item');
            newItem.dataset.id = item.product_id;
            newItem.innerHTML = `
            <div class="image">
                    <img src="${info.image}" alt="${info.name}">
                </div>
                <div class="name">
                ${info.name}
                </div>
                <div class="totalPrice">$${info.price * item.quantity}</div>
                <div class="quantity">
                    <span class="minus"><</span>
                    <span>${item.quantity}</span>
                    <span class="plus">></span>
                </div>
            `;
            listCartHTML.appendChild(newItem);
        });
    }
    if (iconCartSpan) {
        iconCartSpan.innerText = totalQuantity;
    }
};

if (listCartHTML) {
    listCartHTML.addEventListener('click', (event) => {
        const positionClick = event.target;
        if (positionClick.classList.contains('minus') || positionClick.classList.contains('plus')) {
            const product_id = positionClick.parentElement.parentElement.dataset.id;
            const type = positionClick.classList.contains('plus') ? 'plus' : 'minus';
            changeQuantityCart(product_id, type);
        }
    });
}

const changeQuantityCart = (product_id, type) => {
    const positionItemInCart = cart.findIndex((value) => value.product_id == product_id);
    if (positionItemInCart >= 0) {
        switch (type) {
            case 'plus':
                cart[positionItemInCart].quantity = cart[positionItemInCart].quantity + 1;
                break;
            default:
                const changeQuantity = cart[positionItemInCart].quantity - 1;
                if (changeQuantity > 0) {
                    cart[positionItemInCart].quantity = changeQuantity;
                } else {
                    cart.splice(positionItemInCart, 1);
                }
                break;
        }
    }
    addCartToHTML();
    addCartToMemory();
};

const populateCategories = () => {
    if (!categoryFilter) return;
    const categories = Array.from(new Set(products.map(p => p.category).filter(Boolean)));
    categoryFilter.innerHTML = '<option value="all">All categories</option>' +
        categories.map(cat => `<option value="${cat}">${cat}</option>`).join('');
    categoryFilter.value = activeCategory;
};

const setActiveCategory = (category) => {
    activeCategory = category || 'all';
    if (categoryFilter && categoryFilter.value !== activeCategory) {
        categoryFilter.value = activeCategory;
    }
    addDataToHTML();
};

if (categoryFilter) {
    categoryFilter.addEventListener('change', (event) => {
        setActiveCategory(event.target.value);
    });
}

if (categoryTiles && categoryTiles.length) {
    categoryTiles.forEach(tile => {
        const targetCategory = tile.dataset.category || 'all';
        tile.addEventListener('click', () => setActiveCategory(targetCategory));
        tile.addEventListener('keypress', (event) => {
            if (event.key === 'Enter') {
                setActiveCategory(targetCategory);
            }
        });
    });
}

const normalizeImage = (path) => {
    if (!path) return '';
    if (path.startsWith('http') || path.startsWith('//') || path.startsWith('/')) return path;
    return `products/${path.replace(/^\\//, '')}`;
};

const normalizeProducts = (items) => {
    return items.map((item, index) => {
        const name = item.name || `Product ${index + 1}`;
        return {
            ...item,
            name,
            category: item.category || 'Misc',
            description: item.description || `${name} is ready for your next build.`,
            image: normalizeImage(item.image),
        };
    });
};

const initApp = () => {
    const loadCartFromStorage = () => {
        const cachedCart = localStorage.getItem('cart');
        if (cachedCart) {
            cart = JSON.parse(cachedCart);
            addCartToHTML();
        }
    };

    const bootstrapProducts = (data) => {
        products = normalizeProducts(data);
        populateCategories();
        addDataToHTML();
        loadCartFromStorage();
    };

    if (Array.isArray(window.PRODUCT_DATA) && window.PRODUCT_DATA.length) {
        bootstrapProducts(window.PRODUCT_DATA);
        return;
    }

    fetch('products.json')
        .then(response => response.json())
        .then(data => {
            bootstrapProducts(data);
        })
        .catch(() => {
            updateCountLabel(0);
            if (emptyMessage) {
                emptyMessage.style.display = 'block';
            }
        });
};
initApp();
