<?php
session_start();

if (!isset($_SESSION['history'])) {
     $_SESSION['history'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     if (isset($_POST['delete'])) {
          $deleteIndex = (int)$_POST['delete'];
          if (isset($_SESSION['history'][$deleteIndex])) {
               unset($_SESSION['history'][$deleteIndex]);
               $_SESSION['history'] = array_values($_SESSION['history']);
          }
     } else {
          $a = isset($_POST['a']) ? (float)$_POST['a'] : 0;
          $b = isset($_POST['b']) ? (float)$_POST['b'] : 0;
          $c = isset($_POST['c']) ? (float)$_POST['c'] : 0;

          $result = '';

          if ($a == 0) {
               $result = ($b != 0) ? 'Phương trình có một nghiệm: x = ' . (-$c / $b) : 'Phương trình vô nghiệm';
          } else {
               $delta = $b * $b - 4 * $a * $c;
               if ($delta < 0) {
                    $result = 'Phương trình vô nghiệm';
               } elseif ($delta == 0) {
                    $result = 'Phương trình có nghiệm kép: x = ' . (-$b / (2 * $a));
               } else {
                    $x1 = (-$b + sqrt($delta)) / (2 * $a);
                    $x2 = (-$b - sqrt($delta)) / (2 * $a);
                    $result = "Phương trình có hai nghiệm phân biệt: x1 = $x1, x2 = $x2";
               }
          }

          // Lưu lịch sử
          $equation = "{$a}x² + {$b}x + {$c} = 0";
          $_SESSION['history'][] = [
               'equation' => $equation,
               'result' => $result
          ];
     }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Giải Phương Trình Bậc 2</title>
     <style>
          body {
               font-family: Arial, sans-serif;
               margin: 0;
               padding: 0;
               display: flex;
               justify-content: center;
               align-items: center;
               min-height: 100vh;
               background-color: #f9f9f9;
          }
          .container {
               text-align: center;
               background: #fff;
               padding: 20px;
               border-radius: 8px;
               box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
               width: 100%;
               max-width: 400px;
          }
          .container h1 {
               margin-bottom: 20px;
               font-size: 24px;
               color: #333;
          }
          .container form {
               display: flex;
               flex-direction: column;
               align-items: center;
          }
          .input-group {
               display: flex;
               justify-content: space-between;
               width: 100%;
          }
          .container input {
               width: 30%;
               padding: 10px;
               margin: 5px;
               font-size: 16px;
               border: 1px solid #ccc;
               border-radius: 4px;
               outline: none;
          }
          .container button {
               margin-top: 20px;
               padding: 10px 20px;
               background-color: #ff7f0e;
               color: #fff;
               font-size: 16px;
               border: none;
               border-radius: 25px;
               cursor: pointer;
          }
          .container button:hover {
               background-color: #e66a00;
          }
          .result {
               margin-top: 20px;
               font-size: 18px;
               color: #333;
          }
          .history {
               margin-top: 30px;
               text-align: left;
               max-height: 250px;
               overflow-y: auto;
               border: 1px solid #ccc;
               border-radius: 4px;
               padding: 10px;
               background: #f9f9f9;
          }
          .history h2 {
               font-size: 20px;
               color: #333;
          }
          .history ul {
               list-style: none;
               padding: 0;
               margin: 0;
          }
          .history li {
               margin-bottom: 10px;
               padding: 10px;
               background: #f1f1f1;
               border-radius: 4px;
               display: flex;
               justify-content: space-between;
               align-items: center;
          }
          .history form {
               margin: 0;
          }
     </style>
</head>
<body>
<div class="container">
     <h1>Giải Phương Trình Bậc 2</h1>
     <form method="POST">
          <div class="input-group">
               <input type="text" name="a" placeholder="Nhập a" required>
               <input type="text" name="b" placeholder="Nhập b" required>
               <input type="text" name="c" placeholder="Nhập c" required>
          </div>
          <button type="submit">Giải Phương Trình</button>
     </form>
     <?php if (isset($result)) : ?>
          <div class="result">
               <strong>Kết quả:</strong> <?php echo htmlspecialchars($result); ?>
          </div>
     <?php endif; ?>

     <?php if (!empty($_SESSION['history'])) : ?>
          <div class="history">
               <h2>Lịch sử</h2>
               <ul>
                    <?php foreach ($_SESSION['history'] as $index => $entry) : ?>
                         <li>
                         <div>
                              <strong>Phương trình:</strong> <?php echo htmlspecialchars($entry['equation']); ?><br>
                              <strong>Kết quả:</strong> <?php echo htmlspecialchars($entry['result']); ?>
                         </div>
                         <form method="POST">
                              <button type="submit" name="delete" value="<?php echo $index; ?>">Xóa</button>
                         </form>
                         </li>
                    <?php endforeach; ?>
               </ul>
          </div>
     <?php endif; ?>
</div>
</body>
</html>
