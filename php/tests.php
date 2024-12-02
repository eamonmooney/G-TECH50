<!-- This page is for testing out entrying aspects into the database -->
 <!-- The idea is to use these as guidelines when creating / removing from the database and to test the database works as it should -->
  <!-- Worked on by Safa Riasat  -->


<?php
	// Try catch statement for error handling
	try {
		// Ensure database is connected
		require_once('database.php');


		//FIRST TEST: Create Customer Role in Role table

		//Checking if customer role exists
		//Create the customer variable
		$customer = "Customer";

		//Creating exists SQL statement
		$sql1 = 
		"SELECT COUNT(*) FROM Role WHERE role = :role";

		//Preparing our SQL statement by sending to database
		//Using prepare() to prevent SQL injections - seperates user input from query
		//Prepare() also increases efficiency - all queries are cached for reusability
		$stmt1 = $db->prepare($sql1);
		//Execute statment1 by replacing the :Customer placeholder with the actual customer value -> safer as prevents SQL injections
		$stmt1->execute([':role' => $customer]);
		//Store the results into a count variable
		$count1 = $stmt1->fetchColumn();

		//If there's instances of Customer existing
		if ($count1 > 0) {
			//Tell the user this role already exists
			echo "The Customer role already exists.<br>";
		//Otherwise
		} else {
			//Creating Customer Role SQL statement
			$customerRole = 
			"INSERT INTO Role (role) 
			VALUES (:role)";
			//Prepare the SQL statement to insert into the table
			$insertStmt1 = $db->prepare($customerRole);
			//Executing this SQL statement by passing the variable (seperating for safety)
			$insertStmt1->execute([':role' => $customer]);
			//Testing output
			echo "Customer role successfully added!<br>";
		}
		

		//SECOND TEST - USER

		//Checking if email is in use
		//Create the email
		$email1 = "janeDoe@gmail.com";

		//Create the name
		$name1 = "Jane Doe";

		//Create the password
		$password1 = "password123";

		//Create the roleID for customers - ASSUMING CUSTOMER ROLE HAS BEEN CREATED IN THE FIRST POSITION
		$roleID_C = 1;

		//Creating exists SQL statement
		$sql2 = 
		"SELECT COUNT(*) FROM Users WHERE email = :email";

		//Preparing our SQL statement by sending to database
		//Using prepare() to prevent SQL injections - seperates user input from query
		//Prepare() also increases efficiency - all queries are cached for reusability
		$stmt2 = $db->prepare($sql2);
		//Execute statment by replacing the :email placeholder with the actual email value -> safer as prevents SQL injections
		$stmt2->execute([':email' => $email1]);
		//Store the results into a count variable
		$count2 = $stmt2->fetchColumn();

		//If there's instances of email in use
		if ($count2 > 0) {
			//Tell the user this email is in use
			echo "This email is in use<br>";
		//Otherwise
		} else {
			//Creating first user for test
			$user1 = 
			"INSERT INTO Users (name, email, password, roleID) 
			VALUES (:name, :email, :password, :roleID_C)";
			//Prepare the SQL statement to insert into the table
			$insertStmt2 = $db->prepare($user1);
			//Executing this SQL statement by passing the variable (seperating for safety)
			$insertStmt2->execute([
				':name' => $name1, 
				':email' => $email1, 
				':password' => $password1, 
				':roleID_C' => $roleID_C]);
			//Testing output
			echo "User1 successfully added!<br>";
		}
		
		//THIRD TEST: Create Product Order in OrderTypes table

		//Checking if product order exists
		//Create the product variable
		$product = "Product";

		//Creating exists SQL statement
		$sql3 = 
		"SELECT COUNT(*) FROM OrderTypes WHERE OrderType = :OrderType";

		//Preparing our SQL statement by sending to database
		//Using prepare() to prevent SQL injections - seperates user input from query
		//Prepare() also increases efficiency - all queries are cached for reusability
		$stmt3 = $db->prepare($sql3);
		//Execute statment by replacing the :ordertype placeholder with the actual ordertype value -> safer as prevents SQL injections
		$stmt3->execute([':OrderType' => $product]);
		//Store the results into a count variable
		$count3 = $stmt3->fetchColumn();

		//If there's instances of product existing
		if ($count3 > 0) {
			//Tell the user this order type already exists
			echo "The Product role already exists.<br>";
		//Otherwise
		} else {
			//Creating Product Order Type SQL statement
			$ProductOrderType = 
			"INSERT INTO OrderTypes (OrderType) 
			VALUES (:OrderType)";
			//Prepare the SQL statement to insert into the table
			$insertStmt3 = $db->prepare($ProductOrderType);
			//Executing this SQL statement by passing the variable (seperating for safety)
			$insertStmt3->execute([':OrderType' => $product]);
			//Testing output
			echo "Product Order Type successfully added!<br>";
		}


		//FOURTH TEST: Create Keyboards in Product Types table

		//Checking if keyboard type exists
		//Create the keyboard variable
		$keyboard = "Keyboard";

		//Creating exists SQL statement
		$sql4 = 
		"SELECT COUNT(*) FROM ProductType WHERE ProductType = :ProductType";

		//Preparing our SQL statement by sending to database
		//Using prepare() to prevent SQL injections - seperates user input from query
		//Prepare() also increases efficiency - all queries are cached for reusability
		$stmt4 = $db->prepare($sql4);
		//Execute statment by replacing the :ProductType placeholder with the actual ProductType value -> safer as prevents SQL injections
		$stmt4->execute([':ProductType' => $keyboard]);
		//Store the results into a count variable
		$count4 = $stmt4->fetchColumn();

		//If there's instances of keyboard existing
		if ($count4 > 0) {
			//Tell the user this product type already exists
			echo "The Keyboard Product Type already exists.<br>";
		//Otherwise
		} else {
			//Creating Product Type SQL statement
			$ProductType = 
			"INSERT INTO ProductType (ProductType) 
			VALUES (:ProductType)";
			//Prepare the SQL statement to insert into the table
			$insertStmt4 = $db->prepare($ProductType);
			//Executing this SQL statement by passing the variable (seperating for safety)
			$insertStmt4->execute([':ProductType' => $keyboard]);
			//Testing output
			echo "Product Type successfully added!<br>";
		}

		//FIFTH TEST: Create CandyKeys Keyboard in Product table

		//Checking if this product already exists
		//Create the CandyKeys variable
		$CandyKeys = "CandyKeys";

		//Creating the Product TypeID by looking into the ProductType table and finding the Keyboard's ID
		// The product type we are searching for
		$productType1 = "Keyboard";
		// SQL Query for this purpose
		$productsql1 = "SELECT ProductTypeID FROM ProductType WHERE ProductType = :ProductType";
		// Prepare the SQL statement
		$productstmt1 = $db->prepare($productsql1);
		// Execute the statement
		$productstmt1->execute([':ProductType' => $productType1]);
		// Setting the ProductTypeID
		$ProductTypeID1 = $productstmt1->fetchColumn();
		//TESTING WHAT WE'VE FOUND FROM ANOTHER TABLE
		// If we found a valid ProductTypeID
		if ($ProductTypeID1 !== false) {
			//Echo it out
			echo "The ProductTypeID for '$productType1' is: $ProductTypeID1<br>";
		} else {
			//Otherwise echo that we couldnt find it for that product type
			echo "ProductType '$productType1' not found. <br>";
		}

		//Creating the returnable variable
		$Returnable1 = TRUE;
		//Creating the Stock level amount
		$Stock1 = 100;
		//Creating the Price amount
		$Price1 = 10.99;


		//Creating exists SQL statement
		$sql5 = 
		"SELECT COUNT(*) FROM Products WHERE ProductName = :ProductName";

		//Preparing our SQL statement by sending to database
		//Using prepare() to prevent SQL injections - seperates user input from query
		//Prepare() also increases efficiency - all queries are cached for reusability
		$stmt5 = $db->prepare($sql5);
		//Execute statment by replacing the :Product placeholder with the actual Product value -> safer as prevents SQL injections
		$stmt5->execute([':ProductName' => $CandyKeys]);
		//Store the results into a count variable
		$count5 = $stmt5->fetchColumn();

		//If there's instances of this product existing
		if ($count5 > 0) {
			//Tell the user this product already exists
			echo "The Product already exists.<br>";
		//Otherwise
		} else {
			//Creating Product SQL statement
			$ProductSQL = 
			"INSERT INTO Products (ProductTypeID, ProductName, Returnable, Stock, Price ) 
			VALUES (:ProductTypeID, :ProductName, :Returnable, :Stock, :Price)";
			//Prepare the SQL statement to insert into the table
			$insertStmt5 = $db->prepare($ProductSQL);
			//Executing this SQL statement by passing the variable (seperating for safety)
			$insertStmt5->execute([
				':ProductTypeID' => $ProductTypeID1,
				':ProductName' => $CandyKeys,
				':Returnable' => $Returnable1,
				':Stock' => $Stock1,
				':Price' => $Price1,
			]);
			//Testing output
			echo "CandyKeys successfully added!<br>";
		}

		//SIXTH TEST: Create an Order for Candy Keys in Orders Table

		//We would get the user ID from the session variables
		//For this test, I will set it to the first user
		$userID1 = 1;

		//Setting Order types to product
		$OrderTypeID1 = 1;

		//Setting the date of the order
		$OrderDate1 = "08/12/2024";

		//Setting the cost of the order
		$OrderCost1 = 150.00;

		//Creating Order SQL statement
		$OrderSQL = 
		"INSERT INTO Orders (UserID, OrderTypeID, OrderDate, OrderCost ) 
		VALUES (:UserID, :OrderTypeID, :OrderDate, :OrderCost)";
		//Prepare the SQL statement to insert into the table
		$insertStmt6 = $db->prepare($OrderSQL);
		//Executing this SQL statement by passing the variable (seperating for safety)
		$insertStmt6->execute([
			':UserID' => $userID1,
			':OrderTypeID' => $OrderTypeID1,
			':OrderDate' => $OrderDate1,
			':OrderCost' => $OrderCost1,
		]);
		//Testing output
		echo "Order successfully created!<br>";
	

		//SEVENTH TEST: Create an OrderItem Candy Keys in OrderItem Table

		//The OrderID that this product is being ordered from
		$OrderID1 = 1;

		//The ProductID of the product being ordered
		$ProductID1 = 1;

		//Setting the Quantity to be ordered
		$Quantity1 = 100;

		//Creating exists SQL statement
		$sql7 = 
		"SELECT COUNT(*) FROM OrderItem WHERE ProductID = :ProductID AND OrderID = :OrderID";

		//Preparing our SQL statement by sending to database
		//Using prepare() to prevent SQL injections - seperates user input from query
		//Prepare() also increases efficiency - all queries are cached for reusability
		$stmt7 = $db->prepare($sql7);
		//Execute statment by replacing the placeholders with the actual values -> safer as prevents SQL injections
		$stmt7->execute([
			':ProductID' => $ProductID1,
			':OrderID' => $OrderID1
		]);
		//Store the results into a count variable
		$count7 = $stmt7->fetchColumn();

		//If there's instances of this order item existing for that order add the input quantity to the quantity in the database for that orderitem
		if ($count7 > 0) {
			
			// Grab the current temp quantity SQL
			$TempQuantitySQL = 
			"SELECT Quantity FROM OrderItem WHERE ProductID = :ProductID AND OrderID = :OrderID";
			//Preparing our SQL statement by sending to database
			$tempQuantityStmt = $db->prepare($TempQuantitySQL);
			//Execute statment by replacing the placeholders with the actual values -> safer as prevents SQL injections
			$tempQuantityStmt->execute([
				':ProductID' => $ProductID1,
				':OrderID' => $OrderID1
			]);

			//Store the results into a temp quantity variable
			$tempQuantity = $tempQuantityStmt->fetchColumn();

			// Add the new quantity to the temp quantity
			$newQuantity = $tempQuantity + $Quantity1;

			// SQL statement to update the quantity in the database
			$QuantityUpdateSQL = 
			"UPDATE OrderItem SET Quantity = :Quantity WHERE ProductID = :ProductID AND OrderID = :OrderID";
			//Preparing our SQL statement by sending to database
			$UpdateQuantityStmt = $db->prepare($QuantityUpdateSQL);
			//Execute statment by replacing the placeholders with the actual values -> safer as prevents SQL injections
			$UpdateQuantityStmt->execute([
				':Quantity' => $newQuantity,
				':ProductID' => $ProductID1,
				':OrderID' => $OrderID1
			]);

			//Testing output
			echo "OrderItem's quantity successfully updated!<br>";

		//Otherwise create a new order item
		} else {

			//Creating OrderItem SQL statement
			$OrderItemSQL = 
			"INSERT INTO OrderItem (OrderID, ProductID, Quantity ) 
			VALUES (:OrderID, :ProductID, :Quantity)";
			//Prepare the SQL statement to insert into the table
			$insertStmt7 = $db->prepare($OrderItemSQL);
			//Executing this SQL statement by passing the variable (seperating for safety)
			$insertStmt7->execute([
				':OrderID' => $OrderID1,
				':ProductID' => $ProductID1,
				':Quantity' => $Quantity1
			]);
			//Testing output
			echo "OrderItem successfully created!<br>";
		}

		//EIGHT TEST: Create a ticket type in TicketType table

		//Checking if ticket type exists
		//Create the ticket type variable
		$TicketType1 = "Login";

		//Creating exists SQL statement
		$sql8 = 
		"SELECT COUNT(*) FROM TicketTypes WHERE TicketType = :TicketType";

		//Preparing our SQL statement by sending to database
		$stmt8 = $db->prepare($sql8);
		//Execute statment by replacing the placeholder with the actual value -> safer as prevents SQL injections
		$stmt8->execute([':TicketType' => $TicketType1]);
		//Store the results into a count variable
		$count8 = $stmt8->fetchColumn();

		//If there's instances of product existing
		if ($count8 > 0) {
			//Tell the user this order type already exists
			echo "The Ticket Type already exists.<br>";
		//Otherwise
		} else {
			//Creating TicketType SQL statement
			$TicketTypeSQL = 
			"INSERT INTO TicketTypes (TicketType) 
			VALUES (:TicketType)";
			//Prepare the SQL statement to insert into the table
			$insertStmt8 = $db->prepare($TicketTypeSQL);
			//Executing this SQL statement by passing the variable (seperating for safety)
			$insertStmt8->execute([':TicketType' => $TicketType1]);
			//Testing output
			echo "TicketType successfully added!<br>";
		}


		//NINTH TEST: Create a Support Ticket

		//Create the TicketTypeID variable
		$TicketTypeID1 = 1;

		//Create the UserID
		$UserID1 = 1;

		//Create the Ticket Date based on the current date
		//!! This does not work -> fix
		$TicketDate1 = date('d-m-Y');

		//Create the ticket content
		$TicketContent1 = "I want to open this ticket please";

		//Creating SupportTickets SQL statement
		$SupportTicketsSQL = 
		"INSERT INTO SupportTickets (TicketTypeID, UserID, TicketDate, TicketContent ) 
		VALUES (:TicketTypeID, :UserID, :TicketDate, :TicketContent)";
		//Prepare the SQL statement to insert into the table
		$insertStmt9 = $db->prepare($SupportTicketsSQL);
		//Executing this SQL statement by passing the variable (seperating for safety)
		$insertStmt9->execute([
			':TicketTypeID' => $TicketTypeID1,
			':UserID' => $UserID1,
			':TicketDate' => $TicketDate1,
			':TicketContent' => $TicketContent1,
		]);
		//Testing output
		echo "Ticket successfully created!<br>";

		//TENTH TEST: Create a Returns request in the returns table

		//Variable for the UserID
		$UserID1 = 1;

		//Variable for the OrderID
		$OrderID1 = 1;

		//Variable for the ProductID
		$ProductID1 = 1;

		//Variable for the Return Value ( price of the product being returned)
		//!!! If we have return value based on one product then it;s not 1NF
		//BUT IF WE HAVE MULTIPLE PRODUCTS IN ONE RETURN ID THERES MULTIPLE PRODUCTIDS
		//MAY HAVE TO DO SMTH LIKE THE ORDER SYSTEM

		//!!ALSO CONSIDER CHECKING IF ITS BEEN RETURNED ALREADY
		//BUT THESE CHECKS MAY NOT BE NEEDED DEPENDING ON FRONT IMPLEMENTATION - I.E. IF IT'S DROP DOWN MENUS, BUT WILL BE NEEDED IF IT'S TEXT 

		//Variable for the return date set to the current date
		$ReturnDate1 = date('d-m-Y');

		//Checking if this order exists in the Orders table SQL to make sure this order exists
		$sql10 = 
		"SELECT COUNT(*) FROM OrderItem WHERE ProductID = :ProductID AND OrderID = :OrderID";
		//Preparing our SQL statement by sending to database
		$stmt10 = $db->prepare($sql10);
		//Execute statment by replacing the :Product placeholder with the actual Product value -> safer as prevents SQL injections
		$stmt10->execute([':ProductID' => $ProductID1, ':OrderID' => $OrderID1]);
		//Store the results into a count variable
		$count10 = $stmt10->fetchColumn();

		//If there's no instances of this product existing
		if ($count10 < 0) {
			//Tell the user they haven't ordered this before
			echo "This order does not exist.<br>";
		//Otherwise
		} else {
			//Creating Returns SQL statement
			$ReturnsSQL = 
			"INSERT INTO Returns (UserID, OrderID, ProductID, ReturnDate) 
			VALUES (:UserID, :OrderID, :ProductID, :ReturnDate)";
			//Prepare the SQL statement to insert into the table
			$insertStmt10 = $db->prepare($ReturnsSQL);
			//Executing this SQL statement by passing the variable (seperating for safety)
			$insertStmt10->execute([
				':UserID' => $UserID1,
				':OrderID' => $OrderID1,
				':ProductID' => $ProductID1,
				':ReturnDate' => $ReturnDate1,
			]);
			//Testing output
			echo "Product Successfully Returned!<br>";
		}

		echo "-- -- -- <br> Entries added successfully!<br>";


	//If there's been an error
	} catch (PDOException $e) {
		//Display the message
		echo "Error: " . $e->getMessage();
	}

	//Closing connection
	$conn = null;
?>