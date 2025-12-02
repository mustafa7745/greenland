// --- State Variables ---
let allCategories = [];
let currentProducts = [];
let activeCategory = 0;

let currentProduct = null;
let selectedPrimaryOption = null;
let currentModifiers = {};
let mainQty = 1;

let cart = [];
let searchTimeout = null;

// متغير لتتبع حالة التعديل (إذا كان -1 يعني إضافة جديدة، غير ذلك يعني رقم العنصر في السلة)
let editingCartIndex = -1;

const placeholderImg =
  "data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22300%22%20height%3D%22300%22%20viewBox%3D%220%200%20300%20300%22%3E%3Crect%20fill%3D%22%23f3f4f6%22%20width%3D%22300%22%20height%3D%22300%22%2F%3E%3Ctext%20fill%3D%22%239ca3af%22%20font-family%3D%22sans-serif%22%20font-size%3D%2230%22%20dy%3D%2210.5%22%20font-weight%3D%22bold%22%20x%3D%2250%25%22%20y%3D%2250%25%22%20text-anchor%3D%22middle%22%3E%F0%9F%93%B7%3C%2Ftext%3E%3C%2Fsvg%3E";

// --- Init ---
window.onload = async () => {
  await loadData(0);
};

function navigateTo(viewId) {
  document
    .querySelectorAll(".page-section")
    .forEach((el) => el.classList.remove("active"));
  document.getElementById(viewId).classList.add("active");
  window.scrollTo(0, 0);
}

// --- API & Data ---
async function loadData(catId) {
  const grid = document.getElementById("products-grid");
  if (grid)
    grid.innerHTML =
      '<div class="col-span-full text-center py-10 text-gray-400">جاري التحميل...</div>';

  try {
    let url = "api.php";
    if (catId > 0) url += `?cat_id=${catId}`;

    const res = await fetch(url);
    const data = await res.json();

    if (data.categories) {
      allCategories = data.categories;
      renderCategories();
    }
    if (data.active_category) {
      activeCategory = data.active_category;
      renderCategories();
    }

    currentProducts = data.products || [];
    renderHome();
  } catch (e) {
    console.error(e);
    if (grid)
      grid.innerHTML =
        '<div class="col-span-full text-center text-red-400 py-10">فشل الاتصال بالسيرفر</div>';
  }
}

function renderCategories() {
  document.getElementById("categories-container").innerHTML = allCategories
    .map(
      (c) => `
                <button onclick="setCat('${
                  c.id
                }')" class="px-5 py-2 rounded-full whitespace-nowrap text-sm font-bold transition-all ${
        activeCategory == c.id
          ? "bg-apple-500 text-white shadow-md scale-105 shadow-apple-500/30"
          : "bg-gray-100 text-gray-600 hover:bg-gray-200"
      }">
                    ${c.name}
                </button>
            `
    )
    .join("");
}

function setCat(id) {
  if (id != activeCategory) {
    activeCategory = id;
    renderCategories();
    document.getElementById("searchInput").value = "";
    loadData(id);
  }
}

function renderHome() {
  renderGrid(currentProducts);
}

function renderGrid(products) {
  const grid = document.getElementById("products-grid");
  if (!products || products.length === 0) {
    grid.innerHTML =
      '<div class="col-span-full text-center py-20 text-gray-400 flex flex-col items-center"><span class="text-4xl mb-2">🍽️</span><span>لا توجد منتجات</span></div>';
    return;
  }
  grid.innerHTML = products
    .map(
      (p) => `
                <div onclick="showProductPage(${
                  p.id
                })" class="bg-white p-3 rounded-2xl shadow-sm border border-gray-100 cursor-pointer active:scale-95 transition group hover:shadow-md">
                    <div class="h-32 w-full overflow-hidden rounded-xl mb-3 bg-gray-100"><img src="${
                      p.image_url
                    }" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" onerror="this.src='${placeholderImg}'"></div>
                    <h3 class="font-bold text-gray-800 text-sm mb-1 line-clamp-1">${
                      p.name
                    }</h3>
                    <p class="text-xs text-gray-400 truncate">${
                      p.description || ""
                    }</p>
                </div>
            `
    )
    .join("");
}

function handleSearch() {
  const query = document.getElementById("searchInput").value.trim();
  const scope = document.querySelector(
    'input[name="searchScope"]:checked'
  ).value;
  const grid = document.getElementById("products-grid");

  if (query === "") {
    if (scope === "all") loadData(activeCategory);
    else renderGrid(currentProducts);
    return;
  }

  if (scope === "category") {
    const filtered = currentProducts.filter((p) => p.name.includes(query));
    renderGrid(filtered);
  } else {
    clearTimeout(searchTimeout);
    grid.innerHTML =
      '<div class="col-span-full text-center py-10 text-gray-400">جاري البحث...</div>';
    searchTimeout = setTimeout(async () => {
      try {
        const res = await fetch(`api.php?search=${encodeURIComponent(query)}`);
        const data = await res.json();
        if (data.products) {
          data.products.forEach((p) => {
            if (!currentProducts.find((cp) => cp.id == p.id))
              currentProducts.push(p);
          });
          renderGrid(data.products);
        } else {
          grid.innerHTML =
            '<div class="col-span-full text-center py-10 text-gray-400">لا نتائج</div>';
        }
      } catch (e) {
        console.error(e);
      }
    }, 500);
  }
}

// --- Product View ---
function showProductPage(pid) {
  currentProduct = currentProducts.find((p) => p.id == pid);
  if (!currentProduct) return;

  document.getElementById("p-page-img").src = currentProduct.image_url;
  document.getElementById("p-page-title").innerText = currentProduct.name;
  document.getElementById("p-page-desc").innerText = currentProduct.description;

  const modifiers = currentProduct.modifiers || [];
  const container = document.getElementById("primary-options-grid");

  if (modifiers.length > 0) {
    const firstGroup = modifiers[0];
    container.innerHTML = firstGroup.options
      .map(
        (opt) => `
                    <div onclick="selectPrimaryOption(${opt.price}, '${opt.name}')" class="flex justify-between items-center p-4 border border-gray-200 rounded-xl hover:border-apple-500 hover:bg-apple-50 cursor-pointer transition shadow-sm bg-white mb-2 group">
                        <div class="flex items-center gap-3">
                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 group-hover:border-apple-500"></div>
                            <span class="font-bold text-gray-700">${opt.name}</span>
                        </div>
                        <span class="font-bold text-apple-500">${opt.price} ريال</span>
                    </div>
                `
      )
      .join("");
  } else {
    container.innerHTML = `<div onclick="selectPrimaryOption(${currentProduct.price}, 'Default')" class="w-full bg-apple-500 text-white p-4 rounded-xl font-bold text-center shadow-lg cursor-pointer hover:bg-apple-600">اطلب الآن (${currentProduct.price} ريال)</div>`;
  }
  navigateTo("view-product");
}

// --- MODAL: Selection & Logic ---
function selectPrimaryOption(price, name) {
  selectedPrimaryOption = { name: name, price: parseFloat(price) };

  // إعادة ضبط الحالة (حالة إضافة جديدة)
  mainQty = 1;
  currentModifiers = {};
  editingCartIndex = -1; // تأكيد أننا لا نعدل منتجاً قديماً
  updateAddBtnText();

  openAddonsModal();
}

function openAddonsModal() {
  document.getElementById(
    "selected-variant-name"
  ).innerText = `${currentProduct.name} - ${selectedPrimaryOption.name}`;
  document.getElementById("final-qty").innerText = mainQty;

  const modifiers = currentProduct.modifiers || [];
  const container = document.getElementById("secondary-options-container");
  const addOnsGroups = modifiers.slice(1);

  if (addOnsGroups.length === 0) {
    container.innerHTML =
      '<div class="text-center text-gray-400 py-10 bg-gray-50 rounded-xl">لا توجد إضافات لهذا الصنف</div>';
  } else {
    container.innerHTML = addOnsGroups
      .map(
        (grp, gIdx) => `
                    <div class="mb-6">
                        <h4 class="font-bold text-gray-800 mb-3 text-sm bg-gray-100 inline-block px-3 py-1 rounded-lg">${
                          grp.title
                        }</h4>
                        <div class="space-y-3">
                            ${grp.options
                              .map((opt, oIdx) => {
                                const modId = `mod_${gIdx}_${oIdx}`;
                                // نتحقق هل الإضافة موجودة مسبقاً (في حالة التعديل)
                                const currentQty = currentModifiers[modId]
                                  ? currentModifiers[modId].qty
                                  : 0;
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
                              })
                              .join("")}
                        </div>
                    </div>
                `
      )
      .join("");
  }
  document.getElementById("addons-modal").classList.remove("hidden");
  calcTotal();
}

function updateModifierQty(id, name, price, change) {
  if (!currentModifiers[id]) currentModifiers[id] = { name, price, qty: 0 };
  if (currentModifiers[id].qty + change >= 0) {
    currentModifiers[id].qty += change;
    document.getElementById(`qty_${id}`).innerText = currentModifiers[id].qty;
    if (currentModifiers[id].qty === 0) delete currentModifiers[id];
    calcTotal();
  }
}

function updateMainQty(n) {
  if (mainQty + n >= 1) {
    mainQty += n;
    document.getElementById("final-qty").innerText = mainQty;
    calcTotal();
  }
}

function calcTotal() {
  let addonsTotal = 0;
  for (let key in currentModifiers) {
    addonsTotal += currentModifiers[key].price * currentModifiers[key].qty;
  }
  const total = (selectedPrimaryOption.price + addonsTotal) * mainQty;
  document.getElementById("final-price-btn").innerText =
    total.toFixed(2) + " ريال";
  return total;
}

function updateAddBtnText() {
  const btnText = document.getElementById("add-btn-text");
  if (editingCartIndex > -1) {
    btnText.innerText = "حفظ التعديلات";
  } else {
    btnText.innerText = "إضافة للسلة";
  }
}

function closeAddons() {
  document.getElementById("addons-modal").classList.add("hidden");
}

// --- CART: Add, Edit, Delete ---
function addToCart() {
  const finalTotal = calcTotal();
  const modifiersList = Object.values(currentModifiers)
    .filter((m) => m.qty > 0)
    .sort((a, b) => a.name.localeCompare(b.name));
  const signature = JSON.stringify({
    pid: currentProduct.id,
    variant: selectedPrimaryOption.name,
    mods: modifiersList,
  });

  // بيانات المنتج للسلة
  const cartItem = {
    signature: signature,
    pid: currentProduct.id, // نحفظ الـ ID لاستخدامه عند التعديل
    productName: currentProduct.name,
    variant: selectedPrimaryOption.name,
    price: selectedPrimaryOption.price, // سعر الوحدة الأساسي
    modifiers: modifiersList,
    rawModifiers: JSON.parse(JSON.stringify(currentModifiers)), // نحفظ النسخة الخام لإعادة تعبئة المودال
    qty: mainQty,
    total: finalTotal,
    unitPrice: finalTotal / mainQty,
  };

  if (editingCartIndex > -1) {
    // حالة التعديل: نستبدل العنصر القديم
    cart[editingCartIndex] = cartItem;
    editingCartIndex = -1; // إعادة تعيين
  } else {
    // حالة الإضافة الجديدة: نتحقق من التكرار
    const existingIdx = cart.findIndex((item) => item.signature === signature);
    if (existingIdx > -1) {
      cart[existingIdx].qty += mainQty;
      cart[existingIdx].total =
        cart[existingIdx].unitPrice * cart[existingIdx].qty;
    } else {
      cart.push(cartItem);
    }
  }

  updateCartUI();
  closeAddons();
  // إذا كنا نعدل، نعود للسلة، وإلا نعود للرئيسية
  if (document.getElementById("view-cart").classList.contains("active")) {
    openCartPage();
  } else {
    navigateTo("view-home");
  }
}

// --- ميزة التعديل الجديدة ---
function editCartItem(index) {
  const item = cart[index];

  // 1. العثور على المنتج الأصلي لبيانات الصورة والخيارات
  // نحاول العثور عليه في المنتجات المحملة، إذا لم نجده (بسبب تغيير الفئة) قد نحتاج لجلبه
  // للتبسيط، نفترض أنه موجود في currentProducts أو allProducts
  // (في نظام متكامل، يجب جلب المنتج بالـ ID من السيرفر إذا لم يكن موجوداً)
  let product = currentProducts.find((p) => p.id == item.pid);

  // إذا لم يكن المنتج في القائمة الحالية (مثلاً بحثنا عن شيء آخر)، نستخدم بيانات السلة للتمهيد
  // ملاحظة: لكي يعمل التعديل بشكل مثالي يجب أن يكون المنتج متاحاً في currentProducts
  if (!product) {
    // حل سريع: البحث في المنتجات المخزنة مؤقتاً لو كنا نستخدم Store أكبر
    // أو تنبيه المستخدم
    // سنجرب البحث عنه، إذا فشل سنعتمد على البيانات المخزنة جزئياً
    alert("لا يمكن تعديل هذا المنتج حالياً لأنه غير موجود في القائمة المعروضة");
    return;
  }

  currentProduct = product;

  // 2. استعادة الحالة
  selectedPrimaryOption = { name: item.variant, price: item.price };
  mainQty = item.qty;
  currentModifiers = JSON.parse(JSON.stringify(item.rawModifiers || {})); // استعادة الإضافات

  editingCartIndex = index; // تفعيل وضع التعديل

  // 3. تحديث واجهة المودال وفتحه
  updateAddBtnText();
  openAddonsModal();
}

function updateCartUI() {
  const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);
  const totalPrice = cart.reduce((sum, item) => sum + item.total, 0);

  document.getElementById("home-cart-count").innerText = totalQty;
  document.getElementById("float-count").innerText = totalQty;
  document.getElementById("float-total").innerText =
    totalPrice.toFixed(2) + " ريال";

  if (totalQty > 0)
    document.getElementById("sticky-cart").classList.remove("hidden");
  else document.getElementById("sticky-cart").classList.add("hidden");
}

function openCartPage() {
  const container = document.getElementById("cart-items-container");
  if (cart.length === 0) {
    container.innerHTML =
      '<div class="text-center py-20 text-gray-400 flex flex-col items-center"><span class="text-6xl mb-4">🛒</span><span>السلة فارغة</span></div>';
    document.getElementById("cart-grand-total").innerText = "0 ريال";
  } else {
    container.innerHTML = cart
      .map((item, idx) => {
        const modsText = item.modifiers
          .map((m) => `${m.name} (x${m.qty})`)
          .join(", ");
        return `
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex justify-between items-start animate-fade-in">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800 text-lg">${
                              item.productName
                            }</h3>
                            <div class="text-sm text-gray-500 mt-1 space-y-1">
                                <span class="bg-apple-50 text-apple-700 px-2 py-0.5 rounded text-xs font-bold border border-apple-100">${
                                  item.variant
                                }</span>
                                ${
                                  modsText
                                    ? `<div class="text-xs text-gray-600 mt-1 flex items-center gap-1"><span class="text-apple-500">➕</span> ${modsText}</div>`
                                    : ""
                                }
                            </div>
                            <div class="mt-3 font-bold text-gray-800 flex items-center gap-2">
                                <span class="text-xs text-gray-400">سعر الوحدة: ${item.unitPrice.toFixed(
                                  2
                                )}</span>
                                <span class="bg-gray-100 px-2 rounded text-sm">x${
                                  item.qty
                                }</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-3 justify-between h-full pl-2">
                            <span class="font-bold text-lg text-apple-500">${item.total.toFixed(
                              2
                            )}</span>
                            <div class="flex gap-2">
                                <button onclick="editCartItem(${idx})" class="text-blue-500 bg-blue-50 p-2 rounded-lg hover:bg-blue-100 transition shadow-sm" title="تعديل">✏️</button>
                                <button onclick="removeFromCart(${idx})" class="text-red-500 bg-red-50 p-2 rounded-lg hover:bg-red-100 transition shadow-sm" title="حذف">🗑️</button>
                            </div>
                        </div>
                    </div>
                `;
      })
      .join("");

    const totalPrice = cart.reduce((sum, item) => sum + item.total, 0);
    document.getElementById("cart-grand-total").innerText =
      totalPrice.toFixed(2) + " ريال";
  }
  navigateTo("view-cart");
}

function removeFromCart(idx) {
  if (confirm("هل أنت متأكد من حذف هذا العنصر؟")) {
    cart.splice(idx, 1);
    updateCartUI();
    openCartPage();
  }
}

////
// إنشاء UUID إذا لم يكن موجود
function getOrCreateUUID() {
  let uuid = localStorage.getItem("user_uuid");
  if (!uuid) {
    uuid = crypto.randomUUID();
    localStorage.setItem("user_uuid", uuid);
  }
  return uuid;
}

// تحديد الجهاز والمتصفح والإصدار
function getDeviceDetails() {
  const ua = navigator.userAgent;

  let device = "Unknown";
  let os = "Unknown";
  let os_version = "Unknown";
  let browser = "Unknown";
  let browser_version = "Unknown";

  // ====== تحديد الجهاز ======
  if (ua.includes("Android")) device = "Android Phone";
  else if (ua.includes("iPhone")) device = "iPhone";
  else if (ua.includes("iPad")) device = "iPad";
  else if (ua.includes("Windows")) device = "Windows PC";
  else if (ua.includes("Macintosh")) device = "Mac";
  else if (ua.includes("Linux")) device = "Linux PC";

  // ====== نظام التشغيل + الإصدار ======
  if (/Android\s([0-9.]+)/.test(ua)) {
    os = "Android";
    os_version = ua.match(/Android\s([0-9.]+)/)[1];
  } else if (/iPhone OS\s([0-9_]+)/.test(ua)) {
    os = "iOS";
    os_version = ua.match(/iPhone OS\s([0-9_]+)/)[1].replace(/_/g, ".");
  } else if (/Mac OS X\s([0-9_]+)/.test(ua)) {
    os = "MacOS";
    os_version = ua.match(/Mac OS X\s([0-9_]+)/)[1].replace(/_/g, ".");
  } else if (/Windows NT\s([0-9.]+)/.test(ua)) {
    os = "Windows";
    os_version = ua.match(/Windows NT\s([0-9.]+)/)[1];
  }

  // ====== المتصفح + الإصدار ======
  if (/Chrome\/([0-9.]+)/.test(ua)) {
    browser = "Chrome";
    browser_version = ua.match(/Chrome\/([0-9.]+)/)[1];
  } else if (/Firefox\/([0-9.]+)/.test(ua)) {
    browser = "Firefox";
    browser_version = ua.match(/Firefox\/([0-9.]+)/)[1];
  } else if (/Safari\/([0-9.]+)/.test(ua) && !ua.includes("Chrome")) {
    browser = "Safari";
    browser_version = ua.match(/Version\/([0-9.]+)/)?.[1] ?? "Unknown";
  }

  return {
    device,
    os,
    os_version,
    browser,
    browser_version,
    user_agent: ua,
  };
}

// إرسال بيانات الزائر للسيرفر
async function registerVisitor() {
  const uuid = getOrCreateUUID();
  const info = getDeviceDetails();

  await fetch("visitor.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      uuid,
      device: info.device,
      os: info.os,
      os_version: info.os_version,
      browser: info.browser,
      browser_version: info.browser_version,
      user_agent: info.user_agent,
    }),
  });
}

// استدعاء عند فتح الموقع
registerVisitor();
