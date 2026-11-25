
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('productSearch');
    const categoryButtons = document.querySelectorAll('.pos-category');
    const productCards = document.querySelectorAll('.pos-product-card');

    const cartContainer = document.getElementById('posCartItems');
    const cartItemCount = document.getElementById('cartItemCount');

    // CUSTOMER ELEMENTS
    const customerSelect = document.getElementById('posCustomerSelect');
    const walkInCustomerField = document.getElementById('walkInCustomerField');
    const walkInCustomerName = document.getElementById('walkInCustomerName');
    const selectedCustomerDetails = document.getElementById('selectedCustomerDetails');
    const selectedCustomerName = document.getElementById('selectedCustomerName');
    const selectedCustomerContact = document.getElementById('selectedCustomerContact');
    const addCustomerButton = document.getElementById('addCustomerButton');

    let activeCategory = 'all';
    let cart = [];
    let billDiscountType = 'percentage';
    let billDiscountValue = 0;

    // CUSTOMER SELECTION
    function updateCustomerSelection() {

        const selectedOption = customerSelect.selectedOptions[0];

        if (!customerSelect.value) {

            // WALK-IN CUSTOMER
            walkInCustomerField.style.display = 'block';
            selectedCustomerDetails.hidden = true;

            walkInCustomerName.disabled = false;

        } else {

            // REGISTERED CUSTOMER
            walkInCustomerField.style.display = 'none';
            selectedCustomerDetails.hidden = false;

            walkInCustomerName.disabled = true;

            const name = selectedOption.dataset.name || '';
            const phone = selectedOption.dataset.phone || '';
            const email = selectedOption.dataset.email || '';

            selectedCustomerName.textContent = name;

            selectedCustomerContact.textContent =
            [phone, email].filter(Boolean).join(" | ");
        }
    }

    customerSelect.addEventListener('change', updateCustomerSelection);

    // INITIAL CUSTOMER STATE
    updateCustomerSelection();

    // ADD CUSTOMER MODAL AND AJAX REGISTRATION
    const customerModal = document.getElementById('addCustomerModal');
    const customerForm = document.getElementById('addCustomerForm');
    const saveCustomerButton = document.getElementById('saveCustomerButton');
    const customerFormMessage = document.getElementById('customerFormMessage');

    function openCustomerModal() {
        customerForm.reset();
        clearCustomerErrors();

        customerModal.classList.add('active');
        customerModal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('pos-modal-open');

        document.getElementById('newCustomerName').focus();
    }

    function closeCustomerModal() {
        customerModal.classList.remove('active');
        customerModal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('pos-modal-open');
    }

    function clearCustomerErrors() {
        ['name', 'phone', 'email'].forEach(function (field) {
            const error = document.getElementById(field + 'Error');
            const input = document.querySelector('[name="' + field + '"]');

            error.textContent = '';
            input.classList.remove('is-invalid');
        });

        customerFormMessage.textContent = '';
        customerFormMessage.className = 'pos-customer-form-message';
    }

    function showCustomerError(field, message) {
        const error = document.getElementById(field + 'Error');
        const input = document.querySelector('[name="' + field + '"]');

        if (error && input) {
            error.textContent = message;
            input.classList.add('is-invalid');
        }
    }

    addCustomerButton.addEventListener('click', openCustomerModal);

    document.getElementById('closeCustomerModal')
        .addEventListener('click', closeCustomerModal);

    document.getElementById('cancelCustomerButton')
        .addEventListener('click', closeCustomerModal);

    document.getElementById('closeCustomerModalOverlay')
        .addEventListener('click', closeCustomerModal);

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && customerModal.classList.contains('active')) {
            closeCustomerModal();
        }
    });

    document.getElementById('newCustomerPhone').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
    });

    customerForm.querySelectorAll('input').forEach(function (input) {
        input.addEventListener('input', function () {
            this.classList.remove('is-invalid');

            const error = document.getElementById(this.name + 'Error');
            if (error) error.textContent = '';
        });
    });

    customerForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        clearCustomerErrors();

        if (!customerForm.reportValidity()) {
            return;
        }

        const phone = document.getElementById('newCustomerPhone').value.trim();
        const email = document.getElementById('newCustomerEmail').value.trim();

        if (!/^[6-9][0-9]{9}$/.test(phone)) {
            showCustomerError('phone', 'Enter a valid 10-digit Indian mobile number.');
            return;
        }

        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showCustomerError('email', 'Enter a valid email address.');
            return;
        }

        saveCustomerButton.disabled = true;
        saveCustomerButton.innerHTML = '<i class="bi bi-arrow-repeat"></i> Saving...';

        try {
            const response = await fetch(customerForm.dataset.url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(customerForm)
            });

            const result = await response.json();

            if (!response.ok) {
                if (response.status === 422 && result.errors) {
                    Object.keys(result.errors).forEach(function (field) {
                        showCustomerError(field, result.errors[field][0]);
                    });
                } else {
                    throw new Error(result.message || 'Unable to save customer.');
                }

                return;
            }

            const customer = result.customer;

            const option = document.createElement('option');
            option.value = customer.id;
            option.dataset.name = customer.name;
            option.dataset.phone = customer.phone;
            option.dataset.email = customer.email || '';
            option.textContent = customer.name + ' - ' + customer.phone;

            customerSelect.appendChild(option);
            customerSelect.value = customer.id;

            updateCustomerSelection();

            walkInCustomerName.value = 'Walk-in Customer';

            customerFormMessage.textContent = 'Customer added successfully.';
            customerFormMessage.className = 'pos-customer-form-message success';

            saveCustomerButton.innerHTML = '<i class="bi bi-check-circle"></i> Saved';

            setTimeout(function () {
                closeCustomerModal();
                customerForm.reset();
                clearCustomerErrors();
                saveCustomerButton.innerHTML = '<i class="bi bi-check-circle"></i> Save Customer';
            }, 900);

        } catch (error) {
            customerFormMessage.textContent = error.message || 'Something went wrong. Please try again.';
            customerFormMessage.className = 'pos-customer-form-message error';

        } finally {
            saveCustomerButton.disabled = false;
        }
    });

    // GET CURRENT CUSTOMER DETAILS FOR FUTURE BILLING
    function getCustomerDetails() {

        if (!customerSelect.value) {

            return {
                customer_id: null,
                customer_name: walkInCustomerName.value.trim() || 'Walk-in Customer',
                customer_phone: null,
                customer_email: null
            };

        }

        const option = customerSelect.selectedOptions[0];

        return {
            customer_id: customerSelect.value,
            customer_name: option.dataset.name || '',
            customer_phone: option.dataset.phone || '',
            customer_email: option.dataset.email || ''
        };
    }

    // SEARCH AND CATEGORY FILTER
    function filterProducts() {

        const search = searchInput.value.toLowerCase().trim();

        productCards.forEach(function (card) {

            const matchesSearch = card.dataset.search.includes(search);

            const matchesCategory = activeCategory === 'all' ||
                card.dataset.category === activeCategory;

            card.style.display = matchesSearch && matchesCategory ? '' : 'none';

        });
    }

    searchInput.addEventListener('input', filterProducts);

    categoryButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            categoryButtons.forEach(btn => btn.classList.remove('active'));

            button.classList.add('active');

            activeCategory = button.dataset.category;

            filterProducts();

        });
    });

    // BILL DISCOUNT MODAL
    const billDiscountModal = document.getElementById('addBillDiscountModal');
    const billDiscountButton = document.getElementById('addBillDiscountButton');
    const billDiscountTypeInput = document.getElementById('billDiscountType');
    const billDiscountValueInput = document.getElementById('billDiscountValue');
    const billDiscountError = document.getElementById('billDiscountError');
    const billDiscountMessage = document.getElementById('billDiscountMessage');

    function openBillDiscountModal() {
        billDiscountTypeInput.value = billDiscountType;
        billDiscountValueInput.value = billDiscountValue || '';
        billDiscountError.textContent = '';
        billDiscountMessage.textContent = '';

        billDiscountModal.classList.add('active');
        billDiscountModal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('pos-modal-open');

        billDiscountValueInput.focus();
    }

    function closeBillDiscountModal() {
        billDiscountModal.classList.remove('active');
        billDiscountModal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('pos-modal-open');
    }

    billDiscountButton.addEventListener('click', openBillDiscountModal);

    document.getElementById('closeBillDiscountModal')
        .addEventListener('click', closeBillDiscountModal);

    document.getElementById('closeBillDiscountOverlay')
        .addEventListener('click', closeBillDiscountModal);

    billDiscountValueInput.addEventListener('input', function () {
        billDiscountError.textContent = '';
        billDiscountMessage.textContent = '';
    });

    document.getElementById('applyBillDiscountButton')
        .addEventListener('click', function () {

            const value = Number(billDiscountValueInput.value);
            const type = billDiscountTypeInput.value;

            const subtotal = cart.reduce(function (sum, item) {
                return sum + (item.price * item.quantity);
            }, 0);

            if (cart.length === 0 || subtotal <= 0) {
                billDiscountError.textContent = 'Add products to the cart first.';
                return;
            }

            if (!Number.isFinite(value) || value <= 0) {
                billDiscountError.textContent = 'Enter a discount greater than zero.';
                return;
            }

            if (type === 'percentage' && value > 100) {
                billDiscountError.textContent = 'Percentage cannot exceed 100%.';
                return;
            }

            const discountAmount = type === 'percentage'
                ? subtotal * value / 100
                : value;

            if (discountAmount > subtotal) {
                billDiscountError.textContent = 'Discount cannot exceed the subtotal.';
                return;
            }

            billDiscountType = type;
            billDiscountValue = value;

            updateTotals();
            closeBillDiscountModal();
        });

    document.getElementById('removeBillDiscountButton')
        .addEventListener('click', function () {
            billDiscountType = 'percentage';
            billDiscountValue = 0;

            updateTotals();
            closeBillDiscountModal();
        });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && billDiscountModal.classList.contains('active')) {
            closeBillDiscountModal();
        }
    });
    // ADD PRODUCTS TO CART
    productCards.forEach(function (card) {

        card.addEventListener('click', function () {

            const variantId = card.dataset.variantId;
            const price = Number(card.dataset.price);

            if (!variantId || card.dataset.price === '' || !Number.isFinite(price)) {
                return;
            }

            const existingItem = cart.find(item => item.variantId === variantId);

            if (existingItem) {

                existingItem.quantity++;

            } else {

                cart.push({
                    variantId: variantId,
                    name: card.dataset.productName,
                    variant: card.dataset.variantName,
                    sku: card.dataset.sku,
                    price: price,
                    gstRate: Number(card.dataset.gstRate) || 0,
                    quantity: 1
                });

            }

            renderCart();

        });
    });

    // DISPLAY CART
    function renderCart() {

        const totalQuantity = cart.reduce((sum, item) => sum + item.quantity, 0);

        cartItemCount.textContent = totalQuantity +
            (totalQuantity === 1 ? ' Item' : ' Items');

        if (cart.length === 0) {

            cartContainer.innerHTML = `
                <div class="pos-empty-cart">
                    <div>
                        <i class="bi bi-cart3"></i>
                        <h5>Your cart is empty</h5>
                        <p>Select products from the catalog to start billing.</p>
                    </div>
                </div>
            `;

            updateTotals();

            return;
        }

        const isGstInvoice =
            document.querySelector('input[name="invoice_type"]:checked')?.value === 'gst';

        cartContainer.innerHTML = cart.map(item => {

            const originalPrice = item.price * item.quantity;

            const gstAmount = isGstInvoice
                ? originalPrice * item.gstRate / 100
                : 0;

            const finalPrice = originalPrice + gstAmount;

            return `
                <div class="pos-cart-item">

                    <div class="pos-cart-item-info">

                        <h6>${escapeHtml(item.name)}</h6>

                        <small>
                            ${escapeHtml(item.variant)} | SKU: ${escapeHtml(item.sku || '-')}
                        </small>

                        <small class="pos-cart-gst">
                            GST: ${Number(item.gstRate).toFixed(2)}%
                        </small>

                        <div class="pos-cart-item-price">
                            Original Price: ${formatMoney(originalPrice)}
                        </div>

                        <div class="pos-cart-gst">
                            GST Amount: ${formatMoney(gstAmount)}
                        </div>

                        <div class="pos-cart-item-price">
                            Final Price: ${formatMoney(finalPrice)}
                        </div>

                        <div class="pos-cart-qty">

                            <button type="button"
                                    data-action="decrease"
                                    data-id="${item.variantId}">-</button>

                            <span>${item.quantity}</span>

                            <button type="button"
                                    data-action="increase"
                                    data-id="${item.variantId}">+</button>

                        </div>

                    </div>

                    <button type="button"
                            class="pos-cart-remove"
                            data-action="remove"
                            data-id="${item.variantId}"
                            title="Remove item">

                        <i class="bi bi-trash"></i>

                    </button>

                </div>
            `;

        }).join('');

        updateTotals();
    }

    // INCREASE, DECREASE AND REMOVE CART ITEMS
    cartContainer.addEventListener('click', function (event) {

        const button = event.target.closest('button[data-action]');

        if (!button) return;

        const item = cart.find(item => item.variantId === button.dataset.id);

        if (!item) return;

        if (button.dataset.action === 'increase') {

            item.quantity++;

        } else if (button.dataset.action === 'decrease') {

            item.quantity--;

            if (item.quantity <= 0) {
                cart = cart.filter(cartItem => cartItem.variantId !== item.variantId);
            }

        } else if (button.dataset.action === 'remove') {

            cart = cart.filter(cartItem => cartItem.variantId !== item.variantId);

        }

        renderCart();

    });

    // CALCULATE SUBTOTAL, DISCOUNT, GST AND GRAND TOTAL
    function updateTotals() {

        if (cart.length === 0) {
            billDiscountType = 'percentage';
            billDiscountValue = 0;
        }

        const isGstInvoice =
            document.querySelector('input[name="invoice_type"]:checked')?.value === 'gst';

        let taxableSubtotal = 0;
        let gstTotal = 0;

        cart.forEach(function (item) {
            taxableSubtotal += item.price * item.quantity;
        });

        let discountAmount = 0;

        if (billDiscountValue > 0 && taxableSubtotal > 0) {
            discountAmount = billDiscountType === 'percentage'
                ? taxableSubtotal * billDiscountValue / 100
                : billDiscountValue;
        }

        discountAmount = Math.min(discountAmount, taxableSubtotal);

        const discountedSubtotal = taxableSubtotal - discountAmount;

        cart.forEach(function (item) {

            const itemTotal = item.price * item.quantity;

            // Allocate bill discount proportionally to each cart item.
            const itemDiscount = taxableSubtotal > 0
                ? discountAmount * (itemTotal / taxableSubtotal)
                : 0;

            const discountedItemTotal = itemTotal - itemDiscount;

            const itemGst = isGstInvoice
                ? discountedItemTotal * (item.gstRate / 100)
                : 0;

            gstTotal += itemGst;

        });

        const grandTotal = discountedSubtotal + gstTotal;

        document.getElementById('posItemDiscount').textContent =
            formatMoney(0);

        document.getElementById('posBillDiscountAmount').textContent =
            formatMoney(discountAmount);

        document.getElementById('posSubtotal').textContent =
            formatMoney(taxableSubtotal);

        document.getElementById('posGstTotal').textContent =
            formatMoney(gstTotal);

        document.getElementById('posGrandTotal').textContent =
            formatMoney(grandTotal);

        billDiscountButton.disabled = cart.length === 0;

        document.querySelector('.pos-hold-btn').disabled = cart.length === 0;

        document.querySelector('.pos-pay-btn').disabled = cart.length === 0;

    }
    // REFRESH CART WHEN INVOICE TYPE CHANGES
    document.querySelectorAll('input[name="invoice_type"]').forEach(function (input) {

        input.addEventListener('change', function () {
            renderCart();
        });

    });

    // CURRENCY FORMAT
    function formatMoney(amount) {

        return '\u20B9' + Number(amount).toFixed(2);

    }

    // PREVENT HTML INJECTION IN PRODUCT DETAILS
    function escapeHtml(value) {

        return String(value ?? '').replace(/[&<>"']/g, function (char) {

            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];

        });

    }    // PAYMENT & CHECKOUT MODAL CONTROLS

    const checkoutModal = document.getElementById('posCheckoutModal');
    const checkoutButton = document.querySelector('.pos-pay-btn');
    const checkoutMethod = document.getElementById('checkoutPaymentMethod');
    const checkoutSingleFields = document.getElementById('checkoutSinglePaymentFields');
    const checkoutSplitFields = document.getElementById('checkoutSplitPaymentFields');
    const checkoutReferenceField = document.getElementById('checkoutReferenceField');
    const checkoutAmountInput = document.getElementById('checkoutAmountReceived');
    const checkoutAmountLabel = document.getElementById('checkoutAmountLabel');
    const checkoutError = document.getElementById('checkoutPaymentError');

    function openCheckoutModal() {
        if (cart.length === 0) return;

        const total = Number(document.getElementById('posGrandTotal').textContent.replace(/[^\d.]/g, '')) || 0;

        document.getElementById('checkoutGrandTotal').textContent = formatMoney(total);
        checkoutError.textContent = '';
        checkoutAmountInput.value = total.toFixed(2);

        document.getElementById('checkoutPaymentReference').value = '';

        document.getElementById('splitCashAmount').value = '0';
        document.getElementById('splitUpiAmount').value = '0';
        document.getElementById('splitCardAmount').value = '0';
        document.getElementById('splitUpiReference').value = '';
        document.getElementById('splitCardReference').value = '';

        checkoutMethod.value = 'cash';
        updateCheckoutPaymentFields();
        updateCheckoutBalance();

        checkoutModal.classList.add('active');
        checkoutModal.setAttribute('aria-hidden', 'false');
    }

    function closeCheckoutModal() {
        checkoutModal.classList.remove('active');
        checkoutModal.setAttribute('aria-hidden', 'true');
        checkoutError.textContent = '';
    }

    function updateCheckoutPaymentFields() {
        const method = checkoutMethod.value;

        checkoutSingleFields.style.display = method === 'split' ? 'none' : 'block';
        checkoutSplitFields.style.display = method === 'split' ? 'block' : 'none';

        checkoutReferenceField.style.display =
            ['upi', 'card'].includes(method) ? 'block' : 'none';

        const confirmButton = document.getElementById('confirmCheckoutButton');

        checkoutAmountLabel.textContent =
            method === 'cash' ? 'Cash Received' : 'Amount Paid for This Bill';

        checkoutError.textContent = '';
        confirmButton.disabled = true;

        updateCheckoutBalance();
    }

    function updateCheckoutBalance() {
        const total = Number(
            document.getElementById('checkoutGrandTotal').textContent.replace(/[^\d.]/g, '')
        ) || 0;

        const method = checkoutMethod.value;
        const reference = document.getElementById('checkoutPaymentReference').value.trim();

        const cash = Number(document.getElementById('splitCashAmount').value || 0);
        const upi = Number(document.getElementById('splitUpiAmount').value || 0);
        const card = Number(document.getElementById('splitCardAmount').value || 0);
        const upiReference = document.getElementById('splitUpiReference').value.trim();
        const cardReference = document.getElementById('splitCardReference').value.trim();
        const singleAmount = Number(checkoutAmountInput.value || 0);

        let received = 0;
        let change = 0;
        let message = '';

        const validAmount = function (value) {
            return Number.isFinite(value) &&
                value >= 0 &&
                Math.abs(value * 100 - Math.round(value * 100)) < 0.000001;
        };

        if (method === 'cash') {
            received = singleAmount;

            if (!validAmount(singleAmount) || singleAmount <= 0) {
                message = 'Enter a valid cash amount with up to two decimal places.';
            } else if (singleAmount < total) {
                message = 'Cash received must be at least the bill total.';
            } else {
                change = singleAmount - total;
            }

        } else if (method === 'upi' || method === 'card') {
            received = singleAmount;

            if (!validAmount(singleAmount) || singleAmount <= 0) {
                message = 'Enter a valid payment amount with up to two decimal places.';
            } else if (Math.abs(singleAmount - total) >= 0.005) {
                message = 'Payment amount must exactly match the bill total.';
            } else if (!reference) {
                message = 'Enter the transaction reference.';
            }

        } else if (method === 'split') {
            const amounts = [cash, upi, card];

            received = amounts.reduce(function (sum, amount) {
                return sum + amount;
            }, 0);

            if (!amounts.every(validAmount)) {
                message = 'All split amounts must be non-negative with up to two decimal places.';
            } else if (Math.abs(received - total) >= 0.005) {
                message = 'Cash + UPI + Card must exactly equal the bill total.';
            } else if (upi > 0 && !upiReference) {
                message = 'Enter the UPI transaction reference.';
            } else if (card > 0 && !cardReference) {
                message = 'Enter the card transaction reference.';
            }

        }
        const remaining = Math.max(0, total - received);

        document.getElementById('checkoutChange').textContent = formatMoney(change);
        document.getElementById('checkoutRemaining').textContent = formatMoney(remaining);

        const confirmButton = document.getElementById('confirmCheckoutButton');

        confirmButton.disabled = true;

        checkoutError.textContent = message ||
            'Payment details are valid. Actual payment processing is not enabled yet.';
    }

    checkoutButton.addEventListener('click', openCheckoutModal);

    document.getElementById('closeCheckoutModal').addEventListener('click', closeCheckoutModal);
    document.getElementById('cancelCheckoutButton').addEventListener('click', closeCheckoutModal);
    document.getElementById('closeCheckoutOverlay').addEventListener('click', closeCheckoutModal);

    checkoutMethod.addEventListener('change', updateCheckoutPaymentFields);
    checkoutAmountInput.addEventListener('input', updateCheckoutBalance);
    document.getElementById('splitUpiReference').addEventListener('input', updateCheckoutBalance);
    document.getElementById('splitCardReference').addEventListener('input', updateCheckoutBalance);
    document.getElementById('checkoutPaymentReference').addEventListener('input', updateCheckoutBalance);

    checkoutSplitFields.querySelectorAll('input').forEach(function (input) {
        input.addEventListener('input', updateCheckoutBalance);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && checkoutModal.classList.contains('active')) {
            closeCheckoutModal();
        }
    });


});
