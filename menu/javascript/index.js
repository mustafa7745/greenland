/**
 * Greenland Menu Application Logic
 * Refactored for maintainability and robustness.
 */

// --- Constants & Config ---
const API_URL = "api.php";
const CART_STORAGE_KEY = "greenland_cart";
const USER_UUID_KEY = "user_uuid";
const PLACEHOLDER_IMG = "data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22300%22%20height%3D%22300%22%20viewBox%3D%220%200%20300%20300%22%3E%3Crect%20fill%3D%22%23f3f4f6%22%20width%3D%22300%22%20height%3D%22300%22%2F%3E%3Ctext%20fill%3D%22%239ca3af%22%20font-family%3D%22sans-serif%22%20font-size%3D%2230%22%20dy%3D%2210.5%22%20font-weight%3D%22bold%22%20x%3D%2250%25%22%20y%3D%2250%25%22%20text-anchor%3D%22middle%22%3E%F0%9F%93%B7%3C%2Ftext%3E%3C%2Fsvg%3E";

// --- State Management ---
const state = {
  allCategories: [],
  currentProducts: [],
  activeCategory: 0,
  cart: [],
  // Selection State (for Product/Modal)
  currentProduct: null,
  selectedPrimaryOption: null,
  currentModifiers: {},
  mainQty: 1,
  editingCartIndex: -1,
  searchTimeout: null
};

// --- DOM Helpers ---
const getEl = (id) => document.getElementById(id);
const formatPrice = (price) => parseFloat(price).toFixed(2) + " ريال";

// --- Initialization ---
window.onload = async () => {
  loadCartFromStorage();
  registerVisitor(); // Analytics

  const path = window.location.pathname;

  if (path.includes("product.html")) {
    await initProductPage();
  } else if (path.includes("cart.html")) {
    await initCartPage();
  } else {
    // Default to Home Page
    await initHomePage();
    setupExitConfirmation();
  }

  updateCartUI();
};

// --- Page Initializers ---

async function initHomePage() {
  await loadData(0);
}

async function initProductPage() {
  const urlParams = new URLSearchParams(window.location.search);
  const pid = urlParams.get("id");

  if (!pid) {
    alert("رابط المنتج غير صحيح");
    window.location.href = "index.html";
    return;
  }

  // Ensure we have product data
  if (state.currentProducts.length === 0) {
    await loadData(0);
  }

  const product = state.currentProducts.find(p => p.id == pid);
  if (product) {
    renderProductPage(product);
  } else {
    alert("المنتج غير موجود");
    window.location.href = "index.html";
  }
}

async function initCartPage() {
  // If we are editing, we might need product data to show the modal
  // But for just viewing the cart, we rely on local storage data.
  // However, if the user clicks "Edit", we need the product details.
  // So let's load data in background just in case, or load it on demand.
  // For simplicity and speed, we'll load it now.
  await loadData(0);
  renderCartPage();
}

// --- Data Fetching ---

async function loadData(catId) {
  const grid = getEl("products-grid");
  if (grid) grid.innerHTML = '<div class="col-span-full text-center py-10 text-gray-400">جاري التحميل...</div>';

  try {
    let url = API_URL;
    if (catId > 0) url += `?cat_id=${catId}`;

    const res = await fetch(url);
    const data = await res.json();

    if (data.categories) {
      state.allCategories = data.categories;
      renderCategories();
    }
    if (data.active_category !== undefined) {
      state.activeCategory = data.active_category;
      renderCategories();
    }

    state.currentProducts = data.products || [];

    // Only render grid if we are on home page
    if (grid) renderGrid(state.currentProducts);

  } catch (e) {
    console.error("Data Load Error:", e);
    if (grid) grid.innerHTML = '<div class="col-span-full text-center text-red-400 py-10">فشل الاتصال بالسيرفر</div>';
  }
}

// --- Rendering: Home Page ---

function renderCategories() {
  const container = getEl("categories-container");
  if (!container) return;

  container.innerHTML = state.allCategories.map(c => `
        <button onclick="setCategory('${c.id}')" 
                class="px-5 py-2 rounded-full whitespace-nowrap text-sm font-bold transition-all ${state.activeCategory == c.id
      ? "bg-apple-500 text-white shadow-md scale-105 shadow-apple-500/30"
      : "bg-gray-100 text-gray-600 hover:bg-gray-200"}">
            ${c.name}
        </button>
    `).join("");
}

function setCategory(id) {
  if (id != state.activeCategory) {
    state.activeCategory = id;
    renderCategories();
    const searchInput = getEl("searchInput");
    if (searchInput) searchInput.value = "";
    loadData(id);
  }
}

function renderGrid(products) {
  const grid = getEl("products-grid");
  if (!grid) return;

  if (!products || products.length === 0) {
    grid.innerHTML = '<div class="col-span-full text-center py-20 text-gray-400 flex flex-col items-center"><span class="text-4xl mb-2">🍽️</span><span>لا توجد منتجات</span></div>';
    return;
  }

  grid.innerHTML = products.map(p => `
        <div onclick="goToProduct(${p.id})" class="bg-white p-3 rounded-2xl shadow-sm border border-gray-100 cursor-pointer active:scale-95 transition group hover:shadow-md">
            <div class="h-32 w-full overflow-hidden rounded-xl mb-3 bg-gray-100">
                <img src="${p.image_url}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" onerror="this.src='${PLACEHOLDER_IMG}'">
            </div>
            <h3 class="font-bold text-gray-800 text-sm mb-1 line-clamp-1">${p.name}</h3>
            <p class="text-xs text-gray-400 truncate">${p.description || ""}</p>
        </div>
    `).join("");
}

function goToProduct(pid) {
  window.location.href = `product.html?id=${pid}`;
}

function handleSearch() {
  const searchInput = getEl("searchInput");
  if (!searchInput) return;

  const query = searchInput.value.trim();
  const scopeEl = document.querySelector('input[name="searchScope"]:checked');
  const scope = scopeEl ? scopeEl.value : "category";
  const grid = getEl("products-grid");

  if (query === "") {
    if (scope === "all") loadData(state.activeCategory);
    else renderGrid(state.currentProducts);
    return;
  }

  if (scope === "category") {
    const filtered = state.currentProducts.filter(p => p.name.includes(query));
    renderGrid(filtered);
  } else {
    clearTimeout(state.searchTimeout);
    if (grid) grid.innerHTML = '<div class="col-span-full text-center py-10 text-gray-400">جاري البحث...</div>';

    state.searchTimeout = setTimeout(async () => {
      try {
        const res = await fetch(`${API_URL}?search=${encodeURIComponent(query)}`);
        const data = await res.json();
        if (data.products) {
          // Merge found products into current state to avoid duplicates if needed, 
          // or just render them. Here we just render them.
          // We also push them to currentProducts so if user clicks one, we have the data.
          data.products.forEach(p => {
            if (!state.currentProducts.find(cp => cp.id == p.id)) {
              state.currentProducts.push(p);
            }
          });
          renderGrid(data.products);
        } else {
          if (grid) grid.innerHTML = '<div class="col-span-full text-center py-10 text-gray-400">لا نتائج</div>';
        }
      } catch (e) {
        console.error(e);
      }
    }, 500);
  }
}

// --- Rendering: Product Page ---

function renderProductPage(product) {
  state.currentProduct = product;

  const imgEl = getEl("p-page-img");
  if (imgEl) imgEl.src = product.image_url;

  const titleEl = getEl("p-page-title");
  if (titleEl) titleEl.innerText = product.name;

  const descEl = getEl("p-page-desc");
  if (descEl) descEl.innerText = product.description;

  const container = getEl("primary-options-grid");
  if (container) {
    const modifiers = product.modifiers || [];
    if (modifiers.length > 0) {
      const firstGroup = modifiers[0];
      container.innerHTML = firstGroup.options.map(opt => `
                <div onclick="selectPrimaryOption(${opt.price}, '${opt.name}')" class="flex justify-between items-center p-4 border border-gray-200 rounded-xl hover:border-apple-500 hover:bg-apple-50 cursor-pointer transition shadow-sm bg-white mb-2 group">
                    <div class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 group-hover:border-apple-500"></div>
                        <span class="font-bold text-gray-700">${opt.name}</span>
                    </div>
                    <span class="font-bold text-apple-500">${opt.price} ريال</span>
                </div>
            `).join("");
    } else {
      container.innerHTML = `<div onclick="selectPrimaryOption(${product.price}, 'Default')" class="w-full bg-apple-500 text-white p-4 rounded-xl font-bold text-center shadow-lg cursor-pointer hover:bg-apple-600">اطلب الآن (${product.price} ريال)</div>`;
    }
  }
}

// --- Logic: Selection & Modal ---

function selectPrimaryOption(price, name) {
  state.selectedPrimaryOption = { name: name, price: parseFloat(price) };

  // Reset Selection State
  state.mainQty = 1;
  state.currentModifiers = {};
  state.editingCartIndex = -1;

  updateAddBtnText();
  openAddonsModal();
}

function openAddonsModal() {
  const modal = getEl("addons-modal");
  if (!modal) return;

  getEl("selected-variant-name").innerText = `${state.currentProduct.name} - ${state.selectedPrimaryOption.name}`;
  getEl("final-qty").innerText = state.mainQty;

  const modifiers = state.currentProduct.modifiers || [];
  const container = getEl("secondary-options-container");
  const addOnsGroups = modifiers.slice(1); // Skip first group (primary)

  if (container) {
    if (addOnsGroups.length === 0) {
      container.innerHTML = '<div class="text-center text-gray-400 py-10 bg-gray-50 rounded-xl">لا توجد إضافات لهذا الصنف</div>';
    } else {
      container.innerHTML = addOnsGroups.map((grp, gIdx) => `
                <div class="mb-6">
                    <h4 class="font-bold text-gray-800 mb-3 text-sm bg-gray-100 inline-block px-3 py-1 rounded-lg">${grp.title}</h4>
                    <div class="space-y-3">
                        ${grp.options.map((opt, oIdx) => {
        const modId = `mod_${gIdx}_${oIdx}`;
        const currentQty = state.currentModifiers[modId] ? state.currentModifiers[modId].qty : 0;
        return `
                                <div class="flex items-center justify-between p-3 border border-gray-100 rounded-xl bg-white shadow-sm">
                                    <div class="flex flex-col">
                                        <span class="text-gray-700 font-bold text-sm">${opt.name}</span>
                                        <span class="text-xs text-apple-500 font-bold">+${opt.price} ريال</span>
                                    </div>
                                    <div class="flex items-center gap-3 bg-gray-50 rounded-lg p-1">
                                        <button onclick="updateModifierQty('${modId}', '${opt.name}', ${opt.price}, -1)" class="w-8 h-8 rounded-lg bg-white shadow text-gray-600 font-bold hover:bg-red-50 hover:text-red-500">-</button>
                                        <span id="qty_${modId}" class="font-bold w-4 text-center text-sm">${currentQty}</span>
                                        <button onclick="updateModifierQty('${modId}', '${opt.name}', ${opt.price}, 1)" class="w-8 h-8 rounded-lg bg-black text-white shadow font-bold hover:bg-gray-800">+</button>
                                    </div>
                                </div>
                            `;
      }).join("")}
                    </div>
                </div>
            `).join("");
    }
  }

  modal.classList.remove("hidden");
  calcTotal();
}

function updateModifierQty(id, name, price, change) {
  if (!state.currentModifiers[id]) state.currentModifiers[id] = { name, price, qty: 0 };

  if (state.currentModifiers[id].qty + change >= 0) {
    state.currentModifiers[id].qty += change;
    const qtyEl = getEl(`qty_${id}`);
    if (qtyEl) qtyEl.innerText = state.currentModifiers[id].qty;

    if (state.currentModifiers[id].qty === 0) delete state.currentModifiers[id];
    calcTotal();
  }
}

function updateMainQty(n) {
  if (state.mainQty + n >= 1) {
    state.mainQty += n;
    getEl("final-qty").innerText = state.mainQty;
    calcTotal();
  }
}

function calcTotal() {
  let addonsTotal = 0;
  for (let key in state.currentModifiers) {
    addonsTotal += state.currentModifiers[key].price * state.currentModifiers[key].qty;
  }
  const total = (state.selectedPrimaryOption.price + addonsTotal) * state.mainQty;
  getEl("final-price-btn").innerText = formatPrice(total);
  return total;
}

function updateAddBtnText() {
  const btnText = getEl("add-btn-text");
  if (btnText) {
    btnText.innerText = state.editingCartIndex > -1 ? "حفظ التعديلات" : "إضافة للسلة";
  }
}

function closeAddons() {
  const modal = getEl("addons-modal");
  if (modal) modal.classList.add("hidden");
}

// --- Cart Logic ---

function loadCartFromStorage() {
  const stored = localStorage.getItem(CART_STORAGE_KEY);
  if (stored) {
    try {
      state.cart = JSON.parse(stored);
    } catch (e) {
      console.error("Cart Parse Error", e);
      state.cart = [];
    }
  }
}

function saveCartToStorage() {
  localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(state.cart));
  updateCartUI();
}

function addToCart() {
  const finalTotal = calcTotal();
  const modifiersList = Object.values(state.currentModifiers)
    .filter(m => m.qty > 0)
    .sort((a, b) => a.name.localeCompare(b.name));

  const signature = JSON.stringify({
    pid: state.currentProduct.id,
    variant: state.selectedPrimaryOption.name,
    mods: modifiersList
  });

  const cartItem = {
    signature: signature,
    pid: state.currentProduct.id,
    productName: state.currentProduct.name,
    variant: state.selectedPrimaryOption.name,
    price: state.selectedPrimaryOption.price,
    modifiers: modifiersList,
    rawModifiers: JSON.parse(JSON.stringify(state.currentModifiers)),
    qty: state.mainQty,
    total: finalTotal,
    unitPrice: finalTotal / state.mainQty
  };

  if (state.editingCartIndex > -1) {
    state.cart[state.editingCartIndex] = cartItem;
    state.editingCartIndex = -1;
  } else {
    const existingIdx = state.cart.findIndex(item => item.signature === signature);
    if (existingIdx > -1) {
      state.cart[existingIdx].qty += state.mainQty;
      state.cart[existingIdx].total = state.cart[existingIdx].unitPrice * state.cart[existingIdx].qty;
    } else {
      state.cart.push(cartItem);
    }
  }

  saveCartToStorage();
  closeAddons();

  // Navigation Logic
  if (window.location.pathname.includes("cart.html")) {
    renderCartPage();
  } else {
    window.location.href = "index.html";
  }
}

function editCartItem(index) {
  const item = state.cart[index];

  // Find product data
  let product = state.currentProducts.find(p => p.id == item.pid);

  if (!product) {
    alert("لا يمكن تعديل هذا المنتج حالياً لأنه غير موجود في القائمة المحملة");
    return;
  }

  state.currentProduct = product;
  state.selectedPrimaryOption = { name: item.variant, price: item.price };
  state.mainQty = item.qty;
  state.currentModifiers = JSON.parse(JSON.stringify(item.rawModifiers || {}));
  state.editingCartIndex = index;

  updateAddBtnText();
  openAddonsModal();
}

function removeFromCart(idx) {
  if (confirm("هل أنت متأكد من حذف هذا العنصر؟")) {
    state.cart.splice(idx, 1);
    saveCartToStorage();
    renderCartPage();
  }
}

function renderCartPage() {
  const container = getEl("cart-items-container");
  if (!container) return;

  if (state.cart.length === 0) {
    container.innerHTML = '<div class="text-center py-20 text-gray-400 flex flex-col items-center"><span class="text-6xl mb-4">🛒</span><span>السلة فارغة</span></div>';
    const grandTotal = getEl("cart-grand-total");
    if (grandTotal) grandTotal.innerText = "0.00 ريال";
  } else {
    container.innerHTML = state.cart.map((item, idx) => {
      const modsText = item.modifiers.map(m => `${m.name} (x${m.qty})`).join(", ");
      return `
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex justify-between items-start animate-fade-in">
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800 text-lg">${item.productName}</h3>
                        <div class="text-sm text-gray-500 mt-1 space-y-1">
                            <span class="bg-apple-50 text-apple-700 px-2 py-0.5 rounded text-xs font-bold border border-apple-100">${item.variant}</span>
                            ${modsText ? `<div class="text-xs text-gray-600 mt-1 flex items-center gap-1"><span class="text-apple-500">➕</span> ${modsText}</div>` : ""}
                        </div>
                        <div class="mt-3 font-bold text-gray-800 flex items-center gap-2">
                            <span class="text-xs text-gray-400">سعر الوحدة: ${item.unitPrice.toFixed(2)}</span>
                            <span class="bg-gray-100 px-2 rounded text-sm">x${item.qty}</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-3 justify-between h-full pl-2">
                        <span class="font-bold text-lg text-apple-500">${formatPrice(item.total)}</span>
                        <div class="flex gap-2">
                            <button onclick="editCartItem(${idx})" class="text-blue-500 bg-blue-50 p-2 rounded-lg hover:bg-blue-100 transition shadow-sm" title="تعديل">✏️</button>
                            <button onclick="removeFromCart(${idx})" class="text-red-500 bg-red-50 p-2 rounded-lg hover:bg-red-100 transition shadow-sm" title="حذف">🗑️</button>
                        </div>
                    </div>
                </div>
            `;
    }).join("");

    const totalPrice = state.cart.reduce((sum, item) => sum + item.total, 0);
    const grandTotal = getEl("cart-grand-total");
    if (grandTotal) grandTotal.innerText = formatPrice(totalPrice);
  }
}

function updateCartUI() {
  const totalQty = state.cart.reduce((sum, item) => sum + item.qty, 0);
  const totalPrice = state.cart.reduce((sum, item) => sum + item.total, 0);

  const countEl = getEl("home-cart-count");
  if (countEl) countEl.innerText = totalQty;

  const floatCount = getEl("float-count");
  if (floatCount) floatCount.innerText = totalQty;

  const floatTotal = getEl("float-total");
  if (floatTotal) floatTotal.innerText = formatPrice(totalPrice);

  const stickyCart = getEl("sticky-cart");
  if (stickyCart) {
    if (totalQty > 0) stickyCart.classList.remove("hidden");
    else stickyCart.classList.add("hidden");
  }
}

function openCartPage() {
  window.location.href = "cart.html";
}

// --- Exit Confirmation (Home Only) ---

function setupExitConfirmation() {
  history.pushState(null, null, location.href);
  window.addEventListener("popstate", () => {
    history.pushState(null, null, location.href);
    const modal = getEl("exit-modal");
    if (modal) modal.classList.remove("hidden");
  });
}

function confirmExit(shouldExit) {
  const modal = getEl("exit-modal");
  if (modal) modal.classList.add("hidden");
  if (shouldExit) {
    history.go(-2);
  }
}

// --- Analytics ---

function getOrCreateUUID() {
  let uuid = localStorage.getItem(USER_UUID_KEY);
  if (!uuid) {
    uuid = crypto.randomUUID();
    localStorage.setItem(USER_UUID_KEY, uuid);
  }
  return uuid;
}

function getDeviceDetails() {
  const ua = navigator.userAgent;
  let device = "Unknown", os = "Unknown", os_version = "Unknown", browser = "Unknown", browser_version = "Unknown";

  if (ua.includes("Android")) device = "Android Phone";
  else if (ua.includes("iPhone")) device = "iPhone";
  else if (ua.includes("iPad")) device = "iPad";
  else if (ua.includes("Windows")) device = "Windows PC";
  else if (ua.includes("Macintosh")) device = "Mac";
  else if (ua.includes("Linux")) device = "Linux PC";

  if (/Android\s([0-9.]+)/.test(ua)) { os = "Android"; os_version = ua.match(/Android\s([0-9.]+)/)[1]; }
  else if (/iPhone OS\s([0-9_]+)/.test(ua)) { os = "iOS"; os_version = ua.match(/iPhone OS\s([0-9_]+)/)[1].replace(/_/g, "."); }
  else if (/Mac OS X\s([0-9_]+)/.test(ua)) { os = "MacOS"; os_version = ua.match(/Mac OS X\s([0-9_]+)/)[1].replace(/_/g, "."); }
  else if (/Windows NT\s([0-9.]+)/.test(ua)) { os = "Windows"; os_version = ua.match(/Windows NT\s([0-9.]+)/)[1]; }

  if (/Chrome\/([0-9.]+)/.test(ua)) { browser = "Chrome"; browser_version = ua.match(/Chrome\/([0-9.]+)/)[1]; }
  else if (/Firefox\/([0-9.]+)/.test(ua)) { browser = "Firefox"; browser_version = ua.match(/Firefox\/([0-9.]+)/)[1]; }
  else if (/Safari\/([0-9.]+)/.test(ua) && !ua.includes("Chrome")) { browser = "Safari"; browser_version = ua.match(/Version\/([0-9.]+)/)?.[1] ?? "Unknown"; }

  return { device, os, os_version, browser, browser_version, user_agent: ua };
}

async function registerVisitor() {
  const uuid = getOrCreateUUID();
  const info = getDeviceDetails();
  try {
    await fetch("visitor.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ uuid, ...info }),
    });
  } catch (e) { console.error("Analytics Error", e); }
}
