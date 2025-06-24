function initStudentInvoices() {
    const container = document.getElementById('studentInvoicesList');

    // Clear previous content and show loading message
    container.innerHTML = '<p>Loading your invoices...</p>';

    fetch('../api/get_my_invoices.php')
        .then(res => res.json())
        .then(json => {
            if (json.status !== 'success') {
                throw new Error(json.error || 'Failed to fetch invoices.');
            }

            if (json.data.length === 0) {
                container.innerHTML = '<p>No invoices found.</p>';
                return;
            }

            // Clear loading message
            container.innerHTML = '';

            json.data.forEach(inv => {
                const invoiceCard = document.createElement('div');
                invoiceCard.className = 'invoice-card';

                invoiceCard.innerHTML = `
                    <div class="invoice-status-badge ${inv.status}">${inv.status}</div>

                    <div class="invoice-detail-row">
                        <strong>Month:</strong> <span>${inv.month}/${inv.year}</span>
                    </div>
                    <div class="invoice-detail-row">
                        <strong>Amount:</strong> <span>₹${inv.amount}</span>
                    </div>
                    <div class="invoice-detail-row">
                        <strong>Type:</strong> <span>${inv.type}</span>
                    </div>
                    <div class="invoice-detail-row">
                        <strong>Date:</strong> <span>${inv.created_at}</span>
                    </div>

                    ${inv.status === 'Paid'
                        ? `<button class="invoice-action-btn" onclick='downloadReceipt(${inv.id})'>Download Receipt</button>`
                        : `<button class="invoice-action-btn pending-text" disabled>Pending</button>`
                    }
                `;
                container.appendChild(invoiceCard);
            });
        })
        .catch(err => {
            container.innerHTML = `<p class="error-message">Error loading invoices: ${err.message}</p>`;
            console.error('Error fetching invoices:', err);
        });
}

// Opens server-side PDF using mPDF (remains unchanged)
function downloadReceipt(invoiceId) {
    window.open(`../api/generate_receipt.php?id=${invoiceId}`, '_blank');
}