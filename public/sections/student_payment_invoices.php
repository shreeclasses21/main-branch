<?php
session_start();
if (!isset($_SESSION['contact_id'])) {
    header("Location: ../index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Payment Invoices</title>
    <style>
        /* Base styles for better responsiveness */
        :root {
            font-size: 16px; /* Base font size for rem units */
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #333;
            background-color: #f3f4f6; /* Light gray background */
            min-height: 100vh;
            line-height: 1.6;
        }

        /* Main content wrapper to center and provide padding */
        .main-content-wrapper {
            width: 100%;
            max-width: 900px; /* Max width for readability on larger screens */
            margin: 0 auto;
            padding: 1rem; /* Responsive padding */
            box-sizing: border-box;
        }

        /* Styles for the main section container */
        .invoice-section-container {
            background-color: #ffffff;
            box-shadow: 0 0.625rem 1.5625rem rgba(0, 0, 0, 0.1); /* Use rem for shadow */
            border-radius: 1rem; /* Use rem for border-radius */
            padding: 1.5rem; /* Responsive padding */
            box-sizing: border-box;
            border: 0.0625rem solid #e0e0e0;
        }

        /* Styles for the main heading */
        .invoice-section-container h2 {
            font-size: 1.8rem; /* Adjusted for better mobile scaling */
            color: #6a0572; /* Deeper purple */
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 0.1875rem solid #8e24aa; /* Use rem for border thickness */
            display: flex;
            align-items: center;
            gap: 0.5rem; /* Space between icon and text */
            flex-wrap: wrap; /* Allow wrapping for long titles on small screens */
        }

        /* Invoice List Container */
        #studentInvoicesList {
            display: grid;
            gap: 1.25rem; /* Space between invoice cards */
        }

        /* Individual Invoice Card */
        .invoice-card {
            background-color: #ffffff;
            border: 0.0625rem solid #e0e0e0;
            border-radius: 0.75rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.08);
            padding: 1.25rem; /* Responsive padding */
            box-sizing: border-box;
            display: flex;
            flex-direction: column; /* Stack details vertically */
            gap: 0.625rem; /* Space between rows of details */
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            /* position: relative; Removed as badge is no longer absolutely positioned */
        }

        .invoice-card:hover {
            transform: translateY(-0.4375rem);
            box-shadow: 0 0.9375rem 1.5625rem rgba(0, 0, 0, 0.15);
        }

        /* Invoice detail row (e.g., Month, Amount) */
        .invoice-detail-row {
            display: flex;
            justify-content: space-between; /* Space out label and value */
            align-items: baseline; /* Align text baselines */
            font-size: 0.95rem; /* Responsive font size */
            padding-bottom: 0.3rem; /* Small padding at bottom */
            border-bottom: 0.0625rem dashed #f0f0f0; /* Subtle separator */
        }

        .invoice-detail-row:last-of-type {
            border-bottom: none; /* No border for the last detail row */
        }

        .invoice-detail-row strong {
            color: #555; /* Label color */
            flex-shrink: 0; /* Prevent label from shrinking */
            margin-right: 0.5rem; /* Space between label and value */
        }

        .invoice-detail-row span {
            color: #333; /* Value color */
            text-align: right; /* Align value to the right */
            flex-grow: 1; /* Allow value to take up remaining space */
        }

        /* For the month row, to align month and badge */
        .month-row-content {
            display: flex;
            justify-content: space-between;
            align-items: center; /* Align items vertically in the middle */
            flex-grow: 1;
            gap: 0.5rem; /* Space between month and badge */
        }


        /* Status Badge */
        .invoice-status-badge {
            /* Removed absolute positioning */
            padding: 0.3rem 0.6rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #ffffff;
            text-transform: uppercase;
            white-space: nowrap; /* Prevent text wrapping */
        }

        .invoice-status-badge.Paid {
            background-color: #28a745; /* Green for Paid */
        }

        .invoice-status-badge.Pending {
            background-color: #ff9800; /* Orange for Pending */
        }

        /* Action button styling */
        .invoice-action-btn {
            background-color: #673ab7; /* Deeper indigo */
            color: #ffffff;
            padding: 0.75rem 1rem; /* Responsive padding */
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
            transition: background-color 0.2s ease-in-out, transform 0.1s ease-in-out;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
            align-self: flex-start; /* Align button to the start of the flex container */
            margin-top: 0.75rem; /* Space above button */
        }

        .invoice-action-btn:hover {
            background-color: #5e35b1;
            transform: translateY(-0.125rem);
        }

        .invoice-action-btn.pending-text {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
            opacity: 0.8;
            padding: 0.75rem 1rem; /* Keep consistent padding */
        }

        /* Loading/Error message styles */
        #studentInvoicesList p {
            text-align: center;
            font-size: 1.1rem;
            padding: 1.25rem;
            color: #6b7280;
            background-color: #f8f8f8;
            border-radius: 0.75rem;
        }
        #studentInvoicesList p.error-message {
            color: #dc2626;
            background-color: #fef2f2;
            border: 0.0625rem solid #fee2e2;
        }

        /* Media Queries for fine-tuning on smaller screens */
        @media (max-width: 768px) {
            .main-content-wrapper {
                padding: 0.75rem;
            }
            .invoice-section-container {
                padding: 1rem;
            }
            .invoice-section-container h2 {
                font-size: 1.6rem;
                justify-content: center; /* Center heading on smaller screens */
            }
            .invoice-card {
                padding: 1rem;
            }
            .invoice-detail-row {
                font-size: 0.9rem;
            }
            .invoice-action-btn {
                font-size: 0.9rem;
                padding: 0.6rem 0.9rem;
                width: 100%; /* Make buttons full width on small screens */
                text-align: center;
            }
            .invoice-status-badge {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
                /* Removed top/right adjustments */
            }
        }

        @media (max-width: 480px) {
            :root {
                font-size: 14px; /* Even smaller base font size for tiny screens */
            }
            .main-content-wrapper {
                padding: 0.5rem;
            }
            .invoice-section-container {
                padding: 0.75rem;
            }
            .invoice-section-container h2 {
                font-size: 1.4rem;
            }
            .invoice-card {
                padding: 0.75rem;
            }
            .invoice-detail-row {
                font-size: 0.85rem;
                flex-direction: column; /* Stack label and value vertically on very small screens */
                align-items: flex-start;
                gap: 0.2rem;
            }
            .invoice-detail-row span {
                text-align: left; /* Adjust alignment when stacked */
            }
            .invoice-action-btn {
                font-size: 0.85rem;
                padding: 0.5rem 0.8rem;
            }
            #studentInvoicesList p {
                font-size: 1rem;
                padding: 1rem;
            }
            /* Adjustments for month row on very small screens if needed */
            .month-row-content {
                flex-direction: column; /* Stack month and badge if space is tight */
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<div class="main-content-wrapper">
    <div class="invoice-section-container">
        <h2>🧾 My Payment Invoices</h2>

        <div id="studentInvoicesList">
            <p>Loading your invoices...</p>
        </div>
    </div>
</div>

<script src="./js/student_invoices.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        initStudentInvoices();
    });
</script>

</body>
</html>