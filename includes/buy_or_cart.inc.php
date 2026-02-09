<?php

function buyOrCart($conn, $quantityInStock, $cartQty, $itemID, $price, $cart)
{
  // Check stock
  if ($quantityInStock < $cartQty) {
    echo "<script>alert('Not enough stock available');</script>";
    return;
  }

  // Check login
  if (!isset($_SESSION["Member"])) {
    echo "<script>alert('Login to add to cart.');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit;
  }

  $db = $conn->conn();
  $orderID = $cart->getOrderID();

  /* --------------------------------------------------
       1. Check if item already exists in cart
    -------------------------------------------------- */
  $sql = "
        SELECT OrderItemID, Quantity
        FROM OrderItems
        WHERE OrderID = ?
          AND ItemID = ?
    ";

  $stmt = $db->prepare($sql);
  $stmt->bind_param("ii", $orderID, $itemID);
  $stmt->execute();
  $result = $stmt->get_result();

  /* --------------------------------------------------
       2A. Item NOT in cart → INSERT
    -------------------------------------------------- */
  if ($result->num_rows === 0) {

    $sql = "
            INSERT INTO OrderItems
              (OrderID, ItemID, Price, Quantity, AddedDatetime)
            VALUES (?, ?, ?, ?, NOW())
        ";

    $stmt = $db->prepare($sql);
    $stmt->bind_param(
      "iidi",
      $orderID,
      $itemID,
      $price,
      $cartQty
    );

    $stmt->execute();
  }
  /* --------------------------------------------------
       2B. Item EXISTS → UPDATE quantity
    -------------------------------------------------- */ else {
    $row = $result->fetch_assoc();
    $newQty = $row["Quantity"] + $cartQty;

    $sql = "
            UPDATE OrderItems
            SET Quantity = ?
            WHERE OrderID = ?
              AND ItemID = ?
        ";

    $stmt = $db->prepare($sql);
    $stmt->bind_param(
      "iii",
      $newQty,
      $orderID,
      $itemID
    );

    $stmt->execute();
  }
}
