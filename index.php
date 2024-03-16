<!DOCTYPE html>
<html>
  <head>
    <title>My Page</title>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <header>
      <img src="logo.png" alt="Logo">
      <nav>
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#">About</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </nav>
      <input type="text" id="search-box" placeholder="Search...">
    </header>
    <main>
      <section id="for-you">
        <h2>For you</h2>
        <ul id="trending-posts"></ul>
      </section>
      <section id="following">
        <h2>Following</h2>
        <ul id="following-posts"></ul>
      </section>
    </main>
    <footer>
      <p>© 2023 My Page</p>
      <ul>
        <li><a href="#">Terms of Service</a></li>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Contact Us</a></li>
      </ul>
    </footer>
    <script src="script.js"></script>
  </body>
</html>