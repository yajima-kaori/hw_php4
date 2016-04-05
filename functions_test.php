<?php

//////////////////////////////////////////////////////////////////////
// このファイルの内容に関しては、現段階で理解する必要はありません。
//////////////////////////////////////////////////////////////////////

require('functions.php');

$results = array();

// is_future_date
$today = date('Y-m-d');
$yesterday = date('Y-m-d', time() - 86400);
$tomorrow = date('Y-m-d', time() + 86400);
$test_data = array(
  array('args' => '2016-01-20', 'expected' => false),
  array('args' => '2026-12-10', 'expected' => true),
  array('args' => $yesterday, 'expected' => false),
  array('args' => $today, 'expected' => false),
  array('args' => $tomorrow, 'expected' => true),
);
foreach ($test_data as $data) {
  $results['is_future_date'][$data['args']] = (is_future_date($data['args']) === $data['expected']);
}

// html_espape
$test_data = array(
  array('args' => 'plain text', 'expected' => 'plain text'),
  array('args' => '<h1>header 1</h1>', 'expected' => '&lt;h1&gt;header 1&lt;/h1&gt;'),
  array('args' => '<span style="color:red">sample text</span>', 'expected' => '&lt;span style=&quot;color:red&quot;&gt;sample text&lt;/span&gt;'),
  array('args' => "<span style='color:red'>sample text</span>", 'expected' => "&lt;span style=&apos;color:red&apos;&gt;sample text&lt;/span&gt;"),
  array('args' => "<span style='color:red'>sample & text</span>", 'expected' => "&lt;span style=&apos;color:red&apos;&gt;sample &amp; text&lt;/span&gt;"),
);
foreach ($test_data as $data) {
  $results['html_espape'][$data['args']] = (html_escape($data['args']) === $data['expected']);
}

// get_tax_price
$test_data = array(
  array('args' => 100, 'expected' => 108),
  array('args' => 101, 'expected' => 109),
  array('args' => 102, 'expected' => 110),
  array('args' => 103, 'expected' => 111),
  array('args' => 104, 'expected' => 112),
  array('args' => 105, 'expected' => 113),
  array('args' => 106, 'expected' => 114),
  array('args' => 107, 'expected' => 115),
  array('args' => 108, 'expected' => 116),
  array('args' => 109, 'expected' => 117),
  array('args' => 110, 'expected' => 118),
  array('args' => 200, 'expected' => 216),
  array('args' => 300, 'expected' => 324),
);
foreach ($test_data as $data) {
  $results['get_tax_price'][$data['args']] = (get_tax_price($data['args']) === $data['expected']);
}

// write_log
write_log('error message 1', 'error');
$lines = array_reverse((array)@file('error.log'));
$results['write_log']['error message 1, error'] = preg_match("/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}: error message 1\n/", $lines[0]);

write_log('error message 2', 'error');
$lines = array_reverse((array)@file('error.log'));
$results['write_log']['error message 2, error'] = (
  preg_match("/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}: error message 2\n/", $lines[0]) &&
  preg_match("/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}: error message 1\n/", $lines[1]));

write_log('debug message 1', 'debug');
$lines = array_reverse((array)@file('debug.log'));
$results['write_log']['debug message 1, debug'] = preg_match("/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}: debug message 1\n/", $lines[0]);


// array_column
$users = array(
  array('name' => 'user A', 'age'  => 15, 'email' => 'aa@example.com'),
  array('name' => 'user B', 'age'  => 20, 'email' => 'bb@example.com'),
  array('name' => 'user C', 'age'  => 25, 'email' => 'cc@example.com'),
  array('name' => 'user D', 'age'  => 30, 'email' => 'dd@example.com'),
  array('name' => 'user E', 'age'  => 35, 'email' => 'ee@example.com'),
  array('name' => 'user F', 'age'  => 40, 'email' => 'ff@example.com'),
);

$results['array_column']['$users, name'] = array_column($users, 'name') === array(
  'user A', 'user B', 'user C', 'user D', 'user E', 'user F');
$results['array_column']['$users, email'] = array_column($users, 'email') === array(
  'aa@example.com', 'bb@example.com', 'cc@example.com',
  'dd@example.com', 'ee@example.com', 'ff@example.com'
);

////////////////////////////////////////////////////////////////////////
// 応用要件の関数をテストする場合は、以下をコメントアウトしてください。
////////////////////////////////////////////////////////////////////////

// $results['array_sum_recursive']['array(1, 2, 3, 0, 10, -5)'] = array_sum_recursive(array(1, 2, 3, 0, 10, -5)) === 11;
// $results['array_sum_recursive']['array(1, 2, array(3, 4), 5, array(-10))'] = array_sum_recursive(array(1, 2, array(3, 4), 5, array(-10))) === 5;
// $results['array_sum_recursive']['array(1, array(2, array(3, array(4, 5)), 6, array(-10, array(2, 3))))'] = array_sum_recursive(array(1, array(2, array(3, array(4, 5)), 6, array(-10, array(2, 3))))) === 16;

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title></title>
</head>
<body>
<?php foreach ($results as $func => $result): ?>
<h3><?php  echo $func; ?>のテスト</h3>
<table border=1 cellspacing=0 cellpadding=5>
  <?php foreach ($result as $args => $ok): ?>
    <tr>
      <td> <?php echo $func ?>('<?php echo htmlspecialchars($args, ENT_QUOTES) ?>')</td>
      <td>
        <?php if ($ok): ?>
          <span style="font-weight:bold; color:green">OK</span>
        <?php else: ?>
          <span style="font-weight:bold; color:red;">NG</span>
        <?php endif ?>
      </td>
    </tr>
  <?php endforeach ?>
</table>
<?php endforeach ?>
</body>
</html>


