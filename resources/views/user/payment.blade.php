@extends('layouts.user')

@section('content')
    <style>
        /* Styling for the payment page */
        .payment-page {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f9fafb;
        }

        .payment-container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .payment-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .payment-desc {
            font-size: 16px;
            color: #555;
            margin-bottom: 30px;
        }

        .payment-summary {
            margin-bottom: 30px;
            text-align: left;
        }

        .payment-summary h2 {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }

        .summary-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .summary-list li {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .summary-list .item-title {
            font-weight: 500;
            color: #555;
        }

        .summary-list .item-value {
            font-weight: 600;
            color: #333;
        }

        .payment-action {
            text-align: center;
        }

        .btn-pay {
            background-color: #4CAF50;
            color: white;
            font-size: 18px;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-pay i {
            margin-right: 8px;
        }

        .btn-pay:hover {
            background-color: #45a049;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .payment-container {
                padding: 20px;
            }

            .payment-title {
                font-size: 20px;
            }

            .payment-desc {
                font-size: 14px;
            }

            .summary-list li {
                font-size: 14px;
            }

            .btn-pay {
                font-size: 16px;
                padding: 10px 15px;
            }
        }
    </style>
    <div class="payment-page">
        <div class="payment-container">
            <h1 class="payment-title">Pembayaran Anda</h1>
            <p class="payment-desc">Selesaikan pembayaran Anda untuk melanjutkan proses pemesanan.</p>

            <div class="payment-summary">
                <h2>Ringkasan Pembayaran</h2>
                <ul class="summary-list">
                    <li>
                        <span class="item-title">Kode Transaksi:</span>
                        <span class="item-value">{{ $transaction->transaction_code }}</span>
                    </li>
                    <li>
                        <span class="item-title">Total Harga:</span>
                        <span class="item-value">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                    </li>
                    <li>
                        <span class="item-title">Metode Pembayaran:</span>
                        <span class="item-value">{{ ucfirst($transaction->payment_method) }}</span>
                    </li>
                </ul>
            </div>
            <form action="api/midtrans/notification" method="POST">
                @csrf
                <div class="payment-action">
                    <button id="pay-button" type="button" class="btn-pay">
                        <i class="fas fa-credit-card"></i> Bayar Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.clientKey') }}">
    </script>
    <script type="text/javascript">
        const payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function(event) {
            event.preventDefault(); 
            console.log('Snap Token: {{ $snapToken }}');
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href =
                        "{{ route('checkout.success', ['transaction_code' => $transaction->transaction_code]) }}";
                },
                onPending: function(result) {
                    alert("Menunggu pembayaran.");
                    window.location.href = "{{ url()->previous() }}";
                },
                onError: function(result) {
                    alert("Pembayaran gagal!");
                    console.log(result);
                }
            });
        });
    </script>
@endsection
