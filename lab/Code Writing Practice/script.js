const TAX_RATE = 0.05;
const priceInput = document.getElementById('price');
const discountInput = document.getElementById('discount');
const finalPriceDisplay = document.getElementById('final-price');
const priceError = document.getElementById('price-error');
const discountError = document.getElementById('discount-error');

let budgetAlertShown = false;

function calculateFinalPrice() {
    let price = Number(priceInput.value);
    let discount = Number(discountInput.value);

    if (Number.isNaN(price)) {
        price = 0;
    }
    if (Number.isNaN(discount)) {
        discount = 0;
    }


    if (price < 0) {
        price = 0;
        priceInput.value = 0;
        priceError.textContent = 'Price cannot be less than 0.';
    } else {
        priceError.textContent = '';
    }
    if (discount < 0) {
        discount = 0;
        discountInput.value = 0;
        discountError.textContent = 'discount cannot be less than 0.';
    }
    else if (discount > 100) {
        discount = 100;
        discountInput.value = 100;
        discountError.textContent = 'discount cannot exceed 100.';
    } else {
        discountError.textContent = '';
    }

    const discountAmount = price * (discount / 100);
    const discountedPrice = price - discountAmount;
    const tax = discountedPrice * TAX_RATE;
    const finalPrice = (price - discountAmount) + tax;

    finalPriceDisplay.value = `৳${finalPrice}`;

    if (finalPrice < 500 && finalPrice > 0 && !budgetAlertShown) {
        alert("You unlocked a Budget Deal!");
        budgetAlertShown = true;
    }

    if (finalPrice >= 500) {
        budgetAlertShown = false;
    }
}
priceInput.addEventListener('input', calculateFinalPrice);
discountInput.addEventListener('input', calculateFinalPrice);

calculateFinalPrice();