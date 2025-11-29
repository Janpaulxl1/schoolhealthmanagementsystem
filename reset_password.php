<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reset Password - School Clinic</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poltawski+Nowy:wght@400;700&display=swap" rel="stylesheet">

  <style>
    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      background: linear-gradient(to bottom, #E4A73E, #DD442D,#BB1D1D);
      color: white;
      margin: 0;
      font-family: 'Poltawski Nowy', serif;
    }

    .container {
      flex-grow: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 2rem;
      max-width: 1100px;
      margin: 0 auto;
    }

    .main-content {
      display: flex;
      width: 100%;
      align-items: center;
      justify-content: center;
      flex-wrap: wrap;
      gap: 1.5rem;
    }

    .logo-area {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 1rem;
      padding: 1rem;
      text-align: center;
    }

    .logo-area img {
      width: 260px;
      height: auto;
    }

    .logo-text {
      font-weight: bold;
      font-size: 2.8rem;
      line-height: 1.4;
    }

    .form-area {
      padding: 1rem;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .reset-card {
      background-color: rgba(255, 255, 255, 0.15);
      border-radius: 1rem;
      padding: 3rem 2.5rem;
      box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
      backdrop-filter: blur(10px);
      width: 100%;
      max-width: 600px;
      min-height: 400px;
    }

    .reset-card h3 {
      font-size: 1.8rem;
      font-weight: bold;
      margin-bottom: 1.5rem;
      text-align: center;
    }

    .form-group {
      margin-bottom: 1.2rem;
      width: 100%;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: bold;
      color: #f0f0f0;
      font-size: 1rem;
    }

    .form-group input {
      width: 100%;
      padding: 0.9rem;
      border: none;
      border-radius: 0.5rem;
      color: #333;
      font-size: 1rem;
    }

    .reset-button {
      background-color: #f1d669;
      color: #333;
      width: 100%;
      padding: 1rem;
      border: none;
      border-radius: 0.5rem;
      font-weight: bold;
      cursor: pointer;
      font-size: 1.1rem;
    }

    .reset-button:hover {
      background-color: #fde047;
    }

    .back-link {
      margin-top: 1rem;
      font-size: 1rem;
      color: #f0f0f0;
      text-decoration: underline;
      cursor: pointer;
    }

    .back-link:hover {
      color: #fde047;
    }

    @media (max-width: 768px) {
      .main-content {
        flex-direction: column;
        align-items: center;
      }

      .logo-area {
        flex-direction: column;
        text-align: center;
      }

      .form-area {
        width: 100%;
      }

      .reset-card {
        max-width: 90%;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="main-content">
      <div class="logo-area">
        <img src="images/logo.png" alt="School Clinic Logo">
        <div class="text-white logo-text">
          <p>School Health</p>
          <p>Clinic</p>
          <p>Management</p>
          <p>System</p>
        </div>
      </div>

      <div class="form-area">
        <div class="reset-card">
          <h3>RESET YOUR PASSWORD</h3>
          <form action="reset_password_process.php" method="POST">
            <div class="form-group">
              <label for="username">Student ID</label>
              <input type="text" id="username" name="username" placeholder="Enter your student ID" required />
            </div>
            <div class="form-group">
              <label for="old_password">Old Password</label>
              <input type="password" id="old_password" name="old_password" placeholder="Enter your old password" required />
            </div>
            <div class="form-group">
              <label for="new_password">New Password</label>
              <input type="password" id="new_password" name="new_password" placeholder="Enter your new password" required />
            </div>
            <div class="form-group">
              <label for="confirm_password">Confirm New Password</label>
              <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your new password" required />
            </div>
            <button type="submit" class="reset-button">Reset Password</button>
          </form>
          <a href="login.html" class="back-link">Back to Login</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
