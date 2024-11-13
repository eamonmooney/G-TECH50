<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>G-Tech 50</title>
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
      align-items: center;
      gap: 5px; 
      margin-right: 20px;
    }

    
    .search-bar {
      display: flex;
      align-items: center;
      background-color: #201c1c;
      border-radius: 5px;
      padding: 5px 10px;
    }

    .search-bar input[type="text"] {
      background-color: transparent;
      border: none;
      color: white;
      outline: none;
      padding: 5px;
      font-size: 16px;
      width: 120px; 
    }

    .search-bar .material-icons {
      color: white;
      cursor: pointer;
      font-size: 24px;
    }

    .search-bar input[type="text"]::placeholder {
      color: #aaa; 
    }

    
    .dropdown {
      position: relative;
      margin-top: 15px; 
    }

    .dropdown-content {
      display: none;
      position: absolute;
      top: 115%;
      left: 50%;
      transform: translateX(-50%);
      background-color: #201c1c;
      padding: 10px;
      width: 600px;
      border-radius: 5px;
      box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    }

    .dropdown:hover .dropdown-content {
      display: flex;
      justify-content: center;
      gap: 5px;
    }

    .dropdown-content a {
      color: white;
      width: 100px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      text-align: center;
      background-color: #201c1c;
      border-radius: 5px;
    }

    .dropdown-content a:hover {
      background-color: #333333;
    }
  </style>

</head>

<body>
  
  <div class="navbar">
    <div class="logo">
      <a href="Home">Logo</a>
    </div>

    <div class="nav-links">
      <a href="Home.html">Home</a>

      <div class="dropdown">
        <a href="Categories">Categories</a>

        <div class="dropdown-content">
          <a href="Mice.html">Mice</a>
          <a href="Monitors.html">Monitors</a>
          <a href="Headphones.html">Headphones</a>
          <a href="Keyboards.html">Keyboards</a>
          <a href="Mousepads.html">Mousepads</a>
        </div>

      </div>
      <a href="Deals.html">Deals</a>
      <a href="Support.html">Support</a>
    </div>

    <div class="icon-container">
        
        <div class="search-bar">
          <input type="text" placeholder="Search...">
          <i class="material-icons">search</i>
        </div>

        <a href="profile.html"><i class="material-icons">account_circle</i></a>
        <a href="basket.html"><i class="material-icons">shopping_basket</i></a>
    </div>
  </div>

</body>
</html>