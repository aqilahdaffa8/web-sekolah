// resources/js/utils.js

export const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    }).format(date);
};

export const formatRupiah = (amount) => {
    if (amount === null || amount === undefined) return '-';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
};

export const showToast = (message, type = 'success') => {
    const event = new CustomEvent('toast-message', {
        detail: { message, type }
    });
    window.dispatchEvent(event);
};
