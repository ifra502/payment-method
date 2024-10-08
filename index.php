<?php
require('stripe-php-master/init.php');

$publishableKey = "pk_test_51PwLlE2L79rUgs2waC9cHUcAxGMBtnoozwrXbakGmnuG3S0WvuJJOofdNSFJAdMxPlKEdfhSuwS0rXz9KMcrZx8p00psTnoZ1l";
$secretKey = "sk_test_51PwLlE2L79rUgs2wVShoNLmGkK82x2Aa3CJzmmszTYEDoIIEWSdvn2gzxGYFoHYqF9I9W05fnPKivNrCYnprf6YI00TP4KF0sl";

\Stripe\Stripe::setApiKey($secretKey);

if (isset($_POST['stripeToken'])) {
    \Stripe\Stripe::setVerifySslcerts(false);
    $token = $_POST['stripeToken'];
    
    // Get the selected amount from the dropdown
    $amount = isset($_POST['amount']) ? intval($_POST['amount']) : 0;

    // Convert amount to the smallest currency unit (e.g., paisa for PKR)
    $amountInPaisa = $amount * 100; // Assuming the input is in PKR

    // Create the charge
    $data = \Stripe\Charge::create(array(
        "amount" => $amountInPaisa,
        "currency" => "pkr",
        "description" => "Example charge",
        "source" => $token,
    ));
    
    echo "<pre>";
    print_r($data);
}
?>

<!-- Button to Open the Modal -->
<button id="payButton">Pay with Card</button>

<!-- The Modal -->
<div id="amountModal" style="display:none;">
    <div>
        <h2>Select Amount (PKR)</h2>
        <form id="paymentForm" method="post" action="stripe_payment.php">
            <label for="amount">Select Amount:</label>
            <select name="amount" id="amount" required>
                <option value="">-- Select Amount --</option>
                <option value="5000">5000 PKR</option>
                <option value="10000">10000 PKR</option>
                <option value="100">20000 PKR</option>
                <option value="500">30000 PKR</option>
                <option value="1000">35000 PKR</option>
                 <option value="10000">40000 PKR</option>
            </select>
            <br><br>
            <button type="button" onclick="openStripeCheckout()">Proceed to Payment</button>
        </form>
    </div>
</div>

<script src="https://checkout.stripe.com/checkout.js"></script>
<script>
    document.getElementById('payButton').onclick = function() {
        document.getElementById('amountModal').style.display = 'block';
    };

    function openStripeCheckout() {
        var amount = document.getElementById('amount').value;
        if (amount) {
            var handler = StripeCheckout.configure({
                key: "<?php echo $publishableKey ?>",
                locale: 'auto',
                token: function(token) {
                    // Add the token to the form
                    var form = document.getElementById('paymentForm');
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripeToken');
                    hiddenInput.setAttribute('value', token.id);
                    form.appendChild(hiddenInput);
                    form.submit();
                }
            });

            handler.open({
                name: 'E-Learning School',
                description: 'Learning with E Learning School',
                amount: amount * 100, // Convert to paisa
                currency: 'pkr'
            });
        } else {
            alert('Please select an amount.');
        }
    }
</script>

<style>
    #amountModal {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
    }
    #amountModal div {
        background: white;
        padding: 20px;
        border-radius: 5px;
    }
</style>