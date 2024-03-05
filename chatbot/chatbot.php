<?php
// chatbot.php

// Include database connection file
include('../includes/db_connect.php');

// Start session
session_start();

// Get user message
$message = $_POST['message'];

// Process user message
$response = processMessage($message, $conn); // Pass $conn as a parameter

// Send response
echo $response;

// Function to process user message
function processMessage($message, $conn) { // Accept $conn as a parameter
    $message = strtolower($message);
    
    // Get current hour
    $currentHour = date('G');

    // Greetings
    $greetings = array("hello", "hi", "hey", "howdy", "good morning", "good afternoon", "good evening");
    if (in_array($message, $greetings)) {
        if ($message == "good morning" && $currentHour >= 5 && $currentHour < 12) {
            return "Good morning! I'm Agrina. How can I assist you today?";
        } elseif ($message == "good afternoon" && $currentHour >= 12 && $currentHour < 18) {
            return "Good afternoon! I'm Agrina. How can I assist you today?";
        } elseif ($message == "good evening" && ($currentHour >= 18 || $currentHour < 5)) {
            return "Good evening! I'm Agrina. How can I assist you today?";
        } else {
            return "Hello! I'm Agrina your chat assistant. How can I assist you today?";
        }
    }
    
    // FAQs
    $faqs = array(
        "what is your return policy?" => "Our return policy allows you to return items within 30 days of purchase for a full refund.",
        "do you offer free shipping?" => "Yes, we offer free shipping on orders over $50.",
        "how can I track my order?" => "You can track your order by logging into your account and visiting the order tracking page.",
        "what payment methods do you accept?" => "We accept Visa, Mastercard, American Express, and PayPal.",
        "do you offer international shipping?" => "Yes, we offer international shipping to most countries. Shipping rates vary depending on the destination.",
        "what is the delivery time for orders?" => "Delivery times vary depending on your location and the shipping method chosen. Typically, orders are delivered within 3-7 business days.",
        "how can I contact customer support?" => "You can contact our customer support team by phone at 1-800-123-4567 or by email at support@example.com.",
        "do you have a loyalty program?" => "Yes, we have a loyalty program that rewards you with points for every purchase. You can redeem these points for discounts on future orders.",
        "what is your product warranty policy?" => "Our products come with a one-year warranty against manufacturing defects. Please refer to our warranty page for more details.",
        "do you offer gift wrapping services?" => "Yes, we offer gift wrapping services for an additional fee. You can select this option during checkout.",
        "can I change or cancel my order?" => "Once an order is placed, it cannot be changed or canceled. However, you may be able to return the items for a refund once they are received.",
        "are your products eco-friendly?" => "We strive to offer eco-friendly products whenever possible. Look for the 'eco-friendly' label on product pages for more information.",
        "do you have a size guide for clothing?" => "Yes, we provide a size guide on each clothing product page to help you choose the right size.",
        // Add more FAQs as needed
    );
    
    foreach ($faqs as $question => $answer) {
        if (strpos($message, $question) !== false) {
            return $answer;
        }
    }

    // Product Inquiry
    if (strpos($message, "product inquiry") !== false) {
        // Handle product inquiry logic
        return "Sure! Please provide the name or ID of the product you're interested in.";
    }

    // Query for available products
    if (strpos($message, "what products do you have?") !== false || strpos($message, "show me products") !== false) {
        // Query the database to fetch available products
        $query = "SELECT name FROM products";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $productsList = "Here are some of our available products:\n";
            while ($row = $result->fetch_assoc()) {
                $productsList .= "- " . $row["name"] . "\n";
            }
            // Ask if customer wants to purchase
            $productsList .= "\nWould you like to purchase any of these products? Please type 'purchase' to proceed or 'skip' to continue browsing.";
            return $productsList;
        } else {
            return "I'm sorry, but we currently don't have any products available.";
        }
    }

    // Handle skipping product purchase
    if (strpos($message, "skip") !== false) {
        return "Is there anything else that I can help you with?";
    }

    // Handle purchase initiation
    if (strpos($message, "purchase") !== false) {
        // Set session flag to indicate purchase initiation
        $_SESSION['purchase_initiated'] = true;
        // Ask for the product name
        return "What product would you like to purchase?";
    }

    // Handle subsequent messages after purchase initiation
    if (isset($_SESSION['purchase_initiated']) && $_SESSION['purchase_initiated'] === true) {
        // Reset the purchase initiation flag
        unset($_SESSION['purchase_initiated']);
    
        // Extract the product name from the user's message
        $productName = extractProductName($message);
    
        // Check if the product is available
        if (isProductAvailable($productName, $conn)) {
            // Set the selected product in session for future reference
            $_SESSION['selected_product'] = $productName;
        
            // Ask for the quantity
            return "How many units of '$productName' would you like to purchase?";
        } else {
            return "I'm sorry, but '$productName' is not available. Please choose another product or type 'skip' to cancel the purchase.";
        }
    }

    // Handle product selection and quantity
    if (strpos($message, "quantity") !== false) {
        // Extract quantity from the message
        $quantity = extractQuantity($message); // Implement this function to extract the quantity
        
        // Generate simple invoice
        $productName = $_SESSION['selected_product']; // Product name extracted during purchase initiation
        $totalPrice = calculateTotalPrice($productName, $quantity); // Implement this function
        $invoice = "Invoice:\nProduct: " . $productName . "\nQuantity: " . $quantity . "\nTotal Price: $" . $totalPrice;
        
        // Ask if customer wants to continue purchase
        $invoice .= "\nWould you like to continue with this purchase? Please type 'yes' to proceed or 'no' to cancel.";
        return $invoice;
    }

    // Handle purchase confirmation
    if (strpos($message, "yes") !== false) {
        // Add product to cart and navigate to checkout page
        $productName = $_SESSION['selected_product']; // Extracted product name
        $quantity = extractQuantity($message); // Extracted quantity
        
        addToCart($productName, $quantity, $conn); // Implement this function
        redirectToCheckoutPage(); // Implement this function
        
        return "The product has been added to your cart. You will now be redirected to the checkout page.";
    }

    // Handle order tracking
    if (strpos($message, "track order") !== false) {
        // Extract tracking number from the message
        $trackingNumber = extractTrackingNumber($message);
        
        // Query the database to retrieve order information
        $orderInfo = getOrderInfo($trackingNumber, $conn);
        $customersupport = "<a href='tel:+918451236710'>+918451236710</a>"; // Customer support number with on-click link
        
        if ($orderInfo) {
            // Construct and return response with order details
            return "Your order status for Order ID $trackingNumber is: " . $orderInfo['status']. "To know more please feel free to contact our customer support agent $customersupport";
        } else {
            return "Sorry, we couldn't find any order with Order ID $trackingNumber. Please verify the Order ID and try again.";
        }
    }

    // Handle order cancellation
    if (strpos($message, "cancel order") !== false) {
        $trackingNumber = strtoupper(trim(substr($message, strpos($message, "cancel order") + strlen("cancel order"))));
        $orderInfo = getOrderInfo($trackingNumber, $conn);

        if ($orderInfo) {
            // Update order status to canceled
            $query = "UPDATE orders SET status = 'canceled' WHERE order_id = '$trackingNumber'";
            if ($conn->query($query) === TRUE) {
                return "Your order with Order ID $trackingNumber has been successfully canceled.";
            } else {
                return "Sorry, we encountered an error while canceling your order. Please try again later.";
            }
        } else {
            return "Sorry, we couldn't find any order with Order ID $trackingNumber. Please verify the Order ID and try again.";
        }
    }

     // Handle closing conversation and feedback
     $closingMessages = array("thanks", "thank you", "bye", "see you", "catch you later");
     if (in_array($message, $closingMessages)) {
         // Ask if the customer would like to leave feedback
         return "Would you like to leave feedback? Please type 'yes' or 'no'.";
     }
 
    // Handle feedback initiation
    if (strpos($message, "yes") !== false) {
        // Set session flag to indicate feedback initiation
        $_SESSION['feedback_initiated'] = true;
        return "Please leave your feedback below:";
    } elseif (strpos($message, "no") !== false) {
        return "Thank you for your visit. Have a nice day!";
    }

    // Handle feedback submission
    if (isset($_SESSION['feedback_initiated']) && $_SESSION['feedback_initiated'] === true) {
        // Extract feedback from the user's message
        $feedback = $message;

        // Save feedback in the database
        $customerName = ""; // Extracted customer name (if available)
        $customerEmail = ""; // Extracted customer email (if available)
        saveFeedback($customerName, $customerEmail, $feedback, $conn); // Implement this function

        // Reset the feedback initiation flag
        unset($_SESSION['feedback_initiated']);

        // Say goodbye with a personalized message if customer leaves feedback
        return "Thank you for your feedback. Have a nice day!";
    }

    // Default response
    return "I'm sorry, I didn't understand that. Could you please rephrase?";
}

// Function to extract product name from message
function extractProductName($message) {
    // Implement logic to extract product name from the message
    // For demonstration purposes, let's assume the product name is everything after "purchase"
    $startIndex = strpos($message, "purchase") + strlen("purchase");
    return trim(substr($message, $startIndex));
}

// Function to extract quantity from message
function extractQuantity($message) {
    // Implement logic to extract quantity from the message
    // For demonstration purposes, let's assume the quantity is everything after "quantity"
    $startIndex = strpos($message, "quantity") + strlen("quantity");
    return trim(substr($message, $startIndex));
}

// Function to check if the product is available
function isProductAvailable($productName, $conn) {
    // Implement logic to check if the product is available in the database
    // For demonstration purposes, let's assume there is a table named 'products' with a column 'name'
    $productName = mysqli_real_escape_string($conn, $productName);
    $query = "SELECT * FROM products WHERE name = '$productName'";
    $result = $conn->query($query);
    return $result->num_rows > 0;
}

// Function to calculate total price
function calculateTotalPrice($productName, $quantity) {
    // Here you would implement the logic to calculate the total price based on the product and quantity
    // For demonstration purposes, let's assume a fixed price per product
    $pricePerProduct = 50; // Update with actual price per product
    return $pricePerProduct * $quantity;
}

// Function to add product to cart
function addToCart($productName, $quantity, $conn) {
    // Here you would implement the logic to add the product to the cart
    // For demonstration purposes, let's assume we insert the product into a cart table in the database
    $query = "INSERT INTO cart (product_name, quantity) VALUES ('$productName', $quantity)";
    $conn->query($query);
}

// Function to redirect to checkout page
function redirectToCheckoutPage() {
    // Here you would implement the logic to redirect the user to the checkout page
    // Redirect user to the checkout page using JavaScript
    echo "<script>window.location.href = 'checkout.php';</script>";
}
// Function to extract tracking number from message
function extractTrackingNumber($message) {
    // Implement logic to extract tracking number from the message
    // For demonstration purposes, let's assume the tracking number is everything after "track order"
    $startIndex = strpos($message, "track order") + strlen("track order");
    return trim(substr($message, $startIndex));
}

// Function to retrieve order information from the database
function getOrderInfo($trackingNumber, $conn) {
    // Implement logic to retrieve order information from the database based on the tracking number
    // For demonstration purposes, let's assume there is a table named 'orders' with columns 'tracking_number' and 'status'
    $trackingNumber = mysqli_real_escape_string($conn, $trackingNumber);
    $query = "SELECT * FROM orders WHERE order_id = '$trackingNumber'";
    $result = $conn->query($query);
    
    if ($result === false) {
        // Print the error for debugging
        echo "Error: " . $conn->error;
        return false;
    }
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

// Function to save feedback in the database
function saveFeedback($customerName, $customerEmail, $feedback, $conn) {
    // Implement the logic to save feedback in the database
    // You can display a form here to collect additional information such as customer name and email
    // For simplicity, I'll assume we only save the feedback
    $query = "INSERT INTO feedback (customer_name, customer_email, feedback) VALUES ('$customerName', '$customerEmail', '$feedback')";
    $conn->query($query);
}
?>
