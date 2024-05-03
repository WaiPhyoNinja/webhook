<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Form</title>
  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- Stripe.js -->
  <script src="https://js.stripe.com/v3/"></script>
  <style>
    /* Add your custom styles here */
  </style>
</head>
<body>

<div class="container mx-auto">
  <div class="flex justify-center">
    <div class="md:w-1/2">
      <h2 class="text-2xl font-bold mb-4">Payment Form</h2>
      <form id="payment-form">
        @csrf
        <div class="mb-4">
          <label for="card-element" class="block">Credit or debit card</label>
          <div id="card-element" class="border border-gray-300 rounded-md p-2 flex flex-col gap-2">
            <!-- Stripe Elements Placeholder -->
          </div>
          <div id="card-errors" role="alert" class="text-red-500 mt-2"></div>
        </div>

        <button id="submit-button" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full w-full mt-4">Pay Now</button>
      </form>
    </div>
  </div>
</div>

<script>

  const stripePublicKey = '{{ env('STRIPE_KEY') }}';
  const stripe = Stripe(stripePublicKey);
  console.log(stripe);
  const elements = stripe.elements();
  const cardElement = elements.create('card');
  cardElement.mount('#card-element');
  const cardErrors = document.getElementById('card-errors');
  const submitButton = document.getElementById('submit-button');


  document.getElementById('payment-form').addEventListener('submit', async (event) => {
    event.preventDefault();

    submitButton.disabled = true;

    const { paymentMethod, error } = await stripe.createPaymentMethod({
      type: 'card',
      card: cardElement,
    });

    if (error) {
      cardErrors.textContent = error.message;
      submitButton.disabled = false;
    } else {

      fetch('/process-payment', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ payment_method_id: paymentMethod.id }),
      }).then((response) => {
        console.log(response);
      });
    }
  });
</script>

</body>
</html>
