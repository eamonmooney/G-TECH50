<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Headphones</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">


    <style>
        .navbar {
          display: flex;
          justify-content: space-between; 
          align-items: center;
          background-color: #000000;
          width: 100%;
          position: fixed;
          top: 0; 
          left: 0;
          z-index: 1000;
          padding: 0 20px;
          
        }
    
        .logo {
          flex: 1;
           
        }
    
        .nav-links {
          display: flex;
          gap: 10px;
          
        }
    
        .navbar a {  
          color: white;
          text-align: center;
          padding: 14px 16px;
          text-decoration: none;
        }
    
        .navbar a:hover {
          background-color: #201c1c;
        }
    
        .material-icons {
          color: white; 
          font-size: 24px;
        }
    
        .icon-container {
          flex: 1; 
          display: flex;
          justify-content: flex-end;
          margin-right: 20px; 
        }
      </style>







</head>

<body>
  
    <div class="navbar">
      <div class="logo">
        <a href="Home">Logo</a>
      </div>
      <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="Categories">Categories</a>
        <a href="deals.php">Deals</a>
        <a href="support.php">Support</a>
      </div>
      <div class="icon-container">
          <a href="#"><i class="material-icons">search</i></a>
          <a href="profile.php"><i class="material-icons">account_circle</i></a>
          <a href="basket.php"><i class="material-icons">shopping_basket</i></a>
      </div>
    </div>
  
  </body>


























    </html>