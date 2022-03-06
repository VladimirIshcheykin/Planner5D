<?php if ($this->get('pages', 0) > 1): ?>
	<div class="pager">
		<?php for ($i = 1; $i <= $this->get('pages'); $i++):
			if ($i == $this->get('page')) : ?><span><?= $i ?></span>
			<?php else : ?>
				<a href="<?= $this->get('url') ?><?= ($i != 1)?'?page='.$i:''  ?>"><?= $i ?></a>
			<?php endif;
		endfor; ?>
	</div>
<?php endif; ?>