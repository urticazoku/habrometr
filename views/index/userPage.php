<?php echo $this->menuView(); ?>
<h1>Страница хабраюзера <a href="http://<?php print $this->userData['user_code']; ?>.habrahabr.ru/"><?php print $this->userData['user_code']; ?></a></h1>
<?php if (!$this->current) { ?>
<p>Данные по пользователю пока не запрашивались.</p>
<?php } else { ?>
<h2>Текущие хабразначения:</h2>
<ul>
	<li>Карма: <span class="label label-success"><?php print $this->current['karma_value']; ?></span></li>
	<li>Хабрасила: <span class="label label-info"><?php print $this->current['habraforce']; ?></span></li>
	<li>Позиция в рейтинге: <span class="label"><?php print $this->current['rate_position']; ?></span></li>
</ul>
<?php } ?>

<p><a href="./users/<?php print $this->userData['user_code']; ?>/get/">Коды Хаброметров пользователя</a></p>

<p><img src="./habrometr_425x120_<?php print $this->userData['user_code']; ?>.png" width="425" height="120" alt="Хаброметр <?php print $this->userData['user_code']; ?>" title="Хаброметр <?php print $this->userData['user_code']; ?>" /></p>

<h2>История</h2>
<p class="muted">История хабразначений <?php print $this->userData['user_code']; ?>  за последние 90 дней (отображается среднее значение показателей за сутки)</p>
<?php if (!$this->history) { ?>
<p>История пуста.</p>
<?php } else { ?>
<table class="table table-striped">
	<tr>
		<th>Карма</th>
		<th>Хабрасила</th>
		<th>Рейтинг</th>
		<th>Дата</th>
	</tr>
	<?php $h = $this->history; foreach($h as $row) { 
		print "\t<tr>\r\n\t\t<td>" . round($row['karma_value'], 2)
			. "</td>\r\n\t\t<td>" . round($row['habraforce'], 2)
			. "</td>\r\n\t\t<td>" . round($row['rate_position'], 2)
			. "</td>\r\n\t\t<td>{$row['date']}</td>\r\n\t<tr>\r\n";
	} ?>
</table>
<?php } ?>