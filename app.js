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

const isLoggedIn = Boolean(window.DE_IS_LOGGED_IN);
const loginPageUrl = window.DE_LOGIN_URL || '/home_page.php';
const afterLoginUrl = window.DE_AFTER_LOGIN_URL || `${window.location.origin}${window.location.pathname}?showCart=1`;
const addToCartUrl = window.DE_ADD_TO_CART_URL || null;

let products = [];
let cart = [];
let activeCategory = 'all';

const toggleCart = () => {
    if (!body) return;
    body.classList.toggle('showCart');
};

const openCartPanel = () => {
    if (body && !body.classList.contains('showCart')) {
        body.classList.add('showCart');
    }
};

const markCartForPostLogin = () => {
    try {
        localStorage.setItem('cartOpenAfterLogin', '1');
        localStorage.setItem('cartRedirectAfterLogin', afterLoginUrl);
    } catch (e) {
        // Ignore storage issues (e.g., private mode).
    }
};

const buildLoginRedirectUrl = () => {
    const separator = loginPageUrl.indexOf('?') !== -1 ? '&' : '?';
    return `${loginPageUrl}${separator}next=${encodeURIComponent(afterLoginUrl)}`;
};

const redirectGuestForLogin = () => {
    markCartForPostLogin();
    window.location.href = buildLoginRedirectUrl();
};

const openCartFromFlags = () => {
    const params = new URLSearchParams(window.location.search);
    const queryFlag = params.get('showCart') === '1';
    let storedFlag = false;

    try {
        storedFlag =
            localStorage.getItem('cartOpenAfterLogin') === '1' ||
            localStorage.getItem('cartRedirectAfterLogin') === afterLoginUrl ||
            localStorage.getItem('cartRedirectAfterLogin') === window.location.href;
        if (storedFlag) {
            localStorage.removeItem('cartOpenAfterLogin');
            localStorage.removeItem('cartRedirectAfterLogin');
        }
    } catch (e) {
        storedFlag = false;
    }

    if ((queryFlag || storedFlag) && body && !body.classList.contains('showCart')) {
        body.classList.add('showCart');
    }
};

if (iconCart) {
    iconCart.addEventListener('click', toggleCart);
}
if (closeCart) {
    closeCart.addEventListener('click', toggleCart);
}

const findProductById = (id) => {
    return products.find(p => String(p.id) === String(id));
};

const syncCartItemToServer = (productId, quantity) => {
    if (!addToCartUrl) return;

    const product = findProductById(productId);
    const qty = quantity || 1;
    if (!product) return;

    const payload = new URLSearchParams();
    payload.append('product_id', product.id);
    payload.append('product_name', product.name || '');
    payload.append('product_price', product.price || 0);
    payload.append('product_image', product.image || '');
    payload.append('qty', qty);

    fetch(addToCartUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'include',
        body: payload.toString()
    })
        .then(res => res.json())
        .then(data => {
            if (data && data.status === 'login_required') {
                redirectGuestForLogin();
                return;
            }
            if (data && data.status === 'ok' && typeof data.cartCount !== 'undefined' && iconCartSpan) {
                iconCartSpan.innerText = data.cartCount;
            }
        })
        .catch(() => {
            // Silently keep local cart if server call fails.
        });
};

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
    openCartPanel();

    if (addToCartUrl) {
        syncCartItemToServer(product_id, 1);
    }
};

const addCartToMemory = () => {
    try {
        localStorage.setItem('cart', JSON.stringify(cart));
    } catch (e) {
        // Ignore storage failures (e.g., private mode).
    }
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
    // Strip any leading slashes so we can safely prefix the products folder.
    const cleanPath = path.replace(/^\/+/, '');
    return `products/${cleanPath}`;
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
        openCartFromFlags();
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
