function initStudentInvoices() {
  const container = document.getElementById('studentInvoicesList');

  fetch('../api/get_my_invoices.php')
    .then(res => res.json())
    .then(json => {
      if (json.status !== 'success') throw new Error(json.error);

      const rows = json.data.map(inv => `
        <tr class="border-t text-sm">
          <td class="p-2">${inv.month}/${inv.year}</td>
          <td class="p-2">₹${inv.amount}</td>
          <td class="p-2">${inv.status}</td>
          <td class="p-2">${inv.type}</td>
          <td class="p-2">${inv.created_at}</td>
          <td class="p-2">
            ${inv.status === 'Paid'
              ? `<button class="text-blue-600 underline" onclick='downloadReceipt(${inv.id})'>Download</button>`
              : '<span class="text-gray-400">Pending</span>'}
          </td>
        </tr>
      `).join('');

      container.innerHTML = `
        <table class="w-full text-left border">
          <thead class="bg-gray-50">
            <tr>
              <th class="p-2">Month</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Type</th>
              <th>Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>${rows}</tbody>
        </table>
      `;
    })
    .catch(err => {
      container.innerHTML = `<p class="text-red-600">Error loading invoices: ${err.message}</p>`;
    });
}

// ✅ Now opens server-side PDF using mPDF
function downloadReceipt(invoiceId) {
  window.open(`../api/generate_receipt.php?id=${invoiceId}`, '_blank');
}
