<?php echo $this->menuView(null, '/register'); ?>

<h1>Получите свой Хаброметр</h1>
<?php if ($this->ok) { ?>
<p>Регистрация пройдена. <a href="./users/<?php print $this->userCode; ?>">Ваша страница</a>.
	<a href="./users/<?php print $this->userCode; ?>/get/">Код ваших Хаброметров</a>.
</p>
<?php } else { ?>
<p>Введите ваш хабрологин в форму ниже, чтобы получить свой Хаброметр. E-mail вводить не обязательно, но если вы его введете,
	то он будет использоваться исключительно для уведомлений о работе системы.</p>
<?php if ($this->errors) { print "<ul style=\"color:red\">\r\n"; $e = $this->errors; foreach ($e as $error) print "\t<li>$error</li>\r\n"; print "</ul>\r\n";} ?>
<form action="./register/">
	<label for="user_code">Хабралогин *: </label>
	<input type="text" id="user_code" name="user_code" size="25" <?php if($this->userCode !== '')
		print 'value="' . htmlspecialchars($this->userCode) . '"'; ?>/> 
	<span class="help-block"><small>Поле обязательно для заполнения</small></span>
	<label for="user_email">E-mail: </label>
	<input type="text" id="user_email" name="user_email" size="25" <?php if($this->userEmail !== '')
		print 'value="' . htmlspecialchars($this->userEmail) . '"'; ?>/>
	<span class="help-block"><small>Можно не заполнять</small></span>
	<div><button type="submit" class="btn">Отправить</button></div>
</form>
<?php } ?>