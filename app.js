let listProductHTML = document.querySelector('.listProduct');
let listCartHTML = document.querySelector('.listCart');
let iconCart = document.querySelector('.icon-cart');
let iconCartSpan = document.querySelector('.icon-cart span');
let navCartBadge = document.querySelector('.decom-cart-badge');
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
let activeCategoryLabel = 'All categories';
let serverCartCount = typeof window !== 'undefined' && typeof window.DE_SERVER_CART_COUNT !== 'undefined'
    ? parseInt(window.DE_SERVER_CART_COUNT, 10) || 0
    : 0;

const normalizeCategoryValue = (val) => {
    if (!val) return 'all';
    return String(val).toLowerCase().replace(/[^a-z0-9]/g, '') || 'all';
};

const toggleCart = () => {
    if (!body) return;
    body.classList.toggle('showCart');
};

const openCartPanel = () => {
    if (body && !body.classList.contains('showCart')) {
        body.classList.add('showCart');
    }
};

const buildReturnUrl = (reason) => {
    const target = afterLoginUrl || `${window.location.origin}${window.location.pathname}?showCart=1`;
    try {
        const urlObj = new URL(target, window.location.origin);
        if (!urlObj.searchParams.get('showCart')) {
            urlObj.searchParams.set('showCart', '1');
        }
        if (reason === 'checkout') {
            urlObj.searchParams.set('checkout', '1');
        }
        return urlObj.toString();
    } catch (e) {
        // Fallback to string manipulation if URL parsing fails
        let out = target;
        if (out.indexOf('showCart=') === -1) {
            const sep = out.indexOf('?') !== -1 ? '&' : '?';
            out = `${out}${sep}showCart=1`;
        }
        if (reason === 'checkout' && out.indexOf('checkout=1') === -1) {
            const sep = out.indexOf('?') !== -1 ? '&' : '?';
            out = `${out}${sep}checkout=1`;
        }
        return out;
    }
};

const markCartForPostLogin = (reason) => {
    const returnUrl = buildReturnUrl(reason);
    try {
        localStorage.setItem('cartOpenAfterLogin', '1');
        localStorage.setItem('cartRedirectAfterLogin', returnUrl);
        if (reason === 'checkout') {
            localStorage.setItem('cartCheckoutAfterLogin', '1');
        }
    } catch (e) {
        // Ignore storage issues (e.g., private mode).
    }
};

const buildLoginRedirectUrl = (reason) => {
    const returnUrl = buildReturnUrl(reason);
    const separator = loginPageUrl.indexOf('?') !== -1 ? '&' : '?';
    let url = `${loginPageUrl}${separator}next=${encodeURIComponent(returnUrl)}`;
    if (reason === 'checkout') {
        url += '&checkout=1';
    }
    return url;
};

const redirectGuestForLogin = (reason = '') => {
    markCartForPostLogin(reason);
    window.location.href = buildLoginRedirectUrl(reason);
};

// Modal helpers (modals added in Products.php)
const loginModal = document.getElementById('loginModal');
const loginModalTitle = loginModal ? loginModal.querySelector('h3') : null;
const loginModalBody = loginModal ? loginModal.querySelector('p') : null;
const addCartModal = document.getElementById('addCartModal');
const addCartModalMsg = document.getElementById('addCartModalMsg');

const showModal = (modal) => {
    if (!modal) return;
    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');
};
const hideModal = (modal) => {
    if (!modal) return;
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
};

const showLoginRedirectNotice = (title, message, reason = '') => {
    if (loginModal) {
        if (loginModalTitle && title) {
            loginModalTitle.textContent = title;
        }
        if (loginModalBody && message) {
            loginModalBody.textContent = message;
        }
        showModal(loginModal);
        setTimeout(() => redirectGuestForLogin(reason), 900);
    } else {
        redirectGuestForLogin(reason);
    }
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

// Checkout button: show quantity-selection modal for logged-in users
const checkoutBtn = document.querySelector('.checkOut');
const checkoutQtyModal = document.getElementById('checkoutQtyModal');
const checkoutItemsList = document.getElementById('checkoutItemsList');

const triggerCheckoutFlow = async () => {
    try {
        const res = await fetch('/Decomponents/api_auth_status', { credentials: 'same-origin' });
        const json = await res.json();
        if (!json.logged_in) {
            redirectGuestForLogin('checkout');
            return;
        }
    } catch (err) {
        // network error -> fallback to client-side flag
        if (!isLoggedIn) {
            redirectGuestForLogin('checkout');
            return;
        }
    }

    // Populate modal with items from cart
    if (!checkoutItemsList) return showModal(loginModal);

    checkoutItemsList.innerHTML = '';
    if (!cart || cart.length === 0) {
        checkoutItemsList.innerHTML = '<p>Your cart is empty.</p>';
    } else {
        cart.forEach((item, idx) => {
            const info = findProductById(item.product_id) || { name: item.product_id };
            const row = document.createElement('div');
            row.style.display = 'flex';
            row.style.alignItems = 'center';
            row.style.justifyContent = 'space-between';
            row.style.marginBottom = '8px';
            row.innerHTML = `
                <div style="flex:1;margin-right:8px">${info.name}</div>
                <div style="width:120px;text-align:right">
                    <input type="number" min="1" value="${item.quantity || item.qty || 1}" data-idx="${idx}" style="width:72px;padding:6px;border-radius:4px;border:1px solid #ccc">
                </div>
            `;
            checkoutItemsList.appendChild(row);
        });
    }

    showModal(checkoutQtyModal);
};

if (checkoutBtn) {
    checkoutBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        triggerCheckoutFlow();
    });
}

// Login modal actions
const loginNowBtn = document.querySelector('.loginNow');
const loginCloseBtn = document.querySelector('.loginClose');
if (loginNowBtn) {
    loginNowBtn.addEventListener('click', () => {
        hideModal(loginModal);
        redirectGuestForLogin();
    });
}
if (loginCloseBtn) {
    loginCloseBtn.addEventListener('click', () => hideModal(loginModal));
}

// Add-to-cart modal actions
const viewCartBtn = document.querySelector('.viewCart');
const continueShoppingBtn = document.querySelector('.continueShopping');
if (viewCartBtn) {
    viewCartBtn.addEventListener('click', async () => {
        hideModal(addCartModal);
        try {
            const res = await fetch('/Decomponents/api_auth_status', { credentials: 'same-origin' });
            const json = await res.json();
            if (!json.logged_in) {
                showLoginRedirectNotice('Login required', 'Please log in to view your cart.', '');
                return;
            }
            // server says logged in -> go to cart page
            window.location.href = '/Decomponents/cart';
        } catch (err) {
            // fallback to client-side flag
            if (!isLoggedIn) {
                showLoginRedirectNotice('Login required', 'Please log in to view your cart.', '');
                return;
            }
            window.location.href = '/Decomponents/cart';
        }
    });
}
if (continueShoppingBtn) {
    continueShoppingBtn.addEventListener('click', () => hideModal(addCartModal));
}

// Checkout quantity modal actions
const checkoutQtyCloseBtn = document.querySelector('.checkoutQtyClose');
const checkoutQtyProceedBtn = document.querySelector('.checkoutQtyProceed');
if (checkoutQtyCloseBtn) {
    checkoutQtyCloseBtn.addEventListener('click', () => hideModal(checkoutQtyModal));
}
if (checkoutQtyProceedBtn) {
    checkoutQtyProceedBtn.addEventListener('click', () => {
        // Read all inputs inside checkoutItemsList and update cart
        if (!checkoutItemsList) return hideModal(checkoutQtyModal);
        const inputs = checkoutItemsList.querySelectorAll('input[type="number"][data-idx]');
        inputs.forEach(inp => {
            const idx = parseInt(inp.getAttribute('data-idx'), 10);
            const val = parseInt(inp.value, 10) || 1;
            if (cart[idx]) {
                cart[idx].quantity = val;
                // Keep legacy key name 'product_id' and 'quantity'
                if (!cart[idx].product_id && cart[idx].id) {
                    cart[idx].product_id = cart[idx].id;
                }
            }
        });

        // Persist client cart and update UI
        addCartToHTML();
        addCartToMemory();

        // Sync to server for each cart item (best-effort)
        cart.forEach(item => {
            const pid = item.product_id || item.id;
            const qty = item.quantity || item.qty || 1;
            if (addToCartUrl && pid) {
                syncCartItemToServer(pid, qty);
            }
        });

        hideModal(checkoutQtyModal);
        // Redirect to checkout review page
        window.location.href = '/Decomponents/checkout_review';
    });
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
            if (data && data.status === 'ok' && typeof data.cartCount !== 'undefined') {
                updateCartBadges(data.cartCount);
            }
        })
        .catch(() => {
            // Silently keep local cart if server call fails.
        });
};

const getFilteredProducts = () => {
    if (activeCategory === 'all') return products;
    return products.filter(product => (product.categorySlug || normalizeCategoryValue(product.category)) === activeCategory);
};

const updateCountLabel = (count) => {
    if (productCountLabel) {
        const suffix = count === 1 ? 'item' : 'items';
        productCountLabel.innerText = `Showing ${count} ${suffix}`;
        productCountLabel.style.display = count > 0 ? 'block' : 'none';
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

const addToCart = async (product_id) => {
    // Confirm intent before proceeding
    const confirmed = window.confirm('Add this item to your cart?');
    if (!confirmed) return;

    // Add to client cart first (works for guests too)
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

    // Show add-to-cart confirmation modal
    const productInfo = findProductById(product_id) || { name: 'Product' };
    if (addCartModalMsg) {
        addCartModalMsg.innerText = `${productInfo.name} added to your cart.`;
    }
    showModal(addCartModal);

    // Best-effort sync to server if logged in; if not, checkout flow will prompt
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

const updateCartBadges = (count) => {
    if (iconCartSpan) {
        iconCartSpan.innerText = count;
    }
    if (navCartBadge) {
        navCartBadge.innerText = count;
    }
};

const addCartToHTML = () => {
    let totalQuantity = 0;

    if (listCartHTML) {
        listCartHTML.innerHTML = '';
    }

    if (cart.length > 0) {
        cart.forEach(item => {
            const qty = item.quantity || item.qty || 0;
            totalQuantity += qty;

            if (!listCartHTML) return;

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
                <div class="totalPrice">$${info.price * qty}</div>
                <div class="quantity">
                    <span class="minus"><</span>
                    <span>${qty}</span>
                    <span class="plus">></span>
                </div>
            `;
            listCartHTML.appendChild(newItem);
        });
    }

    if (listCartHTML) {
        // Clear and re-render only when listCartHTML exists
        if (!cart.length) {
            listCartHTML.innerHTML = '';
        }
    }

    updateCartBadges(totalQuantity);
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
    const options = [];
    products.forEach(p => {
        const label = p.category || 'General';
        const value = p.categorySlug || normalizeCategoryValue(label);
        options.push({ label, value });
    });
    const unique = [];
    const seen = new Set();
    options.forEach(opt => {
        if (!seen.has(opt.value)) {
            seen.add(opt.value);
            unique.push(opt);
        }
    });
    const optsHtml = ['<option value="all">All categories</option>'].concat(
        unique.map(opt => `<option value="${opt.value}">${opt.label}</option>`)
    );
    categoryFilter.innerHTML = optsHtml.join('');
    categoryFilter.value = activeCategory;
};

const setActiveCategory = (category) => {
    activeCategory = normalizeCategoryValue(category || 'all');
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
        const categoryLabel = item.category || item.category_name || 'General';
        return {
            ...item,
            name,
            category: categoryLabel,
            categorySlug: normalizeCategoryValue(categoryLabel),
            description: item.description || `${name} is ready for your next build.`,
            image: normalizeImage(item.image),
        };
    });
};

const initApp = () => {
    const loadCartFromStorage = () => {
        const cachedCart = localStorage.getItem('cart');

        // If server says cart is empty, clear stale local cart
        if (serverCartCount === 0 && cachedCart) {
            localStorage.removeItem('cart');
        } else if (cachedCart) {
            cart = JSON.parse(cachedCart);
        }

        addCartToHTML();
        updateCartBadges(cart.length ? cart.reduce((sum, item) => sum + (item.quantity || item.qty || 0), 0) : serverCartCount);
    };

    const resumeCheckoutAfterLogin = () => {
        let shouldResume = false;
        const params = new URLSearchParams(window.location.search);
        if (params.get('checkout') === '1') {
            shouldResume = true;
        }
        try {
            if (localStorage.getItem('cartCheckoutAfterLogin') === '1') {
                shouldResume = true;
                localStorage.removeItem('cartCheckoutAfterLogin');
                localStorage.removeItem('cartOpenAfterLogin');
                localStorage.removeItem('cartRedirectAfterLogin');
            }
        } catch (e) {
            // Ignore storage issues.
        }
        if (shouldResume && checkoutBtn) {
            triggerCheckoutFlow();
        }
    };

    const bootstrapProducts = (data) => {
        products = normalizeProducts(data);
        populateCategories();
        addDataToHTML();
        loadCartFromStorage();
        openCartFromFlags();
        resumeCheckoutAfterLogin();
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
